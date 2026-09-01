MakerFest Application Setup Guide

Follow these instructions to set up and run the MakerFest application on your local machine.

1. Clone the Repository
Open your terminal and clone the repository from GitHub:
git clone https://github.com/krunal9116/makerfest-app.git<br>
cd makerfest-app

2. Install Dependencies
Make sure you have PHP and Composer installed on your machine. Run the following command to install the required PHP packages:
composer install

3. Environment Setup
Copy the example environment file to create your own configuration:
cp .env.example .env

Generate the application key:
php artisan key:generate

Open the .env file in your code editor and update the database configuration to match your local MySQL server (typically DB_DATABASE=makerfest, DB_USERNAME=root, DB_PASSWORD=).

4. Database Setup
Instead of running migrations from scratch, import the provided SQL file to get the exact database structure and seed data.
- Open your database manager (like phpMyAdmin or MySQL CLI).
- Create a new database matching the name in your .env file (e.g., makerfest).
- Import the makerfest_database.sql file provided in the repository into this new database.

5. Run the Application
Start the local development server:
php artisan serve

Open your browser and visit: http://localhost:8000

--------------------------------------------------
Test Credentials
The imported database comes pre-loaded with users for each role so you can test the application flows easily:

Admin Account
Email: admin@gmail.com
Password: Admin@123

Maker Account
Email: maker@gmail.com
Password: Maker@123

Judge Account
Email: judge@gmail.com
Password: Judge@123

Volunteer Account
Email: volunteer@gmail.com
Password: Volunteer@123
