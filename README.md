# Smart Task Manager

AI-powered team productivity system built with Laravel 12, Livewire, and Tailwind CSS.

## Tech Stack
- Laravel 12
- Livewire + Volt
- Tailwind CSS
- Alpine.js
- Spatie Laravel Permission
- Chart.js

## Features
- Kanban board with drag & drop
- Role-based access (Admin, Manager, Member)
- AI-powered task features (OpenAI)
- Dark mode with persistence
- Avatar picker
- Real-time updates with Pusher
- Activity log
- Dashboard charts

## Setup
1. Clone the repo
2. Run `composer install`
3. Run `npm install && npm run build`
4. Copy `.env.example` to `.env` and configure
5. Run `php artisan key:generate`
6. Run `php artisan migrate`
7. Run `php artisan db:seed`
8. Run `php artisan serve`

## Default Login
- Email: `admin@example.com`
- Password: `password`
