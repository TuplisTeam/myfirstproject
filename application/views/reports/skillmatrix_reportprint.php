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
									<?php
									if($filterBy == "EmployeeWise")
									{
									?>
									<th>Employee No.</th>
									<th>Employee Name</th>
									<?php	
									}
									?>
									<th>Operation Name</th>
									<th>Produced Minutes</th>
									<th>Pieces</th>
									<th>SAM</th>
									<th>Shift Hours</th>
									<th>OT Hours</th>
									<th>Efficiency</th>
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
										<?php
										if($filterBy == "EmployeeWise")
										{
										?>
										<td><?php echo $row->empno; ?></td>
										<td><?php echo $row->empname; ?></td>
										<?php	
										}
										?>
										<td><?php echo $row->operationname; ?></td>
										<td><?php echo $row->producedmin; ?></td>
										<td><?php echo $row->pieces; ?></td>
										<td><?php echo $row->sam; ?></td>
										<td><?php echo $row->shifthrs; ?></td>
										<td><?php echo $row->othours; ?></td>
										<td><?php echo $row->efficiency; ?></td>
									</tr>
									<?php
									}
								}
								else
								{
									echo '<tr><td colspan="7">No Data Found.</td></tr>';
								}
								?>
							</tbody>-->
							<thead>
								<tr>
									<th>Sl No.</th>
									<th>Entry Date</th>
									<th>Line Name</th>
									<th>Shift Name</th>
									<th>Employee No.</th>
									<th>Style Name</th>
									<th>Table Name</th>
									<th>Operation Name</th>
									<th>Machinary Name</th>
									<th>SMV</th>
									<th>Target Minutes</th>
									<th>OT Hours</th>
									<th>Produced Minutes</th>
									<th>Employee Efficiency</th>
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
										<td><?php echo $row->empno.' - '.$row->empname; ?></td>
										<td><?php echo $row->styleno; ?></td>
										<td><?php echo $row->tablename; ?></td>
										<td><?php echo $row->operationname; ?></td>
										<td><?php echo $row->machineryname; ?></td>
										<td><?php echo $row->smv; ?></td>
										<td><?php echo $row->targetminutes; ?></td>
										<td><?php echo $row->ot_hours; ?></td>
										<td><?php echo $row->producedmin; ?></td>
										<td><?php echo number_format(((($row->output_cnt*$row->smv)/$row->producedmin)*100),2); ?></td>
									</tr>
									<?php
									}
								}
								else
								{
									echo '<tr><td colspan="14">No Data Found.</td></tr>';
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