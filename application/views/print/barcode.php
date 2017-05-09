<html>
	<head>
		<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	</head>

	<body>
		<div class="container">
			<h3 align="center">Barcode</h3>
			
			<div class="row">
				<table width="300px" border="">
					<tr>
						<td align="center" colspan="3" style="padding: 3px;">
							<img src="<?php echo base_url().'images/barcodes/barcode_'.$barcodeId.'.png'; ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 3px;">O.No. : <?php echo $orderNo; ?></td>
					</tr>
					<tr>
						<td style="padding: 3px;">Date : <?php echo $receiptDate; ?></td>
						<td style="padding: 3px;">Process : <?php echo $process; ?></td>
					</tr>
					<tr>
						<td style="padding: 3px;">Style : <?php echo $style; ?></td>
						<td style="padding: 3px;">Item : <?php echo $item; ?></td>
					</tr>
					<tr>
						<td style="padding: 3px;">Rate : <?php echo $rate; ?></td>
						<td style="padding: 3px;">Buyer : <?php echo $buyerName; ?></td>
					</tr>
					<tr>
						<td style="padding: 3px;">Color : <?php echo $color; ?></td>
						<td style="padding: 3px;">Size : <?php echo $size; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>