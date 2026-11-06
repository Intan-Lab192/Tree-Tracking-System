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

$search="";

if(isset($_GET['search']))
{
    $search=trim($_GET['search']);
}

$sql="

SELECT

R.ASSESSMENT_ID,

T.TREE_CODE,

R.ASSESSMENT_DATE,

R.RISK_LEVEL,

R.INSPECTED_BY,

R.ACTION_REQUIRED

FROM RISK_ASSESSMENT R

JOIN TREE T

ON R.TREE_ID=T.TREE_ID

WHERE

UPPER(T.TREE_CODE) LIKE UPPER(:search)

OR

UPPER(R.RISK_LEVEL) LIKE UPPER(:search)

OR

UPPER(R.INSPECTED_BY) LIKE UPPER(:search)

ORDER BY

R.ASSESSMENT_DATE DESC

";

$statement=oci_parse($conn,$sql);

$searchValue="%".$search."%";

oci_bind_by_name($statement,":search",$searchValue);

oci_execute($statement);

$sql="SELECT COUNT(*) TOTAL FROM RISK_ASSESSMENT";

$s=oci_parse($conn,$sql);

oci_execute($s);

$r=oci_fetch_assoc($s);

$totalRecords=$r['TOTAL'];

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

⚠️ Risk Assessment Management

</h2>

<p class="text-muted">

Manage all tree risk assessments.

</p>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <a

        href="add.php"

        class="btn btn-warning">

            <i class="fa-solid fa-plus"></i>

            Add Assessment

        </a>

        <a

        href="analytics.php"

        class="btn btn-primary ms-3">

            <i class="fa-solid fa-chart-column"></i>

            Analytics

        </a>

    </div>

    <div style="width:420px;">

        <form method="GET">

            <div class="input-group">

                <input

                type="text"

                name="search"

                class="form-control"

                placeholder="Search Tree Code, Risk Level or Inspector..."

                value="<?php echo htmlspecialchars($search); ?>">

                <button

                type="submit"

                class="btn btn-warning">

                    Search

                </button>

                <a

                href="view.php"

                class="btn btn-secondary">

                    Reset

                </a>

            </div>

        </form>

    </div>

</div>

<p class="text-secondary">

Total Records :

<strong>

<?php echo $totalRecords; ?>

</strong>

</p>

<table class="table table-hover table-bordered shadow">

<thead class="table-warning">

<tr>

<th>Tree Code</th>

<th>Assessment Date</th>

<th>Risk Level</th>

<th>Inspected By</th>

<th>Action Required</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>

<?php while($row=oci_fetch_assoc($statement)){ ?>

<tr>

<td>

<?php echo $row['TREE_CODE']; ?>

</td>

<td>

<?php echo date("d/m/Y",strtotime($row['ASSESSMENT_DATE'])); ?>

</td>

<td>

<?php echo $row['RISK_LEVEL']; ?>

</td>

<td>

<?php echo $row['INSPECTED_BY']; ?>

</td>

<td>

<?php echo $row['ACTION_REQUIRED']; ?>

</td>

<td>

<a

href="details.php?id=<?php echo $row['ASSESSMENT_ID']; ?>"

class="btn btn-outline-primary btn-sm">

<i class="fa-solid fa-eye"></i>

</a>

<a

href="edit.php?id=<?php echo $row['ASSESSMENT_ID']; ?>"

class="btn btn-outline-warning btn-sm">

<i class="fa-solid fa-pen"></i>

</a>

<a

href="delete.php?id=<?php echo $row['ASSESSMENT_ID']; ?>"

class="btn btn-outline-danger btn-sm"

onclick="return confirm('Delete this assessment record?');">

<i class="fa-solid fa-trash"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php

include("../includes/footer.php");

?>