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

	$instructors = Lib::$Lib->Instructors->GetInstructors();
?>
			<table class='tabular-information' style='min-width: 280px;'>
				<thead>
					<tr>
						<th>Instructor</th>
					</tr>
				</thead>
				<tbody>
<?php foreach ($instructors as $k => $instructor) : ?>
					<tr>
						<td><a href='instructor-bio?instructor=<?=$instructor['InstructorId']; ?>'><?=$instructor['Name'] ?></a></td>
					</tr>
<?php endforeach ; ?>				
				</tbody>
			</table>

			</section>
			<div id='push'></div>
		</div>
<?php include_once('inc.footer.php'); ?>