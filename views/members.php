<!DOCTYPE html>
<html>
<head>
  <title>TexasHold'em</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
		<script src = "//code.jquery.com/jquery-1.12.4.js"></script>
		<script src = "https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
		

<!--<script>
    $(document).ready(function() {
       var timer = setTimeout( updateDiv, 100000);
        function updateDiv() {

                $.ajax({
                    type:"GET",
                    cache: false,
                    url: document.URL,
                    datatype: 'html',
                    success: function(data){
                        $("#top").html(data);

                    }
                }).done(function() {

                });;
     

            }   

        });


</script>-->

    <script>
      $(document).ready(function() {
          $('#tabela').DataTable( {
        "order": [[ 3, "desc" ]]
          } );
          
        $('thead').css('background-color','green');
        $('div#tabela_wrapper.dataTables_wrapper no-footer').css('margin-top','200px');

          $('#tabela2').DataTable( {
        "order": [[ 2, "desc" ]]
          } );
          
        $('thead').css('background-color','green');
        $('div#tabela_wrapper.dataTables_wrapper no-footer').css('margin-top','200px');
      } );
    </script>
     
<script>

$(document).ready(function(){
    $("#join").hide();
        $("#create").hide();
         $("#activegames").show();
});

$(document).ready(function(){
    $("a").click(function(){
        if($(this).attr("id") === "btacreate" ) {
            $("#join").hide();
            $("#create").show();
            $("#activegames").hide();
            $("#btaactivegames").removeAttr("class");
            $("#btajoin").removeAttr("class");
            $(this).attr("class","active");
            
        }
        else if($(this).attr("id") === "btajoin" ){
            $("#join").show();
            $("#create").hide();
            $("#activegames").hide();
            $("#btaactivegames").removeAttr("class");
            $("#btacreate").removeAttr("class");
            $(this).attr("class","active");
            
             
        }
      else if($(this).attr("id") === "btaactivegames" ){
            $("#activegames").show();
            $("#create").hide();
            $("#join").hide();
            $("#btajoin").removeAttr("class");
            $("#btacreate").removeAttr("class");
            $(this).attr("class","active");
            
        }
    });
});
</script>
</head>
<body>
<?php  $this->load->view('headerlogged');?>

<div align="middle" id='top'>
	<div class="lobby-box">
		<div>
			<div class="btn-lobbys"><a id= "btacreate" >Create Lobby</a></div>
			<div class="btn-lobbys"><a id= "btajoin">Join Lobby</a></div>
			<div class="btn-lobbys"><a id = "btaactivegames" class="active">Active Games</a></div>
		</div>
        <?php  
				echo form_open('Game/new_game'); 
				echo validation_errors();
				?>

	<div id = "create" class="container" id = 'games-available'>
		<div class="username-box">
 			<div>Table name</div>
            	<div style="margin-top: 5px;">  <?php echo form_input('name',$this->input->post('name'));?>  </div>
         	</div>
       
         <div class="pass-box">
      		<div>Number of Players</div>
           	 <div style="margin-top: 5px;">

                <select name="max_players">
                      <option value="2" <?php echo  set_select('max_players', '2', TRUE); ?> >2</option>
                      <option value="3" <?php echo  set_select('max_players', '3'); ?> >3</option>
                      <option value="4" <?php echo  set_select('max_players', '4'); ?> >4</option>
                      <option value="5" <?php echo  set_select('max_players', '5'); ?> >5</option>
                      <option value="6" <?php echo  set_select('max_players', '6'); ?> >6</option>
                      <option value="7" <?php echo  set_select('max_players', '7'); ?> >7</option>
                      <option value="8" <?php echo  set_select('max_players', '8'); ?> >8</option>
                      <option value="9" <?php echo  set_select('max_players', '9'); ?> >9</option>
                      <option value="10" <?php echo  set_select('max_players', '10'); ?> >10</option>

                </select>
            </div>
            </div>
             <div class="pass-box">
      		<div>Initial Bet</div>
           	 <div style="margin-top: 5px;">

                <select name="first_bet">
                      <option value="20" <?php echo  set_select('first_bet', '20', TRUE); ?> >20</option>
                      <option value="30" <?php echo  set_select('first_bet', '30'); ?> >30</option>
                      <option value="40" <?php echo  set_select('first_bet', '40'); ?> >40</option>
                      <option value="50" <?php echo  set_select('first_bet', '50'); ?> >50</option>
                      <option value="60" <?php echo  set_select('first_bet', '60'); ?> >60</option>
                      <option value="70" <?php echo  set_select('first_bet', '70'); ?> >70</option>
                      <option value="80" <?php echo  set_select('first_bet', '80'); ?> >80</option>
                      <option value="90" <?php echo  set_select('first_bet', '90'); ?> >90</option>
                      <option value="100" <?php echo  set_select('first_bet', '100'); ?> >100</option>

                </select>
            </div>
            </div>
          <div class="pass-box">
          <div>Minimum Entry</div>
             <div style="margin-top: 5px;">

                <select name="entry">
                      <option value="1000" <?php echo  set_select('entry', '1000', TRUE); ?> >1000</option>
                      <option value="2000" <?php echo  set_select('entry', '2000'); ?> >2000</option>
                      <option value="3000" <?php echo  set_select('entry', '3000'); ?> >3000</option>
                      <option value="4000" <?php echo  set_select('entry', '4000'); ?> >4000</option>
                      <option value="5000" <?php echo  set_select('entry', '5000'); ?> >5000</option>


                </select>
            </div>
            </div>
                <div class="pass-box">
          <div>Table speed</div>
             <div style="margin-top: 5px;">

                <select name="timeout">
                      <option value="-1" <?php echo  set_select('timeout', '-1', TRUE); ?> >normal</option>
                      <option value="20" <?php echo  set_select('timeout', '20'); ?> >fast</option>
                      <option value="15" <?php echo  set_select('timeout', '15'); ?> >hyper</option>
                 

                </select>
            </div>
            </div>
		<div class="pass-box">
			<div>Description</div>
			<div style="margin-top: 5px;"><?php echo form_textarea('description',$this->input->post('description'));?> </div>
		</div>
            <div class="submit">
                       
              <?php  
                        echo form_submit('create','Create', array('class' =>'submit-btn1'));
                           echo form_close(); 
                           
                          ?>
            
          </div>
        </div> 
        
        <div id = "join" class="container" >
        <?php
		
        $this->table->set_heading("ROOM","OWNER","INITIAL BET","TABLE SEATS", "                          ");

        echo $this->table->generate($game_request);


        ?>

        </div>
        <div id = "activegames">
        <?php
    
        $this->table->set_heading("ROOM","STARTED AT","CURRENT PLAYER","GAME STATUS");
       $this->table->set_template($template2);
        echo $this->table->generate($games_active);
            
           

        ?>

        </div>
    </div>
</div> 


</body>
</html>