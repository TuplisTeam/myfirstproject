<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/employee_vs_operation" style="text-decoration: none;">
				Employees Vs Operations
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
                        <h4 class="panel-title">Employee Details</h4>
                        <button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
                        <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                            <thead>
                                <tr>
                                    <th>Entry Date</th>
                                    <th>Employee Name</th>
                                    <th>Line Name</th>
                                    <th>Line Location</th>
                                    <th>Shift Name</th>
                                    <th>Style Name</th>
                                    <th>Table Name</th>
                                    <th>Operation Name</th>
                                    <th>Machine Name</th>
                                    <th>SMV</th>
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
									<td><?php echo $row->entrydate; ?></td>
									<td><?php echo $row->empname; ?></td>
									<td><?php echo $row->line_name; ?></td>
									<td><?php echo $row->line_location; ?></td>
									<td><?php echo $row->shiftname; ?></td>
									<td><?php echo $row->styleno.' - '.$row->styledesc; ?></td>
									<td><?php echo $row->tablename; ?></td>
									<td><?php echo $row->operationname; ?></td>
									<td><?php echo $row->machineryname; ?></td>
									<td><?php echo $row->smv; ?></td>
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
	                    <h4 class="panel-title">Employees Vs Operations Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="entryForm" method="POST">
	                    	<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Entry Date&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control datePicker" id="entryDate" name="entryDate" placeholder="Entry Date" value="<?php echo $entryDate; ?>" required="">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">
									Employee Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <select class="form-control" style="width: 100%;" id="employeeId" name="employeeId">
										<option value="">All</option>
										<?php
										foreach($empDtls as $row)
										{
											echo '<option value="'.$row->id.'"';
											if($row->id == $employeeId)
											{
												echo ' selected="selected"';
											}
											echo '>'.$row->empno.' - '.$row->empname.'</option>';
										}
										?>
									</select>
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Line Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <select class="form-control" style="width: 100%;" id="lineId" name="lineId" required="">
										<option value="">All</option>
										<?php
										foreach($lineVsStyleDtls as $row)
										{
											echo '<option value="'.$row->id.'"';
											if($row->id == $lineId)
											{
												echo ' selected="selected"';
											}
											echo '>'.$row->line_name.' - '.$row->line_location.'</option>';
										}
										?>
									</select>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">
									Shift Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
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
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Style Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                            	<select class="form-control" id="styleId" style="width: 100%;" data-placeholder="Select" required="">
										<option value=""></option>
										<?php
										foreach($styleDtls as $row)
										{
											echo '<option value="'.$row->id.'"';
											if($row->id == $styleId)
											{
												echo ' selected="selected"';
											}
											echo '>'.$row->styleno.' - '.$row->styledesc.'</option>';
										}
										?>
									</select>
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Table Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="tableName" name="tableName" placeholder="Table Name" value="<?php echo $tableName; ?>" required="">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">
									Operation Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <select class="form-control" style="width: 100%;" id="operationId" name="operationId">
										<option value="">All</option>
										<?php
										foreach($operationDtls as $row)
										{
											echo '<option value="'.$row->id.'"';
											if($row->id == $operationId)
											{
												echo ' selected="selected"';
											}
											echo '>'.$row->operationname.'</option>';
										}
										?>
									</select>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">
									Machinary Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <select class="form-control" style="width: 100%;" id="machinaryId" name="machinaryId">
										<option value="">All</option>
										<?php
										foreach($machinaryDtls as $row)
										{
											echo '<option value="'.$row->id.'"';
											if($row->id == $machinaryId)
											{
												echo ' selected="selected"';
											}
											echo '>'.$row->machineryname.'</option>';
										}
										?>
									</select>
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									SMV&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="smv" name="smv" value="<?php echo $smv; ?>" disabled="" />
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Target Minutes
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control numeric" id="targetMinutes" name="targetMinutes" value="<?php echo $targetMinutes; ?>" />
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									OT Hours
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control numeric" id="otHours" name="otHours" value="<?php echo $otHours; ?>" />
	                            </div>
	                        </div>
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
		$('.datePicker').datepicker(
		{
			format: 'dd/mm/yyyy', 
			autoclose: true
		});
		$("select").select2();
	});
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$("#styleId,#operationId,#machinaryId").change(function()
	{
		var styleId = $("#styleId").val();
		var operationId = $("#operationId").val();
		var machinaryId = $("#machinaryId").val();
		
		if(styleId > 0 && operationId > 0 && machinaryId > 0)
		{
			var req = new Request();
			req.data = 
			{
				"styleId" : styleId, 
				"operationId" : operationId, 
				"machinaryId" : machinaryId
			};
			req.url = "admin/getSMVFromOBSheet";
			RequestHandler(req, setSMVValue);
		}
		else
		{
			return;
		}
	});
	
	function setSMVValue(data)
	{
		data = JSON.parse(data);
		var isError = data.isError;
		var msg = data.msg;
		if(isError)
		{
			alert(msg);
			$("#smv").val(0);
		}
		else
		{
			$("#smv").val(data.smv);
		}
	}
	
	$("#entryForm").submit(function(e)
	{
		e.preventDefault();
		
		var entryId = '<?php echo $entryId; ?>';
		
		var entryDate = $("#entryDate").val();
		var employeeId = $("#employeeId").val();
		var lineId = $("#lineId").val();
		var shiftId = $("#shiftId").val();
		var styleId = $("#styleId").val();
		var tableName = $("#tableName").val();
		var operationId = $("#operationId").val();
		var machinaryId = $("#machinaryId").val();
		var smv = $("#smv").val();
		var targetMinutes = $("#targetMinutes").val();
		var otHours = $("#otHours").val();
		
		if(entryDate != "" && employeeId > 0 && lineId > 0 && shiftId > 0 && styleId > 0 && tableName != "" && operationId > 0 && machinaryId > 0 && smv != "")
		{
			$("#responseMsg").html('');
			
			var req = new Request();
			req.data = 
			{
				"menuId" : '<?php echo $menuId; ?>', 
				"entryId" : entryId,
				"entryDate" : entryDate, 
				"employeeId" : employeeId, 
				"lineId" : lineId, 
				"shiftId" : shiftId, 
				"styleId" : styleId, 
				"tableName" : tableName, 
				"operationId" : operationId, 
				"machinaryId" : machinaryId, 
				"smv" : smv, 
				"targetMinutes" : targetMinutes, 
				"otHours" : otHours
			};
			req.url = "admin/saveEmployeeVsOperation";
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
			location.href = '<?php echo base_url(); ?>admin/employee_vs_operation/'+entryId;
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
					"tableName" : "employee_vs_operation", 
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
			
			redirectURL = '<?php echo base_url(); ?>admin/employee_vs_operation';
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
		location.href = '<?php echo base_url(); ?>admin/employee_vs_operation/';
	});
	
</script>