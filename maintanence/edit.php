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

$message="";

if(isset($_POST['update']))
{

    $treeID=$_POST['tree_id'];

    $maintenanceDate=$_POST['maintenance_date'];

    $maintenanceType=$_POST['maintenance_type'];

    $performedBy=$_POST['performed_by'];

    $cost=$_POST['cost'];

    $remarks=$_POST['remarks'];

    $sql="

    UPDATE MAINTENANCE

    SET

    TREE_ID=:tree_id,

    MAINTENANCE_DATE=TO_DATE(:maintenance_date,'YYYY-MM-DD'),

    MAINTENANCE_TYPE=:maintenance_type,

    PERFORMED_BY=:performed_by,

    COST=:cost,

    REMARKS=:remarks

    WHERE

    MAINTENANCE_ID=:id

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":tree_id",$treeID);

    oci_bind_by_name($statement,":maintenance_date",$maintenanceDate);

    oci_bind_by_name($statement,":maintenance_type",$maintenanceType);

    oci_bind_by_name($statement,":performed_by",$performedBy);

    oci_bind_by_name($statement,":cost",$cost);

    oci_bind_by_name($statement,":remarks",$remarks);

    oci_bind_by_name($statement,":id",$id);

    if(oci_execute($statement))
    {
        $message="Maintenance record updated successfully.";
    }

}

$sql="

SELECT *

FROM MAINTENANCE

WHERE MAINTENANCE_ID=:id

";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

✏️ Edit Maintenance

</h2>

<p class="text-muted">

Update maintenance information.

</p>

<?php

if($message!="")
{

?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php

}

?>

<form method="POST">

<div class="card shadow">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label class="form-label">

Tree

</label>

<select

name="tree_id"

class="form-select"

required>

<?php

$sql="SELECT TREE_ID,TREE_CODE FROM TREE ORDER BY TREE_CODE";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($tree=oci_fetch_assoc($s))
{

?>

<option

value="<?php echo $tree['TREE_ID']; ?>"

<?php

if($tree['TREE_ID']==$row['TREE_ID'])

echo "selected";

?>

>

<?php echo $tree['TREE_CODE']; ?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-md-6">

<label class="form-label">

Maintenance Date

</label>

<input

type="date"

name="maintenance_date"

class="form-control"

value="<?php echo date('Y-m-d',strtotime($row['MAINTENANCE_DATE'])); ?>"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Maintenance Type

</label>

<select

name="maintenance_type"

class="form-select"

required>

<option value="Pruning"
<?php if($row['MAINTENANCE_TYPE']=="Pruning") echo "selected"; ?>>
Pruning
</option>

<option value="Fertilizing"
<?php if($row['MAINTENANCE_TYPE']=="Fertilizing") echo "selected"; ?>>
Fertilizing
</option>

<option value="Watering"
<?php if($row['MAINTENANCE_TYPE']=="Watering") echo "selected"; ?>>
Watering
</option>

<option value="Pest Control"
<?php if($row['MAINTENANCE_TYPE']=="Pest Control") echo "selected"; ?>>
Pest Control
</option>

<option value="Disease Treatment"
<?php if($row['MAINTENANCE_TYPE']=="Disease Treatment") echo "selected"; ?>>
Disease Treatment
</option>

<option value="Tree Inspection"
<?php if($row['MAINTENANCE_TYPE']=="Tree Inspection") echo "selected"; ?>>
Tree Inspection
</option>

<option value="Emergency Maintenance"
<?php if($row['MAINTENANCE_TYPE']=="Emergency Maintenance") echo "selected"; ?>>
Emergency Maintenance
</option>

<option value="Cleaning"
<?php if($row['MAINTENANCE_TYPE']=="Cleaning") echo "selected"; ?>>
Cleaning
</option>

<option value="Other"
<?php if($row['MAINTENANCE_TYPE']=="Other") echo "selected"; ?>>
Other
</option>

</select>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Performed By

</label>

<input

type="text"

name="performed_by"

class="form-control"

value="<?php echo $row['PERFORMED_BY']; ?>"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Cost (RM)

</label>

<input

type="number"

step="0.01"

min="0"

name="cost"

class="form-control"

value="<?php echo $row['COST']; ?>"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Remarks

</label>

<input

type="text"

name="remarks"

class="form-control"

value="<?php echo $row['REMARKS']; ?>">

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="update"

class="btn btn-warning">

<i class="fa-solid fa-pen"></i>

Update Maintenance

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