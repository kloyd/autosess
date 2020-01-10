<?php
session_start();

if ( isset($_SESSION['name'])) {
  $name = $_SESSION['name'];
} else {
  die('Not logged in');
}


// If any error recorded in session, show once, then reset.
if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}

require_once "pdo.php";

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
  session_destroy();
  header('Location: index.php');
  return;
}

// Cancel entry.
if ( isset($_POST['cancel']) ) {
  header('Location: view.php');
  return;
}

if ( isset($_POST['addnew']) && isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
  $make = $_POST['make'];
  if (strlen($make) > 1) {
    if (is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
      $sql = "INSERT INTO autos (make, year, mileage)
                VALUES (:make, :year, :mileage)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':make' => htmlentities($_POST['make']),
          ':year' => $_POST['year'],
          ':mileage' => $_POST['mileage']));
          // now redirect to view.php
          $make = htmlentities($_POST['make']);
          $_SESSION['success'] = "New Auto added";
          header('Location: view.php');
          return;
      } else {
        $_SESSION['error'] = "Mileage and year must be numeric.";
        header("Location: add.php");
        return;
      }
    } else {
      $_SESSION['error'] = "Make is required.";
      header("Location: add.php");
      return;
    }
}

?>
<html>
<head>
<title>Kelly Loyd Automobile Tracker (f418185d)</title>
</head><body>
  <?php echo("<h1>Tracking Autos for $name</h1>\n"); ?>
<p>Add A New Auto</p>
<form method="post">
  <p>Make:
  <input type="text" name="make" size="40"></p>
  <p>Year:
  <input type="text" name="year"></p>
  <p>Mileage:
  <input type="text" name="mileage"></p>
  <p>
    <input type="submit" value="Add New" name="addnew" />
    <input type="submit" value="Cancel" name="cancel" />
  </p>
</form>
<a href="logout.php">Logout</a>
<form method="post">
<input type="submit" name="logout" value="Logout">
</form>
</body>
