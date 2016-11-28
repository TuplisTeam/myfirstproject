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
						<a target="_blank" href="<?php echo base_url(); ?>admin/printBarcode/<?php echo $barcodeId;?>/TRUE"><button class="btn btn-primary">Download As PDF</button></a>
					</center>
				</div>
			</div>
			<?php
			}
			?>
			
			<h3 align="center">Barcode</h3>
			
			<div class="row">
			<table class="table">
				<tbody>
					<tr>
						<td style="width: 5%;border-top: none;">Barcode Name</td>
						<td style="width: 5%;border-top: none;">&emsp;:&emsp;</td>
						<td style="width: 25%;border-top: none;"><?php echo $barcodeName; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Receipt Date</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $receiptDate; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Order No.</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $orderNo; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Process</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $process; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Style</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $style; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Item</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $item; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Rate</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $rate; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Buyer Name</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $buyerName; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Color</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $color; ?></td>
					</tr>
					<tr>
						<td style="border-top: none;">Size</td>
						<td style="border-top: none;">&emsp;:&emsp;</td>
						<td style="border-top: none;"><?php echo $size; ?></td>
					</tr>
				</tbody>
			</table>
			</div>
		</div>
	</body>
</html>