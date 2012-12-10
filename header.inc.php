<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="favicon.ico" rel="shortcut icon">
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
  <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
  <script src="https://jquery-ui.googlecode.com/svn/tags/1.8.20/ui/i18n/jquery.ui.datepicker-de.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <title><?php echo !empty($title) ? $title . " - " : "" ?><?php echo !empty($config["pageTitle"]) ? $config["pageTitle"] : "" ?></title>  
  <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>

<body>
  <div class="container">

    <header class="header clearfix">
      <div class="logo"><?php echo !empty($config["pageTitle"]) ? $config["pageTitle"] : "" ?></div>
      <nav class="menu_main">
        <ul>
          <?php foreach ($config["menuItems"] as $key => $item): ?>
            <li<?php echo (isset($currentPage) && $currentPage == $key) ? ' class="active"' : "" ?>>
              <a href="<?php echo $item["url"] ?>"><?php echo $item["name"] ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
    </header>
    <div class="info">

