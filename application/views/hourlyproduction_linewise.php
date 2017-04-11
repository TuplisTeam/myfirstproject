<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/hourlyproduction_linewise" style="text-decoration: none;">
				Hourly Production Line Wise
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($entryId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Line Details</h4>
                        <button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
                        <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                            <thead>
                                <tr>
                                    <th>Line No.</th>
                                    <th>Shift</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
							if(count($allDetails) > 0)
							{
								foreach($allDetails as $row)
								{
								?>
								<tr>
									<td><?php echo $row->linename; ?></td>
									<td><?php echo $row->shiftname; ?></td>
									<td>
										<button class="btn btn-primary btn-xs editEntry" entryId="<?php echo $row->id; ?>">
											Edit
										</button>
										<button class="btn btn-danger btn-xs delEntry" entryId="<?php echo $row->id; ?>">
											Del
										</button>
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
			</div>
		</div>
        <div class="row" id="entryDetails" <?php echo $displayblock; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
	                <div class="panel-heading clearfix">
	                    <h4 class="panel-title">Hourly Production Line Wise Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="entryForm" method="POST">	
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Entry Date&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control datePicker" id="entryDate" name="entryDate" placeholder="Entry Date" value="<?php echo $entryDate; ?>" required="">
			                            </div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Line Name&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control onBlurDateLine" id="lineName" name="lineName" placeholder="Line Name" value="<?php echo $lineName; ?>" required="">
			                            </div>
			                        </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Shift Time&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <select class="form-control" id="shiftId" style="width: 100%;" data-placeholder="Select">
												<option value=""></option>
												<?php
												foreach($shiftTimingDtls as $row)
												{
													echo '<option value="'.$row->id.'"';
													if($row->id == $shiftId)
													{
														echo ' selected="selected"';
													}
													echo '>'.$row->shiftname.'</option>';
												}
												?>
											</select>
			                            </div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Operation&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <select class="form-control" style="width: 100%;" data-placeholder="Select" id="operationId" name="operationId[]" multiple="" required="">
												<option value=""></option>
												<?php
												foreach($operationDtls as $res)
												{
													echo '<option value="'.$res->id.'"';
													foreach($operationIdArr as $row)
													{
														if($row->operationid == $res->id)
														{
															echo ' selected="selected"';
														}
													}
													echo '>'.$res->operationname.'</option>';
												}
												?>
											</select>
			                            </div>
			                        </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											No. Of Workers&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="noOfWorkers" name="noOfWorkers" placeholder="No. Of Workers" value="<?php echo $noOfWorkers; ?>" disabled="" required="">
			                            </div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Days Target&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="daysTarget" name="daysTarget" placeholder="Days Target" value="<?php echo $daysTarget; ?>" disabled="" required="">
			                            </div>
			                        </div>
								</div>
							</div>
	                        <div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Target Per Hour&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="targetPerHour" name="targetPerHour" placeholder="Target Per Hour" value="<?php echo $targetPerHour; ?>" disabled="" required="">
			                            </div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											No. Of Operators&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="noOfOperators" name="noOfOperators" placeholder="No. Of Operators" value="<?php echo $noOfOperators; ?>" required="">
			                            </div>
			                        </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Avail Minutes&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="availMinutes" name="availMinutes" placeholder="Avail Minutes" value="<?php echo $availMinutes; ?>" required="">
			                            </div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Current Target&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="currentTarget" name="currentTarget" placeholder="Current Target" value="<?php echo $currentTarget; ?>" required="">
			                            </div>
			                        </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Issues&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <textarea class="form-control" id="issues" name="issues" placeholder="Issues"><?php echo $issues; ?></textarea>
			                            </div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											WIP
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control" id="wip" name="wip" placeholder="WIP" value="<?php echo $wip; ?>">
			                            </div>
			                        </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Idle Time
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="idleTime" name="idleTime" placeholder="Idle Time" value="<?php echo $idleTime; ?>">
			                            </div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Breakdown Time
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="breakDownTime" name="breakDownTime" placeholder="Breakdown Time" value="<?php echo $breakDownTime; ?>">
			                            </div>
			                        </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Rework Time
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="reworkTime" name="reworkTime" placeholder="Rework Time" value="<?php echo $reworkTime; ?>">
			                            </div>
			                        </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											No Work Time
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="noWorkTime" name="noWorkTime" placeholder="No Work Time" value="<?php echo $noWorkTime; ?>">
			                            </div>
			                        </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Line Efficiency
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control numeric" id="lineEfficiency" name="lineEfficiency" placeholder="Line Efficiency" value="<?php echo $lineEfficiency; ?>">
			                            </div>
			                        </div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
		                            <div class="col-sm-offset-2 col-sm-10">
		                                <button type="submit" class="btn btn-success">
											<?php
											if($entryId > 0)
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
		$('.numeric').numeric();
		$('select').select2();
		$('.datePicker').datepicker(
		{
			format: 'dd/mm/yyyy', 
			autoclose: true
		});
	});
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$("#shiftId").change(function()
	{
		getLineDetails();
	});
	
	$(".onBlurDateLine").blur(function()
	{
		getLineDetails();
	});
	
	function getLineDetails()
	{
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		var shiftId = $("#shiftId").val();
		if(entryDate != "" && lineName != "" && shiftId > 0)
		{
			var req = new Request();
			req.data = 
			{
				"entryDate" : entryDate, 
				"lineName" : lineName, 
				"shiftId" : shiftId
			};
			req.url = "admin/getLineDetails";
			RequestHandler(req, setLineDetails);
		}
	}
	
	function setLineDetails(data)
	{
		data = JSON.parse(data);
		if(data.isError)
		{
			alert(data.msg);
		}
		else
		{
			var res = data.res;
			if(res.length > 0)
			{
				$("#noOfWorkers").val(res[0].noofworkers);
				$("#daysTarget").val(res[0].target);
				$("#targetPerHour").val(parseFloat(res[0].target/8));
			}
			else
			{
				$("#noOfWorkers").val('');
				$("#daysTarget").val('');
				$("#targetPerHour").val('');
			}
		}
	}
	
	$("#entryForm").submit(function(e)
	{
		e.preventDefault();
		
		var entryId = '<?php echo $entryId; ?>';
		
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		var shiftId = $("#shiftId").val();
		var operationId = $("#operationId").val();
		var noOfWorkers = $("#noOfWorkers").val();
		var daysTarget = $("#daysTarget").val();
		var targetPerHour = $("#targetPerHour").val();
		var noOfOperators = $("#noOfOperators").val();
		var availMinutes = $("#availMinutes").val();
		var currentTarget = $("#currentTarget").val();
		var issues = $("#issues").val();
		var wip = $("#wip").val();
		var idleTime = $("#idleTime").val();
		var breakDownTime = $("#breakDownTime").val();
		var reworkTime = $("#reworkTime").val();
		var noWorkTime = $("#noWorkTime").val();
		var lineEfficiency = $("#lineEfficiency").val();
		
		if(entryDate != "" && lineName != "" && shiftId > 0 && operationId != "" && noOfWorkers > 0 && daysTarget > 0 && noOfOperators > 0 && availMinutes > 0 && currentTarget > 0 && issues != "")
		{
			$("#responseMsg").html('');
			
			var req = new Request();
			req.data = 
			{
				"menuId" : '<?php echo $menuId; ?>', 
				"entryId" : entryId,
				"entryDate" : entryDate, 
				"lineName" : lineName, 
				"shiftId" : shiftId, 
				"operationId" : operationId, 
				"noOfWorkers" : noOfWorkers, 
				"daysTarget" : daysTarget, 
				"targetPerHour" : targetPerHour, 
				"noOfOperators" : noOfOperators, 
				"availMinutes" : availMinutes, 
				"currentTarget" : currentTarget, 
				"issues" : issues, 
				"wip" : wip, 
				"idleTime" : idleTime, 
				"breakDownTime" : breakDownTime, 
				"reworkTime" : reworkTime, 
				"noWorkTime" : noWorkTime, 
				"lineEfficiency" : lineEfficiency
			};
			req.url = "admin/saveHourlyProduction_LineWise";
			RequestHandler(req, showResponse);
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
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/hourlyproduction_linewise/'+entryId;
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
					"tableName" : "hourlyproduction_linewise", 
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
			
			redirectURL = '<?php echo base_url(); ?>admin/hourlyproduction_linewise';
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
		location.href = '<?php echo base_url(); ?>admin/hourlyproduction_linewise/';
	});
	
</script>