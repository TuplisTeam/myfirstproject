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
                                    <th>User Type</th>
                                    <th>Created By</th>
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
										<td><?php echo ucwords($row->usertype); ?></td>
										<td><?php echo $row->createdby; ?></td>
										<td>
											<button class="btn btn-primary btn-xs editUser" userId="<?php echo $row->userid; ?>">
												Edit
											</button>
											<button class="btn btn-danger btn-xs delEntry" entryId="<?php echo $row->userid; ?>">
												Inactivate
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
	                            <label class="col-sm-2 control-label">
									User Type&nbsp;<span style="color: red;">*</span>
								</label>
	                            <div class="col-sm-6">
	                                <select class="form-control" style="width: 100%;" data-placeholder="Select" id="userType" name="userType" required="">
										<option value=""></option>
										<option value="user">User</option>
									</select>
	                            </div>
	                        </div>
	                        <div class="table-responsive">
	                        	<table class="table table-bordered table-striped">
	                                <thead>
		                                <tr>
		                                    <th>S.No</th>
											<th>Menu Name</th>
											<th>Select</th>
											<th>Save</th>
											<th>Edit</th>
											<th>Delete</th>
		                                </tr>
	                                </thead>
	                                <tbody>
	                                	<?php
	                                	if(count($menuDtls) > 0)
	                                	{
											foreach($menuDtls as $row)
											{
											?>
											<tr class="menuListsTR" menuId="<?php echo $row->menu_id; ?>">
												<td><?php echo $row->slno; ?></td>
												<td><?php echo $row->menu_name; ?></td>
												<td class="selectCheckTD parentMenuId_<?php echo $row->parent_id; ?>" menuId="<?php echo $row->menu_id; ?>">
													<input type="checkbox" id="check_<?php echo $row->menu_id; ?>" class="onCheckSelectField" isParent="<?php echo $row->is_parent; ?>" menuId="<?php echo $row->menu_id; ?>" />
												</td>
												<td>
													<?php
													if($row->is_parent == "no")
													{
													?>
													<input type="checkbox" id="saveAccess_<?php echo $row->menu_id; ?>" class="onCheckSaveField" menuId="<?php echo $row->menu_id; ?>" />
													<?php
													}
													?>
												</td>
												<td>
													<?php
													if($row->is_parent == "no")
													{
													?>
													<input type="checkbox" id="editAccess_<?php echo $row->menu_id; ?>" />
													<?php
													}
													?>
												</td>
												<td>
													<?php
													if($row->is_parent == "no")
													{
													?>
													<input type="checkbox" id="delAccess_<?php echo $row->menu_id; ?>" />
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
		$('select').select2();
		
		if('<?php echo $userId; ?>' > 0)
		{
			$("#userType").select2('val','<?php echo $userType; ?>');
			
			var userPermissions = '<?php echo json_encode($userPermissions); ?>';
			userPermissions = JSON.parse(userPermissions);
			
			setUserMenuPermissions(userPermissions);
		}
	});
	
	function setUserMenuPermissions(res)
	{
		if(res.length > 0)
		{
			for(var n=0; n<res.length; n++)
			{
				var menuId = res[n].menu_id;
				if(res[n].save_access == "yes")
				{
					$("#check_"+menuId).prop("checked",true);
					$("#saveAccess_"+menuId).prop("checked",true);
				}
				if(res[n].edit_access == "yes")
				{
					$("#editAccess_"+menuId).prop("checked",true);
				}
				if(res[n].delete_access == "yes")
				{
					$("#delAccess_"+menuId).prop("checked",true);
				}
			}
		}
	}
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$(".onCheckSelectField").click(function()
	{
		var menuId = $(this).attr('menuId');
		var isParent = $(this).attr('isParent');
		var isChecked = $(this).is(":checked");
		
		if(menuId > 0)
		{
			if(isParent == "yes")
			{
				$(".menuListsTR td.selectCheckTD").each(function()
				{
					var isAvail = $(this).hasClass("parentMenuId_"+menuId);
					if(isAvail)
					{
						var curMenuId = $(this).attr('menuId');
						if(isChecked)
						{
							$("#check_"+curMenuId).prop("checked",true);
							$("#saveAccess_"+curMenuId).prop("checked",true);
						}
						else
						{
							$("#check_"+curMenuId).prop("checked",false);
							$("#saveAccess_"+curMenuId).prop("checked",false);
						}
					}
				});
			}
			else
			{
				var curMenuId = $(this).attr('menuId');
				if(isChecked)
				{
					$("#saveAccess_"+curMenuId).prop("checked",true);
				}
				else
				{
					$("#saveAccess_"+curMenuId).prop("checked",false);
				}
			}
		}
	});
	
	$(".onCheckSaveField").click(function()
	{
		var menuId = $(this).attr('menuId');
		if(menuId > 0)
		{
			$("#check_"+menuId).prop("checked",false);
		}
	});
	
	$("#userForm").submit(function(e)
	{
		e.preventDefault();
		
		var userId = '<?php echo $userId; ?>';	
		var userName = $("#userName").val();
		var userEmail = $("#userEmail").val();
		var userType = $("#userType").val();
		
		var menuPermissionsArr = [];
		
		$(".menuListsTR").each(function()
		{
			var menuId = $(this).attr('menuId');
			if(menuId > 0)
			{
				var check = $("#check_"+menuId).is(":checked");
				var saveAccess = $("#saveAccess_"+menuId).is(":checked");
				var editAccess = $("#editAccess_"+menuId).is(":checked");
				var delAccess = $("#delAccess_"+menuId).is(":checked");
				
				if(check == true || saveAccess == true || editAccess == true || delAccess == true)
				{
					var newSaveAccess = "no";
					if(check == true || saveAccess == true)
					{
						newSaveAccess = "yes";
					}
					
					var cri = {};
					cri["menuId"] = menuId;
					cri["saveAccess"] = newSaveAccess;
					cri["editAccess"] = editAccess ? "yes" : "no";
					cri["delAccess"] = delAccess ? "yes" : "no";
					
					menuPermissionsArr.push(cri);
				}
			}
		});
		
		if(menuPermissionsArr.length > 0)
		{
			var req = new Request();
			req.data = 
			{
				"menuId" : '<?php echo $menuId; ?>', 
				"userId" : userId, 
				"userName" : userName, 
				"userEmail" : userEmail, 
				"userType" : userType, 
				"menuPermissionsArr" : JSON.stringify(menuPermissionsArr)
			};
			req.url = "admin/saveUser";
			RequestHandler(req, showResponse);
		}
		else
		{
			alert('Please select User Permissions.');
			return;
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
	
	$(".delEntry").click(function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			var bool = confirm("Are You Sure Want To Remove This User?");
			if(bool)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"entryId" : entryId, 
					"tableName" : "users", 
					"columnName" : "userid"
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