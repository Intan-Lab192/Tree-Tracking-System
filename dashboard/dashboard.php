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

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2 class="mb-1">
Dashboard
</h2>

<p class="text-muted">
Overview of Tree Tracking Inventory System
</p>

<?php

/* ==========================================
   DASHBOARD CARDS
========================================== */

$sql="SELECT COUNT(*) TOTAL FROM TREE";
$s=oci_parse($conn,$sql);
oci_execute($s);
$r=oci_fetch_assoc($s);
$totalTrees=$r['TOTAL'];

$sql="SELECT COUNT(*) TOTAL FROM LOCATIONZONE";
$s=oci_parse($conn,$sql);
oci_execute($s);
$r=oci_fetch_assoc($s);
$totalLocation=$r['TOTAL'];

$sql="SELECT COUNT(*) TOTAL FROM SPECIES_CATALOG";
$s=oci_parse($conn,$sql);
oci_execute($s);
$r=oci_fetch_assoc($s);
$totalSpecies=$r['TOTAL'];

$sql="SELECT COUNT(*) TOTAL FROM MAINTENANCE";
$s=oci_parse($conn,$sql);
oci_execute($s);
$r=oci_fetch_assoc($s);
$totalMaintenance=$r['TOTAL'];


/* ==========================================
   CHART 1
   TREE STATUS
========================================== */

$statusLabels=[];
$statusTotals=[];

$sql="
SELECT
CURRENT_STATUS,
COUNT(*) TOTAL
FROM TREE
GROUP BY CURRENT_STATUS
ORDER BY CURRENT_STATUS
";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $statusLabels[]=$row['CURRENT_STATUS'];
    $statusTotals[]=(int)$row['TOTAL'];
}

/* =====================================
   MAP DATA
===================================== */

$treeLocations=[];

$sql="

SELECT

TREE_CODE,

CURRENT_STATUS,

LATITUDE,

LONGITUDE

FROM TREE

WHERE

LATITUDE IS NOT NULL

AND

LONGITUDE IS NOT NULL

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $treeLocations[]=$row;
}

/* =====================================
   RECENT ACTIVITIES
===================================== */

$activities=[];

/* Latest Tree Added */

$sql="

SELECT

TREE_CODE,

PLANTING_DATE

FROM TREE

ORDER BY PLANTING_DATE DESC

FETCH FIRST 3 ROWS ONLY

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $activities[]=[
        "icon"=>"fa-tree",
        "color"=>"success",
        "title"=>"Tree Added",
        "description"=>$row['TREE_CODE'],
        "date"=>$row['PLANTING_DATE']
    ];
}

/* Latest Maintenance */

$sql="

SELECT

T.TREE_CODE,

M.MAINTENANCE_DATE

FROM MAINTENANCE M

JOIN TREE T

ON M.TREE_ID=T.TREE_ID

ORDER BY M.MAINTENANCE_DATE DESC

FETCH FIRST 3 ROWS ONLY

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $activities[]=[
        "icon"=>"fa-screwdriver-wrench",
        "color"=>"warning",
        "title"=>"Maintenance",
        "description"=>$row['TREE_CODE'],
        "date"=>$row['MAINTENANCE_DATE']
    ];
}

/* Latest Risk Assessment */

$sql="

SELECT

T.TREE_CODE,

R.ASSESSMENT_DATE

FROM RISK_ASSESSMENT R

JOIN TREE T

ON R.TREE_ID=T.TREE_ID

ORDER BY R.ASSESSMENT_DATE DESC

FETCH FIRST 3 ROWS ONLY

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $activities[]=[
        "icon"=>"fa-triangle-exclamation",
        "color"=>"danger",
        "title"=>"Risk Assessment",
        "description"=>$row['TREE_CODE'],
        "date"=>$row['ASSESSMENT_DATE']
    ];
}

/* Sort newest first */

usort($activities,function($a,$b){

    return strtotime($b['date'])-strtotime($a['date']);

});

/* Keep latest five */

$activities=array_slice($activities,0,5);

?>

<div class="row">

<div class="col-md-3">

<div class="card shadow dashboard-card">

<div class="card-body">

<h6>

<i class="fa-solid fa-tree text-success"></i>

Total Trees

</h6>

<h2>

<?php echo $totalTrees; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow dashboard-card">

<div class="card-body">

<h6>

<i class="fa-solid fa-location-dot text-primary"></i>

Locations

</h6>

<h2>

<?php echo $totalLocation; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow dashboard-card">

<div class="card-body">

<h6>

<i class="fa-solid fa-leaf text-success"></i>

Species

</h6>

<h2>

<?php echo $totalSpecies; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow dashboard-card">

<div class="card-body">

<h6>

<i class="fa-solid fa-screwdriver-wrench text-warning"></i>

Maintenance

