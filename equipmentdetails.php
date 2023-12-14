<?php 
    include('functions.php');
    no_SSL();
    

    if(isset($_GET['itm_code']) && !empty($_GET['itm_code'])){

        // Initial query to check if the item is a racket or shoe
        $initialQuery = "SELECT * FROM equipment WHERE itm_code = ?";
        $initStmt = $db->prepare($initialQuery);
        $initStmt->bind_param('s', $_GET['itm_code']);
        $initStmt->execute();
        $initResult = $initStmt->get_result();
        $initType = mysqli_fetch_assoc($initResult);

        // If item can't be found in the database, return to equipmentlist.php
        if(!$initResult->num_rows >0){
            header("Location: equipmentlist.php");
        }

        // If item is a racket grab the details needed
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
        }else{ //If item is a shoe grab less details
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

        // Free initial query
        $initResult->free_result();

    }else{ //If empty, go back to equipmentlist
        header("Location: equipmentlist.php");
    }


    // Get number of people who favourited item
    $favquery = "SELECT * FROM favourites WHERE itm_code = ?";
    $stmt2 = $db->prepare($favquery);
    $stmt2->bind_param('s', $productDetails['itm_code']);
    $stmt2->execute();
    $countResult = $stmt2->get_result();
    $countNum = $countResult->num_rows;
    
    // Get all pro players who use this equipment
    $usedbyQuery = "SELECT * FROM  usedby JOIN player ON usedby.id = player.id
                    WHERE usedby.itm_code = ?";
    $usedbyStmt = $db->prepare($usedbyQuery);
    $usedbyStmt->bind_param('s',$productDetails['itm_code']);
    $usedbyStmt->execute();
    $usedbyResult = $usedbyStmt->get_result();

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

<!-- Main Container -->
<div class="container-fluid">

    <!-- Top left showing equipment/brand/product -->
    <div class="row pl-5 pt-3">
        <h6><a href="equipmentlist.php">All Equipment</a> / <a href="equipmentlist.php?type=all&manufacturer=<?php echo $manufacturer?>"><?php echo $manufacturer; ?></a> / <?php echo $productDetails['itm_code']; ?></h6>
    </div>

    <!-- Row containing image and details-->
    <div class="row justify-content-center">

        <!-- Image -->
        <div class="col-5 text-center ml-5 pl-5 mr-0 pr-0">
            <?php  
                echo '<img src="data:image;base64,' . base64_encode($img) . '" class="img-fluid mx-auto w-50">';
            ?>
        </div>

        <!-- Details -->
        <div class="col-4">
            <h2 class="mt-5"><?php echo $productDetails['name']; ?></h2>
            <h5 class="mb-3 mt-3">Details</h5>

            <?php if($productDetails['type'] == 'Racket'): ?>
            <p>Item Code: <?php echo $productDetails['itm_code']; ?></p>
            <p>Manufacturer: <?php echo $productDetails['manufacturer']; ?></p>
            <p>Weight: <?php echo $productDetails['weight']; ?></p>
            <p>Balance: <?php echo $productDetails['balance']; ?></p>
            <p>Stiffness: <?php echo $productDetails['stiffness']; ?></p>
            <?php else: ?>
            <p>Item Code: <?php echo $productDetails['itm_code']; ?></p>
            <p>Description: <?php echo $productDetails['itm_desc']; ?></p>
            <?php endif; ?>
            
            <!-- Number of people who have favourited item -->
            <?php echo "<p class=\"lead\">&#9733 $countNum favourites</p>"; ?>
            
            <!-- If user is logged in, add to favourites button, checks if already in favourites -->
            <?php if(isset($_SESSION['username']) && !in_watchlist($productDetails['itm_code'])): ?>
                <form method="post" action="addtofavourites.php">
                    <input type="hidden" name="itm_code" value="<?= $productDetails['itm_code']; ?>">
                    <button class="btn btn-primary" type="submit">Add to Favourites</button>
                </form>
            <?php elseif(isset($_SESSION['username']) && in_watchlist($productDetails['itm_code'])): ?>
                <form action="removefavourites.php" method="post">
                    <input type="hidden" name="itm_code" value="<?= $productDetails['itm_code']; ?>">
                    <p>&#10003; Item is in your favourites. <button class="btn btn-danger btn-sm" type="submit">remove</button></p>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Used by pros SECTION, show image and name -->
