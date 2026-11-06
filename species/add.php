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

    $botanical=$_POST['botanical_name'];
    $common=$_POST['common_name'];
    $growth=$_POST['growth_rate'];
    $life=$_POST['max_life_span'];
    $risk=$_POST['risk_factor'];

    $sql="

    INSERT INTO SPECIES_CATALOG
    (
        BOTANICAL_NAME,
        COMMON_NAME,
        GROWTH_RATE,
        MAX_LIFE_SPAN,
        RISK_FACTOR
    )

    VALUES
    (
        :botanical,
        :common,
        :growth,
        :life,
        :risk
    )

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":botanical",$botanical);
    oci_bind_by_name($statement,":common",$common);
    oci_bind_by_name($statement,":growth",$growth);
    oci_bind_by_name($statement,":life",$life);
    oci_bind_by_name($statement,":risk",$risk);

    if(oci_execute($statement))
    {
        $message="Species added successfully.";
    }

}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🌿 Add Species

</h2>

<p class="text-muted">

Register a new tree species.

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

Common Name

</label>

<input

type="text"

name="common_name"

class="form-control"

required>

</div>

<div class="col-md-6">

<label class="form-label">

Botanical Name

</label>

<input

type="text"

name="botanical_name"

class="form-control"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Growth Rate

</label>

<select

name="growth_rate"

class="form-select"

required>

<option value="">-- Select Growth Rate --</option>

<option value="Fast">Fast</option>

<option value="Medium">Medium</option>

<option value="Slow">Slow</option>

</select>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Maximum Life Span (Years)

</label>

<input

type="number"

name="max_life_span"

class="form-control"

min="1"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Risk Factor

</label>

<select

name="risk_factor"

class="form-select"

required>

<option value="">-- Select Risk Factor --</option>

<option value="Low">Low</option>

<option value="Med">Medium</option>

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

Save Species

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