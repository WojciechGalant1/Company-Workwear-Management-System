<div align="center">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-777BB4.svg?style=for-the-badge&logo=PHP&logoColor=white">
  <img alt="JavaScript" src="https://img.shields.io/badge/JavaScript-F7DF1E.svg?style=for-the-badge&logo=JavaScript&logoColor=black">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-4479A1.svg?style=for-the-badge&logo=MySQL&logoColor=white">
  <img alt="Bootstrap" src="https://img.shields.io/badge/Bootstrap-7952B3.svg?style=for-the-badge&logo=Bootstrap&logoColor=white">
  <img alt="jQuery" src="https://img.shields.io/badge/jQuery-0769AD.svg?style=for-the-badge&logo=jQuery&logoColor=white">
  <img alt="HTML" src="https://img.shields.io/badge/HTML5-E34F26.svg?style=for-the-badge&logo=HTML5&logoColor=white">
  <img alt="CSS" src="https://img.shields.io/badge/CSS3-1572B6.svg?style=for-the-badge&logo=CSS3&logoColor=white">
</div>

<br />
<div align="center">
  <h1 align="center">Company Workwear Management System</h3>
  <p align="center">
    <br />
    <a href="https://company-clothing-management-system.ct.ws/log/logowanie.php?i=1">View Demo</a>
    &middot;
    <a href=""><strong>Polish version »</strong></a>
  </p>
</div>

## Table of Contents
- [Overview](##-Overview)
- [Key Features](###-Key-Features)
- [Technology Stack](##-Technology-Stack)
- [Project Structure](##-Project-Structure-(Simplified))
- [System Modules](##-System-Modules)
- [My Role & Responsibilities](##-My-Role-&-Responsibilities)
- [Future Development](##-Potential-Enhancements-&-Future-Development)



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
> **Warning**
> Barcode scanners must be configured to automatically append an "Enter" keystroke after each scan for proper form submission and system interaction.

##  Technology Stack

|Layer|Tech|
|:-|:-|
|Backend|PHP(custom MVC), REST-style endpoints|
|Frontend|JavaScript (ES6), Bootstrap, jQuery|
|Database|MySQL (relational, optimized queries)|
|Performance|Designed for low-resource deployment|
> **Note**
> Optimized for performance in PHP 5.3 environments due to infrastructure constraints at the time of development


##  Project Structure (Simplified)

```
project/
├── app/                    # Application core
│   ├── controllers/        # Business logic controllers
│   ├── models/             # Data models
│   ├── config/             # Configuration files
│   ├── database/           # Database connection 
│   ├── forms/              # Form processing handlers
│   └── helpers/            # Utility functions
├── views/                  # View templates
├── handlers/               # AJAX request handlers
├── img/                    # Image assets
├── layout/                 # Layout templates
├── log/                    # Logging and session management
├── script/                 # JavaScript modules
├── styl/                   # CSS stylesheets
├── .htaccess               # Apache configuration
├── App.js                  # Main application JavaScript
└── index.php               # Application entry point
```

##  System Modules

|Area|Description|
|:-|:-|
|Orders|Add clothing items (manually or via barcode) with metadata|
|Distributions|Assign gear to employees with full history + returns/damage logging|
|Inventory|Search, sort, update, and receive alerts on low stock|
|Employee Mgmt|View/update employee info with distribution linkages|
|Expiration Reports|Track upcoming renewals and automate replacements|
|Access Control|Define admin/staff roles with granular permission levels|

## Potential Enhancements & Future Development

- Codebase Modernization – Upgrade PHP version and refactor legacy components for modern standards (e.g., PHP 8+, namespaces, Composer)
- Multi-language Support – Implement English-language version for broader usability
- Mobile Optimization – Enhance touch interactions and responsive views for tablet/handheld use in warehouse environments
- API Integration – Introduce REST API endpoints for external system sync (e.g., ERP or HR software)
- Batch Processing – Enable bulk import/export of inventory data via CSV 
- Analytics Dashboard – Visual performance indicators and usage statistics over time

## My Role & Responsibilities

- Designing and implementing a custom MVC framework
- Architecting the database schema and writing optimized SQL queries
- Building full CRUD interfaces with responsive design
- Integrating barcode scanning into workflows
- Developing a role-based authentication system
- Collaborating with company staff to shape system workflows
- Conducted testing and validation in collaboration with company staff
- Deployed and documented the system for long-term internal use


