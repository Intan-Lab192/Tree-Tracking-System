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

    $location=$_POST['location_name'];
    $road=$_POST['road_name'];
    $district=$_POST['district_state'];
    $postcode=$_POST['postcode'];
    $traffic=$_POST['traffic_volume'];
    $risk=$_POST['risk_priority'];

    $sql="

    INSERT INTO LOCATIONZONE
    (
        LOCATION_NAME,
        ROAD_NAME,
        DISTRICT_STATE,
        POSTCODE,
        TRAFFIC_VOLUME,
        RISK_PRIORITY
    )

    VALUES
    (
        :location,
        :road,
        :district,
        :postcode,
        :traffic,
        :risk
    )

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":location",$location);
    oci_bind_by_name($statement,":road",$road);
    oci_bind_by_name($statement,":district",$district);
    oci_bind_by_name($statement,":postcode",$postcode);
    oci_bind_by_name($statement,":traffic",$traffic);
    oci_bind_by_name($statement,":risk",$risk);

    if(oci_execute($statement))
    {
        $message="Location added successfully.";
    }

}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

📍 Add Location

</h2>

<p class="text-muted">

Register a new tree location.

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

Location Name

</label>

<input

type="text"

name="location_name"

class="form-control"

required>

</div>

<div class="col-md-6">

<label class="form-label">

Road Name

</label>

<input

type="text"

name="road_name"

class="form-control">

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

District / State

</label>

<input

type="text"

name="district_state"

class="form-control"

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

maxlength="10">

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Traffic Volume

</label>

<select

name="traffic_volume"

class="form-select"

required>

<option value="">-- Select Traffic Volume --</option>

<option value="Low">Low</option>

<option value="Medium">Medium</option>

<option value="High">High</option>

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

<option value="">-- Select Risk Priority --</option>

<option value="Low">Low</option>

<option value="Medium">Medium</option>

<option value="High">High</option>

</select>

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="save"

class="btn btn-success">

<i class="fa-solid fa-floppy-disk"></i>

Save Location

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