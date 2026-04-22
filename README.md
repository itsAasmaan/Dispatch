# Dispatch

A interview management platform built with Laravel, designed to streamline the interview process for companies and candidates.

## Features

- **Multi-role Authentication**: Support for Admin, Company, and Candidate roles
- **Company Management**: Create and manage company profiles
- **Interview Management**: Create, publish, and track interviews with comments
- **Question Bank**: Community-driven question repository with voting, bookmarking, and comments
- **Quiz System**: Generate and take AI-powered quizzes with real-time scoring
- **Learning Roadmaps**: Curated learning paths with progress tracking
- **Preparation Plans**: Custom study plans with daily tasks and progress monitoring
- **Salary Insights**: Community salary data and market insights
- **Notifications**: Real-time notification system for user interactions
- **User Profiles**: Public profiles with interview history and bookmarks
- **Admin Dashboard**: Comprehensive moderation and analytics tools
- **Comments & Discussions**: Thread-based comments on interviews and questions
- **Role-based Permissions**: Secure API endpoints with granular permissions
- **Modern Frontend**: Built with Vite and Tailwind CSS

## Tech Stack

- **Backend**: Laravel 13, PHP 8.3+
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel Permission
- **Database**: MySQL
- **Frontend**: Vite, Tailwind CSS
- **Development**: Laravel Sail (Docker)
- **Search**: Meilisearch
- **Caching**: Redis
- **Mail**: Mailpit (development)

## Quick Start

### Prerequisites

- Docker & Docker Compose
- PHP 8.3+
- Composer
- Node.js & npm

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd dispatch
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   ./vendor/bin/sail up -d
   ./vendor/bin/sail artisan migrate
   ./vendor/bin/sail artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

### Development

Start the development servers:
```bash
composer run dev
```

