</main><!-- Page Content -->
        
        <div class="cd-overlay"></div>

		<a href="javascript:void(0);" class="scrollToTop" title="Scroll to Top">Top<span></span></a>
	

        <!-- Javascripts -->
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/pace-master/pace.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/switchery/switchery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/uniform/jquery.uniform.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/offcanvasmenueffects/js/classie.js"></script>
        <!--<script src="<?php echo base_url(); ?>assets/plugins/offcanvasmenueffects/js/main.js"></script>-->
        <script src="<?php echo base_url(); ?>assets/plugins/waves/waves.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/3d-bold-navigation/js/main.js"></script>
		<script src="<?php echo base_url(); ?>assets/plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/modern.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/waypoints/jquery.waypoints.min.js"></script>
        
        <script src="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.js"></script>
        <!--<script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.time.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.symbol.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/curvedlines/curvedLines.js"></script>-->
        <script src="<?php echo base_url(); ?>assets/plugins/metrojs/MetroJs.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
        <script>
        	
        	$(window).scroll(function()
			{
				if ($(this).scrollTop() > 100)
				{
					$('.scrollToTop').fadeIn();
				}
				else
				{
					$('.scrollToTop').fadeOut();
				}
			});
			
			$('.scrollToTop').click(function()
			{
				$("html, body").animate({ scrollTop: 0 }, 600);
				return false;
			});
			
        </script>
    </body>
</html>