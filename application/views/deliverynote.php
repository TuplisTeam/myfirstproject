<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/deliverynote" style="text-decoration: none;">
				Delivery Note
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($deliveryNoteId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Delivery Note Details</h4>
						<button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
						   <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
	                            <thead>
	                                <tr>
	                                    <th>Delivery No.</th>
	                                    <th>DC Date</th>
	                                    <th>Supplier Name</th>
	                                    <th>Supplier Address</th>
	                                    <th>Customer Name</th>
	                                    <th>Receiver Name</th>
	                                    <th>Manage</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php
	                                foreach($deliveryNoteHdrDtls as $row)
	                                {
									?>
									<tr>
										<td><?php echo $row->deliveryno; ?></td>
										<td><?php echo $row->dcdate; ?></td>
										<td><?php echo $row->suppliername; ?></td>
										<td><?php echo $row->supplieraddress; ?></td>
										<td><?php echo $row->customername; ?></td>
										<td><?php echo $row->receivername; ?></td>
										<td>
											<?php
											if($row->all_items_received == "yes")
											{
											?>
											<button class="btn btn-sm btn-success" title="All Items Received">
												<span class="fa fa-check"></span>
											</button>
											<?php
											}
											else
											{
											?>
											<button class="btn btn-sm btn-success editEntry" deliveryNoteId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-pencil"></span>
											</button>
											<button class="btn btn-sm btn-danger delEntry" entryId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-close"></span>
											</button>
											<?php
											}
											?>
											<button class="btn btn-sm btn-warning printEntry" deliveryNoteId="<?php echo $row->id; ?>" title="Print">
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
	                    <h4 class="panel-title">Delivery Note Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="deliveryNoteForm" method="POST">
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Delivery No.&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="deliveryNo" name="deliveryNo" placeholder="Delivery No." value="<?php echo $deliveryNo; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												DC Date&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control datePicker" id="dcDate" name="dcDate" placeholder="DC Date" value="<?php echo $dcDate; ?>" required="">
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
												Supplier Name&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="supplierName" name="supplierName" placeholder="Supplier Name" value="<?php echo $supplierName; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Supplier Address&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <textarea class="form-control" id="supplierAddress" name="supplierAddress" required=""><?php echo $supplierAddress; ?></textarea>
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
												Customer Name&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="customerName" name="customerName" placeholder="Customer Name" value="<?php echo $customerName; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Receiver Name&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="receiverName" name="receiverName" placeholder="Receiver Name" value="<?php echo $receiverName; ?>" required="">
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
												Delivery From&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="deliveryFrom" name="deliveryFrom" placeholder="Delivery From" value="<?php echo $deliveryFrom; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Delivered By&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="deliveredBy" name="deliveredBy" placeholder="Delivered By" value="<?php echo $deliveredBy; ?>" required="">
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="form-group">
		                        	<div class="col-md-12">
		                        		<div class="table-responsive">
		                        			<div style="float: right;">
				                        		<button type="button" class="btn btn-success addRow">
				                        		Add New Row <i class="fa fa-plus"></i>
				                        		</button>
			                        		</div>
			                        		<table class="table table-bordered">
				                        		<thead>
				                        			<tr>
				                        				<th width="15%">Barcode</th>
				                        				<th width="15%">Item</th>
				                        				<th width="15%">Division</th>
				                        				<th width="15%">Rate</th>
				                        				<th width="15%">Quantity</th>
				                        				<th width="15%">Amount</th>
				                        				<th width="10%">Manage</th>
				                        			</tr>
				                        		</thead>
				                        		<tbody class="itemDetailsTBody">
			                        			<?php
			                        			if($deliveryNoteId > 0)
			                        			{
													if(count($itemDtls) > 0)
													{
														$cnt = 0;
														foreach($itemDtls as $row)
														{
															$cnt++;
														?>
														<tr class="itemDetailsTR" rowNo="<?php echo $cnt; ?>">
															<td>
																<input type="text" class="form-control onBlurBarcode barcode_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Barcode" value="<?php echo $row->barcode; ?>">
															</td>
															<td>
																<input type="text" class="form-control itemName_<?php echo $cnt; ?>" placeholder="Item Name" value="<?php echo $row->itemname; ?>">
															</td>
															<td>
																<input type="text" class="form-control division_<?php echo $cnt; ?>" placeholder="Division" value="<?php echo $row->division; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurRateQty rate_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Rate" value="<?php echo $row->rate; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurRateQty quantity_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Quantity" value="<?php echo $row->quantity; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric amount_<?php echo $cnt; ?>" disabled="" placeholder="Amount" value="<?php echo $row->amount; ?>">
															</td>
															<td>
																<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delItemDtl"><i class="fa fa-close"></i></button>
															</td>
														</tr>
														<?php
														}
													}
												}
			                        			?>
				                        		</tbody>
				                        	</table>
			                        	</div>
		                        	</div>
		                        </div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-offset-8 col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Total
											</label>
				                            <div class="col-sm-8">
				                                <input type="text" class="form-control" id="totalAmount" name="totalAmount" placeholder="Total Amount" disabled="" value="<?php echo $totalAmount; ?>">
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-8">
	                    				<div class="form-group">
				                            <label class="col-sm-4 control-label">
												Remarks
											</label>
				                            <div class="col-sm-8">
				                                <textarea class="form-control" id="remarks" name="remarks" rows="5"><?php echo $remarks; ?></textarea>
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
												if($deliveryNoteId > 0)
												{
													echo 'Update DC';
												}
												else
												{
													echo 'Create DC';
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
	
	var deletedRowsCount = 0;
	
	$(document).ready(function()
	{
		$('#example').dataTable();
		$('.numeric').numeric();
		$('.datePicker').datepicker(
		{
			format: 'dd/mm/yyyy', 
			autoclose: true
		});
		
		var deliveryNoteId = '<?php echo $deliveryNoteId; ?>';
		if(deliveryNoteId == "")
		{
			addNewRow();
		}
	});
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$(document).on('click','.addRow',function()
	{
		addNewRow();
		scrollToDown();
	});
	
	function addNewRow()
	{
		var rowNo = $(".itemDetailsTBody tr").length;
		rowNo = parseInt(rowNo) + parseInt(deletedRowsCount) + 1;
		
		renderNewRows(rowNo);
	}
	
	function scrollToDown()
	{
		$("html, body").animate({ scrollTop: $(document).height() }, 1000);
	}
	
	function renderNewRows(rowNo)
	{
		if(rowNo > 0)
		{
			var str = '';
			
			str += '<tr class="itemDetailsTR" rowNo="'+parseInt(rowNo)+'">';
			str += '<td>';
			str += '<input type="text" class="form-control onBlurBarcode barcode_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Barcode">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control itemName_'+parseInt(rowNo)+'" placeholder="Item Name">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control division_'+parseInt(rowNo)+'" placeholder="Division">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurRateQty rate_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Rate">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurRateQty quantity_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Quantity">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric amount_'+parseInt(rowNo)+'" disabled="" placeholder="Amount">';
			str += '</td>';
			str += '<td>';
			str += '<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delItemDtl"><i class="fa fa-close"></i></button>';
			str += '</td>';
			str += '</tr>';
			
			$(".itemDetailsTBody").append(str);
			
			$(".numeric").numeric();
		}
	}
	
	$(document).on('blur','.onBlurBarcode',function()
	{
		var rowNo = $(this).attr('rowNo');
		if(rowNo > 0)
		{
			var barcode = $(".barcode_"+rowNo).val();
			if(barcode != "")
			{
				var isDuplicate = checkDuplicateBarcode(rowNo, barcode);
				
				if(isDuplicate)
				{
					alert('This Barcode Is Already Entered. Please Check.');
					$(".barcode_"+rowNo).val('');
					return;
				}
				else
				{
					var req = new Request();
					req.data = 
					{
						"barcode" : barcode
					};
					req.url = "admin/getBarcodeDetails";
					RequestHandler(req, setBarcodeDetails, rowNo);
				}
			}
			else
			{
				return;
			}
		}
	});
	
	function checkDuplicateBarcode(rowNo, barcode)
	{
		var isError = false;
		$(".itemDetailsTR").each(function()
		{
			var tempRowNo = $(this).attr('rowNo');
			var tempBarcode = $(".barcode_"+tempRowNo).val();
			
			if(tempRowNo != rowNo && tempBarcode == barcode)
			{
				isError = true;
			}
		});
		return isError;
	}
	
	function setBarcodeDetails(data, rowNo)
	{
		data = JSON.parse(data);
		
		var isError = data.isError;
		var msg = data.msg;
		if(!isError)
		{
			var res = data.res;
			if(res.length > 0)
			{
				$(".itemName_"+rowNo).val(res[0].itemname);
				$(".rate_"+rowNo).val(res[0].rate);
			}
		}
	}
	
	$(document).on('blur','.onBlurRateQty',function()
	{
		var rowNo = $(this).attr('rowNo');
		if(rowNo > 0)
		{
			var rate = $(".rate_"+rowNo).val();
			var qty = $(".quantity_"+rowNo).val();
			var amount = parseFloat(rate ? rate : 0) * parseFloat(qty ? qty : 0);
			
			$(".amount_"+rowNo).val(amount);
		}
		
		calculateTotalAmount();
	});
	
	function calculateTotalAmount()
	{
		var totalAmount = 0;
		$(".itemDetailsTBody tr.itemDetailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			var amount = $(".amount_"+rowNo).val();
			
			totalAmount += parseFloat(amount ? amount : 0);
		});
		
		$("#totalAmount").val(totalAmount);
	}
	
	$(document).on('click','.delItemDtl',function()
	{
		var bool = confirm("Are you sure want to Remove this Item Detail?");
		if(bool == true)
		{
			deletedRowsCount++;
			$(this).closest('tr').remove();
			calculateTotalAmount();
		}
	});
	
	$("#deliveryNoteForm").submit(function(e)
	{
		e.preventDefault();
		
		var deliveryNoteId = '<?php echo $deliveryNoteId; ?>';
		var deliveryNo = $("#deliveryNo").val();
		var dcDate = $("#dcDate").val();
		var supplierName = $("#supplierName").val();
		var supplierAddress = $("#supplierAddress").val();
		var customerName = $("#customerName").val();
		var receiverName = $("#receiverName").val();
		var totalAmount = $("#totalAmount").val();
		var deliveryFrom = $("#deliveryFrom").val();
		var deliveredBy = $("#deliveredBy").val();
		var remarks = $("#remarks").val();
		
		var dtlArr = [];
		
		var isError = false;
		
		$(".itemDetailsTBody tr.itemDetailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			var barcode = $(".barcode_"+rowNo).val();
			var itemName = $(".itemName_"+rowNo).val();
			var division = $(".division_"+rowNo).val();
			var rate = $(".rate_"+rowNo).val();
			var quantity = $(".quantity_"+rowNo).val();
			var amount = $(".amount_"+rowNo).val();
			
			if(barcode != "" && itemName != "" && division != "" && rate > 0 && quantity > 0 && amount > 0)
			{
				var cri = {};
				cri["barcode"] = barcode;
				cri["itemName"] = itemName;
				cri["division"] = division;
				cri["rate"] = rate;
				cri["quantity"] = quantity;
				cri["amount"] = amount;
				
				dtlArr.push(cri);
			}
			else
			{
				isError = true;
			}
		});
		
		if(isError)
		{
			alert('Please Fill All Item Details.');
			return;
		}
		
		if(deliveryNo != "" && supplierName != "" && supplierAddress != "" && customerName != "" && receiverName != "" && totalAmount > 0 && deliveryFrom != "" && deliveredBy != "" && dtlArr.length > 0)
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"deliveryNoteId" : deliveryNoteId,
					"deliveryNo" : deliveryNo,
					"dcDate" : dcDate,
					"supplierName" : supplierName,
					"supplierAddress" : supplierAddress,
					"customerName" : customerName,
					"receiverName" : receiverName, 
					"totalAmount" : totalAmount, 
					"deliveryFrom" : deliveryFrom, 
					"deliveredBy" : deliveredBy, 
					"remarks" : remarks, 
					"dtlArr" : JSON.stringify(dtlArr)
				};
				req.url = "admin/saveDeliveryNote";
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
		var deliveryNoteId = $(this).attr('deliveryNoteId');
		if(deliveryNoteId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/deliverynote/'+deliveryNoteId;
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
					"tableName" : "deliverynote_hdr", 
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
		var deliveryNoteId = $(this).attr('deliveryNoteId');
		if(deliveryNoteId > 0)
		{
			window.open('<?php echo base_url(); ?>admin/printDeliveryNote/'+deliveryNoteId);
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
			
			redirectURL = '<?php echo base_url(); ?>admin/deliverynote';
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
		location.href = '<?php echo base_url(); ?>admin/deliverynote/';
	});
	
</script>