<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Events Table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('year');
            $table->date('registration_start');
            $table->date('submission_deadline');
            $table->date('screening_deadline');
            $table->date('evaluation_start');
            $table->date('evaluation_deadline');
            $table->date('publication_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Update Users Table to support MakerFest roles
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('maker'); // admin, maker, judge, volunteer
            $table->string('mobile')->nullable();
            $table->string('status')->default('active'); // active, invited, deactivated
        });

        // 2. Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Projects Table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('project_code')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('registration_type')->default('individual'); // individual or institutional
            
            // Mentor Fields (for institutional school/college projects)
            $table->string('mentor_name')->nullable();
            $table->string('mentor_email')->nullable();
            $table->string('school_organization_name')->nullable();
            $table->string('mentor_designation')->nullable();

            // Status fields (per locked rules)
            // project.status: Draft, Submitted, Revision_Requested, Approved, Evaluation_In_Progress, Evaluation_Complete, Not_Admitted, Withdrawn
            $table->string('status')->default('Draft');
            // project.final_decision: pending, approved_for_makerfest, not_approved_for_makerfest
            $table->string('final_decision')->default('pending');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('revision_deadline')->nullable();
            $table->text('revision_note')->nullable();
            $table->timestamps();
        });

        // 4. Project Team Members (up to 4 student/maker members max)
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->date('dob')->nullable();
            $table->string('class_standard')->nullable();
            $table->string('invite_status')->default('accepted'); // invited, accepted, declined
            $table->timestamps();
        });

        // 5. Project Media Attachments
        Schema::create('project_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('media_type'); // image, video_link, document
            $table->string('url');
            $table->timestamps();
        });

        // 6. Rubric Templates & Criteria Tables
        Schema::create('rubric_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        Schema::create('rubric_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubric_template_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_score')->default(10);
            $table->integer('weightage')->default(20); // percentage
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 7. Judge Assignments & Category Pools
        Schema::create('judge_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Judge
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('judge_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('assigned'); // assigned, completed
            $table->timestamps();
        });

        // 8. Judge Evaluations & Individual Scores
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rubric_template_id')->constrained()->onDelete('cascade');
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('suggestions')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->foreignId('rubric_criterion_id')->constrained('rubric_criteria')->onDelete('cascade');
            $table->integer('score');
            $table->timestamps();
        });

        // 9. Volunteer Tasks Kanban Board Table
        Schema::create('volunteer_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('volunteer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->string('status')->default('To Do'); // To Do, In Progress, Done
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        // 10. Audit Log & Notifications
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('volunteer_tasks');
        Schema::dropIfExists('evaluation_scores');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('judge_assignments');
        Schema::dropIfExists('judge_categories');
        Schema::dropIfExists('rubric_criteria');
        Schema::dropIfExists('rubric_templates');
        Schema::dropIfExists('project_media');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('events');
    }
};
