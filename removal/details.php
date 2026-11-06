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

R.REMOVAL_ID,

T.TREE_CODE,

R.REMOVAL_DATE,

R.REMOVAL_REASON,

R.APPROVED_BY,

R.REMARKS

FROM TREE_REMOVAL R

JOIN TREE T

ON R.TREE_ID=T.TREE_ID

WHERE

R.REMOVAL_ID=:id

";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🪓 Tree Removal Details

</h2>

<p class="text-muted">

View complete removal information.

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

Removal Date

</th>

<td>

<?php

echo date(
"d/m/Y",
strtotime($row['REMOVAL_DATE'])
);

?>

</td>

</tr>

<tr>

<th>

Removal Reason

</th>

<td>

<?php echo $row['REMOVAL_REASON']; ?>

</td>

</tr>

<tr>

<th>

Approved By

</th>

<td>

<?php echo $row['APPROVED_BY']; ?>

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

href="edit.php?id=<?php echo $row['REMOVAL_ID']; ?>"

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