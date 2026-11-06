
<?php

$username = "YOUR_ORACLE_USERNAME";
$password = "YOUR_ORACLE_PASSWORD";
$connection_string = "localhost/FREEPDB1";

$conn = oci_connect($username, $password, $connection_string);

if (!$conn) {

    $e = oci_error();

    die("Oracle Connection Failed : " . $e['message']);

}

?>
