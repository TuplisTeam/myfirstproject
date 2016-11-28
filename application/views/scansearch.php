<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/<?php echo $page; ?>" style="text-decoration: none;">
				<?php echo ucwords($page); ?>
			</a>
		</h3>
    </div>
    
	<div id="main-wrapper">
		<div class="row" id="listDetails">
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Delivery Challan Details</h4>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
						   <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
	                            <thead>
	                                <tr>
	                                    <th>Delivery No.</th>
	                                    <th>DC Date</th>
	                                    <th>Barcode</th>
	                                    <th>Item Name</th>
	                                    <th>Division</th>
	                                    <th>Rate</th>
	                                    <th>Quantity</th>
	                                    <th>Amount</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php
	                                foreach($dcDtls as $row)
	                                {
									?>
									<tr>
										<td><?php echo $row->deliveryno; ?></td>
										<td><?php echo $row->dcdt; ?></td>
										<td><?php echo $row->barcode; ?></td>
										<td><?php echo $row->itemname; ?></td>
										<td><?php echo $row->division; ?></td>
										<td><?php echo $row->rate; ?></td>
										<td><?php echo $row->quantity; ?></td>
										<td><?php echo $row->amount; ?></td>
									</tr>
									<?php
									}
	                                ?>
	                            </tbody>
							</table> 
                        </div>
                    </div>
                </div>
			</div>
		</div>
    </div>
</div>

<script>
	
	$(document).ready(function()
	{
		$('#example').dataTable();
	});
	
</script>