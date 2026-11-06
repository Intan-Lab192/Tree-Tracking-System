<?php
session_start();
include("../config/db.php");

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM USERS
            WHERE USERNAME = :username
            AND PASSWORD = :password
            AND ACCOUNT_STATUS = 'Active'";

    $statement = oci_parse($conn, $sql);

    oci_bind_by_name($statement, ":username", $username);
    oci_bind_by_name($statement, ":password", $password);

    oci_execute($statement);

    if($row = oci_fetch_assoc($statement))
    {
        $_SESSION['username'] = $row['USERNAME'];
        $_SESSION['fullname'] = $row['FULL_NAME'];

        header("Location: ../dashboard/dashboard.php");
        exit;
    }
    else
    {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

TreeTrack Login

</title>

<link

href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"

rel="stylesheet">

<link

rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link

rel="stylesheet"

href="../assets/css/login.css">

</head>

<body>

<div class="login-container">

<div class="login-left">

<img

src="../assets/images/tree.png"

class="login-logo"

alt="TreeTrack Logo">

<h1>

TreeTrack

</h1>

<h4>

Tree Tracking Inventory System

</h4>

<p>

Manage • Monitor • Maintain

</p>

<div class="login-features">

<div>

<i class="fa-solid fa-tree"></i>

Tree Inventory Management

</div>

<div>

<i class="fa-solid fa-screwdriver-wrench"></i>

Maintenance Monitoring

</div>

<div>

<i class="fa-solid fa-map-location-dot"></i>

Interactive Location Mapping

</div>

<div>

<i class="fa-solid fa-triangle-exclamation"></i>

Risk Assessment Tracking

</div>

</div>

</div>

<div class="login-right">

<div class="login-card">

<h2>

Welcome Back

</h2>

<p class="text-muted">

Sign in to continue to TreeTrack.

</p>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Username

</label>

<div class="input-group">

<span class="input-group-text">

<i class="fa-solid fa-user"></i>

</span>

<input

type="text"

name="username"

class="form-control"

placeholder="Enter your username"

required>

</div>

</div>

<div class="mb-4">

<label class="form-label">

Password

</label>

<div class="input-group">

<span class="input-group-text">

<i class="fa-solid fa-lock"></i>

</span>

<input

type="password"

id="password"

name="password"

class="form-control"

placeholder="Enter your password"

required>

<button

class="btn btn-outline-secondary"

type="button"

id="togglePassword">

<i

class="fa-solid fa-eye"

id="toggleIcon">

</i>

</button>

</div>

</div>

<?php

if(isset($error))
{

?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php

}

?>

<div class="d-grid">

<button

type="submit"

name="login"

class="btn btn-login">

<i class="fa-solid fa-right-to-bracket"></i>

Login

</button>

</div>

</form>

<div class="login-footer">

© 2026 TreeTrack Inventory System

</div>

</div>

</div>

</div>

</body>

<script>

const password=document.getElementById("password");

const toggle=document.getElementById("togglePassword");

const icon=document.getElementById("toggleIcon");

toggle.addEventListener("click",function(){

if(password.type==="password")
{

password.type="text";

icon.classList.remove("fa-eye");

icon.classList.add("fa-eye-slash");

}
else
{

password.type="password";

icon.classList.remove("fa-eye-slash");

icon.classList.add("fa-eye");

}

});

</script>

</html>