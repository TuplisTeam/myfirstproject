<!DOCTYPE html>
<html>
	<head>
	<!-- Title -->
	<title><?php echo $this->config->item('projectTitle'); ?> | Login</title>

	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta charset="UTF-8">
	<meta name="description" content="<?php echo $this->config->item('projectTitle'); ?>" />
	<meta name="keywords" content="<?php echo $this->config->item('projectTitle'); ?>" />
	<meta name="author" content="<?php echo $this->config->item('projectTitle'); ?>" />

	<!-- Styles -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
	<link href="<?php echo base_url(); ?>assets/plugins/pace-master/themes/blue/pace-theme-flash.css" rel="stylesheet"/>
	<link href="<?php echo base_url(); ?>assets/plugins/uniform/css/uniform.default.min.css" rel="stylesheet"/>
	<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/plugins/line-icons/simple-line-icons.css" rel="stylesheet" type="text/css"/>	
	<link href="<?php echo base_url(); ?>assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css" rel="stylesheet" type="text/css"/>	
	<link href="<?php echo base_url(); ?>assets/plugins/waves/waves.min.css" rel="stylesheet" type="text/css"/>	
	<link href="<?php echo base_url(); ?>assets/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/plugins/3d-bold-navigation/css/style.css" rel="stylesheet" type="text/css"/>	
	<link href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
	
	<!-- Theme Styles -->
	<link href="<?php echo base_url(); ?>assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/css/themes/green.css" class="theme-color" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css"/>

	<script src="<?php echo base_url(); ?>assets/plugins/3d-bold-navigation/js/modernizr.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	</head>
	<body class="page-login">
		<main class="page-content">
			<div class="page-inner">
				<div id="main-wrapper">
					<div class="row">
						<div class="col-md-3 center">
							<div class="login-box">
								<a href="<?php echo base_url(); ?>login/" class="logo-name text-lg text-center">
									<?php echo $this->config->item('projectTitle'); ?>
								</a>
								<p class="text-center m-t-md">
									Please Login Into Your Account.
								</p>
								<form class="m-t-md" action="" method="POST" id="loginForm">
									<div class="form-group">
										<input type="email" class="form-control" placeholder="Email" id="email" name="email" required />
									</div>
									<div class="form-group">
										<input type="password" class="form-control" placeholder="Password" id="password" name="password" required />
									</div>
									<div class="form-group" id="sectionDiv">
										<select class="form-control" data-placeholder="Select Store" id="sectionName" name="sectionName">
											<option value=""></option>
											<option value="Cutting">Cutting Section</option>
											<option value="Sewing">Sewing Section</option>
											<option value="Storage_Procurement">Storage / Procurement Section</option>
											<option value="Ironing_Packing">Ironing / Packing Section</option>
											<option value="Printing">Printing Section</option>
											<option value="Embroidary">Embroidary Section</option>
										</select>
									</div>
									<button type="submit" class="btn btn-success btn-block">
										Login
									</button>
									<a href="<?php echo base_url(); ?>login/forgotpassword" class="display-block text-center m-t-md text-sm">Forgot Password?</a>
									<!--<p class="text-center m-t-xs text-sm">
										DO NOT HAVE AN ACCOUNT?
									</p>
									<a href="<?php echo base_url(); ?>login/register" class="btn btn-default btn-block m-t-md">CREATE AN ACCOUNT</a>-->
								</form>
								<p class="text-center m-t-xs text-sm">
									2016 &copy;
								</p>
								<br>
								<div id="responseMsg"></div>
							</div>
						</div>
					</div><!-- Row -->
				</div><!-- Main Wrapper -->
			</div><!-- Page Inner -->
		</main><!-- Page Content -->

		<!-- Javascripts -->
		<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-2.1.4.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/pace-master/pace.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/jquery.ajax.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/jquery-blockui/jquery.blockui.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/switchery/switchery.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/uniform/jquery.uniform.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/offcanvasmenueffects/js/classie.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/waves/waves.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/modern.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js"></script>
		<script type="text/javascript">
			
			$(document).ready(function()
			{
				$('select').select2();
			});
			
			$("#email").blur(function()
			{
				var email = $(this).val();
				if(email != "")
				{
					var req = new Request();
					req.data = 
					{
						"email" : email
					};
					req.url = "login/getUserDetailsByEmail";
					RequestHandler(req, setUserDetails);
				}
			});
			
			function setUserDetails(data)
			{
				data = JSON.parse(data);
				var isError = data.isError;
				var msg = data.msg;
				if(isError)
				{
					alert(msg);
					$("#email").val('');
				}
				else
				{
					var res = data.res;
					if(res.length > 0)
					{
						if(res[0].usertype == "user")
						{
							$("#sectionDiv").css('display','block');
							
							var sectionName = res[0].sectionname;
						
							$("#sectionName").select2('val', sectionName);
							$("#sectionName").select2('enable', false);
						}
						else
						{
							$("#sectionDiv").css('display','none');
						}
					}
				}
			}
			
			$("#loginForm").submit(function(e)
			{
				e.preventDefault();
				
				var email = $("#email").val();
				var password = $("#password").val();
				var sectionName = $("#sectionName").val();
				
				if(email != "" && password != "")
				{
					var req = new Request();
					req.data = 
					{
						"email" : email,
						"password" : password, 
						"sectionName" : sectionName
					};
					req.url = "login/checkLogin";
					RequestHandler(req, showResponse);
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
					
					redirectURL = '<?php echo base_url(); ?>admin/';
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
		
	</body>
</html>