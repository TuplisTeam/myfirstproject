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
									<th>Line Efficiency</th>
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
										$totalWorkers = intval($row->operators_in_line) + intval($row->helpers_in_line);
										$lineEfficiency = ($row->output_cnt*$row->total_sam*100)/($totalWorkers*$row->producedmin);
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
										<td><?php echo number_format($lineEfficiency, 2, '.', ''); ?></td>
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