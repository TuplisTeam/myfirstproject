<?php
$curpage = $this->uri->uri_string;
$editpage = explode('/',$curpage);

if(count($editpage) > 1)
{
	$curpage = $editpage[0].'/'.$editpage[1];
}
?>
			
<div class="page-sidebar sidebar">
    <div class="page-sidebar-inner slimscroll">
        <!--<div class="sidebar-header">
            <div class="sidebar-profile">
                <a href="javascript:void(0);" id="profile-menu-link">
                    <div class="sidebar-profile-image">
                        <img src="<?php echo base_url(); ?>assets/images/default.jpg" class="img-circle img-responsive" alt="">
                    </div>
                    <div class="sidebar-profile-details">
                        <span>
							<?php echo $this->session->userdata('firstname'); ?>
						</span>
                    </div>
                </a>
            </div>
        </div>-->
        
        <ul class="menu accordion-menu">
			<?php
			$myMenus = array();
        	if($this->session->userdata('usertype') == "admin")
        	{
				$myMenus = $this->adminmodel->getMenuDetails();
			}
        	else
        	{
				$myMenus = $this->adminmodel->getMenuDetails_User($this->session->userdata('userid'));
			}
			if(count($myMenus) > 0)
			{
				foreach($myMenus as $row)
				{
					if($row->parent_id == 0)
					{
						$liClass = "";
						if($row->is_parent == "yes")
						{
							$liClass = "droplink";
						}
						else
						{
							$liClass = "";
						}
						?>
						<li class="<?php echo $liClass; ?>">
							<?php
							if($row->is_parent == "yes")
							{
							?>
							<a href="#" class="waves-effect waves-button">
								<span class="menu-icon glyphicon <?php echo $row->menu_icon; ?>"></span>
								<p><?php echo $row->menu_name; ?></p>
								<span class="arrow"></span>
							</a>
							
							<ul class="sub-menu">
								<?php
								if(count($myMenus) > 0)
								{
									foreach($myMenus as $row1)
									{
										if($row->menu_id == $row1->parent_id)
										{
										?>
										<li>
											<a href="<?php echo base_url().$row1->menu_url; ?>">
												<?php echo $row1->menu_name; ?>
											</a>
										</li>
										<?php
										}
									}
								}
								?>
							</ul>
							<?php
							}
							else
							{
							?>
							<a href="<?php echo base_url().$row->menu_url; ?>" class="waves-effect waves-button">
								<span class="menu-icon glyphicon <?php echo $row->menu_icon; ?>"></span>
								<p><?php echo $row->menu_name; ?></p>
							</a>
							<?php
							}
							?>
						</li>
						<?php
					}
				}
			}
			?>
			
        </ul>
    </div><!-- Page Sidebar Inner -->
</div><!-- Page Sidebar -->