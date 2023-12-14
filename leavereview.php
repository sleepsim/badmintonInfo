<?php 
    include('functions.php');

    $message = "";
    
    // Check item code
    if(isset($_POST['itm_code'])){
        $itemCode = $_POST['itm_code'];
    }else{
        header("Location: equipmentlist.php");
        exit();
    }

    // Checks if user inputted star rating
    if(isset($_POST['stars'])){
        $commentHolder = "";
        if(isset($_POST['reviewText'])){
            $commentHolder = $_POST['reviewText'];
        }
        $query = "INSERT INTO comments (itm_code, username, rating, comment) VALUES (?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ssss', $itemCode, $_SESSION['username'], $_POST['stars'], $commentHolder);
        $stmt->execute();

        echo "<script>
                alert(\"Review Added!\");
              </script>";
        header("Location: equipmentdetails.php?itm_code=$itemCode");
        exit();
    }else{ //If there are no stars, alerts users and returns them back to item page
        echo "<script>
                alert(\"Please enter number of stars!\");
                window.location = \"equipmentdetails.php?itm_code=$itemCode\";
              </script>";
        
        exit();   
    }
?>