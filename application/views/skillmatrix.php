<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/skillmatrix" style="text-decoration: none;">
				Individual Target Assign Form
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($skillMatrixId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Skill Matrix Details</h4>
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
	                                    <th>Shift Time</th>
	                                    <th>Line Name</th>
	                                    <th>Manage</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php
	                                foreach($allDetails as $row)
	                                {
									?>
									<tr>
										<td><?php echo $row->entrydate; ?></td>
										<td><?php echo $row->shifttime; ?></td>
										<td><?php echo $row->linename; ?></td>
										<td>
											<button class="btn btn-sm btn-success editEntry" skillMatrixId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-pencil"></span>
											</button>
											<button class="btn btn-sm btn-danger delEntry" entryId="<?php echo $row->id; ?>" title="Delete">
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
	                    <h4 class="panel-title">Skill Matrix Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="skillMatrixForm" method="POST">
	                    	<div class="row">
	                    		<div class="col-md-4">
	                    			<div class="form-group">
			                            <label class="col-sm-4 control-label">
											Entry Date&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control datePicker" id="entryDate" name="entryDate" placeholder="Entry Date" value="<?php echo $entryDate; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
								<div class="col-md-4">
	                    			<div class="form-group">
			                            <label class="col-sm-4 control-label">
											Shift Time&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <select class="form-control" id="shiftTime" style="width: 100%;" data-placeholder="Select">
												<option value=""></option>
												<option value="General">General</option>
												<option value="Other">Other</option>
											</select>
			                            </div>
			                        </div>
	                    		</div>
								<div class="col-md-4">
	                    			<div class="form-group">
			                            <label class="col-sm-4 control-label">
											Line Name&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control" id="lineName" name="lineName" placeholder="Line Name" value="<?php echo $lineName; ?>" required="">
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
				                        				<th width="15%">Employee Name</th>
				                        				<th width="10%">Operation</th>
				                        				<th width="10%">Target Minutes</th>
				                        				<th width="10%">Target Pieces</th>
				                        				<th width="10%">SAM</th>
				                        				<th width="10%">Actual Minutes</th>
				                        				<th width="10%">OT Hrs Worked</th>
				                        				<th width="15%">Manage</th>
				                        			</tr>
				                        		</thead>
				                        		<tbody class="empDetailsTBody">
			                        			<?php
			                        			if($skillMatrixId > 0)
			                        			{
													if(count($dtlArr) > 0)
													{
														$cnt = 0;
														foreach($dtlArr as $row)
														{
															$cnt++;
														?>
														<tr class="empDetailsTR" rowNo="<?php echo $cnt; ?>">
															<td>
																<select class="form-control empId_<?php echo $cnt; ?>" style="width: 100%;" data-placeholder="Select">
																<option value=""></option>
																<?php
																foreach($empDtls as $res)
																{
																	echo '<option value="'.$res->id.'"';
																	echo '>'.$res->empname.'</option>';
																}
																?>
																</select>
															</td>
															<td>
																<select class="form-control operationId_<?php echo $cnt; ?>" style="width: 100%;" data-placeholder="Select">
																<option value=""></option>
																<?php
																foreach($operationDtls as $res)
																{
																	echo '<option value="'.$res->id.'"';
																	echo '>'.$res->operationname.'</option>';
																}
																?>
																</select>
															</td>
															<td>
																<input type="text" class="form-control numeric producedMin_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Target Minutes" value="<?php echo $row->producedmin; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric pieces_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Target Pieces" value="<?php echo $row->pieces; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric sam_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="SAM" value="<?php echo $row->sam; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric shiftHrs_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Actual Minutes" value="<?php echo $row->shifthrs; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric otHours_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="OT Hours" value="<?php echo $row->othours; ?>">
															</td>
															<td>
																<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delEmpDtl"><i class="fa fa-close"></i></button>
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
			                        <div class="form-group">
			                            <div class="col-sm-offset-4 col-sm-8">
			                                <button type="submit" class="btn btn-success">
												<?php
												if($skillMatrixId > 0)
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

<div id="empDtlsDiv" style="display: none;"><?php echo json_encode($empDtls); ?></div>
<div id="operationDtlsDiv" style="display: none;"><?php echo json_encode($operationDtls); ?></div>
<script>
	
	var deletedRowsCount = 0;
	
	var empDtls = $("#empDtlsDiv").html();
	empDtls = JSON.parse(empDtls);
	
	var operationDtls = $("#operationDtlsDiv").html();
	operationDtls = JSON.parse(operationDtls);
	
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
		
		var skillMatrixId = '<?php echo $skillMatrixId; ?>';
		if(skillMatrixId == "")
		{
			addNewRow();
		}
		else
		{
			$("#shiftTime").select2('val', '<?php echo $shiftName; ?>');
			
			var myDtlArr = '<?php echo json_encode($dtlArr); ?>';
			myDtlArr = JSON.parse(myDtlArr);
			
			if(myDtlArr.length > 0)
			{
				for(var k=0; k<myDtlArr.length; k++)
				{
					$(".empId_"+(k+1)).select2('val',myDtlArr[k].empid);
					$(".operationId_"+(k+1)).select2('val',myDtlArr[k].operationid);
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
		var rowNo = $(".empDetailsTBody tr").length;
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
			
			str += '<tr class="empDetailsTR" rowNo="'+parseInt(rowNo)+'">';
			str += '<td>';
			str += '<select class="form-control empId_'+parseInt(rowNo)+'" style="width: 100%;" data-placeholder="Select">';
			str += '<option value=""></option>';
			for(var n=0; n<empDtls.length; n++)
			{
				str += '<option value="'+empDtls[n].id+'">'+empDtls[n].empname+'</option>';
			}
			str += '</select>';
			str += '</td>';
			str += '<td>';
			str += '<select class="form-control operationId_'+parseInt(rowNo)+'" style="width: 100%;" data-placeholder="Select">';
			str += '<option value=""></option>';
			for(var n=0; n<operationDtls.length; n++)
			{
				str += '<option value="'+operationDtls[n].id+'">'+operationDtls[n].operationname+'</option>';
			}
			str += '</select>';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric producedMin_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Target Minutes">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric pieces_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Target Pieces">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric sam_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="SAM">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric shiftHrs_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Actual Minutes">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric otHours_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="OT Hours">';
			str += '</td>';
			str += '<td>';
			str += '<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delEmpDtl"><i class="fa fa-close"></i></button>';
			str += '</td>';
			str += '</tr>';
			
			$(".empDetailsTBody").append(str);
			
			$(".numeric").numeric();
			$("select").select2();
		}
	}
	
	$(document).on('click','.delEmpDtl',function()
	{
		var bool = confirm("Are you sure want to Remove this Employee Detail?");
		if(bool == true)
		{
			deletedRowsCount++;
			$(this).closest('tr').remove();
		}
	});
	
	$("#skillMatrixForm").submit(function(e)
	{
		e.preventDefault();
		
		var skillMatrixId = '<?php echo $skillMatrixId; ?>';
		var entryDate = $("#entryDate").val();
		var shiftTime = $("#shiftTime").val();
		var lineName = $("#lineName").val();
		
		var dtlArr = [];
		
		var isError = false;
		
		$(".empDetailsTBody tr.empDetailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			var empId = $(".empId_"+rowNo).val();
			var operationId = $(".operationId_"+rowNo).val();
			var producedMin = $(".producedMin_"+rowNo).val();
			var pieces = $(".pieces_"+rowNo).val();
			var sam = $(".sam_"+rowNo).val();
			var shiftHrs = $(".shiftHrs_"+rowNo).val();
			var otHours = $(".otHours_"+rowNo).val();
			
			if(empId > 0 && operationId > 0 && producedMin > 0 && pieces > 0 && sam > 0 && shiftHrs > 0)
			{
				var cri = {};
				cri["empId"] = empId;
				cri["operationId"] = operationId;
				cri["producedMin"] = producedMin;
				cri["pieces"] = pieces;
				cri["sam"] = sam;
				cri["shiftHrs"] = shiftHrs;
				cri["otHours"] = otHours;
				
				dtlArr.push(cri);
			}
			else
			{
				isError = true;
			}
		});
		
		if(isError)
		{
			alert('Please Fill All Employee Details.');
			return;
		}
		
		if(entryDate != "" && shiftTime != "" && lineName != "" && dtlArr.length > 0)
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"skillMatrixId" : skillMatrixId,
					"shiftTime" : shiftTime,
					"entryDate" : entryDate,
					"lineName" : lineName,
					"dtlArr" : JSON.stringify(dtlArr)
				};
				req.url = "admin/saveSkillMatrix";
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
		var skillMatrixId = $(this).attr('skillMatrixId');
		if(skillMatrixId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/skillmatrix/'+skillMatrixId;
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
					"tableName" : "skillmatrix_hdr", 
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
			
			redirectURL = '<?php echo base_url(); ?>admin/skillmatrix';
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
		location.href = '<?php echo base_url(); ?>admin/skillmatrix/';
	});
	
</script>