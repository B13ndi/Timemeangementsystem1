<?php
require('../public/db.inc.php');
session_start();
$msg = "";

if (isset($_POST['email']) && isset($_POST['password'])) {
    authenticate($con, $_POST['email'], $_POST['password']);
}

function authenticate(mysqli $database, $email, $password)
{
    $email = mysqli_real_escape_string($database, $email);
    $password = mysqli_real_escape_string($database, $password);

    $res = mysqli_query($database, "SELECT * FROM employee WHERE email='$email' AND password='$password'");
    $count = mysqli_num_rows($res);

    if ($count > 0) {
        $row = mysqli_fetch_assoc($res);
        setSession($row);
        header('location:../views/profile.php');
        die();
    } else {
        $msg = "Please enter correct details";
        header('location:../views/login.php?msg=' . $msg);
        die();
    }
}

function setSession($row)
{
    $_SESSION['role'] = $row['role'];
    $_SESSION['USER_ID'] = $row['employeeid'];
    $_SESSION['USER_NAME'] = $row['name'];
    $_SESSION['USER_DEPARTMENT_ID'] = $row['departmentId'];
}
?>
