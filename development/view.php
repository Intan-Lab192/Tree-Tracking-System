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

⚙️ Development

</h2>

<p class="text-muted">

Manage system master data and future administrative settings.

</p>

<div class="row mt-4">

<div class="col-md-6">

<div class="card shadow h-100">

<div class="card-body">

<h4>

🌿 Species Management

</h4>

<p class="text-muted">

Manage all registered tree species used throughout the system.

</p>

<a

href="../species/view.php"

class="btn btn-success">

Open Species

</a>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow h-100">

<div class="card-body">

<h4>

📍 Location Management

</h4>

<p class="text-muted">

Manage all registered locations and development zones.

</p>

<a

href="../location/view.php"

class="btn btn-primary">

Open Locations

</a>

</div>

</div>

</div>

</div>

<hr class="my-5">

<h4>

🚀 Future Expansion

</h4>

<div class="row mt-3">

<div class="col-md-4">

<div class="card border-secondary">

<div class="card-body text-center">

<i class="fa-solid fa-users fa-2x mb-3 text-secondary"></i>

<h5>

User Roles

</h5>

<p class="text-muted">

Future feature.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-secondary">

<div class="card-body text-center">

<i class="fa-solid fa-database fa-2x mb-3 text-secondary"></i>

<h5>

Backup

</h5>

<p class="text-muted">

Future feature.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-secondary">

<div class="card-body text-center">

<i class="fa-solid fa-gear fa-2x mb-3 text-secondary"></i>

<h5>

System Settings

</h5>

<p class="text-muted">

Future feature.

</p>

</div>

</div>

</div>

</div>

</div>

<?php

include("../includes/footer.php");

?>