<?php 

include('functions.php');
no_SSL();

if (isset($_GET['gender']) && !empty($_GET['gender']) && ($_GET['gender'] != 'all')) {
    $query = "SELECT *
              FROM player JOIN discipline on player.id = discipline.id
              WHERE player.gender = ?";

    // Check if discipline is set
    if (isset($_GET['discipline']) && !empty($_GET['discipline']) && ($_GET['discipline'] != 'all')) {
        if($_GET['discipline'] == 'Singles'){
            $key1 = 'Womens Singles';
            $key2 = 'Mens Singles';
            $query .= " AND (discipline.type = ? OR discipline.type = ?)";
            $query .= " ORDER BY lastName";
            $stmt = $db->prepare($query);
            $stmt->bind_param('sss', $_GET['gender'], $key1, $key2);
        }
        if($_GET['discipline'] == 'Doubles'){
            $key1 = 'Mixed Doubles';
            $key2 = 'Mens Doubles';
            $key3 = 'Womens Doubles';
            $query .= " AND (discipline.type = ? OR discipline.type = ? OR discipline.type = ?)";
            $query .= " ORDER BY lastName";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssss', $_GET['gender'], $key1, $key2, $key3);
        }
    } else {
        $query .= " ORDER BY lastName";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $_GET['gender']);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else { //If filter isnt set
    $query = "SELECT *
              FROM player JOIN discipline on player.id = discipline.id";

    // Check if the manufacturer is set and not empty
    if (isset($_GET['discipline']) && !empty($_GET['discipline']) && ($_GET['discipline'] != 'all')) {
        if($_GET['discipline'] == 'Singles'){
            $key1 = 'Womens Singles';
            $key2 = 'Mens Singles';
            $query .= " WHERE discipline.type = ? OR discipline.type = ?";
            $query .= " ORDER BY lastName";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ss', $key1, $key2);
        }
        if($_GET['discipline'] == 'Doubles'){
            $key1 = 'Mixed Doubles';
            $key2 = 'Mens Doubles';
            $key3 = 'Womens Doubles';
            $query .= " WHERE discipline.type = ? OR discipline.type = ? OR discipline.type = ?";
            $query .= " ORDER BY lastName";
            $stmt = $db->prepare($query);
            $stmt->bind_param('sss', $key1, $key2, $key3);
        }
    } else {
        $query .= " ORDER BY lastName";
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
    <h1 class="display-3 pt-5 mt-5 text-center text-white">Professional Players</h1>
    <p class="lead text-center text-white">BWF Ranked Players</p>

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
                <label><input type="radio" name="discipline" value="all" checked> All</label><br>
                <label><input type="radio" name="discipline" value="Singles"> Singles</label><br>
                <label><input type="radio" name="discipline" value="Doubles"> Doubles</label><br>
                <!-- <label><input type="radio" name="manufacturer" value=""> Victor</label><br> -->

                <input type="submit" value="Filter" class="btn btn-primary">
            </form>

        </div>

        <div class="col ml-5">
            <?php
                $counter = 0;

                while ($row = mysqli_fetch_array($result)) {
                    if ($counter == 0) {
                        echo "<div class=\"row align-items-end\">";
                    }
                
                    echo "<div class=\"col text-center mb-3\">";
                    
                    if (isset($row['firstName'])) {
                        echo '<a href="playerdetails.php?id=' . $row['id'] . '"><img src="data:image;base64,' . base64_encode($row['img']) . '" class="rounded-circle img-fluid mx-auto w-50"></a><br>';
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var params = new URLSearchParams(window.location.search);

    // Set the selected value for the "gender" radio buttons
    var genderParam = params.get('gender');
    if (genderParam) {
      var genderRadioButton = document.querySelector('input[name="gender"][value="' + genderParam + '"]');
      if (genderRadioButton) {
        genderRadioButton.checked = true;
      }
    }

    // Set the selected value for the "discipline" radio buttons
    var disciplineParam = params.get('discipline');
    if (disciplineParam) {
      var disciplineRadioButton = document.querySelector('input[name="discipline"][value="' + disciplineParam + '"]');
      if (disciplineRadioButton) {
        disciplineRadioButton.checked = true;
      }
    }

    document.getElementById('filterForm').addEventListener('change', function() {
      var selectedGender = document.querySelector('input[name="gender"]:checked').value;
      var selectedDiscipline = document.querySelector('input[name="discipline"]:checked').value;
      console.log("Selected Gender:", selectedGender);
      console.log("Selected Discipline:", selectedDiscipline);
    });
  });
</script>