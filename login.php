<?php
session_start();
require "./components/head.php";
?>

    <form action="./processing/login-processing.php" method="POST">
        <h2>LOGIN</h2>
        <label for="username">Username</label>
        <input id="username" type="text" name="username" placeholder="Username" required><br>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Password" required><br>

        <button type="submit" name="submit">Login</button>
    </form>
    <?php
    if (isset($_SESSION['loginerror'])){
        echo $_SESSION['loginerror'];
    }
    require "./components/foot.php";
    ?>