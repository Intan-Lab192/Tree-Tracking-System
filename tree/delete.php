<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../authentication/login.php");
    exit;
}

include("../config/db.php");

if(!isset($_GET['id']))
{
    header("Location:view.php");
    exit;
}

$id=$_GET['id'];

$sql="DELETE FROM TREE
WHERE TREE_ID=:id";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

oci_commit($conn);

header("Location:view.php");

exit;
?>