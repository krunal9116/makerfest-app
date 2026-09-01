<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class MakerFestSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for clean wipe
        Schema::disableForeignKeyConstraints();

        // Wipe existing tables for clean seed
        DB::table('audit_logs')->truncate();
        DB::table('volunteer_tasks')->truncate();
        DB::table('evaluation_scores')->truncate();
        DB::table('evaluations')->truncate();
        DB::table('judge_assignments')->truncate();
        DB::table('judge_categories')->truncate();
        DB::table('rubric_criteria')->truncate();
        DB::table('rubric_templates')->truncate();
        DB::table('project_media')->truncate();
        DB::table('project_members')->truncate();
        DB::table('projects')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        DB::table('events')->truncate();

        Schema::enableForeignKeyConstraints();

        // 1. Create Active Event
        $eventId = DB::table('events')->insertGetId([
            'name' => 'Maker Fest Vadodara 2026',
            'year' => '2026',
            'registration_start' => '2026-08-01',
            'submission_deadline' => '2026-10-15',
            'screening_deadline' => '2026-10-25',
            'evaluation_start' => '2026-11-01',
            'evaluation_deadline' => '2026-11-10',
            'publication_date' => '2026-11-15',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create Users with Exact Requested Credentials (role@gmail.com / Role@123)
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $makerId = DB::table('users')->insertGetId([
            'name' => 'Maker User',
            'email' => 'maker@gmail.com',
            'password' => Hash::make('Maker@123'),
            'role' => 'maker',
            'mobile' => '+91 98765 43210',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $judge1 = DB::table('users')->insertGetId([
            'name' => 'Judge User',
            'email' => 'judge@gmail.com',
            'password' => Hash::make('Judge@123'),
            'role' => 'judge',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $volunteerId = DB::table('users')->insertGetId([
            'name' => 'Volunteer User',
            'email' => 'volunteer@gmail.com',
            'password' => Hash::make('Volunteer@123'),
            'role' => 'volunteer',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create Categories
        $cat1 = DB::table('categories')->insertGetId([
            'event_id' => $eventId,
            'name' => 'Sustainability & Green Tech',
            'description' => 'Renewable energy, waste recycling, clean water solutions',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cat2 = DB::table('categories')->insertGetId([
            'event_id' => $eventId,
            'name' => 'Healthcare & MedTech',
            'description' => 'Medical devices, healthcare monitoring, bio-engineering',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Create Standard Rubric Template & Criteria
        $rubricId = DB::table('rubric_templates')->insertGetId([
            'event_id' => $eventId,
            'name' => 'Standard Evaluation Rubric 2026',
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('rubric_criteria')->insert([
            [
                'rubric_template_id' => $rubricId,
                'name' => 'Technical Complexity',
                'description' => 'Evaluates the technical difficulty, engineering quality, and execution.',
                'max_score' => 10,
                'weightage' => 40,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rubric_template_id' => $rubricId,
                'name' => 'Innovation & Originality',
                'description' => 'Assesses how unique, creative, and novel the solution is.',
                'max_score' => 10,
                'weightage' => 30,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Create Seed Projects
        $p1 = DB::table('projects')->insertGetId([
            'event_id' => $eventId,
            'leader_id' => $makerId,
            'category_id' => $cat1,
            'project_code' => 'PRJ-2026-689',
            'title' => 'EcoTracker IoT Solar Monitor',
            'description' => 'An affordable solar-powered water quality and soil health tracker built for local farmers.',
            'registration_type' => 'individual',
            'status' => 'Submitted',
            'final_decision' => 'pending',
            'submitted_at' => now()->subDays(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Volunteer Tasks
        DB::table('volunteer_tasks')->insert([
            [
                'event_id' => $eventId,
                'volunteer_id' => $volunteerId,
                'title' => 'Prepare Venue Exhibition Floor Layout',
                'description' => 'Draft the floor plan for Main Convention Hall including power outlets.',
                'priority' => 'high',
                'status' => 'In Progress',
                'due_date' => '2026-10-24',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
