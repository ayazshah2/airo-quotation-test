# AIRO Quotation Test


### Setup Instructions:
Clone the repository
```git clone https://github.com/ayazshah2/airo-quotation-test.git```

Install dependencies
```composer install```

Copy `.env.example` and configure environment
`cp .env.example .env`

Then update DB credentials, app key, etc.

Generate application key
```php artisan key:generate```

Run migrations
```php artisan migrate```

Serve the application
```php artisan serve```

Access Frontend Forms
Visit: `http://localhost:8000/register`
The project includes simple forms for:

- Register
- Login
- Quotation (JWT-protected)