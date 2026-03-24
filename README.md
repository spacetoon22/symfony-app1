# Symfony App 1

Simple Symfony application with MySQL database import and local PHP server.

---

## 📦 Requirements

* PHP 8.2+ (tested with PHP 8.4.18)
* Composer
* MySQL Server
* Git
* Linux / Ubuntu (recommended)

---

## 🚀 Installation

### 1. Update system

```bash
sudo apt update
```

### 2. Install PHP + extensions

```bash
sudo apt install -y php php-cli php-mbstring php-xml php-curl php-mysql unzip git
```

### 3. Install Composer

```bash
sudo apt install -y composer
```

### 4. Verify installation

```bash
php -v
composer -V
```

---

## 📥 Clone Project

```bash
git clone https://github.com/spacetoon22/symfony-app1.git
cd symfony-app1
cp .env.dev .env
```

---

## 🛢️ Database Setup

Install MySQL:

```bash
sudo apt install -y mysql-server
```

Edit `.env` file:

```bash
nano .env
```

Update database config:

```
DEFAULT_URI=http://localhost
DATABASE_URL="mysql://symfony:password@127.0.0.1:3306/symfony_app"
```

---

## 🔐 Create MySQL User

```sql
sudo mysql
```

```sql
DROP USER IF EXISTS 'symfony'@'localhost';

CREATE USER 'symfony'@'localhost' IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES ON symfony_app.* TO 'symfony'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

---

## 📂 Import Database

```bash
mysql -u symfony -p symfony_app < backup.sql
```

---

## 📦 Install Dependencies

```bash
composer install
```

If you get a PHP version error, install the compatible version.

Check PHP version:

```bash
php -v
```

Example:

```
PHP 8.4.18 (cli)
```

---

## ▶️ Run Application

```bash
php -S 0.0.0.0:8000 -t public
```

Open in browser:

```
http://localhost:8000
```

---

## 👤 Default Database Credentials

| Field    | Value       |
| -------- | ----------- |
| User     | symfony     |
| Password | password    |
| Database | symfony_app |
| Host     | 127.0.0.1   |

---

## 🛠️ Troubleshooting

### Composer version error

Install compatible PHP version or run:

```bash
composer update
```

### Database connection error

Check `.env` file and MySQL user permissions.

---

## 📄 License

Private project — internal use.
