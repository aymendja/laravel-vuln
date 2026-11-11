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

No need to install PHP, Composer, or MySQL on your machine. Docker provides all of them.

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
> Until then, prefix every `docker` command with `sudo`.
>
> If `docker compose` is not recognized, install the plugin: `sudo apt install docker-compose-v2` (or `docker-compose` and use the hyphenated command).

### Step 1: Clone the repository

```bash
# Download the project
git clone https://github.com/aymendja/laravel-vuln.git

# Go into the project folder
cd laravel-vuln
```

### Step 2: Check the Docker files

The repository must contain these two files in the project root (note the exact names, Linux is case-sensitive):

- `Dockerfile` (not `DockerFile`)
- `docker-compose.yaml`

<details>
<summary>Reference: Dockerfile</summary>

```dockerfile
# The project requires PHP 8.4 or newer (see composer.lock)
FROM php:8.4-cli

# System libraries + PHP extensions needed by Laravel
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd bcmath

# Install Composer (PHP package manager)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Avoid git "dubious ownership" warnings inside the container
RUN git config --global --add safe.directory /var/www/html
```

</details>

<details>
<summary>Reference: docker-compose.yaml</summary>

```yaml
services:
  app:                                   # Laravel application
    build: .
    ports:
      - "8000:8000"                      # http://localhost:8000
    volumes:
      - .:/var/www/html                  # live-sync your code into the container
    command: php artisan serve --host=0.0.0.0 --port=8000
    depends_on:
      - db

  db:                                    # MySQL database (downloaded automatically)
    image: mysql:8
    environment:
      MYSQL_DATABASE: laravel
      MYSQL_USER: laravel
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"                      # change to "3307:3306" if 3306 is already used
    volumes:
      - dbdata:/var/lib/mysql            # keeps your data between restarts

volumes:
  dbdata:
```

</details>

### Step 3: Create the `.env` file

```bash
# Copy the example configuration
cp .env.example .env
```

Open `.env` and set the database section **exactly** like this. The values must match `docker-compose.yaml`:

```env
DB_CONNECTION=mysql       # .env.example uses sqlite, change it to mysql
DB_HOST=db                # the service name in docker-compose.yaml, NOT localhost
DB_PORT=3306
DB_DATABASE=laravel       # = MYSQL_DATABASE
DB_USERNAME=laravel       # = MYSQL_USER
DB_PASSWORD=secret        # = MYSQL_PASSWORD
```

> Do not put the `# comments` on the same line in your real `.env` file if your values contain spaces. They are shown here only for explanation.

### Step 4: Build the Docker image

```bash
# Builds the PHP container (first time takes a few minutes)
docker compose build
```

### Step 5: Install PHP dependencies

```bash
# Runs composer inside the container and creates the vendor/ folder
docker compose run --rm app composer install
```

### Step 6: Generate the application key

```bash
# Writes APP_KEY into your .env file
docker compose run --rm app php artisan key:generate
```

### Step 7: Start the containers

```bash
# -d = run in the background
docker compose up -d
```

### Step 8: Create the database tables

```bash
# Wait about 20 seconds after the first start so MySQL can initialize, then:
docker compose exec app php artisan migrate:fresh --seed
```

### Step 9: Open the application

```text
http://localhost:8000
```

### Everyday commands

```bash
docker compose up -d                           # start the project
docker compose down                            # stop the project (data is kept)
docker compose down -v                         # stop AND delete the database data
docker compose logs -f app                     # view live logs / errors
docker compose exec app php artisan <command>  # run any artisan command
docker compose exec app composer require x/y   # install a new PHP package
docker compose exec db mysql -u laravel -psecret laravel   # open the MySQL shell
```

### Troubleshooting

| Problem | Solution |
|---|---|
| `failed to read dockerfile: no such file` | The file must be named `Dockerfile` (lowercase f). Run `mv DockerFile Dockerfile`. |
| `requires php >=8.4` during `composer install` | Make sure the Dockerfile starts with `FROM php:8.4-cli`, then run `docker compose build --no-cache`. |
| `permission denied ... docker.sock` | Add your user to the docker group (see Prerequisites) or use `sudo`. |
| `Connection refused` when migrating | MySQL is still starting. Wait 20 seconds and retry. |
| `port is already allocated` on 3306 | Change `"3306:3306"` to `"3307:3306"` in `docker-compose.yaml`. |
| Permission errors on `storage/` | `docker compose exec app chmod -R 777 storage bootstrap/cache` |
| Changed DB credentials but nothing happens | MySQL reads them only on first creation. Run `docker compose down -v` and start again. |
| Page loads without styling | Build the frontend assets: `docker run --rm -v "$PWD":/app -w /app node:22 sh -c "npm install && npm run build"` |

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
