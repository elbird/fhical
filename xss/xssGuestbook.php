<?php
    // enter your database credentials here:
    $DB_HOST = "localhost";
    $DB_USER = "root";
    $DB_PW = "";
    $DB_NAME = "xss";
    $DB_PORT = 3306;

    $resultEntry = "";
    $resultName = "";
    $resultTimestamp = 0;
    $resultArray = array();

    $myDB = new mysqli($DB_HOST, $DB_USER, $DB_PW, $DB_NAME, $DB_PORT);
    if (mysqli_connect_errno()) {
        echo "Sorry, no connection to DB";
        exit();
    }
    if (!empty($_POST["name"]) && !empty($_POST["entry"])) {
        $name = $_POST["name"];
        $entry = $_POST["entry"];   

        $stmt = $myDB->prepare("INSERT INTO guestbook (name, entry) VALUES(?, ?);");
        $stmt->bind_param("ss", $name, $entry);
        if (!$stmt) {
            echo 'could not prepare statement';
            exit();
        }
        $stmt->execute();
        $stmt->close();
    }
    $stmt = $myDB->prepare("SELECT name, entry, date FROM guestbook ORDER BY date DESC;");

    if (!$stmt) {
        echo 'could not prepare statement';
        exit();
    }
    $stmt->execute();
    $stmt->bind_result($resultName, $resultEntry, $resultTimestamp);
    while ($stmt->fetch()) {
        array_push($resultArray, array('name' => $resultName, 'entry' => $resultEntry, 'timestamp' => $resultTimestamp));
    }
    $stmt->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Persistent XSS Showcase</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="/js/jquery.cookie.js"></script>
        <link rel="stylesheet" media="screen" type="text/css" href="myStyles.css" ></link>
    </head>
    <body>
        <h1>Persistent XSS Showcase</h1>

        <h2>New Guestbook entry</h2>
        <form method="POST" action="xssGuestbook.php">
            <label>Name 
            <input type="text" name="name" id="inputName" /></label><br />
            <label>Entry
            <textarea name="entry" id="textareaEntry"></textarea></label><br />
            <input type="submit" value="New Entry" />
        </form>

        <h2>Guestbook:</h2>
        <?php if (!empty($resultArray)): ?>
        <ul>
            <?php foreach ($resultArray as $result): ?>
            <li>
                <strong><?php echo $result["name"]; ?> </strong></br>
                <?php echo $result["entry"]; ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </body>
</html>
