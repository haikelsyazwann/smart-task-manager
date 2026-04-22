<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ──
        $adminRole   = Role::findOrCreate('admin');
        $managerRole = Role::findOrCreate('manager');
        $memberRole  = Role::findOrCreate('member');

        // ── Users ──
        $admin = User::firstOrCreate(['email' => 'admin@taskmanager.test'], [
            'name'     => 'Alex Chen',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $manager1 = User::firstOrCreate(['email' => 'maria@taskmanager.test'], [
            'name'     => 'Maria Santos',
            'password' => Hash::make('password'),
        ]);
        $manager1->assignRole('manager');

        $manager2 = User::firstOrCreate(['email' => 'james@taskmanager.test'], [
            'name'     => 'James Wu',
            'password' => Hash::make('password'),
        ]);
        $manager2->assignRole('manager');

        $member1 = User::firstOrCreate(['email' => 'david@taskmanager.test'], [
            'name'     => 'David Kim',
            'password' => Hash::make('password'),
        ]);
        $member1->assignRole('member');

        $member2 = User::firstOrCreate(['email' => 'priya@taskmanager.test'], [
            'name'     => 'Priya Nair',
            'password' => Hash::make('password'),
        ]);
        $member2->assignRole('member');

        // ── Teams ──
        $frontendTeam = Team::firstOrCreate(['name' => 'Frontend'], [
            'owner_id'    => $manager1->id,
            'description' => 'UI/UX and client-side engineering',
        ]);
        $frontendTeam->members()->syncWithoutDetaching([
            $manager1->id => ['role' => 'manager'],
            $member2->id  => ['role' => 'member'],
            $admin->id    => ['role' => 'member'],
        ]);

        $backendTeam = Team::firstOrCreate(['name' => 'Backend'], [
            'owner_id'    => $manager2->id,
            'description' => 'API, database, and infrastructure',
        ]);
        $backendTeam->members()->syncWithoutDetaching([
            $manager2->id => ['role' => 'manager'],
            $member1->id  => ['role' => 'member'],
            $admin->id    => ['role' => 'member'],
        ]);

        $qaTeam = Team::firstOrCreate(['name' => 'QA & Testing'], [
            'owner_id'    => $admin->id,
            'description' => 'Quality assurance and test automation',
        ]);
        $qaTeam->members()->syncWithoutDetaching([
            $admin->id   => ['role' => 'manager'],
            $member1->id => ['role' => 'member'],
            $member2->id => ['role' => 'member'],
        ]);

        // ── Projects ──
        $p1 = Project::firstOrCreate(['name' => 'Alpha Launch'], [
            'team_id'     => $frontendTeam->id,
            'owner_id'    => $manager1->id,
            'description' => 'Q2 product launch — UI, auth & onboarding.',
            'color'       => '#8b5cf6',
        ]);

        $p2 = Project::firstOrCreate(['name' => 'API v3'], [
            'team_id'     => $backendTeam->id,
            'owner_id'    => $manager2->id,
            'description' => 'RESTful API redesign with versioning & rate limiting.',
            'color'       => '#f59e0b',
        ]);

        $p3 = Project::firstOrCreate(['name' => 'Design System'], [
            'team_id'     => $frontendTeam->id,
            'owner_id'    => $manager1->id,
            'description' => 'Reusable component library for all products.',
            'color'       => '#10b981',
        ]);

        // ── Tasks ──
        $tasks = [
            // Alpha Launch
            ['project' => $p1, 'title' => 'Set up CI/CD pipeline',          'desc' => 'Configure GitHub Actions for automated testing and deployment to staging.', 'status' => 'done',        'priority' => 'high',   'deadline' => now()->subDays(5), 'assignees' => [$admin, $member1],
             'subtasks' => [['Add lint job',true],['Add test runner',true],['Deploy step',true]],
             'comments' => [[$manager1,'Pipeline looks solid, merged!'],[$admin,'Thanks, deploying to prod next.']]],

            ['project' => $p1, 'title' => 'Design login & register screens', 'desc' => 'Create responsive auth screens following the new brand guidelines.', 'status' => 'done', 'priority' => 'medium', 'deadline' => now()->subDays(3), 'assignees' => [$manager1, $member2],
             'subtasks' => [['Wireframes',true],['Figma mockups',true],['Responsive breakpoints',true]],
             'comments' => [[$member2,'Designs approved by stakeholders.']]],

            ['project' => $p1, 'title' => 'Implement authentication endpoints', 'desc' => 'Build /login, /register, /logout, /refresh using Laravel Sanctum.', 'status' => 'in_progress', 'priority' => 'high', 'deadline' => now()->addDays(3), 'assignees' => [$member1],
             'subtasks' => [['POST /login',true],['POST /register',false],['Token refresh',false],['Logout endpoint',false]],
             'comments' => [[$member1,'Login done. Working on register now.']]],

            ['project' => $p1, 'title' => 'Set up Pusher broadcasting',      'desc' => 'Enable real-time updates for tasks and comments using Pusher channels.', 'status' => 'todo', 'priority' => 'medium', 'deadline' => now()->addDays(7), 'assignees' => [$admin],
             'subtasks' => [], 'comments' => []],

            ['project' => $p1, 'title' => 'Integrate OpenAI API',            'desc' => 'Connect to OpenAI for subtask generation, description improvement, and summarization.', 'status' => 'todo', 'priority' => 'low', 'deadline' => now()->addDays(10), 'assignees' => [$admin, $member1],
             'subtasks' => [], 'comments' => []],

            ['project' => $p1, 'title' => 'Write unit tests — models',       'desc' => 'Ensure 80%+ coverage on User, Task, Project, and Team models.', 'status' => 'in_progress', 'priority' => 'medium', 'deadline' => now()->addDays(5), 'assignees' => [$member2, $member1],
             'subtasks' => [['User model',true],['Task model',false],['Project model',false]],
             'comments' => [[$member2,'User model tests passing, 94% coverage.']]],

            // API v3
            ['project' => $p2, 'title' => 'Define OpenAPI schema',            'desc' => 'Document all v3 endpoints using OpenAPI 3.0 specification.', 'status' => 'done', 'priority' => 'high', 'deadline' => now()->subDays(8), 'assignees' => [$member1, $manager2],
             'subtasks' => [['User endpoints',true],['Task endpoints',true],['Auth endpoints',true]],
             'comments' => [[$manager2,'Schema approved by the team.']]],

            ['project' => $p2, 'title' => 'Rate limiting middleware',         'desc' => 'Implement per-user and per-IP rate limiting on all API endpoints.', 'status' => 'in_progress', 'priority' => 'high', 'deadline' => now()->addDays(4), 'assignees' => [$member1],
             'subtasks' => [['IP-based limiting',true],['User-based limiting',false],['429 error response',false]],
             'comments' => []],

            ['project' => $p2, 'title' => 'API versioning strategy',          'desc' => 'Implement URL-based versioning (/api/v3/) and document the migration guide.', 'status' => 'todo', 'priority' => 'medium', 'deadline' => now()->addDays(14), 'assignees' => [$admin, $member1],
             'subtasks' => [], 'comments' => []],

            ['project' => $p2, 'title' => 'Overdue: API auth documentation',  'desc' => 'Write developer documentation for the new auth flow.', 'status' => 'todo', 'priority' => 'high', 'deadline' => now()->subDays(2), 'assignees' => [$manager2],
             'subtasks' => [], 'comments' => []],

            // Design System
            ['project' => $p3, 'title' => 'Button component variants',        'desc' => 'Build primary, secondary, danger, and ghost button variants.', 'status' => 'done', 'priority' => 'medium', 'deadline' => now()->subDays(6), 'assignees' => [$member2],
             'subtasks' => [['Base styles',true],['Hover states',true],['Disabled state',true],['Dark mode',true]],
             'comments' => [[$manager1,'Looks great! Ship it.']]],

            ['project' => $p3, 'title' => 'Form input components',            'desc' => 'Build text input, select, checkbox, radio, and textarea components.', 'status' => 'in_progress', 'priority' => 'high', 'deadline' => now()->addDays(6), 'assignees' => [$member2, $manager1],
             'subtasks' => [['Text input',true],['Select',true],['Checkbox',false],['Radio',false],['Textarea',false]],
             'comments' => [[$member2,'Text and select done. Working on checkbox.'],[$manager1,'Can you add error state styling too?']]],

            ['project' => $p3, 'title' => 'Dark mode support',               'desc' => 'Implement system-aware and manual dark mode toggle across all components.', 'status' => 'todo', 'priority' => 'low', 'deadline' => now()->addDays(20), 'assignees' => [$member2],
             'subtasks' => [], 'comments' => []],
        ];

        foreach ($tasks as $t) {
            $task = Task::firstOrCreate(
                ['project_id' => $t['project']->id, 'title' => $t['title']],
                [
                    'creator_id'  => $t['assignees'][0]->id,
                    'description' => $t['desc'],
                    'status'      => $t['status'],
                    'priority'    => $t['priority'],
                    'deadline'    => $t['deadline'],
                    'position'    => rand(1, 100),
                ]
            );

            $task->assignees()->syncWithoutDetaching(collect($t['assignees'])->pluck('id')->toArray());

            foreach ($t['subtasks'] as [$stitle, $done]) {
                Subtask::firstOrCreate(
                    ['task_id' => $task->id, 'title' => $stitle],
                    ['completed' => $done, 'position' => 0]
                );
            }

            foreach ($t['comments'] as [$user, $body]) {
                $task->comments()->firstOrCreate(
                    ['user_id' => $user->id, 'body' => $body]
                );
            }

            // Log creation
            ActivityLog::firstOrCreate(
                ['action' => 'task.created', 'user_id' => $t['assignees'][0]->id],
                []
            );
        }

        // Project & team creation logs
        foreach ([$p1, $p2, $p3] as $p) {
            ActivityLog::create(['user_id' => $admin->id, 'action' => 'project.created', 'subject_type' => Project::class, 'subject_id' => $p->id]);
        }
        foreach ([$frontendTeam, $backendTeam, $qaTeam] as $t) {
            ActivityLog::create(['user_id' => $admin->id, 'action' => 'team.created', 'subject_type' => Team::class, 'subject_id' => $t->id]);
        }

        $this->command->info('✓ Seeded: 5 users, 3 teams, 3 projects, 13 tasks');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',   'admin@taskmanager.test',   'password'],
                ['Manager', 'maria@taskmanager.test',   'password'],
                ['Manager', 'james@taskmanager.test',   'password'],
                ['Member',  'david@taskmanager.test',   'password'],
                ['Member',  'priya@taskmanager.test',   'password'],
            ]
        );
    }
}
