<?php 
    require('header.php'); 
    include('functions.php');

    require_SSL();
    
    if(!isset($_POST['submit'])){
        $username = "";
        $password = "";
    }else{
        $username = !empty($_POST['username']) ? trim($_POST['username']) : "";
        $password = !empty($_POST["password"]) ? $_POST["password"] : "";

        // Check if fields are filled
        if(!$username || !$password){
            $message = "Please make sure all fields are filled";
        }else{
            $query = "SELECT username, password FROM user WHERE username = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s',$username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verify if the user exists, then check if password is correct
            if(mysqli_num_rows($result)>0){
                $hashed_password = mysqli_fetch_assoc($result)['password'];
                if(password_verify($password, $hashed_password)){
                    $_SESSION['username'] = $username;

                    // Let user know login was a success
                    echo "<script> 
                            alert(\"Login Success!\");
                            window.location = 'index.php';
                        </script>";
                }else{
                    // Let user know they entered the wrong password
                    $message = "The password you entered is incorrect";
                }
            }else{
                // Let user know the entered wrong username
                $message = "User not found, please check your username";
            }
        }
    }
    
?>


<div class="container mt-5">
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <form action="login.php" method="post">
                <h2 class="mb-5">Sign In</h2>
                <!-- Error Message -->
                <?php if(!empty($message)) echo '<p class="text-danger">' . $message . '</p>' ?>
                <label for="username">Username</label><br>
                <input type="username" name="username" class="form-control">
                <br>
                
                <label for="password">Password</label><br>
                <input type="password" name="password" class="form-control">
                <br><br>

                <input type="submit" name="submit" value="Login" class="btn btn-primary mb-3">
                <p>Need an account? <a href="register.php">Register Here</a></p>
            </form>
        </div>
        <div class="col"></div>
    </div>
</div>

<?php require('footer.php')?>