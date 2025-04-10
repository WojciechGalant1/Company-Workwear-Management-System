<div id="top"></div>

<div align="center">
  <h1>COMPANY CLOTHING MANAGEMENT SYSTEM</h1>
  
  <img alt="PHP" src="https://img.shields.io/badge/PHP-777BB4.svg?style=flat&logo=PHP&logoColor=white">
  <img alt="JavaScript" src="https://img.shields.io/badge/JavaScript-F7DF1E.svg?style=flat&logo=JavaScript&logoColor=black">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-4479A1.svg?style=flat&logo=MySQL&logoColor=white">
  <img alt="Bootstrap" src="https://img.shields.io/badge/Bootstrap-7952B3.svg?style=flat&logo=Bootstrap&logoColor=white">
  <img alt="jQuery" src="https://img.shields.io/badge/jQuery-0769AD.svg?style=flat&logo=jQuery&logoColor=white">
  <img alt="CSS" src="https://img.shields.io/badge/CSS3-1572B6.svg?style=flat&logo=CSS3&logoColor=white">
  <img alt="HTML" src="https://img.shields.io/badge/HTML5-E34F26.svg?style=flat&logo=HTML5&logoColor=white">
</div>

<br>

<hr>

## Table of Contents
<ul>
  <li><a href="#overview">Overview</a></li>
  <li><a href="#system-requirements">System Requirements</a></li>
  <li><a href="#project-structure">Project Structure</a></li>
  <li><a href="#key-components">Key Components</a></li>
  <li><a href="#installation">Installation</a></li>
  <li><a href="#configuration">Configuration</a></li>
  <li><a href="#usage">Usage</a></li>
  <li><a href="#code-architecture">Code Architecture</a></li>
</ul>

<hr>

<div id="overview"></div>

## Overview

<p>The <strong>Company Clothing Management System</strong> is a comprehensive web application designed to streamline the management and distribution of work clothing within a company environment. The system enables efficient inventory tracking, on-demand clothing distribution to employees, order management, and detailed reporting.</p>

### Features
<ul>
  <li><strong>Clothing inventory management</strong> with size tracking</li>
  <li><strong>Employee profile management</strong> for tracking assignments</li>
  <li><strong>Clothing request and distribution workflow</strong></li>
  <li><strong>Real-time inventory status monitoring</strong></li>
  <li><strong>Automatic shortage notifications</strong></li>
  <li><strong>Historical distribution records and reporting</strong></li>
  <li><strong>Role-based access control</strong></li>
</ul>

<p>This system is optimized for PHP 5.3 environments and designed for fast performance with minimal resource requirements, making it suitable for deployment across various company infrastructures.</p>

<hr>

<div id="system-requirements"></div>

## System Requirements

<ul>
  <li><strong>PHP 5.3</strong> or higher</li>
  <li><strong>Web server</strong> (Apache/Nginx)</li>
  <li><strong>MySQL/MariaDB</strong> database</li>
  <li><strong>Modern web browser</strong> with JavaScript enabled</li>
</ul>

<hr>

<div id="project-structure"></div>

## Project Structure

```
project/
├── app/                    # Application core
│   ├── config/             # Configuration files
│   │   ├── RouteConfig.php # Route definitions
│   │   └── modules.php     # JavaScript module dependencies
│   ├── controllers/        # Controller classes
│   ├── database/           # Database connection and queries
│   ├── forms/              # Form handling
│   ├── helpers/            # Helper classes
│   │   ├── NavBuilder.php  # Navigation builder
│   │   └── UrlHelper.php   # URL processing utilities
│   ├── models/             # Data models
│   ├── views/              # View templates
│   └── Router.php          # URL routing system
├── handlers/               # AJAX request handlers
├── img/                    # Image assets
├── layout/                 # Layout templates
│   ├── ClassMenu.php       # Navigation menu
│   ├── footer.php          # Page footer
│   ├── header.php          # Page header
│   └── navbar.css          # Navigation styling
├── log/                    # Logging and session management
│   └── sesja/              # Session handling
├── script/                 # JavaScript modules
│   ├── AlertManager.js     # Alert notifications
│   ├── AnulujWydanie.js    # Cancel distribution
│   ├── ChangeStatus.js     # Status management
│   ├── CheckUbranie.js     # Clothing validation
│   ├── EdycjaUbranie.js    # Clothing editing
│   ├── HistoriaUbranSzczegoly.js # Clothing history details
│   ├── ModalEdytujPracownika.js  # Employee edit modal
│   ├── ModalWydajUbranie.js      # Clothing distribution modal
│   ├── ProductSuggestions.js     # Product autocomplete
│   ├── RedirectStatus.js         # Status redirection
│   ├── UbraniaKod.js             # Clothing code handling
│   ├── UbraniaManager.js         # Clothing management
│   ├── UserSuggestions.js        # User autocomplete
│   └── ZniszczUbranie.js         # Clothing disposal
├── styl/                   # CSS and UI libraries (Bootstrap)
├── .htaccess               # Apache configuration
├── App.js                  # Main application JavaScript
├── index.php               # Application entry point
└── README.md               # This documentation
```

