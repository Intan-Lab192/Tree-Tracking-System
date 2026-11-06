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
    $assessmentDate=$_POST['assessment_date'];
    $riskLevel=$_POST['risk_level'];
    $inspectedBy=$_POST['inspected_by'];
    $actionRequired=$_POST['action_required'];

    $sql="

    INSERT INTO RISK_ASSESSMENT
    (
        TREE_ID,
        ASSESSMENT_DATE,
        RISK_LEVEL,
        INSPECTED_BY,
        ACTION_REQUIRED
    )

    VALUES
    (
        :tree_id,
        TO_DATE(:assessment_date,'YYYY-MM-DD'),
        :risk_level,
        :inspected_by,
        :action_required
    )

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":tree_id",$treeID);
    oci_bind_by_name($statement,":assessment_date",$assessmentDate);
    oci_bind_by_name($statement,":risk_level",$riskLevel);
    oci_bind_by_name($statement,":inspected_by",$inspectedBy);
    oci_bind_by_name($statement,":action_required",$actionRequired);

    if(oci_execute($statement))
    {
        $message="Risk assessment added successfully.";
    }

}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

⚠️ Add Risk Assessment

</h2>

<p class="text-muted">

Register a new tree risk assessment.

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

Assessment Date

</label>

<input

type="date"

name="assessment_date"

class="form-control"

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

<option value="">-- Select Risk Level --</option>

<option value="Low">Low</option>

<option value="Medium">Medium</option>

<option value="High">High</option>

<option value="Critical">Critical</option>

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

placeholder="Example: MBSA Inspector"

required>

</div>

<div class="col-md-12 mt-3">

<label class="form-label">

Action Required

</label>

<textarea

name="action_required"

class="form-control"

rows="3"

placeholder="Example: Immediate pruning required"></textarea>

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="save"

class="btn btn-warning">

<i class="fa-solid fa-floppy-disk"></i>

Save Assessment

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