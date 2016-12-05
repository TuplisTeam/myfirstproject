<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/assemblyloading" style="text-decoration: none;">
				Assembly Loading
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($assemblyLoadingId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Assembly Loading Details</h4>
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
	                                    <th>Line Name</th>
	                                    <th>Shift Name</th>
	                                    <th>Total Workers</th>
	                                    <th>Total Pieces</th>
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
										<td><?php echo $row->linename; ?></td>
										<td><?php echo $row->shift; ?></td>
										<td><?php echo $row->totalworkers; ?></td>
										<td><?php echo $row->totalpieces; ?></td>
										<td>
											<button class="btn btn-sm btn-success editEntry" entryId="<?php echo $row->id; ?>" title="Edit">
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
	                    <h4 class="panel-title">Assembly Loading Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="entryForm" method="POST">
	                    	<div class="row">
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Entry Date&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-4">
			                                <input type="text" class="form-control datePicker" id="entryDate" name="entryDate" placeholder="Entry Date" value="<?php echo $entryDate; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
								<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Line Name&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-4">
			                                <input type="text" class="form-control" id="lineName" name="lineName" placeholder="Line Name" value="<?php echo $lineName; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Shift Name&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-4">
			                                <input type="text" class="form-control" id="shiftName" name="shiftName" placeholder="Shift Name" value="<?php echo $shiftName; ?>" required="">
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
				                        				<th width="20%">
				                        					Employee Name
				                        				</th>
				                        				<th>Target</th>
				                        				<th>1</th>
				                        				<th>2</th>
				                        				<th>3</th>
				                        				<th>4</th>
				                        				<th>5</th>
				                        				<th>6</th>
				                        				<th>7</th>
				                        				<th>8</th>
				                        				<th>OT</th>
				                        				<th>Total</th>
				                        				<th>Manage</th>
				                        			</tr>
				                        		</thead>
				                        		<tbody class="detailsTBody">
			                        			<?php
			                        			if($assemblyLoadingId > 0)
			                        			{
													if(count($dtlArr) > 0)
													{
														$cnt = 0;
														foreach($dtlArr as $row)
														{
															$cnt++;
														?>
														<tr class="detailsTR" rowNo="<?php echo $cnt; ?>">
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
																<input type="text" class="form-control numeric selectText target_<?php echo $cnt; ?>" placeholder="Target" value="<?php echo $row->target; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText hour1_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Hour 1" value="<?php echo $row->hour_1; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText hour2_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Hour 2" value="<?php echo $row->hour_2; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText hour3_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Hour 3" value="<?php echo $row->hour_3; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText hour4_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Hour 4" value="<?php echo $row->hour_4; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText hour5_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Hour 5" value="<?php echo $row->hour_5; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText hour6_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Hour 6" value="<?php echo $row->hour_6; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText hour7_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Hour 7" value="<?php echo $row->hour_7; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText hour8_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Hour 8" value="<?php echo $row->hour_8; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurPieces selectText otPieces_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="OT" value="<?php echo $row->ot_pieces; ?>">
															</td>
															<td>
																<input type="text" class="form-control numeric totalPieces_<?php echo $cnt; ?>" placeholder="Total Pieces" disabled="" value="<?php echo $row->totalpieces; ?>">
															</td>
															<td>
																<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delDtl"><i class="fa fa-close"></i></button>
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
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Total Workers&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-4">
			                                <input type="text" class="form-control" id="totalWorkers" name="totalWorkers" placeholder="Total Workers" value="<?php echo $totalWorkers; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
								<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Total Pieces&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-4">
			                                <input type="text" class="form-control" id="totalPieces" name="totalPieces" placeholder="Total Pieces" value="<?php echo $totalPieces; ?>" disabled="">
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
												if($assemblyLoadingId > 0)
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
	
	var empDtls = '<?php echo json_encode($empDtls); ?>';
	empDtls = JSON.parse(empDtls);
	
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
		
		var assemblyLoadingId = '<?php echo $assemblyLoadingId; ?>';
		if(assemblyLoadingId == "")
		{
			addNewRow();
		}
		else
		{
			var myDtlArr = '<?php echo json_encode($dtlArr); ?>';
			myDtlArr = JSON.parse(myDtlArr);
			
			if(myDtlArr.length > 0)
			{
				for(var k=0; k<myDtlArr.length; k++)
				{
					$(".empId_"+(k+1)).select2('val',myDtlArr[k].empid);
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
		var rowNo = $(".detailsTBody tr").length;
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
			
			str += '<tr class="detailsTR" rowNo="'+parseInt(rowNo)+'">';
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
			str += '<input type="text" class="form-control numeric selectText target_'+parseInt(rowNo)+'" placeholder="Target" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText hour1_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Hour 1" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText hour2_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Hour 2" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText hour3_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Hour 3" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText hour4_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Hour 4" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText hour5_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Hour 5" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText hour6_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Hour 6" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText hour7_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Hour 7" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText hour8_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Hour 8" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurPieces selectText otPieces_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="OT" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric totalPieces_'+parseInt(rowNo)+'" placeholder="Total Pieces" disabled="" value="">';
			str += '</td>';
			str += '<td>';
			str += '<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delDtl"><i class="fa fa-close"></i></button>';
			str += '</td>';
			str += '</tr>';
			
			$(".detailsTBody").append(str);
			
			$(".numeric").numeric();
			$("select").select2();
		}
	}
	
	$(document).on('click','.selectText',function()
	{
		$(this).select();
	});
	
	$(document).on('blur','.onBlurPieces',function()
	{
		var rowNo = $(this).attr('rowNo');
		if(rowNo > 0)
		{
			var hour1 = $(".hour1_"+rowNo).val();
			var hour2 = $(".hour2_"+rowNo).val();
			var hour3 = $(".hour3_"+rowNo).val();
			var hour4 = $(".hour4_"+rowNo).val();
			var hour5 = $(".hour5_"+rowNo).val();
			var hour6 = $(".hour6_"+rowNo).val();
			var hour7 = $(".hour7_"+rowNo).val();
			var hour8 = $(".hour8_"+rowNo).val();
			var otPieces = $(".otPieces_"+rowNo).val();
			
			var totalPieces = parseFloat(hour1 ? hour1 : 0) + 
								parseFloat(hour2 ? hour2 : 0) + 
								parseFloat(hour3 ? hour3 : 0) + 
								parseFloat(hour4 ? hour4 : 0) + 
								parseFloat(hour5 ? hour5 : 0) + 
								parseFloat(hour6 ? hour6 : 0) + 
								parseFloat(hour7 ? hour7 : 0) + 
								parseFloat(hour8 ? hour8 : 0) + 
								parseFloat(otPieces ? otPieces : 0) ;
			$(".totalPieces_"+rowNo).val(totalPieces);
			
			calculateConsolidatedCounts();
		}
	});
	
	function calculateConsolidatedCounts()
	{
		var totalPieces = 0;
		var totalWorkers = 0;
		$("tr.detailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			if(rowNo > 0)
			{
				var totalPiecesTR = $(".totalPieces_"+rowNo).val();
				totalPieces += parseFloat(totalPiecesTR);
				totalWorkers++;
			}
		});
		
		$("#totalWorkers").val(totalWorkers);
		$("#totalPieces").val(totalPieces);
	}
	
	$(document).on('click','.delDtl',function()
	{
		var bool = confirm("Are you sure want to Remove this Employee Detail?");
		if(bool == true)
		{
			deletedRowsCount++;
			$(this).closest('tr').remove();
		}
	});
	
	$("#entryForm").submit(function(e)
	{
		e.preventDefault();
		
		var assemblyLoadingId = '<?php echo $assemblyLoadingId; ?>';
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		var shiftName = $("#shiftName").val();
		var totalWorkers = $("#totalWorkers").val();
		var totalPieces = $("#totalPieces").val();
		var totalTarget = 0;
		
		var dtlArr = [];
		
		var isError = false;
		
		$(".detailsTBody tr.detailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			var empId = $(".empId_"+rowNo).val();
			var target = $(".target_"+rowNo).val();
			var hour1 = $(".hour1_"+rowNo).val();
			var hour2 = $(".hour2_"+rowNo).val();
			var hour3 = $(".hour3_"+rowNo).val();
			var hour4 = $(".hour4_"+rowNo).val();
			var hour5 = $(".hour5_"+rowNo).val();
			var hour6 = $(".hour6_"+rowNo).val();
			var hour7 = $(".hour7_"+rowNo).val();
			var hour8 = $(".hour8_"+rowNo).val();
			var otPieces = $(".otPieces_"+rowNo).val();
			var totalPiecesTR = $(".totalPieces_"+rowNo).val();
			
			if(empId > 0 && target > 0 && (hour1 > 0 || hour2 > 0 || hour3 > 0 || hour4 > 0 || hour5 > 0 || hour6 > 0 || hour7 > 0 || hour8 > 0 || otPieces > 0) && totalPiecesTR > 0)
			{
				var cri = {};
				cri["empId"] = empId;
				cri["target"] = target;
				cri["hour1"] = hour1;
				cri["hour2"] = hour2;
				cri["hour3"] = hour3;
				cri["hour4"] = hour4;
				cri["hour5"] = hour5;
				cri["hour6"] = hour6;
				cri["hour7"] = hour7;
				cri["hour8"] = hour8;
				cri["otPieces"] = otPieces;
				cri["totalPieces"] = totalPiecesTR;
				
				totalTarget += parseFloat(target);
				
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
		
		if(entryDate != "" && lineName != "" && totalWorkers > 0 && totalPieces > 0 && dtlArr.length > 0)
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"assemblyLoadingId" : assemblyLoadingId,
					"entryDate" : entryDate,
					"lineName" : lineName,
					"shiftName" : shiftName, 
					"totalWorkers" : totalWorkers, 
					"totalPieces" : totalPieces, 
					"totalTarget" : totalTarget, 
					"dtlArr" : JSON.stringify(dtlArr)
				};
				req.url = "admin/saveAssemblyLoading";
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
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/assemblyloading/'+entryId;
		}
	});
	
	$(".delEntry").on("click",function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			var bool = confirm("Do you want to remove this Assembly Loading Detail?");
			if(bool)
			{
				var req = new Request();
				req.data =
				{
					"entryId" : entryId
				};
				req.url = "admin/delAssemblyLoading";
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
			
			redirectURL = '<?php echo base_url(); ?>admin/assemblyloading';
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
		location.href = '<?php echo base_url(); ?>admin/assemblyloading/';
	});
	
</script>