<hr>

<div id="key-components"></div>

## Key Components

### Backend (PHP)

<ul>
  <li><strong>Router System:</strong> Handles URL routing using clean URLs</li>
  <li><strong>MVC Architecture:</strong> Separates application into Models, Views, and Controllers</li>
  <li><strong>Session Management:</strong> Handles user authentication and access control</li>
  <li><strong>Database Layer:</strong> Manages database connections and queries</li>
</ul>

### Frontend (JavaScript)

<ul>
  <li><strong>Module System:</strong> Uses ES6 modules for code organization</li>
  <li><strong>AlertManager:</strong> Provides user feedback through notifications</li>
  <li><strong>UbraniaManager:</strong> Core module for clothing management</li>
  <li><strong>Form Handling:</strong> AJAX form submission with validation</li>
  <li><strong>Modal Dialogs:</strong> Interactive dialogs for user actions</li>
</ul>

<hr>

<div id="installation"></div>

## Installation

<ol>
  <li>Clone the repository to your web server's document root</li>
  <li>Configure your web server to point to the project directory</li>
  <li>Configure database settings in <code>app/database/Database.php</code></li>
  <li>Ensure the web server has appropriate permissions</li>
  <li>Access the application through your web browser</li>
</ol>

<hr>

<div id="configuration"></div>

## Configuration

### Route Configuration

<p>Routes are centralized in <code>app/config/RouteConfig.php</code>. This file maps clean URLs to PHP views:</p>

```php
'/dodaj-zamowienie' => './app/views/dodaj_zamowienie.php'
```

### Module Configuration

<p>JavaScript module dependencies for each page are defined in <code>app/config/modules.php</code>:</p>

```php
'wydaj_ubranie.php' => 'UbraniaManager,AlertManager,UserSuggestions,ModalWydajUbranie,ChangeStatus'
```

<hr>

<div id="usage"></div>

## Usage

### User Access Levels

<p>The system supports different access levels:</p>
<ul>
  <li><strong>Level 1+:</strong> Basic distribution operations</li>
  <li><strong>Level 3+:</strong> Inventory management, ordering</li>
  <li><strong>Level 5+:</strong> Complete system access including reports and employee management</li>
</ul>

### Core Functionality

<ol>
  <li><strong>Clothing Distribution:</strong> Issue clothing to employees</li>
  <li><strong>Inventory Management:</strong> Track clothing types, sizes, and quantities</li>
  <li><strong>Employee Management:</strong> Manage employee information</li>
  <li><strong>Order Processing:</strong> Place and track clothing orders</li>
  <li><strong>Reporting:</strong> Generate reports on clothing distribution</li>
</ol>

<hr>

<div id="code-architecture"></div>

## Code Architecture

### URL Processing

<p>The application uses a clean URL approach with:</p>
<ul>
  <li><code>UrlHelper.php</code>: Processes and normalizes URLs</li>
  <li><code>Router.php</code>: Maps URLs to appropriate views</li>
  <li><code>RouteConfig.php</code>: Centralizes route definitions</li>
</ul>

### Navigation System

<p>Menu generation is handled by:</p>
<ul>
  <li><code>ClassMenu.php</code>: Main navigation container</li>
  <li><code>NavBuilder.php</code>: Builds navigation items based on user access level</li>
</ul>

### JavaScript Architecture

<p>The frontend uses a modular approach:</p>
<ul>
  <li>Modules are loaded based on page requirements</li>
  <li><code>App.js</code> orchestrates module initialization</li>
  <li>Event-driven communication between modules</li>
</ul>

<hr>

<div id="development-guidelines"></div>

## 



<hr>

<div align="right">
  <a href="#top">⬆ Return to top</a>
</div>
