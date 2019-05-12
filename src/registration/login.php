<?php
include "../lib/conn.php";
# Checks and performs actions if login event occurs
if (!empty($_POST) && ($_POST["signin"] == "Log in")){ // Signup occurred - There's probably a better way to do this

    $userEmail = htmlspecialchars($_POST["your_email"]);
    $userPassword = htmlspecialchars($_POST["your_pass"]);

    $hashedPassword = hash('sha256', $userPassword); 

    $sql = "SELECT UserId, Name, Email, Password 
            FROM Users 
            WHERE Password = \"" . $hashedPassword . "\" AND Email = \"" . $userEmail . "\"";

    $result = $conn->query($sql);
    
    # Checks if login is successful
    if ($result->num_rows == 0){
        echo "<script>alert(\"No accounts exist with those credentials. Please try again.\")</script>";
    }
    else{
        echo "<script>alert(\"Congrats on logging in. \")</script>";
        # Sends UserId to declare signin is successful
        while($row = $result->fetch_assoc()) {
            echo '<form id="sendData" action="../index.php" method="post">
                      <input type="hidden" name="UserId" value="'.$row["UserId"].'">
                  </form>';
            break;
        }
        echo "<script> document.getElementById('sendData').submit() </script>";

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
    <title>Digital Twin Login</title>
    
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="main">

        <!-- Sign in  Form -->
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="images/signin-image.jpg" alt="sign up image"></figure>
                        <a href="signup.php" class="signup-image-link">Create an account</a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Login</h2>
                        <form method="POST" class="register-form" id="login-form">
                            <div class="form-group">
                                <label for="your_name"><i class="zmdi zmdi-email"></i></label>
                                <input type="text" name="your_email" id="your_email" placeholder="Email"/>
                            </div>
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="your_pass" id="your_pass" placeholder="Password"/>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="remember-me" id="remember-me" class="agree-term" />
                                <label for="remember-me" class="label-agree-term"><span><span></span></span>Remember me</label>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signin" id="signin" class="form-submit" value="Log in"/>
                            </div>
                        </form>
                        <div class="social-login">
                            <span class="social-label">Or login with</span>
                            <ul class="socials">
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-facebook"></i></a></li>
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-twitter"></i></a></li>
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-google"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>