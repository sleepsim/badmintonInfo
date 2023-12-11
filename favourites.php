<?php 
    include('functions.php');
    no_SSL();

    if(!isset($_SESSION['username'])){
        $_SESSION['redirect'] = 'watchlist.php';
        header("Location: login.php");
        exit();
    }

    // Get current logged in user
    $username = $_SESSION['username'];

    // Query to get list of watchlist items
    $query = "SELECT * 
              FROM equipment JOIN favourites on equipment.itm_code = favourites.itm_code
              WHERE favourites.username = ?";
    $query .= " ORDER BY equipment.type, equipment.name";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

?>

<!-- Header -->
<?php require('header.php');?>

<!-- Masthead image -->
<div class="masthead-small text-center">
    <div class="masthead-overlay">
            <!-- Main Title and sub text -->
    <h1 class="display-3 pt-5 mt-5 text-center text-white">My Favourites</h1>
    <p class="lead text-center text-white">keep track of your favourite gear</p>
    </div>
</div>

<div class="container pt-3 mb-5">
    <div class="row justify-content-center">
        <h2><?= $_SESSION['username'] ?>'s Favourites</h2>
    </div>

    <div class="col">
        <?php
            $counter = 0;

            while ($row = mysqli_fetch_array($result)) {
                if ($counter == 0) {
                    echo "<div class=\"row align-items-end\">";
                }
            
                echo "<div class=\"col text-center\">";
                
                if (isset($row['itm_code'])) {
                    echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '"><img src="data:image;base64,' . base64_encode($row['img']) . '" class="img-fluid mx-auto w-50"></a><br>';
                    echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '" class="list-link">' . $row['name'] . '</a>';
                    echo '<form action="removefavourites.php" method="post">';
                    echo '<input type="hidden" name="itm_code" value="' . $row['itm_code'] . '">';
                    echo '<input type="hidden" name="redirect" value="favourites">';
                    echo '<p><button class="btn btn-danger btn-sm" type="submit">remove</button></p>';
                    echo '</form>';
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


<?php 

    // while($row = mysqli_fetch_assoc($result)){
    //     $name = $row['name'];
    //     $itm_code = $row['itm_code'];
    //     $img = $row['img'];

    //     echo "<li>";
    //     echo "$name + $itm_code";
    //     echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '"><img src="data:image;base64,' . base64_encode($row['img']) . '" class="img-fluid mx-auto w-50"></a><br>';
    //     echo "</li>";
    // }

?>
