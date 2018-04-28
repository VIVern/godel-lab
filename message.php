<?php
  require_once 'index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>lab1</title>

  <link rel="stylesheet" href="css/message.css">
</head>
<body>
  <?php
    if (empty($error) === false) {
      foreach ($error as $err) {
  ?>
      <p class='error'><?php echo $err ?></p>
  <?php
      }
  ?>
      <button class='goBack'><a href='./index.php'>Go back</a></button>
  <?php
      return;
    }
    if (isset($text) === false) {
      return;
    }
    else {
  ?>
      <div class="messageCard">
        <?php echo $text; ?>
        <button class="goBack"><a href="./index.php">Go back</a></button>
      </div>
  <?php
    }
  ?>
</body>
</html>
