# Authentication Documentation

This document describes the authentication system implemented in the MyHotel application using JWT (JSON Web Tokens).

## Role System

The application implements a hierarchical role system with the following roles:

- `ROLE_ADMIN`: Highest level of access, can perform all operations
- `ROLE_MANAGER`: Management level access
- `ROLE_CLIENT`: Client level access
- `ROLE_USER`: Basic user level access

### Role Hierarchy

The roles follow a hierarchical structure where higher roles include permissions of lower roles:

1. ROLE_ADMIN
   - Has all permissions
   - Can manage users and system settings
   - Access to all administrative functions
2. ROLE_MANAGER
   - Management of hotel operations
   - Access to operational dashboards
3. ROLE_CLIENT
   - Access to booking functionality
   - Management of own reservations
4. ROLE_USER
   - Basic access level
   - View public information

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

## User Management API

The following endpoints are available for user management (requires ROLE_ADMIN):

### List Users
```http
GET /api/users
Authorization: Bearer {token}
```

### Get User Details
```http
GET /api/users/{id}
Authorization: Bearer {token}
```

### Create User
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
    "email": "newuser@example.com",
    "password": "password",
    "roles": ["ROLE_USER"],
    "first_name": "New",
    "last_name": "User"
}
```

### Update User
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "email": "updated@example.com",
    "roles": ["ROLE_MANAGER"]
}
```

### Patch User
```http
PATCH /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "roles": ["ROLE_CLIENT"]
}
```

### Delete User
```http
DELETE /api/users/{id}
Authorization: Bearer {token}
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
3. User management endpoints require `ROLE_ADMIN`
4. JWT tokens are valid for 1 hour by default
5. Tokens are stateless and must be included in every request
6. Role checks are enforced at the controller level using `#[IsGranted()]` attributes

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

### Insufficient Permissions
```json
{
    "code": 403,
    "message": "Access Denied."
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
- Role system implemented using `RoleEnum`
- Stateless authentication (no sessions)
- Role-based access control using Symfony's security attributes
