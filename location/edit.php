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

    $location=$_POST['location_name'];
    $road=$_POST['road_name'];
    $district=$_POST['district_state'];
    $postcode=$_POST['postcode'];
    $traffic=$_POST['traffic_volume'];
    $risk=$_POST['risk_priority'];

    $sql="

    UPDATE LOCATIONZONE

    SET

    LOCATION_NAME=:location,

    ROAD_NAME=:road,

    DISTRICT_STATE=:district,

    POSTCODE=:postcode,

    TRAFFIC_VOLUME=:traffic,

    RISK_PRIORITY=:risk

    WHERE

    LOCATION_ID=:id

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":location",$location);
    oci_bind_by_name($statement,":road",$road);
    oci_bind_by_name($statement,":district",$district);
    oci_bind_by_name($statement,":postcode",$postcode);
    oci_bind_by_name($statement,":traffic",$traffic);
    oci_bind_by_name($statement,":risk",$risk);
    oci_bind_by_name($statement,":id",$id);

    if(oci_execute($statement))
    {
        header("Location:view.php");
        exit;
    }

}

$sql="SELECT * FROM LOCATIONZONE WHERE LOCATION_ID=:id";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

✏️ Edit Location

</h2>

<form method="POST">

<div class="card shadow">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label class="form-label">

Location Name

</label>

<input

type="text"

name="location_name"

class="form-control"

value="<?php echo $row['LOCATION_NAME']; ?>"

required>

</div>

<div class="col-md-6">

<label class="form-label">

Road Name

</label>

<input

type="text"

name="road_name"

class="form-control"

value="<?php echo $row['ROAD_NAME']; ?>">

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

District / State

</label>

<input

type="text"

name="district_state"

class="form-control"

value="<?php echo $row['DISTRICT_STATE']; ?>"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Postcode

</label>

<input

type="text"

name="postcode"

class="form-control"

maxlength="10"

value="<?php echo $row['POSTCODE']; ?>">

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Traffic Volume

</label>

<select

name="traffic_volume"

class="form-select"

required>

<option value="Low" <?php if($row['TRAFFIC_VOLUME']=="Low") echo "selected"; ?>>Low</option>

<option value="Medium" <?php if($row['TRAFFIC_VOLUME']=="Medium") echo "selected"; ?>>Medium</option>

<option value="High" <?php if($row['TRAFFIC_VOLUME']=="High") echo "selected"; ?>>High</option>

</select>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Risk Priority

</label>

<select

name="risk_priority"

class="form-select"

required>

<option value="Low" <?php if($row['RISK_PRIORITY']=="Low") echo "selected"; ?>>Low</option>

<option value="Medium" <?php if($row['RISK_PRIORITY']=="Medium") echo "selected"; ?>>Medium</option>

<option value="High" <?php if($row['RISK_PRIORITY']=="High") echo "selected"; ?>>High</option>

</select>

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="update"

class="btn btn-warning">

<i class="fa-solid fa-pen"></i>

Update Location

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
