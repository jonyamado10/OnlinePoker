<div class= "game-box" id = 'game-poker'>
 <?php $game_time = strtotime(date("Y/m/d H:i:s"))-strtotime($game_status[0]['started_at']);
 	?>
 	<h1 id = "room" style = "color:white; text-align: left;"> <?php echo "<i>".$roomname."</i>: ".gmdate("H:i:s",$game_time);?></h1>
 	
 	<div id = "table-cards">
 	   	<?php
	if($game_status[0]['current_player'] == $username){?>
 		<div id= "timer" style="color: white; ">
 			 <audio id="audio2" src=" <?php echo base_url("assets/05_danger.wav") ?>" autostart="false" ></audio>
	 		<audio id="audio" src="http://www.soundjay.com/button/beep-07.wav" autostart="false" ></audio>
	 		 
	 		<p>It's your Turn<br> <?php if($timeout > 0 ){?><span id="countdown" style="font-size: 15pt; color: red;"><?php echo $timer?></span> second remaining<?php }?><br>Pot: <?php echo $game_status[0]['current_pot']?>$</p>

 		</div>
 		<?php }else{?>
 
 		<p style = "color:white;">Pot: <?php echo $game_status[0]['current_pot']?>$</p>
 		<?php }?>
 		<?php if(strlen($game_status[0]['table_cards']) == 0){
				?>
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">

			<?php
			}
			?>
			<?php 
			if(strlen($game_status[0]['table_cards']) == 8){?>

					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],0,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],3,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],6,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">

			<?php 	
			}
		
			else if(strlen($game_status[0]['table_cards']) == 11){?>
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],0,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],3,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],6,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],9,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">


			<?php 	
			}
			else if (strlen($game_status[0]['table_cards']) == 14){?>
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],0,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],3,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],6,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],9,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],12).'.png' ?> ">

			<?php }

			?>
			<p style = "color:white;"> To call: <?php echo $game_status[0]['current_bet']- $mybet?>$</p>
 	</div>
 	


 	<?php 
			$cardback = "<img id = 'logo' src =".base_url("assets/img/cards/card_back.jpg")." '>";
			$button = "<img id = 'logo' src =".base_url("assets/img/button.png")." '>";
			$avatar = "<img id = 'avatar' src=".base_url("assets/img/profile.png").">";
			$act = "<p id = 'action' style = 'color:white;'> ";
			$contador = 0;
			$players_order = array();
			
			foreach($players_in_game as $player){foreach ($player as $playername ) {
					array_push($players_order,$playername['username']);}
			}
					
			$off = array_search($username, $players_order);
   			$result = array_merge(array_slice($players_order, $off, null, true), array_slice($players_order, 0, $off, true));
    
			
			
			foreach ($result as $player_name) {

					if($username != $player_name){
						if($player_on_button[0]['username'] == $player_name){
							if(in_array($player_name, $players_folded)){
								
								echo "<div id ='seat-cards-".$contador."'>".$act.$actions[$player_name]."</p>".$button;
								echo "</div>";
							}
							else{
								echo "<div id ='seat-cards-".$contador."'>".$act.$actions[$player_name]."</p>".$cardback.$cardback.$button;
								echo "</div>";
							}
							if($game_status[0]['current_player'] == $player_name){
								echo "<div style = 'background-color: green;'id ='seat-".$contador."'>";
								echo $avatar;
								echo "<h2>".$player_name."</h2>";
								echo "</div>";
								$contador += 1;
							}
							else{
								echo "<div id ='seat-".$contador."'>";
								echo $avatar;
								echo "<h2>".$player_name."</h2>";
								echo "</div>";
								$contador += 1;

							}
						}
					
						else{
							if(in_array($player_name, $players_folded)){
								echo "<div id ='seat-cards-".$contador."'>".$act.$actions[$player_name]."<br></p>";
								echo "</div>";
							}
							else{
								echo "<div id ='seat-cards-".$contador."'>".$act.$actions[$player_name]."<br></p>".$cardback.$cardback;
								echo "</div>";
							}
							if($game_status[0]['current_player'] == $player_name){
								
								echo "<div style = 'background-color: green;' id ='seat-".$contador."'>";
								echo $avatar;
								echo "<h2>".$player_name."</h2>";
								echo "</div>";
								$contador += 1;
							}
							else{
								
								echo "<div id ='seat-".$contador."'>";
								echo $avatar;
								echo "<h2>".$player_name."</h2>";
								echo "</div>";
								$contador += 1;
							}
						}
						
				
					}else{
							?>
 	
 	<div id = "my-cards">
 	<?php 

 	if(!in_array($username, $players_folded)){ 
 		if($player_on_button[0]['username'] == $username){ echo $button;?>

		<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($hand[0]['player_hand'],0,2).'.png' ?> ">
		<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($hand[0]['player_hand'],3).'.png' ?> ">
<?php }else{ ?>
 		<img id = 'logo' style="visibility: hidden;" src ="<?php echo base_url("assets/img/button.png")?>">
 		<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($hand[0]['player_hand'],0,2).'.png' ?> ">
		<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($hand[0]['player_hand'],3).'.png' ?> ">
	<?php } }else{?>
	<img id = "card" style = "visibility : hidden;" src =" <?php echo base_url("assets/img/cards/").substr($hand[0]['player_hand'],0,2).'.png' ?> ">
		<img id = "card" style = "visibility : hidden;" src =" <?php echo base_url("assets/img/cards/").substr($hand[0]['player_hand'],3).'.png' ?> "> <?php } ?>
		<div id = "mystack">
		<img id = "chip-stack" src= " <?php echo base_url("assets/img/chip_stack.png") ?> ">
		<h3> <?php echo $mystack ?> $</h3></div>
 	</div>
 	<?php 
	if($game_status[0]['current_player'] == $username){
		echo "<div style = 'background-color:green;' id = 'my-seat'><img src=".base_url("assets/img/profile.png")."><h2>".$username."</h2>"."</div>";
	}
	else{
		echo "<div id = 'my-seat'><img src=".base_url("assets/img/profile.png")."><h2>".$username."</h2></div>";
	}
 	}?>

			<?php			
				# code...
			} ?>
 		

 
	<!-- <table class= "game-table">
		
		<tr>
			<th>Started at:</th>
			<td><?php echo $game_status[0]['started_at']?></td>
			
		</tr>
		<tr>
			<th>Current Player:</th>
			<td><?php echo $game_status[0]['current_player']?></td>
			
		</tr>

		<tr>
			<th>Cards on Table:</th>
			<td><?php if(strlen($game_status[0]['table_cards']) == 0){
				?>
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">

			<?php
			}
			?>
			<?php 
			if(strlen($game_status[0]['table_cards']) == 8){?>

					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],0,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],3,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],6,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">

			<?php 	
			}
		
			else if(strlen($game_status[0]['table_cards']) == 11){?>
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],0,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],3,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],6,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],9,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/card_back.jpg")?> ">


			<?php 	
			}
			else if (strlen($game_status[0]['table_cards']) == 14){?>
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],0,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],3,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],6,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],9,2).'.png' ?> ">
					<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($game_status[0]['table_cards'],12).'.png' ?> ">

			<?php }

			?></td>
			
		</tr>
		<tr>
			<th>My Cards:</th>
			<td><img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($hand[0]['player_hand'],0,2).'.png' ?> ">
			<img id = "card" src =" <?php echo base_url("assets/img/cards/").substr($hand[0]['player_hand'],3).'.png' ?> ">
			</td>
			
		</tr>
				<tr>
		<th>My Stack:</th>
			<td><?php echo $mystack?>$</td>
			
		</tr>
		<th>My bet:</th>
			<td><?php echo $mybet?>$</td>
			
		</tr>
		<tr>
			<th>Current bet:</th>
			<td><?php echo $game_status[0]['current_bet']?>$</td>
			
		</tr>
		<tr>
			<th>Pot:</th>
			<td><?php echo $game_status[0]['current_pot']?>$</td>
		</tr>
		<tr>
		<th>Players seats:</th>
			<td><?php 
			$cardback = "<img id = 'logo' src =".base_url("assets/img/cards/card_back.jpg")." '>";
			$button = "<img id = 'logo' src =".base_url("assets/img/button.png")." '>";
			foreach($players_in_game as $player){foreach ($player as $playername ) {
				
					if($player_on_button[0]['username'] == $playername['username']){
						
						echo $playername['username']." ".$button.$cardback.$cardback."  ";
					}
					
					else{
						
						echo $playername['username']." ".$cardback.$cardback."  ";
					}
				# code...
			}}  ?></td>
		</tr>

	</table> -->
     </div>