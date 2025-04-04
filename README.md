# Smart Parent-Teacher Communication System

## Overview
The **Smart Parent-Teacher Communication System** is a web-based platform designed to enhance collaboration between teachers and parents. It provides a structured way for teachers to share students' progress, behavior, performance, and feedback with parents in real-time. This ensures that parents stay informed and involved in their child's education.

## Features
- **Admin Dashboard**: Approve or reject teacher registrations.
- **Teacher Portal**: Manage students, provide feedback, and enter performance data.
- **Parent Portal**: View child's progress, feedback, and receive notifications.
- **Student Performance Tracking**: Teachers can record and update students' scores.
- **Notifications System**: Automated messages for parents regarding student updates.
- **Secure Authentication**: Role-based login system for admins, teachers, and parents.

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL

## Installation
### 1. Clone the Repository
```sh
git clone https://github.com/your-username/smart-parent-teacher.git
cd smart-parent-teacher
```

### 2. Set Up Database
- Create a MySQL database named `smart_parent_teacher`.
- Import the provided `database.sql` file.

### 3. Configure Database Connection
- Navigate to the `config/` folder.
- Open `config.php` and update database credentials:
```php
<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "smart_parent_teacher";
?>
```

### 4. Start the Server
- If using XAMPP, move the project to `htdocs/` and start Apache & MySQL.
- Open the browser and go to:
  ```
  http://localhost/smart-parent-teacher/
  ```

## Usage
1. **Admin** registers and approves teachers.
2. **Teachers** add students and update their performance.
3. **Parents** register using their child's student ID and receive updates.

## Contribution
Feel free to contribute! Fork the repo, make changes, and submit a pull request.

## License
This project is open-source under the [MIT License](LICENSE).

