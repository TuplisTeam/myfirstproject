<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/rackdisplay" style="text-decoration: none;">
				Rack Display
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($rackDisplayId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Rack Display Details</h4>
						<button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
						   <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
	                            <thead>
	                                <tr>
	                                    <th>Date</th>
	                                    <th>Manage</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php
	                                foreach($rackDisplayDtls as $row)
	                                {
									?>
									<tr>
										<td><?php echo $row->entrydate; ?></td>
										<td>
											<button class="btn btn-sm btn-success editEntry" rackDisplayId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-pencil"></span>
											</button>
											<button class="btn btn-sm btn-danger delEntry" rackDisplayId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-close"></span>
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
	                    <h4 class="panel-title">Rack Display Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="rackDisplayForm" method="POST">
							<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Entry Date&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-4">
			                                <input type="text" class="form-control datePicker" id="entryDate" name="entryDate" placeholder="Entry Date" value="<?php echo $entryDate; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
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
			                        				<th>Rack No.</th>
													<th>Order No.</th>
													<th>No. Of Rolls</th>
													<th>Barcode Id</th>
													<th>Line Incharge</th>
													<th>Weight</th>
													<th>Dispatched</th>
													<th>Pending</th>
													<th>Total Available Stock</th>
													<th>Remarks</th>
			                        				<th>Manage</th>
			                        			</tr>
			                        		</thead>
			                        		<tbody class="rackDetailsTBody">
		                        				<?php
												if($rackDisplayId > 0)
												{
													$cnt = 0;
													foreach($dtlArr as $row)
													{
														$cnt++;
													?>
													<tr class="rackDetailsTR" rowNo="<?php echo $cnt; ?>">
														<td>
														<input type="text" class="form-control rackNo_<?php echo $cnt; ?>" placeholder="Rack No." value="<?php echo $row->rackname; ?>">
														</td>
														<td>
														<input type="text" class="form-control orderNo_<?php echo $cnt; ?>" placeholder="Order No." value="<?php echo $row->orderno; ?>">
														</td>
														<td>
														<input type="text" class="form-control numeric noOfRolls_<?php echo $cnt; ?>" placeholder="No. Of Rolls" value="<?php echo $row->noofrolls; ?>">
														<button type="button" class="btn btn-xs btn-warning addProcessInfo" title="Add Process Info" rowNo="<?php echo $cnt; ?>">
															<i class="fa fa-plus"></i>
														</button>
														</td>
														<td>
														<input type="text" class="form-control barcodeId_<?php echo $cnt; ?>" placeholder="Barcode" value="<?php echo $row->barcodeid; ?>">
														</td>
														<td>
														<input type="text" class="form-control lineIncharge_<?php echo $cnt; ?>" placeholder="Line Incharge" value="<?php echo $row->lineincharge; ?>">		
														</td>
														<td>
														<input type="text" class="form-control numeric weight_<?php echo $cnt; ?>" placeholder="Weight" value="<?php echo $row->weight; ?>">
														</td>
														<td>
														<input type="text" class="form-control numeric dispatched_<?php echo $cnt; ?>" placeholder="Dispatched" value="<?php echo $row->dispatched; ?>">	
														</td>
														<td>
														<input type="text" class="form-control numeric pending_<?php echo $cnt; ?>" placeholder="Pending" value="<?php echo $row->pending; ?>">
														</td>
														<td>
														<input type="text" class="form-control numeric totalAvailStock_<?php echo $cnt; ?>" placeholder="Total Available Stock" value="<?php echo $row->totalavailstock; ?>">		
														</td>
														<td>
														<textarea class="form-control remarks_<?php echo $cnt; ?>" placeholder="Remarks"><?php echo $row->remarks; ?></textarea>
														</td>
														<td>
														<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delRackDtl"><i class="fa fa-close"></i></button>
														</td>
													</tr>
													<?php
													}
												}
												?>
			                        		</tbody>
			                        	</table>
		                        	</div>
	                    		</div>
	                    	</div>
	                        <div class="row">
	                    		<div class="col-md-12">
			                        <div class="form-group">
			                            <div class="col-sm-offset-4 col-sm-8">
			                                <button type="submit" class="btn btn-success">
												Update Rack
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

<!--Modal Window Starts-->

<div class="modal fade processInfoModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <h4 class="modal-title" id="mySmallModalLabel">Process Info</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="processInfoForm" method="POST">
					<div class="row">
                		<div class="col-md-12">
                			<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Process 1&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="process1" placeholder="Process 1" value="">
									<input type="hidden" id="curRowNo_Process" />
	                            </div>
	                        </div>
                		</div>
                	</div>
					<div class="row">
                		<div class="col-md-12">
                			<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Process 2&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="process2" placeholder="Process 2" value="">
	                            </div>
	                        </div>
                		</div>
                	</div>
					<div class="row">
                		<div class="col-md-12">
                			<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Process 3&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="process3" placeholder="Process 3" value="">
	                            </div>
	                        </div>
                		</div>
                	</div>
				</form>
            </div>
            <div class="modal-footer">
				<div class="row">
            		<div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="button" class="btn btn-success updateProcessInfo">
									Update Process
								</button>
								<button type="reset" class="btn btn-info" data-dismiss="modal">
									Close
								</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Modal Window Ends-->

<script>
	
	var processInfoArr = [];
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
		
		var rackDisplayId = '<?php echo $rackDisplayId; ?>';
		if(rackDisplayId == "")
		{
			addNewRow();
		}
		else
		{
			var processDtlsArr = '<?php echo json_encode($processDtlsArr); ?>';
			processDtlsArr = JSON.parse(processDtlsArr);
			
			if(processDtlsArr.length > 0)
			{
				for(var k=0; k<processDtlsArr.length; k++)
				{
					var cri = {};
					cri["rackNo"] = processDtlsArr[k].rackname;
					cri["process1"] = processDtlsArr[k].process1;
					cri["process2"] = processDtlsArr[k].process2;
					cri["process3"] = processDtlsArr[k].process3;
					
					processInfoArr.push(cri);
				}
			}
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
		var rowNo = $(".rackDetailsTBody tr").length;
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
			
			str += '<tr class="rackDetailsTR" rowNo="'+parseInt(rowNo)+'">';
			str += '<td>';
			str += '<input type="text" class="form-control rackNo_'+parseInt(rowNo)+'" placeholder="Rack No.">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control orderNo_'+parseInt(rowNo)+'" placeholder="Order No.">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric noOfRolls_'+parseInt(rowNo)+'" placeholder="No. Of Rolls">';
			str += '<button type="button" class="btn btn-xs btn-warning addProcessInfo" title="Add Process Info" rowNo="'+parseInt(rowNo)+'">>';
			str += '<i class="fa fa-plus"></i>';
			str += '</button>';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control barcodeId_'+parseInt(rowNo)+'" placeholder="Barcode">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control lineIncharge_'+parseInt(rowNo)+'" placeholder="Line Incharge">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric weight_'+parseInt(rowNo)+'" placeholder="Weight">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric dispatched_'+parseInt(rowNo)+'" placeholder="Dispatched">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric pending_'+parseInt(rowNo)+'" placeholder="Pending">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric totalAvailStock_'+parseInt(rowNo)+'" placeholder="Total Available Stock">';
			str += '</td>';
			str += '<td>';
			str += '<textarea class="form-control remarks_'+parseInt(rowNo)+'" placeholder="Remarks"></textarea>';
			str += '</td>';
			str += '<td>';
			str += '<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delRackDtl"><i class="fa fa-close"></i></button>';
			str += '</td>';
			str += '</tr>';
			
			$(".rackDetailsTBody").append(str);
			$(".numeric").numeric();
		}
	}
	
	$(document).on('click','.addProcessInfo',function()
	{
		var rowNo = $(this).attr('rowNo');
		if(rowNo > 0)
		{
			$("#curRowNo_Process").val(rowNo);
			var rackNo = $(".rackNo_"+rowNo).val();
			
			if(processInfoArr.length > 0)
			{
				for(var n=0; n<processInfoArr.length; n++)
				{
					if(processInfoArr[n] != null)
					{
						if(processInfoArr[n].rackNo == rackNo)
						{
							$("#process1").val(processInfoArr[n].process1);
							$("#process2").val(processInfoArr[n].process2);
							$("#process3").val(processInfoArr[n].process3);
						}
					}
				}
			}
			
			$(".processInfoModal").modal('show');
		}
	});
	
	$(".updateProcessInfo").click(function()
	{
		var rowNo = $("#curRowNo_Process").val();
		if(rowNo > 0)
		{
			var rackNo = $(".rackNo_"+rowNo).val();
			var process1 = $("#process1").val();
			var process2 = $("#process2").val();
			var process3 = $("#process3").val();
			
			var isSet = false;
			
			if(processInfoArr.length > 0)
			{
				for(var n=0; n<processInfoArr.length; n++)
				{
					if(processInfoArr[n] != null)
					{
						if(processInfoArr[n].rackNo == rackNo)
						{
							isSet = true;
							
							processInfoArr[n].process1 = process1;
							processInfoArr[n].process2 = process2;
							processInfoArr[n].process3 = process3;
						}
					}
				}
			}
			
			if(!isSet)
			{
				var cri = {};
				cri["rackNo"] = rackNo;
				cri["process1"] = process1;
				cri["process2"] = process2;
				cri["process3"] = process3;
				
				processInfoArr.push(cri);
			}
			alert('Process Info Updated Successfully.');
			$(".processInfoModal").modal('hide');
			clearModalFields();
		}
		else
		{
			return;
		}
	});
	
	function clearModalFields()
	{
		$("#curRowNo_Process").val('');
		$("#process1").val('');
		$("#process2").val('');
		$("#process3").val('');
	}
	
	$(document).on('click','.delRackDtl',function()
	{
		var bool = confirm("Are you sure want to Remove this Rack Detail?");
		if(bool == true)
		{
			deletedRowsCount++;
			$(this).closest('tr').remove();
		}
	});
	
	$("#rackDisplayForm").submit(function(e)
	{
		e.preventDefault();
		
		var rackDisplayId = '<?php echo $rackDisplayId; ?>';
		var entryDate = $("#entryDate").val();
		
		var dtlArr = [];
		
		var isError = false;
		
		$(".rackDetailsTBody tr.rackDetailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			var rackNo = $(".rackNo_"+rowNo).val();
			var orderNo = $(".orderNo_"+rowNo).val();
			var noOfRolls = $(".noOfRolls_"+rowNo).val();
			var barcodeId = $(".barcodeId_"+rowNo).val();
			var lineIncharge = $(".lineIncharge_"+rowNo).val();
			var weight = $(".weight_"+rowNo).val();
			var dispatched = $(".dispatched_"+rowNo).val();
			var pending = $(".pending_"+rowNo).val();
			var totalAvailStock = $(".totalAvailStock_"+rowNo).val();
			var remarks = $(".remarks_"+rowNo).val();
			
			if(rackNo != "" && orderNo != "" && noOfRolls != "" && barcodeId != "" && lineIncharge != "" && weight != "")
			{
				var cri = {};
				cri["rackNo"] = rackNo;
				cri["orderNo"] = orderNo;
				cri["noOfRolls"] = noOfRolls;
				cri["barcodeId"] = barcodeId;
				cri["lineIncharge"] = lineIncharge;
				cri["weight"] = weight;
				cri["dispatched"] = dispatched;
				cri["pending"] = pending;
				cri["totalAvailStock"] = totalAvailStock;
				cri["remarks"] = remarks;
				
				dtlArr.push(cri);
			}
			else
			{
				isError = true;
			}
		});
		
		if(isError)
		{
			alert('Please Fill All Rack Details.');
			return;
		}
		
		if(entryDate != "" && dtlArr.length > 0)
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"rackDisplayId" : rackDisplayId,
					"entryDate" : entryDate,
					"dtlArr" : JSON.stringify(dtlArr), 
					"processInfoArr" : JSON.stringify(processInfoArr)
				};
				req.url = "admin/saveRackDetails";
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
		var rackDisplayId = $(this).attr('rackDisplayId');
		if(rackDisplayId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/rackdisplay/'+rackDisplayId;
		}
	});
	
	$(".delEntry").on("click",function()
	{
		var rackDisplayId = $(this).attr('rackDisplayId');
		if(rackDisplayId > 0)
		{
			var bool = confirm("Do you want to remove this Rack Detail?");
			if(bool)
			{
				var req = new Request();
				req.data =
				{
					"rackDisplayId" : rackDisplayId
				};
				req.url = "admin/delRackDisplay";
				RequestHandler(req, showResponse);
			}
			else
			{
				return;
			}
		}
		else
		{
			return;
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
			
			redirectURL = '<?php echo base_url(); ?>admin/rackdisplay';
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
		location.href = '<?php echo base_url(); ?>admin/rackdisplay/';
	});
	
</script>