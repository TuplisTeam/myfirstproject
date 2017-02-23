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
	                    	<div class="row">
	                    		<div class="form-group">
	                    			<div class="col-md-6">
	                    				<label class="col-sm-4 control-label">
											Buyer&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control" id="buyer" name="buyer" placeholder="Buyer" value="<?php echo $buyer; ?>" required="">
			                            </div>
	                    			</div>
	                    			<div class="col-md-6">
	                    				<label class="col-sm-4 control-label">
											Merchant&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control" id="merchant" name="merchant" placeholder="Merchant" value="<?php echo $merchant; ?>" required="">
			                            </div>
	                    			</div>
		                        </div>
	                    	</div>
							<div class="row">
								<div class="form-group">
		                            <div class="col-md-6">
		                            	<label class="col-sm-4 control-label">
											Style No.&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control" id="styleNo" name="styleNo" placeholder="Style No." value="<?php echo $styleNo; ?>" required="">
			                            </div>
		                            </div>
		                            <div class="col-md-6">
		                            	<label class="col-sm-4 control-label">
											Style Desc&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-8">
			                                <textarea class="form-control" id="styleDesc" name="styleDesc" placeholder="Style Desc"><?php echo $styleDesc; ?></textarea>
			                            </div>
		                            </div>
		                        </div>
	                        </div>
	                        <div class="row">
								<div class="form-group">
		                            <div class="col-md-6">
		                            	<label class="col-sm-4 control-label">
											Colour
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control" id="colour" name="colour" placeholder="Colour" value="<?php echo $colour; ?>" >
			                            </div>
		                            </div>
		                            <div class="col-md-6">
		                            	<label class="col-sm-4 control-label">
											Size
										</label>
			                            <div class="col-sm-8">
			                                <input type="text" class="form-control" id="size" name="size" placeholder="Size" value="<?php echo $size; ?>" >
			                            </div>
		                            </div>
		                        </div>
	                        </div>
	                        <div class="row">
		                        <div class="form-group">
		                        	<div class="col-md-6">
										<label class="col-sm-4 control-label">
											Style Image
										</label>
										<div class="col-sm-8">
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
								</div>
							</div>
							<div class="row">
	                    		<div class="form-group">
		                        	<div class="col-md-12">
		                        		<div class="table-responsive">
		                        			<div style="float: right;">
				                        		<button type="button" class="btn btn-success addOperationRow">
				                        		Add New Row <i class="fa fa-plus"></i>
				                        		</button>
			                        		</div>
			                        		<table class="table table-bordered">
				                        		<thead>
				                        			<tr>
				                        				<th>Operation Desc</th>
				                        				<th>Machine</th>
				                        				<th>SMV</th>
				                        				<th>Manage</th>
				                        			</tr>
				                        		</thead>
				                        		<tbody class="detailsTBody">
			                        			<?php
			                        			if($styleId > 0)
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
																<select class="form-control operationSelect operationId_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" style="width: 100%;" data-placeholder="Select">
																	<option value=""></option>
																	<?php
																	foreach($operations as $res)
																	{
																		echo '<option value="'.$res->id.'"';
																		if($row->operationid == $res->id)
																		{
																			echo ' selected="selected"';
																		}
																		echo '>'.$res->operationname.'</option>';
																	}
																	?>
																</select>
															</td>
															<td>
																<select class="form-control machinarySelect machinaryId_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" style="width: 100%;" data-placeholder="Select">
																	<option value=""></option>
																	<?php
																	foreach($machinaryRequirements as $res)
																	{
																		echo '<option value="'.$res->id.'"';
																		if($row->machineid == $res->id)
																		{
																			echo ' selected="selected"';
																		}
																		echo '>'.$res->machineryname.'</option>';
																	}
																	?>
																</select>
															</td>
															<td>
																<input type="text" class="form-control numeric smv_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="SMV" value="<?php echo $row->smv; ?>">
															</td>
															<td>
																<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delOperationDtl"><i class="fa fa-close"></i></button>
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
	                        </div>
	                    </form>
	                </div>
	            </div>
			</div>
        </div>
    </div>
</div>

<div id="operationsDiv" style="display: none;"><?php echo json_encode($operations); ?></div>
<div id="machinaryRequirementsDiv" style="display: none;"><?php echo json_encode($machinaryRequirements); ?></div>

