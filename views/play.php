<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
  <title>TexasHold'em</title>
        <link rel="stylesheet" type="text/css" href=" <?php echo base_url("assets/css/style.css") ?>" />
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript">function updateTextInput(val) {
          document.getElementById('textInput').innerHTML = val;
        }
        </script>


    <script type="text/javascript">


        $(document).ready(function() {
        	<?php
        	if($game_status[0]['current_player'] == $username and $timeout != -1){?>
                var timeLeft = <?php echo $timer?>;
                var foldButton = document.getElementsByName("foldButton");


                if(timeLeft == 0){

                    document.forms["foldButton"].submit();
                }
                 if(timeLeft <= 10){
                       var sound = document.getElementById("audio");
                   sound.play();
                 /*   if(timeLeft > 5){

                    var sound = document.getElementById("audio");
                   sound.play();
                    }
                   else{

                       var sound2 = document.getElementById("audio2");
                       sound2.play();
                   }*/

                }
 			<?php }?>


        	<?php  if($game_state != "endgame"){?>
                var timer = setTimeout( updateDiv, 1000);

            <?php }else{?>
                var timer = setTimeout( updateDiv, 5000);
            <?php }?>
            var counter = 0;  //only here so we can see that the div is being updated if the same error is thrown

            function updateDiv() {

                $("#top").load('<?php echo base_url("Main/play_update/").$gameid?>');

            }   

        });
    </script>
</head>


<body>
<?php  $this->load->view('headerlogged');?>

<div allign = 'middle' id = "top">

<div class= "game-box" id = 'game-poker'>
 <?php $game_time = strtotime(date("Y/m/d H:i:s"))-strtotime($game_status[0]['started_at']);
 	?>
 	<h1 id = "room" style = "color:white; text-align: left;"> <?php echo "<i>".$roomname."</i>: ".gmdate("H:i:s",$game_time);?></h1>
 	
 	<div id = "table-cards">
 	   	<?php
	if($game_status[0]['current_player'] == $username){?>
 		<div id= "timer" style="color: white; ">
 		
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
			<p style = "color:white;"> To call: <?php if ($game_status[0]['current_bet']- $mybet >0){
                                                        echo $game_status[0]['current_bet']- $mybet;}
                                                        else{
                                                            echo 0;
                                                        }?>$</p>
        
 	</div>
 	


 	<?php 
			$cardback = "<img id = 'cardback' src =".base_url("assets/img/cards/card_back.jpg")." '>";
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
								
								echo "<div id ='seat-cards-".$contador."'>".$button.$act.$actions[$player_name]."</p>";
								echo "</div>";
							}
							else{
								echo "<div id ='seat-cards-".$contador."'>".$cardback.$cardback.$button.$act.$actions[$player_name]."</p>";
								echo "</div>";
							}
							if($game_status[0]['current_player'] == $player_name){
								echo "<div style = 'background-color: green;'id ='seat-".$contador."'>";
								echo $avatar;
								echo "<h2>".$player_name."</h2>";
                                echo "<h5>".$all_players_stacks[$player_name]."$</h5>";
								echo "</div>";
								$contador += 1;
							}
							else{
								echo "<div id ='seat-".$contador."'>";
								echo $avatar;
								echo "<h2>".$player_name."</h2>";
                                echo "<h5>".$all_players_stacks[$player_name]."$</h5>";
								echo "</div>";
								$contador += 1;

							}
						}
					
						else{
							if(in_array($player_name, $players_folded)){
								echo "<div id ='seat-cards-".$contador."'>".$act.$actions[$player_name]."</p>";
								echo "</div>";
							}
							else{
								echo "<div id ='seat-cards-".$contador."'>".$cardback.$cardback.$act.$actions[$player_name]."</p>";
								echo "</div>";
							}
							if($game_status[0]['current_player'] == $player_name){
								
								echo "<div style = 'background-color: green;' id ='seat-".$contador."'>";
								echo $avatar;
								echo "<h2>".$player_name."</h2>";
                                echo "<h5>".$all_players_stacks[$player_name]."$</h5>";
								echo "</div>";
								$contador += 1;
							}
							else{
								
								echo "<div id ='seat-".$contador."'>";
								echo $avatar;
								echo "<h2>".$player_name."</h2>";
                                echo "<h5>".$all_players_stacks[$player_name]."$</h5>";
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
  
</div>
 
     <div id = pokerbuttons >
		   <?php  
		   $gameid = $game_status[0]['id'];
			echo form_open('Game/call/'.$gameid); 
			echo validation_errors();
		?>
	  <?php  
           echo form_submit('call','Call', array('class' =>'pthe_button'));
           echo form_close(); 
                           
         ?>
         	   <?php
         	   

			echo form_open('Game/bet/'.$gameid); 
			echo validation_errors();
		?>
			<div id = bet_button>
		  <input autocomplete="off" type="range" value = "<?php echo $mystack/2 ?>" name="player_bet" min="0" max="<?php echo $mystack?>" onchange="updateTextInput(this.value);">
			<p style ="color:white; " id="textInput"><?php echo $mystack/2 ?>$<p> 
<!--   			<input type="submit">
		      <select name="player_bet">
                      <option value="20" <?php echo  set_select('player_bet', '20', TRUE); ?> >20</option>
                      <option value="40" <?php echo  set_select('player_bet', '40'); ?> >40</option>
                      <option value="60" <?php echo  set_select('player_bet', '60'); ?> >60</option>
                      <option value="80" <?php echo  set_select('player_bet', '80'); ?> >80</option>
                      <option value="100" <?php echo  set_select('player_bet', '100'); ?> >100</option>
                      <option value="120" <?php echo  set_select('player_bet', '120'); ?> >120</option>
                      <option value="140" <?php echo  set_select('player_bet', '140'); ?> >140</option>
                      <option value="160" <?php echo  set_select('player_bet', '160'); ?> >160</option>
                      <option value="180" <?php echo  set_select('player_bet', '180'); ?> >180</option>
                      <option value="200" <?php echo  set_select('player_bet', '200'); ?> >200</option>
                      <option value="220" <?php echo  set_select('player_bet', '220'); ?> >220</option>
                      <option value="240" <?php echo  set_select('player_bet', '240'); ?> >240</option>
                      <option value="260" <?php echo  set_select('player_bet', '260'); ?> >260</option>
                      <option value="280" <?php echo  set_select('player_bet', '280'); ?> >280</option>
                      <option value="300" <?php echo  set_select('player_bet', '300'); ?> >300</option>
                </select> -->

	  <?php  
           echo form_submit('bet','Bet', array('class' =>'pthe_button'));
           echo form_close(); 
                           
         ?> </div>
	   <?php  
			echo form_open('Game/check/'.$gameid); 
			echo validation_errors();
		?>
	  <?php  
           echo form_submit('check','Check', array('class' =>'pthe_button'));
           echo form_close(); 
                           
         ?>
         	   <?php  
			echo form_open('Game/fold/'.$gameid, array('name' => 'foldButton' )); 
			echo validation_errors()
		?>

	  <?php  
           echo form_submit('fold','Fold', array('class' =>'pthe_button'));
           echo form_close(); 
                           
         ?>
        <?php  
			echo form_open('Game/all_in/'.$gameid); 
			echo validation_errors();
		?>
	  <?php  
           echo form_submit('allin','ALL-IN', array('class' =>'pthe_button'));
           echo form_close(); 
                           
         ?>
      </div>


</body>
</html>