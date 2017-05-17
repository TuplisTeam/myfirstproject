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
									<th>Shift</th>
									<th>Employee No.</th>
									<th>Employee Name</th>
									<th>Start Time</th>
									<th>End Time</th>
									<th>Input Pieces</th>
									<th>Output Pieces</th>
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
										<td><?php echo $row->entrydt; ?></td>
										<td><?php echo $row->line_name.' - '.$row->line_location; ?></td>
										<td><?php echo $row->shiftname; ?></td>
										<td><?php echo $row->empno; ?></td>
										<td><?php echo $row->empname; ?></td>
										<td><?php echo $row->start_time; ?></td>
										<td><?php echo $row->end_time; ?></td>
										<td><?php echo $row->incnt; ?></td>
										<td><?php echo $row->outcnt; ?></td>
									</tr>
									<?php
									}
								}
								else
								{
									echo '<tr><td colspan="10">No Data Found.</td></tr>';
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