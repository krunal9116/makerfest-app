<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showAdminLogin()
    {
        return view('auth.admin_login');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = DB::table('users')->where('email', $email)->where('role', 'admin')->first();

        if ($user && Hash::check($password, $user->password)) {
            Session::put('user_id', $user->id);
            Session::put('user_role', 'admin');
            Session::put('user_name', $user->name);
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid Admin Credentials.');
    }

    public function showParticipantLogin()
    {
        return view('auth.participant_login');
    }

    public function loginParticipant(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = DB::table('users')->where('email', $email)->whereIn('role', ['maker', 'judge', 'volunteer'])->first();

        if ($user && Hash::check($password, $user->password)) {
            Session::put('user_id', $user->id);
            Session::put('user_role', $user->role);
            Session::put('user_name', $user->name);

            if ($user->role === 'maker') {
                return redirect()->route('maker.dashboard');
            } elseif ($user->role === 'judge') {
                return redirect()->route('judge.dashboard');
            } elseif ($user->role === 'volunteer') {
                return redirect()->route('volunteer.dashboard');
            }
        }

        return redirect()->back()->with('error', 'Invalid email or password.');
    }

    // Show Maker Public Registration Form
    public function showMakerRegister()
    {
        return view('auth.maker_register');
    }

    // Send OTP for Registration
    public function sendRegistrationOtp(Request $request)
    {
        $email = $request->input('email');
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email address is required.'], 400);
        }

        $existing = DB::table('users')->where('email', $email)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'This email address is already registered. Please sign in.'], 400);
        }

        $otp = sprintf('%06d', mt_rand(100000, 999999));
        Session::put('registration_otp_' . $email, [
            'code' => $otp,
            'expires_at' => now()->addMinutes(5)
        ]);

        try {
            $htmlBody = "
            <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                <div style='max-width: 500px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                    <h2 style='color: #6b38fb; margin-top: 0;'>MakerFest Vadodara</h2>
                    <p style='font-size: 15px;'>Hello,</p>
                    <p style='font-size: 15px;'>Your email verification code for MakerFest registration is:</p>
                    <div style='font-size: 32px; font-weight: 700; color: #6b38fb; letter-spacing: 4px; padding: 16px 0; text-align: center; background: #f3e8ff; border-radius: 8px; margin: 16px 0;'>{$otp}</div>
                    <p style='font-size: 13px; color: #6b7280;'>This OTP code will expire in 5 minutes. If you did not request this code, please ignore this email.</p>
                </div>
            </div>";

            \Illuminate\Support\Facades\Mail::html($htmlBody, function ($message) use ($email) {
                $message->to($email)->subject('MakerFest Registration Verification OTP');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Mail error: " . $e->getMessage());
        }

        \Illuminate\Support\Facades\Log::info("MAKERFEST REGISTRATION OTP SENT TO {$email}: OTP is {$otp}");

        return response()->json([
            'success' => true, 
            'message' => 'Verification OTP has been sent to ' . $email
        ]);
    }

    // Process Maker Registration with OTP Verification (No Mobile required)
    public function registerMaker(Request $request)
    {
        $email = $request->input('email');
        $userOtp = $request->input('otp');
        $name = $request->input('name');

        $sessionOtpData = Session::get('registration_otp_' . $email);

        if (!$sessionOtpData) {
            return redirect()->back()->withInput()->with('error', 'No active OTP request found or OTP expired. Please request a new OTP.');
        }

        // Check if OTP is expired (5 minutes)
        if (now()->gt(\Carbon\Carbon::parse($sessionOtpData['expires_at']))) {
            Session::forget('registration_otp_' . $email);
            return redirect()->back()->withInput()->with('error', 'OTP has expired. Please click Resend OTP.');
        }

        $attemptsKey = 'otp_attempts_' . $email;
        $attempts = Session::get($attemptsKey, 0);

        if ($sessionOtpData['code'] !== $userOtp) {
            $attempts++;
            Session::put($attemptsKey, $attempts);
            $remaining = 4 - $attempts;
            
            if ($remaining <= 0) {
                Session::forget('registration_otp_' . $email);
                Session::forget($attemptsKey);
                return redirect()->back()->withInput()->with('error', 'Maximum invalid OTP attempts reached (4/4). Please click Resend OTP for a new code.');
            }
            
            return redirect()->back()->withInput()->with('error', 'Invalid OTP Code entered. ' . $remaining . ' attempt(s) remaining.');
        }

        Session::forget($attemptsKey);

        // Check if email already exists
        $existing = DB::table('users')->where('email', $email)->first();
        if ($existing) {
            Session::forget('registration_otp_' . $email);
            return redirect()->back()->with('error', 'This email address is already registered. Please sign in.');
        }

        // Insert new Maker user in MySQL database (Only active, verified users stored in DB)
        $userId = DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($request->input('password')),
            'role' => 'maker',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Clean up OTP session data immediately after successful verification
        Session::forget('registration_otp_' . $email);

        // Send Registration Welcome Confirmation Email
        try {
            $welcomeBody = "
            <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                <div style='max-width: 500px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                    <h2 style='color: #6b38fb; margin-top: 0;'>Welcome to MakerFest Vadodara!</h2>
                    <p style='font-size: 15px;'>Dear <strong>{$name}</strong>,</p>
                    <p style='font-size: 15px;'>Your Maker account has been successfully created and verified.</p>
                    <p style='font-size: 15px;'>You can now log in to your dashboard, submit your innovative projects, and track evaluation updates.</p>
                    <div style='text-align: center; margin-top: 24px;'>
                        <a href='" . route('login') . "' style='background: #6b38fb; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;'>Go to Dashboard</a>
                    </div>
                </div>
            </div>";

            \Illuminate\Support\Facades\Mail::html($welcomeBody, function ($message) use ($email) {
                $message->to($email)->subject('Welcome to MakerFest Vadodara — Account Registered Successfully');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Welcome mail error: " . $e->getMessage());
        }

        // Auto-login newly registered Maker
        Session::put('user_id', $userId);
        Session::put('user_role', 'maker');
        Session::put('user_name', $name);

        return redirect()->route('maker.dashboard')->with('success', 'Registration successful! Welcome to MakerFest.');
    }

    // Forgot Password Views & Handlers
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    public function sendForgotOtp(Request $request)
    {
        $email = $request->input('email');
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No account found with this email address.'], 404);
        }

        // Security Check: If a user is currently logged in, prevent them from changing another user's password
        if (Session::has('user_email') && Session::get('user_email') !== $email) {
            return response()->json(['success' => false, 'message' => 'You can only change the password for your currently logged-in account.'], 403);
        }

        $otp = sprintf('%06d', mt_rand(100000, 999999));
        Session::put('forgot_otp_' . $email, [
            'code' => $otp,
            'expires_at' => now()->addMinutes(5)
        ]);

        try {
            $forgotHtml = "
            <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                <div style='max-width: 500px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                    <h2 style='color: #6b38fb; margin-top: 0;'>MakerFest Password Reset</h2>
                    <p style='font-size: 15px;'>Hello,</p>
                    <p style='font-size: 15px;'>You requested to reset your password. Use the following 6-digit OTP code:</p>
                    <div style='font-size: 32px; font-weight: 700; color: #6b38fb; letter-spacing: 4px; padding: 16px 0; text-align: center; background: #f3e8ff; border-radius: 8px; margin: 16px 0;'>{$otp}</div>
                    <p style='font-size: 13px; color: #6b7280;'>This code expires in 5 minutes. If you did not request a password reset, please ignore this email.</p>
                </div>
            </div>";

            \Illuminate\Support\Facades\Mail::html($forgotHtml, function ($message) use ($email) {
                $message->to($email)->subject('MakerFest Password Reset Verification OTP');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Mail error: " . $e->getMessage());
        }

        \Illuminate\Support\Facades\Log::info("MAKERFEST FORGOT PASSWORD OTP SENT TO {$email}: OTP is {$otp}");

        return response()->json([
            'success' => true,
            'message' => 'Password reset OTP has been sent to ' . $email
        ]);
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $email = $request->input('email');
        $userOtp = $request->input('otp');
        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('new_password_confirmation');

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New password and confirm password do not match.');
        }

        $sessionOtpData = Session::get('forgot_otp_' . $email);
        if (!$sessionOtpData) {
            return redirect()->back()->with('error', 'No active OTP request found or OTP expired. Please request a new OTP.');
        }

        if (now()->gt(\Carbon\Carbon::parse($sessionOtpData['expires_at']))) {
            Session::forget('forgot_otp_' . $email);
            return redirect()->back()->with('error', 'OTP has expired. Please request a new OTP code.');
        }

        if ($sessionOtpData['code'] !== $userOtp) {
            return redirect()->back()->with('error', 'Invalid OTP Code entered.');
        }

        DB::table('users')->where('email', $email)->update([
            'password' => Hash::make($newPassword),
            'updated_at' => now(),
        ]);

        // Delete OTP session data immediately after successful password reset
        Session::forget('forgot_otp_' . $email);

        if (Session::has('user_id')) {
            return redirect()->route('profile')->with('success', 'Password reset successfully!');
        }

        return redirect()->route('login')->with('success', 'Password reset successfully! Please sign in with your new password.');
    }

    // Admin endpoint to create Judge & Volunteer accounts
    public function createStaffUser(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $email = $request->input('email');
        $role = $request->input('role');

        if (!in_array($role, ['judge', 'volunteer'])) {
            return redirect()->back()->with('error', 'Invalid role selected.');
        }

        $existing = DB::table('users')->where('email', $email)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'User with this email already exists.');
        }

        // Auto-generate random 8-character password (e.g. MKR82x9A)
        $randomPassword = \Illuminate\Support\Str::random(8);

        DB::table('users')->insert([
            'name' => $request->input('name'),
            'email' => $email,
            'password' => Hash::make($randomPassword),
            'role' => $role,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Dispatch credentials email
        try {
            $roleTitle = ucfirst($role);
            $loginUrl = route('login');
            $staffName = $request->input('name');

            $mailBody = "
            <div style='font-family: Arial, sans-serif; padding: 24px; background: #f8f9fa; color: #111827;'>
                <div style='max-width: 500px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid #e5e7eb;'>
                    <h2 style='color: #6b38fb; margin-top: 0;'>MakerFest Vadodara</h2>
                    <h3 style='color: #111827;'>Your {$roleTitle} Account Credentials</h3>
                    <p style='font-size: 14px;'>Dear <strong>{$staffName}</strong>,</p>
                    <p style='font-size: 14px;'>An official <strong>{$roleTitle}</strong> account has been created for you by the Admin.</p>
                    
                    <div style='background: #f0fdf4; border: 1px solid #bbf7d0; padding: 16px; border-radius: 8px; margin: 20px 0;'>
                        <p style='margin: 0 0 6px 0; font-size: 14px;'><strong>Email:</strong> {$email}</p>
                        <p style='margin: 0; font-size: 14px;'><strong>Temporary Password:</strong> <code style='background: #e0e7ff; color: #4338ca; padding: 3px 8px; border-radius: 4px; font-weight: bold;'>{$randomPassword}</code></p>
                    </div>

                    <div style='text-align: center; margin-top: 24px;'>
                        <a href='{$loginUrl}' style='background: #6b38fb; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;'>Log In to Dashboard</a>
                    </div>
                </div>
            </div>";

            \Illuminate\Support\Facades\Mail::html($mailBody, function ($message) use ($email, $roleTitle) {
                $message->to($email)->subject("MakerFest Vadodara — Your {$roleTitle} Account Credentials");
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Staff creation mail error: " . $e->getMessage());
        }

        return redirect()->back()->with('success', ucfirst($role) . " account created! Login credentials emailed to {$email}.");
    }

    // Show Dedicated Profile Page
    public function showProfile()
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $currentUser = DB::table('users')->where('id', $userId)->first();
        return response()->view('profile', [
            'currentUser' => $currentUser,
            'userName' => $currentUser->name ?? Session::get('user_name'),
            'role' => $currentUser->role ?? Session::get('user_role')
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }

    // Edit Profile Endpoint
    public function updateProfile(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        DB::table('users')->where('id', $userId)->update([
            'name' => $request->input('name'),
            'updated_at' => now(),
        ]);

        Session::put('user_name', $request->input('name'));
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    // Update Password Endpoint
    public function updatePassword(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) return redirect()->route('login');

        $user = DB::table('users')->where('id', $userId)->first();
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('new_password_confirmation');

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New password and confirm password do not match.');
        }

        DB::table('users')->where('id', $userId)->update([
            'password' => Hash::make($newPassword),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}

