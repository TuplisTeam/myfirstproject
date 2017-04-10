<!DOCTYPE html>
<html>

<head>        
        <!-- Title -->
        <title><?php echo $this->config->item('projectTitle'); ?></title>
        
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
		<link href="<?php echo base_url(); ?>assets/plugins/datatables/css/jquery.datatables.min.css" rel="stylesheet" type="text/css"/>	
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css"/>	
        <link href="<?php echo base_url(); ?>assets/plugins/waves/waves.min.css" rel="stylesheet" type="text/css"/>	
        <link href="<?php echo base_url(); ?>assets/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/plugins/3d-bold-navigation/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/plugins/slidepushmenus/css/component.css" rel="stylesheet" type="text/css"/>	
        <link href="<?php echo base_url(); ?>assets/plugins/weather-icons-master/css/weather-icons.min.css" rel="stylesheet" type="text/css"/>	
        <link href="<?php echo base_url(); ?>assets/plugins/metrojs/MetroJs.min.css" rel="stylesheet" type="text/css"/>	
        <link href="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css"/>	
        <link href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>	
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
		
        <!-- Theme Styles -->
        <link href="<?php echo base_url(); ?>assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/css/themes/green.css" class="theme-color" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
        
		<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-2.1.4.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/jquery.ajax.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/3d-bold-navigation/js/modernizr.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-mockjax-master/jquery.mockjax.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/moment/moment.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/datatables/js/jquery.datatables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/jquery.numeric.js"></script>
		
		<script src="<?php echo base_url(); ?>assets/myjs.js"></script>
		
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
			.page-footer
			{
				position: relative;
			}
			.scrollToTop
			{
			    position:fixed;
			    right:10px;
			    bottom:10px;
			    cursor:pointer;
			    width:50px;
			    height:50px;
			    background-color:#3498db;
			    text-indent:-9999px;
			    display:none;
			    -webkit-border-radius:60px;
			    -moz-border-radius:60px;
			    border-radius:60px
			}
			.scrollToTop span
			{
			    position:absolute;
			    top:50%;
			    left:50%;
			    margin-left:-8px;
			    margin-top:-12px;
			    height:0;
			    width:0;
			    border:8px solid transparent;
			    border-bottom-color:#ffffff;
			}
			.scrollToTop:hover
			{
			    background-color:#e74c3c;
			    opacity:1;filter:"alpha(opacity=100)";
			    -ms-filter:"alpha(opacity=100)";
			}
			.panel .panel-heading .panel-title
			{
				float: none !important;
				text-align: center;
			}
		</style>
		
    </head>
    <body class="page-header-fixed" style="background: #f1f4f9 !important;">
        <div class="overlay"></div>