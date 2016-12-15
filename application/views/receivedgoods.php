<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/receivedgoods" style="text-decoration: none;">
				Received Goods
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<div class="row" id="entryDetails">
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Received Goods entry</h4>
                    </div>
                    <div class="panel-body">
                    	<form class="form-horizontal" id="receivedGoodsForm" method="POST">
	                    	<div class="row">
	                    		<div class="col-md-12">
                    				<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Barcode Name&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" id="barcodeName" name="barcodeName" placeholder="Barcode Name" value="" required="">
			                            </div>
	                    			</div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
			                        <div class="form-group">
			                            <div class="col-sm-offset-4 col-sm-8">
			                                <button type="submit" class="btn btn-success">
												Receive Goods
											</button>
											<button type="reset" class="btn btn-info resetBtn">
												Reset
											</button>
			                            </div>
			                        </div>
			                    </div>
			                </div>
	                    </form>
                    </div>
                </div>
            </div>
		</div>
		<div class="row" id="listDetails">
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Received Goods Details</h4>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
						   <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
	                            <thead>
	                                <tr>
	                                    <th>Delivery No.</th>
	                                    <th>DC Date</th>
	                                    <th>Supplier Name</th>
	                                    <th>Customer Name</th>
	                                    <th>Receiver Name</th>
	                                    <th>Total Qty</th>
	                                    <th>Received Qty</th>
	                                    <th>Pending Qty</th>
	                                    <th>Total Amount</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php
	                                foreach($rcDtls as $row)
	                                {
									?>
									<tr>
										<td><?php echo $row->deliveryno; ?></td>
										<td><?php echo $row->dcdt; ?></td>
										<td><?php echo $row->suppliername; ?></td>
										<td><?php echo $row->customername; ?></td>
										<td><?php echo $row->receivername; ?></td>
										<td><?php echo $row->totalqty; ?></td>
										<td><?php echo $row->yesquantity; ?></td>
										<td><?php echo $row->noquantity; ?></td>
										<td><?php echo $row->totalamount; ?></td>
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
	
	$("#receivedGoodsForm").submit(function(e)
	{
		e.preventDefault();
		var barcodeName = $("#barcodeName").val();
		if(barcodeName != "")
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"barcodeName" : barcodeName
				};
				req.url = "admin/saveReceivedGoods";
				RequestHandler(req, showResponse);
			}
			else
			{
				alert('Your Session Has Been Expired. Please Login..');
				location.href = '<?php echo base_url(); ?>';
				return;
			}
		}
		else
		{
			var str = '';
			
			str += '<div role="alert" class="alert alert-danger">';
			str += 'OOPS! Please Fill All Details.';
			str += '</div>';

			$("#responseMsg").html(str);
		}
	});
	
	function showResponse(data)
	{
		data = JSON.parse(data);
		
		var isError = data.isError;
		var msg = data.msg;
		
		var str = '';
		var redirectURL = '';
		
		if(isError)
		{
			str += '<div role="alert" class="alert alert-danger">';
			str += 'OOPS! ' + msg;
			str += '</div>';
		}
		else
		{
			str += '<div role="alert" class="alert alert-success">';
			str += 'WELL DONE! ' + msg;
			str += '</div>';
			
			redirectURL = '<?php echo base_url(); ?>admin/receivedgoods';
		}
		$("#responseMsg").html(str);
		if(!isError)
		{
			setTimeout(function()
			{
				location.href = redirectURL;
			},1000);
		}
	}
	
	$(".resetBtn").click(function()
	{
		$("#responseMsg").html('');
		location.href = '<?php echo base_url(); ?>admin/receivedgoods/';
	});
	
</script>