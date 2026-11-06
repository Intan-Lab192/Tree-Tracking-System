<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../authentication/login.php");
    exit;
}

include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

/* Save Tree */

if(isset($_POST['save']))
{
    $treeCode = $_POST['tree_code'];
    $species = $_POST['species'];
    $location = $_POST['location'];
    $plantingDate = $_POST['planting_date'];
    $status = $_POST['status'];
    $condition = $_POST['condition'];
    $height = $_POST['height'];
    $diameter = $_POST['diameter'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO TREE
    (
        TREE_CODE,
        SPECIES_ID,
        LOCATION_ID,
        PLANTING_DATE,
        CURRENT_STATUS,
        TREE_CONDITION,
        HEIGHT,
        DIAMETER,
        LATITUDE,
        LONGITUDE,
        LAST_INSPECTION_DATE,
        NOTES
    )
    VALUES
    (
        :treecode,
        :species,
        :location,
        TO_DATE(:plantingdate,'YYYY-MM-DD'),
        :status,
        :condition,
        :height,
        :diameter,
        :latitude,
        :longitude,
        SYSDATE,
        :notes
    )";

    $statement = oci_parse($conn,$sql);

    oci_bind_by_name($statement,":treecode",$treeCode);
    oci_bind_by_name($statement,":species",$species);
    oci_bind_by_name($statement,":location",$location);
    oci_bind_by_name($statement,":plantingdate",$plantingDate);
    oci_bind_by_name($statement,":status",$status);
    oci_bind_by_name($statement,":condition",$condition);
    oci_bind_by_name($statement,":height",$height);
    oci_bind_by_name($statement,":diameter",$diameter);
    oci_bind_by_name($statement,":latitude",$latitude);
    oci_bind_by_name($statement,":longitude",$longitude);
    oci_bind_by_name($statement,":notes",$notes);

    if(oci_execute($statement))
    {
        oci_commit($conn);
        echo "<script>alert('Tree added successfully!');</script>";
    }
    else
    {
        echo "<script>alert('Failed to add tree.');</script>";
    }
}
?>

<div class="content">

<h2>Add New Tree</h2>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<?php

$sql = "SELECT MAX(TREE_ID) AS MAX_ID FROM TREE";

$statement = oci_parse($conn, $sql);

oci_execute($statement);

$row = oci_fetch_assoc($statement);

$nextID = $row['MAX_ID'] + 1;

$treeCode = "T-" . str_pad($nextID, 5, "0", STR_PAD_LEFT);

?>

<div class="col-md-6 mb-3">

<label>Tree Code</label>

<input
type="text"
name="tree_code"
class="form-control"
value="<?php echo $treeCode; ?>"
readonly>

</div>

</div>

<div class="col-md-6 mb-3">

<label>Species</label>

<select
name="species"
class="form-control"
required>

<option value="">-- Select Species --</option>

<?php

$sql="SELECT SPECIES_ID,COMMON_NAME
FROM SPECIES_CATALOG
ORDER BY COMMON_NAME";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
?>

<option value="<?php echo $row['SPECIES_ID']; ?>">

<?php echo $row['COMMON_NAME']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Location</label>

<select
name="location"
class="form-control"
required>

<option value="">-- Select Location --</option>

<?php

$sql="SELECT LOCATION_ID,LOCATION_NAME
FROM LOCATIONZONE
ORDER BY LOCATION_NAME";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
?>

<option value="<?php echo $row['LOCATION_ID']; ?>">

<?php echo $row['LOCATION_NAME']; ?>

</option>

<?php
}
?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Planting Date</label>

<input
type="date"
name="planting_date"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option>Healthy</option>

<option>Needs Inspection</option>

<option>Medium Risk</option>

<option>High Risk</option>

<option>Removed</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Condition</label>

<select
name="condition"
class="form-control">

<option>Excellent</option>
<option>Very Good</option>
<option>Good</option>
<option>Fair</option>
<option>Poor</option>
<option>Dead</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Height (m)</label>

<input
type="number"
step="0.01"
name="height"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Diameter (m)</label>

<input
type="number"
step="0.01"
name="diameter"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Latitude</label>

<input
type="number"
step="0.0000001"
name="latitude"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Longitude</label>

<input
type="number"
step="0.0000001"
name="longitude"
class="form-control">

</div>

<div class="col-md-12 mb-3">

<label>Notes</label>

<textarea
name="notes"
class="form-control"
rows="4"></textarea>

</div>

<div class="col-md-12">

<button
type="submit"
name="save"
class="btn btn-success">

Save Tree

</button>

<a
href="view.php"
class="btn btn-secondary">

Back

</a>

</div>

</div>

</form>

</div>

<?php
include("../includes/footer.php");
?>