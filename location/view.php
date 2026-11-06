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

SELECT *

FROM LOCATIONZONE

WHERE

UPPER(LOCATION_NAME) LIKE UPPER(:search)

OR

UPPER(ROAD_NAME) LIKE UPPER(:search)

ORDER BY

LOCATION_NAME

";

$statement=oci_parse($conn,$sql);

$searchValue="%".$search."%";

oci_bind_by_name($statement,":search",$searchValue);

oci_execute($statement);

$sql="SELECT COUNT(*) TOTAL FROM LOCATIONZONE";

$s=oci_parse($conn,$sql);

oci_execute($s);

$r=oci_fetch_assoc($s);

$totalLocation=$r['TOTAL'];

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2 class="mb-1">

📍 Location Management

</h2>

<p class="text-muted">

Manage all registered tree locations.

</p>

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<a

href="add.php"

class="btn btn-success">

<i class="fa-solid fa-plus"></i>

Add Location

</a>

</div>

<div style="width:420px;">

<form method="GET">

<div class="input-group">

<input

type="text"

name="search"

class="form-control"

placeholder="Search location..."

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

Total Locations :

<strong>

<?php echo $totalLocation; ?>

</strong>

</p>

<table class="table table-hover table-bordered shadow">

<thead class="table-success">

<tr>

<th>Location</th>

<th>Road</th>

<th>District / State</th>

<th>Traffic</th>

<th>Risk Priority</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>

<?php while($row=oci_fetch_assoc($statement)){ ?>

<tr>

<td>

<?php echo $row['LOCATION_NAME']; ?>

</td>

<td>

<?php echo $row['ROAD_NAME']; ?>

</td>

<td>

<?php echo $row['DISTRICT_STATE']; ?>

</td>

<td>

<?php echo $row['TRAFFIC_VOLUME']; ?>

</td>

<td>

<?php

$risk=$row['RISK_PRIORITY'];

if($risk=="Low")
{
    echo "<span class='badge bg-success'>Low</span>";
}
elseif($risk=="Medium")
{
    echo "<span class='badge bg-warning text-dark'>Medium</span>";
}
elseif($risk=="High")
{
    echo "<span class='badge bg-danger'>High</span>";
}
else
{
    echo $risk;
}

?>

</td>

<td>

<a

href="details.php?id=<?php echo $row['LOCATION_ID']; ?>"

class="btn btn-outline-primary btn-sm">

<i class="fa-solid fa-eye"></i>

</a>

<a

href="edit.php?id=<?php echo $row['LOCATION_ID']; ?>"

class="btn btn-outline-warning btn-sm">

<i class="fa-solid fa-pen"></i>

</a>

<a

href="delete.php?id=<?php echo $row['LOCATION_ID']; ?>"

class="btn btn-outline-danger btn-sm"

onclick="return confirm('Delete this location?');">

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