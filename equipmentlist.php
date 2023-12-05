<?php 

include('functions.php');
no_SSL();

$testQuery = "SELECT equipment.name, equipment.itm_code, equipment.img
              FROM equipment LEFT JOIN racket ON equipment.itm_code = racket.itm_code 
              LEFT JOIN shoes ON equipment.itm_code = shoes.itm_code";
$result = $db->query($testQuery);



?>


<?php require('header.php') ?>

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

<div class="container pt-3">
    <div class="row justify-content-around">
        <div class="col-2">
            <h4>Filters</h4>
            <p>Type</p>
            <input type="radio" name="type" id="racket">
            <label for="racket">Racket</label><br>
            <input type="radio" name="type" id="shoes">
            <label for="shoes">Shoes</label>
            <p>Manufacturers</p>
            <input type="radio" name="brand" id="yonex">
            <label for="yonex">Yonex</label><br>
            <input type="radio" name="brand" id="lining">
            <label for="lining">Li Ning</label><br>
            <input type="radio" name="brand" id="victor">
            <label for="victor">Victor</label><br>

            <input type="submit" value="Filter">
        </div>

        <div class="col ml-5">
            <?php

                echo "wassup gang";
                $counter = 0;

                // while($row = mysqli_fetch_array($result)){

                //     if($counter == 0){
                //         echo "<div class=\"row\"";
                //     }

                //     echo "<div class=\"col text-center\">";
                //     echo '<img src="data:image;base64,'.base64_encode($row['img']).' class=\"img-fluid mx-auto w-50\""/>';
                //     echo "</div>";

                //     if($counter == 0){
                //         echo "</div>";
                //         $counter++;
                //     }else if($counter == 3){
                //         $counter = 0;
                //     }else{
                //         $counter++;
                //     }
                
                // }  
                
                while ($row = mysqli_fetch_array($result)) {
                    if ($counter == 0) {
                        echo "<div class=\"row align-items-end mb-5\">";
                    }
                
                    echo "<div class=\"col text-center\">";
                    echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '"><img src="data:image;base64,' . base64_encode($row['img']) . '" class="img-fluid mx-auto w-50"></a><br>';
                    echo '<a href="equipmentdetails.php?itm_code=' . $row['itm_code'] . '" class="list-link">' . $row['name'] . '</a>';
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
                    echo "</div>";
                }
            ?>
        </div>
    </div>

</div>

