<?php

// Test connection

$conn = new PDO("sqlsrv:Server=mysql.diskcoversystem.com;Database=Diskcover_Prismanet", "sa", "disk2017Cover");

if($conn)
    echo "Connected!";

$conn->setAttribute(constant('PDO::SQLSRV_ATTR_DIRECT_QUERY'), true);


/*

PARA MAS INFORMACION:

https://docs.microsoft.com/en-us/sql/connect/php/programming-guide-for-php-sql-driver?view=sql-server-2017

*/

?>

