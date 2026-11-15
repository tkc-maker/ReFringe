<?php
ini_set('display_errors', 0);          // Hide errors from UI
ini_set('log_errors', 1);              // Enable error logging
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/TEST/ErrorLogs' . '/php-error.log'); // Log file path
error_reporting(E_ALL);

class Constants{
    //PostgreSQL Database Constants:
    //------------------------------
    public static $dsn = 'pgsql:host=your_host;port=your_port;dbname=your_db_name';
    public static $db_username = 'your_db_username';
    public static $db_password = 'your_db_password';

}