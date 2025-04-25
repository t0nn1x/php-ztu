# Authentication Documentation

This document describes the authentication system implemented in the MyHotel application using JWT (JSON Web Tokens).

## Endpoints

### Register a New User
```http
POST /api/register
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "your_password",
    "first_name": "John",
    "last_name": "Doe"
}
```

#### Required Fields
- `email`: User's email address (must be unique)
- `password`: User's password
- `first_name`: User's first name
- `last_name`: User's last name

#### Response
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "email": "user@example.com"
    }
}
```

### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "your_password"
}
```

#### Response
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGci..."
}
```

## Using the JWT Token

After successful login, you'll receive a JWT token. Include this token in the Authorization header for all protected API requests:

```http
GET /api/protected-endpoint
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGci...
```

## Security Rules

1. Registration and login endpoints are publicly accessible
2. All other `/api/*` endpoints require authentication
3. JWT tokens are valid for 1 hour by default
4. Tokens are stateless and must be included in every request

## Error Responses

### Invalid Credentials
```json
{
    "code": 401,
    "message": "Invalid credentials."
}
```

### Missing Token
```json
{
    "code": 401,
    "message": "JWT Token not found"
}
```

### Invalid Token
```json
{
    "code": 401,
    "message": "Invalid JWT Token"
}
```

### Missing Required Fields
```json
{
    "message": "Email, password, first_name, and last_name are required"
}
```

## Implementation Details

- Uses Symfony's Security component
- JWT authentication handled by LexikJWTAuthenticationBundle
- User passwords are hashed using Symfony's password hasher
- User entity implements `UserInterface` and `PasswordAuthenticatedUserInterface`
- Stateless authentication (no sessions) 
