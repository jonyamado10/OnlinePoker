<!DOCTYPE html>
<html>
<head>
  <title>TexasHold'em</title>
</head>
<body>
<?php  $this->load->view('header');?>

  <div align="middle">
    <div class="login-box">
      <div>
        <div class="btn"><a class="active">Log In</a></div>
        <div class="btn"><a href="<?php echo base_url('Main/signup')?>">Sign Up</a></div>
      </div>


      <div id= "login_form">

      <?php  
        echo form_open('login/login_validation');
        echo validation_errors();

      ?>
       <div class="username-box">
      <?php  
        echo form_label('Username: ', 'username');
        echo form_input('username');

      ?>
        </div>
        <div class="pass-box">
            <?php  
        echo form_label('Password: ', 'password');
        echo form_password('password','','placeholder="password" class = "password"');
    ?>
        </div>
        <div>
          <div class="submit">
          <?php  
                     echo form_submit('login','Login »', array('class' =>'submit-btn1'));
                     echo form_close(); 
                     
                         ?>
          </div>
        </div>
        <div class="create_acc-box">
          <div>Forgot Password?<br><a href=# style="color: #42428a">Click here</a></div>
            <br><br><br><br>
        </div>
      </div>
    </div>
    </div>


</body>
</html>