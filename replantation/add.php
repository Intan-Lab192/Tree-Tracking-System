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

    $speciesID=$_POST['species_id'];

    $locationID=$_POST['location_id'];

    $plantingDate=$_POST['planting_date'];

    $officer=$_POST['officer_name'];

    $remarks=$_POST['remarks'];

    $sql="

    INSERT INTO REPLANTATION
    (
        TREE_ID,
        PLANTING_DATE,
        SPECIES_ID,
        LOCATION_ID,
        OFFICER_NAME,
        REMARKS
    )

    VALUES
    (
        :tree_id,
        TO_DATE(:planting_date,'YYYY-MM-DD'),
        :species_id,
        :location_id,
        :officer_name,
        :remarks
    )

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":tree_id",$treeID);
    oci_bind_by_name($statement,":planting_date",$plantingDate);
    oci_bind_by_name($statement,":species_id",$speciesID);
    oci_bind_by_name($statement,":location_id",$locationID);
    oci_bind_by_name($statement,":officer_name",$officer);
    oci_bind_by_name($statement,":remarks",$remarks);

    if(oci_execute($statement))
    {
        $message="Replantation record added successfully.";
    }

}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🌱 Add Replantation

</h2>

<p class="text-muted">

Register a new replantation activity.

</p>

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php } ?>

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

<option value="">-- Select Tree --</option>

<?php

$sql="SELECT TREE_ID,TREE_CODE FROM TREE ORDER BY TREE_CODE";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($tree=oci_fetch_assoc($s))
{

?>

<option value="<?php echo $tree['TREE_ID']; ?>">

<?php echo $tree['TREE_CODE']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6">

<label class="form-label">

Species

</label>

<select

name="species_id"

class="form-select"

required>

<option value="">-- Select Species --</option>

<?php

$sql="SELECT SPECIES_ID,COMMON_NAME FROM SPECIES_CATALOG ORDER BY COMMON_NAME";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($species=oci_fetch_assoc($s))
{

?>

<option value="<?php echo $species['SPECIES_ID']; ?>">

<?php echo $species['COMMON_NAME']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Location

</label>

<select

name="location_id"

class="form-select"

required>

<option value="">-- Select Location --</option>

<?php

$sql="SELECT LOCATION_ID,LOCATION_NAME FROM LOCATIONZONE ORDER BY LOCATION_NAME";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($location=oci_fetch_assoc($s))
{

?>

<option value="<?php echo $location['LOCATION_ID']; ?>">

<?php echo $location['LOCATION_NAME']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Planting Date

</label>

<input

type="date"

name="planting_date"

class="form-control"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Officer Name

</label>

<input

type="text"

name="officer_name"

class="form-control"

placeholder="Example: MBSA Officer"

required>

</div>

<div class="col-md-12 mt-3">

<label class="form-label">

Remarks

</label>

<textarea

name="remarks"

class="form-control"

rows="3"

placeholder="Enter remarks here..."></textarea>

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="save"

class="btn btn-success">

<i class="fa-solid fa-floppy-disk"></i>

Save Replantation

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