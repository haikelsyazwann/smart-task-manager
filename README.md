# Smart Task Manager (Laravel + Livewire)

AI-powered team productivity platform inspired by Jira, built with Laravel 12, Livewire 3, Tailwind CSS, MySQL, Pusher, and OpenAI.

## Implemented Architecture

- **Auth & Roles:** Laravel Breeze (Livewire stack) + Spatie permission roles (`admin`, `manager`, `member`).
- **Team hierarchy:** `teams` with owner + `team_user` pivot role.
- **Project hierarchy:** projects belong to teams.
- **Task system:** kanban-style statuses (`todo`, `in_progress`, `done`), priority, deadline, assignees, subtasks.
- **Polymorphic entities:** comments (`commentable`) and activity logs (`subject`).
- **Files:** task attachments on local `public` disk (Cloudinary can be swapped later).
- **Realtime:** broadcast events for task updates and comments (Pusher/Echo ready).
- **AI service:** subtask generation, description improvement, and comment summarization.

## Core Folder Structure

- `app/Livewire/TaskBoard.php` - main kanban board component.
- `app/Livewire/TaskModal.php` - task CRUD modal + AI actions + attachments.
- `app/Livewire/TaskComments.php` - real-time comment flow + summary.
- `app/Livewire/DashboardStats.php` - dashboard counters and distribution.
- `app/Services/OpenAIProductivityService.php` - AI integration layer.
- `app/Services/ActivityLogService.php` - centralized action logging.
- `app/Events/TaskUpdated.php` and `app/Events/CommentCreated.php` - realtime events.
- `app/Models/*` - full Eloquent relationships.
- `database/migrations/*` - normalized relational schema.
- `routes/channels.php` - private broadcast channel authorization.

## Database Schema

Main tables and relationships:

- `users`
- `teams` (`owner_id`)
- `team_user` (`team_id`, `user_id`, `role`)
- `projects` (`team_id`, `owner_id`)
- `tasks` (`project_id`, `creator_id`, status, priority, deadline)
- `task_user` (`task_id`, `user_id`)
- `subtasks` (`task_id`)
- `comments` polymorphic (`commentable_type`, `commentable_id`, `user_id`)
- `attachments` (`task_id`, `uploaded_by`)
- `activity_logs` polymorphic (`subject_type`, `subject_id`, `user_id`)
- Spatie permission tables (`roles`, `permissions`, etc.)

## AI Methods

`app/Services/OpenAIProductivityService.php` includes:

- `generateSubtasks(string $title, ?string $description): array`
- `improveDescription(string $description): string`
- `summarizeComments(string $comments): string`

## Realtime Events

- `TaskUpdated` broadcasts to `private-project.{projectId}` equivalent channel: `project.{projectId}`
- `CommentCreated` broadcasts to `task.{taskId}`
- Channel rules are in `routes/channels.php`
- Frontend Echo setup is in `resources/js/bootstrap.js`

## Setup Guide

1. Copy env and update keys:
   - `cp .env.example .env`
2. Set credentials in `.env`:
   - MySQL connection
   - `OPENAI_API_KEY`
   - Pusher credentials (`PUSHER_*`)
3. Install dependencies:
   - `composer install`
   - `npm install`
4. Generate key:
   - `php artisan key:generate`
5. Run database:
   - `php artisan migrate --seed`
6. Link storage:
   - `php artisan storage:link`
7. Build frontend:
   - `npm run build` (or `npm run dev`)
8. Start app:
   - `php artisan serve`
9. Run queue worker for notifications/events:
   - `php artisan queue:work`

## Optional Scheduled Reminder

Manual reminder command:

- `php artisan stm:send-deadline-reminders`

Add to scheduler/cron in production for periodic deadline emails.
