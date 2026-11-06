<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location: ../authentication/login.php");
    exit;
}

include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

if(!isset($_GET['id']))
{
    header("Location:view.php");
    exit;
}

$id=$_GET['id'];

$sql="

SELECT *

FROM LOCATIONZONE

WHERE

LOCATION_ID=:id

";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

📍 Location Details

</h2>

<p class="text-muted">

View complete location information.

</p>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="250">

Location Name

</th>

<td>

<?php echo $row['LOCATION_NAME']; ?>

</td>

</tr>

<tr>

<th>

Road Name

</th>

<td>

<?php echo $row['ROAD_NAME']; ?>

</td>

</tr>

<tr>

<th>

District / State

</th>

<td>

<?php echo $row['DISTRICT_STATE']; ?>

</td>

</tr>

<tr>

<th>

Postcode

</th>

<td>

<?php echo $row['POSTCODE']; ?>

</td>

</tr>

<tr>

<th>

Traffic Volume

</th>

<td>

<?php echo $row['TRAFFIC_VOLUME']; ?>

</td>

</tr>

<tr>

<th>

Risk Priority

</th>

<td>

<?php echo $row['RISK_PRIORITY']; ?>

</td>

</tr>

</table>

<a

href="edit.php?id=<?php echo $row['LOCATION_ID']; ?>"

class="btn btn-warning">

<i class="fa-solid fa-pen"></i>

Edit

</a>

<a

href="view.php"

class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

<?php

include("../includes/footer.php");

?>