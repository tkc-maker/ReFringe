<?php
ini_set('display_errors', 0);          // Hide errors from UI
ini_set('log_errors', 1);              // Enable error logging
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/TEST/ErrorLogs' . '/php-error.log'); // Log file path
error_reporting(E_ALL);

class Constants{
    //PostgreSQL Database Constants:
    //------------------------------

/*
    public static $dsn = 'pgsql:host=localhost;dbname=wwwolamy_refringedb';
    public static $db_username = 'wwwolamy_tcarpenter';
    public static $db_password = 'R0e1F1r9I2n5Ge';
 */
    public static $dsn = 'pgsql:host=localhost;port=5432;dbname=postgres';
    public static $db_username = 'thomascarpenter';
    public static $db_password = 'samoht';

}