<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Database connection
    $dsn = "pgsql:host=localhost;dbname=wwwolamy_refringedb";
    $db = new PDO($dsn, 'wwwolamy_tcarpenter', 'R0e1F1r9I2n5Ge');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the statement
    $stmt = $db->prepare("SELECT \"first_name\", \"last_name\" FROM \"Users\" WHERE \"ID\" = :id");
    $stmt->execute([":id" => 2]); // Example record ID

    // Fetch the result
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the row exists
    if ($row) {
//        print_r($row);
        echo $row['first_name'] . ' ' . $row['last_name'];
    } else {
        echo "No user found with the given ID.";
    }

    // Close the connection
    $db = null;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
