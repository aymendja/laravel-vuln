# 🛡️ Laravel Vulnerability Lab

**An Educational Web Security Project Built with Laravel**

Laravel Vulnerability Lab is an educational web application designed to demonstrate, understand, and test common web security vulnerabilities in a controlled environment.

The project provides two testing modes for each vulnerability:

- **Vulnerable Mode:** Demonstrates insecure implementations and allows users to understand how common vulnerabilities can be exploited.
- **Secure Mode:** Demonstrates secure coding practices and defensive techniques to prevent these vulnerabilities.

The goal is to bridge the gap between theoretical cybersecurity knowledge and practical web application security.

---

## 🚀 Vulnerability Labs

### 1. SQL Injection (SQLi)

**Route:** `/lab/sql-injection`

An authentication lab demonstrating how insecure SQL query construction can lead to authentication bypass.

| Mode | Description |
|---|---|
| Vulnerable Mode | Uses raw SQL queries with direct user input concatenation, allowing SQL Injection attacks. |
| Secure Mode | Uses Laravel Eloquent ORM and secure password hashing to prevent SQL Injection and authentication bypass. |

**Vulnerable implementation example:**

```php
SELECT * FROM users
WHERE email = '$email'
AND password = '$password'
```

**Security concepts covered:**
- SQL Injection fundamentals
- Authentication bypass
- SQL comments (`--`)
- Raw SQL query vulnerabilities
- Eloquent ORM
- Secure password hashing

---

## 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| PHP | Backend programming language |
| Laravel | Backend framework |
| MySQL | Relational database |
| Blade | Server-side templating engine |
| Tailwind CSS | Frontend styling |
| Composer | PHP dependency management |
| Node.js & NPM | Frontend asset management |

---

## 📋 Requirements

Before installing the project, make sure your local environment meets the following requirements:

- **PHP:** >= 8.2
- **Composer:** Latest stable version
- **Database:** MySQL or MariaDB
- **Node.js & NPM:** Required if frontend assets need compilation.


---

## ⚙️ Installation & Setup

Follow these steps to run the project locally.

### Step 1: Clone the Repository

```bash
git clone https://github.com/aymendja/laravel-vuln.git

cd laravel-vuln
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Configure Environment Variables

Create your local environment configuration file:

```bash
cp .env.example .env
```

Generate the Laravel application encryption key:

```bash
php artisan key:generate
```

### Step 4: Configure the Database

Create a MySQL database named:

```sql
CREATE DATABASE laravel_vuln_lab;
```

Update the database configuration in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_vuln_lab
DB_USERNAME=root
DB_PASSWORD=your_database_password
```

Replace the database username and password with your local MySQL credentials.

### Step 5: Run Database Migrations

```bash
php artisan migrate
```

### Step 6: Start the Development Server

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

---

## ⚠️ Security Disclaimer

**This project is developed strictly for educational purposes.**

The vulnerable implementations are intentionally insecure and are designed to demonstrate common web application security weaknesses.

- Run the application only in a local or isolated testing environment.
- Never deploy vulnerable configurations to a production server.
- Do not use the techniques demonstrated here against systems without explicit authorization.

---

## 🎯 Project Objectives

- Understand common web application vulnerabilities.
- Explore how insecure code creates security weaknesses.
- Practice identifying and exploiting vulnerabilities in a controlled environment.
- Learn how to implement secure coding practices.
- Understand the differences between vulnerable and secure application architectures.

---


*Built for learning, experimenting, and understanding web application security.*
