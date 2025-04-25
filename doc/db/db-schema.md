# Database Schema Documentation

## Overview
This document describes the database schema for the hotel management system. The system consists of several interconnected entities that manage rooms, reservations, payments, and other hotel-related data.

## Entities

### User
Manages user accounts for both customers and staff.

| Field         | Type         | Constraints       | Description                    |
|--------------|--------------|------------------|--------------------------------|
| id           | int          | PK, auto         | Primary identifier             |
| email        | string(255)  | unique          | User's email address           |
| roles        | json         | nullable        | User's system roles            |
| password     | string(255)  |                | Hashed password                |
| firstName    | string(255)  |                | User's first name              |
| lastName     | string(255)  |                | User's last name               |
| phoneNumber  | string(20)   | nullable        | Contact phone number           |
| isActive     | boolean      | nullable        | Account status                 |

### RoomType
Defines different types of rooms available in the hotel.

| Field           | Type         | Constraints    | Description                    |
|----------------|--------------|---------------|--------------------------------|
| id             | int          | PK, auto      | Primary identifier             |
| name           | string(100)  |              | Room type name                 |
| capacity       | int          |              | Maximum occupancy              |
| pricePerNight  | decimal(10,2)|              | Standard price per night       |
| description    | text         |              | Detailed description           |
| amenities      | json         | nullable      | List of included amenities     |
| createdAt      | datetime     |              | Creation timestamp             |
| updatedAt      | datetime     |              | Last update timestamp          |

### Room
Represents individual rooms in the hotel.

| Field         | Type         | Constraints    | Description                    |
|--------------|--------------|---------------|--------------------------------|
| id           | int          | PK, auto      | Primary identifier             |
| roomNumber   | string(20)   | unique        | Room identifier                |
| roomType     | relation     | FK            | Reference to RoomType          |
| floorNumber  | int          |              | Floor location                 |
| isAvailable  | boolean      |              | Availability status            |
| description  | text         | nullable      | Room-specific description      |
| createdAt    | datetime     |              | Creation timestamp             |
| updatedAt    | datetime     |              | Last update timestamp          |

### Reservation
Manages room bookings and stays.

| Field           | Type         | Constraints    | Description                    |
|----------------|--------------|---------------|--------------------------------|
| id             | int          | PK, auto      | Primary identifier             |
| user           | relation     | FK            | Reference to User              |
| checkInDate    | datetime     |              | Check-in date and time         |
| checkOutDate   | datetime     |              | Check-out date and time        |
| totalPrice     | decimal(10,2)|              | Total reservation cost         |
| status         | string(20)   |              | Reservation status             |
| specialRequests| text         | nullable      | Special accommodation requests |
| createdAt      | datetime     |              | Creation timestamp             |
| updatedAt      | datetime     |              | Last update timestamp          |

### ReservationRoom
Links reservations with specific rooms.

| Field         | Type         | Constraints    | Description                    |
|--------------|--------------|---------------|--------------------------------|
| id           | int          | PK, auto      | Primary identifier             |
| reservation  | relation     | FK            | Reference to Reservation       |
| room         | relation     | FK            | Reference to Room              |
| pricePerNight| decimal(10,2)|              | Price at time of booking       |
| createdAt    | datetime     |              | Creation timestamp             |
| updatedAt    | datetime     |              | Last update timestamp          |

### Payment
Tracks payments for reservations.

| Field          | Type         | Constraints    | Description                    |
|---------------|--------------|---------------|--------------------------------|
| id            | int          | PK, auto      | Primary identifier             |
| reservation   | relation     | FK            | Reference to Reservation       |
| amount        | decimal(10,2)|              | Payment amount                 |
| paymentMethod | string(50)   |              | Method of payment              |
| paymentStatus | string(20)   |              | Status of payment              |
| transactionId | string(100)  | nullable      | External transaction reference |
| paymentDate   | datetime     |              | Date of payment                |
| createdAt     | datetime     |              | Creation timestamp             |
| updatedAt     | datetime     |              | Last update timestamp          |

### Invoice
Manages billing information for reservations.

