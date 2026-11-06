<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../authentication/login.php");
    exit;
}

include("../config/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$id=$_GET['id'];

$sql="SELECT

T.*,

S.COMMON_NAME,

L.LOCATION_NAME

FROM TREE T

JOIN SPECIES_CATALOG S

ON T.SPECIES_ID=S.SPECIES_ID

JOIN LOCATIONZONE L

ON T.LOCATION_ID=L.LOCATION_ID

WHERE TREE_ID=:id";

$statement=oci_parse($conn,$sql);

oci_bind_by_name($statement,":id",$id);

oci_execute($statement);

$row=oci_fetch_assoc($statement);

?>

<div class="content">

<h2>Tree Details</h2>

<table class="table table-bordered">

<tr>

<th>Tree Code</th>

<td><?php echo $row['TREE_CODE']; ?></td>

</tr>

<tr>

<th>Species</th>

<td><?php echo $row['COMMON_NAME']; ?></td>

</tr>

<tr>

<th>Location</th>

<td><?php echo $row['LOCATION_NAME']; ?></td>

</tr>

<tr>

<th>Status</th>

<td><?php echo $row['CURRENT_STATUS']; ?></td>

</tr>

<tr>

<th>Condition</th>

<td><?php echo $row['TREE_CONDITION']; ?></td>

</tr>

<tr>

<th>Height</th>

<td><?php echo $row['HEIGHT']; ?> m</td>

</tr>

<tr>

<th>Diameter</th>

<td><?php echo $row['DIAMETER']; ?> m</td>

</tr>

<tr>

<th>Latitude</th>

<td><?php echo $row['LATITUDE']; ?></td>

</tr>

<tr>

<th>Longitude</th>

<td><?php echo $row['LONGITUDE']; ?></td>

</tr>

<tr>

<th>Notes</th>

<td><?php echo $row['NOTES']; ?></td>

</tr>

</table>

<a
href="view.php"
class="btn btn-secondary">

Back

</a>

</div>

<?php
include("../includes/footer.php");
?>