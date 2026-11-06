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
   RISK LEVEL DISTRIBUTION
========================================== */

$riskLabels=[];
$riskTotals=[];

$sql="

SELECT

RISK_LEVEL,

COUNT(*) TOTAL

FROM RISK_ASSESSMENT

GROUP BY RISK_LEVEL

ORDER BY RISK_LEVEL

";

$s=oci_parse($conn,$sql);

oci_execute($s);

while($row=oci_fetch_assoc($s))
{
    $riskLabels[]=$row['RISK_LEVEL'];
    $riskTotals[]=(int)$row['TOTAL'];
}

/* ==========================================
   CHART 2
   MONTHLY ASSESSMENTS
========================================== */

$monthLabels=[];
$monthTotals=[];

$sql="

SELECT

TO_CHAR(ASSESSMENT_DATE,'Mon') MONTH,

COUNT(*) TOTAL

FROM RISK_ASSESSMENT

GROUP BY

TO_CHAR(ASSESSMENT_DATE,'Mon'),

EXTRACT(MONTH FROM ASSESSMENT_DATE)

ORDER BY

EXTRACT(MONTH FROM ASSESSMENT_DATE)

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

📊 Risk Assessment Analytics

</h2>

<p class="text-muted">

Risk assessment statistics and reports.

</p>

<div class="row">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5>

Risk Level Distribution

</h5>

<div style="height:350px;">

<canvas id="riskChart"></canvas>

</div>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h5>

Monthly Assessments

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

Back to Risk Assessment

</a>

</div>

</div>

<?php

include("../includes/footer.php");

?>

<script>

/* =====================================
   CHART 1
   RISK LEVEL
===================================== */

const riskChart=document.getElementById("riskChart");

new Chart(riskChart,{

type:"doughnut",

data:{

labels:<?php echo json_encode($riskLabels); ?>,

datasets:[{

data:<?php echo json_encode($riskTotals); ?>,

backgroundColor:[

"#198754",

"#ffc107",

"#dc3545"

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

/* =====================================
   CHART 2
   MONTHLY ASSESSMENTS
===================================== */

const monthChart=document.getElementById("monthChart");

new Chart(monthChart,{

type:"bar",

data:{

labels:<?php echo json_encode($monthLabels); ?>,

datasets:[{

label:"Assessments",

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
