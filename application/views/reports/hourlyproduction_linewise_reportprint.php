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
                            <!--<thead>
								<tr>
									<th>Sl No.</th>
									<th>Entry Date</th>
									<th>Line Name</th>
									<th>Shift</th>
									<th>Operation</th>
									<th>No. Of Workers</th>
									<th>Day's Target</th>
									<th>Target Per Hour</th>
									<th>No. Of Operators</th>
									<th>Avail Minutes</th>
									<th>Current Target</th>
									<th>Issues</th>
									<th>Hour 1</th>
									<th>Hour 2</th>
									<th>Hour 3</th>
									<th>Hour 4</th>
									<th>Hour 5</th>
									<th>Hour 6</th>
									<th>Hour 7</th>
									<th>Hour 8</th>
									<th>OT Pieces</th>
									<th>Total Output</th>
									<th>WIP</th>
									<th>Idle Time</th>
									<th>Breakdown Time</th>
									<th>Rework Time</th>
									<th>No Work Time</th>
									<th>Line Efficiency</th>
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
										<td><?php echo $row->linename; ?></td>
										<td><?php echo $row->shiftname; ?></td>
										<td><?php echo $row->operationname; ?></td>
										<td><?php echo $row->no_of_workers; ?></td>
										<td><?php echo $row->days_target; ?></td>
										<td><?php echo $row->target_per_hr; ?></td>
										<td><?php echo $row->no_of_operators; ?></td>
										<td><?php echo $row->avail_min; ?></td>
										<td><?php echo $row->current_target; ?></td>
										<td><?php echo $row->issues; ?></td>
										<td><?php echo $row->hour1; ?></td>
										<td><?php echo $row->hour2; ?></td>
										<td><?php echo $row->hour3; ?></td>
										<td><?php echo $row->hour4; ?></td>
										<td><?php echo $row->hour5; ?></td>
										<td><?php echo $row->hour6; ?></td>
										<td><?php echo $row->hour7; ?></td>
										<td><?php echo $row->hour8; ?></td>
										<td><?php echo $row->othour; ?></td>
										<td><?php echo $row->totalpieces; ?></td>
										<td><?php echo $row->wip; ?></td>
										<td><?php echo $row->idletime; ?></td>
										<td><?php echo $row->breakdown_time; ?></td>
										<td><?php echo $row->rework_time; ?></td>
										<td><?php echo $row->nowork_time; ?></td>
										<td><?php echo $row->line_efficiency; ?></td>
									</tr>
									<?php
									}
								}
								else
								{
									echo '<tr><td colspan="28">No Data Found.</td></tr>';
								}
								?>
							</tbody>-->
							<thead>
								<tr>
									<th>Sl. No.</th>
									<th>Entry Date</th>
									<th>Line Name</th>
									<th>Line Location</th>
									<th>Style No.</th>
									<th>No. Of Workers</th>
									<th>Input Pieces</th>
									<th>Output Pieces</th>
									<th>WIP</th>
									<th>Breakdown Timings</th>
									<th>Issue Type</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if(count($datas) > 0)
								{
									$n = 0;
									foreach($datas as $row)
									{
										$n++;
									?>
									<tr>
										<td><?php echo $n; ?></td>
										<td><?php echo $row->createddt; ?></td>
										<td><?php echo $row->lineid; ?></td>
										<td><?php echo $row->linelocation; ?></td>
										<td><?php echo $row->styleno; ?></td>
										<td><?php echo $row->noofworkers; ?></td>
										<td><?php echo $row->input_cnt; ?></td>
										<td><?php echo $row->output_cnt; ?></td>
										<td><?php echo $row->wip; ?></td>
										<td><?php echo $row->timings; ?></td>
										<td><?php echo $row->issuetype; ?></td>
									</tr>
									<?php	
									}
								}
								else
								{
									echo '<tr><td colspan="11">No Data Found.</td></tr>';
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