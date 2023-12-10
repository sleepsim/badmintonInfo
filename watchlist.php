<?php 
    include('functions.php');
    no_SSL();

    if(!isset($_SESSION['username'])){
        $_SESSION['redirect'] = 'watchlist.php';
        header("Location: login.php");
        exit();
    }

    // Get current logged in user
    $username = $_SESSION['username'];

    // Query to get list of watchlist items
    $query = "";

    // Header
    require('header.php');
?>