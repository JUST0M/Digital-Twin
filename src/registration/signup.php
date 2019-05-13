<?php
include "../lib/conn.php";

// Checks and performs action if signup event occurred
if (!empty($_POST) && ($_POST["signup"] == "Register")){ // Signup occurred - There's probably a better way to do this
    $userName = htmlspecialchars($_POST["name"]);
    $userUsername = htmlspecialchars($_POST["username"]);
    $userPassword = htmlspecialchars($_POST["pass"]);

    $sql = "SELECT name, username, password
            FROM users
            WHERE name = \"" . $userName . "\" AND username = \"" . $userUsername . "\"";

    $result = $conn->query($sql);

    // Checks if account has been made before
    if ($result->num_rows != 0){ // Account was created before
           echo "<script>alert(\"The account with the same credentials has been created before. Please try again.\")</script>";
    }
    else{
        // Do some kind of hashing for the password
        $hashedPassword = hash('sha256', $userPassword);
        // Inserts account info database
        $createUserSql = "INSERT INTO users
                          (username, password)
                          VALUES
                          (\"" . $userUsername . "\", \"" . $hashedPassword . "\")";
        $flag = $conn->query($createUserSql);

        if($flag){
            echo "<script>alert(\"Your account has been made. \")</script>";
            echo "<script> window.location.href = \"login.php\"</script>";
        }
        else{
            echo "<script>alert(\"Account registration failed. Please try again.\")</script>";
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <title>Sign Up Form by Colorlib</title> -->
    <title>Digital Twin Signup</title>
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="main">

        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Sign up</h2>
                        <form method="POST" class="register-form" id="register-form" onsubmit="submitSignup()">
                            <div class="form-group">
                                <label for="username"><i class="zmdi zmdi-account"></i></label>
                                <input type="username" name="username" id="username" placeholder="Your Username"/>
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="pass" id="pass" placeholder="Password"/>
                            </div>
                            <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password"/>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                                <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in  <a href="#" class="term-service">Terms of service</a></label>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="Register"/>
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <<figure><img src="images/signup-image.jpg" alt="sign up image"></figure>
                        <a href="login.php" class="signup-image-link">I am already member</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/signup.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
