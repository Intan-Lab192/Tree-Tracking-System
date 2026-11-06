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

M.MAINTENANCE_ID,

T.TREE_CODE,

M.MAINTENANCE_DATE,

M.MAINTENANCE_TYPE,

M.PERFORMED_BY,

M.COST,

M.REMARKS

FROM MAINTENANCE M

JOIN TREE T

ON M.TREE_ID=T.TREE_ID

WHERE

M.MAINTENANCE_ID=:id

";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🔍 Maintenance Details

</h2>

<p class="text-muted">

View complete maintenance information.

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

Maintenance Date

</th>

<td>

<?php

echo date(

"d/m/Y",

strtotime($row['MAINTENANCE_DATE'])

);

?>

</td>

</tr>

<tr>

<th>

Maintenance Type

</th>

<td>

<?php echo $row['MAINTENANCE_TYPE']; ?>

</td>

</tr>

<tr>

<th>

Performed By

</th>

<td>

<?php echo $row['PERFORMED_BY']; ?>

</td>

</tr>

<tr>

<th>

Cost (RM)

</th>

<td>

RM <?php echo number_format($row['COST'],2); ?>

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

href="edit.php?id=<?php echo $row['MAINTENANCE_ID']; ?>"

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