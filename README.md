# Laravel API

This is a Laravel-based API for rent-a-car application

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

