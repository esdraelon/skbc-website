<?php

  $message = null;
  if (isset($_REQUEST['urlkey']) && isset($_REQUEST['password'])) {
    if ($student_id = Lib::$Lib->Students->ResetPassword($_REQUEST['urlkey'], $_REQUEST['password'])) {
      $_SESSION['student_id'] = $student_id;
      header("Location: my-classes");
    } else {
      $message = "Your password could not be reset with this password reset key."; 
    }
  }

?>
<?php include_once('inc.header.php'); ?>
		<div id='wrapper' style='margin-top: 60px;'>
			<section class='information-section'>
			
      <div id='align-fix' style='margin: 30px auto; padding: 30px 0; width: 300px;'>
        <?=message($message) ?>
        <form method='post' class='skbc-form'>
          <input type='password' name='password' placeholder='New Password' />
          <input type='hidden' name='urlkey' value='<?=$_REQUEST['urlkey'] ?>' />
          <input type='submit' value='Set Password' />
        </form>
        <p>
      </div>

			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>