<?php
// --- DATABASE CREDENTIALS ---
$serverName = "localhost"; // Use "localhost" instead of "."
$connectionOptions = [
    "Database" => "hiptime",
    "Uid" => "sa",
    "PWD" => "@dm1n",
    "Encrypt" => false, // Use false (boolean) instead of 0
    "TrustServerCertificate" => false, // Use true (boolean) instead of 1
    "CharacterSet" => "UTF-8"
];

// --- CONNECTION AND ERROR HANDLING ---

/**
 * A function to format and display SQL server errors.
 *
 * @param array $errors The error array from sqlsrv_errors().
 */
function formatErrors($errors)
{
    // Display errors
    echo "<h1>SQL Error:</h1>";
    echo "Error information: <br/>";
    foreach ($errors as $error) {
        echo "SQLSTATE: " . $error['SQLSTATE'] . "<br/>";
        echo "Code: " . $error['code'] . "<br/>";
        echo "Message: " . $error['message'] . "<br/>";
    }
}

// Establishes the connection and stores it in the $conn variable.
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check if the connection failed. If it did, display errors and stop the script.
if ($conn === false) {
    // The die() function stops the script completely.
    die(formatErrors(sqlsrv_errors()));
}

// If the script reaches this point, the connection was successful.
// The $conn variable is now available to any script that includes this file.
