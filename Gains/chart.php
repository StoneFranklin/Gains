<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: signin.php");
        exit;
    }

    require_once "dbconnect.php";

    if(isset($_POST["submit"])) {
        $_SESSION["categoryName"] = $_POST["lift"];
        $select = $db->prepare('SELECT id FROM categories WHERE name = ?');
        $select->bindParam(1, $_POST["lift"]);
        $select->execute();
        $catID = $select->fetch();
        $_SESSION["categoryID"] = $catID["id"];
    }    
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Trends</title>
<!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<link rel="stylesheet" href="gains.css">

<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>

<body>
    <?php
    require 'header.php';
    ?>
    <br>
    <div class="container-fluid">
    <form method="post">
        <div class="form-group">
            <label for="lift">Showing Trends for</label>
            <select name="lift" id="lift">
                <?php
                    $selectOptions = $db->prepare('SELECT * from categories');
                    $selectOptions->execute();
                    foreach ($selectOptions as $row) {
                        if($row["name"] == $_SESSION["categoryName"]) {
                            echo '<option value="' . $row["name"] . '" selected >' . $row["name"] . '</option>';
                        }
                        else {
                            echo '<option value="' . $row["name"] . '" >' . $row["name"] . '</option>';
                        }
                    }
                ?>
            </select>
            <input type="submit" name="submit" id="submit" value="Update" class="btn btn-danger">
        </div>
    </form>

    <!-- I didn't put this in another file because I would have had to just make it another php file since I'm pulling the data from the db. -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Date', 'Weight'],
            <?
            session_start();
            $select = $db->prepare('SELECT * FROM entries WHERE user_id = ? AND category_id = ? ORDER BY year, month, day');
            $select->bindParam(1, $_SESSION["id"]);
            $select->bindParam(2, $_SESSION["categoryID"]);
            $select->execute();
            foreach ($select as $row) {
                echo '[new Date(' . $row['year'] . ',' . ($row['month'] - 1) . ',' . $row['day'] . '),' .  $row['weight'] . '],';
            }
            ?>
        ]);

        var options = {
            title: "Trends",
            hAxis: {title: 'Date',  titleTextStyle: {color: 'white'}, textStyle: {color: 'white'}, minorGridlines: {color: '#222426'}},
            vAxis: {minValue: 0, textStyle: {color: 'white'}, minorGridlines: {color: '#222426'}},
            colors:['red','#004411'],
            titleTextStyle: {color: 'white'},
            pointSize: 3,
            legend: {textStyle: {color: 'white'}},
            backgroundColor: '#222426'
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
        }

        $(window).resize(function(){
        drawChart();
        });
    </script>

    <div id="chart_div"></div>
    </div>
    <?php
    require 'footer.php';
    ?>
</body>
</html>