# Dispatch

A comprehensive interview management platform built with Laravel, designed to streamline the interview process for companies and candidates.

## Features

- **Multi-role Authentication**: Support for Admin, Company, and Candidate roles
- **Company Management**: Create and manage company profiles
- **Interview Management**: Create, publish, and track interviews
- **Question Bank**: Community-driven question repository with voting and bookmarking
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
- `POST /api/companies/{id}/follow` - Follow/unfollow company

### Interviews

- `GET /api/interviews` - List interviews
- `GET /api/interviews/{id}` - Get interview details
- `POST /api/interviews` - Create interview (candidates only)
- `PUT /api/interviews/{id}` - Update interview
- `DELETE /api/interviews/{id}` - Delete interview

### Questions

- `GET /api/questions` - List questions
- `GET /api/questions/{id}` - Get question details
- `POST /api/questions` - Create question
- `POST /api/questions/{id}/upvote` - Upvote question
- `POST /api/questions/{id}/bookmark` - Bookmark question

## Project Structure

```
app/
├── Http/Controllers/     # API Controllers
├── Models/              # Eloquent Models
├── Providers/           # Service Providers
└── Traits/              # Reusable traits

database/
├── migrations/          # Database migrations
├── seeders/            # Database seeders
└── factories/          # Model factories

routes/
└── api.php             # API routes

resources/              # Frontend assets
├── css/
└── js/
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.