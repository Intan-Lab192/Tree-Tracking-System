<?php
session_start();

if (!isset($_SESSION['username'])) {
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

$sql="SELECT *
FROM TREE
WHERE TREE_ID=:id";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$tree=oci_fetch_assoc($statement);

if(isset($_POST['update']))
{
    $species=$_POST['species'];
    $location=$_POST['location'];
    $status=$_POST['status'];
    $condition=$_POST['condition'];
    $height=$_POST['height'];
    $diameter=$_POST['diameter'];
    $latitude=$_POST['latitude'];
    $longitude=$_POST['longitude'];
    $notes=$_POST['notes'];

    $sql="UPDATE TREE SET

    SPECIES_ID=:species,

    LOCATION_ID=:location,

    CURRENT_STATUS=:status,

    TREE_CONDITION=:condition,

    HEIGHT=:height,

    DIAMETER=:diameter,

    LATITUDE=:latitude,

    LONGITUDE=:longitude,

    NOTES=:notes

    WHERE TREE_ID=:id";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":species",$species);
    oci_bind_by_name($statement,":location",$location);
    oci_bind_by_name($statement,":status",$status);
    oci_bind_by_name($statement,":condition",$condition);
    oci_bind_by_name($statement,":height",$height);
    oci_bind_by_name($statement,":diameter",$diameter);
    oci_bind_by_name($statement,":latitude",$latitude);
    oci_bind_by_name($statement,":longitude",$longitude);
    oci_bind_by_name($statement,":notes",$notes);
    oci_bind_by_name($statement,":id",$id);

    oci_execute($statement);

    oci_commit($conn);

    header("Location:view.php");

    exit;
}

?>

<div class="content">

<h2>Edit Tree</h2>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Species</label>

<select
name="species"
class="form-control">

<?php

$sql="SELECT *
FROM SPECIES_CATALOG
ORDER BY COMMON_NAME";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{

?>

<option

value="<?php echo $row['SPECIES_ID'];?>"

<?php

if($row['SPECIES_ID']==$tree['SPECIES_ID'])

echo"selected";

?>

>

<?php echo $row['COMMON_NAME'];?>

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
class="form-control">

<?php

$sql="SELECT *
FROM LOCATIONZONE
ORDER BY LOCATION_NAME";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{

?>

<option

value="<?php echo $row['LOCATION_ID'];?>"

<?php

if($row['LOCATION_ID']==$tree['LOCATION_ID'])

echo"selected";

?>

>

<?php echo $row['LOCATION_NAME'];?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Status</label>

<input

type="text"

name="status"

value="<?php echo $tree['CURRENT_STATUS'];?>"

class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Condition</label>

<input

type="text"

name="condition"

value="<?php echo $tree['TREE_CONDITION'];?>"

class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Height</label>

<input

type="number"

step="0.01"

name="height"

value="<?php echo $tree['HEIGHT'];?>"

class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Diameter</label>

<input

type="number"

step="0.01"

name="diameter"

value="<?php echo $tree['DIAMETER'];?>"

class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Latitude</label>

<input

type="number"

step="0.0000001"

name="latitude"

value="<?php echo $tree['LATITUDE'];?>"

class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Longitude</label>

<input

type="number"

step="0.0000001"

name="longitude"

value="<?php echo $tree['LONGITUDE'];?>"

class="form-control">

</div>

<div class="col-md-12 mb-3">

<label>Notes</label>

<textarea

name="notes"

rows="4"

class="form-control"><?php echo $tree['NOTES'];?></textarea>

</div>

<div class="col-md-12">

<button

class="btn btn-success"

name="update">

Update Tree

</button>

<a

href="view.php"

class="btn btn-secondary">

Cancel

</a>

</div>

</div>

</form>

</div>

<?php

include("../includes/footer.php");

?>