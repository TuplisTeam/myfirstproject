<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/table" style="text-decoration: none;">
				Table
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
                        <h4 class="panel-title">Table Details</h4>
                        <button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
                        <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                            <thead>
                                <tr>
                                    <th>Table Sl.No</th>
                                    <th>Assert Name</th>
                                    <th>Table Name</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
							if(count($allTables) > 0)
							{
								foreach($allTables as $row)
								{
								?>
								<tr>
									<td><?php echo $row->id; ?></td>
									<td><?php echo $row->table_slno; ?></td>
									<td><?php echo $row->table_name; ?></td>
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
	                    <h4 class="panel-title">Table Creation Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="tableForm" method="POST">
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Table Sl.No
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="tableSlNo" name="tableSlNo" placeholder="Table Sl.No" value="<?php echo $entryId; ?>" disabled="">
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Assert Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="assertName" name="assertName" placeholder="Assert Name" value="<?php echo $assertName; ?>" required="">
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
	});
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$("#tableForm").submit(function(e)
	{
		e.preventDefault();
		
		var entryId = '<?php echo $entryId; ?>';
		
		var assertName = $("#assertName").val();
		var tableName = $("#tableName").val();
		
		if(assertName != "" && tableName != "")
		{
			$("#responseMsg").html('');
			
			var req = new Request();
			req.data = 
			{
				"menuId" : '<?php echo $menuId; ?>', 
				"entryId" : entryId,
				"assertName" : assertName, 
				"tableName" : tableName
			};
			req.url = "admin/saveTable";
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
			location.href = '<?php echo base_url(); ?>admin/table/'+entryId;
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
					"tableName" : "tables", 
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
			
			redirectURL = '<?php echo base_url(); ?>admin/table';
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
		location.href = '<?php echo base_url(); ?>admin/table/';
	});
	
</script>