<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/hanger" style="text-decoration: none;">
				Hanger
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($hangerId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Hanger Details</h4>
                        <button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
                        <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                            <thead>
                                <tr>
                                    <th>Hanger Sl.No</th>
                                    <th>Assert Name</th>
                                    <th>Hanger RFID</th>
                                    <th>Hanger Name</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
							if(count($allHangers) > 0)
							{
								foreach($allHangers as $row)
								{
								?>
								<tr>
									<td><?php echo $row->id; ?></td>
									<td><?php echo $row->assert_name; ?></td>
									<td><?php echo $row->hanger_slno; ?></td>
									<td><?php echo $row->hanger_name; ?></td>
									<td>
										<button class="btn btn-primary btn-xs editEntry" hangerId="<?php echo $row->id; ?>">
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
	                    <h4 class="panel-title">Hanger Creation Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="hangerForm" method="POST">
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Hanger Sl.No
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="hangerSlNo" name="hangerSlNo" placeholder="Hanger Sl.No" value="<?php echo $hangerId; ?>" disabled="">
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
									Hanger RFID&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="hangerRFID" name="hangerRFID" placeholder="Hanger RFID" value="<?php echo $hangerRFID; ?>" required="">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">
									Hanger Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="hangerName" name="hangerName" placeholder="Hanger Name" value="<?php echo $hangerName; ?>" required="">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <div class="col-sm-offset-2 col-sm-10">
	                                <button type="submit" class="btn btn-success">
										<?php
										if($hangerId > 0)
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
	
	$("#hangerForm").submit(function(e)
	{
		e.preventDefault();
		
		var hangerId = '<?php echo $hangerId; ?>';
		
		var assertName = $("#assertName").val();
		var hangerRFID = $("#hangerRFID").val();
		var hangerName = $("#hangerName").val();
		
		if(assertName != "" && hangerRFID != "" && hangerName != "")
		{
			$("#responseMsg").html('');
			
			var req = new Request();
			req.data = 
			{
				"menuId" : '<?php echo $menuId; ?>', 
				"hangerId" : hangerId,
				"assertName" : assertName,
				"hangerRFID" : hangerRFID, 
				"hangerName" : hangerName
			};
			req.url = "admin/saveHanger";
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
		var hangerId = $(this).attr('hangerId');
		if(hangerId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/hanger/'+hangerId;
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
					"tableName" : "hanger", 
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
			
			redirectURL = '<?php echo base_url(); ?>admin/hanger';
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
		location.href = '<?php echo base_url(); ?>admin/hanger/';
	});
	
</script>