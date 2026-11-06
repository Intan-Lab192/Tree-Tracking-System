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

    $removalDate=$_POST['removal_date'];

    $reason=$_POST['removal_reason'];

    $approvedBy=$_POST['approved_by'];

    $remarks=$_POST['remarks'];

    $sql="

    INSERT INTO TREE_REMOVAL
    (
        TREE_ID,
        REMOVAL_DATE,
        REMOVAL_REASON,
        APPROVED_BY,
        REMARKS
    )

    VALUES
    (
        :tree_id,
        TO_DATE(:removal_date,'YYYY-MM-DD'),
        :removal_reason,
        :approved_by,
        :remarks
    )

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":tree_id",$treeID);

    oci_bind_by_name($statement,":removal_date",$removalDate);

    oci_bind_by_name($statement,":removal_reason",$reason);

    oci_bind_by_name($statement,":approved_by",$approvedBy);

    oci_bind_by_name($statement,":remarks",$remarks);

    if(oci_execute($statement))
    {
        $message="Removal record added successfully.";
    }
    else
    {
        $message="Failed to save removal record.";
    }

}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🪓 Add Tree Removal

</h2>

<p class="text-muted">

Register a new tree removal record.

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

<option value="<?php echo $tree['TREE_ID']; ?>">

<?php echo $tree['TREE_CODE']; ?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-md-6">

<label class="form-label">

Removal Date

</label>

<input

type="date"

name="removal_date"

class="form-control"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Removal Reason

</label>

<textarea

name="removal_reason"

class="form-control"

rows="3"

required></textarea>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Approved By

</label>

<input

type="text"

name="approved_by"

class="form-control"

placeholder="Example: City Council"

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

placeholder="Additional remarks..."></textarea>

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="save"

class="btn btn-danger">

<i class="fa-solid fa-floppy-disk"></i>

Save Removal Record

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

$message="";

if(isset($_POST['update']))
{

    $treeID=$_POST['tree_id'];

    $removalDate=$_POST['removal_date'];

    $reason=$_POST['removal_reason'];

    $approvedBy=$_POST['approved_by'];

    $remarks=$_POST['remarks'];

    $sql="

    UPDATE TREE_REMOVAL

    SET

    TREE_ID=:tree_id,

    REMOVAL_DATE=TO_DATE(:removal_date,'YYYY-MM-DD'),

    REMOVAL_REASON=:removal_reason,

    APPROVED_BY=:approved_by,

    REMARKS=:remarks

    WHERE

    REMOVAL_ID=:id

    ";

    $statement=oci_parse($conn,$sql);

    oci_bind_by_name($statement,":tree_id",$treeID);

    oci_bind_by_name($statement,":removal_date",$removalDate);

    oci_bind_by_name($statement,":removal_reason",$reason);

    oci_bind_by_name($statement,":approved_by",$approvedBy);

    oci_bind_by_name($statement,":remarks",$remarks);

    oci_bind_by_name($statement,":id",$id);

    if(oci_execute($statement))
    {
        $message="Removal record updated successfully.";
    }

}

$sql="

SELECT *

FROM TREE_REMOVAL

WHERE REMOVAL_ID=:id

";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

✏️ Edit Tree Removal

</h2>

<p class="text-muted">

Update the selected tree removal record.

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

<?php } ?>

</select>

</div>

<div class="col-md-6">

<label class="form-label">

Removal Date

</label>

<input

type="date"

name="removal_date"

class="form-control"

value="<?php echo date('Y-m-d',strtotime($row['REMOVAL_DATE'])); ?>"

required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Removal Reason

</label>

<textarea

name="removal_reason"

class="form-control"

rows="3"

required><?php echo $row['REMOVAL_REASON']; ?></textarea>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Approved By

</label>

<input

type="text"

name="approved_by"

class="form-control"

value="<?php echo $row['APPROVED_BY']; ?>"

required>

</div>

<div class="col-md-12 mt-3">

<label class="form-label">

Remarks

</label>

<textarea

name="remarks"

class="form-control"

rows="3"><?php echo $row['REMARKS']; ?></textarea>

</div>

</div>

<hr>

<div class="mt-3">

<button

type="submit"

name="update"

class="btn btn-warning">

<i class="fa-solid fa-pen"></i>

Update Removal Record

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