<?php 

    include('functions.php');

    $player_id = !empty($_POST['player_id']) ? $_POST['player_id'] : "";

    //If user is logged out, redirect them to login
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }

    //If user is logged in
    $username = $_SESSION['username'];

    // Add the item to watchlist
    if(!is_following($player_id)){
        $query = "INSERT INTO following (id, username) VALUES (?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $player_id, $username);
        $stmt->execute();

    }

    // Header to return to item listing
    header("Location: playerdetails.php?id=$player_id");
    exit();
?>