</h6>

<h2>

<?php echo $totalMaintenance; ?>

</h2>

</div>

</div>

</div>

</div>

<div class="row mt-4">

    <div class="col-md-8">

        <div class="card shadow">

            <div class="card-body">

                <h5>

                    <i class="fa-solid fa-chart-pie text-primary"></i>

                    Tree Status Overview

                </h5>

                <div style="height:300px;width:380px;margin:auto;">

                    <canvas id="statusChart"></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow">

            <div class="card-body">

                <h5>

                    <i class="fa-solid fa-circle-info text-primary"></i>

                    Quick Summary

                </h5>

                <table class="table table-borderless">

                    <tr>

                        <td>Total Trees</td>

                        <td><strong><?php echo $totalTrees; ?></strong></td>

                    </tr>

                    <tr>

                        <td>Species</td>

                        <td><strong><?php echo $totalSpecies; ?></strong></td>

                    </tr>

                    <tr>

                        <td>Locations</td>

                        <td><strong><?php echo $totalLocation; ?></strong></td>

                    </tr>

                    <tr>

                        <td>Maintenance</td>

                        <td><strong><?php echo $totalMaintenance; ?></strong></td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

<div class="row mt-4">

    <div class="col-md-12">

        <div class="card shadow">

            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    📍 Location Zone Map

                </h5>

                <a

                href="../locationmap/view.php"

                class="btn btn-light btn-sm">

                    Open Full Map

                </a>

            </div>

            <div class="card-body">

                <div

                id="dashboardMap"

                style="height:450px;border-radius:10px;">

                </div>

                <div class="mt-3">

                    <span class="badge bg-success">

                        Healthy

                    </span>

                    <span class="badge bg-warning text-dark">

                        Medium Risk

                    </span>

                    <span class="badge bg-danger">

                        High Risk

                    </span>

                    <span class="badge bg-primary">

                        Other

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row mt-4">

    <div class="col-md-12">

        <div class="card shadow">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">

                    <i class="fa-solid fa-clock-rotate-left"></i>

                    Recent Activities

                </h5>

            </div>

            <div class="card-body">

                <?php

                if(count($activities)==0)
                {

                    echo "<p class='text-muted'>No recent activities found.</p>";

                }
                else
                {

                    foreach($activities as $activity)
                    {

                ?>

                <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                    <div>

                        <i class="fa-solid <?php echo $activity['icon']; ?> text-<?php echo $activity['color']; ?>"></i>

                        <strong>

                            <?php echo $activity['title']; ?>

                        </strong>

                        <br>

                        <small class="text-muted">

                            Tree Code :

                            <?php echo $activity['description']; ?>

                        </small>

                    </div>

                    <div>

                        <small class="text-muted">

                            <?php

                            echo date(

                                "d/m/Y",

                                strtotime($activity['date'])

                            );

                            ?>

                        </small>

                    </div>

                </div>

                <?php

                    }

                }

                ?>

            </div>

        </div>

    </div>

</div>

<?php

include("../includes/footer.php");

?>

<script>

/* =====================================
   CHART 1
   TREE STATUS
===================================== */

const statusChart = document.getElementById("statusChart");

new Chart(statusChart,{

    type:"pie",

    data:{

        labels:<?php echo json_encode($statusLabels); ?>,

        datasets:[{

            label:"Trees",

            data:<?php echo json_encode($statusTotals); ?>,

            backgroundColor:[
                "#198754",
                "#ffc107",
                "#dc3545",
                "#0dcaf0",
                "#6f42c1"
            ],

            borderWidth:1

        }]

    },

    options:{

        responsive:true,

        maintainAspectRatio:false,

        plugins:{

            legend:{

                position:"right"

            }

        }

    }

});

</script>

<script>

var map=L.map('dashboardMap').setView(

[3.0738,101.5183],

12

);

L.tileLayer(

'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

{

maxZoom:19,

attribution:'© OpenStreetMap'

}

).addTo(map);
const treeLocations=<?php echo json_encode($treeLocations); ?>;
function getMarkerColor(status)
{

    if(status==="Healthy")
    {
        return "green";
    }

    if(status==="Medium Risk")
    {
        return "orange";
    }

    if(status==="High Risk")
    {
        return "red";
    }

    return "blue";

}

treeLocations.forEach(function(tree){

var marker=L.circleMarker(

[

parseFloat(tree.LATITUDE),

parseFloat(tree.LONGITUDE)

],

{

radius:8,

fillColor:getMarkerColor(tree.CURRENT_STATUS),

color:"#ffffff",

weight:2,

opacity:1,

fillOpacity:0.9

}

);

marker

.addTo(map)

.bindPopup(

"<b>"+tree.TREE_CODE+"</b><br>Status : "+tree.CURRENT_STATUS

);
});

</script>