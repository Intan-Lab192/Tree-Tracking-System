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
   REMOVAL REASONS
========================================== */

$reasonLabels=[];
$reasonTotals=[];

$sql="

SELECT

REMOVAL_REASON,

COUNT(*) TOTAL

FROM TREE_REMOVAL

GROUP BY REMOVAL_REASON

ORDER BY REMOVAL_REASON

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $reasonLabels[]=$row['REMOVAL_REASON'];
    $reasonTotals[]=(int)$row['TOTAL'];
}

/* ==========================================
   CHART 2
   MONTHLY REMOVALS
========================================== */

$monthLabels=[];
$monthTotals=[];

$sql="

SELECT

TO_CHAR(REMOVAL_DATE,'Mon') MONTH,

COUNT(*) TOTAL

FROM TREE_REMOVAL

GROUP BY

TO_CHAR(REMOVAL_DATE,'Mon'),

EXTRACT(MONTH FROM REMOVAL_DATE)

ORDER BY

EXTRACT(MONTH FROM REMOVAL_DATE)

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $monthLabels[]=$row['MONTH'];
    $monthTotals[]=(int)$row['TOTAL'];
}

?>

<div class="content">

<?php include("../includes/navbar.php"); ?>

<h2>

📊 Tree Removal Analytics

</h2>

<p class="text-muted">

Tree removal statistics and reports.

</p>

<div class="row">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5>

Removal Reasons

</h5>

<div style="height:350px;">

<canvas id="reasonChart"></canvas>

</div>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5>

Monthly Removals

</h5>

<div style="height:350px;">

<canvas id="monthChart"></canvas>

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

Back to Tree Removal

</a>

</div>

</div>

<?php include("../includes/footer.php"); ?>

<script>

const reasonChart=document.getElementById("reasonChart");

new Chart(reasonChart,{

type:"doughnut",

data:{

labels:<?php echo json_encode($reasonLabels); ?>,

datasets:[{

data:<?php echo json_encode($reasonTotals); ?>,

backgroundColor:[

"#dc3545",

"#ffc107",

"#198754",

"#0dcaf0",

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

const monthChart=document.getElementById("monthChart");

new Chart(monthChart,{

type:"bar",

data:{

labels:<?php echo json_encode($monthLabels); ?>,

datasets:[{

label:"Removals",

data:<?php echo json_encode($monthTotals); ?>,

backgroundColor:"#dc3545"

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