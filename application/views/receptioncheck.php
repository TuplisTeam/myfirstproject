<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/receptioncheck" style="text-decoration: none;">
				Reception Check
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($receptionCheckId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Reception Check Details</h4>
						<button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
						   <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
	                            <thead>
	                                <tr>
	                                    <th>From</th>
	                                    <th>To</th>
	                                    <th>DC No./Invoice No.</th>
	                                    <th>Lot No./I. No.</th>
	                                    <th>Vehicle No.</th>
	                                    <th>Date</th>
	                                    <th>Unit Name</th>
	                                    <th>Manage</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php
	                                foreach($receptionCheckDtls as $row)
	                                {
									?>
									<tr>
										<td><?php echo $row->fromname; ?></td>
										<td><?php echo $row->toname; ?></td>
										<td><?php echo $row->deliveryno; ?></td>
										<td><?php echo $row->lotno; ?></td>
										<td><?php echo $row->vehicleno; ?></td>
										<td><?php echo $row->rcdt; ?></td>
										<td><?php echo $row->unitname; ?></td>
										<td>
											<button class="btn btn-sm btn-success editEntry" receptionCheckId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-pencil"></span>
											</button>
											<button class="btn btn-sm btn-danger delEntry" receptionCheckId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-close"></span>
											</button>
											<button class="btn btn-sm btn-warning printEntry" receptionCheckId="<?php echo $row->id; ?>" title="Print">
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
	                    <h4 class="panel-title">Reception Check Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="receptionCheckForm" method="POST">
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											From&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" id="fromName" name="fromName" placeholder="From" value="<?php echo $fromName; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											To&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" id="toName" name="toName" placeholder="To" value="<?php echo $toName; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											DC No. / Invoice No.&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <select class="form-control" id="dcNo" name="dcNo" data-placeholder="Select DC No." style="width: 100%;">
			                                	<option value=""></option>
			                                	<?php
			                                	foreach($dcDtls as $row)
			                                	{
												echo '<option value="'.$row->id.'"';
												echo '>'.$row->deliveryno.'</option>';
												}
			                                	?>
			                                </select>
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Lot No. / I. No.&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" id="lotNo" name="lotNo" placeholder="Lot No." value="<?php echo $lotNo; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Vehicle No.&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" id="vehicleNo" name="vehicleNo" placeholder="Vehicle No." value="<?php echo $vehicleNo; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Date&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control datePicker" id="rcDate" name="rcDate" placeholder="Date" value="<?php echo $rcDate; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Unit Name&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" id="unitName" name="unitName" placeholder="Unit Name" value="<?php echo $unitName; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="form-group">
		                        	<div class="col-md-12">
		                        		<div class="table-responsive">
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
			                        						<input type="checkbox" class="form-control" id="descCheck1" name="descCheck1" <?php
			                        			if($descCheck1 == "yes")
			                        			{
			                        				echo 'checked=""';
			                        			}
			                        			?> />
			                        					</td>
			                        				</tr>
			                        				<tr>
			                        					<td>2</td>
			                        					<td>See also the proper closing and opening of the packaging materials so that the product is not exposed directly for the outside materials as contaminants to loose the product integrity.</td>
			                        					<td>
			                        						<input type="checkbox" class="form-control" id="descCheck2" name="descCheck2" <?php
			                        			if($descCheck2 == "yes")
			                        			{
			                        				echo 'checked=""';
			                        			}
			                        			?> />
			                        					</td>
			                        				</tr>
			                        				<tr>
			                        					<td>3</td>
			                        					<td>Check for the required labels Organic/Fair Trade on the carton or on the covered product surface.</td>
			                        					<td>
			                        						<input type="checkbox" class="form-control" id="descCheck3" name="descCheck3" <?php
			                        			if($descCheck3 == "yes")
			                        			{
			                        				echo 'checked=""';
			                        			}
			                        			?> />
			                        					</td>
			                        				</tr>
			                        				<tr>
			                        					<td>4</td>
			                        					<td>Check for the quantity of the materials received/transferred based on the D.C's., Invoice & Packing List.</td>
			                        					<td>
			                        						<input type="checkbox" class="form-control" id="descCheck4" name="descCheck4" <?php
			                        			if($descCheck4 == "yes")
			                        			{
			                        				echo 'checked=""';
			                        			}
			                        			?> />
			                        					</td>
			                        				</tr>
				                        		</tbody>
				                        	</table>
			                        	</div>
		                        	</div>
		                        </div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Checked By&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" id="checkedBy" name="checkedBy" placeholder="Checked By" value="<?php echo $checkedBy; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Incharge&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control" id="incharge" name="incharge" placeholder="Incharge" value="<?php echo $incharge; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="form-group">
			                            <label class="col-sm-2 control-label">
											Remarks
										</label>
			                            <div class="col-sm-6">
			                                <textarea class="form-control" id="remarks" name="remarks" rows="5"><?php echo $remarks; ?></textarea>
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
												if($receptionCheckId > 0)
												{
													echo 'Update';
												}
												else
												{
													echo 'Create';
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
		$('select').select2();
		$('.datePicker').datepicker(
		{
			format: 'dd/mm/yyyy', 
			autoclose: true
		});
		
		var receptionCheckId = '<?php echo $receptionCheckId; ?>';
		if(receptionCheckId > 0)
		{
			$("#dcNo").select2('val', '<?php echo $dcNo; ?>');
		}
	});
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$("#receptionCheckForm").submit(function(e)
	{
		e.preventDefault();
		
		var receptionCheckId = '<?php echo $receptionCheckId; ?>';
		var fromName = $("#fromName").val();
		var toName = $("#toName").val();
		var dcNo = $("#dcNo").val();
		var lotNo = $("#lotNo").val();
		var vehicleNo = $("#vehicleNo").val();
		var rcDate = $("#rcDate").val();
		var unitName = $("#unitName").val();
		var descCheck1 = $("#descCheck1").is(":checked");
		var descCheck2 = $("#descCheck2").is(":checked");
		var descCheck3 = $("#descCheck3").is(":checked");
		var descCheck4 = $("#descCheck4").is(":checked");
		var checkedBy = $("#checkedBy").val();
		var incharge = $("#incharge").val();
		var remarks = $("#remarks").val();
		
		
		if(fromName != "" && toName != "" && dcNo > 0 && lotNo != "" && vehicleNo != "" && rcDate != "" && unitName != "" && checkedBy != "" && incharge != "")
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"receptionCheckId" : receptionCheckId,
					"fromName" : fromName,
					"toName" : toName,
					"dcNo" : dcNo,
					"lotNo" : lotNo,
					"vehicleNo" : vehicleNo,
					"rcDate" : rcDate, 
					"unitName" : unitName, 
					"descCheck1" : descCheck1 ? "yes" : "no", 
					"descCheck2" : descCheck2 ? "yes" : "no", 
					"descCheck3" : descCheck3 ? "yes" : "no", 
					"descCheck4" : descCheck4 ? "yes" : "no", 
					"checkedBy" : checkedBy, 
					"incharge" : incharge, 
					"remarks" : remarks
				};
				req.url = "admin/saveReceptionCheck";
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
		var receptionCheckId = $(this).attr('receptionCheckId');
		if(receptionCheckId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/receptioncheck/'+receptionCheckId;
		}
	});
	
	$(".delEntry").on("click",function()
	{
		var receptionCheckId = $(this).attr('receptionCheckId');
		if(receptionCheckId > 0)
		{
			var bool = confirm("Do you want to remove this Reception Check Detail?");
			if(bool)
			{
				var req = new Request();
				req.data =
				{
					"receptionCheckId" : receptionCheckId
				};
				req.url = "admin/delReceptionCheck";
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
	
	$(".printEntry").click(function()
	{
		var receptionCheckId = $(this).attr('receptionCheckId');
		if(receptionCheckId > 0)
		{
			window.open('<?php echo base_url(); ?>admin/printReceptionCheck/'+receptionCheckId);
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
			
			redirectURL = '<?php echo base_url(); ?>admin/receptioncheck';
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
		location.href = '<?php echo base_url(); ?>admin/receptioncheck/';
	});
	
</script>