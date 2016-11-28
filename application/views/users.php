<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/users" style="text-decoration: none;">
				Users
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($userId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">User Details</h4>
						<button class="btn btn-success pull-right newEntry">
							Create User
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
                        <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>User Email</th>
                                    <th>User Status</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								if(count($allUsers) > 0)
								{
									foreach($allUsers as $row)
									{
									?>
									<tr>
										<td><?php echo $row->firstname; ?></td>
										<td><?php echo $row->email; ?></td>
										<td><?php echo ucwords($row->status); ?></td>
										<td>
											<?php
											if($row->status != "inactive")
											{
											?>
											<button class="btn btn-primary btn-xs editUser" userId="<?php echo $row->userid; ?>">
												Edit
											</button>
											<button class="btn btn-danger btn-xs delUser" userId="<?php echo $row->userid; ?>">
												Inactivate
											</button>
											<?php
											}
											?>
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
	                    <h4 class="panel-title">User Creation Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="userForm" method="POST">
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									User Name&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="text" class="form-control" id="userName" name="userName" placeholder="User Name" value="<?php echo $userName; ?>" required="">
	                            </div>
	                        </div>
							<div class="form-group">
	                            <label class="col-sm-2 control-label">
									User Email&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="User Email" value="<?php echo $userEmail; ?>" required="">
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <div class="col-sm-offset-2 col-sm-10">
	                                <button type="submit" class="btn btn-success">
										<?php
										if($userId > 0)
										{
											echo 'Update User';
										}
										else
										{
											echo 'Create User';
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
	
	$("#userForm").submit(function(e)
	{
		e.preventDefault();
		
		var userId = '<?php echo $userId; ?>';	
		var userName = $("#userName").val();
		var userEmail = $("#userEmail").val();
		
		if(userName != "" && userEmail != "")
		{
			$("#responseMsg").html('');
			
			var req = new Request();
			req.data = 
			{
				"userId" : userId,
				"userName" : userName,
				"userEmail" : userEmail
			};
			req.url = "admin/saveUser";
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
	
	$(".resetBtn").click(function()
	{
		$("#responseMsg").html('');
		location.href = '<?php echo base_url(); ?>admin/users/';
	});
	
	$(".editUser").click(function()
	{
		var userId = $(this).attr('userId');
		
		if(userId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/users/'+userId;
		}
	});
	
	$(".delUser").on("click",function()
	{
		var userId = $(this).attr('userId');
		if(userId > 0)
		{
			var bool = confirm("Do you want to remove this User?");
			if(bool)
			{
				var req = new Request();
				req.data =
				{
					"userId" : userId
				};
				req.url = "admin/delUser";
				RequestHandler(req,showResponse);
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
			
			redirectURL = '<?php echo base_url(); ?>admin/users';
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