# UNIQUATE - University Hall Booking System 

A comprehensive web application for booking university halls and event spaces, developed as part of CS2001 Project by Group 59 at the University of Colombo.

## 🎯 Overview

The University Hall Booking System is a user-friendly web application that streamlines the process of booking university halls and rooms for various events including conferences, meetings, celebrations, and academic activities. The system provides separate interfaces for regular users and administrators, ensuring efficient management of bookings and resources.

## ✨ Features

### For Users
- **User Registration & Authentication**: Secure user account creation and login
- **Hall Search & Filtering**: Search halls by name, location, capacity, type, and date
- **Booking Management**: Create, view, and cancel bookings (with time restrictions)
- **Real-time Availability**: Check hall availability before booking
- **Cost Calculation**: Automatic calculation of booking costs based on duration
- **Booking History**: View all past and upcoming bookings with detailed information
- **Responsive Design**: Mobile-friendly interface with modern UI

### For Administrators
- **Admin Dashboard**: Comprehensive overview with quick statistics
- **Hall Management**: Add, edit, delete halls with image upload functionality
- **Booking Management**: Approve, reject, or modify booking requests
- **User Management**: Create, edit, and manage user accounts
- **Reports**: Generate detailed reports on bookings, users, and halls
- **System Monitoring**: Track system usage and generate insights

### General Features
- **Role-based Access Control**: Different permissions for users and administrators
- **Image Upload**: Support for hall images with secure file handling
- **Input Validation**: Comprehensive form validation and error handling
- **Time Conflict Prevention**: Automatic detection of booking conflicts
- **Status Tracking**: Real-time booking status updates (Pending, Confirmed, Cancelled)
- **Help & Support**: Comprehensive help documentation and FAQ

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache (via XAMPP/MAMP)
- **Architecture**: MVC-like structure with procedural PHP

## 📦 Installation

### Prerequisites
- XAMPP (Windows/Linux) or MAMP (Mac)
- PHP 7.4 or higher (included in XAMPP/MAMP)
- MySQL 5.7 or higher (included in XAMPP/MAMP)


### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/thinulah/Uniquate
   cd uniquate
   ```

2. **Set up web server**
   - Place the project folder in your web server directory (`htdocs` for XAMPP/MAMP)
   - Ensure PHP and MySQL services are running (Start servers - [Apache + MySQL])

3. **Configure database connection**
   Go to phpMyAdmin:
	- •	XAMPP → http://localhost/phpmyadmin/
	- •	MAMP → http://localhost:8888/phpMyAdmin/
   
   - Update database credentials:
   ```php
   $host = "localhost";
   $database_name = "hall_booking_system";
   $username = "your_username";
   $password = "your_password";
   ```

## 🗄️ Database Setup

1. **Create database**
   ```sql
   CREATE DATABASE hall_booking_system;
   ```

2. **Import database structure**
   ```sql
   USE hall_booking_system;
   
   -- Users table
   CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       username VARCHAR(50) UNIQUE NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       password VARCHAR(255) NOT NULL,
       role ENUM('user', 'admin') DEFAULT 'user',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   
   -- Halls table
   CREATE TABLE halls (
       id INT PRIMARY KEY AUTO_INCREMENT,
       name VARCHAR(100) NOT NULL,
       description TEXT,
       capacity INT NOT NULL,
       location VARCHAR(100) NOT NULL,
       price_per_hour DECIMAL(10,2) NOT NULL,
       image_url VARCHAR(255),
       amenities TEXT,
       type VARCHAR(50),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   
   -- Bookings table
   CREATE TABLE bookings (
       id INT PRIMARY KEY AUTO_INCREMENT,
       user_id INT NOT NULL,
       hall_id INT NOT NULL,
       booking_date DATE NOT NULL,
       start_time TIME NOT NULL,
       end_time TIME NOT NULL,
       purpose TEXT,
       total_amount DECIMAL(10,2) NOT NULL,
       status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
       FOREIGN KEY (hall_id) REFERENCES halls(id) ON DELETE RESTRICT
   );
   ```

3. **Create admin user**
   ```sql
   INSERT INTO users (username, email, password, role) 
   VALUES ('admin', 'admin@university.edu', '$2y$10$hash_here', 'admin');
   ```

## 🚀 Usage

### For Users
1. **Registration**: Visit `/register.php` to create a new account
2. **Login**: Use `/login.php` to access your account
3. **Browse Halls**: View available halls at `/halls.php`
4. **Search**: Use the search functionality to find specific halls
5. **Book a Hall**: Click "Book Now" on any hall to make a reservation
6. **Manage Bookings**: View your bookings at `/my_bookings.php`

### For Administrators
1. **Admin Login**: Login with admin credentials
2. **Dashboard**: Access admin panel at `/admin.php`
3. **Manage Halls**: Add, edit, or delete halls
4. **Process Bookings**: Approve or reject booking requests
5. **User Management**: Create and manage user accounts
6. **Generate Reports**: View system reports at `/admin_reports.php`

## 📁 Project Structure

```
Uniquate/
├── auth/
│   └── session.php                 # Session management
├── classes/
│   ├── Booking.php                # Booking operations
│   ├── Hall.php                   # Hall management
│   └── User.php                   # User operations
├── config/
│   └── database.php               # Database configuration
├── images/
│   ├── team/                      # Team member photos
│   ├── hall1.jpeg                 # Sample hall images
│   └── logo1.png                  # Application logo
├── includes/
│   ├── header.php                 # Common header
│   └── footer.php                 # Common footer
├── styles/
│   └── styles.css                 # Application styles
├── uploads/                       # User uploaded images
├── admin.php                      # Admin dashboard
├── admin_reports.php              # Admin reports
├── book_hall.php                  # Hall booking form
├── halls.php                      # Hall listing
├── help.php                       # Help documentation
├── index.php                      # Homepage
├── login.php                      # User login
├── logout.php                     # User logout
├── my_bookings.php                # User bookings
├── register.php                   # User registration
├── search.php                     # Search functionality
├── team.php                       # Team information
└── README.md                      # This file
```

### Security Features
- Password hashing using PHP's `password_hash()`
- SQL injection prevention with prepared statements
- File upload validation and sanitization
- Session-based authentication
- Role-based access control

## 📝 API Documentation

### Key Functions

#### User Management
```php
createUser($conn, $username, $password, $email, $role)
loginUser($conn, $username, $password)
updateUser($conn, $id, $username, $email, $role, $password)
```

#### Hall Management
```php
createHall($conn, $name, $description, $capacity, $location, $price, $amenities, $type, $image)
getAllHalls($conn)
getHallById($conn, $id)
updateHall($conn, $id, ...)
searchHalls($conn, $search_term, $capacity, $type)
```

#### Booking Management
```php
createBooking($conn, $user_id, $hall_id, $date, $start_time, $end_time, $purpose, $amount, $status)
isHallAvailable($conn, $hall_id, $date, $start_time, $end_time)
getUserBookings($conn, $user_id)
updateBookingStatus($conn, $booking_id, $status)
canModifyBooking($conn, $booking_id)
```

## 👥 Team

**Group 59 - CS2001 Project**
- **Thinula Harischandra** (S17478) - Team Leader & Developer
- **Sasmika Gunawardhana** (S17474) - Developer  
- **Rasini Hansika** (S17476) - Developer
- **Devindi Hansani** (S17475) - Developer
- **Nilakshi Gunasena** (S17473) - Developer

---

**© 2024 Uniquate - University Hall Booking System**  
*Developed by Group 59 | University of Colombo*
