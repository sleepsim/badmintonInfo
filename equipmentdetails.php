<?php 
    include('functions.php');
    no_SSL();
    

    if(isset($_GET['itm_code']) && !empty($_GET['itm_code'])){


        $initialQuery = "SELECT * FROM equipment WHERE itm_code = ?";
        $initStmt = $db->prepare($initialQuery);
        $initStmt->bind_param('s', $_GET['itm_code']);
        $initStmt->execute();
        $initResult = $initStmt->get_result();
        $initType = mysqli_fetch_assoc($initResult);

        if($initType['type'] == 'Racket'){
            $query = "SELECT * 
                  FROM equipment
                  JOIN racket ON equipment.itm_code = racket.itm_code
                  WHERE equipment.itm_code = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $_GET['itm_code']);
            $stmt->execute();

            $result = $stmt->get_result();
            $productDetails = $result->fetch_assoc();

            // Set variables for racket
            $itemCode = $productDetails['itm_code'];
            $manufacturer = $productDetails['manufacturer'];
            $weight = $productDetails['weight'];
            $balance = $productDetails['balance'];
            $stiffness = $productDetails['stiffness'];
            $img = $productDetails['img'];
            $itemName = $productDetails['name'];
        }else{
            $query = "SELECT * 
                  FROM equipment
                  JOIN shoes ON equipment.itm_code = shoes.itm_code
                  WHERE equipment.itm_code = ?";

            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $_GET['itm_code']);
            $stmt->execute();

            $result = $stmt->get_result();
            $productDetails = $result->fetch_assoc();

            // Set variables for non-racket
            $itemName = $productDetails['name'];
            $manufacturer = $productDetails['manufacturer'];
            $itemCode = $productDetails['itm_code'];
            $img = $productDetails['img'];
            $description = $productDetails['itm_desc'];
        }

        $initResult->free_result();

    }else{
        header("Location: equipmentlist.php");
    }

    

?>

<?php require('header.php')?>

<!-- Mastehead Image -->
<div class="masthead-small text-center">
    <div class="masthead-overlay">
            <!-- Main Title and sub text -->
    <h1 class="display-3 pt-5 mt-5 text-center text-white">badmintonINFO</h1>
    <p class="lead text-center text-white">equipment wiki</p>

    </div>
</div>

<div class="container-fluid">
    <div class="row pl-5 pt-3">
        <h6><a href="equipmentlist.php">All Equipment</a> / <a href="equipmentlist.php?type=all&manufacturer=<?php echo $manufacturer?>"><?php echo $manufacturer; ?></a> / <?php echo $productDetails['itm_code']; ?></h6>
    </div>

    <div class="row justify-content-center">
        <div class="col-5 text-center ml-5 pl-5 mr-0 pr-0">
            <?php  
                echo '<img src="data:image;base64,' . base64_encode($img) . '" class="img-fluid mx-auto w-50">';
            ?>
        </div>
        <div class="col-4">
            <h2 class="mt-5"><?php echo $productDetails['name']; ?></h2>
            <h5 class="mb-3 mt-3">Details</h5>

            <?php if ($productDetails['type'] == 'Racket'): ?>
            <p>Item Code: <?php echo $productDetails['itm_code']; ?></p>
            <p>Manufacturer: <?php echo $productDetails['manufacturer']; ?></p>
            <p>Weight: <?php echo $productDetails['weight']; ?></p>
            <p>Balance: <?php echo $productDetails['balance']; ?></p>
            <p>Stiffness: <?php echo $productDetails['stiffness']; ?></p>
            <?php else: ?>
            <p>Item Code: <?php echo $productDetails['itm_code']; ?></p>
            <p>Description: <?php echo $productDetails['itm_desc']; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <h2>Comments and Reviews</h2>
    </div>

</div>

<?php 
    require('footer.php');
    // After rendering everything free the result.
    $result->free_result();
?>
