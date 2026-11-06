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

FROM SPECIES_CATALOG

WHERE

UPPER(COMMON_NAME) LIKE UPPER(:search)

OR

UPPER(BOTANICAL_NAME) LIKE UPPER(:search)

ORDER BY

COMMON_NAME

";

$statement=oci_parse($conn,$sql);

$searchValue="%".$search."%";

oci_bind_by_name($statement,":search",$searchValue);

oci_execute($statement);

$sql="SELECT COUNT(*) TOTAL FROM SPECIES_CATALOG";

$s=oci_parse($conn,$sql);

oci_execute($s);

$r=oci_fetch_assoc($s);

$totalSpecies=$r['TOTAL'];

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2 class="mb-1">

🌿 Species Management

</h2>

<p class="text-muted">

Manage all registered tree species.

</p>

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<a

href="add.php"

class="btn btn-success">

<i class="fa-solid fa-plus"></i>

Add Species

</a>

</div>

<div style="width:420px;">

<form method="GET">

<div class="input-group">

<input

type="text"

name="search"

class="form-control"

placeholder="Search species..."

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

Total Species :

<strong>

<?php echo $totalSpecies; ?>

</strong>

</p>

<table class="table table-hover table-bordered shadow">

<thead class="table-success">

<tr>

<th>Common Name</th>

<th>Botanical Name</th>

<th>Growth Rate</th>

<th>Life Span</th>

<th>Risk Factor</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>

<?php while($row=oci_fetch_assoc($statement)){ ?>

<tr>

<td>

<?php echo $row['COMMON_NAME']; ?>

</td>

<td>

<i>

<?php echo $row['BOTANICAL_NAME']; ?>

</i>

</td>

<td>

<?php

$growth=$row['GROWTH_RATE'];

if($growth=="Fast")
{
    echo "<span class='badge bg-success'>Fast</span>";
}
elseif($growth=="Medium")
{
    echo "<span class='badge bg-warning text-dark'>Medium</span>";
}
elseif($growth=="Slow")
{
    echo "<span class='badge bg-secondary'>Slow</span>";
}
else
{
    echo $growth;
}

?>

</td>

<td>

<?php echo $row['MAX_LIFE_SPAN']; ?> Years

</td>

<td>

<?php

$risk=$row['RISK_FACTOR'];

if($risk=="Low")
{
    echo "<span class='badge bg-success'>Low</span>";
}
elseif($risk=="Med")
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

href="details.php?id=<?php echo $row['SPECIES_ID']; ?>"

class="btn btn-outline-primary btn-sm">

<i class="fa-solid fa-eye"></i>

</a>

<a

href="edit.php?id=<?php echo $row['SPECIES_ID']; ?>"

class="btn btn-outline-warning btn-sm">

<i class="fa-solid fa-pen"></i>

</a>

<a

href="delete.php?id=<?php echo $row['SPECIES_ID']; ?>"

class="btn btn-outline-danger btn-sm"

onclick="return confirm('Delete this species?');">

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