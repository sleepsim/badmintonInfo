<?php 

    include('functions.php');

    $itm_code = !empty($_POST['itm_code']) ? $_POST['itm_code'] : "";

    //If user is logged out, redirect them to login
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }

    //If user is logged in
    $username = $_SESSION['username'];

    // Add the item to watchlist
    if(!in_watchlist($itm_code)){
        $query = "INSERT INTO favourites (itm_code, username) VALUES (?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $itm_code, $username);
        $stmt->execute();

        //Old popup, feels very dated 
        // echo "<script> 
        //         alert(\"Added to favourites!\");
        //       </script>";
    }

    header("Location: equipmentdetails.php?itm_code=$itm_code");
    exit();
?>