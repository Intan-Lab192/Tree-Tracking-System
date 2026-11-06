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

R.REMOVAL_ID,

T.TREE_CODE,

R.REMOVAL_DATE,

R.REMOVAL_REASON,

R.APPROVED_BY,

R.REMARKS

FROM TREE_REMOVAL R

JOIN TREE T

ON R.TREE_ID=T.TREE_ID

WHERE

UPPER(T.TREE_CODE) LIKE UPPER(:search)

OR

UPPER(R.REMOVAL_REASON) LIKE UPPER(:search)

OR

UPPER(R.APPROVED_BY) LIKE UPPER(:search)

ORDER BY

R.REMOVAL_DATE DESC

";

$statement=oci_parse($conn,$sql);

$searchValue="%".$search."%";

oci_bind_by_name($statement,":search",$searchValue);

oci_execute($statement);

$totalRecords=0;

$sql="SELECT COUNT(*) TOTAL FROM TREE_REMOVAL";

$s=oci_parse($conn,$sql);

oci_execute($s);

$r=oci_fetch_assoc($s);

$totalRecords=$r['TOTAL'];

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🪓 Tree Removal Management

</h2>

<p class="text-muted">

Manage all tree removal records.

</p>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <a
        href="add.php"
        class="btn btn-danger">

            <i class="fa-solid fa-plus"></i>

            Add Removal Record

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
                placeholder="Search Tree Code, Reason or Approved By..."
                value="<?php echo htmlspecialchars($search); ?>">

                <button
                type="submit"
                class="btn btn-danger">

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

<thead class="table-danger">

<tr>

<th>Tree Code</th>

<th>Removal Date</th>

<th>Removal Reason</th>

<th>Approved By</th>

<th>Remarks</th>

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

<?php

echo date(

"d/m/Y",

strtotime($row['REMOVAL_DATE'])

);

?>

</td>

<td>

<?php echo $row['REMOVAL_REASON']; ?>

</td>

<td>

<?php echo $row['APPROVED_BY']; ?>

</td>

<td>

<?php echo $row['REMARKS']; ?>

</td>

<td>

<a

href="details.php?id=<?php echo $row['REMOVAL_ID']; ?>"

class="btn btn-outline-primary btn-sm">

<i class="fa-solid fa-eye"></i>

</a>

<a

href="edit.php?id=<?php echo $row['REMOVAL_ID']; ?>"

class="btn btn-outline-warning btn-sm">

<i class="fa-solid fa-pen"></i>

</a>

<a

href="delete.php?id=<?php echo $row['REMOVAL_ID']; ?>"

class="btn btn-outline-danger btn-sm"

onclick="return confirm('Delete this removal record?');">

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