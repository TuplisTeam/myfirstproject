<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/line_vs_style" style="text-decoration: none;">
				Piece Log Details
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<div class="row" id="listDetails">
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
                                    <th>Table Name</th>
                                    <th>Hanger Name</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Time Taken</th>
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
									<td><?php echo $row->linename; ?></td>
									<td><?php echo $row->styleno; ?></td>
									<td><?php echo $row->tablename; ?></td>
									<td><?php echo $row->hanger_name; ?></td>
									<td><?php echo $row->in_time; ?></td>
									<td><?php echo $row->out_time; ?></td>
									<td><?php echo $row->timetaken; ?></td>
									<td>
										<button class="btn btn-primary btn-xs editStyle" entryId="<?php echo $row->id; ?>" pieceLogId="<?php echo $row->piecelog_id; ?>">
											Edit Style
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
    </div>
</div>

<div id="showLineStyletModal" class="modal fade" tabindex="-1" aria-labelledby="selectLineStyle" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
            <div class="modal-header">
                <h4 id="selectLineStyle" class="modal-title">
					Change Line Style
				</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<div class="col-sm-12">
	            		<div class="form-group">
	            			<label class="col-sm-2 control-label">
								Style&nbsp;<span style="color: red;">*</span>
							</label>
	                        <div class="col-sm-10">
	                        	<input type="hidden" id="pieceLogDtlId" name="pieceLogDtlId" />
	                            <select class="form-control" style="width: 100%;" data-placeholder="Select" id="styleId" name="styleId">
									<option value=""></option>
									<?php
									foreach($styleDtls as $res)
									{
										echo '<option value="'.$res->id.'"';
										if($styleId == $res->id)
										{
											echo ' selected="selected"';
										}
										echo '>'.$res->styleno.'</option>';
									}
									?>
								</select>
	                        </div>
	            		</div>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success doneBtn">
                	Done
                </button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                	Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
	
	$(document).ready(function()
	{
		$('#example1').dataTable();
		$('select').select2();
	});
	
	$(".editStyle").click(function()
	{
		var entryId = $(this).attr('entryId');
		var pieceLogId = $(this).attr('pieceLogId');
		if(entryId > 0 && pieceLogId > 0)
		{
			$("#pieceLogDtlId").val(entryId);
			$("#showLineStyletModal").modal('show');
		}	
	});
	
	$(".doneBtn").on("click",function()
	{
		var styleId = $("#styleId").val();
		var pieceLogDtlId = $("#pieceLogDtlId").val();
		if(pieceLogDtlId > 0 && styleId > 0)
		{
			var bool = confirm("Are You Sure Want To Update This Entry?");
			if(bool)
			{
				var req = new Request();
				req.data = 
				{
					"pieceLogId" : "<?php echo $pieceLogId; ?>", 
					"pieceLogDtlId" : pieceLogDtlId, 
					"styleId" : styleId
				};
				req.url = "admin/updatePieceLogDtlId";
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
		
		$("#showLineStyletModal").modal('hide');
		
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
			
			redirectURL = '<?php echo base_url(); ?>admin/piecelogdtl/<?php echo $pieceLogId; ?>';
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
	
</script>