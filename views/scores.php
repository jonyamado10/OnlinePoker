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
            <div id = "btn" class="btn"><a id = "btagame" class="active">Top 10</a></div>
            <div  class="btn"><a id= "btahands">My Rank</a></div>
        </div>
        
        
        
            <div id = "game" class="game-content">


            </div>


      
        

            <div id = "hands" class="hands-content">
                    
            </div>
        </div>  
    </div>


</body>
</html>