<?php
// Include the database connection file.
// require_once ensures it's included only once and will cause a fatal error if the file is missing.
//require_once 'db_connect.php';
require_once 'config/database.php';

// At this point, the $conn variable from db_connect.php is available for use.

// 1. Define the SQL query
$tsql = "SELECT * FROM [hiptime].[dbo].[Student]";

// 2. Execute the query using the $conn variable
$stmt = sqlsrv_query($conn, $tsql);

// 3. Handle query errors
if ($stmt === false) {
    die(formatErrors(sqlsrv_errors()));
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student List</title>
</head>

<body>

    <h1>Success Results:</h1>

    <?php
    // 4. Fetch and display the results
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<pre>';
        print_r($row);
        echo '</pre>';
    }
    ?>

</body>

</html>

<?php
// 5. Clean up resources
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>