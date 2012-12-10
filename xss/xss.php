<?php
          //header("X-XSS-Protection: 1");
          $value = '';
          if(!empty($_GET['GET_PARAMETER'])){
              $value = $_GET['GET_PARAMETER'];
              if (get_magic_quotes_gpc()) { 
                $value = stripslashes($value);
              }
          }
        ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Reflective XSS</title>
    </head>
    <body>
        <h1>Raw Output</h1>
        <p><?php echo urldecode($value) ?></p>
        
        <h1>Sanitized Output</h1>
        <p><?php echo htmlentities($value) ?></p>
    </body>
</html>
