<?php 

    session_start();

    function require_SSL() {
        if($_SERVER['HTTPS'] != "on") {
            header("Location: https://" . $_SERVER['HTTP_HOST'] .
                $_SERVER['REQUEST_URI']);
            exit();
        }
    }

    function no_SSL() {
        if(isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS']== "on") {
            header("Location: http://" . $_SERVER['HTTP_HOST'] .
                $_SERVER['REQUEST_URI']);
            exit();
        }
    }

    $db = connectToDB('localhost', 'root', '', 'badmintoninfo');

    // Only ever used inside functions
    function connectToDB($dbhost, $dbuser, $dbpass, $dbname) {
        $connection = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        
        //try catch
        if (mysqli_connect_errno()) {
            //quit and display error and error number
            die("Database connection failed:" .
                mysqli_connect_error() .
                " (" . mysqli_connect_errno() . ")"
            );
        }
        return $connection;
    }

    // Checks if a user is logged in
    function is_logged_in() {
        return isset($_SESSION['username']);
    }

    // Check if item is in watchlist
    function in_watchlist($itm_code){
        global $db;

        if(isset($_SESSION['username'])){
            $query = "SELECT COUNT(*) FROM favourites WHERE itm_code = ? AND username = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ss', $itm_code, $_SESSION['username']);
            $stmt->execute();
            $stmt->bind_result($count);
            return ($stmt->fetch() && $count >0);
        }

        return false;
    }

    function is_following($player_id){
        global $db;

        if(isset($_SESSION['username'])){
            $query = "SELECT COUNT(*) FROM following WHERE id = ? AND username = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ss', $player_id, $_SESSION['username']);
            $stmt->execute();
            $stmt->bind_result($count);
            return ($stmt->fetch() && $count >0);
        }

        return false;
    }
?>