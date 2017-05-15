<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/line_vs_style" style="text-decoration: none;">
				Line Vs Style
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
                        <h4 class="panel-title">Line Vs Style Details</h4>
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
                                    <th>Line Location</th>
                                    <th>OB Name</th>
                                    <th>Style Name</th>
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
									<td><?php echo $row->entrydt; ?></td>
									<td><?php echo $row->line_name; ?></td>
									<td><?php echo $row->line_location; ?></td>
									<td><?php echo $row->obname; ?></td>
									<td><?php echo $row->styleno; ?></td>
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
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">PieceLog Details</h4>
                    </div>
                    <div class="panel-body">
						<div class="table-responsive">
                        <table id="example1" class="display table" style="width: 100%; cellspacing: 0;">
                            <thead>
                                <tr>
                                    <th>Entry Date</th>
                                    <th>Line Name</th>
                                    <th>Style Name</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
							if(count($pieceLogDtls) > 0)
							{
								foreach($pieceLogDtls as $row)
								{
								?>
								<tr>
									<td><?php echo $row->createddt; ?></td>
									<td><?php echo $row->lineid; ?></td>
									<td><?php echo $row->styleno; ?></td>
									<td>
										<button class="btn btn-primary btn-xs viewPieceLogDetails" entryId="<?php echo $row->id; ?>">
											View
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
	                    <h4 class="panel-title">Line Vs Style Creation Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="hangerForm" method="POST">
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
									Line Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="lineName" name="lineName" placeholder="Line Name" value="<?php echo $lineName; ?>" required="">
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Line Location&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="linelocation" name="linelocation" placeholder="Line Location" value="<?php echo $lineLocation; ?>" required="">
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									In Table&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="inTable" name="inTable" placeholder="In Table" value="<?php echo $inTable; ?>" required="">
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Out Table&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="outTable" name="outTable" placeholder="Out Table" value="<?php echo $outTable; ?>" required="">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">
									Style&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <select class="form-control" style="width: 100%;" data-placeholder="Select" id="obId" name="obId" required="">
										<option value=""></option>
										<?php
										foreach($obDtls as $res)
										{
											echo '<option value="'.$res->id.'"';
											if($res->id == $obId)
											{
												echo ' selected="selected"';
											}
											echo '>'.$res->obname.' - '.$res->styleno.'</option>';
										}
										?>
									</select>
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
		$('#example1').dataTable();
		$('.datePicker').datepicker(
		{
			format: 'dd/mm/yyyy', 
			autoclose: true
		});
		$('select').select2();
	});
	
	$(".viewPieceLogDetails").click(function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			window.open('<?php echo base_url(); ?>admin/piecelogdtl/'+entryId);
		}	
	});
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$("#hangerForm").submit(function(e)
	{
		e.preventDefault();
		
		var entryId = '<?php echo $entryId; ?>';
		
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		var linelocation = $("#linelocation").val();
		var inTable = $("#inTable").val();
		var outTable = $("#outTable").val();
		var obId = $("#obId").val();
		
		if(entryDate != "" && lineName != "" && linelocation != "" && inTable != "" && outTable != "" && obId > 0)
		{
			$("#responseMsg").html('');
			
			var req = new Request();
			req.data = 
			{
				"menuId" : '<?php echo $menuId; ?>', 
				"entryId" : entryId,
				"entryDate" : entryDate,
				"lineName" : lineName, 
				"linelocation" : linelocation, 
				"inTable" : inTable, 
				"outTable" : outTable, 
				"obId" : obId
			};
			req.url = "admin/saveLineVsStyle";
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
			location.href = '<?php echo base_url(); ?>admin/line_vs_style/'+entryId;
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
					"tableName" : "line_vs_style", 
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
			
			redirectURL = '<?php echo base_url(); ?>admin/line_vs_style';
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
		location.href = '<?php echo base_url(); ?>admin/line_vs_style/';
	});
	
</script>