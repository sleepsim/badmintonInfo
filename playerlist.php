<?php 

include('functions.php');
no_SSL();

if (isset($_GET['gender']) && !empty($_GET['gender']) && ($_GET['gender'] != 'all')) {
    $query = "SELECT *
              FROM player
              WHERE gender = ?";

    // Check if gender is set
    if (isset($_GET['discipline']) && !empty($_GET['discipline']) && ($_GET['discipline'] != 'all')) {
        $query .= " AND discipline = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $_GET['gender'], $_GET['discipline']);
    } else {
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $_GET['gender']);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else { //If filter isnt set
    $query = "SELECT *
              FROM player";

    // Check if the manufacturer is set and not empty
    if (isset($_GET['discipline']) && !empty($_GET['discipline']) && ($_GET['discipline'] != 'all')) {
        $query .= " WHERE equipment.manufacturer = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $_GET['discipline']);
    } else {
        $query .= " ORDER BY firstName";
        $stmt = $db->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();
}


?>


<?php require('header.php') ?>

<!-- Masthead image -->
<div class="masthead-small text-center">
    <div class="masthead-overlay">
            <!-- Main Title and sub text -->
    <h1 class="display-3 pt-5 mt-5 text-center text-white">badmintonINFO</h1>
    <p class="lead text-center text-white">professional players</p>

    </div>
</div>

<div class="container pt-3">
    <div class="row ml-0"><h6><a href="playerlist.php">All Players</a> / </h6></div>

    <div class="row justify-content-center"><h2>Professional Players</h2></div>
</div>

<div class="container pt-3 mb-5">
    <div class="row justify-content-around">
        <div class="col-2">
            <h4>Filters</h4>
            <form action="playerlist.php" method="get">
                <p>Gender</p>
                <label><input type="radio" name="gender" value="all" checked> All</label> <br>
                <label><input type="radio" name="gender" value="male"> Men</label> <br>
                <label><input type="radio" name="gender" value="female"> Women</label> <br>
                <p>Discipline</p>
                <label><input type="radio" name="" value="all" checked> All</label><br>
                <label><input type="radio" name="" value="Singles"> Singles</label><br>
                <label><input type="radio" name="" value="Doubles"> Doubles</label><br>
                <!-- <label><input type="radio" name="manufacturer" value=""> Victor</label><br> -->

                <input type="submit" value="Filter">
            </form>

        </div>

        <div class="col ml-5">
            <?php
                $counter = 0;

                while ($row = mysqli_fetch_array($result)) {
                    if ($counter == 0) {
                        echo "<div class=\"row align-items-end\">";
                    }
                
                    echo "<div class=\"col text-center\">";
                    
                    if (isset($row['firstName'])) {
                        echo '<a href="playerdetails.php?id=' . $row['id'] . '"><img src="data:image;base64,' . base64_encode($row['img']) . '" class="img-fluid mx-auto w-50"></a><br>';
                        echo '<a href="playerdetails.php?id=' . $row['id'] . '" class="list-link">' . $row['lastName'] . ", ".  $row['firstName'] . '</a>';
                    } else {
                        // Empty column
                        echo "&nbsp;";
                    }
                    
                    echo "</div>";
                
                    if ($counter == 2) { // Adjust this condition based on the number of columns you want per row
                        echo "</div>"; // Close the row div
                        $counter = 0;
                    } else {
                        $counter++;
                    }
                }
                
                // Close the row div if it's not already closed
                if ($counter != 0) {
                    // Add empty columns to fill the row
                    for ($i = $counter; $i < 3; $i++) {
                        echo '<div class="col text-center">&nbsp;</div>';
                    }
                    echo "</div>";
                }
            ?>
        </div>
    </div>

</div>

<?php 
    require("footer.php");
    // After rendering everything free the result.
    $result->free_result();
?>
