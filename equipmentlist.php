<?php 

include('functions.php');
no_SSL();

if (isset($_GET['type']) && !empty($_GET['type']) && ($_GET['type'] != 'all')) {
    $query = "SELECT equipment.name, equipment.itm_code, equipment.img
              FROM equipment 
              LEFT JOIN racket ON equipment.itm_code = racket.itm_code 
              LEFT JOIN shoes ON equipment.itm_code = shoes.itm_code 
              WHERE equipment.type = ?";

    // Check if the manufacturer is set and not empty
    if (isset($_GET['manufacturer']) && !empty($_GET['manufacturer']) && ($_GET['manufacturer'] != 'all')) {
        $query .= " AND equipment.manufacturer = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $_GET['type'], $_GET['manufacturer']);
    } else {
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $_GET['type']);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else { //If filter isnt set
    $query = "SELECT equipment.name, equipment.itm_code, equipment.img
              FROM equipment 
              LEFT JOIN racket ON equipment.itm_code = racket.itm_code 
              LEFT JOIN shoes ON equipment.itm_code = shoes.itm_code";

    // Check if the manufacturer is set and not empty
    if (isset($_GET['manufacturer']) && !empty($_GET['manufacturer']) && ($_GET['manufacturer'] != 'all')) {
        $query .= " WHERE equipment.manufacturer = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $_GET['manufacturer']);
    } else {
        $query .= " ORDER BY equipment.type, equipment.name";
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
    <p class="lead text-center text-white">equipment wiki</p>

    </div>
</div>

<div class="container pt-3">
    <div class="row ml-0"><h6><a href="equipmentlist.php">All Equipment</a> / </h6></div>

    <div class="row justify-content-center"><h2>Badminton Rackets</h2></div>
</div>

<div class="container pt-3 mb-5">
    <div class="row justify-content-around">
        <div class="col-2">
            <h4>Filters</h4>
            <p>Type</p>
            <form action="equipmentlist.php" method="get">
                <label><input type="radio" name="type" value="all" checked> All</label> <br>
                <label><input type="radio" name="type" value="racket"> Racket</label> <br>
                <label><input type="radio" name="type" value="shoes"> Shoes</label> <br>
                <p>Manufacturers</p>
                <label><input type="radio" name="manufacturer" value="all" checked> All</label><br>
                <label><input type="radio" name="manufacturer" value="yonex"> Yonex</label><br>
                <label><input type="radio" name="manufacturer" value="li ning"> Li Ning</label><br>
                <label><input type="radio" name="manufacturer" value="victor"> Victor</label><br>

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
                    
                    if (isset($row['itm_code'])) {
                        echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '"><img src="data:image;base64,' . base64_encode($row['img']) . '" class="img-fluid mx-auto w-50"></a><br>';
                        echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '" class="list-link">' . $row['name'] . '</a>';
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

<!-- JS for choosing which radio -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var params = new URLSearchParams(window.location.search);

    // Set the selected value for the "type" radio buttons
    var typeParam = params.get('type');
    if (typeParam) {
      var typeRadioButton = document.querySelector('input[name="type"][value="' + typeParam + '"]');
      if (typeRadioButton) {
        typeRadioButton.checked = true;
      }
    }

    // Set the selected value for the "manufacturer" radio buttons
    var manufacturerParam = params.get('manufacturer');
    if (manufacturerParam) {
      var manufacturerRadioButton = document.querySelector('input[name="manufacturer"][value="' + manufacturerParam + '"]');
      if (manufacturerRadioButton) {
        manufacturerRadioButton.checked = true;
      }
    }

    document.getElementById('filterForm').addEventListener('change', function() {
      var selectedType = document.querySelector('input[name="type"]:checked').value;
      var selectedManufacturer = document.querySelector('input[name="manufacturer"]:checked').value;
      console.log("Selected Type:", selectedType);
      console.log("Selected Manufacturer:", selectedManufacturer);
    });
  });
</script>
