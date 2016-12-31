<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/style" style="text-decoration: none;">
				Style
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($styleId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Style Details</h4>
                        <button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
                        <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                            <thead>
                                <tr>
                                    <th>Buyer</th>
                                    <th>Merchant</th>
                                    <th>Style No.</th>
                                    <th>Style Desc</th>
                                    <th>Colour</th>
                                    <th>Size</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
							if(count($allStyles) > 0)
							{
								foreach($allStyles as $row)
								{
								?>
								<tr>
									<td><?php echo $row->buyer; ?></td>
									<td><?php echo $row->merchant; ?></td>
									<td><?php echo $row->styleno; ?></td>
									<td><?php echo $row->styledesc; ?></td>
									<td><?php echo $row->colour; ?></td>
									<td><?php echo $row->size; ?></td>
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
	                    <h4 class="panel-title">Style Creation Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="entryForm" method="POST">
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Buyer&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="buyer" name="buyer" placeholder="Buyer" value="<?php echo $buyer; ?>" required="">
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Merchant&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="merchant" name="merchant" placeholder="Merchant" value="<?php echo $merchant; ?>" required="">
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Style No.&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="styleNo" name="styleNo" placeholder="Style No." value="<?php echo $styleNo; ?>" required="">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">
									Style Desc
								</label>
	                            <div class="col-sm-6">
	                                <textarea class="form-control" id="styleDesc" name="styleDesc" placeholder="Style Desc"><?php echo $styleDesc; ?></textarea>
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Colour
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="colour" name="colour" placeholder="Colour" value="<?php echo $colour; ?>" >
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									Size
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="size" name="size" placeholder="Size" value="<?php echo $size; ?>" >
	                            </div>
	                        </div>
	                        <div class="form-group">
								<label class="col-sm-2 control-label">
									Style Image
								</label>
								<div class="col-sm-6">
									<input type="file" id="styleImage" name="styleImage" value="<?php echo $styleImage; ?>">
									<textarea id="styleImagePath" name="styleImagePath" style="display: none;"><?php echo $styleImage; ?></textarea>
									<div id="styleImagePreview">
		                            	<?php
		                            	if($styleImage != "")
										{
											echo "<img src=".base_url().$styleImage." height='100px' />";
										}
		                            	?>
		                            </div>
								</div>
							</div>
	                        <div class="form-group">
	                            <div class="col-sm-offset-2 col-sm-10">
	                                <button type="submit" class="btn btn-success">
										<?php
										if($styleId > 0)
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
	
	$("#styleImage").change(function()
	{
		readURL(this);
	});
	
	function readURL(input)
	{
		if (input.files && input.files[0]) 
		{
			var reader = new FileReader();
			reader.onload = function (e) 
			{
				var str = "<img src='"+e.target.result+"' height='100px' />";
				$("#styleImagePreview").html(str);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#entryForm").submit(function(e)
	{
		e.preventDefault();
		
		var styleId = '<?php echo $styleId; ?>';
		
		var buyer = $("#buyer").val();
		var merchant = $("#merchant").val();
		var styleNo = $("#styleNo").val();
		var styleDesc = $("#styleDesc").val();
		var colour = $("#colour").val();
		var size = $("#size").val();
		var oldStyleImagePath = $("#styleImagePath").val();
		
		if(styleNo != "")
		{
			$("#responseMsg").html('');
			
			var data = new FormData();
			
			var filesList =  document.getElementById('styleImage');
			for (var ie = 0; ie< filesList.files.length; ie ++) 
			{
				data.append('styleImage', filesList.files[ie]);
			}
			
			data.append( "menuId", '<?php echo $menuId; ?>');
			data.append( "styleId", styleId);
			data.append( "buyer", buyer);
			data.append( "merchant", merchant);
			data.append( "styleNo", styleNo);
			data.append( "styleDesc", styleDesc);
			data.append( "colour", colour);
			data.append( "size", size);
			data.append( "oldStyleImagePath", oldStyleImagePath);
			
			var req = new Request();
			req.data = data;
			req.url = "admin/saveStyle";
			ImageRequestHandler(req, showResponse);
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
			location.href = '<?php echo base_url(); ?>admin/style/'+entryId;
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
					"tableName" : "style", 
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
			
			redirectURL = '<?php echo base_url(); ?>admin/style';
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
		location.href = '<?php echo base_url(); ?>admin/style/';
	});
	
</script>