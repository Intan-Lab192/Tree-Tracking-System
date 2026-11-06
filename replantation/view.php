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

R.REPLANT_ID,

T.TREE_CODE,

S.COMMON_NAME,

L.LOCATION_NAME,

R.PLANTING_DATE,

R.OFFICER_NAME,

R.REMARKS

FROM REPLANTATION R

LEFT JOIN TREE T
ON R.TREE_ID=T.TREE_ID

LEFT JOIN SPECIES_CATALOG S
ON R.SPECIES_ID=S.SPECIES_ID

LEFT JOIN LOCATIONZONE L
ON R.LOCATION_ID=L.LOCATION_ID

WHERE

UPPER(T.TREE_CODE) LIKE UPPER(:search)

OR

UPPER(S.COMMON_NAME) LIKE UPPER(:search)

OR

UPPER(L.LOCATION_NAME) LIKE UPPER(:search)

OR

UPPER(R.OFFICER_NAME) LIKE UPPER(:search)

ORDER BY

R.PLANTING_DATE DESC

";

$statement=oci_parse($conn,$sql);

$searchValue="%".$search."%";

oci_bind_by_name($statement,":search",$searchValue);

oci_execute($statement);

$sql="SELECT COUNT(*) TOTAL FROM REPLANTATION";

$s=oci_parse($conn,$sql);

oci_execute($s);

$r=oci_fetch_assoc($s);

$totalRecords=$r['TOTAL'];

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

🌱 Replantation Management

</h2>

<p class="text-muted">

Manage all tree replantation records.

</p>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <a
        href="add.php"
        class="btn btn-success">

            <i class="fa-solid fa-plus"></i>

            Add Replantation

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
                placeholder="Search Tree, Species, Location or Officer..."
                value="<?php echo htmlspecialchars($search); ?>">

                <button
                type="submit"
                class="btn btn-success">

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

<thead class="table-success">

<tr>

<th>Tree Code</th>

<th>Species</th>

<th>Location</th>

<th>Planting Date</th>

<th>Officer</th>

<th>Remarks</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>

<?php while($row=oci_fetch_assoc($statement)){ ?>

<tr>

<td><?php echo $row['TREE_CODE']; ?></td>

<td><?php echo $row['COMMON_NAME']; ?></td>

<td><?php echo $row['LOCATION_NAME']; ?></td>

<td><?php echo date("d/m/Y",strtotime($row['PLANTING_DATE'])); ?></td>

<td>

<?php echo $row['OFFICER_NAME']; ?>

</td>

<td>

<?php echo $row['REMARKS']; ?>

</td>

<td>

<a

href="details.php?id=<?php echo $row['REPLANT_ID']; ?>"

class="btn btn-outline-primary btn-sm">

<i class="fa-solid fa-eye"></i>

</a>

<a

href="edit.php?id=<?php echo $row['REPLANT_ID']; ?>"

class="btn btn-outline-warning btn-sm">

<i class="fa-solid fa-pen"></i>

</a>

<a

href="delete.php?id=<?php echo $row['REPLANT_ID']; ?>"

class="btn btn-outline-danger btn-sm"

onclick="return confirm('Delete this replantation record?');">

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