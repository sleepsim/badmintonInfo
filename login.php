<?php 
    require('header.php'); 
    include('functions.php');
?>


<div class="container mt-5">
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <form action="login.php" method="post">
                <h2 class="mb-5">Sign In</h2>
                <label for="email">Email</label><br>
                <input type="email" name="email" class="form-control">
                <br><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" class="form-control">
                <br><br>

                <input type="submit" name="submit" value="Submit" class="btn btn-primary mb-3">
                <p>Need an account? <a href="register.php">Register Here</a></p>
            </form>
        </div>
        <div class="col"></div>
    </div>
</div>

<?php require('footer.php')?>