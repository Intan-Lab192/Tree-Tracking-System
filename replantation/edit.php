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

if(isset($_POST['update']))
{

    $treeID=$_POST['tree_id'];
    $speciesID=$_POST['species_id'];
    $locationID=$_POST['location_id'];
    $plantingDate=$_POST['planting_date'];
    $officer=$_POST['officer_name'];
    $remarks=$_POST['remarks'];

    $sql="

    UPDATE REPLANTATION

    SET

    TREE_ID=:tree_id,

    PLANTING_DATE=TO_DATE(:planting_date,'YYYY-MM-DD'),

    SPECIES_ID=:species_id,

    LOCATION_ID=:location_id,

    OFFICER_NAME=:officer_name,

    REMARKS=:remarks

    WHERE

    REPLANT_ID=:id

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":tree_id",$treeID);
    oci_bind_by_name($statement,":species_id",$speciesID);
    oci_bind_by_name($statement,":location_id",$locationID);
    oci_bind_by_name($statement,":planting_date",$plantingDate);
    oci_bind_by_name($statement,":officer_name",$officer);
    oci_bind_by_name($statement,":remarks",$remarks);
    oci_bind_by_name($statement,":id",$id);

    if(oci_execute($statement))
    {
        header("Location:view.php");
        exit;
    }

}

$sql="SELECT * FROM REPLANTATION WHERE REPLANT_ID=:id";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

✏️ Edit Replantation

</h2>

<form method="POST">

<div class="card shadow">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label>Tree</label>

<select name="tree_id" class="form-select">

<?php

$sql="SELECT TREE_ID,TREE_CODE FROM TREE ORDER BY TREE_CODE";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($tree=oci_fetch_assoc($s))
{

?>

<option

value="<?php echo $tree['TREE_ID'];?>"

<?php if($tree['TREE_ID']==$row['TREE_ID']) echo "selected"; ?>

>

<?php echo $tree['TREE_CODE'];?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6">

<label>Species</label>

<select name="species_id" class="form-select">

<?php

$sql="SELECT SPECIES_ID,COMMON_NAME FROM SPECIES_CATALOG ORDER BY COMMON_NAME";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($species=oci_fetch_assoc($s))
{

?>

<option

value="<?php echo $species['SPECIES_ID'];?>"

<?php if($species['SPECIES_ID']==$row['SPECIES_ID']) echo "selected"; ?>

>

<?php echo $species['COMMON_NAME'];?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mt-3">

<label>Location</label>

<select name="location_id" class="form-select">

<?php

$sql="SELECT LOCATION_ID,LOCATION_NAME FROM LOCATIONZONE ORDER BY LOCATION_NAME";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($location=oci_fetch_assoc($s))
{

?>

<option

value="<?php echo $location['LOCATION_ID'];?>"

<?php if($location['LOCATION_ID']==$row['LOCATION_ID']) echo "selected"; ?>

>

<?php echo $location['LOCATION_NAME'];?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mt-3">

<label>Planting Date</label>

<input

type="date"

name="planting_date"

class="form-control"

value="<?php echo date('Y-m-d',strtotime($row['PLANTING_DATE'])); ?>"

required>

</div>

<div class="col-md-6 mt-3">

<label>

Officer Name

</label>

<input

type="text"

name="officer_name"

class="form-control"

value="<?php echo $row['OFFICER_NAME']; ?>"

required>

</div>

<div class="col-md-12 mt-3">

<label>

Remarks

</label>

<textarea

name="remarks"

class="form-control"

rows="3"><?php echo $row['REMARKS']; ?></textarea>

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="update"

class="btn btn-warning">

<i class="fa-solid fa-pen"></i>

Update Replantation

</button>

<a

href="view.php"

class="btn btn-secondary">

Cancel

</a>

</div>

</div>

</div>

</form>

</div>

<?php

include("../includes/footer.php");

?>