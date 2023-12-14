<?php 

    include('functions.php');
    no_SSL();

    if(isset($_GET['id']) && !empty($_GET['id'])){
        $query = "SELECT * 
                  FROM player JOIN discipline on player.id = discipline.id 
                  WHERE player.id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $_GET['id']);
        $stmt->execute();   

        //Get prepared statement results
        $result = $stmt->get_result();
        $playerDetails = $result->fetch_assoc();

        //Set variables for players
        $id = $playerDetails['id'];
        $firstName = $playerDetails['firstName'];
        $lastName = $playerDetails['lastName'];
        $nationality = $playerDetails['nationality'];
        $age = $playerDetails['age'];
        $gender = $playerDetails['gender'];
        $img = $playerDetails['img'];
        $discipline = $playerDetails['type'];
        $rank = $playerDetails['rank'];


        $query2 = "SELECT *
                   FROM usedby JOIN equipment ON usedby.itm_code = equipment.itm_code 
                   WHERE usedby.id = ?";
        $stmt2 = $db->prepare($query2);
        $stmt2->bind_param('s', $_GET['id']);
        $stmt2->execute();
        $equipmentResult = $stmt2->get_result();
    }
?>


<?php require('header.php')?>

<div class="masthead-small text-center">
    <div class="masthead-overlay">
            <!-- Main Title and sub text -->
    <h1 class="display-3 pt-5 mt-5 text-center text-white">badmintonINFO</h1>
    <p class="lead text-center text-white">player information</p>

    </div>
</div>

<!-- Main Container -->
<div class="container-fluid">
    <div class="row pl-5 pt-3">
        <h6><a href="playerlist.php">All Players</a>  / <?php echo $firstName. " " . $lastName; ?></h6>
    </div>

    <div class="row justify-content-center">
        <!-- Left column, player image-->
        <div class="col-5 text-center ml-5 pl-5 mr-0 pr-0">
            <?php  
                echo '<img src="data:image;base64,' . base64_encode($img) . '" class="rounded img-fluid mx-auto w-50">';
            ?>
        </div>
        <!-- Right column, details -->
        <div class="col-4">
            <h2 class="mb-3"><?php echo $firstName . " " .$lastName; ?></h2>
            <p class="lead mb-4">0 Followers</p>
            <p>Nationality: <?php echo $playerDetails['nationality']; ?></p>
            <p>Age: <?php echo $playerDetails['age']; ?></p>
            <p>Gender: <?php echo $playerDetails['gender']; ?></p>
            <p>World Rank: <?php echo $rank?> - <?php echo $discipline?></p>

            <!-- If user is logged in, add to favourites button, checks if already in favourites -->
            <?php if(isset($_SESSION['username']) && !is_following($id)): ?>
                <form method="post" action="addtofavourites.php">
                    <input type="hidden" name="itm_code" value="<?= $id; ?>">
                    <button class="btn btn-primary" type="submit">Follow player</button>
                </form>
            <?php elseif(isset($_SESSION['username']) && is_following($id)): ?>
                <form action="removefavourites.php" method="post">
                    <input type="hidden" name="itm_code" value="<?= $id; ?>">
                    <p>&#10003; following player. <button class="btn btn-danger btn-sm" type="submit">unfollow</button></p>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Shows what equipment the player uses -->
    <div class="row justify-content-center mt-5">
        <h4>Equipment Used</h4>
    </div>

    <?php 
        $counter = 0;
        while ($row = mysqli_fetch_array($equipmentResult)) {
            // Start a new row for every two items
            if ($counter % 2 == 0) {
                echo '<div class="row align-items-end justify-content-center text-center mb-5">';
                // Add a spacer column before the items
                echo '<div class="col"></div>';
            }
            
            echo '<div class="col">';
            echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '">';
            echo '<img src="data:image;base64,' . base64_encode($row['img']) . '" class="img-fluid mx-auto w-50"></a><br>';
            echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '" class="list-link">' . $row['name'] . '</a>';
            echo '</div>';

            $counter++;

            // Close the row and add a spacer column after every two items
            if ($counter % 2 == 0) {
                echo '<div class="col"></div></div>';
            }
        }

        // Add an empty column and close the row if the number of items is odd
        if ($counter % 2 == 1) {
            echo '<div class="col"></div>';
            echo '<div class="col"></div></div>';
        }
    ?>

</div>


<?php 
    require('footer.php');
    // After rendering everything free the result.
    $result->free_result();
    $equipmentResult->free_result();
?>
