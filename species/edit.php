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

    $botanical=$_POST['botanical_name'];
    $common=$_POST['common_name'];
    $growth=$_POST['growth_rate'];
    $life=$_POST['max_life_span'];
    $risk=$_POST['risk_factor'];

    $sql="

    UPDATE SPECIES_CATALOG

    SET

    BOTANICAL_NAME=:botanical,

    COMMON_NAME=:common,

    GROWTH_RATE=:growth,

    MAX_LIFE_SPAN=:life,

    RISK_FACTOR=:risk

    WHERE

    SPECIES_ID=:id

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":botanical",$botanical);
    oci_bind_by_name($statement,":common",$common);
    oci_bind_by_name($statement,":growth",$growth);
    oci_bind_by_name($statement,":life",$life);
    oci_bind_by_name($statement,":risk",$risk);
    oci_bind_by_name($statement,":id",$id);

    if(oci_execute($statement))
    {
        header("Location:view.php");
        exit;
    }

}

$sql="SELECT * FROM SPECIES_CATALOG WHERE SPECIES_ID=:id";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

✏️ Edit Species

</h2>

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

value="<?php echo $row['COMMON_NAME']; ?>"

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

value="<?php echo $row['BOTANICAL_NAME']; ?>"

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

<option value="Fast" <?php if($row['GROWTH_RATE']=="Fast") echo "selected"; ?>>Fast</option>

<option value="Medium" <?php if($row['GROWTH_RATE']=="Medium") echo "selected"; ?>>Medium</option>

<option value="Slow" <?php if($row['GROWTH_RATE']=="Slow") echo "selected"; ?>>Slow</option>

</select>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Maximum Life Span

</label>

<input

type="number"

name="max_life_span"

class="form-control"

value="<?php echo $row['MAX_LIFE_SPAN']; ?>"

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

<option value="Low" <?php if($row['RISK_FACTOR']=="Low") echo "selected"; ?>>Low</option>

<option value="Med" <?php if($row['RISK_FACTOR']=="Med") echo "selected"; ?>>Medium</option>

<option value="High" <?php if($row['RISK_FACTOR']=="High") echo "selected"; ?>>High</option>

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

Update Species

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