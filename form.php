<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Lab 1</title>

  <link rel="stylesheet" href="css/index.css">
</head>
<body>
  <div class="startForm">
    <form action="message.php" method="POST" >
      <?php
        foreach($fields as $field){
          if($field != "EXECDATE" && $field != "ENDDATE"){
        ?>
          <div class="startFormUnit">
            <label for="<?php echo $field ?>"><?php echo $field ?></label>
            <input id="<?php echo $field ?>" name="<?php echo $field ?>" type="text">
          </div>
        <?php
          }
        }
        ?>
      <div class="startFormButton">
        <input type="submit">
      </div>
    </form>
  </div>
</body>
</html>
