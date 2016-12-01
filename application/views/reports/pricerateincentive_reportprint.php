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
									<th>Employee No.</th>
									<th>Employee Name</th>
									<th>Target Pieces</th>
									<th>Sewing Pieces</th>
									<th>Incentive Pieces</th>
									<th>Amount</th>
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
										<td><?php echo $row->empno; ?></td>
										<td><?php echo $row->empname; ?></td>
										<td><?php echo $row->target; ?></td>
										<td><?php echo $row->sewing; ?></td>
										<td><?php echo $row->incentive; ?></td>
										<td><?php echo $row->amount; ?></td>
									</tr>
									<?php
									}
								}
								else
								{
									echo '<tr><td colspan="9">No Data Found.</td></tr>';
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