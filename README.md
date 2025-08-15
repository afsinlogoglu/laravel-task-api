# Laravel Task Management API

A task management system built with Laravel 12. It handles teams, task assignments, file uploads, and sends email notifications when things happen.

## What it does

- User accounts: People can sign up and log in using Laravel Sanctum
- Teams: Users can create teams and add other people to them
- Tasks: Full task management - create, read, update, delete, and track status
- Files: Users can attach files to tasks
- Emails: The system automatically sends emails when tasks get assigned or completed
- Permissions: Team members have different access levels
- API: Everything works through REST API endpoints
- Background jobs: Emails are sent in the background using queues

## What you need

- PHP 8.1 or newer
- Composer
- MySQL or PostgreSQL
- Redis (for handling email queues)

## Getting started

1. Get the code
   ```bash
   git clone <repository-url>
   cd laravel-task-api
   ```

2. Install PHP packages
   ```bash
   composer install
   ```

3. Set up environment
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database in .env
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_task_api
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   QUEUE_CONNECTION=redis
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   ```

5. Create database tables
   ```bash
   php artisan migrate
   ```

6. Add some test data
   ```bash
   php artisan db:seed --class=TaskManagementSeeder
   ```

7. Set up file storage
   ```bash
   php artisan storage:link
   ```

8. Start the queue worker for emails
   ```bash
   php artisan queue:work
   ```

9. Run the development server
   ```bash
   php artisan serve
   ```

## API endpoints

### User accounts

| Method | Endpoint | What it does |
|--------|----------|---------------|
| POST | `/api/register` | Create new user account |
| POST | `/api/login` | Log in existing user |
| GET | `/api/user` | Get current user info |
| POST | `/api/logout` | Log out user |

### Teams

| Method | Endpoint | What it does |
|--------|----------|---------------|
| POST | `/api/teams` | Create a new team |
| GET | `/api/teams` | Show user's teams |
| POST | `/api/teams/{id}/members` | Add someone to team |
| DELETE | `/api/teams/{id}/members/{userId}` | Remove someone from team |

### Tasks

| Method | Endpoint | What it does |
|--------|----------|---------------|
| POST | `/api/tasks` | Create a new task |
| GET | `/api/tasks` | List tasks (with filters) |
| PUT | `/api/tasks/{id}` | Update existing task |
| DELETE | `/api/tasks/{id}` | Delete a task |
| POST | `/api/tasks/{id}/files` | Upload file to task |

## How responses look

### When things go well
```json
{
    "success": true,
    "data": {...},
    "message": "Operation completed successfully"
}
```

### When something goes wrong
```json
{
    "success": false,
    "message": "Error description",
    "errors": {...}
}
```

## Authentication

The API uses Laravel Sanctum tokens. Put your token in the Authorization header like this:

```
Authorization: Bearer {your-token}
```

## Test accounts

After running the seeder, you can use these accounts to test:

- John Doe: john@example.com / password123
- Jane Smith: jane@example.com / password123  
- Mike Johnson: mike@example.com / password123

## Task statuses

- pending - Task exists but hasn't started yet
- in_progress - Someone is working on it
- completed - Task is done

## File uploads

- Max file size: 10MB
- File types: Any type allowed
- Files go to storage/app/public/task-files/
- Access them at /storage/task-files/{filename}

## Email notifications

- Task assigned: Email sent to user when they get a new task
- Task completed: Email sent to team owner when someone finishes a task
- Uses Redis queue so emails don't slow down the app

## Database structure

### Users
- id, name, email, password, email_verified_at, created_at, updated_at

### Teams
- id, name, description, owner_id, created_at, updated_at

### Team Members
- id, team_id, user_id, created_at, updated_at

### Tasks
- id, title, description, status, assigned_user_id, due_date, team_id, created_by, created_at, updated_at

### Task Files
- id, task_id, filename, original_name, file_path, created_at, updated_at

## Running tests

```bash
php artisan test
```

## Queue setup

Make sure Redis is running:
```bash
# Start Redis on macOS
brew services start redis

# Start the queue worker
php artisan queue:work
```

## Security stuff

- CSRF protection
- Input validation
- SQL injection prevention
- Safe file uploads
- Role-based access
- Token authentication

## Performance features

- Database relationships load efficiently
- Pagination for large lists
- Background processing for emails
- Optimized file storage

## How to contribute

1. Fork the repo
2. Make a feature branch
3. Commit your changes
4. Push to your branch
5. Make a Pull Request

## License

MIT license - feel free to use this code.

## Need help?

Open an issue in the repository if you have questions or run into problems.
