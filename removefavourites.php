<?php 

    include('functions.php');

    $itm_code = !empty($_POST['itm_code']) ? $_POST['itm_code'] : "";

    //If user is logged out, redirect them to login [REDUNDANT CODE, THIS SHOULD NEVER BE CLICKABLE IF NOT LOGGED IN]
    //Im keeping it just to be safe
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }

    //Grab username
    $username = $_SESSION['username'];

    //Remove item from watchlist
    if(in_watchlist($itm_code)){
        $query = "DELETE FROM favourites WHERE itm_code = ? AND username = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $itm_code, $username);
        $stmt->execute();
        
        echo "<script> 
                alert(\"Removed from favourites!\");
              </script>";
    }

    // If item was removed in favourite page, redirect there, otherwise redirect back to product page
    if(!empty($_POST['redirect']) && $_POST['redirect'] == 'favourites'){
        header("Location: favourites.php");
    }else{
        header("Location: equipmentdetails.php?itm_code=$itm_code");
    }
    exit();

?>