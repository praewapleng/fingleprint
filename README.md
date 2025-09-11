# Fingerprint Attendance System

This project is a web application designed for managing attendance using fingerprint scanning technology. It has two user roles: Teacher and Student.

## Features

- **Teacher Role:**
  - Create and manage subjects.
  - Edit existing subjects.
  - View attendance records for their subjects, including scan-in and scan-out times.

- **Student Role:**
  - View their class schedule.
  - Access their own attendance records.

## Project Structure

```
root
├── config
│   └── database.php          # Database connection settings
├── includes
│   ├── auth.php              # User authentication logic
│   └── functions.php         # Utility functions
├── public
│   ├── css
│   │   └── style.css         # CSS styles for the application
│   ├── js
│   │   └── main.js           # JavaScript functionality
│   └── index.php             # Main entry point of the application
├── teacher
│   ├── create-subject.php    # Form to create new subjects
│   ├── edit-subject.php      # Edit existing subjects
│   ├── manage-subjects.php    # List of subjects created by the teacher
│   └── view-attendance.php    # View attendance records
├── student
│   ├── view-schedule.php      # View class schedule
│   └── view-attendance.php     # View own attendance records
├── auth
│   ├── login.php              # User login form
│   ├── logout.php             # User logout functionality
│   └── register.php           # User registration form
├── database
│   └── schema.sql            # SQL schema for database setup
└── README.md                  # Project documentation
```

## Setup Instructions

1. Clone the repository to your local machine.
2. Navigate to the `config` directory and update `database.php` with your database connection settings.
3. Run the SQL commands in `database/schema.sql` to set up the database tables.
4. Open `public/index.php` in your web browser to access the application.

## Usage Guidelines

- Teachers can register and log in to create and manage subjects.
- Students can register and log in to view their schedules and attendance records.
- Ensure that the fingerprint scanning hardware is properly configured and integrated with the application for attendance tracking.

## License

This project is licensed under the MIT License.