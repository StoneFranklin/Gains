<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signin.php");
        exit;
    }

    require_once "dbconnect.php";
    $select = $db->prepare('SELECT * FROM entries WHERE user_id = ? ORDER BY year DESC, month DESC, day DESC, weight DESC');
    $select->bindParam(1, $_SESSION["id"]);
    $select->execute();
    $results = $select->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Entry Log</title>
<!-- CSS only -->

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="gains.css">


<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="entries.js"></script>
</head>

<body>
    <?php
    require 'header.php';
    ?>

    <br>
    <h3>Individual Entries</h3>

    <div class="container form-container bg-dark">
        <div id="jsonAJAXContent"></div>
        <!--Wrong way, I know-->
        <input type="button" onclick="next()" value="<<" class="btn btn-danger">
        <input type="button" onclick="prev()" value=">>" class="btn btn-danger">
    </div>
    
    <h3>All Entries</h3>
    <br>
    
    <div class="container-fluid tabcontent" id="table">
        <div class="table table-striped table-dark">
            <div class="row">
                <div class="col-sm-4">
                    <h5>Category</h5>
                    <?php
                        foreach($results as $row) {
                            $liftSelect = $db->prepare('SELECT name FROM categories WHERE id = ?');
                            $liftSelect->bindParam(1, $row["category_id"]);
                            $liftSelect->execute();
                            $liftRow = $liftSelect->fetch();
                            $liftName = $liftRow["name"];
                            echo "<p>" . $liftName . "</p>";
                        }
                    ?>
                </div>
                <div class="col-sm-4">
                    <h5>Weight</h5>
                    <?php
                    foreach($results as $row) {
                        echo "<p>" . $row["weight"] . "</p>";
                    }
                    ?>
                </div>
                <div class="col-sm-4">
                    <h5>Date</h5>
                    <?php
                    foreach($results as $row) {
                        echo "<p>" . $row["month"] ."/". $row["day"] ."/". $row["year"] . "</p>";
                    }
                    ?>
                </div>
            </div> 
        </div> 
    </div>
        <?php
    require 'footer.php';
    ?>
</body>
</html>