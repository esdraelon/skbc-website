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

	$classes = Lib::$Lib->Classes->GetClassRegistrations();
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
					</tr>
<?php		foreach ($class['Students'] as $student_id => $student) : ?>
					<tr>
						<td colspan='2' class='limited-display'></td>
						<td colspan='4'><?=array_pop($student) ?></td>
					</tr>
<?php		endforeach ; ?>
<?php endforeach ; ?>				
				</tbody>
			</table>

			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>