<?php include_once('inc.header.php'); ?>
<?php

$bio = Lib::$Lib->Instructors->GetInstructor($_GET['instructor']);

?>
		<div id='wrapper' style='margin-top: 120px;'>
			<section class='information-section' style='margin: 60px auto; width: 70vw;'>
			
				<h2><?=$bio['Name'] ?></h2>
<?php if (trimlen($bio['ImageUrl']) > 0) : ?>
				<img src='<?=$bio['ImageUrl'] ?>' style='margin: 0 1vw 1vh 0; float: left; border: 1px solid #222; max-width: 20vw; max-height: 40vh;' />
<?php endif ; ?>
				<?=$bio['Bio'] ?>
				
				<h3 style='clear: left;'>Classes</h3>
				<ul>
<?php foreach ($bio['Classes'] as $c => $class) : ?>
					<li><a href='class-detail?class=<?=$class['ClassId'] ?>'><?=$class['Name'] ?></a></li>
<?php endforeach ; ?>
				</ul>
			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>