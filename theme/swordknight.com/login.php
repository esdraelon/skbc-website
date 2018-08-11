<?php

  if (logged_in()) {
    header("Location: my-classes"); 
  }
  
  $message = null;
  if (isset($_REQUEST['login']) && isset($_REQUEST['password'])) {
    $uri = trimpre("id=login&", $_SERVER['QUERY_STRING']);
    $uri = trimpre("id=login", $uri);
    if ($student_id = Lib::$Lib->Students->Login($_REQUEST['login'], $_REQUEST['password'])) {
      $_SESSION['student_id'] = $student_id;
      header("Location: $uri");
    } else {
      $message = "Your email or password did not work."; 
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
          <input type='password' name='password' placeholder='Password' />
          <input type='submit' value='Login' />
        </form>
        <p>
        <a href='forgot-password'>Forget your password? Click here to reset it.</a>
      </div>

			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>
