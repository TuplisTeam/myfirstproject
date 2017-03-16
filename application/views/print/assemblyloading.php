<html>
	<head>
		<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class="container">
			<h3 align="center">Assembly Loading</h3>
			
			<div class="row">
				<table class="table">
					<tbody>
						<tr>
							<td>Entry Date</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $entryDate; ?></td>
							<td>Line Name</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $lineName; ?></td>
						</tr>
						<tr>
							<td>Shift Name</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $shiftName; ?></td>
							<td>Line Incharge</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $lineIncharge; ?></td>
						</tr>
						<tr>
							<td>Hour 1</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $hour1; ?></td>
							<td>Hour 2</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $hour2; ?></td>
						</tr>
						<tr>
							<td>Hour 3</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $hour3; ?></td>
							<td>Hour 4</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $hour4; ?></td>
						</tr>
						<tr>
							<td>Hour 5</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $hour5; ?></td>
							<td>Hour 6</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $hour6; ?></td>
						</tr>
						<tr>
							<td>Hour 7</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $hour7; ?></td>
							<td>Hour 8</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $hour8; ?></td>
						</tr>
						<tr>
							<td>OT Hour</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $otHour; ?></td>
							<td>Total Pieces</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $totalPieces; ?></td>
						</tr>
						<tr>
							<td>Target</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $target; ?></td>
							<td>Is Target Achieved</td>
							<td>&emsp;:&emsp;</td>
							<td><?php echo $isTargetAchieved; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>