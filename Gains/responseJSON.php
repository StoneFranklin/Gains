<?
session_start();
require_once "dbconnect.php";

$query = $db->prepare("SELECT * FROM entries WHERE user_id = ? ORDER BY year ASC, month ASC, day ASC");
$query->bindParam(1, $_SESSION["id"]);
$query->execute();

$results = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($results as $rowNum => $row) {
    $liftSelect = $db->prepare('SELECT name FROM categories WHERE id = ?');
    $liftSelect->bindParam(1, $row["category_id"]);
    $liftSelect->execute();
    $liftRow = $liftSelect->fetch();
    $liftName = $liftRow["name"];
	$arr[$rowNum] = [$liftName, $row['weight'], $row["month"] . '/' . $row["day"] . '/' . $row["year"]];
}
echo json_encode($arr);
?>