<?php 

    include('functions.php');
    no_SSL();

    if(isset($_GET['id']) && !empty($_GET['id'])){
        $query = "SELECT * FROM player WHERE id = ?";
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

<div class="container-fluid">
    <div class="row pl-5 pt-3">
        <h6><a href="playerlist.php">All Players</a>  / <?php echo $firstName. " " . $lastName; ?></h6>
    </div>

    <div class="row justify-content-center">
        <div class="col-5 text-center ml-5 pl-5 mr-0 pr-0">
            <?php  
                echo '<img src="data:image;base64,' . base64_encode($img) . '" class="img-fluid mx-auto w-50">';
            ?>
        </div>
        <div class="col-4">
            <h2 class="mt-5"><?php echo $firstName . " " .$lastName; ?></h2>
            <h5 class="mb-3 mt-3">Details</h5>
            <p>First Name: <?php echo $playerDetails['firstName']; ?></p>
            <p>Last Name: <?php echo $playerDetails['lastName']; ?></p>
            <p>Nationality: <?php echo $playerDetails['nationality']; ?></p>
            <p>Age: <?php echo $playerDetails['age']; ?></p>
            <p>Gender: <?php echo $playerDetails['gender']; ?></p>
           
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
