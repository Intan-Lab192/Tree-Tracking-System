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

$message="";

if(isset($_POST['save']))
{

    $treeID=$_POST['tree_id'];

    $maintenanceDate=$_POST['maintenance_date'];

    $maintenanceType=$_POST['maintenance_type'];

    $performedBy=$_POST['performed_by'];

    $cost=$_POST['cost'];

    $remarks=$_POST['remarks'];

    $sql="

    INSERT INTO MAINTENANCE
    (
        TREE_ID,
        MAINTENANCE_DATE,
        MAINTENANCE_TYPE,
        PERFORMED_BY,
        COST,
        REMARKS
    )

    VALUES
    (
        :tree_id,
        TO_DATE(:maintenance_date,'YYYY-MM-DD'),
        :maintenance_type,
        :performed_by,
        :cost,
        :remarks
    )

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":tree_id",$treeID);

    oci_bind_by_name($statement,":maintenance_date",$maintenanceDate);

    oci_bind_by_name($statement,":maintenance_type",$maintenanceType);

    oci_bind_by_name($statement,":performed_by",$performedBy);

    oci_bind_by_name($statement,":cost",$cost);

    oci_bind_by_name($statement,":remarks",$remarks);

    if(oci_execute($statement))
    {
        $message="Maintenance record added successfully.";
    }
    else
    {
        $message="Failed to save maintenance record.";
    }

}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🔧 Add Maintenance

</h2>

<p class="text-muted">

Register a new maintenance activity.

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

<option value="">

-- Select Tree --

</option>

<?php

$sql="

SELECT

TREE_ID,

TREE_CODE

FROM TREE

ORDER BY TREE_CODE

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($tree=oci_fetch_assoc($s))
{

?>

<option
value="<?php echo $tree['TREE_ID']; ?>">

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

<option value="">-- Select Type --</option>

<option value="Pruning">Pruning</option>

<option value="Fertilizing">Fertilizing</option>

<option value="Watering">Watering</option>

<option value="Pest Control">Pest Control</option>

<option value="Disease Treatment">Disease Treatment</option>

<option value="Tree Inspection">Tree Inspection</option>

<option value="Emergency Maintenance">Emergency Maintenance</option>

<option value="Cleaning">Cleaning</option>

<option value="Other">Other</option>

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

placeholder="Example: MBSA Contractor"

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

placeholder="0.00"

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

placeholder="Enter maintenance remarks">

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="save"

class="btn btn-success">

<i class="fa-solid fa-floppy-disk"></i>

Save Maintenance

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