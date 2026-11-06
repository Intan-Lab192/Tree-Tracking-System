<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../authentication/login.php");
    exit;
}

include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$search = "";

$statusFilter = "";

if(isset($_GET['status']))
{
    $statusFilter = trim($_GET['status']);
}

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);
}

$sql = "SELECT
            T.TREE_ID,
            T.TREE_CODE,
            S.COMMON_NAME,
            L.LOCATION_NAME,
            T.HEIGHT,
            T.PLANTING_DATE,
            T.LAST_INSPECTION_DATE,
            T.CURRENT_STATUS,
            T.TREE_CONDITION
        FROM TREE T
        JOIN SPECIES_CATALOG S
            ON T.SPECIES_ID = S.SPECIES_ID
        JOIN LOCATIONZONE L
            ON T.LOCATION_ID = L.LOCATION_ID
        WHERE
(
    UPPER(T.TREE_CODE) LIKE UPPER(:search)
    OR UPPER(S.COMMON_NAME) LIKE UPPER(:search)
    OR UPPER(L.LOCATION_NAME) LIKE UPPER(:search)
)

AND

(
    :status IS NULL
    OR T.CURRENT_STATUS = :status
)
        ORDER BY T.TREE_ID";

$statement = oci_parse($conn,$sql);

$searchValue = "%".$search."%";

oci_bind_by_name($statement,":search",$searchValue);
$statusValue = ($statusFilter == "") ? null : $statusFilter;

oci_bind_by_name(
    $statement,
    ":status",
    $statusValue
);

oci_execute($statement);

/* Dashboard Cards */

$totalTrees = 0;
$healthyTrees = 0;
$mediumRisk = 0;
$highRisk = 0;

$sql = "SELECT COUNT(*) TOTAL FROM TREE";
$s = oci_parse($conn, $sql);
oci_execute($s);
$r = oci_fetch_assoc($s);
$totalTrees = $r['TOTAL'];

$sql = "SELECT COUNT(*) TOTAL FROM TREE WHERE CURRENT_STATUS='Healthy'";
$s = oci_parse($conn, $sql);
oci_execute($s);
$r = oci_fetch_assoc($s);
$healthyTrees = $r['TOTAL'];

$sql = "SELECT COUNT(*) TOTAL FROM TREE WHERE CURRENT_STATUS='Medium Risk'";
$s = oci_parse($conn, $sql);
oci_execute($s);
$r = oci_fetch_assoc($s);
$mediumRisk = $r['TOTAL'];

$sql = "SELECT COUNT(*) TOTAL FROM TREE WHERE CURRENT_STATUS='High Risk'";
$s = oci_parse($conn, $sql);
oci_execute($s);
$r = oci_fetch_assoc($s);
$highRisk = $r['TOTAL'];

?>

<div class="content">
<?php include("../includes/navbar.php"); ?>
<h2 class="mb-1">

🌳 Tree Inventory

</h2>

<p class="text-muted">

Manage and monitor all registered public trees.

</p>

<div class="row mb-4">

<div class="col-md-3">
<div class="card shadow">
<div class="card-body">
<h6>Total Trees</h6>
<h3><?php echo $totalTrees; ?></h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow">
<div class="card-body">
<h6>Healthy Trees</h6>
<h3><?php echo $healthyTrees; ?></h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow">
<div class="card-body">
<h6>Medium Risk</h6>
<h3><?php echo $mediumRisk; ?></h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow">
<div class="card-body">
<h6>High Risk</h6>
<h3><?php echo $highRisk; ?></h3>
</div>
</div>
</div>

</div>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <a
        href="add.php"
        class="btn btn-success">

            <i class="fa-solid fa-plus"></i>

            Add New Tree

        </a>

        <a
        href="analytics.php"
        class="btn btn-primary ms-3">

            <i class="fa-solid fa-chart-column"></i>

            Analytics

        </a>

    </div>

    <div style="width:520px;">

        <form method="GET">

            <div class="input-group">

                <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search Tree Code, Species or Location..."
                value="<?php echo htmlspecialchars($search); ?>">

                <select
                name="status"
                class="form-select">

                    <option value="">All Status</option>

                    <option value="Healthy"
                    <?php if($statusFilter=="Healthy") echo "selected"; ?>>
                    Healthy
                    </option>

                    <option value="Medium Risk"
                    <?php if($statusFilter=="Medium Risk") echo "selected"; ?>>
                    Medium Risk
                    </option>

                    <option value="High Risk"
                    <?php if($statusFilter=="High Risk") echo "selected"; ?>>
                    High Risk
                    </option>

                    <option value="Needs Inspection"
                    <?php if($statusFilter=="Needs Inspection") echo "selected"; ?>>
                    Needs Inspection
                    </option>

                </select>

                <button
                class="btn btn-success"
                type="submit">

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

<?php echo $totalTrees; ?>

</strong>

</p>

<table class="table table-hover table-bordered shadow">

<thead class="table-success">

<tr>

<th>Tree Code</th>

<th>Species</th>

<th>Location</th>

<th>Height (m)</th>

<th>Planting Date</th>

<th>Last Inspection</th>

<th>Status</th>

<th>Condition</th>

<th width="180">Action</th>

</tr>

</thead>

<tbody>

<?php while($row = oci_fetch_assoc($statement)) { ?>

<tr>

<td><?php echo $row['TREE_CODE']; ?></td>

<td><?php echo $row['COMMON_NAME']; ?></td>

<td><?php echo $row['LOCATION_NAME']; ?></td>

<td><?php echo $row['HEIGHT']; ?></td>

<td>

<?php

echo date(
"d/m/Y",
strtotime($row['PLANTING_DATE'])
);

?>

</td>

<td>

<?php

echo date(
"d/m/Y",
strtotime($row['LAST_INSPECTION_DATE'])
);

?>

</td>

<td>

<?php

$status = $row['CURRENT_STATUS'];

if($status=="Healthy")
{
    echo "<span class='badge bg-success'>Healthy</span>";
}
elseif($status=="Medium Risk")
{
    echo "<span class='badge bg-warning text-dark'>Medium Risk</span>";
}
elseif($status=="High Risk")
{
    echo "<span class='badge bg-danger'>High Risk</span>";
}
elseif($status=="Needs Inspection")
{
    echo "<span class='badge bg-info text-dark'>Needs Inspection</span>";
}
else
{
    echo "<span class='badge bg-secondary'>$status</span>";
}

?>

</td>

<td>

<?php

$condition = $row['TREE_CONDITION'];

if($condition=="Excellent")
{
    echo "<span class='badge bg-primary'>Excellent</span>";
}
elseif($condition=="Very Good")
{
    echo "<span class='badge bg-success'>Very Good</span>";
}
elseif($condition=="Good")
{
    echo "<span class='badge bg-success'>Good</span>";
}
elseif($condition=="Fair")
{
    echo "<span class='badge bg-warning text-dark'>Fair</span>";
}
elseif($condition=="Poor")
{
    echo "<span class='badge bg-danger'>Poor</span>";
}
else
{
    echo $condition;
}

?>

</td>

<td>

<a
href="details.php?id=<?php echo $row['TREE_ID']; ?>"
class="btn btn-outline-primary btn-sm">

<i class="fa-solid fa-eye"></i>

</a>

<a href="edit.php?id=<?php echo $row['TREE_ID']; ?>" class="btn btn-outline-warning btn-sm">
    <i class="fa-solid fa-pen"></i>
</a>

<a
href="delete.php?id=<?php echo $row['TREE_ID']; ?>"
class="btn btn-outline-danger btn-sm"
onclick="return confirm('Delete this tree?');">

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