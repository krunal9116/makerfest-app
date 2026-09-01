<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class MakerFestController extends Controller
{
    public function setLocale($lang)
    {
        if (in_array($lang, ['en', 'gu'])) {
            Session::put('locale', $lang);
            App::setLocale($lang);
        }
        return redirect()->back();
    }

    public function index(Request $request)
    {
        $locale = Session::get('locale', 'en');
        App::setLocale($locale);

        $sessionRole = Session::get('user_role');
        
        $routePath = $request->path();
        if ($routePath === 'admin/dashboard' || $sessionRole === 'admin') {
            $role = 'admin';
        } elseif ($routePath === 'maker/dashboard' || $sessionRole === 'maker') {
            $role = 'maker';
        } elseif ($routePath === 'judge/dashboard' || $sessionRole === 'judge') {
            $role = 'judge';
        } elseif ($routePath === 'volunteer/dashboard' || $sessionRole === 'volunteer') {
            $role = 'volunteer';
        } else {
            $role = $sessionRole ?? 'maker';
        }

        $userId = Session::get('user_id');
        $currentUser = DB::table('users')->where('id', $userId)->first();
        $userName = $currentUser ? $currentUser->name : Session::get('user_name', 'User');

        $activeEvent = DB::table('events')->where('is_active', true)->first();
        $categories = DB::table('categories')->where('event_id', $activeEvent->id ?? 1)->get();

        $projectsQuery = DB::table('projects')
            ->leftJoin('categories', 'projects.category_id', '=', 'categories.id')
            ->leftJoin('users', 'projects.leader_id', '=', 'users.id')
            ->select('projects.*', 'categories.name as category_name', 'users.name as leader_name', 'users.email as leader_email');

        if ($role === 'judge') {
            $projectsQuery->where('projects.status', '!=', 'Draft');
        }

        $projects = $projectsQuery->get();

        foreach ($projects as $p) {
            $p->members = DB::table('project_members')->where('project_id', $p->id)->get();
            $p->media = DB::table('project_media')->where('project_id', $p->id)->get();
        }

        $volunteers = DB::table('users')->where('role', 'volunteer')->get();
        $judges = DB::table('users')->where('role', 'judge')->get();
        $allUsers = DB::table('users')->orderBy('id', 'desc')->get();
        $allEvents = DB::table('events')->orderBy('id', 'desc')->get();
        $volunteerTasks = DB::table('volunteer_tasks')->get();
        $judgeAssignments = DB::table('judge_assignments')
            ->leftJoin('projects', 'judge_assignments.project_id', '=', 'projects.id')
            ->leftJoin('users', 'judge_assignments.judge_id', '=', 'users.id')
            ->select('judge_assignments.*', 'projects.title as project_title', 'projects.project_code', 'users.name as judge_name')
            ->get();

        $assignedProjectIds = DB::table('judge_assignments')->pluck('project_id')->toArray();
        $unassignedProjects = collect($projects)->filter(function($project) use ($assignedProjectIds) {
            return !in_array($project->id, $assignedProjectIds);
        });

        $makerProjects = [];
        if ($role === 'maker' && $userId) {
            $userEmail = $currentUser ? $currentUser->email : null;

            $makerProjects = DB::table('projects')
                ->leftJoin('categories', 'projects.category_id', '=', 'categories.id')
                ->where(function ($query) use ($userId, $userEmail) {
                    $query->where('projects.leader_id', $userId)
                          ->orWhereIn('projects.id', function ($subQuery) use ($userId, $userEmail) {
                              $subQuery->select('project_id')
                                       ->from('project_members')
                                       ->where('user_id', $userId);
                              if ($userEmail) {
                                  $subQuery->orWhere('email', $userEmail);
                              }
                          });
                })
                ->select('projects.*', 'categories.name as category_name')
                ->orderBy('projects.id', 'desc')
                ->distinct()
                ->get();

            foreach ($makerProjects as $mp) {
                $mp->members = DB::table('project_members')->where('project_id', $mp->id)->get();
                $mp->media = DB::table('project_media')->where('project_id', $mp->id)->get();
                $leaderUser = DB::table('users')->where('id', $mp->leader_id)->first();
                $mp->leader_name = $leaderUser ? $leaderUser->name : 'Leader';
                $mp->leader_email = $leaderUser ? $leaderUser->email : '';
            }
        }

        $judgePendingProjects = [];
        $judgeEvaluatedProjects = [];
        if ($role === 'judge') {
            $currentUserId = Session::get('user_id');
            $allJudgeProjects = DB::table('judge_assignments')
                ->join('projects', 'judge_assignments.project_id', '=', 'projects.id')
                ->leftJoin('categories', 'projects.category_id', '=', 'categories.id')
                ->leftJoin('users', 'projects.leader_id', '=', 'users.id')
                ->where('judge_assignments.judge_id', $currentUserId)
                ->where('projects.status', '!=', 'Draft')
                ->select(
                    'projects.*', 
                    'categories.name as category_name', 
                    'users.name as leader_name',
                    'judge_assignments.id as assignment_id',
                    'judge_assignments.technical_score',
                    'judge_assignments.remarks',
                    'judge_assignments.status as assignment_status'
                )
                ->orderBy('projects.id', 'desc')
                ->get();
                
            foreach ($allJudgeProjects as $jp) {
                $jp->members = DB::table('project_members')->where('project_id', $jp->id)->get();
                $jp->media = DB::table('project_media')->where('project_id', $jp->id)->get();
                
                if ($jp->assignment_status === 'evaluated') {
                    $judgeEvaluatedProjects[] = $jp;
                } else {
                    $judgePendingProjects[] = $jp;
                }
            }
        }

        $makerProject = DB::table('projects')->where('leader_id', $userId)->orderBy('id', 'desc')->first();
        $makerMembers = [];
        if ($makerProject) {
            $makerMembers = DB::table('project_members')->where('project_id', $makerProject->id)->get();
        }

        $makersCount = DB::table('users')->where('role', 'maker')->count();
        $volunteersCount = DB::table('users')->where('role', 'volunteer')->count();
        $judgesCount = DB::table('users')->where('role', 'judge')->count();
        $pendingReviewsCount = DB::table('projects')->where('status', 'Submitted')->count();

        // Calculate real monthly project counts for the current year
        $monthlyProjectsData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyProjectsData[] = DB::table('projects')
                ->whereMonth('created_at', $m)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        return response()->view('makerfest', [
            'locale' => $locale,
            'role' => $role,
            'userName' => $userName,
            'currentUser' => $currentUser,
            'activeEvent' => $activeEvent,
            'allEvents' => $allEvents,
            'categories' => $categories,
            'projects' => $projects,
            'unassignedProjects' => $unassignedProjects,
            'volunteers' => $volunteers,
            'judges' => $judges,
            'allUsers' => $allUsers,
            'volunteerTasks' => $volunteerTasks,
            'judgeAssignments' => $judgeAssignments,
            'makerProjects' => $makerProjects,
            'judgePendingProjects' => $judgePendingProjects,
            'judgeEvaluatedProjects' => $judgeEvaluatedProjects,
            'makerProject' => $makerProject,
            'makerMembers' => $makerMembers,
            'makersCount' => $makersCount,
            'volunteersCount' => $volunteersCount,
            'judgesCount' => $judgesCount,
            'pendingReviewsCount' => $pendingReviewsCount,
            'monthlyProjectsData' => $monthlyProjectsData
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }

    public function saveProject(Request $request)
    {
        $action = $request->input('action', 'draft');
        $status = ($action === 'submit') ? 'Submitted' : 'Draft';

        $photoRule = ($action === 'submit') ? 'required' : 'nullable';
        $youtubeRule = ($action === 'submit') ? 'required' : 'nullable';

        $request->validate([
            'youtube_link_1' => [$youtubeRule, 'url', 'regex:/^(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/'],
            'youtube_link_2' => ['nullable', 'url', 'regex:/^(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/'],
            'project_photos.*' => [$photoRule, 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ], [
            'youtube_link_1.regex' => 'The first video link must be a valid YouTube URL.',
            'youtube_link_2.regex' => 'The second video link must be a valid YouTube URL.',
            'project_photos.*.mimes' => 'Project photos must be a file of type: jpeg, png, jpg.',
        ]);

        $leaderEmail = $request->input('leader_email');
        $memberEmails = $request->input('member_emails', []);

        // Check for duplicate emails among the team members and leader
        $allEmails = array_filter(array_merge([$leaderEmail], $memberEmails));
        if (count($allEmails) !== count(array_unique($allEmails))) {
            return redirect()->back()->withInput()->with('error', 'Each team member must have a unique email address. Duplicate emails are not allowed.');
        }

        $userId = Session::get('user_id');

        if (!$userId) {
            $leaderEmail = $request->input('leader_email');
            if ($leaderEmail) {
                $user = DB::table('users')->where('email', $leaderEmail)->first();
                if ($user) {
                    $userId = $user->id;
                }
            }
        }

        if (!$userId) {
            $firstUser = DB::table('users')->first();
            $userId = $firstUser ? $firstUser->id : 1;
        }

        $draftProjectId = $request->input('draft_project_id');

        // --- DFD Eligibility Check ---
        $activeEvent = DB::table('events')->where('is_active', true)->first();
        if ($activeEvent) {
            // Check if user already has a project
            $query = DB::table('projects')
                ->where('leader_id', $userId)
                ->whereIn('status', ['Draft', 'Submitted', 'Approved for Evaluation']);
            
            if ($draftProjectId) {
                $query->where('id', '!=', $draftProjectId);
            }
            
            $existingProject = $query->first();
            
            if ($existingProject) {
                return redirect()->back()->with('error', 'You already have an active project. You can only submit one project per event.');
            }

            // Check deadlines (only if columns exist from migration)
            if (isset($activeEvent->registration_close)) {
                $regClose = \Carbon\Carbon::parse($activeEvent->registration_close);
                if (now()->gt($regClose) && $status === 'Submitted') {
                    return redirect()->back()->with('error', 'Registration deadline has passed. Cannot submit a new project.');
                }
            }
        }
        // -----------------------------

        if ($draftProjectId) {
            DB::table('projects')->where('id', $draftProjectId)->where('leader_id', $userId)->update([
                'category_id' => $request->input('category_id'),
                'title' => $request->input('title', 'New Innovation Project'),
                'description' => $request->input('description', 'Project details...'),
                'registration_type' => $request->input('registration_type', 'Myself'),
                'school_organization_name' => $request->input('school_name'),
                'youtube_link_1' => $request->input('youtube_link_1'),
                'youtube_link_2' => $request->input('youtube_link_2'),
                'status' => $status,
                'submitted_at' => ($status === 'Submitted') ? now() : null,
                'updated_at' => now(),
            ]);
            $projectId = $draftProjectId;
            DB::table('project_members')->where('project_id', $projectId)->delete();
        } else {
            $projectId = DB::table('projects')->insertGetId([
                'event_id' => $activeEvent ? $activeEvent->id : 1,
                'leader_id' => $userId,
                'category_id' => $request->input('category_id'),
                'project_code' => 'TEMP', // Placeholder to be updated
                'title' => $request->input('title', 'New Innovation Project'),
                'description' => $request->input('description', 'Project details...'),
                'registration_type' => $request->input('registration_type', 'Myself'),
                'school_organization_name' => $request->input('school_name'),
                'youtube_link_1' => $request->input('youtube_link_1'),
                'youtube_link_2' => $request->input('youtube_link_2'),
                'status' => $status,
                'submitted_at' => ($status === 'Submitted') ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update with actual project ID and current year
            DB::table('projects')->where('id', $projectId)->update([
                'project_code' => 'PRJ-' . date('Y') . '-' . str_pad($projectId, 4, '0', STR_PAD_LEFT)
            ]);
        }

        // Insert Leader as Team Member 1
        DB::table('project_members')->insert([
            'project_id' => $projectId,
            'user_id' => $userId,
            'name' => $request->input('leader_name', Session::get('user_name')),
            'email' => $request->input('leader_email'),
            'mobile' => $request->input('leader_mobile', ''),
            'school_name' => $request->input('school_name', ''),
            'invite_status' => 'accepted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert additional team members
        $memberNames = $request->input('member_names', []);
        $memberEmails = $request->input('member_emails', []);
        $memberMobiles = $request->input('member_mobiles', []);
        $memberSchools = $request->input('member_schools', []);

        if (is_array($memberNames)) {
            foreach ($memberNames as $idx => $mName) {
                if (!empty($mName)) {
                    DB::table('project_members')->insert([
                        'project_id' => $projectId,
                        'name' => $mName,
                        'email' => $memberEmails[$idx] ?? null,
                        'mobile' => $memberMobiles[$idx] ?? '',
                        'school_name' => $memberSchools[$idx] ?? null,
                        'invite_status' => 'accepted',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Process Project Photos
        $photos = $request->file('project_photos');
        if (is_array($photos)) {
            foreach ($photos as $side => $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . $projectId . '_' . $side . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('project_media', $filename, 'public');
                    $existingMedia = DB::table('project_media')->where('project_id', $projectId)->where('side', $side)->first();
                    if ($existingMedia) {
                        DB::table('project_media')->where('id', $existingMedia->id)->update([
                            'file_path' => $path,
                            'updated_at' => now(),
                        ]);
                    } else {
                        DB::table('project_media')->insert([
                            'project_id' => $projectId,
                            'file_path' => $path,
                            'side' => $side,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // Send confirmation email to Leader and Team Members when project is submitted
        if ($status === 'Submitted') {
            try {
                $categoryName = DB::table('categories')->where('id', $request->input('category_id'))->value('name') ?? 'General';
                $leaderName = $request->input('leader_name', Session::get('user_name'));
                $leaderEmail = $request->input('leader_email');
                $leaderMobile = $request->input('leader_mobile', '');
                $projectTitle = $request->input('title', 'New Innovation Project');
                $projectDesc = $request->input('description', '');
                $schoolOrg = $request->input('school_name', 'N/A');
                $regType = ucfirst($request->input('registration_type', 'individual'));
                $projectCode = DB::table('projects')->where('id', $projectId)->value('project_code');

                // Collect team members details
                $teamDetailsList = [];
                $recipients = [];

                if ($leaderEmail) {
                    $recipients[] = $leaderEmail;
                }
                $teamDetailsList[] = "<li><strong>Leader:</strong> {$leaderName} ({$leaderEmail})</li>";

                if (is_array($memberNames)) {
                    foreach ($memberNames as $idx => $mName) {
                        if (!empty($mName)) {
                            $mEmail = $memberEmails[$idx] ?? 'N/A';
                            $teamDetailsList[] = "<li><strong>Member " . ($idx + 2) . ":</strong> {$mName} (" . ($mEmail !== 'N/A' ? $mEmail : 'No Email') . ")</li>";
                            if (!empty($memberEmails[$idx]) && filter_var($memberEmails[$idx], FILTER_VALIDATE_EMAIL)) {
                                $recipients[] = $memberEmails[$idx];
                            }
                        }
                    }
                }

                $recipients = array_unique($recipients);
                $membersHtml = implode('', $teamDetailsList);

                $emailContent = "
                <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                    <div style='max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                        <div style='text-align: center; margin-bottom: 20px;'>
                            <h2 style='color: #6b38fb; margin: 0;'>MakerFest Vadodara</h2>
                            <p style='color: #6b7280; font-size: 14px;'>Project Submission Confirmation</p>
                        </div>

                        <div style='background: #f0fdf4; border: 1px solid #bbf7d0; padding: 16px; border-radius: 8px; margin-bottom: 24px;'>
                            <h3 style='color: #166534; margin: 0 0 8px 0; font-size: 18px;'>🎉 Project Submitted Successfully!</h3>
                            <p style='color: #15803d; margin: 0; font-size: 14px;'>Your project application has been officially submitted for evaluation.</p>
                        </div>

                        <h4 style='color: #111827; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px; margin-top: 0;'>Project Information</h4>
                        <table style='width: 100%; font-size: 14px; color: #374151; margin-bottom: 20px; border-collapse: collapse;'>
                            <tr><td style='padding: 6px 0; font-weight: bold; width: 140px;'>Project Code:</td><td><span style='background: #eef2ff; color: #4f46e5; padding: 2px 8px; border-radius: 4px; font-weight: bold;'>{$projectCode}</span></td></tr>
                            <tr><td style='padding: 6px 0; font-weight: bold;'>Project Title:</td><td>{$projectTitle}</td></tr>
                            <tr><td style='padding: 6px 0; font-weight: bold;'>Category:</td><td>{$categoryName}</td></tr>
                            <tr><td style='padding: 6px 0; font-weight: bold;'>Registration Type:</td><td>{$regType}</td></tr>
                            " . ($schoolOrg !== 'N/A' ? "<tr><td style='padding: 6px 0; font-weight: bold;'>Institution/School:</td><td>{$schoolOrg}</td></tr>" : "") . "
                            <tr><td style='padding: 6px 0; font-weight: bold;'>Description:</td><td>{$projectDesc}</td></tr>
                        </table>

                        <h4 style='color: #111827; border-bottom: 2px solid #f3f4f6; padding-bottom: 8px;'>Team Information</h4>
                        <ul style='padding-left: 20px; font-size: 14px; color: #374151; line-height: 1.8;'>
                            {$membersHtml}
                        </ul>

                        <div style='margin-top: 28px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 12px;'>
                            <p>Thank you for participating in MakerFest Vadodara!</p>
                        </div>
                    </div>
                </div>";

                if (!empty($recipients)) {
                    \Illuminate\Support\Facades\Mail::html($emailContent, function ($message) use ($recipients, $projectTitle, $projectCode) {
                        $message->to($recipients)
                                ->subject("Project Submitted: {$projectTitle} [{$projectCode}] — MakerFest Vadodara");
                    });
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Project submission email notification error: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', ($status === 'Submitted') ? 'Project submitted successfully with team details!' : 'Draft saved successfully!');
    }

    public function updateProjectStatus(Request $request, $id)
    {
        $id = (int) $id;
        $request->validate([
            'status' => 'required|string|in:Approved for Evaluation,Not Admitted,Request Revision,Approved for Maker Fest,Not Approved for Maker Fest,Withdrawn,Submitted,Draft'
        ]);

        $status = $request->input('status');
        DB::table('projects')->where('id', $id)->update([
            'status' => $status,
            'updated_at' => now()
        ]);

        $project = DB::table('projects')->where('id', $id)->first();
        if ($project) {
            $members = DB::table('project_members')->where('project_id', $id)->pluck('email')->filter()->toArray();
            $leader = DB::table('users')->where('id', $project->leader_id)->first();
            $recipients = array_unique(array_filter(array_merge([$leader->email ?? null], $members)));

            if (!empty($recipients)) {
                try {
                    $statusTitle = ($status === 'Approved') ? 'Project Approved' : 'Project Status Update: ' . $status;
                    $emailBody = "
                    <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                        <div style='max-width: 550px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                            <h2 style='color: #6b38fb; margin-top: 0;'>MakerFest Vadodara Evaluation Update</h2>
                            <p style='font-size: 15px;'>Your project <strong>{$project->title}</strong> [{$project->project_code}] has been reviewed by our team.</p>
                            <div style='background: #f3f4f6; padding: 16px; border-radius: 8px; font-weight: bold; text-align: center; font-size: 18px; margin: 16px 0;'>
                                Status: {$status}
                            </div>
                            <p style='font-size: 14px; color: #6b7280;'>Log in to your MakerFest portal to view details and next steps.</p>
                        </div>
                    </div>";

                    \Illuminate\Support\Facades\Mail::html($emailBody, function ($message) use ($recipients, $project, $statusTitle) {
                        $message->to($recipients)->subject("{$statusTitle} — {$project->title} [{$project->project_code}]");
                    });
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Status update email error: " . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('success', 'Project status updated to ' . $status);
    }

    // Event Management Handler
    public function storeEvent(Request $request)
    {
        $title = $request->input('title');
        $location = $request->input('location', 'Vadodara');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isActive = $request->has('is_active') ? 1 : 0;

        if ($isActive) {
            DB::table('events')->update(['is_active' => 0]);
        }

        $endDateObj = \Carbon\Carbon::parse($endDate);

        DB::table('events')->insert([
            'name' => $title,
            'year' => date('Y', strtotime($startDate)),
            'registration_open' => $startDate,
            'submission_deadline' => $endDate,
            'screening_deadline' => $endDateObj->copy()->addDays(7)->toDateString(),
            'evaluation_start' => $endDateObj->copy()->addDays(8)->toDateString(),
            'evaluation_deadline' => $endDateObj->copy()->addDays(21)->toDateString(),
            'publication_date' => $endDateObj->copy()->addDays(30)->toDateString(),
            'registration_close' => $endDate,
            'revision_deadline' => $endDateObj->copy()->addDays(14)->toDateString(),
            'withdrawal_cutoff' => $endDate,
            'is_active' => $isActive,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Email all registered users about new event edition
        $allEmails = DB::table('users')->pluck('email')->filter()->toArray();
        if (!empty($allEmails)) {
            try {
                $eventHtml = "
                <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                    <div style='max-width: 550px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                        <h2 style='color: #6b38fb; margin-top: 0;'>New Event Announced: {$title}</h2>
                        <p style='font-size: 15px;'>We are excited to announce a new edition of MakerFest Vadodara!</p>
                        <ul style='font-size: 14px; color: #374151; line-height: 1.8;'>
                            <li><strong>Edition:</strong> {$title}</li>
                            <li><strong>Venue:</strong> {$location}</li>
                            <li><strong>Dates:</strong> {$startDate} to {$endDate}</li>
                        </ul>
                        <p style='font-size: 14px;'>Log in to submit your projects for this event edition!</p>
                    </div>
                </div>";

                \Illuminate\Support\Facades\Mail::html($eventHtml, function ($message) use ($allEmails, $title) {
                    $message->to($allEmails)->subject("New Event Announcement: {$title} — MakerFest");
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Event announcement mail error: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'New MakerFest event created successfully!');
    }

    // Assign Judge to Project Handler
    public function assignJudge(Request $request)
    {
        $projectId = $request->input('project_id');
        $judgeId = $request->input('judge_id');

        DB::table('judge_assignments')->insert([
            'project_id' => $projectId,
            'judge_id' => $judgeId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $judge = DB::table('users')->where('id', $judgeId)->first();
        $project = DB::table('projects')->where('id', $projectId)->first();

        if ($judge && $project) {
            try {
                $assignHtml = "
                <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                    <div style='max-width: 500px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                        <h2 style='color: #6b38fb; margin-top: 0;'>New Project Assignment</h2>
                        <p style='font-size: 15px;'>Hello <strong>{$judge->name}</strong>,</p>
                        <p style='font-size: 15px;'>A project has been assigned to you for evaluation:</p>
                        <div style='background: #f3f4f6; padding: 16px; border-radius: 8px; margin: 16px 0;'>
                            <strong style='color: #6b38fb;'>{$project->project_code}</strong>: {$project->title}
                        </div>
                        <p style='font-size: 14px; color: #6b7280;'>Log in to your Judge Portal to submit technical scores and evaluation remarks.</p>
                    </div>
                </div>";

                \Illuminate\Support\Facades\Mail::html($assignHtml, function ($message) use ($judge, $project) {
                    $message->to($judge->email)->subject("New Project Assigned for Evaluation: {$project->project_code}");
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Judge assignment mail error: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Judge assigned to project successfully!');
    }
    // Submit Judge Evaluation
    public function submitEvaluation(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|integer',
            'project_id' => 'required|integer',
            'technical_score' => 'required|integer|min:0|max:10',
            'remarks' => 'nullable|string'
        ]);

        $assignmentId = $request->input('assignment_id');
        $projectId = $request->input('project_id');
        $score = $request->input('technical_score');
        $remarks = $request->input('remarks');
        $judgeId = Session::get('user_id');

        // Update the assignment
        DB::table('judge_assignments')
            ->where('id', $assignmentId)
            ->where('judge_id', $judgeId)
            ->update([
                'technical_score' => $score,
                'remarks' => $remarks,
                'status' => 'evaluated',
                'updated_at' => now(),
            ]);

        // Update the project status
        DB::table('projects')
            ->where('id', $projectId)
            ->update([
                'status' => 'Evaluated',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Evaluation submitted successfully!');
    }
    // Assign Volunteer Task Handler
    public function assignTask(Request $request)
    {
        $volunteerId = $request->input('volunteer_id');
        $title = $request->input('title');
        $description = $request->input('description');

        DB::table('volunteer_tasks')->insert([
            'volunteer_id' => $volunteerId,
            'title' => $title,
            'description' => $description,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Task assigned to volunteer successfully!');
    }

    // Broadcast Email Handler
    public function broadcastMail(Request $request)
    {
        $targetRole = $request->input('target_role', 'all');
        $subject = $request->input('subject');
        $content = $request->input('content');

        $query = DB::table('users');
        if ($targetRole !== 'all') {
            $query->where('role', $targetRole);
        }
        $emails = $query->pluck('email')->toArray();

        if (!empty($emails)) {
            try {
                $htmlBody = "
                <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                    <div style='max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                        <h2 style='color: #6b38fb; margin-top: 0;'>MakerFest Vadodara Announcement</h2>
                        <div style='font-size: 15px; color: #374151; line-height: 1.6;'>
                            {$content}
                        </div>
                    </div>
                </div>";

                \Illuminate\Support\Facades\Mail::html($htmlBody, function ($message) use ($emails, $subject) {
                    $message->to($emails)->subject($subject);
                });

                return redirect()->back()->with('success', 'Broadcast email successfully dispatched to ' . count($emails) . ' recipients!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error dispatching broadcast email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'No users found matching the selected filter.');
    }

    // Admin Delete / Suspend User
    public function deleteUser($id)
    {
        $id = (int) $id;
        DB::table('users')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'User removed successfully!');
    }

    public function deleteDraft($id)
    {
        $userId = Session::get('user_id');
        $project = DB::table('projects')->where('id', $id)->first();
        
        if ($project && $project->leader_id == $userId && $project->status === 'Draft') {
            DB::table('projects')->where('id', $id)->delete();
        }
        
        return redirect()->back();
    }
}