This will start:
- Laravel server (http://localhost)
- Vite dev server (http://localhost:5173)
- Queue worker
- Log monitoring

### Testing

Run the test suite:
```bash
./vendor/bin/sail artisan test
```

## API Documentation

### Authentication Endpoints

- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `GET /api/auth/me` - Get current user
- `POST /api/auth/logout` - Logout

### Companies

- `GET /api/companies` - List companies
- `GET /api/companies/{id}` - Get company details
- `POST /api/companies/{id}/follow` - Follow company
- `DELETE /api/companies/{id}/follow` - Unfollow company
- `POST /api/companies` - Create company (admin only)
- `PUT /api/companies/{id}` - Update company (admin only)
- `DELETE /api/companies/{id}` - Delete company (admin only)

### Interviews

- `GET /api/interviews` - List interviews
- `GET /api/interviews/{id}` - Get interview details
- `POST /api/interviews` - Create interview (candidates only)
- `PUT /api/interviews/{id}` - Update interview
- `DELETE /api/interviews/{id}` - Delete interview
- `POST /api/interviews/{id}/publish` - Publish interview
- `POST /api/interviews/{id}/upvote` - Upvote interview
- `POST /api/interviews/{id}/bookmark` - Bookmark interview

### Questions

- `GET /api/questions` - List questions
- `GET /api/questions/{id}` - Get question details
- `POST /api/questions` - Create question
- `POST /api/questions/{id}/upvote` - Upvote question
- `POST /api/questions/{id}/bookmark` - Bookmark question
- `PUT /api/questions/{id}/approve` - Approve question (admin only)

### Comments

- `GET /api/interviews/{id}/comments` - Get interview comments
- `GET /api/questions/{id}/comments` - Get question comments
- `POST /api/interviews/{id}/comments` - Comment on interview
- `POST /api/questions/{id}/comments` - Comment on question
- `DELETE /api/comments/{id}` - Delete comment
- `POST /api/comments/{id}/upvote` - Upvote comment
- `POST /api/comments/{id}/flag` - Flag inappropriate comment

### Quizzes

- `GET /api/quizzes` - List available quizzes
- `GET /api/quizzes/{id}` - Get quiz details
- `POST /api/quizzes/generate` - Generate AI quiz (authenticated)
- `GET /api/quizzes/my-attempts` - Get user's quiz attempts
- `POST /api/quizzes/{id}/start` - Start quiz attempt
- `POST /api/attempts/{id}/answer` - Submit quiz answer
- `POST /api/attempts/{id}/complete` - Complete quiz attempt
- `GET /api/attempts/{id}/result` - Get quiz result

### Roadmaps (Learning Paths)

- `GET /api/topics` - Get all topics
- `GET /api/roadmaps` - List learning roadmaps
- `GET /api/roadmaps/{id}` - Get roadmap details
- `POST /api/roadmaps/{id}/enroll` - Enroll in roadmap (candidates only)
- `DELETE /api/roadmaps/{id}/enroll` - Unenroll from roadmap
- `PUT /api/roadmaps/{id}/topics/{topic}/progress` - Update topic progress
- `GET /api/roadmaps/my-progress` - Get user's learning progress

### Preparation Plans

- `GET /api/preparation-plans` - List user's preparation plans (candidates only)
- `POST /api/preparation-plans` - Create preparation plan
- `GET /api/preparation-plans/{id}` - Get plan details
- `DELETE /api/preparation-plans/{id}` - Delete plan
- `GET /api/preparation-plans/{id}/today` - Get today's tasks
- `POST /api/preparation-plans/tasks/{id}/complete` - Mark task complete
- `POST /api/preparation-plans/tasks/{id}/skip` - Skip task

### Notifications

- `GET /api/notifications` - Get user notifications
- `GET /api/notifications/unread-count` - Get unread count
- `POST /api/notifications/read-all` - Mark all as read
- `POST /api/notifications/{id}/read` - Mark notification as read

### Salary Insights

- `GET /api/salary-insights` - Get salary insights data
- `GET /api/salary-insights/stats` - Get salary statistics
- `POST /api/salary-insights` - Submit salary insight (authenticated)

### User Profile

- `GET /api/profile/{username}` - Get public user profile
- `PUT /api/profile` - Update own profile (authenticated)
- `GET /api/profile/my-interviews` - Get user's interviews (authenticated)
- `GET /api/profile/my-bookmarks` - Get user's bookmarks (authenticated)

### Admin Dashboard

- `GET /api/admin/stats` - Get platform statistics (admin only)
- `GET /api/admin/users` - List all users (admin only)
- `POST /api/admin/users/{id}/toggle-active` - Toggle user active status (admin only)
- `GET /api/admin/comments/flagged` - Get flagged comments (admin only)
- `POST /api/admin/comments/{id}/dismiss-flag` - Dismiss flag (admin only)
- `DELETE /api/admin/comments/{id}` - Delete comment (admin only)
- `POST /api/admin/companies/{id}/verify` - Verify company (admin only)
- `GET /api/admin/questions/pending` - Get pending questions (admin only)

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # Admin dashboard & moderation
│   │   ├── Auth/               # Authentication
│   │   ├── Comment/            # Comments on interviews & questions
│   │   ├── Company/            # Company management
│   │   ├── Interview/          # Interview management
│   │   ├── NotificationController.php
│   │   ├── PreparationPlan/    # Study plan management
│   │   ├── Profile/            # User profiles
│   │   ├── Question/           # Question management
│   │   ├── Quiz/               # Quiz system
│   │   ├── Roadmap/            # Learning roadmaps
│   │   └── SalaryInsight/      # Salary data
│   ├── Middleware/
│   ├── Requests/               # Form request validation
│   ├── Models/                 # Eloquent models
│   ├── Observers/              # Model observers
│   ├── Providers/              # Service providers
│   └── Traits/                 # Reusable traits

database/
├── migrations/                 # Database schema
├── seeders/                    # Database seeders
└── factories/                  # Model factories

routes/
└── api.php                     # All API routes
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.