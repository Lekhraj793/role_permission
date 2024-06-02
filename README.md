<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Project Setup

To set up the project, follow the steps below:

1. Clone the Repository: Clone this repository to your local machine:

```bash
git clone https://github.com/Lekhraj793/role_permission.git
```


2. Install Dependencies: Navigate to the project directory and install PHP dependencies using Composer:
```bash
composer update
```

```bash
composer dump-autoload
```

3. Database Setup: Rename .env.example file. Create the mysql database. Change DB_DATABASE, DB_USERNAME and DB_PASSWORD witht the actual values.

4. Run Migrations and Seeders: Run database migrations and seeders to set up the database schema and populate initial data:

```bash
php artisan migrate
```

5. Install Laravel Passport: Install Laravel Passport for API authentication:

```bash
php artisan passport:install --no-interaction
```

6. To create a personal access client, you can use the following command:

Notice: If you do not create it, you will get the error that "Personal access client not found. Please create one."

```bash
php artisan passport:client --personal

```

7. Seed the database to get some dummy data

```bash
php artisan db:seed 
```

8. Generate the application key.

```bash
php artisan key:generate 
```

This command will seed two tables: User table and Healthcare professional table

9. Start the Laravel development server

```bash
php artisan serve
```

10. Do the testing through postman. Collection can be found in root directory.

## Usage

- **POST /api/user/store: Register a new user.**

- **POST /api/login: Log in with user credentials and obtain an access token**

- **GET /api/user: Retrieve a list of all available users.**

- **POST /api/show/id: Retrieve a single user detail using primary key.**

- **GET /api/delete/id: Delete user by primary key.**

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
