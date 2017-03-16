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
	                                    <th>Line Incharge</th>
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
										<td><?php echo $row->shiftname; ?></td>
										<td><?php echo $row->lineinchargename; ?></td>
										<td><?php echo $row->totalpieces; ?></td>
										<td>
											<button class="btn btn-sm btn-success editEntry" entryId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-pencil"></span>
											</button>
											<button class="btn btn-sm btn-danger delEntry" entryId="<?php echo $row->id; ?>" title="Delete">
												<span class="fa fa-close"></span>
											</button>
											<button class="btn btn-sm btn-info printEntry" entryId="<?php echo $row->id; ?>" title="Print">
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
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control datePicker onBlurEntryDateLineName" id="entryDate" name="entryDate" placeholder="Entry Date" value="<?php echo $entryDate; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
								<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Line Name&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control onBlurEntryDateLineName" id="lineName" name="lineName" placeholder="Line Name" value="<?php echo $lineName; ?>" required="">
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
			                            <div class="col-sm-7">
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
											Line Incharge&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-7">
			                                <select class="form-control" id="lineIncharge" name="lineIncharge" style="width: 100%;" data-placeholder="Select" required="">
												<option value=""></option>
												<?php
												foreach($empDtls as $res)
												{
													echo '<option value="'.$res->id.'"';
													if($res->id == $lineIncharge)
													{
														echo ' selected="selected"';
													}
													echo '>'.$res->empname.'</option>';
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
											Hour 1
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="hour1" name="hour1" placeholder="Hour 1" value="<?php echo $hour1; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Hour 2
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="hour2" name="hour2" placeholder="Hour 2" value="<?php echo $hour2; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
							<div class="row">
								<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Hour 3
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="hour3" name="hour3" placeholder="Hour 3" value="<?php echo $hour3; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Hour 4
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="hour4" name="hour4" placeholder="Hour 4" value="<?php echo $hour4; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
							<div class="row">
								<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Hour 5
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="hour5" name="hour5" placeholder="Hour 5" value="<?php echo $hour5; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Hour 6
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="hour6" name="hour6" placeholder="Hour 6" value="<?php echo $hour6; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
							<div class="row">
								<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Hour 7
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="hour7" name="hour7" placeholder="Hour 7" value="<?php echo $hour7; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Hour 8
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="hour8" name="hour8" placeholder="Hour 8" value="<?php echo $hour8; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
							<div class="row">
								<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											OT Hour
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="otHour" name="otHour" placeholder="OT Hour" value="<?php echo $otHour; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Total Pieces
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="totalPieces" name="totalPieces" placeholder="Total Pieces" value="<?php echo $totalPieces; ?>" disabled="">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
							<div class="row">
								<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Target&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-7">
			                                <input type="text" class="form-control numeric" id="target" name="target" placeholder="Target" value="<?php echo $target; ?>" required="">
			                            </div>
			                        </div>
	                    		</div>
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Is Target Achieved
										</label>
			                            <div class="col-sm-7">
			                                <select class="form-control" id="isTargetAchieved" style="width: 100%;" data-placeholder="Select" disabled="">
												<option value="No" <?php
												if($isTargetAchieved == "No")
												{
													echo ' selected="selected"';
												}
												?>>No</option>
												<option value="Yes" <?php
												if($isTargetAchieved == "Yes")
												{
													echo ' selected="selected"';
												}
												?>>Yes</option>
											</select>
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
	
	$(document).on('blur','.onBlurEntryDateLineName',function()
	{
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		var shiftId = $("#shiftId").val();
		
		getPiecelogDetails(entryDate, lineName, shiftId);
	});
	
	$(document).on('change','#shiftId',function()
	{
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		var shiftId = $("#shiftId").val();
		
		getPiecelogDetails(entryDate, lineName, shiftId);
	});
	
	function getPiecelogDetails(entryDate = '', lineName = '', shiftId = '')
	{
		if(entryDate != "" && lineName != "" && shiftId > 0)
		{
			var req = new Request();
			req.data = 
			{
				"entryDate" : entryDate, 
				"lineName" : lineName, 
				"shiftId" : shiftId
			};
			req.url = "admin/getPieceLogsDetailsByDateLine";
			RequestHandler(req, setPieceLogs);
		}
		else
		{
			return;
		}
	}
	
	function setPieceLogs(data)
	{
		data = JSON.parse(data);
		
		var isError = data.isError;
		var msg = data.msg;
		if(isError == false)
		{
			var lineInchargeDtls = data.lineInchargeDtls;
			var pieceLogDtls = data.pieceLogDtls;
			if(lineInchargeDtls.length > 0)
			{
				var tempArr = [];
				var cri = [];
				cri["id"] = "";
				cri["text"] = "";
				tempArr.push(cri);
				for(var a=0; a<lineInchargeDtls.length; a++)
				{
					var cri = {};
					cri["id"] = lineInchargeDtls[a].empid;
					cri["text"] = lineInchargeDtls[a].empname;
					tempArr.push(cri);
				}
				$("#lineIncharge").select2('destroy').empty().select2({data:tempArr});
			}
			if(pieceLogDtls.length > 0)
			{
				var hour1 = pieceLogDtls[0].Nos, 
					hour2 = pieceLogDtls[1].Nos, 
					hour3 = pieceLogDtls[2].Nos, 
					hour4 = pieceLogDtls[3].Nos, 
					hour5 = pieceLogDtls[4].Nos, 
					hour6 = pieceLogDtls[5].Nos, 
					hour7 = pieceLogDtls[6].Nos, 
					hour8 = pieceLogDtls[7].Nos,  
					otHour = 0;
				if(pieceLogDtls.length = 9)
				{
					otHour = pieceLogDtls[8].Nos;
				}
				$("#hour1").val(hour1);
				$("#hour2").val(hour2);
				$("#hour3").val(hour3);
				$("#hour4").val(hour4);
				$("#hour5").val(hour5);
				$("#hour6").val(hour6);
				$("#hour7").val(hour7);
				$("#hour8").val(hour8);
				$("#otHour").val(otHour);
				
				var totalPieces = parseFloat(hour1 ? hour1 : 0) + 
							parseFloat(hour2 ? hour2 : 0) + 
							parseFloat(hour3 ? hour3 : 0) + 
							parseFloat(hour4 ? hour4 : 0) + 
							parseFloat(hour5 ? hour5 : 0) + 
							parseFloat(hour6 ? hour6 : 0) + 
							parseFloat(hour7 ? hour7 : 0) + 
							parseFloat(hour8 ? hour8 : 0) + 
							parseFloat(otHour ? otHour : 0);
				$("#totalPieces").val(parseInt(totalPieces ? totalPieces : 0));
			}
		}
	}
	
	$("#target").blur(function()
	{
		var target = $(this).val();
		var totalPieces = $("#totalPieces").val();
		var isTargetAchieved = 'Yes';
		if(parseFloat(target) > parseFloat(totalPieces))
		{
			isTargetAchieved = 'No';
		}
		$("#isTargetAchieved").select2('val', isTargetAchieved);
	});
	
	$("#entryForm").submit(function(e)
	{
		e.preventDefault();
		
		var assemblyLoadingId = '<?php echo $assemblyLoadingId; ?>';
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		var shiftId = $("#shiftId").val();
		var lineIncharge = $("#lineIncharge").val();
		var hour1 = $("#hour1").val();
		var hour2 = $("#hour2").val();
		var hour3 = $("#hour3").val();
		var hour4 = $("#hour4").val();
		var hour5 = $("#hour5").val();
		var hour6 = $("#hour6").val();
		var hour7 = $("#hour7").val();
		var hour8 = $("#hour8").val();
		var otHour = $("#otHour").val();
		var totalPieces = $("#totalPieces").val();
		var target = $("#target").val();
		var isTargetAchieved = $("#isTargetAchieved").val();
		
		if(entryDate != "" && lineName != "" && shiftId > 0 && lineIncharge > 0 && parseFloat(totalPieces) > 0 && parseFloat(target) > 0)
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"assemblyLoadingId" : assemblyLoadingId,
					"entryDate" : entryDate,
					"lineName" : lineName,
					"shiftId" : shiftId, 
					"lineIncharge" : lineIncharge, 
					"hour1" : hour1, 
					"hour2" : hour2, 
					"hour3" : hour3, 
					"hour4" : hour4, 
					"hour5" : hour5, 
					"hour6" : hour6, 
					"hour7" : hour7, 
					"hour8" : hour8, 
					"otHour" : otHour, 
					"totalPieces" : totalPieces, 
					"target" : target, 
					"isTargetAchieved" : isTargetAchieved
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
			var bool = confirm("Are You Sure Want To Remove This Entry?");
			if(bool)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"entryId" : entryId, 
					"tableName" : "assemblyloading", 
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
	
	$(".printEntry").on("click",function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			window.open('<?php echo base_url(); ?>admin/assemblyloadingprint/'+entryId);
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