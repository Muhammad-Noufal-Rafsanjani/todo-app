# Todo App

A simple notes/task management web app built with **PHP (native)** and **MySQL**. This project is a foundation for further development — more features are planned as the app grows.

## Features

- User authentication (register, login, logout) with session-based access control
- Create, read, update, and delete notes (CRUD)
- Mark notes as done/undone
- Clean, responsive UI

## Tech Stack

- **Backend:** PHP (native, no framework)
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Local environment:** XAMPP

## Project Structure

```
todo-app/
├── assets/
│   └── css/
│       └── style.css
├── config.example.php   # Template for database credentials
├── koneksi.php          # Database connection
├── index.php            # Main notes list page
├── login.php
├── register.php
├── logout.php
├── proses_login.php
├── proses_register.php
├── tambah.php            # Add note
├── edit.php              # Edit note
├── update.php            # Update note
├── hapus.php              # Delete note
├── toggle.php             # Toggle done/undone status
└── .gitignore
```

## Database Schema

**Database name:** `to_do_list`

**Table: `users`**
| Column | Type |
|--------|------|
| id | INT, Primary Key, Auto Increment |
| email | VARCHAR |
| password | VARCHAR (hashed) |

**Table: `notes`**
| Column | Type |
|--------|------|
| id | INT, Primary Key, Auto Increment |
| user_id | INT, Foreign Key -> users.id |
| title | VARCHAR |
| content | TEXT |
| created_at | DATETIME |
| is_done | BOOLEAN |

## Setup Instructions

1. Clone this repository into your XAMPP `htdocs` folder:
   ```
   git clone https://github.com/Muhammad-Noufal-Rafsanjani/todo-app.git
   ```
2. Start **Apache** and **MySQL** via XAMPP Control Panel.
3. Create a database named `to_do_list` in phpMyAdmin, then create the `users` and `notes` tables as described above.
4. Copy `config.example.php` to `config.php`:
   ```
   cp config.example.php config.php
   ```
5. Open `config.php` and adjust the database credentials if needed (defaults match a standard XAMPP setup).
6. Open the app in your browser:
   ```
   http://localhost/todo-app
   ```

## Planned Improvements

- [ ] Search and filter notes
- [ ] Categories/tags for notes
- [ ] Better UI/UX
- [ ] Input validation and error handling improvements

## Author

**Noufal Rafsanjani**
