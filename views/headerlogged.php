<!DOCTYPE html>
<html>
<head>
  <title>TexasHold'em</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,500,700|Oswald:300,400,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href=" <?php echo base_url('assets/css/style.css') ?>" />

</head>
<body>
  <div class="navigation">
    <ul>
       <li><a href="<?php echo base_url('Main/members') ?>">Play</a></li>
      <li><a href="<?php echo base_url('Main/gamelogged') ?>">Game</a></li>
	     <li><img id = "logo" src=" <?php echo base_url("assets/img/poker.png") ?> "></li>
      	<li><a href="<?php echo base_url('Main/scoreslogged') ?>">Score</a></li>
	  <li><a href="<?php echo base_url("Main/news") ?>">News</a></li>
	  <li class="dropdown">
		
		<a href="javascript:void(0)" style = "color:red; font-weight: bold; font-size:15px;text-transform: capitalize;"class="dropbtn" onclick="myFunction()"> Welcome <?php echo $username?>! </a>
    		<script src=" <?php echo base_url('assets/js/javascript.js') ?>"></script>
		<div class="dropdown-content" id="myDropdown">
          <a class="dropdown-content-options" href="<?php echo base_url("Main/profile") ?>">Profile </a>
		  <a class="dropdown-content-options" href="<?php echo base_url("Main/wallet") ?>">My Wallet: <?php echo $wallet?></a>
		  <a class="dropdown-content-options" href="<?php echo base_url('Login/logout') ?>">Log out</a>
		</div>
	  </li>
    </ul>

  </div>