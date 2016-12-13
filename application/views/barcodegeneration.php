<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/barcodegeneration" style="text-decoration: none;">
				Barcode Generation
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($barcodeId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Barcode Generation Details</h4>
						<button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
						   <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
	                            <thead>
	                                <tr>
	                                    <th>Receipt Date</th>
	                                    <th>Order No.</th>
	                                    <th>Process</th>
	                                    <th>Style</th>
	                                    <th>Item</th>
	                                    <th>Rate</th>
	                                    <th>Buyer Name</th>
	                                    <th>Color</th>
	                                    <th>Size</th>
	                                    <th>Manage</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php
	                                foreach($barcodeDtls as $row)
	                                {
									?>
									<tr>
										<td><?php echo $row->receiptdt; ?></td>
										<td><?php echo $row->orderno; ?></td>
										<td><?php echo $row->processname; ?></td>
										<td><?php echo $row->style; ?></td>
										<td><?php echo $row->itemname; ?></td>
										<td><?php echo $row->rate; ?></td>
										<td><?php echo $row->buyername; ?></td>
										<td><?php echo $row->color; ?></td>
										<td><?php echo $row->size; ?></td>
										<td>
											<button class="btn btn-sm btn-success editEntry" barcodeId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-pencil"></span>
											</button>
											<button class="btn btn-sm btn-danger delEntry" entryId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-close"></span>
											</button>
											<button class="btn btn-sm btn-warning printEntry" barcodeId="<?php echo $row->id; ?>" title="Print">
												<span class="fa fa-print"></span>
											</button>
										</td>
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
        <div class="row" id="entryDetails" <?php echo $displayblock; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
	                <div class="panel-heading clearfix">
	                    <h4 class="panel-title">Barcode Generation Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="barcodeGenerationForm" method="POST">
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Barcode Name&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="barcodeName" name="barcodeName" placeholder="Barcode Name" value="<?php echo $barcodeName; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Receipt Date&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control datePicker" id="receiptDate" name="receiptDate" placeholder="Receipt Date" value="<?php echo $receiptDate; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Order No.&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="orderNo" name="orderNo" placeholder="Order No." value="<?php echo $orderNo; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Process&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
												<select class="form-control" style="width: 100%;" id="process" name="process" data-placeholder="Process" required="">
				                                	<option value="">Select Process</option>
				                                	<option value="Cutting">Cutting</option>
				                                	<option value="Packing">Packing</option>
												</select>
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Style&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="style" name="style" placeholder="Style" value="<?php echo $style; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Item&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="item" name="item" placeholder="Item" value="<?php echo $item; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Rate&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control numeric" id="rate" name="rate" placeholder="Rate" value="<?php echo $rate; ?>" required="">
				                            </div>
					                    </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Buyer Name&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="buyerName" name="buyerName" placeholder="Buyer Name" value="<?php echo $buyerName; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Color&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="<?php echo $color; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Size&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="size" name="size" placeholder="Size" value="<?php echo $size; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
	                        <div class="row">
	                    		<div class="col-md-12">
			                        <div class="form-group">
			                            <div class="col-sm-offset-4 col-sm-8">
			                                <button type="submit" class="btn btn-success">
												<?php
												if($barcodeId > 0)
												{
													echo 'Update Barcode';
												}
												else
												{
													echo 'Create Barcode';
												}
												?>
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
    </div>
</div>

<script>
	
	$(document).ready(function()
	{
		$('#example').dataTable();
		$('select').select2();
		$('.numeric').numeric();
		$('.datePicker').datepicker(
		{
			format: 'dd/mm/yyyy', 
			autoclose: true
		});
		
		var barcodeId = '<?php echo $barcodeId; ?>';
		if(barcodeId > 0)
		{
			$("#process").select2('val', '<?php echo $process; ?>');
		}
	});
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$("#barcodeName").blur(function()
	{
		var barcodeId = '<?php echo $barcodeId; ?>';
		var barcodeName = $(this).val();
		
		if(barcodeName != "")
		{
			var req = new Request();
			req.data = 
			{
				"barcodeId" : barcodeId, 
				"barcodeName" : barcodeName
			};
			req.url = "admin/checkBarcodeAvailability";
			RequestHandler(req, showBarcodeAvailability);
		}
	});
	
	function showBarcodeAvailability(data)
	{
		data = JSON.parse(data);
		var isError = data.isError;
		var msg = data.msg;
		if(isError == true)
		{
			alert(msg);
			$("#barcodeName").val('');
		}
	}
	
	$("#barcodeGenerationForm").submit(function(e)
	{
		e.preventDefault();
		
		var barcodeId = '<?php echo $barcodeId; ?>';
		var barcodeName = $("#barcodeName").val();
		var receiptDate = $("#receiptDate").val();
		var orderNo = $("#orderNo").val();
		var process = $("#process").val();
		var style = $("#style").val();
		var itemName = $("#item").val();
		var rate = $("#rate").val();
		var buyerName = $("#buyerName").val();
		var color = $("#color").val();
		var size = $("#size").val();
		
		if(barcodeName != "" && receiptDate != "" && orderNo != "" && process != "" && style != "" && itemName != "" && rate != "" && buyerName != "" && color != "" && size != "")
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"barcodeId" : barcodeId,
					"barcodeName" : barcodeName,
					"receiptDate" : receiptDate,
					"orderNo" : orderNo,
					"process" : process,
					"style" : style,
					"item" : itemName,
					"rate" : rate, 
					"buyerName" : buyerName, 
					"color" : color, 
					"size" : size
				};
				req.url = "admin/saveBarcodeGeneration";
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
	
	$(".editEntry").click(function()
	{
		var barcodeId = $(this).attr('barcodeId');
		if(barcodeId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/barcodegeneration/'+barcodeId;
		}
	});
	
	$(".delEntry").on("click",function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			var bool = confirm("Are You Sure Want To Remove This Entry?");
			if(bool)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"entryId" : entryId, 
					"tableName" : "barcode", 
					"columnName" : "id"
				};
				req.url = "admin/delEntry";
				RequestHandler(req, showResponse);
			}
		}
		else
		{
			return;
		}
	});
	
	$(".printEntry").click(function()
	{
		var barcodeId = $(this).attr('barcodeId');
		if(barcodeId > 0)
		{
			window.open('<?php echo base_url(); ?>admin/printBarcode/'+barcodeId);
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
			
			redirectURL = '<?php echo base_url(); ?>admin/barcodegeneration';
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
		location.href = '<?php echo base_url(); ?>admin/barcodegeneration/';
	});
	
</script>