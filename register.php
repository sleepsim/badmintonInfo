<?php 
    require('header.php'); 
    include('functions.php');

    require_SSL();

    // Check if submit is clicked
    if(isset($_POST['submit'])){
        $username = !empty($_POST['username']) ? trim($_POST['username']) : "";
        $email = !empty($_POST["email"]) ? trim($_POST["email"]) : "";
        $password = !empty($_POST["password"]) ? $_POST["password"] : "";
        $password2 = !empty($_POST["password2"]) ? $_POST["password2"] : "";
        
        // Check if passwords match, if no match send message
        if($password != $password2){
            $message = "Passwords do not match";
        }elseif(!$username || !$email || !$password || !$password2){ //Check all fields are filled
            $message = "Please make sure all fields are filled";
        }else{
            // Check if username already exists
            $usernameQuery = "SELECT * FROM user WHERE username = ?";
            $stmt0 = $db->prepare($usernameQuery);
            $stmt0->bind_param('s', $username);
            $stmt0->execute();
            $usernameCheckResult = $stmt0->get_result();

            // If email already exists, echo 
            if(mysqli_num_rows($usernameCheckResult)>0){
                $message = "Username already exists, please enter a different username";
                echo "<script>
                        alert(\"ERROR: username already exists!\");
                      </script>";
            }else{ //If not, convert password to hash then store all information
                $pw_encrypted = password_hash($password, PASSWORD_DEFAULT);
                $storeQuery = "INSERT INTO user(username, email, password) VALUES (?,?,?)";
                $stmt1 = $db->prepare($storeQuery);
                $stmt1->bind_param('sss', $username, $email, $pw_encrypted);
                $stmt1->execute();

                // Login the user on successful registration
                $_SESSION['username'] = $username;
                echo "<script>
                        alert(\"Registration Success!\");
                        window.location = 'index.php';
                      </script>";
                exit();
            }
        }
    }
?>


<div class="container mt-5">
    <div class="row">
        <!-- Spacer -->
        <div class="col"></div>
         <!--Main  -->
        <div class="col"> 
            <form action="register.php" method="post">
                <h2 class="mb-5">Register</h2>
                <!-- Error Message -->
                <?php if(!empty($message)) echo '<p class="text-danger">' . $message . '</p>' ?>
                <!-- Email -->
                <label for="email">Email</label><br>
                <input type="email" name="email" class="form-control">
                <br>
                <!-- Username -->
                <label for="username">Username</label><br>
                <input type="username" name="username" class="form-control">
                <br>
                <!-- Password -->
                <label for="password">Password</label><br>
                <input type="password" name="password" class="form-control">
                <br>
                <!-- Password Confirm-->
                <label for="password2">Re-Enter Password</label><br>
                <input type="password" name="password2" class="form-control">
                <br>
                <!-- Submit button -->
                <input type="submit" name="submit" value="submit" class="btn btn-primary mb-3">
                <p>Already a member? <a href="login.php">Login Here</a></p>
            </form>
        </div>
        <div class="col"></div> <!-- Spacer2-->
    </div>
</div>

<?php require('footer.php')?>

