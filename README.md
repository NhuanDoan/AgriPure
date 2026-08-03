# AgriPure

AgriPure is a full-stack web application for connecting consumers with clean, certified agricultural products. The system supports product browsing, shopping cart checkout, farm certification workflows, blockchain-based product traceability, and an AI-powered assistant for customer support.

This project is designed as a practical platform for modern agriculture, combining e-commerce, inspection management, and trust-building features for both farmers and consumers.

## 1. Project Overview

AgriPure aims to provide a transparent digital ecosystem for agricultural products by combining:

- Product marketplace and online ordering
- Farm registration and certification management
- Inspection workflow for agricultural quality control
- Product traceability through blockchain-style record handling
- AI chatbot assistance for user support
- Real-time chat and order tracking

## 2. Key Features

### Customer features
- Browse agricultural products
- Add products to cart and place orders
- Choose payment methods including COD and VNPay
- Chat with support team or other users
- View order status and history

### Farmer features
- Register and manage farm information
- Submit certification requests for products/farms
- Manage product listings
- Create blockchain traceability records for products
- View inspection and certificate results

### Inspection officer features
- Review inspection requests
- Fill in inspection results and evaluation details
- Generate certificates based on inspection outcomes
- View certification history

### Additional features
- AI chatbot powered by Gemini for product-related inquiries
- QR-code style product traceability support
- Responsive UI built with Bootstrap

## 3. Technology Stack

### Backend
- PHP
- MySQL / MariaDB
- mysqli extension
- Session-based authentication

### Frontend
- HTML5
- CSS3
- JavaScript
- Bootstrap
- AJAX for dynamic page interactions

### Integrations
- VNPay for online payment flow
- Google Gemini API for chatbot responses
- QRCode library for traceability-related features

## 4. Project Structure

```text
AgriPure/
├── controllers/         # Request handlers and business logic
├── core/                # Core bootstrap and routing utilities
├── models/              # Database connection and shared model logic
├── views/               # UI templates and page views
├── assets/              # CSS, JS, images, and static resources
├── vnpay_php/           # VNPay payment integration demo/code
└── index.php            # Main entry point
```

## 5. Prerequisites

Before running the project, make sure the following tools are installed:

- PHP 8.x or newer (recommended)
- MySQL or MariaDB
- Apache / Nginx with PHP support
- XAMPP, WAMP, MAMP, or a similar local server stack
- Git

## 6. Installation and Setup

### Step 1: Clone the repository

```bash
git clone <repository-url>
cd AgriPure
```

### Step 2: Start your local web server

If you are using XAMPP / WAMP / MAMP:
- Place the project folder inside the server root directory
- Start Apache and MySQL

Example for XAMPP:
- Copy the project to htdocs/AgriPure
- Open http://localhost/AgriPure

### Step 3: Create the database

Create a MySQL database named:

```sql
CREATE DATABASE agripure;
```

Then update the database configuration in:

```text
models/config.php
```

Default configuration currently expects:

```php
$localname = "localhost";
$username = "root";
$password = "";
$dbname = "agripure";
```

Adjust these values according to your local environment.

### Step 4: Run the application

Open your browser and visit:

```text
http://localhost/AgriPure
```

## 7. Configuration Notes

### Database connection
The application uses a direct MySQL connection via mysqli. If your local setup uses a different username/password, update the values in the database config file.

### Chatbot integration
The chatbot feature calls the Gemini API from the server-side controller. If you want to use it locally, make sure the API key is valid and properly configured.

> For production, it is recommended to move the API key and sensitive configuration into environment variables instead of hardcoding them in source files.

### Payment integration
VNPay integration is included under the vnpay_php folder. You may need to update payment configuration values for real transaction testing.

## 8. User Roles

The application uses role-based access control for different user types:

- Role 1: Inspection officer
- Role 2: Farmer / farm owner
- Role 4: Customer / general user

Each role has access to different pages and actions in the navigation menu.

## 9. Development Notes

This project is built using procedural PHP with page-based includes rather than a modern framework such as Laravel or Symfony. That makes it easy to understand for learning and small-scale deployment, but it also means that:

- The codebase would benefit from better modularization over time
- Security hardening should be prioritized for production use
- Database access and configuration should be centralized more cleanly

## 10. Suggested Next Improvements

Potential improvements for future versions:

- Migrate to a modern PHP framework
- Add proper authentication and authorization middleware
- Introduce a proper ORM layer
- Add automated tests
- Improve code structure and separate service layers
- Replace hardcoded credentials with environment-based configuration

## 11. License

This project does not yet include a formal public license. If you intend to redistribute or commercialize it, confirm the licensing terms with the project owner or team before doing so.

## 12. Contact

For questions or collaboration, please contact the project team maintaining this repository.
