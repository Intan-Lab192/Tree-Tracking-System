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

/* ==========================================
   CHART 1
   REPLANTATION BY SPECIES
========================================== */

$speciesLabels=[];
$speciesTotals=[];

$sql="

SELECT

S.COMMON_NAME,

COUNT(*) TOTAL

FROM REPLANTATION R

JOIN SPECIES_CATALOG S

ON R.SPECIES_ID=S.SPECIES_ID

GROUP BY S.COMMON_NAME

ORDER BY S.COMMON_NAME

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $speciesLabels[]=$row['COMMON_NAME'];
    $speciesTotals[]=(int)$row['TOTAL'];
}

/* ==========================================
   CHART 2
   REPLANTATION BY LOCATION
========================================== */

$locationLabels=[];
$locationTotals=[];

$sql="

SELECT

L.LOCATION_NAME,

COUNT(*) TOTAL

FROM REPLANTATION R

JOIN LOCATIONZONE L

ON R.LOCATION_ID=L.LOCATION_ID

GROUP BY L.LOCATION_NAME

ORDER BY L.LOCATION_NAME

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $locationLabels[]=$row['LOCATION_NAME'];
    $locationTotals[]=(int)$row['TOTAL'];
}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

📊 Replantation Analytics

</h2>

<p class="text-muted">

Replantation statistics and reports.

</p>

<div class="row">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5>

Replantation by Species

</h5>

<div style="height:350px;">

<canvas id="speciesChart"></canvas>

</div>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5>

Replantation by Location

</h5>

<div style="height:350px;">

<canvas id="locationChart"></canvas>

</div>

</div>

</div>

</div>

</div>

<div class="mt-4">

<a

href="view.php"

class="btn btn-secondary">

<i class="fa-solid fa-arrow-left"></i>

Back to Replantation

</a>

</div>

</div>

<?php include("../includes/footer.php"); ?>

<script>

const speciesChart=document.getElementById("speciesChart");

new Chart(speciesChart,{

type:"doughnut",

data:{

labels:<?php echo json_encode($speciesLabels); ?>,

datasets:[{

data:<?php echo json_encode($speciesTotals); ?>,

backgroundColor:[

"#198754",

"#ffc107",

"#0dcaf0",

"#dc3545",

"#6f42c1",

"#fd7e14"

]

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{position:"right"}

}

}

});

const locationChart=document.getElementById("locationChart");

new Chart(locationChart,{

type:"bar",

data:{

labels:<?php echo json_encode($locationLabels); ?>,

datasets:[{

label:"Replantations",

data:<?php echo json_encode($locationTotals); ?>,

backgroundColor:"#198754"

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{display:false}

},

scales:{

y:{beginAtZero:true}

}

}

});

</script>