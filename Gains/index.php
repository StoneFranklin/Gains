<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signin.php");
        exit;
    }

    if(isset($_POST["submit"])) {
        $_SESSION["categoryID"] = $_POST["categoryID"];
        $_SESSION["categoryName"] = $_POST["categoryName"];
        header("location: chart.php");
        exit;
    }

    require_once "dbconnect.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Gains</title>
<!-- CSS only -->

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="gains.css">


<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>


<body>
    <?php
    require 'header.php';
    ?>
    
    <div class="container">
        <br>
        <br>
        <div class="bg-danger text-light text-center">
        <h2>
            Bodyweight
        </h2>
    </div>
        <?php
            session_start();
            
            $select = $db->prepare('SELECT * FROM categories');
            $select->execute();
            $results = $select->fetchAll();

            foreach($results as $row) {
                $select1 = $db->prepare('SELECT weight FROM entries WHERE user_id = ? AND category_id = ? ORDER BY year ASC, month ASC, day ASC LIMIT 1');
                $select1->bindParam(1, $_SESSION["id"]);
                $select1->bindParam(2, $row["id"]);

                $select2 = $db->prepare('SELECT weight FROM entries WHERE user_id = ? AND category_id = ? ORDER BY year DESC, month DESC, day DESC, weight DESC LIMIT 1');
                $select2->bindParam(1, $_SESSION["id"]);
                $select2->bindParam(2, $row["id"]);

                $select1->execute();
                $select2->execute();

                $firstWeight = $select1->fetch();
                $currentWeight = $select2->fetch();

                $differece = $currentWeight["weight"] - $firstWeight["weight"];

                echo '<div class="bg-light text-dark card text-center">';
                echo '<div class="card-body">';
                echo "<h5 class='card-title'>" . $row["name"] . "</h5>";
                echo '<p class="card-text" id="green">Current: ' . $currentWeight["weight"] . "</p>";
                if($differece >= 0) {
                    echo '<p class="card-text text-success"> Progress: +' . $differece . "</p>";
                }
                else {
                    echo '<p class="card-text text-danger"> Progress: ' . $differece . "</p>";
                }
                
                echo '
                <form method="post">
                        <input type="hidden" name="categoryID" value="' . $row["id"] . '"/>
                        <input type="hidden" name="categoryName" value="' . $row["name"] . '"/>
                        <input type="submit" name="submit" value="View Details" class="btn btn-primary bg-dark"/>
                </form>
                ';
                echo "</div>";
                echo "</div> <br>";
                if($row["name"] == "Bodyweight") {
                    echo '
                        <br> 
                        <div class="bg-danger text-light text-center">
                            <h2>
                                Lifts
                            </h2>
                        </div>
                    ';
                }
                
                
            }
        ?>
    </div>
    <?php
    require 'footer.php';
    ?>
</body>
</html>