<?php $REQUIRES_LOGIN = true; ?>
<?php include_once('inc.header.php'); ?>
		<div id='wrapper' style='margin-top: 120px;'>
			<section class='information-section'>
			<style type='text/css'>
				td.day, td.slot {
					color: black;
					background-color: #eee;
				}
				td.day {
					font-weight: bold;
				}
				thead th {
					font-size: 1.2em;
					background-color: rgb(52, 116, 219);
					color: white;
					text-align: left;
				}
				tr.class td.overflow {
					color: #B51414;
				}
				tr.class td {
					font-weight: bold;
					color: rgb(52, 116, 219);
				}
			</style>
<?php

  if (isset($_REQUEST['drop']) && isset($_REQUEST['class_id']) && is_numeric($_REQUEST['class_id'])) {
    Lib::$Lib->Students->DropClass(logged_in(), $_REQUEST['class_id']); 
  }
        
	$classes = Lib::$Lib->Classes->GetClassRegistrations(logged_in());
	$day = 0;
	$slot = 0;
?>
			<table class='tabular-information'>
				<thead>
					<tr>
						<th class='limited-display'>Day</th>
						<th class='limited-display'>Slot</th>
						<th>Class</th>
						<th>Instructors</th>
						<th>Limit</th>
						<th style='display: table-cell'>Drop</th>
					</tr>
				</thead>
				<tbody>
<?php foreach ($classes as $class_id => $class) : ?>
<?php	if ($class['Day'] != $day) : ?>
<?php 		$day = $class['Day']; $slot = 1; ?>
					<tr><td colspan='6' class='day'>Day <?= $day ?></td></tr>
					<tr><td colspan='6' class='slot'>Slot <?= $slot ?></td></tr>
<?php	endif; ?>
<?php	if ($class['Slot'] != $slot) : ?>
<?php 		$slot = $class['Slot']; ?>
					<tr><td colspan='6' class='slot'>Slot <?= $slot ?></td></tr>
<?php	endif; ?>
<?php $instructors = instructor_list($class); ?>
					<tr class='class'>
						<td class='limited-display'><?=$class['Day'] ?></td>
						<td class='limited-display'><?=$class['Slot'] ?></td>
						<td><a href='class-detail?class=<?=$class['ClassId'] ?>' style='font-weight: bold;'><?=$class['Name'] ?></a></td>
						<td><?=$instructors ?></td>
						<td <?=count($class['Students'])>$class['Limit']?"class='overflow'":"" ?>><?=$class['Limit'] ?> (<?=count($class['Students']) ?> enrolled)</td>
						<td style='display: table-cell'><form method='post'><input type='submit' value='Drop' name='drop' /><input type='hidden' name='class_id' value='<?=$class['ClassId'] ?>' /></td>
					</tr>
<?php endforeach ; ?>				
				</tbody>
			</table>

			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>