# SWE491_GRADUATION_PROJECT_1_20210203_MUHAMMED_MURAT_YANASOGLU

**Graduation Project I:** Academicians' Student Office Hours Reservation System, Between Students and Academicians

This project is a web-based application designed to manage office hour reservations between students and academicians. The system includes separate interfaces for academicians and students, allowing for reservation requests, approvals, and schedule viewing etc

- **Student Name:** Muhammed Murat Yanaşoğlu
- **Student Number:** 20210203
- **Department:** Software Engineering (English)
- **Project Supervisor:** Prof. Dr. Fadi Al-TURJMAN

## Table of Contents

- [Project Structure](#project-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Contact](#contact)

## Project Structure

The project is organized into two main directories: `academician_website` and `student_office_hour_reservation`.

```
SWE491_GRADUATION_PROJECT_1_20210203_MUHAMMED_MURAT_YANASOGLU
├── academician_website
│ ├── uploads (Directory for storing uploaded files)
│ ├── vendor (Composer dependencies directory)
│ ├── accept_reservation.php (Script to accept reservation requests)
│ ├── accepted_reservations.php (Page to view accepted reservations)
│ ├── background_img.jpg (Background image)
│ ├── composer.json (Composer configuration file)
│ ├── composer.lock (Composer lock file)
│ ├── config_db.php (Database configuration for academician database)
│ ├── config_students.php (Database configuration for student database)
│ ├── create_db_connection.php (Script to create database connection)
│ ├── default_profile_picture.png (Default profile picture)
│ ├── edit_profile.php (Page to edit academician profile)
│ ├── export_excel.php (Script to export data to Excel)
│ ├── forgot_password.php (Page for password recovery)
│ ├── form_page_design.css (CSS file for form page design)
│ ├── home_background_img.jpg (Home page background image)
│ ├── home.php (Home page for academician)
│ ├── index.php (Login page for academician)
│ ├── neu-logo.png (NEU logo image)
│ ├── pending_reservations.php (Page to view pending reservations)
│ ├── profile.php (Page to view and edit profile)
│ ├── register.php (Page to register a new academician)
│ ├── reject_reservation.php (Script to reject reservation requests)
│ ├── remove_reservation.php (Script to remove reservation requests)
│ ├── save_timetable.php (Script to save timetable entries)
│ ├── settings.php (Settings page for academician)
│ ├── timetable.css (CSS file for timetable)
│ ├── timetable.js (JavaScript file for timetable functionalities)
│ └── timetable.php (Page to view and manage timetable)
└── student_office_hour_reservation
├── uploads (Directory for storing uploaded files)
├── background_img.jpg (Background image)
├── config_db.php (Database configuration)
├── config.php (General configuration file)
├── default_profile_picture.png (Default profile picture)
├── edit_profile.php (Page to edit student profile)
├── forgot_password.php (Page for password recovery)
├── home_background_img.jpg (Home page background image)
├── home.css (CSS file for home page)
├── home.php (Home page for student)
├── index.php (Login page for student)
├── list_of_timetables.php (Page to list academicians' timetables)
├── neu-logo.png (NEU logo image)
├── profile.php (Page to view and edit profile)
├── register.php (Page to register a new student)
├── send_reservation_request.php (Script to send reservation requests)
├── settings.php (Settings page for student)
├── student_accepted_reservations.php (Page to view accepted reservations)
├── student_create_db_connection.php (Script to create student database connection)
├── student_pending_reservations.php (Page to view pending reservations)
├── submit_reservation.php (Script to submit reservation requests)
└── view_timetable.php (Page to view specific timetable)
```

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/muratyanasoglu/SWE491_GRADUATION_PROJECT_1_20210203_MUHAMMED_MURAT_YANASOGLU.git
   ```

Navigate to the project directory:

```cd SWE491_GRADUATION_PROJECT_1_20210203_MUHAMMED_MURAT_YANASOGLU```

Install dependencies using Composer:

```composer install```

    Set up your database:
        Create the databases form_db and students_database.
        Import the SQL files provided to set up the necessary tables.

    Configure your server:
        Place the project directory within the root of your web server (e.g., htdocs for XAMPP).

## Configuration
Database Configuration

Ensure that the database connection settings are correct in the following files:

    academician_website/config_db.php
    academician_website/config_students.php
    student_office_hour_reservation/config_db.php
    student_office_hour_reservation/config.php

Directory Permissions

Ensure that the uploads directories in both academician_website and student_office_hour_reservation are writable by the web server.

## Usage

Academician Website

    Home Page (home.php): The homepage displays an overview of the academician's profile and current reservations.
    Profile Management (profile.php): Academicians can edit their profiles and upload profile pictures.
    Pending Reservations (pending_reservations.php): Displays a list of pending reservations from students. Academicians can accept or reject requests.
    Accepted Reservations (accepted_reservations.php): Displays a list of accepted reservations.
    Timetable Management (timetable.php, save_timetable.php): Academicians can manage their timetables, including adding, editing, and deleting time slots.
    Settings (settings.php): Manage account settings.
    Export to Excel (export_excel.php): Export reservation data to an Excel file.
    Register (register.php): Register a new academician account.
    Login (index.php): Login page for academicians.
    Forgot Password (forgot_password.php): Recover academician account password.

Student Website

    Home Page (home.php): The homepage displays an overview of the student's profile and current reservations.
    Profile Management (profile.php): Students can edit their profiles and upload profile pictures.
    Search Academicians (list_of_timetables.php): Students can search for academicians and view their timetables.
    Pending Reservations (student_pending_reservations.php): Displays a list of pending reservations sent by the student.
    Accepted Reservations (student_accepted_reservations.php): Displays a list of accepted reservations by the academician.
    Send Reservation Request (send_reservation_request.php): Send a new reservation request to an academician.
    Settings (settings.php): Manage account settings.
    Register (register.php): Register a new student account.
    Login (index.php): Login page for students.
    Forgot Password (forgot_password.php): Recover student account password.

## Contact

For any inquiries, please contact [akademiyanasoglu@gmail.com],[20210203@std.neu.edu.tr]
