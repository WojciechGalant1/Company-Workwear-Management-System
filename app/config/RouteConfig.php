<?php

/**
 * Central configuration for all route mappings in the application
 */
class RouteConfig {
    /**
     * Get routes mapping clean URLs to view files
     */
    public static function getRoutes() {
        return array(
            '/' => './views/issue_clothing.php',
            '/issue-clothing' => './views/issue_clothing.php',
            '/order-history' => './views/order_history.php',
            '/clothing-history' => './views/clothing_history.php',
            '/issue-history' => './views/issue_history.php',
            '/employees' => './views/employee_list.php',
            '/warehouse' => './views/warehouse_list.php',
            '/add-order' => './views/add_order.php',
            '/report' => './views/raport.php',
            '/add-employee' => './views/add_employee.php',
            '/login' => './views/auth/login.php'
        );
    }
    
    /**
     * Get mapping from clean URLs to page filenames (without path)
     */
    public static function getPageMap() {
        return array(
            '/' => 'issue_clothing.php',
            '/issue-clothing' => 'issue_clothing.php',
            '/order-history' => 'order_history.php',
            '/clothing-history' => 'clothing_history.php',
            '/issue-history' => 'issue_history.php',
            '/employees' => 'employee_list.php',
            '/warehouse' => 'warehouse_list.php',
            '/add-order' => 'add_order.php',
            '/report' => 'raport.php',
            '/add-employee' => 'add_employee.php',
            '/login' => 'login.php'
        );
    }
    
    /**
     * Get mapping from page filenames to clean URLs
     */
    public static function getUrlMap() {
        return array(
            'issue_history.php' => '/issue-history',
            'order_history.php' => '/order-history',
            'clothing_history.php' => '/clothing-history',
            'employee_list.php' => '/employees',
            'warehouse_list.php' => '/warehouse',
            'add_order.php' => '/add-order',
            'issue_clothing.php' => '/issue-clothing',
            'raport.php' => '/report',
            'add_employee.php' => '/add-employee',
            'login.php' => '/login'
        );
    }
} 