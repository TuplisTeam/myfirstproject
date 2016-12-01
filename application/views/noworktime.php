<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/noworktime" style="text-decoration: none;">
				No Work Time Issues
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($noWorkId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">No Work Time Issue Details</h4>
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
										<td>
											<button class="btn btn-sm btn-success editEntry" entryId="<?php echo $row->id; ?>" title="Edit">
												<span class="fa fa-pencil"></span>
											</button>
											<button class="btn btn-sm btn-danger delEntry" entryId="<?php echo $row->id; ?>" title="Edit">
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
	                    <h4 class="panel-title">No Work Time Issue Form</h4>
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
				                        				<th width="15%">Reason</th>
				                        				<th width="15%">No Work Time</th>
				                        				<th width="10%">Manage</th>
				                        			</tr>
				                        		</thead>
				                        		<tbody class="detailsTBody">
			                        			<?php
			                        			if($noWorkId > 0)
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
																<input type="text" class="form-control reason_<?php echo $cnt; ?>" placeholder="Reason" value="<?php echo $row->reason; ?>">
															</td>
															<td>
																<input type="text" class="form-control noWorkTime_<?php echo $cnt; ?>" placeholder="No Work Time" value="<?php echo $row->noworktime; ?>">
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
	                    		<div class="col-md-12">
			                        <div class="form-group">
			                            <div class="col-sm-offset-4 col-sm-8">
			                                <button type="submit" class="btn btn-success">
												<?php
												if($noWorkId > 0)
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
		$('.datePicker').datepicker(
		{
			format: 'dd/mm/yyyy', 
			autoclose: true
		});
		
		var noWorkId = '<?php echo $noWorkId; ?>';
		if(noWorkId == "")
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
			str += '<input type="text" class="form-control reason_'+parseInt(rowNo)+'" placeholder="Reason" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control noWorkTime_'+parseInt(rowNo)+'" placeholder="No Work Time" value="">';
			str += '</td>';
			str += '<td>';
			str += '<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delDtl"><i class="fa fa-close"></i></button>';
			str += '</td>';
			str += '</tr>';
			
			$(".detailsTBody").append(str);
		}
	}
	
	$(document).on('click','.delDtl',function()
	{
		var bool = confirm("Are you sure want to Remove this No Work Detail?");
		if(bool == true)
		{
			deletedRowsCount++;
			$(this).closest('tr').remove();
		}
	});
	
	$("#entryForm").submit(function(e)
	{
		e.preventDefault();
		
		var noWorkId = '<?php echo $noWorkId; ?>';
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		
		var dtlArr = [];
		
		var isError = false;
		
		$(".detailsTBody tr.detailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			var reason = $(".reason_"+rowNo).val();
			var noWorkTime = $(".noWorkTime_"+rowNo).val();
			
			if(reason != "" && noWorkTime != "")
			{
				var cri = {};
				cri["reason"] = reason;
				cri["noWorkTime"] = noWorkTime;
				
				dtlArr.push(cri);
			}
			else
			{
				isError = true;
			}
		});
		
		if(isError)
		{
			alert('Please Fill All No Work Details.');
			return;
		}
		
		if(entryDate != "" && lineName != "" && dtlArr.length > 0)
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"noWorkId" : noWorkId,
					"entryDate" : entryDate,
					"lineName" : lineName,
					"dtlArr" : JSON.stringify(dtlArr)
				};
				req.url = "admin/saveNoWorkTime";
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
			location.href = '<?php echo base_url(); ?>admin/noworktime/'+entryId;
		}
	});
	
	$(".delEntry").on("click",function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			var bool = confirm("Do you want to remove this No Work Time Detail?");
			if(bool)
			{
				var req = new Request();
				req.data =
				{
					"noWorkId" : entryId
				};
				req.url = "admin/delNoWorkTime";
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
			
			redirectURL = '<?php echo base_url(); ?>admin/noworktime';
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
		location.href = '<?php echo base_url(); ?>admin/noworktime/';
	});
	
</script>