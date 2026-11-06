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

SELECT

R.REPLANT_ID,

T.TREE_CODE,

S.COMMON_NAME,

L.LOCATION_NAME,

R.PLANTING_DATE,

R.OFFICER_NAME,

R.REMARKS

FROM REPLANTATION R

LEFT JOIN TREE T
ON R.TREE_ID=T.TREE_ID

LEFT JOIN SPECIES_CATALOG S
ON R.SPECIES_ID=S.SPECIES_ID

LEFT JOIN LOCATIONZONE L
ON R.LOCATION_ID=L.LOCATION_ID

WHERE

R.REPLANT_ID=:id

";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🌱 Replantation Details

</h2>

<p class="text-muted">

View complete replantation information.

</p>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="250">

Tree Code

</th>

<td>

<?php echo $row['TREE_CODE']; ?>

</td>

</tr>

<tr>

<th>

Species

</th>

<td>

<?php echo $row['COMMON_NAME']; ?>

</td>

</tr>

<tr>

<th>

Location

</th>

<td>

<?php echo $row['LOCATION_NAME']; ?>

</td>

</tr>

<tr>

<th>

Planting Date

</th>

<td>

<?php echo date("d/m/Y",strtotime($row['PLANTING_DATE'])); ?>

</td>

</tr>

<tr>

<th>

Officer Name

</th>

<td>

<?php echo $row['OFFICER_NAME']; ?>

</td>

</tr>

<tr>

<th>

Remarks

</th>

<td>

<?php echo $row['REMARKS']; ?>

</td>

</tr>

</table>

<a

href="edit.php?id=<?php echo $row['REPLANT_ID']; ?>"

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