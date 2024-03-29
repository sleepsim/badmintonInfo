<?php 
    include('functions.php');
    no_SSL();
?>


<?php require('header.php');?>

        <!-- Masthead header -->
        <div class="masthead text-center mb-5">
            <div class="masthead-overlay">
                 <!-- Main Title and sub text -->
            <h1 class="display-3 pt-5 mt-5 text-center text-white">Welcome to badmintonINFO</h1>
            <p class="lead text-center text-white">An online badminton wiki platform for all your favourite gear and players</p>

            <!-- Buttons -->
            <div class="container pt-4 pb-3">
                <div class="row justify-content-md-center">
                    <div class="col-lg-auto">
                        <a href="equipmentlist.php"><button type="button" class="btn btn-primary">View Equipment</button></a>
                    </div>
                    <div class="col-lg-auto">
                        <a href="playerlist.php"><button type="button" class="btn btn-primary">View Players</button></a>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <!-- Different sections -->
        <div class="container-fluid">

            <!--[SECTION] Rackets -->
            <div class="row justify-content-md-center mb-5">
                <!-- Info + Buttons -->
                <div class="col-md-auto">
                    <h2>Browse Rackets</h2>
                    <p>Browse popular rackets from different manufacturers</p>
                    <div class="d-flex flex-column m-3">
                        <a href="equipmentlist.php?type=racket&manufacturer=yonex" class="btn btn-dark m-1">Browse Yonex</a>
                        <a href="equipmentlist.php?type=racket&manufacturer=li ning" class="btn btn-dark m-1">Browse Li-Ning</a>
                        <a href="equipmentlist.php?type=racket&manufacturer=victor" class="btn btn-dark m-1">Browse Victor</a>
                        <a href="equipmentlist.php?type=racket" class="btn btn-primary m-1">Browse All</a>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-md-auto">
                     <img src="assets/arc11-p.webp" class="img-fluid mx-auto d-block w-50">
                </div>
            </div>

            <!-- [SECTION] Shoes -->
            <div class="row justify-content-md-center pt-5 bg-custom1 text-white mb-5 pb-5">
                <!-- Info + Buttons -->
                <div class="col-md-auto">
                    <img src="assets/SHB65Z.webp" class="img-fluid mx-auto d-block w-50">
                </div>

                <!-- Image -->
                <div class="col-md-auto">
                    <h2>Browse Shoes</h2>
                    <p>Browse shoes from different manufacturers</p>
                    <div class="d-flex flex-column m-3">
                        <a href="equipmentlist.php?type=shoes&manufacturer=yonex" class="btn btn-light m-1">Browse Yonex</a>
                        <a href="equipmentlist.php?type=shoes&manufacturer=li ning" class="btn btn-light m-1">Browse Li-Ning</a>
                        <a href="equipmentlist.php?type=shoes&manufacturer=victor" class="btn btn-light m-1">Browse Victor</a>
                        <a href="equipmentlist.php?type=shoes" class="btn btn-danger m-1">Browse All</a>
                    </div>
                </div>
            </div>

            <!-- [SECTION] Pro players -->
            <div class="row text-center mb-5">

                <div class="col-lg">
                    <h3>Pro players</h3>
                    <p>Find information about your favourite players</p>
                    <div class="d-flex justify-content-center">
                    </div>
                    <!-- Image -->
                    <img src="assets/players.jpg" class="img-fluid mx-auto d-block w-50">

                    <!-- Buttons -->
                    <div class="d-flex justify-content-center mt-3">
                        <a href="playerlist.php?gender=male" class="btn btn-dark m-1">Browse Men's</a>
                        <a href="playerlist.php?gender=female" class="btn btn-dark m-1">Browse Women's</a>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="playerlist.php" class="btn btn-dark m-1">Browse All Players</a>
                    </div>

                </div>
            </div>

        <!-- Section END -->
        </div>

<?php require('footer.php');?>


