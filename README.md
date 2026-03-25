# Symfony Client Dossier Management

A Symfony-based web application used to register, review, and approve client dossiers with automatic SMS notification using Twilio.

---

# 📘 Project Overview

This application allows clients to submit dossiers for different services.
Each dossier goes through a validation workflow:

1. Client submits dossier (`/taxi` or `/health`)
2. Commercial team reviews the request
3. Commercial validates and forwards to admin
4. Admin approves the dossier
5. Client receives SMS notification via Twilio

---

# 🔄 Workflow

Client → Commercial → Admin → SMS Notification

### Step-by-step

1. Client registers from:

   * `/taxi`
   * `/health`

2. Dossier is created in system

3. Commercial team:

   * reviews dossier
   * checks documents
   * validates information
   * sends to admin

4. Admin:

   * approves dossier
   * system updates status

5. Twilio:

   * automatically sends SMS to client
   * confirms approval

---

# ✨ Features

* Client dossier registration
* Taxi dossier form (`/taxi`)
* Health dossier form (`/health`)
* Commercial review panel
* Admin approval dashboard
* Dossier status tracking
* MySQL database
* Backup database import
* Twilio SMS notification on approval
* Environment configuration
* Local PHP server support

---

# 📦 Requirements

* PHP 8.2+
* Composer
* MySQL Server
* Git
* Ubuntu / Linux recommended

Tested with:

```
PHP 8.4.18
```

---

# 🚀 Installation

## 1. Update system

```bash
sudo apt update
```

## 2. Install PHP + extensions

```bash
sudo apt install -y php php-cli php-mbstring php-xml php-curl php-mysql unzip git
sudo add-apt-repository ppa:ondrej/php -y
sudo apt install -y php8.4 php8.4-cli php8.4-mbstring php8.4-xml php8.4-curl php8.4-mysql
```

## 3. Install Composer

```bash
sudo apt install -y composer
```

## 4. Verify

```bash
php -v
composer -V
```

---

# 📥 Clone Project

```bash
git clone https://github.com/spacetoon22/symfony-app1.git
cd symfony-app1
cp .env.dev .env
```

---

# 🛢️ Database Setup

Install MySQL:

```bash
sudo apt install -y mysql-server
```

Edit `.env`

```bash
nano .env
```

Update:

```
DEFAULT_URI=http://localhost
DATABASE_URL="mysql://symfony:password@127.0.0.1:3306/symfony_app"
```

---

# 🔐 Create MySQL User

```bash
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

# 📂 Import Database

```bash
mysql -u symfony -p symfony_app < backup.sql
```

---

# 📦 Install Dependencies

```bash
composer install
```

If version error occurs, install compatible PHP version.

---

# ▶️ Run Application

```bash
php -S 0.0.0.0:8000 -t public
```

Open:

```
http://localhost:8000
```

---

# 📩 Twilio SMS Notification

When admin approves a dossier:

* Client status → Approved
* Twilio API triggered
* SMS sent to client phone number
* Client notified automatically

Example message:

```
Your dossier has been approved. Our team will contact you shortly.
```

---

# 🧠 Dossier Status Flow

```
Created
↓
Commercial Review
↓
Sent to Admin
↓
Approved
↓
SMS Sent
```

---

# 👤 Default Database Credentials

| Field    | Value       |
| -------- | ----------- |
| User     | symfony     |
| Password | password    |
| Database | symfony_app |
| Host     | 127.0.0.1   |

---

# 🛠️ Troubleshooting

### Composer error

```
composer update
```

### Database connection error

Check `.env` file

### Port already used

Run on another port:

```bash
php -S 0.0.0.0:8001 -t public
```

---

# 📁 Routes

| Route   | Description                |
| ------- | -------------------------- |
| /taxi   | Taxi client registration   |
| /health | Health client registration |
| /admin  | Admin dashboard            |
| /login  | Login page                 |

---

# 🔒 Notes

* Twilio credentials stored in environment variables
* Database imported from backup.sql
* Designed for internal workflow usage

---

# 📄 License

Private internal project.