<script>
	
	var deleted_RowsCount = 0;
	
	var operations = $("#operationsDiv").html();
	operations = JSON.parse(operations);
	
	var machinaryRequirements = $("#machinaryRequirementsDiv").html();
	machinaryRequirements = JSON.parse(machinaryRequirements);
	
	$(document).ready(function()
	{
		$('#example').dataTable();
		$('select').select2();
		$('.numeric').numeric();
		
		var styleId = '<?php echo $styleId; ?>';
		if(styleId == "")
		{
			addNewRow();
		}
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
	
	$(document).on('click','.addOperationRow',function()
	{
		addNewRow();
	});
	
	function addNewRow()
	{
		var rowNo = $(".detailsTBody tr").length;
		rowNo = parseInt(rowNo) + parseInt(deleted_RowsCount) + 1;
		
		if(rowNo > 0)
		{
			var str = '';
		
			str += '<tr class="detailsTR" rowNo="'+parseInt(rowNo)+'">';
			str += '<td>';
			str += '<select class="form-control operationSelect operationId_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" style="width: 100%;" data-placeholder="Select">';
			str += '<option value=""></option>';
			for(var k=0; k<operations.length; k++)
			{
				str += '<option value="'+operations[k].id+'"';
				str += '>'+operations[k].operationname+'</option>';
			}
			str += '</select>';
			str += '</td>';
			str += '<td>';
			str += '<select class="form-control machinarySelect machinaryId_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" style="width: 100%;" data-placeholder="Select">';
			str += '<option value=""></option>';
			for(var k=0; k<machinaryRequirements.length; k++)
			{
				str += '<option value="'+machinaryRequirements[k].id+'"';
				str += '>'+machinaryRequirements[k].machineryname+'</option>';
			}
			str += '</select>';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric smv_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="SMV" value="">';
			str += '</td>';
			str += '<td>';
			str += '<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delDtl"><i class="fa fa-close"></i></button>';
			str += '</td>';
			str += '</tr>';
			
			$(".detailsTBody").append(str);
			
			$("select").select2();
			$(".numeric").numeric();
		}
	}
	
	$(document).on('change','.operationSelect,.machinarySelect',function()
	{
		var rowNo = $(this).attr('rowNo');
		if(rowNo > 0)
		{
			var operationId = $(".operationId_"+rowNo).val();
			var machinaryId = $(".machinaryId_"+rowNo).val();
			
			if(operationId > 0 && machinaryId > 0)
			{
				var isError = false;
				
				$(".detailsTR").each(function()
				{
					var tempRowNo = $(this).attr('rowNo');
					var tempOperationId = $(".operationId_"+tempRowNo).val();
					var tempMachinaryId = $(".machinaryId_"+tempRowNo).val();
					
					if(rowNo != tempRowNo && operationId == tempOperationId && machinaryId == tempMachinaryId)
					{
						isError = true;
					}
				});
				
				if(isError)
				{
					alert('This Combination Is Already Available. Please Check.');
					$(".operationId_"+rowNo).select2('val','');
					$(".machinaryId_"+rowNo).select2('val','');
					return;
				}
			}
		}
	});
	
	$(document).on('click','.delDtl',function()
	{
		var bool = confirm("Are you sure want to Remove this Detail?");
		if(bool == true)
		{
			deleted_RowsCount++;
			$(this).closest('tr').remove();
		}
	});
	
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
		
		var dtlArr = [];
		
		var isError = false;
		
		$(".detailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			
			if(rowNo > 0)
			{
				var operationId = $(".operationId_"+rowNo).val();
				var machinaryId = $(".machinaryId_"+rowNo).val();
				var smv = $(".smv_"+rowNo).val();
				
				if(operationId > 0 && machinaryId > 0 && smv > 0)
				{
					var cri = {};
					cri["operationId"] = operationId;
					cri["machinaryId"] = machinaryId;
					cri["smv"] = smv;
					
					dtlArr.push(cri);
				}
				else
				{
					isError = true;
				}
			}
		});
		
		if(isError)
		{
			alert('Please Fill All Details.');
			return;
		}
		
		if(styleNo != "" && dtlArr.length > 0)
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
			data.append( "dtlArr", JSON.stringify(dtlArr));
			
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