<div class="container-fluid bg-custom1 text-white">
    <div class="row justify-content-center mt-5 mb-3s">
        <h3 class="mt-5 pr-4">Used by</h3>
    </div>
    <!-- Row to hold columns -->
    <div class="row justify-content-center mt-5 text-center">
        <?php
            $count = 0; // Counter to keep track of items in the row
            while($row = mysqli_fetch_array($usedbyResult)) {
                // Convert blob to image
                $imageData = base64_encode($row['img']);
                $imageSrc = "data:image;base64," . $imageData;
    
                // Check if the counter is a multiple of 2
                if ($count % 3 == 0 && $count > 0) {
                    echo '</div>'; // Close the current row if it's not the first iteration
                    echo '<div class="row justify-content-center mt-3 text-center">'; // Start a new row
                }
        ?>
                <div class="col-md-auto mb-2">
                    <a href="playerdetails.php?id=<?= $row['id']?>"><img src="<?= $imageSrc; ?>" alt="<?= $row['firstName']; ?>" class="rounded-circle img-fluid mx-auto w-50"></a>
                    <a href="playerdetails.php?id=<?= $row['id']?>">
                        <p class="text-center mt-2 text-white"><?= $row['firstName'] . " " . $row['lastName'] ?></p>
                    </a>
                </div>
        <?php
                $count++;
            }
        ?>
    </div>
</div>


<!-- Row for reviews etc. -->
<div class="container-fluid mt-5">

<!-- Leave a review section, can only be seen if logged in--> 
<?php if(isset($_SESSION['username'])) : ?>
        <div class="row justify-content-center align-items-center">
            <div class="h5">Leave a review</div>
        </div>
        <div class="row text-center">
            <div class="col">
                <form action="leavereview.php" method="post">
                    <!-- Star system -->
                    <input type="hidden" name="itm_code" value="<?= $itemCode ?>">
                    <div class="row justify-content-center text-center">
                        <div class="rating">
                            <label>
                                <input type="radio" name="stars" value="1" />
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="2" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="3" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="4" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="5" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                        </div>
                    </div>
                    <!-- Textarea -->
                    <div class="row justify-content-center mt-3 mb-3">
                        <textarea name="reviewText" id="reviewText" cols="70" rows="5"></textarea>
                    </div>
                    <!-- Submit button -->
                    <div class="row justify-content-center mb-5">
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Ratings and reviews -->
    <div class="row justify-content-center mb-3">
        <h4 class="mr-2">Ratings and Reviews -</h4>
        <?php 
            $ratingQuery = "SELECT AVG(rating) FROM comments WHERE itm_code = ?";
            $ratingStmt = $db->prepare($ratingQuery);
            $ratingStmt->bind_param('s', $itemCode);
            $ratingStmt->execute();
            $ratingResult = $ratingStmt->get_result()->fetch_assoc();
            $reviewStarAverage = $ratingResult['AVG(rating)'];
        ?>
        <p>Avg:<span style="font-size:125%; color: rgb(255,234,0);">&#9733;</span><?= number_format((float)$reviewStarAverage, 1, '.', '')?>/5.0</p>

    </div>

    <!-- Show comments -->
    <div class="row justify-content-center text-center">
        <div class="col mb-5">
        <?php 
            $reviewsQuery = "SELECT * FROM comments WHERE itm_code = ?";
            $reviewsStmt = $db->prepare($reviewsQuery);
            $reviewsStmt->bind_param('s', $itemCode);
            $reviewsStmt->execute();
            $reviewsResult = $reviewsStmt->get_result();

            // Go through all and show comments/ratings
            while ($row = mysqli_fetch_array($reviewsResult)) {
                echo "<h5 class='mt-3'>" . $row['username'] . " " . $row['rating'] . "&#9733</h5>";
                
                echo "<p class='mt-3 mb-3'>" . $row['comment'] . "</p>";

                //If logged in user is author, give option to delete comment 
                if(isset($_SESSION['username'])){
                    if ($row['username'] == $_SESSION['username']) {
                        echo "<form action='deletecomment.php' method='post'>";
                        echo "<input type='hidden' name='commentID' value='" . $row['comment_ID'] . "'>";
                        echo "<input type='hidden' name='itm_code' value='" . $itemCode . "'>";
                        echo "<input type='submit' class='submit-textonly-style' value='delete comment'>";
                        echo "</form>";
                    }       
                }     
            }

        ?>
        </div>
    </div>

    <?php 
        if(isset($_GET['stars']));
    ?>
</div>

<?php 
    require('footer.php');
    // After rendering everything free the result.
    $result->free_result();
    $countResult->free_result();
    $usedbyResult->free_result();
?>
