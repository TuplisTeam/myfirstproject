<!--<div class="page-title">
    <h3 style="text-align: center;">
		<?php echo $title; ?>
	</h3>
</div>-->
<div id="main-wrapper">
    <div class="row">
		<div class="col-md-12">
			<div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title"><?php echo $subtitle; ?></h4>
                </div>
                <div class="panel-body">
					<div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
								<tr>
									<th>Sl No.</th>
									<th>Entry Date</th>
									<th>Line Name</th>
									<th>Table Name</th>
									<th>Issue Type</th>
									<th>In Time</th>
									<th>Out Time</th>
									<th>Time Taken</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(count($datas) > 0)
								{
									$i = 0;
									foreach($datas as $row)
									{
									$i++;
									?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo $row->createddt; ?></td>
										<td><?php echo $row->lineid; ?></td>
										<td><?php echo $row->table_name; ?></td>
										<td><?php echo ucwords($row->issuetype); ?></td>
										<td><?php echo $row->in_time; ?></td>
										<td><?php echo $row->out_time; ?></td>
										<td><?php echo $row->timetaken; ?></td>
									</tr>
									<?php
									}
								}
								else
								{
									echo '<tr><td colspan="7">No Data Found.</td></tr>';
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
<div class="page-footer">
    <p class="no-s">2016 &copy;</p>
</div>