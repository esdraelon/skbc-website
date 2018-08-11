<?php

  $message = null;
  if (isset($_REQUEST['login'])) {
    if ($student_id = Lib::$Lib->Students->RecoverPassword($_REQUEST['login'])) {
      $message = "A reset link has been sent to this email."; 
    } else {
      $message = "Your email could not be found."; 
    }
  }

?>
<?php include_once('inc.header.php'); ?>
		<div id='wrapper' style='margin-top: 60px;'>
			<section class='information-section'>
			
      <div id='align-fix' style='margin: 30px auto; padding: 30px 0; width: 300px;'>
        <?=message($message) ?>
        <form method='post' class='skbc-form'>
          <input type='text' name='login' placeholder='Email' />
          <input type='submit' value='Reset Password' />
        </form>
        <p>
      </div>

			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>
