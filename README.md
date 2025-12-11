# Laravel 12 Blog Application

A comprehensive blog application built with Laravel 12 featuring user authentication, roles, posts, comments, and an admin panel.

## Features

### 1. User Authentication
- User registration and login
- Social media login (Google, Facebook)
- Password reset functionality
- Email verification support

### 2. User Roles and Permissions
- **Admin**: Can manage all posts and users
- **Regular User**: Can create, edit, and delete their own posts

### 3. Post Management
- CRUD operations for posts
- Soft deletes for posts
- Posts have title, content, author, and timestamps
- Users can only edit/delete their own posts (admins can manage all)

### 4. Comments System
- Users can comment on posts
- CRUD operations for comments
- Users can only edit/delete their own comments (admins can manage all)

### 5. Admin Panel
- Dashboard with statistics (total posts, users, comments)
- User management (create, edit, delete users)
- Post management (view, edit, delete, restore soft-deleted posts)
- Protected by role-based middleware

### 6. Advanced Routing
- Route groups with middleware
- Route model binding
- Named routes throughout

### 7. Middleware
- **CheckRole**: Validates user roles for admin routes
- **LogActivity**: Logs user activities to database

### 8. Service Provider
- Custom BlogServiceProvider for application-specific logic

### 9. Performance Optimization
- Caching for posts and comments
- Eager loading to reduce database queries
- Query optimization techniques

### 10. Testing
- Unit tests for models (User, Post, Comment)
- Unit tests for middleware (CheckRole)
- Comprehensive test coverage

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM

### Setup Steps

1. **Clone/Navigate to the project**
```bash
cd blog-app
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Update .env file with your database credentials:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

5. **Configure Social Authentication (Optional)**
Add to your `.env` file:
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URL=http://your-app-url/auth/google/callback

FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret
FACEBOOK_REDIRECT_URL=http://your-app-url/auth/facebook/callback
```

6. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed
```

This will create:
- Admin user: `admin@blog.com` / `password`
- Regular user: `user@blog.com` / `password`

7. **Compile frontend assets**
```bash
npm run dev
# or for production
npm run build
```

8. **Start the development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Login Credentials

### Admin
- Email: `admin@blog.com`
- Password: `password`

### Regular User
- Email: `user@blog.com`
- Password: `password`

## Project Structure

```
blog-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Admin controllers
│   │   │   ├── Auth/         # Authentication controllers
│   │   │   ├── PostController.php
│   │   │   └── CommentController.php
│   │   └── Middleware/
│   │       ├── CheckRole.php
│   │       └── LogActivity.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Comment.php
│   │   └── ActivityLog.php
│   └── Providers/
│       └── BlogServiceProvider.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php      # Regular user layout
│       │   └── admin.blade.php    # Admin layout
│       ├── posts/
│       ├── comments/
│       └── admin/
└── tests/
    └── Unit/
```

## Testing

Run the test suite:
```bash
php artisan test
```

Or run specific tests:
```bash
php artisan test --filter UserTest
php artisan test --filter PostTest
php artisan test --filter CommentTest
php artisan test --filter CheckRoleMiddlewareTest
```

## Key Features Implementation

### Caching
Posts and comments are cached to improve performance:
- Post listing: 1 hour cache
- Individual posts: 30 minutes cache
- Admin statistics: 5 minutes cache

### Eager Loading
All queries use eager loading to prevent N+1 query problems:
- `Post::with(['user', 'comments.user'])`
- `User::withCount(['posts', 'comments'])`

### Soft Deletes
Posts use soft deletes, allowing restoration:
- Posts can be restored by admins
- Deleted posts are hidden from regular users

### Activity Logging
All user activities are logged to the `activity_logs` table, including:
- Action performed
- User ID
- IP address
- User agent
- Timestamp

## Routes

### Public Routes
- `GET /` - Redirects to posts index
- `GET /posts` - List all posts
- `GET /posts/{post}` - View a post

### Authenticated Routes
- `GET /home` - User dashboard
- `POST /posts` - Create a post
- `PUT /posts/{post}` - Update a post
- `DELETE /posts/{post}` - Delete a post
- `POST /posts/{post}/comments` - Add a comment
- `PUT /comments/{comment}` - Update a comment
- `DELETE /comments/{comment}` - Delete a comment

### Admin Routes (Protected by role middleware)
- `GET /admin` - Admin dashboard
- `GET /admin/posts` - Manage posts
- `GET /admin/users` - Manage users
- All CRUD operations for posts and users

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
