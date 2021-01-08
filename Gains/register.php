<?
    require_once "dbconnect.php";

    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: index.php");
        exit;
    }
    
  
    // Define variables and initialize with empty values
    $username = $password = "";
    $errors = "";

    if(isset($_POST["submit"]))
    {
        //Username validation
        if(isset($_POST["username"]) && strlen(trim($_POST["username"])) > 0) {
            $sql = $db->prepare('SELECT id FROM users WHERE username = ?');
            $sql->bindParam(1, trim($_POST["username"]));

            $sql->execute();

            if($sql->rowCount() == 1) {
                $errors .= "This username is already taken. <br>";
            }
            else {
                $username = trim($_POST["username"]);
            }
        }
        else {
            $errors .= "Please enter a username.<br>";
        }

        //Password validation
        if(isset($_POST["password"]) && strlen(trim($_POST["password"])) >= 8) {
            $password = trim($_POST["password"]);
        }
        else {
            $errors .= "Please enter a valid password.<br>";
        }

        if(isset($_POST["bodyweight"]) && is_numeric($_POST["bodyweight"])) {
            $bodyweight = trim($_POST["bodyweight"]);
        }
        else {
            $errors .= "Please enter a valid bodyweight.<br>";
        }

        if(isset($_POST["squat"]) && is_numeric($_POST["squat"])) {
            $squat = trim($_POST["squat"]);
        }
        else {
            $errors .= "Please enter a valid squat max.<br>";
        }

        if(isset($_POST["bench"]) && is_numeric($_POST["bench"])) {
            $bench = trim($_POST["bench"]);
        }
        else {
            $errors .= "Please enter a valid bench max.<br>";
        }

        if(isset($_POST["deadlift"]) && is_numeric($_POST["deadlift"])) {
            $deadlift = trim($_POST["deadlift"]);
        }
        else {
            $errors .= "Please enter a valid deadlift max.<br>";
        }

        if(isset($_POST["ohp"]) && is_numeric($_POST["ohp"])) {
            $ohp = trim($_POST["ohp"]);
        }
        else {
            $errors .= "Please enter a valid overhead press max.<br>";
        }
    
        if(strlen($errors) > 0) {
            echo "<div class='alert alert-danger' role='alert'>";
            echo $errors;
            echo "</div>";
        }
        else {
            $insert = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $insert->bindParam(1, $username);
            $insert->bindParam(2, password_hash($password, PASSWORD_DEFAULT));
            $insert->execute();

            $getUserID = $db->prepare('SELECT id FROM users WHERE username = ?');
            $getUserID->bindParam(1, $username);
            $getUserID->execute();
            $userID = $getUserID->fetch();

            $getCategories = $db->prepare('SELECT * FROM categories');
            $getCategories->execute();
            $categories = $getCategories->fetchAll();

            foreach($categories as $row) {
                switch($row["name"]) {
                    case "Bodyweight":
                        $bwID = $row["id"];
                    case "Squat":
                        $squatID = $row["id"];
                    case "Bench":
                        $benchID = $row["id"];
                    case "Deadlift":
                        $deadliftID = $row["id"];
                    case "Overhead Press":
                        $ohpID = $row["id"];
                }
            }

            $insert1 = $db->prepare('INSERT INTO entries (weight, category_id, user_id, day, month, year) VALUES 
                (:bodyweight, :bwID, :userID, :day, :month, :year),
                (:squat, :squatID, :userID, :day, :month, :year),
                (:bench, :benchID, :userID, :day, :month, :year),
                (:deadlift, :deadliftID, :userID, :day, :month, :year),
                (:ohp, :ohpID, :userID, :day, :month, :year)');
                
            $insert1->bindParam(':bodyweight', $bodyweight);
            $insert1->bindParam(':squat', $squat);
            $insert1->bindParam(':bench', $bench);
            $insert1->bindParam(':deadlift', $deadlift);
            $insert1->bindParam(':ohp', $ohp);

            $insert1->bindParam(':bwID', $bwID);
            $insert1->bindParam(':squatID', $squatID);
            $insert1->bindParam(':benchID', $benchID);
            $insert1->bindParam(':deadliftID', $deadliftID);
            $insert1->bindParam(':ohpID', $ohpID);

            $insert1->bindParam(':userID', $userID["id"]);
            $insert1->bindParam(':day', date("d"));
            $insert1->bindParam(':month', date("m"));
            $insert1->bindParam(':year', date("Y"));

            $insert1->execute();

            //$_SESSION["registeredUsername"] = $username;

            header("location: signin.php");
            exit;
        }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
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
            <label for="password">Password:(at least 8 characters)</label>
            <input type="password" name="password" id="password" class="form-control"><br>
        </div>

        <div class="form-group">
            <label for="bodyweight">Current Bodyweight</label>
            <input type="number" name="bodyweight" id="bodyweight" class="form-control" min="45" max="1000"><br>
        </div>

        <div class="form-group">
            <label for="squat">Current Squat Max</label>
            <input type="number" name="squat" id="squat" class="form-control" min="45" max="1000"><br>
        </div>

        <div class="form-group">
            <label for="bench">Current Bench Max</label>
            <input type="number" name="bench" id="bench" class="form-control" min="45" max="1000"><br>
        </div>

        <div class="form-group">
            <label for="deadlift">Current Deadlift Max</label>
            <input type="number" name="deadlift" id="deadlift" class="form-control" min="45" max="1000"><br>
        </div>

        <div class="form-group">
            <label for="ohp">Current Overhead Press Max</label>
            <input type="number" name="ohp" id="ohp" class="form-control" min="45" max="1000"><br>
        </div>

        
        <input type="submit" name="submit" id="submit" class="btn btn-danger" value="Create Account">
        <p>Already have an account? <a href="signin.php">Login here</a>.</p>
	</form>
</body>
</html>