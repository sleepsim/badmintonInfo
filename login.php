<?php require('header.php') ?>

<h2>Sign In</h2>
<form action="login.php" method="post">
    <label for="email">Email</label><br>
    <input type="email" name="email">
    <br><br>
    
    <label for="password">Password</label><br>
    <input type="password" name="password">
    <br><br>

    <input type="submit" name="submit" value="submit">
    <p>Need an account? <a href="register.php">Register Here</a></p>

</form>