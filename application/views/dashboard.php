<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/" style="text-decoration: none;">
				Dashboard
				<?php
				if($this->session->userdata('usertype') != "admin")
				{
					echo ' - '.$this->session->userdata('sectionName').' Section';
				}
				?>
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php
		if($this->session->userdata('usertype') == "mechanic")
		{
		?>
		<div class="row">
			<div class="col-md-12">
	            <div class="col-lg-3 col-md-6">
	                <div class="panel info-box panel-white">
	                    <div class="panel-body">
	                        <div class="info-box-stats">
	                            <p class="counter">
	                            	<?php echo $todayOpenIssues; ?>
	                            </p>
	                            <span class="info-box-title">
	                            	Open Issues
	                            </span>
	                        </div>
	                        <div class="info-box-icon">
	                            <i class="icon-basket"></i>
	                        </div>
	                        <div class="info-box-progress">
	                            <div class="progress progress-xs progress-squared bs-n">
	                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-lg-3 col-md-6">
	                <div class="panel info-box panel-white">
	                    <div class="panel-body">
	                        <div class="info-box-stats">
	                            <p class="counter">
	                            	<?php echo $todayClosedIssues; ?>
	                            </p>
	                            <span class="info-box-title">
	                            	Closed Issues
	                            </span>
	                        </div>
	                        <div class="info-box-icon">
	                            <i class="icon-envelope"></i>
	                        </div>
	                        <div class="info-box-progress">
	                            <div class="progress progress-xs progress-squared bs-n">
	                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
            </div>
        </div>
        
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Issue Details</h4>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
                        <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                            <thead>
                                <tr>
                                    <th>Line Name</th>
                                    <th>Line Location</th>
                                    <th>Issue Type</th>
                                    <th>Table Assert Name</th>
                                    <th>Table Name</th>
                                    <th>Issue Started Time</th>
                                    <th>Issue Closed Time</th>
                                    <th>Issue Occured On</th>
                                    <th>Issue Status</th>
                                    <th>Assigned To</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
							if(count($issueDls) > 0)
							{
								foreach($issueDls as $row)
								{
									$bgColor = '';
									if($row->issuestatus == "Closed")
									{
										$bgColor = '#95f995';//Green
									}
									if($row->issuestatus == "Active")
									{
										$bgColor = '#ff9191';//Red
									}
								?>
								<tr style="background-color: <?php echo $bgColor; ?>;">
									<td><?php echo $row->lineid; ?></td>
									<td><?php echo $row->linelocation; ?></td>
									<td><?php echo ucwords($row->issuetype); ?></td>
									<td><?php echo $row->table_slno; ?></td>
									<td><?php echo $row->table_name; ?></td>
									<td><?php echo $row->in_time; ?></td>
									<td><?php echo $row->out_time; ?></td>
									<td><?php echo $row->createddt; ?></td>
									<td><?php echo $row->issuestatus; ?></td>
									<td><?php echo $row->assignedto; ?></td>
									<td>
										<button type="button" class="btn btn-success assignTo" entryId="<?php echo $row->id; ?>" assignedTo="<?php echo $row->assign_to; ?>">
											Assign To
										</button>
									</td>
								</tr>
								<?php
								}
							}
							else
							{
							?>
							<tr>
								<td colspan="8">
									No Issues Found.
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
		
		<div id="showAssignToModal" class="modal fade" tabindex="-1" aria-labelledby="assignToDetailsModal" aria-hidden="true" data-backdrop="static">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
		            <div class="modal-header">
		                <h4 id="assignToDetailsModal" class="modal-title">
							Assign To
						</h4>
		            </div>
		            <div class="modal-body">
		            	<div class="row">
			            	<div class="form-group">
			                    <label class="col-sm-2 control-label">
									User&nbsp;<span style="color: red;">*</span>
								</label>
			                    <div class="col-sm-8">
			                        <select class="form-control" id="assignTo" name="assignTo" style="width: 100%;" data-placeholder="Select" required="">
										<option value=""></option>
										<?php
										foreach($mechanicDtls as $res)
										{
											echo '<option value="'.$res->userid.'"';
											echo '>'.$res->firstname.' ('.$res->email.')'.'</option>';
										}
										?>
									</select>
									<input type="hidden" id="curIssueId" />
			                    </div>
			                </div>
		                </div>
		            </div>
		            <div class="modal-footer">
		            	<div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="button" class="btn btn-success updateAssignTo">
									Save
								</button>
								<button type="reset" class="btn btn-info" data-dismiss="modal">
									Reset
								</button>
                            </div>
                        </div>
		            </div>
		        </div>
		    </div>
		</div>
		<?php
		}
		?>
    </div>
</div>

<script>

$(document).ready(function()
{
	$("#example").dataTable();
	$('select').select2();
});

$(".assignTo").click(function()
{
	var entryId = $(this).attr('entryId');
	var assignedTo = $(this).attr('assignedTo');
	if(entryId > 0)
	{
		$("#curIssueId").val(entryId);
		if(assignedTo > 0)
		{
			$("#assignTo").select2('val', assignedTo);
		}
		else
		{
			$("#assignTo").select2('val', '');
		}
		$("#showAssignToModal").modal("show");
	}
});

$(".updateAssignTo").click(function()
{
	var assignTo = $("#assignTo").val();
	if(assignTo > 0)
	{
		$("#showAssignToModal").modal("hide");
		var req = new Request();
		req.data = 
		{
			"issueId" : $("#curIssueId").val(), 
			"assignTo" : assignTo
		};
		req.url = "admin/setIssueAssignedTo";
		RequestHandler(req, showResponse);
	}
	else
	{
		alert('Please assign this work to any mechanic.');
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
		
		redirectURL = '<?php echo base_url(); ?>admin';
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