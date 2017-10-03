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
<?php  $this->load->view('headerlogged');?>
  <div align="middle">
    <div class="profile-box">
        <div>
            <div id = "btn" class="btn"><a id = "btagame" class="active">Profile</a></div>
            <div  class="btn"><a id= "btahands">Edit profile</a></div>
        </div>
        
        
        
            <div id = "game" class="game-content">
		    <div class = "profile-header">
		        <h1><?php echo $username ?></h1>
		        <a><img src=" <?php echo base_url("assets/img/profile.png") ?> "></a>
		    </div>
		
		    <div class="profile-content">
		        <div class = "achivements">
		            <h2>Global Stats</h2>
		            <p>Hands Played:</p>
		            <p>Hands Won:</p>
		            <p>Hands Lost:</p>

		        </div>
		        <div class = "Aboutme">
		            <h2>About Me</h2>
		            <p>Country:</p>
		            <p>Type of Player:</p>
		            <p>Sun glasses: on/off</p>

		        </div>


            </div>


      
        

            <div id = "hands" class="hands-content">
                    <h1>Nao Implementado</h1>
            </div>
        </div>  
    </div>


</body>
</html>