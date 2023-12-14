<?php 

    include('functions.php');

    $itm_code = $_POST['itm_code'];

    if(isset($_POST['commentID'])){
        $commentID = $_POST['commentID'];
        $query = "DELETE FROM comments WHERE comment_ID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i',$commentID);
        $stmt->execute();

        header("Location: equipmentdetails.php?itm_code=$itm_code");

        exit();
    }

    header("Location: index.php");

    exit();

?>