<?php include_once('inc.header.php'); ?>
<?php

  if (isset($_REQUEST['join']) && isset($_REQUEST['class_id']) && is_numeric($_REQUEST['class_id'])) {
    if (Lib::$Lib->Students->JoinClass(logged_in(), $_REQUEST['class_id'])) {
      $message = "You have joined this class. <a href='my-classes'>View your classes here.</a>"; 
    } else {
      $message = "You already have a class during this slot. <a href='my-classes'>View your classes here.</a>"; 
    }
  }

$class = Lib::$Lib->Classes->GetClass($_GET['class']);

?>
		<div id='wrapper' style='margin-top: 120px;'>
			<section class='information-section'>
			
				<h2><?=$class['Name'] ?></h2>
        <?=message($message); ?>
				<h3>
					<a href='instructor-bio?instructor=<?=$class['Instructor1Id'] ?>'><?=$class['Instructor1'] ?></a>
					<?=trimlen($class['Instructor2'])>0?", <a href='instructor-bio?instructor=$class[Instructor2Id]'>$class[Instructor2]</a>":"" ?></h3>
				<h3>Limit <?=$class['Limit'] ?></h3>
				<?=$class['Description'] ?>
        <p>
        <form method='post'><input type='submit' value='Join Class' name='join' /><input type='hidden' name='class_id' value='<?=$class['ClassId']; ?>' /></form>
			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>
