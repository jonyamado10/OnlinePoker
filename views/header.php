<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  <title>TexasHold'em</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,500,700|Oswald:300,400,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href=" <?php echo base_url("assets/css/style.css") ?>" />
</head>
<body>
<header>
  <div class="navigation">
    <ul>
      <li><a href="<?php echo base_url() ?>">Home</a></li>
      <li><a href="<?php echo base_url('Main/game') ?>">Game</a></li>
	     <li><img id = "logo" src=" <?php echo base_url("assets/img/poker.png") ?> "></li>
      <li><a href="<?php echo base_url('Main/scores') ?>">Score</a></li>
	  <li><a href="<?php echo base_url("login") ?>">Login</a></li>
    </ul>

  </div>
  </header>
  </body>
</html>