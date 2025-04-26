# MyHotel - Hotel Management System

A modern, secure, and feature-rich hotel management system built with Symfony framework. This application provides a comprehensive solution for hotel management with RESTful API support and robust authentication.

## 🌟 Features

- **Secure Authentication**
  - JWT-based authentication system
  - Role-based access control
  - Secure token management
  - See [Authentication Documentation](doc/auth/authentication.md) for details

- **API Documentation**
  - Swagger/OpenAPI integration
  - Interactive API documentation using Swagger UI
  - Comprehensive API endpoints documentation
  - See [Swagger Documentation](doc/swagger-setup.md) for details

- **Database Management**
  - Doctrine ORM integration
  - Database migrations support
  - Efficient data persistence
  - See [Database Documentation](doc/db/) for details

- **Scheduled Tasks**
  - Cron job integration
  - Automated task scheduling
  - Background process management
  - See [Cron Documentation](doc/cron/cleanup-reservations.md) for details

## 🚀 Technology Stack

- PHP 8.x
- Symfony Framework
- Doctrine ORM
- JWT Authentication
- Nginx
- Docker

## 📦 Installation

1. Clone the repository
2. Run the Docker setup script:
   ```bash
   ./setup-docker.sh
   ```
3. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

## 🛠️ Development Setup

1. Copy the environment file:
   ```bash
   cp .env.dev .env
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Run database migrations:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

## 📚 Documentation

Detailed documentation is available in the `doc/` directory:

- [Authentication Documentation](doc/auth/)
- [Database Schema and Management](doc/db/)
- [Cron Jobs and Scheduled Tasks](doc/cron/)

## 🐳 Docker Support

The project includes a complete Docker setup with:
- Nginx web server
- PHP-FPM
- Development and production configurations
- Easy deployment process

## 🔒 Security

- JWT-based authentication
- CSRF protection
- Secure password hashing
- Rate limiting
- Input validation
