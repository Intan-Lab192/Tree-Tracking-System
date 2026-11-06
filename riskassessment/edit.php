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
    $assessmentDate=$_POST['assessment_date'];
    $riskLevel=$_POST['risk_level'];
    $inspectedBy=$_POST['inspected_by'];
    $actionRequired=$_POST['action_required'];

    $sql="

    UPDATE RISK_ASSESSMENT

    SET

    TREE_ID=:tree_id,

    ASSESSMENT_DATE=TO_DATE(:assessment_date,'YYYY-MM-DD'),

    RISK_LEVEL=:risk_level,

    INSPECTED_BY=:inspected_by,

    ACTION_REQUIRED=:action_required

    WHERE

    ASSESSMENT_ID=:id

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":tree_id",$treeID);
    oci_bind_by_name($statement,":assessment_date",$assessmentDate);
    oci_bind_by_name($statement,":risk_level",$riskLevel);
    oci_bind_by_name($statement,":inspected_by",$inspectedBy);
    oci_bind_by_name($statement,":action_required",$actionRequired);
    oci_bind_by_name($statement,":id",$id);

    if(oci_execute($statement))
    {
        header("Location:view.php");
        exit;
    }

}

$sql="SELECT * FROM RISK_ASSESSMENT WHERE ASSESSMENT_ID=:id";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

✏️ Edit Risk Assessment

</h2>

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

<?php if($tree['TREE_ID']==$row['TREE_ID']) echo "selected"; ?>

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

Assessment Date

</label>

<input

type="date"

name="assessment_date"

class="form-control"

value="<?php echo date('Y-m-d',strtotime($row['ASSESSMENT_DATE'])); ?>"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Risk Level

</label>

<select

name="risk_level"

class="form-select"

required>

<option value="Low" <?php if($row['RISK_LEVEL']=="Low") echo "selected"; ?>>Low</option>

<option value="Medium" <?php if($row['RISK_LEVEL']=="Medium") echo "selected"; ?>>Medium</option>

<option value="High" <?php if($row['RISK_LEVEL']=="High") echo "selected"; ?>>High</option>

<option value="Critical" <?php if($row['RISK_LEVEL']=="Critical") echo "selected"; ?>>Critical</option>

</select>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Inspected By

</label>

<input

type="text"

name="inspected_by"

class="form-control"

value="<?php echo $row['INSPECTED_BY']; ?>"

required>

</div>

<div class="col-md-12 mt-3">

<label class="form-label">

Action Required

</label>

<textarea

name="action_required"

class="form-control"

rows="3"><?php echo $row['ACTION_REQUIRED']; ?></textarea>

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="update"

class="btn btn-warning">

<i class="fa-solid fa-pen"></i>

Update Assessment

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