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

FROM SPECIES_CATALOG

WHERE

SPECIES_ID=:id

";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🌿 Species Details

</h2>

<p class="text-muted">

View complete species information.

</p>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="250">

Common Name

</th>

<td>

<?php echo $row['COMMON_NAME']; ?>

</td>

</tr>

<tr>

<th>

Botanical Name

</th>

<td>

<i><?php echo $row['BOTANICAL_NAME']; ?></i>

</td>

</tr>

<tr>

<th>

Growth Rate

</th>

<td>

<?php echo $row['GROWTH_RATE']; ?>

</td>

</tr>

<tr>

<th>

Maximum Life Span

</th>

<td>

<?php echo $row['MAX_LIFE_SPAN']; ?> Years

</td>

</tr>

<tr>

<th>

Risk Factor

</th>

<td>

<?php echo $row['RISK_FACTOR']; ?>

</td>

</tr>

</table>

<a

href="edit.php?id=<?php echo $row['SPECIES_ID']; ?>"

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