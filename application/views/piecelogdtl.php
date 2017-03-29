<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/line_vs_style" style="text-decoration: none;">
				Piece Log Details
			</a>
		</h3>
    </div>
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

<script>
	
	$(document).ready(function()
	{
		$('#example1').dataTable();
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
	
	$("#hangerForm").submit(function(e)
	{
		e.preventDefault();
		
		var entryId = '<?php echo $entryId; ?>';
		
		var entryDate = $("#entryDate").val();
		var lineName = $("#lineName").val();
		var styleId = $("#styleId").val();
		
		if(entryDate != "" && lineName != "" && styleId > 0)
		{
			$("#responseMsg").html('');
			
			var req = new Request();
			req.data = 
			{
				"menuId" : '<?php echo $menuId; ?>', 
				"entryId" : entryId,
				"entryDate" : entryDate,
				"lineName" : lineName, 
				"styleId" : styleId
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