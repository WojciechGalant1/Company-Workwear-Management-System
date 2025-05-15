# Company Workwear Management System

<div align="center">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-777BB4.svg?style=for-the-badge&logo=PHP&logoColor=white">
  <img alt="JavaScript" src="https://img.shields.io/badge/JavaScript-F7DF1E.svg?style=for-the-badge&logo=JavaScript&logoColor=black">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-4479A1.svg?style=for-the-badge&logo=MySQL&logoColor=white">
  <img alt="Bootstrap" src="https://img.shields.io/badge/Bootstrap-7952B3.svg?style=for-the-badge&logo=Bootstrap&logoColor=white">
  <img alt="jQuery" src="https://img.shields.io/badge/jQuery-0769AD.svg?style=for-the-badge&logo=jQuery&logoColor=white">
  <img alt="HTML" src="https://img.shields.io/badge/HTML5-E34F26.svg?style=for-the-badge&logo=HTML5&logoColor=white">
  <img alt="CSS" src="https://img.shields.io/badge/CSS3-1572B6.svg?style=for-the-badge&logo=CSS3&logoColor=white">
</div>

##  Overview

A full-featured web platform designed to manage corporate workwear distribution throughout its entire lifecycle. Built from scratch during an internship to solve real-world problems for a manufacturing company — from inventory tracking to employee assignment and expiration-based replenishment.

###  Key Features

- **Inventory Management** - Track clothing items with detailed size, quantity, and barcode information
- **Employee Profiles** - Maintain comprehensive employee records with clothing assignment history
- **Role-based Security** - Role-based login and permission control for different user responsibilities
- **Real-time Dashboard** - Monitor inventory levels and advanced search/sorting
- **Smart Notifications** - Automatic alerts for low stock items and expiration-based reporting
- **Advanced Reporting** - Generate comprehensive reports on distribution and usage
- **Barcode integration** - Items added/edited via scanner input with auto-form submission

##  Technical Highlights

- **Clean Architecture** - MVC pattern for separation of concerns and code maintainability
- **Modern Frontend** - Responsive interface with Bootstrap and modular JavaScript
- **RESTful API Design** - Clean URL routing and AJAX-powered asynchronous operations
- **Optimized Performance** - Efficient database queries and lightweight frontend
- **Scalable Structure** - Modular code organization for easy maintenance and enhancement

##  Technology Stack

- **Backend:** PHP (custom MVC from scratch), REST-style endpoints
- **Frontend:** JavaScript (ES6 modules), Bootstrap, jQuery
- **Database:** MySQL with optimized query structure
- **Security:** Role-based access control, input validation
- **Performance:** Designed for low-resource environments

> **Warning**
> Barcode scanners must be configured to automatically append an "Enter" keystroke after each scan for proper form submission and system interaction.

##  Project Structure (Simplified)

```
project/
├── app/                    # Application core (MVC components)
├── handlers/               # AJAX request handlers
├── img/                    # Image assets
├── layout/                 # Layout templates
├── log/                    # Logging and session management
├── script/                 # JavaScript modules
├── .htaccess               # Apache configuration
├── App.js                  # Main application JavaScript
└── index.php               # Application entry point
```

##  Code Architecture Highlights

### Backend (PHP)

- **Custom Router** providing clean URLs and RESTful endpoints
- **MVC Pattern** with clear separation between data, presentation, and logic
- **Database Abstraction Layer** for secure and optimized queries
- **Authentication System** with role-based permissions

### Frontend (JavaScript)

- **Module System** using ES6 imports for code organization
- **Component-based Architecture** for UI elements
- **Event-driven Communication** between modules
- **Asynchronous Processing** with Fetch API and Promises
> **Note**
> Optimized for performance in PHP 5.3 environments due to infrastructure constraints at the time of development

##  Functional Modules

### Order Management
- Add new clothing products with barcode, name, size, quantity, and minimum quantity
- Add existing products via barcode scanning with automatic data population
- Add products with new barcodes linked to existing inventory items
- View complete order history including items added during inventory adjustments

### Clothing Distribution
- Issue clothing to employees with detailed tracking
- Record employee information for each distribution
- Add items by barcode scanning or manual selection
- Support for multiple items per distribution
- Optional notes for distribution context

### Inventory Management
- Real-time inventory monitoring with search and sort capabilities
- Edit product details including name, size, current quantity, and minimum quantity
- Low stock notifications when quantity falls below minimum threshold
- Comprehensive transaction history tracking all quantity changes

### Distribution History
- View complete distribution history by employee
- Cancel distributions within 30 days for returned undamaged items
- Mark items as damaged when returned in poor condition
- Add or remove items from the expiration report

### Clothing History
- Review details of all distributed clothing items
- Search by clothing name, size, or employee name
- View comprehensive distribution data up to one year

### Expiration Reporting
- Automatic tracking of clothing expiration dates
- Reports for items expiring within two months
- Streamlined replacement process with pre-filled forms
- Summary statistics for items issued from the expiration report

### Employee Management
- Add new employees with name, position and status
- View and search complete employee database
- Edit employee details and update employment status

---

## My Role & Responsibilities

- Designing and implementing a custom MVC framework
- Architecting the database schema and writing optimized SQL queries
- Building full CRUD interfaces with responsive design
- Integrating barcode scanning into workflows
- Developing a role-based authentication system
- Collaborating with company staff to shape system workflows

## Potential Enhancements & Future Development

- Codebase Modernization – Upgrade PHP version and refactor legacy components for modern standards (e.g., PHP 8+, namespaces, Composer)
- Multi-language Support – Implement English-language version for broader usability
- Mobile Optimization – Enhance touch interactions and responsive views for tablet/handheld use in warehouse environments
- API Integration – Introduce REST API endpoints for external system sync (e.g., ERP or HR software)
- Smart Suggestions – Use item history and employee size data to suggest clothing types and sizes automatically
- Batch Processing – Enable bulk import/export of inventory data via CSV or Excel
- Analytics Dashboard – Visual performance indicators and usage statistics over time

## Demo
