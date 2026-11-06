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
   MAINTENANCE TYPE
========================================== */

$typeLabels=[];
$typeTotals=[];

$sql="

SELECT

MAINTENANCE_TYPE,

COUNT(*) TOTAL

FROM MAINTENANCE

GROUP BY MAINTENANCE_TYPE

ORDER BY MAINTENANCE_TYPE

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $typeLabels[]=$row['MAINTENANCE_TYPE'];
    $typeTotals[]=(int)$row['TOTAL'];
}

/* ==========================================
   CHART 2
   MONTHLY MAINTENANCE
========================================== */

$monthLabels=[];
$monthTotals=[];

$sql="

SELECT

TO_CHAR(MAINTENANCE_DATE,'Mon') MONTH,

COUNT(*) TOTAL

FROM MAINTENANCE

GROUP BY TO_CHAR(MAINTENANCE_DATE,'Mon'),
EXTRACT(MONTH FROM MAINTENANCE_DATE)

ORDER BY EXTRACT(MONTH FROM MAINTENANCE_DATE)

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

📊 Maintenance Analytics

</h2>

<p class="text-muted">

Maintenance statistics and reports.

</p>

<div class="row">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5>

Maintenance Type Distribution

</h5>

<div style="height:350px;">

<canvas id="typeChart"></canvas>

</div>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5>

Monthly Maintenance

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

Back to Maintenance

</a>

</div>

</div>

<?php

include("../includes/footer.php");

?>

<script>

/* =====================================
   CHART 1
   MAINTENANCE TYPE
===================================== */

const typeChart=document.getElementById("typeChart");

new Chart(typeChart,{

type:"doughnut",

data:{

labels:<?php echo json_encode($typeLabels); ?>,

datasets:[{

data:<?php echo json_encode($typeTotals); ?>,

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

legend:{

position:"right"

}

}

}

});

/* =====================================
   CHART 2
   MONTHLY MAINTENANCE
===================================== */

const monthChart=document.getElementById("monthChart");

new Chart(monthChart,{

type:"bar",

data:{

labels:<?php echo json_encode($monthLabels); ?>,

datasets:[{

label:"Maintenance",

data:<?php echo json_encode($monthTotals); ?>,

backgroundColor:"#198754"

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{

display:false

}

},

scales:{

y:{

beginAtZero:true

}

}

}

});

</script>