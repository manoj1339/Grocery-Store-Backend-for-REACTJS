<?php
require('include/db.php');
session_start();
if(!isset($_SESSION['admin_user']))
{
    header('location: login.php');
    exit();
}
else
{
    $admin = $_SESSION['admin_user'];
}

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $query = "DELETE FROM products WHERE ProductID='$id' LIMIT 1;";
    $result = mysqli_query($conn, $query);
    if($result)
    {
        header('location: index.php?success='.$id);
        exit();
    }
}
else
{
    header('location: index.php');
    exit();
}
?>