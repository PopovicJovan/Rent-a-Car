# Rent a Car API (Laravel)

## Project Overview
This project was developed as the final project of the Cortex Academy for high school students. It is a REST API built using the Laravel framework for managing a rent-a-car system. The API allows users to register, browse, and book vehicles, while administrators have additional privileges to manage users, vehicles, and reservations. Authentication is implemented using **Laravel Sanctum**.

## Technologies
- **Laravel** (PHP framework)
- **Laravel Sanctum** (Token-based authentication)
- **MySQL** (Database management)
- **Eloquent ORM** (Database interaction)
- **RESTful architecture**
- **WebSocket** (Real-time vehicle tracking simulation)
- **Mail Server Integration**

---

## API Features

### 🔹 **Users**
- **Registration and Authentication**
  - `POST /register` – Create a new user account
  - `POST /login` – Log in and receive an authentication token
  - `POST /logout` – Log out the user (requires authentication)
- **Profile Management**
  - `GET /show-profile` – View user profile
  - `POST /update-profile` – Update user profile
  - `POST /change-password` – Change password
  - `POST /forgot-password` – Send password reset token
  - `POST /reset-password` – Reset password

### 🚗 **Vehicles**
- `GET /car` – Retrieve a list of available vehicles
- `GET /car/{id}` – View details of a specific vehicle
- `POST /car/{car}/is-available` – Check vehicle availability

### 📅 **Reservations**
- `POST /reservation` – Create a new reservation
- `GET /reservation` – View user's reservations
- `DELETE /reservation/{id}` – Cancel a reservation
- `POST /car/{car}/reservation/get-price` – Calculate reservation price
- **Invoice Emails**: Users receive invoices via email upon successful reservation.

### ⭐ **Ratings**
- `POST /reservation/{reservation}/rate` – Rate a reservation
- `GET /car/{car}/rate` – View all ratings for a specific vehicle

---

## 🛠 **Administrative Features** (requires **is-admin** middleware)

### 👥 **User Management**
- `GET /admin/user` – View all users
- `DELETE /admin/user/{id}` – Ban a user
- `GET /admin/user/{user}/reservation` – View user's reservations

### 🚗 **Vehicle Management**
- `POST /admin/car` – Add a new vehicle
- `DELETE /admin/car/{id}` – Delete a vehicle
- `POST /admin/car/{car}` – Update vehicle details

### 📅 **Reservation Management**
- `GET /admin/reservation` – View all reservations

---

## 🔐 **Authentication & Route Protection**
- Most user functionalities require **auth:sanctum** middleware.
- Admin routes additionally require **is-admin** middleware.
- The system uses **token-based authentication** with Laravel Sanctum.

---

## 📌 **Additional Notes**
- The project supports real-time communication using WebSocket to simulate vehicle tracking.
- The API follows RESTful standards.
- The mail server is integrated to send invoices to users after booking a vehicle.

---

## Requirements

Before running this project, ensure you have the following installed:

- PHP (version 7.3 or higher)
- Composer
- MySQL (or any other database supported by Laravel)

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/PopovicJovan/Rent-a-Car.git
   cd your-repository-folder

2. **Install dependencies**
    ```bash
   composer install
3. **Copy the env file**
   ```bash
   cp .env.example .env
4. **Configure your .env file**
   - Set up your database connection
        ```bash
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name
        DB_USERNAME=your_database_user
        DB_PASSWORD=your_database_password
   - Configure your email provider settings (e.g., SMTP)
        ```bash
        MAIL_MAILER=smtp
        MAIL_HOST=your_mail_host
        MAIL_PORT=your_mail_port
        MAIL_USERNAME=your_email
        MAIL_PASSWORD=your_email_password
        MAIL_ENCRYPTION=tls
        MAIL_FROM_ADDRESS=your_from_email
        MAIL_FROM_NAME="${APP_NAME}"
   - Set up Pusher for real-time notifications
        ```bash
        BROADCAST_DRIVER=pusher
        PUSHER_APP_ID=your_pusher_app_id
        PUSHER_APP_KEY=your_pusher_app_key
        PUSHER_APP_SECRET=your_pusher_app_secret
        PUSHER_APP_CLUSTER=your_pusher_app_cluster
5. **Run database migrations**
    ```bash
   php artisan migrate
6. **Running the API**
   - To start the local development server
     ```bash
     php artisan serve
   - To start WebSocket server:
     ```bash
     php artisan websockets:serve

