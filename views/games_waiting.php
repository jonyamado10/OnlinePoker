<html>
	<head>
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">   
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	</head>
	<body>
  <script>
$(document).ready(function(){

    $('#tabela').dataTable();
    refreshTable();
});
   function refreshTable(){
   	
        $('#content').load("http://appserver-01.alunos.di.fc.ul.pt/~asw014/Admin/games_waiting #content", function(){
        	$('#tabela').dataTable();
           setTimeout(refreshTable, 10000);

        });
    }
		</script>
        
        <div id = "content">
        <?php


        array_unshift($games_waiting, array("ID","OWNER","NAME","DESCRIPTION","MAX_PLAYERS","FIRST_BET","ENTRY","CLOSED", "TIMER"));
        echo $this->table->generate($games_waiting);

        ?>
            <a class="d" href="<?php echo base_url('Admin/games_started') ?>">Games Started</a><br>
             <a class="d" href="<?php echo base_url('Admin/games_ended') ?>">Games Ended</a><br>
            <a class="d" href="<?php echo base_url('Admin/games_player_data') ?>">Players Data</a><br>
             <a class="d" href="<?php echo base_url('Admin/adminpage') ?>">Users Info</a><br>
            <a class="d" href="<?php echo base_url('Login/logout') ?>">Log out</a><br>
            </div>
    </body>
</html>