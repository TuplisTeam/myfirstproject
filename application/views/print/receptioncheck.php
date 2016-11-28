<html>
	<head>
		<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class="container">
			
			<?php
			if(!$isprint)
			{	
			?>
			<div class="row">
				<div class="col-sm-12">
					<center>
						<a target="_blank" href="<?php echo base_url(); ?>admin/printReceptionCheck/<?php echo $receptionCheckId;?>/TRUE"><button class="btn btn-primary">Download As PDF</button></a>
					</center>
				</div>
			</div>
			<?php
			}
			?>
			
			<h3 align="center">Reception Check</h3>
			
			<div class="row">
			<table class="table">
				<tbody>
					<tr>
						<td style="width: 5%;border-top: none;">From</td>
						<td style="width: 5%;border-top: none;">&emsp;:&emsp;</td>
						<td style="width: 25%;border-top: none;"><?php echo $fromName; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">To</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $toName; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">DC No. / Invoice No.</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $dcName; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Lot No. / I. No.</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $lotNo; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Vehicle No.</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $vehicleNo; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Date</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $rcDate; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Unit Name</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $unitName; ?></td>
					</tr>
				</tbody>
			</table>
			</div>
			<div class="row">
			<table class="table table-bordered">
	    		<thead>
	    			<tr>
	    				<th>Sl.No.</th>
	    				<th>Description</th>
	    				<th>Yes/No</th>
	    			</tr>
	    		</thead>
	    		<tbody>
				<tr>
					<td>1</td>
					<td>Check the truck before loading or unloading the organic/Fair Trade Material. The Truck is checked for the contamination of materials, if any so that the integrity of the product is not lost.</td>
					<td>
						<?php echo ucwords($descCheck1); ?>
					</td>
				</tr>
				<tr>
					<td>2</td>
					<td>See also the proper closing and opening of the packaging materials so that the product is not exposed directly for the outside materials as contaminants to loose the product integrity.</td>
					<td>
						<?php echo ucwords($descCheck2); ?>
					</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Check for the required labels Organic/Fair Trade on the carton or on the covered product surface.</td>
					<td>
						<?php echo ucwords($descCheck3); ?>
					</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Check for the quantity of the materials received/transferred based on the D.C's., Invoice & Packing List.</td>
					<td>
						<?php echo ucwords($descCheck4); ?>
					</td>
				</tr>
	    		</tbody>
	    	</table>
	    	</div>
	    	<div class="row">
	    	<table class="table">
				<tbody>
					<tr>
						<td style="width: 5%;border-top: none;">Checked By</td>
						<td style="width: 5%;border-top: none;">&emsp;:&emsp;</td>
						<td style="width: 25%;border-top: none;"><?php echo $checkedBy; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Incharge</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $incharge; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Remarks</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $remarks ? $remarks : "-"; ?></td>
					</tr>
				</tbody>
			</table>
			</div>
		</div>
	</body>
</html>