<?php
    require_once "dbconnect.php";
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signin.php");
        exit();
    }
    
    $errors = "";

    if(isset($_POST["submit"])) {
        if(isset($_POST["weight"]) && strlen(trim($_POST["weight"])) > 0) {
            $weight = $_POST["weight"];
        } 
        else {
            $errors .= "Please enter a weight.<br>";
        }

        if(isset($_POST["date"]) && strlen(trim($_POST["date"])) > 0) {
            $timestamp = strtotime($_POST['date']); 
            $day = date('d',$timestamp);
            $month = date('m',$timestamp);
            $year = date('Y',$timestamp);
        }
        else {
            $errors .= "Please select a date.<br>";
        }

        if(strlen($errors) > 0) {
            echo "<div class='alert alert-danger' role='alert'>";
            echo $errors;
            echo "</div>";
        }
        else {
            session_start();
            $select = $db->prepare('SELECT id FROM categories WHERE name = "Bodyweight"');
            $select->execute(); 
            $prepareRow = $select->fetch();

            $insert = $db->prepare('INSERT INTO entries (weight, category_id, user_id, day, month, year) VALUES (?, ?, ?, ?, ?, ?)');
            $insert->bindParam(1, $weight);
            $insert->bindParam(2, $prepareRow["id"]);
            $insert->bindParam(3, $_SESSION["id"]);
            $insert->bindParam(4, $day);
            $insert->bindParam(5, $month);
            $insert->bindParam(6, $year);
      
            $insert->execute();
            
            header("location: index.php");
            exit();
        }
    }

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>New Bodyweight</title>
<!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="gains.css">

<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</head>
<?php
    require 'header.php';
    ?>

<body>
    <br>
    <h1>New Bodyweight</h1>
    <br>
    <form method="post" class="container form-container bg-dark">
        <div class="form-group">
            <label for="weight">Bodyweight</label>
            <input type="number" name="weight" id="weight" class="form-control" min="45" max="1000"><br>
        </div>

        <div>
            <label for="date">Date</label>
            <input type="date" name="date" id="date" min="2000-01-01" class="form-control"><br>
        </div>
        
        <input type="submit" name="submit" id="submit" class="btn btn-danger">
    </form>

    <script src="date.js"></script>

    <?php
    require 'footer.php';
    ?>
</body>
</html>