<?php
function connect(){
  try {
      $conn = new PDO("mysql:host=localhost;dbname=zootopia", 'root', '');
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      }
  catch(PDOException $e) //error mode
    {
    $error_message = $e->getMessage();
?>

    <h1>Database Connection Error</h1>
    <p>There was an error connecting to the database.</p>
    <!-- display the error message -->
    <p>Error message:
      <?php
        echo $error_message;
      ?>
    </p>

<?php
  die();
  }

return $conn;
}
?>

<?php
function test_user_input($data) {
  return trim(filter_var($data, FILTER_SANITIZE_STRING));
}
?>
