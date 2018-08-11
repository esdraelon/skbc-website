<?php include_once('inc.header.php'); ?>
		<div id='wrapper' style='margin-top: 120px;'>
			<section class='information-section tabular-data'>
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
				td:last-child.overflow {
					color: #B51414;
				}
				td:last-child {
					color: #144CB5;
				}
			</style>
<?php

	$classes = Lib::$Lib->Classes->GetClassList();
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
						<th>Enrolled</th>
					</tr>
				</thead>
				<tbody>
<?php foreach ($classes as $k => $class) : ?>
<?php	if ($class['Day'] != $day) : ?>
<?php 		$day = $class['Day']; $slot = 1; ?>
					<tr><td colspan='5' class='day'>Day <?= $day ?></td></tr>
					<tr><td colspan='5' class='slot'>Slot <?= $slot ?></td></tr>
<?php	endif; ?>
<?php	if ($class['Slot'] != $slot) : ?>
<?php 		$slot = $class['Slot']; ?>
					<tr><td colspan='5' class='slot'>Slot <?= $slot ?></td></tr>
<?php	endif; ?>
<?php $instructors = instructor_list($class); ?>
					<tr>
						<td class='limited-display'><?=$class['Day'] ?></td>
						<td class='limited-display'><?=$class['Slot'] ?></td>
						<td><a href='class-detail?class=<?=$class['ClassId'] ?>'><?=$class['Name'] ?></a></td>
						<td><?=$instructors ?></td>
						<td <?=$class['Enrolled']>$class['Limit']?"class='overflow'":"" ?>><?=$class['Enrolled'] ?> enrolled of <?=$class['Limit'] ?></td>
					</tr>
<?php endforeach ; ?>				
				</tbody>
			</table>

			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>