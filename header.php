<!doctype html>

<html lang="en">
    
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title>352 Final template</title>

        <!-- CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">

        

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;700&display=swap" rel="stylesheet">
        
    </head>

    <body>
        <!-- Navbar -->
        <div class="navbar navbar-expand-sm">
            <a class="navbar-brand" href="index.php">badmintonINFO</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="equipmentlist.php">Equipment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="playerlist.php">Pro-Players</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <?php 
                        // If logged in, change right buttons to logout, else register/login
                        if(isset($_SESSION['username'])){
                            echo  "<li class=\"nav-item\">
                                  <a class=\"nav-link\" href=\"logout.php\">Logout</a>
                                  </li>";
                        }else{
                            echo "<li class=\"nav-item\">
                                    <a class=\"nav-link\" href=\"register.php\">Register</a>
                                  </li>";

                            echo "<li class=\"nav-item\">
                                    <a class=\"nav-link\" href=\"login.php\"> Login</a>
                                  </li>";
                        }
                    ?>
                </ul>
            </div>
        </div>