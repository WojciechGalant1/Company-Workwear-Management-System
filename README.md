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
  <li><a href="#development-guidelines">Development Guidelines</a></li>
</ul>

<hr>

<div id="overview"></div>

## Overview

<p>The <strong>Company Clothing Management System</strong> is a comprehensive web application designed to streamline the management and distribution of work clothing within a company environment. The system enables efficient inventory tracking, on-demand clothing distribution to employees, order management, and detailed reporting.</p>

### Features
<ul>
  <li><strong>Clothing inventory management</strong> with size tracking and minimum stock levels</li>
  <li><strong>Barcode scanning support</strong> for quick product identification</li>
  <li><strong>Order tracking and history</strong> with detailed information</li>
  <li><strong>Employee clothing distribution</strong> with complete history records</li>
  <li><strong>Automatic low inventory notifications</strong> when stock falls below minimum levels</li>
  <li><strong>Clothing expiration tracking</strong> with automated reporting</li>
  <li><strong>Damage reporting and tracking</strong> for returned items</li>
  <li><strong>Employee management</strong> with status tracking</li>
  <li><strong>Comprehensive search functionality</strong> across all system components</li>
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
  <li><strong>Barcode scanner</strong> (optional, for enhanced functionality)</li>
</ul>

<hr>

<div id="project-structure"></div>

## 📊 Project Structure

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
  <li>Configure database settings in <code>app/database/config.php</code></li>
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

### Main System Functions

<ol>
  <li><strong>Adding Orders</strong>
    <ul>
      <li>Add new products with barcode, name, size, quantity, and minimum quantity</li>
      <li>Add existing products via barcode scanning with auto-filled fields</li>
      <li>Handle products with new barcodes but existing data</li>
    </ul>
  </li>
  <li><strong>Clothing Distribution</strong>
    <ul>
      <li>Issue clothing to employees by name</li>
      <li>Add items via barcode scanner or manual selection</li>
      <li>Record distribution details with optional notes</li>
    </ul>
  </li>
  <li><strong>Inventory Management</strong>
    <ul>
      <li>View all available products with real-time stock levels</li>
      <li>Edit product details including name, size, quantity, and minimum stock</li>
      <li>Receive automatic notifications for low inventory products</li>
    </ul>
  </li>
  <li><strong>Distribution History</strong>
    <ul>
      <li>View complete distribution history by employee</li>
      <li>Cancel distributions within 30 days for returned items</li>
      <li>Mark items as damaged when returned in poor condition</li>
      <li>Add or remove items from reports</li>
    </ul>
  </li>
  <li><strong>Clothing History</strong>
    <ul>
      <li>Access detailed information about all issued clothing</li>
      <li>Search items by name, size, or employee</li>
      <li>History maintained for up to one year from issue date</li>
    </ul>
  </li>
  <li><strong>Distribution Reports</strong>
    <ul>
      <li>View items with approaching expiration dates (within two months)</li>
      <li>Manage items in reports with removal and restoration options</li>
      <li>Track summary of all items issued from reports</li>
    </ul>
  </li>
  <li><strong>Employee Management</strong>
    <ul>
      <li>Add new employees with name and position information</li>
      <li>View and search complete employee list</li>
      <li>Edit employee details including status (active/inactive)</li>
    </ul>
  </li>
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

## Development Guidelines

<ol>
  <li>Add new routes in <code>app/config/RouteConfig.php</code></li>
  <li>Register JavaScript modules in <code>app/config/modules.php</code></li>
  <li>Follow existing code conventions</li>
  <li>Update documentation when adding features</li>
</ol>

<hr>

<div align="right">
  <a href="#top">⬆ Return to top</a>
</div>
