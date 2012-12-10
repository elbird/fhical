<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/config.php';
$config = Config::get();

$currentPage = "impressum";
$title = "Impressum";
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/inc/header.inc.php');
?>

<article class="article clearfix">
  <div class="col_50">
    <h1>Impressum</h1>
    <p></p>
  </div>
</article>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/inc/footer.inc.php');