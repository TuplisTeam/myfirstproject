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
										<td><?php echo $row->shift; ?></td>
										<td><?php echo $row->empno; ?></td>
										<td><?php echo $row->empname; ?></td>
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
									</tr>
									<?php
									}
								}
								else
								{
									echo '<tr><td colspan="16">No Data Found.</td></tr>';
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