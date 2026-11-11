🛡️ Laravel Vulnerability Lab (Educational Project) An interactive educational web application built with Laravel, designed for understanding, demonstrating, and testing common web security vulnerabilities. The platform provides dual-mode labs featuring both a Vulnerable Mode for hands-on exploitation practice and a Secure Mode demonstrating robust defensive architecture.

🚀 Features & Labs **SQL** Injection Authentication Bypass Lab (/lab/sql-injection)

Vulnerable Mode: Implements raw, concatenated **SQL** queries (**SELECT** * **FROM** users **WHERE** email = '$email' **AND** password = '$password'), making it vulnerable to authentication bypass techniques using **SQL** comments (-- ).

Secure Mode: Utilizes secure Eloquent **ORM** patterns combined with standard password_verify() hashing to completely mitigate injection risks.

🛠️ Tech Stack Backend: Laravel (**PHP**)
Before you begin, ensure your local machine meets the following requirements:
- **PHP:** >= 8.2 (with extensions like BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML)
- **Composer:** Latest version
- **Database:** MySQL or MariaDB
- **Node.js & NPM:** (Optional, if frontend assets need compilation)

Database: MySQL

Frontend: Blade Templates & Tailwind **CSS**

⚙️ Step-by-Step Local Setup Guide for Follow these instructions carefully to clone and run the repository successfully on your local machine:

Step 1: Clone the Repository Open your terminal and clone the project from GitHub, then navigate into the project directory:

Bash git clone [https://github.com/aymendja/laravel-vuln.git](https://github.com/aymendja/laravel-vuln.git) cd laravel-vuln-lab

Step 2: Install **PHP** Dependencies Install all required project packages using Composer:

Bash composer install Step 3: Configure Environment Variables Copy the example environment configuration file to create your local .env file:

Bash cp .env.example .env Generate a unique application encryption key:

Bash php artisan key:generate Step 4: Setup Database Credentials Open the .env file in your code editor and update your local MySQL database configurations:

Code snippet DB_CONNECTION=mysql DB_HOST=**127**.0.0.1 DB_PORT=**3306** DB_DATABASE=laravel_vuln_lab DB_USERNAME=root DB_PASSWORD=your_database_password Step 5: Run Migrations :

Bash php artisan migrate Step 6: Start the Local Development Server Launch the built-in **PHP** development server:

Bash php artisan serve