<!DOCTYPE html>
<html>
<head>
  <title>TexasHold'em</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,500,700|Oswald:300,400,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href=" <?php echo base_url("assets/css/style.css") ?>" />
</head>
<body>
  <div align="middle">
    <div class="login-box">
      <div>
        <div class="btnAdmin"><a class="active">Log In as Administrator</a></div>
      </div>

      <div>
      <div id= "login_form">

      <?php  
      
        echo form_open('login/login_admin_validation');
        echo validation_errors();

      ?>
       <div class="username-box">
      <?php  
        echo form_label('Username: ');
        echo form_input('username', '','placeholder="ADMIN" required');

      ?>
        </div>
        <div class="pass-box">
            <?php  
        echo form_label('Password: ', 'password');
        echo form_password('password','','placeholder="password" class = "password" required');
    ?>
        </div>
        <div>
          <div class="submit">
          <?php  
                     echo form_submit('login','login »', array('class' =>'submit-btn1'));
                     echo form_close(); 
                     
                         ?>
          </div>
        </div>


        </div>
      </div>
    </div>


</body>
</html>