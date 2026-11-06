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

R.ASSESSMENT_ID,

T.TREE_CODE,

R.ASSESSMENT_DATE,

R.RISK_LEVEL,

R.INSPECTED_BY,

R.ACTION_REQUIRED

FROM RISK_ASSESSMENT R

JOIN TREE T

ON R.TREE_ID=T.TREE_ID

WHERE

R.ASSESSMENT_ID=:id

";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

⚠️ Risk Assessment Details

</h2>

<p class="text-muted">

View complete risk assessment information.

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

Assessment Date

</th>

<td>

<?php echo date("d/m/Y",strtotime($row['ASSESSMENT_DATE'])); ?>

</td>

</tr>

<tr>

<th>

Risk Level

</th>

<td>

<?php echo $row['RISK_LEVEL']; ?>

</td>

</tr>

<tr>

<th>

Inspected By

</th>

<td>

<?php echo $row['INSPECTED_BY']; ?>

</td>

</tr>

<tr>

<th>

Action Required

</th>

<td>

<?php echo $row['ACTION_REQUIRED']; ?>

</td>

</tr>

</table>

<a

href="edit.php?id=<?php echo $row['ASSESSMENT_ID']; ?>"

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