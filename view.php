<?php
session_start();

if ( isset($_SESSION['name'])) {
  $name = $_SESSION['name'];
} else {
  die('Not logged in');
}

require_once "pdo.php";

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
  session_destroy();
  header('Location: index.php');
  return;
}

// If any error recorded in session, show once, then reset.
if ( isset($_SESSION['success']) ) {
  echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
  unset($_SESSION['success']);
}


if (isset($_POST['add']) ) {
  header('Location: add.php');
  return;
}

if ( isset($_POST['delete']) && isset($_POST['auto_id']) ) {
    $sql = "DELETE FROM autos WHERE auto_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['auto_id']));
    header('Location: view.php');
}

$stmt = $pdo->query("SELECT make, year, mileage, auto_id FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
<title>Kelly Loyd Automobile Tracker (f418185d)</title>
</head><body>
<?php echo("<h1>Tracking Autos for $name</h1>\n"); ?>
<a href="add.php">Add New</a><a href="logout.php">Logout</a>
<form method="post">
<input type="submit" name="add" value="Add New">
<input type="submit" name="logout" value="Logout">
</form>
<table border="1">
<?php
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo($row['make']);
    echo("</td><td>");
    echo($row['year']);
    echo("</td><td>");
    echo($row['mileage']);
    echo("</td><td>");
    echo('<form method="post"><input type="hidden" ');
    echo('name="auto_id" value="'.$row['auto_id'].'">'."\n");
    echo('<input type="submit" value="Del" name="delete">');
    echo("\n</form>\n");
    echo("</td></tr>\n");
}
?>
</table>

</body>
