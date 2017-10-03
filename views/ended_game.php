<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  <title>TexasHold'em</title>
  <?php  $this->load->view('headerlogged');?>
  <link rel="stylesheet" type="text/css" href=" <?php echo base_url("assets/css/style.css") ?>" />
</head>

<body>

  <div align="middle">
      <h1 style = "color:white; margin-top:100px;">This game has ended!</h1>
 <div class= "game-ended">
     
	<table class= "game-table">
		
		<tr>
			<th>Started at:</th>
			<td><?php echo $game_status[0]['started_at']?></td>
			
		</tr>
		<tr>
			<th>Ended at: </th>
			<td><?php echo $game_status[0]['ended_at']?></td>
			
		</tr>
		<tr>
			<th>Winner:</th>
			<td><?php echo $winner?></td>
			
		</tr>
	</table>


</div>
</div>

</body>
</html>