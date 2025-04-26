# Swagger Documentation Setup

## Overview
This document describes the Swagger/OpenAPI setup for the Hotel Management API. The API documentation is defined in `swagger.yaml` at the root of the project.

## Technical Setup

### Required Bundles
Make sure these bundles are enabled in `config/bundles.php`:
```php
return [
    // ... other bundles
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
];
```

### Controller Setup
1. **ApiDocController** (`src/Controller/ApiDocController.php`):
```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class ApiDocController extends AbstractController
{
    #[Route('/api/doc', name: 'api_doc')]
    public function index(): Response
    {
        return $this->render('swagger-ui.html.twig', [
            'title' => 'Hotel Management API Documentation'
        ]);
    }

    #[Route('/api/doc.json', name: 'api_doc_json')]
    public function getOpenApiSpec(): JsonResponse
    {
        $openApiSpec = Yaml::parseFile($this->getParameter('kernel.project_dir') . '/swagger.yaml');
        return $this->json($openApiSpec);
    }
}
```

### Template Setup
Create Swagger UI template (`templates/swagger-ui.html.twig`):
```twig
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{% if title %}{{ title }}{% else %}API Platform{% endif %}</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "/api/doc.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout"
            });
        };
    </script>
</body>
</html>
```

### Security Configuration
Update `config/packages/security.yaml` to allow access to the Swagger UI:
```yaml
security:
    firewalls:
        swagger_doc:
            pattern: ^/api/doc
            security: false
        # ... other firewalls

    access_control:
        - { path: ^/api/doc, roles: PUBLIC_ACCESS }
        # ... other access controls
```

### How It Works
1. The `swagger.yaml` file in the project root contains the OpenAPI specification
2. `/api/doc` endpoint serves the Swagger UI interface
3. `/api/doc.json` endpoint converts the YAML specification to JSON for Swagger UI
4. Swagger UI is loaded from CDN (unpkg.com)
5. The UI automatically fetches and displays the API specification
6. Security is configured to allow public access to the documentation

### Accessing the Documentation
- Swagger UI: `http://localhost:8080/api/doc`
- Raw JSON specification: `http://localhost:8080/api/doc.json`

## Base Configuration
- **OpenAPI Version**: 3.0.0
- **Base URL**: `/api`
- **Server**: http://localhost:8080/api

## Authentication
The API uses JWT (JSON Web Token) Bearer authentication. Include the JWT token in the Authorization header:
```
Authorization: Bearer <your_token>
```

### Authentication Endpoints
1. **Registration**
   - Endpoint: `POST /register`
   - Purpose: Register new users
   - Required fields:
     - email (string, email format)
     - password (string)
     - firstName (string)
     - lastName (string)

2. **Login**
   - Endpoint: `POST /login`
   - Purpose: Authenticate users and receive JWT token
   - Required fields:
     - email (string, email format)
     - password (string)

## Available Endpoints

### Room Management
- `GET /rooms` - List all rooms
- `GET /rooms/available` - Get available rooms for specific dates

### Room Types
- `GET /room-types` - List all room types
- `GET /room-types/with-promotions` - Get room types with active promotions

### Reservations
- `GET /reservations` - List all reservations (requires authentication)
- `POST /reservations` - Create new reservation (requires authentication)
- `GET /reservations/{id}/calculate-price` - Calculate reservation price

### Promotions
- `GET /promotions/active` - List active promotions
- `GET /promotions/{id}/calculate-price` - Calculate price with promotion

### Amenities
- `GET /amenities` - List all amenities
- `GET /amenities/featured` - Get featured amenities

## Data Models
The API includes the following main data models:
- User
- Room
- RoomType
- Reservation
- Promotion
- Amenity

## Testing the API
You can test the API using:
1. Swagger UI at `http://localhost:8080/api/doc`
2. Postman or similar API testing tools
3. cURL commands from the terminal

## Response Formats
All API endpoints return JSON responses. Successful responses typically include:
- Single resource requests: Direct object response
- List requests: Paginated response with:
  - items: Array of objects
  - total: Total number of items
  - page: Current page number
  - pages: Total number of pages

## Error Handling
Common HTTP status codes:
- 200: Successful operation
- 201: Resource created
- 400: Bad request
- 401: Unauthorized
- 403: Forbidden
- 404: Resource not found
- 409: Conflict (e.g., duplicate resource)
- 500: Internal server error

## Security
- All endpoints are secured with JWT authentication except:
  - /login
  - /register
- Tokens must be included in the Authorization header
- Tokens have an expiration time 
