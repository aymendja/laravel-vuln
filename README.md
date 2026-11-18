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

- **PHP:** >= 8.4
- **Composer:** Latest stable version
- **Database:** MySQL or MariaDB
- **Node.js & NPM:** Required if frontend assets need compilation.


---

## ⚙️ Manual Installation & Setup (without Docker)

Follow these steps if you prefer installing PHP, Composer and MySQL on your own machine. (Docker instructions are in the next section.)

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
php artisan migrate:fresh --seed
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

## 🐳 Running with Docker (Recommended)

No need to install PHP, Composer, or MySQL. Docker installs the dependencies, creates the app key and the database tables, and adds the demo admin user for you.

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) with the Compose plugin
- [Git](https://git-scm.com)

> **Linux/Kali users:** if you get `permission denied ... docker.sock`, add yourself to the docker group, then log out and back in:
>
> ```bash
> sudo usermod -aG docker $USER
> newgrp docker
> ```
>
> If `docker compose` is not recognized, install the plugin: `sudo apt install docker-compose-v2`.

### Run it

```bash
git clone https://github.com/aymendja/laravel-vuln.git
cd laravel-vuln
docker compose up --build
```

The first start takes a few minutes. Then open **http://localhost:8000**
(demo login: `admin@example.com` / `adminadmin`).

### Everyday commands

```bash
docker compose up -d                           # start in the background
docker compose down                            # stop (data is kept)
docker compose down -v                         # stop AND delete the database (fresh start)
docker compose up --build                      # rebuild after changing the code
docker compose logs -f app                     # view logs / errors
docker compose exec app php artisan <command>  # run any artisan command
```

> The app is only reachable from your own computer (`127.0.0.1`), on purpose: some labs allow code execution.

### Troubleshooting

| Problem | Solution |
|---|---|
| `permission denied ... docker.sock` | Add your user to the docker group (see Prerequisites) or use `sudo`. |
| `port is already allocated` | Change `"127.0.0.1:8000:8000"` to `"127.0.0.1:8080:8000"` in `docker-compose.yaml`, then open `http://localhost:8080`. |
| Changed DB credentials but nothing happens | MySQL reads them only on first creation. Run `docker compose down -v` and start again. |
| Page loads without styling | The layout loads Tailwind from a CDN, so the browser needs internet access. |

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
