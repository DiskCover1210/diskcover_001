<?php

$serverName="tcp:mysql.diskcoversystem.com,11433";
$connectionOptions=array(
        "Database"=>"DiskCover_Prismanet",
        "UID"=>"sa",
        "PWD"=>"disk2017Cover");

//Establishestheconnection
$conn = sqlsrv_connect($serverName, $connectionOptions);

//SelectQuery
$tsql = "SELECT @@Version as SQL_VERSION";

//Executes the query
$getResults = sqlsrv_query( $conn,$tsql);

//Errorhandling
if($getResults==FALSE)
        die(FormatErrors(sqlsrv_errors()));

?>
<h1>Results:</h1>

<?php

while($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
        echo ($row['SQL_VERSION']);
        echo ("<br/>");
}

sqlsrv_free_stmt($getResults);

function FormatErrors( $errors )
        {
        // Displayerrors:
        echo ("Error information: <br/>");

        foreach($errors as $error)
                {
                echo "SQLSTATE: " . $error['SQLSTATE'] . "<br/>";
                echo "Code:" . $error['code'] . "<br/>";
                echo "Message:" . $error['message'] . "<br/>";
                }
        }

?>

