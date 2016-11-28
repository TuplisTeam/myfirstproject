<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html class="printableArea">
<head>
	<title><?php echo $this->config->item('projectTitle'); ?></title>
	<meta name="" content="<?php echo $this->config->item('projectTitle'); ?>">
	<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-2.1.4.min.js"></script>
	
	<style>
		table.itemtable{border-collapse: collapse;width: 100%;margin: 15px 5px;}
		table.itemtable, .itemtable td, .itemtable th{padding: 10px;}
		table.itemtable thead{border-bottom: 1px solid #d3d3d3;}
		.mytable{border: 1px solid #DADADA;}
		.twodiv,.mytopheadtable .col-md-6:nth-of-type(odd){border-right: 1px solid #DADADA;}
		.head,.borderbottom,.mytopheadtable .col-md-6{border-bottom: 1px solid #DADADA;}
		.footerrow{border-top: 1px solid #DADADA;border-radius: 32px;}
		.descri{min-height: 200px;}
		.minheight{min-height:100px;}
		.mytable{border-radius: 25px;}
		.row{margin-left: 0;margin-right: 0;}
		.mytopheadtable .col-md-6{min-height: 30px;}
		.mytopheadtable{border-radius: 15px;border: 1px solid #DADADA;}
		.mytopheadtable .col-md-6:last-child, .mytopheadtable .col-md-6:nth-last-child(2){border-bottom: none;}
		.headerdiv {margin-bottom: 10px;}
		.mainDiv,.margintop {margin-top: 10px;}
		.textbottom {vertical-align: text-bottom;margin-right: 5px;}
		.footertextbottom{display: flex;align-items: flex-end;}
	</style>

</head>
<body>
	<?php
	if(!$isprint)
	{	
	?>
	<div class="row">
		<div class="col-sm-12">
			<center>
				<a target="_blank" href="<?php echo base_url(); ?>admin/printDeliveryNote/<?php echo $deliveryNoteId;?>/TRUE"><button class="btn btn-primary">Download As PDF</button></a>
			</center>
		</div>
	</div>
	<?php
	}
	?>
	
	<div class="container mainDiv">
		<div class="row headerdiv">
			<div class="col-md-9">
				<img class="textbottom" src="http://placehold.it/100x100">CEX Reg. No. AAFFA1226PXM001 Range - tirupur I Division - Tirupur.
			</div>
			<div class="col-md-3">
				<div class="row mytopheadtable">
					<div class="col-md-6">No.</div>
					<div class="col-md-6">Date</div>
					<div class="col-md-6"><?php echo $deliveryNo; ?></div>
					<div class="col-md-6"><?php echo $dcDate; ?></div>
					<div class="col-md-6">Page :</div>
					<div class="col-md-6"></div>
				</div>
			</div>
		</div>
		<div class="row mytable">
			<div class="col-md-12 head text-center">
				<h1>DELIVERY NOTE</h1>
			</div>
			<div class="row borderbottom">
				<div class="col-md-6 minheight twodiv">
					<p style="font-weight: bold;">SUPPLIER DETAILS : </p>
					<p>
						<span style="font-weight: bold;">Name : </span>
						<?php echo $supplierName; ?>
					</p>
					<p>
						<span style="font-weight: bold;">Address : </span>
						<?php echo $supplierAddress; ?>
					</p>
					<p>
						<span style="font-weight: bold;">Customer Name : </span>
						<?php echo $customerName; ?>
					</p>
				</div>
				<div class="col-md-6 minheight">
					<p style="font-weight: bold;">RECEIVER DETAILS : </p>
					<p>
						<span style="font-weight: bold;">Name : </span>
						<?php echo $receiverName; ?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 descri">
					<table class="itemtable">
						<thead>
							<tr>
								<th>Barcode</th>
								<th>Item Name</th>
								<th>Division</th>
								<th>Rate</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(count($itemDtls) > 0)
							{
								foreach($itemDtls as $row)
								{
								?>
								<tr>
									<td><?php echo $row->barcode; ?></td>
									<td><?php echo $row->itemname; ?></td>
									<td><?php echo $row->division; ?></td>
									<td><?php echo $row->rate; ?></td>
									<td><?php echo $row->amount; ?></td>
								</tr>
								<?php
								}
								?>
								<tr style="font-weight: bold; border-top: 1px solid #d3d3d3;">
									<td colspan="3"></td>
									<td>Total Amount : </td>
									<td><?php echo $totalAmount; ?></td>
								</tr>
								<?php
							}
							else
							{
							?>
								<tr>
									<td>No Items Found.</td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="row footerrow">
				<div class="col-md-4 twodiv footertextbottom minheight">
					<p style="font-weight: bold;">Receiver's Signature</p>
				</div>
				<div class="col-md-4 twodiv minheight">
					<p style="font-weight: bold;">REMARKS : </p>
					<p>
						<?php echo $remarks; ?>
					</p>
				</div>
				<div class="col-md-4 minheight">
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 margintop">
				<p>THE DC NO. IS TO BE MENTIONED IN ALL DELIVERIES AND BILLS COMPULSORILY</p>
			</div>
		</div>
	</div>
	
<script>
	
</script>

</body>
</html>-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $this->config->item('projectTitle'); ?></title>
<meta name="" content="<?php echo $this->config->item('projectTitle'); ?>">
<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-2.1.4.min.js"></script>
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
					<a target="_blank" href="<?php echo base_url(); ?>admin/printDeliveryNote/<?php echo $deliveryNoteId;?>/TRUE"><button class="btn btn-primary">Download As PDF</button></a>
				</center>
			</div>
		</div>
		<?php
		}
		?>
		<table style="width: 100%;margin-bottom: 5px;">
			<tr>
				<td>
					<img src="<?php echo base_url(); ?>images/180x130.png" style="vertical-align: text-bottom;margin-right: 5px;">
					CEX Reg. No. AAFFA1226PXM001 Range - tirupur I Division - Tirupur.
				</td>
				<td>
					<table width="100%" class="table table-bordered" style="border-collapse:separate;border-radius: 15px 15px 15px 15px;margin-bottom: 0px;">
						<tr style="border-radius: 15px 15px 0px 0px;height:30px;">
							<td style="border-radius: 15px 0 0;">No.</td>
							<td style="border-radius: 0 15px 0 0;">Date</td>
						</tr>
						<tr style="height:30px;">
							<td><?php echo $deliveryNo; ?></td>
							<td><?php echo $dcDate; ?></td>
						</tr>
						<tr style="height:30px;">
							<td style="border-radius: 0 0 0 15px;">Page : </td>
							<td style="border-radius: 0 0 15px 0;"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!--<div class="row" style="margin: 10px 0;">
			<div class="col-md-3">
				<img src="http://placehold.it/100x100">
			</div>
			<div class="col-md-6">
				CEX Reg. No. AAFFA1226PXM001 Range - tirupur I Division - Tirupur.
			</div>
			<div class="col-md-3">
				
			</div>
		</div>-->
		<div>
			<table width="100%" style="border-collapse:separate;border-radius: 25px 25px 25px 25px;margin-bottom: 5px;" class="table table-bordered">
				<tr style="height:60px;">
					<td colspan="6" style="border-radius: 25px 25px 0px 0px;">
						<h1 align="center">DELIVERY NOTE</h1>
					</td>
				</tr>
				<tr style="height:100px;">
					<td colspan="3">
						<p style="font-weight: bold;">SUPPLIER DETAILS : </p>
						<p>
							<span style="font-weight: bold;">Name : </span>
							<?php echo $supplierName; ?>
						</p>
						<p>
							<span style="font-weight: bold;">Address : </span>
							<?php echo $supplierAddress; ?>
						</p>
						<p>
							<span style="font-weight: bold;">Customer Name : </span>
							<?php echo $customerName; ?>
						</p>
					</td>
					<td colspan="3">
						<p style="font-weight: bold;">RECEIVER DETAILS : </p>
						<p>
							<span style="font-weight: bold;">Name : </span>
							<?php echo $receiverName; ?>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="6" height="200">
						<table class="table">
							<thead>
								<tr>
									<th>Barcode</th>
									<th>Item Name</th>
									<th>Division</th>
									<th>Rate</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(count($itemDtls) > 0)
								{
									foreach($itemDtls as $row)
									{
									?>
									<tr>
										<td><?php echo $row->barcode; ?></td>
										<td><?php echo $row->itemname; ?></td>
										<td><?php echo $row->division; ?></td>
										<td><?php echo $row->rate; ?></td>
										<td><?php echo $row->amount; ?></td>
									</tr>
									<?php
									}
									?>
									<tr style="font-weight: bold; border-top: 1px solid #d3d3d3;">
										<td colspan="3"></td>
										<td>Total Amount : </td>
										<td><?php echo $totalAmount; ?></td>
									</tr>
									<?php
								}
								else
								{
								?>
									<tr>
										<td>No Items Found.</td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</td>
				</tr>
				<tr style="height:60px;">
					<td width="33%" style="border-collapse: separate;border-radius: 0 0 0 25px;height: 100px;vertical-align: bottom;" colspan="2">
						<p style="font-weight: bold;">Receiver's Signature</p>
					</td>
					<td width="33%" colspan="2">
						<p style="font-weight: bold;">REMARKS : </p>
						<p>
							<?php echo $remarks; ?>
						</p>
					</td>
					<td width="33%" style="border-collapse: separate;border-radius:  0 0 25px 0 ;" colspan="2">
						
					</td>
				</tr>
			</table>
			<p>THE DC NO. IS TO BE MENTIONED IN ALL DELIVERIES AND BILLS COMPULSORILY</p>
		</div>
	</div>
</body>
</html>