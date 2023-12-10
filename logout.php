<?php
    include('functions.php');
    session_start();
    session_destroy();

    //Popout to let user know logout was successful
    echo "<script>
            alert(\"Logout Success!\");
            window.location = 'index.php';
          </script>";
    exit();
?>