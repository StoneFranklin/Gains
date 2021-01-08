<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "dbconnect.php";

$errors = "";

if(isset($_POST["submit"])){
    if(isset($_POST["username"]) && strlen(trim($_POST["username"])) > 0) {
        $username = trim($_POST["username"]);
    }
    else {
        $errors .= "Please enter your username.<br>";
    }

    if(isset($_POST["password"])) {
        $password = trim($_POST["password"]);
    }
    else {
        $errors .= "Please enter your password.<br>";
    }

    if(strlen($errors) > 0) {
        echo "<div class='alert alert-danger' role='alert'>";
        echo $errors;
        echo "</div>";
    }
    else {
        $select = $db->prepare('SELECT id, username, password FROM users WHERE username = ?');
        $select->bindParam(1, $username);
        $select->execute();

        if($select->rowCount() == 1) {
            $row = $select->fetch();
            $id = $row["id"];
            $username = $row["username"];
            $hashed_password = $row["password"];
            if(password_verify($password, $hashed_password)){ 
                session_start();

                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;  
                $_SESSION["categoryName"] = "Bodyweight";

                $select1 = $db->prepare('SELECT id FROM categories WHERE name = "Bodyweight"');
                $select1->execute();
                $catID = $select1->fetch();
                
                $_SESSION["categoryID"] = $catID["id"];
                 
                header("location: index.php");
            }
            else {
                $errors .= "The password you entered was not valid.<br>";
            }
        }
        else {
            $errors .= "No account found with that username";
        }
        if(strlen($errors) > 0) {
            echo "<div class='alert alert-danger' role='alert'>";
            echo $errors;
            echo "</div>";
        }
    }

}
    
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sign In</title>
<!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="gains.css">

<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	
</head>

<body>
    <br>
    <br>
	<form method="post" class="container form-container bg-dark">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" class="form-control"><br>
        </div>
       
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control"><br>
        </div>
        
        <input type="submit" name="submit" id="submit" class="btn btn-danger" value="Sign In">
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
	</form>
</body>
</html>