| Field         | Type         | Constraints    | Description                    |
|--------------|--------------|---------------|--------------------------------|
| id           | int          | PK, auto      | Primary identifier             |
| reservation  | relation     | FK, OneToOne  | Reference to Reservation       |
| invoiceNumber| string(50)   | unique        | Unique invoice identifier      |
| invoiceDate  | datetime     |              | Date of invoice creation       |
| dueDate      | datetime     |              | Payment due date               |
| subtotal     | decimal(10,2)|              | Amount before tax/discounts    |
| tax          | decimal(10,2)|              | Tax amount                     |
| discount     | decimal(10,2)| default=0     | Discount amount               |
| total        | decimal(10,2)|              | Final amount                   |
| status       | string(20)   |              | Invoice status                 |
| createdAt    | datetime     |              | Creation timestamp             |
| updatedAt    | datetime     |              | Last update timestamp          |

### Review
Stores guest reviews for their stays.

| Field        | Type         | Constraints    | Description                    |
|-------------|--------------|---------------|--------------------------------|
| id          | int          | PK, auto      | Primary identifier             |
| user        | relation     | FK            | Reference to User              |
| reservation | relation     | FK            | Reference to Reservation       |
| rating      | int          | min=1, max=5  | Rating score                   |
| comment     | text         | nullable      | Review comment                 |
| createdAt   | datetime     |              | Creation timestamp             |
| updatedAt   | datetime     |              | Last update timestamp          |

### Amenity
Defines available amenities for room types.

| Field       | Type         | Constraints    | Description                    |
|------------|--------------|---------------|--------------------------------|
| id         | int          | PK, auto      | Primary identifier             |
| name       | string(100)  |              | Amenity name                   |
| description| text         | nullable      | Detailed description           |
| icon       | string(100)  | nullable      | Icon identifier/path           |
| createdAt  | datetime     |              | Creation timestamp             |
| updatedAt  | datetime     |              | Last update timestamp          |

### RoomAmenity
Links room types with their amenities.

| Field     | Type         | Constraints    | Description                    |
|----------|--------------|---------------|--------------------------------|
| id       | int          | PK, auto      | Primary identifier             |
| roomType | relation     | FK            | Reference to RoomType          |
| amenity  | relation     | FK            | Reference to Amenity           |
| createdAt| datetime     |              | Creation timestamp             |
| updatedAt| datetime     |              | Last update timestamp          |

### Promotion
Manages special offers and discounts.

| Field              | Type         | Constraints    | Description                    |
|-------------------|--------------|---------------|--------------------------------|
| id                | int          | PK, auto      | Primary identifier             |
| name              | string(100)  |              | Promotion name                 |
| description       | text         | nullable      | Detailed description           |
| discountPercentage| decimal(5,2) |              | Discount percentage            |
| startDate         | datetime     |              | Promotion start date           |
| endDate           | datetime     |              | Promotion end date             |
| isActive          | boolean      |              | Active status                  |
| createdAt         | datetime     |              | Creation timestamp             |
| updatedAt         | datetime     |              | Last update timestamp          |

### PromotionRoomType
Links promotions with applicable room types.

| Field     | Type         | Constraints    | Description                    |
|----------|--------------|---------------|--------------------------------|
| id       | int          | PK, auto      | Primary identifier             |
| promotion| relation     | FK            | Reference to Promotion         |
| roomType | relation     | FK            | Reference to RoomType          |
| createdAt| datetime     |              | Creation timestamp             |
| updatedAt| datetime     |              | Last update timestamp          |

## Relationships

1. **User - Reservation**: One-to-Many
   - A user can have multiple reservations
   - Each reservation belongs to one user

2. **RoomType - Room**: One-to-Many
   - A room type can have multiple rooms
   - Each room belongs to one room type

3. **Reservation - Room**: Many-to-Many (through ReservationRoom)
   - A reservation can include multiple rooms
   - A room can be part of multiple reservations (at different times)

4. **Reservation - Payment**: One-to-Many
   - A reservation can have multiple payments
   - Each payment belongs to one reservation

5. **Reservation - Invoice**: One-to-One
   - Each reservation has exactly one invoice
   - Each invoice belongs to exactly one reservation

6. **RoomType - Amenity**: Many-to-Many (through RoomAmenity)
   - A room type can have multiple amenities
   - An amenity can be associated with multiple room types

7. **Promotion - RoomType**: Many-to-Many (through PromotionRoomType)
   - A promotion can apply to multiple room types
   - A room type can have multiple promotions

8. **Reservation - Review**: One-to-Many
   - A reservation can have multiple reviews
   - Each review belongs to one reservation

## Notes

- All entities include `createdAt` and `updatedAt` timestamps for audit purposes
- Soft delete functionality can be added if needed
- Decimal fields use scale of 2 for currency amounts
- String lengths are optimized for typical content
- Nullable fields are marked where appropriate 
