<?php 

    include('functions.php');

    $itm_code = $_POST['itm_code'];

    // SQL Command
    if(isset($_POST['commentID'])){
        $commentID = $_POST['commentID'];
        $query = "DELETE FROM comments WHERE comment_ID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i',$commentID);
        $stmt->execute();

        header("Location: equipmentdetails.php?itm_code=$itm_code");

        exit();
    }

    // If the comment doesn't exist and accidentally loads this page, return to this
    header("Location: index.php");

    exit();

?>