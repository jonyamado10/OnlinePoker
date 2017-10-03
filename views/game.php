<!DOCTYPE html>
<html>
<head>
  <title>TexasHold'em</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#hands").hide();
        $("#game").show();
});
    
$(document).ready(function(){
    $("a").click(function(){
        if($(this).attr("id") === "btagame" ) {
            $("#hands").hide();
            $("#game").show();
            $("#btahands").removeAttr("class");
            $(this).attr("class","active");
            
        }
        else if($(this).attr("id") === "btahands" ){
            $("#hands").show();
            $("#game").hide();
            $("#btagame").removeAttr("class");
            $(this).attr("class","active");
            
        }
    });
});
</script>
</head>
<body>
<?php  $this->load->view('header');?>
  <div align="middle">
    <div class="profile-box">
        <div>
            <div id = "btn" class="btn"><a id = "btagame" class="active">The Game</a></div>
            <div  class="btn"><a id= "btahands">Hands</a></div>
        </div>
        
        
        
            <div id = "game" class="game-content">
                    <br>
                    <p>Texas hold 'em (also known as Texas holdem, hold 'em, and holdem) is a variation of the card game of poker. Two cards, known as the hole cards or hold cards, are dealt face down to each player, and then five community cards are dealt face up in three stages. The stages consist of a series of three cards ("the flop"), later an additional single card ("the turn" or "fourth street") and a final card ("the river" or "fifth street"). Each player seeks the best five card poker hand from the combination of the community cards and their own hole cards. If a player's best five card poker hand consists only of the five community cards and none of the player's hole cards, it is called "playing the board". Players have betting options to check, call, raise or fold. Rounds of betting take place before the flop is dealt, and after each subsequent deal.</p>
                    <br>
                    <img src = <?php echo base_url("assets/img/gamecontent.jpg") ?> width="300px">

            </div>


      
        

            <div id = "hands" class="hands-content">
                    
                    <img src = <?php echo base_url("assets/img/hands2.jpg") ?>>
            </div>
        </div>  
    </div>


</body>
</html>