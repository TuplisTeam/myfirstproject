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
                    <h4 class="panel-title">
						Operation Bulletin
					</h4>
                </div>
                <div class="panel-body">
					<div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
								<tr>
									<td>Style No</td>
									<td><?php echo $styleNo; ?></td>
									<td>Created By</td>
									<td><?php echo $createdBy; ?></td>
								</tr>
								<tr>
									<td>Style Desc</td>
									<td><?php echo $styleDesc; ?></td>
									<td>Planned Factory</td>
									<td></td>
								</tr>
								<tr>
									<td>Colour</td>
									<td></td>
									<td>Prepared On</td>
									<td><?php echo $preparedOn; ?></td>
								</tr>
								<tr>
									<td>Size</td>
									<td></td>
									<td>Revised On</td>
									<td><?php echo $revisedOn; ?></td>
								</tr>
								<tr>
									<td>Revision No</td>
									<td></td>
								</tr>
							</thead>
                        </table>
                    </div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<td>Standard No Of Workstations</td>
								<td><?php echo $stdNoOfWorkStations; ?></td>
								<td>Total SAM</td>
								<td><?php echo $totalSAM; ?></td>
							</tr>
							<tr>
								<td>Standard No Of Operators in Line</td>
								<td><?php echo $stdNoOfOperators; ?></td>
								<td>Machine SAM</td>
								<td><?php echo $machineSAM; ?></td>
							</tr>
							<tr>
								<td>Standard No Of Non operators in Line</td>
								<td><?php echo $stdNoOfHelpers; ?></td>
								<td>Manual SAM</td>
								<td><?php echo $manualSAM; ?></td>
							</tr>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<td>Possible Daily Output @ 100%</td>
								<td><?php echo $possibleDailyOutput; ?></td>
								<td>Expected Average Efficiency </td>
								<td><?php echo $expectedAvgEfficiency; ?></td>
							</tr>
							<tr>
								<td>Expected Peak Efficiency</td>
								<td><?php echo $expectedPeakEfficiency; ?></td>
								<td>Expected Daily Output @ Average Efficiency</td>
								<td><?php echo $expectedDailyOutput; ?></td>
							</tr>
							<tr>
								<td>Expected Output @ Peak Efficiency</td>
								<td><?php echo $expectedOutput; ?></td>
								<td>Average Output per Machine</td>
								<td><?php echo $avgOutputPerMachine; ?></td>
							</tr>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<td></td>
								<td>@ PE</td>
								<td>@ AE</td>
								<td>@ 100%</td>
							</tr>
							<tr>
								<td>Target/Head/Shift</td>
								<td>
									<?php
									$val = (600/$totalSAM)*$expectedPeakEfficiency;
									echo $val ? $val : 0;
									?>
								</td>
								<td>
									<?php
									$val = (600/$totalSAM)*$expectedAvgEfficiency;
									echo $val ? $val : 0;
									?>
								</td>
								<td>
									<?php
									$val = (600/$totalSAM);
									echo $val ? $val : 0;
									?>
								</td>
							</tr>
							<tr>
								<td>Target/Machine/Shift</td>
								<td>
									<?php
									$val = (600/$machineSAM)/$expectedPeakEfficiency;
									echo $val ? $val : 0;
									?>
								</td>
								<td>
									<?php
									$val = (600/$machineSAM)/$expectedAvgEfficiency;
									echo $val ? $val : 0;
									?>
								</td>
								<td>
									<?php
									$val = (600/$machineSAM);
									echo $val ? $val : 0;
									?>
								</td>
							</tr>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>Opr #</th>
								<th>Operation Description</th>
								<th>Freq</th>
								<th>Machine</th>
								<th>SMV</th>
								<th>No Of W/s</th>
								<th>Balanced W/s</th>
								<th>sec / unit</th>
								<th>Folders / clips / guides</th>
							</tr>
							<?php
							$i = 1;
							foreach($operationDtls as $res)
							{
							?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $res->operation_desc; ?></td>
								<td><?php echo $res->frequency; ?></td>
								<td><?php echo $res->machine; ?></td>
								<td><?php echo $res->smv; ?></td>
								<td><?php echo $res->no_of_workers; ?></td>
								<td><?php echo $res->balanced_workers; ?></td>
								<td><?php echo $res->sec_per_unit; ?></td>
								<td><?php echo $res->folders_clips_guides; ?></td>
							</tr>
							<?php
							$i++;
							}
							?>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>MACHINERY REQUIRMENT</th>
								<th>NO'S</th>
								<th>SMV</th>
							</tr>
							<?php
							foreach($machineryDtls as $res)
							{
							?>
							<tr>
								<td><?php echo $res->machinery_requirement; ?></td>
								<td><?php echo $res->numbers; ?></td>
								<td><?php echo $res->smv; ?></td>
							</tr>
							<?php
							}
							?>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>Manual Work</th>
								<th>NO'S</th>
								<th>SMV</th>
							</tr>
							<?php
							foreach($manualWorkDtls as $res)
							{
							?>
							<tr>
								<td><?php echo $res->manualwork; ?></td>
								<td><?php echo $res->numbers; ?></td>
								<td><?php echo $res->smv; ?></td>
							</tr>
							<?php
							}
							?>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<caption>Cost Summary</caption>
							<tr>
								<th>TTL Cost</th>
								<th>Target</th>
								<th>Cost / Pcs</th>
							</tr>
							<tr>
								<td>
									<?php
									$ttlCost = ($mc_TotalNumbers * 506) + ($mn_TotalNumbers * 380);
									echo $ttlCost;
									?>
								</td>
								<td><?php echo $expectedDailyOutput; ?></td>
								<td>
									<?php
									$costPerPcs = $ttlCost / $expectedDailyOutput;
									echo $costPerPcs ? $costPerPcs : 0;
									?>
								</td>
							</tr>
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