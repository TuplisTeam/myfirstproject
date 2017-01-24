<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/operationbulletin" style="text-decoration: none;">
				Operation Bulletin
			</a>
		</h3>
    </div>
    <div class="col-md-6" id="responseMsg"></div>
	<div id="main-wrapper">
		<?php 
		$displayblock = "style='display:none;'";
		$displaynone = "style='display:block;'";
		
		if($bulletinId != "")
		{
			$displayblock = "style='display:block;'";
			$displaynone = "style='display:none;'";
		}
		?>
		
		<div class="row" id="listDetails" <?php echo $displaynone; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">Operation Bulletin Details</h4>
						<button class="btn btn-success pull-right newEntry">
							New Entry
						</button>
                    </div>
                    <div class="panel-body">
                       <div class="table-responsive">
						   <table id="example" class="display table" style="width: 100%; cellspacing: 0;">
	                            <thead>
	                                <tr>
	                                    <th>Style No.</th>
										<th>Manage</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php
									foreach($allDetails as $row)
									{
									?>
									<tr>
										<td><?php echo $row->styleno; ?></td>
										<td>
										<button class="btn btn-primary btn-xs editEntry" entryId="<?php echo $row->id; ?>">
											Edit
										</button>
										<button class="btn btn-danger btn-xs delEntry" entryId="<?php echo $row->id; ?>">
											Del
										</button>
										<button class="btn btn-success btn-xs printEntry" entryId="<?php echo $row->id; ?>">
											Print
										</button>
									</td>
									</tr>
									<?php
									}
									?>
	                            </tbody>
							</table>
                        </div>
                    </div>
                </div>
			</div>
		</div>
        <div class="row" id="entryDetails" <?php echo $displayblock; ?>>
			<div class="col-md-12">
				<div class="panel panel-white">
	                <div class="panel-heading clearfix">
	                    <h4 class="panel-title">Operation Bulletin Form</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="operationBulletinForm" method="POST">
	                    	<div class="row">
	                    		<div class="col-md-12">
	                    			<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Style No.&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <select class="form-control" style="width: 100%;" data-placeholder="Select" id="styleId" name="styleId" required="">
													<option value=""></option>
													<?php
													foreach($styleDtls as $res)
													{
														echo '<option value="'.$res->id.'"';
														echo '>'.$res->styleno.'</option>';
													}
													?>
												</select>
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
							<div class="row">
	                    		<div class="col-md-12">
									<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Standard No. Of Workstations&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control" id="stdNoOfWorkStations" name="stdNoOfWorkStations" placeholder="Standard No. Of Workstations" disabled="" value="<?php echo $stdNoOfWorkStations; ?>" />
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Standard No. Of Operators In Line&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control numeric calculateWorkStations" id="stdNoOfOperators" name="stdNoOfOperators" placeholder="Standard No. Of Operators In Line" value="<?php echo $stdNoOfOperators; ?>" />
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Standard No. Of Helpers In Line&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control numeric calculateWorkStations" id="stdNoOfHelpers" name="stdNoOfHelpers" placeholder="Standard No. Of Helpers In Line" value="<?php echo $stdNoOfHelpers; ?>" />
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
							<div class="row">
	                    		<div class="col-md-12">
									<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Total SAM&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control" id="totalSAM" name="totalSAM" placeholder="Total SAM" disabled="" value="<?php echo $totalSAM; ?>" />
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Machine SAM&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control numeric" id="machineSAM" name="machineSAM" placeholder="Machine SAM" disabled="" value="<?php echo $machineSAM; ?>" />
				                            </div>
				                        </div>
	                    			</div>
	                    			<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Manual SAM&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control numeric" id="manualSAM" name="manualSAM" placeholder="Manual SAM" disabled="" value="<?php echo $manualSAM; ?>" />
				                            </div>
				                        </div>
	                    			</div>
	                    		</div>
	                    	</div>
							<div class="row">
	                    		<div class="col-md-12">
									<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Possible Daily Output @ 100%&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control" id="possibleDailyOutput" name="possibleDailyOutput" placeholder="Possible Daily Output @ 100%" disabled="" value="<?php echo $possibleDailyOutput; ?>" />
				                            </div>
				                        </div>
	                    			</div>
									<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Expected Peak Efficiency&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control numeric calculateExpectedOutput" id="expectedPeakEfficiency" name="expectedPeakEfficiency" placeholder="Expected Peak Efficiency" value="<?php echo $expectedPeakEfficiency; ?>" />
				                            </div>
				                        </div>
	                    			</div>
									<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Expected Output @ Peak Efficiency&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control" id="expectedOutput" name="expectedOutput" placeholder="Expected Output @ Peak Efficiency" disabled="" value="<?php echo $expectedOutput; ?>" />
				                            </div>
				                        </div>
	                    			</div>
								</div>
							</div>
							<div class="row">
	                    		<div class="col-md-12">
									<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Expected Average Efficiency&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control numeric calculateExpectedDailyOutput" id="expectedAvgEfficiency" name="expectedAvgEfficiency" placeholder="Expected Average Efficiency" value="<?php echo $expectedAvgEfficiency; ?>" />
				                            </div>
				                        </div>
	                    			</div>
									<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Expected Daily Output @ Average Efficiency&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control numeric" id="expectedDailyOutput" name="expectedDailyOutput" placeholder="Expected Daily Output @ Average Efficiency" disabled="" value="<?php echo $expectedDailyOutput; ?>" />
				                            </div>
				                        </div>
	                    			</div>
									<div class="col-md-4">
	                    				<div class="form-group">
				                            <label class="col-sm-5 control-label">
												Average Output Per Machine&nbsp;<span style="color: red;">*</span>
											</label>
				                            <div class="col-sm-7">
				                                <input type="text" class="form-control numeric" id="avgOutputPerMachine" name="avgOutputPerMachine" placeholder="Average Output Per Machine" disabled="" value="<?php echo $avgOutputPerMachine; ?>" />
				                            </div>
				                        </div>
	                    			</div>
								</div>
							</div>
							
	                    	<div class="row">
	                    		<div class="form-group">
		                        	<div class="col-md-12">
		                        		<div class="table-responsive">
		                        			<div style="float: right;">
				                        		<button type="button" class="btn btn-success addOperationRow">
				                        		Add New Row <i class="fa fa-plus"></i>
				                        		</button>
			                        		</div>
			                        		<table class="table table-bordered">
				                        		<thead>
				                        			<tr>
				                        				<th width="20%">Operation Desc</th>
				                        				<th width="10%">Freq</th>
				                        				<th width="10%">Machine</th>
				                        				<th width="10%">SMV</th>
				                        				<th width="10%">No. Of W/s</th>
				                        				<th width="10%">Balanced W/s</th>
				                        				<th width="10%">Sec / Unit</th>
				                        				<th width="10%">Folders / Clips / Guides</th>
				                        				<th width="10%">Manage</th>
				                        			</tr>
				                        		</thead>
				                        		<tbody class="operationDetailsTBody">
			                        			<?php
			                        			if($bulletinId > 0)
			                        			{
													if(count($operationDtls) > 0)
													{
														$cnt = 0;
														foreach($operationDtls as $row)
														{
															$cnt++;
														?>
														<tr class="operationDetailsTR" rowNo="<?php echo $cnt; ?>">
															<td>
																<select class="form-control op_operationId_<?php echo $cnt; ?>" style="width: 100%;" data-placeholder="Select">
																	<option value=""></option>
																	<?php
																	foreach($operations as $res)
																	{
																		echo '<option value="'.$res->id.'"';
																		if($row->operationid == $res->id)
																		{
																			echo ' selected="selected"';
																		}
																		echo '>'.$res->operationname.'</option>';
																	}
																	?>
																</select>
															</td>
															<td>
																<input type="text" class="form-control op_frequency_<?php echo $cnt; ?>" placeholder="Frequency" value="<?php echo $row->frequency; ?>">
															</td>
															<td>
																<select class="form-control op_machine_<?php echo $cnt; ?>" style="width: 100%;" data-placeholder="Select">
																	<option value=""></option>
																	<?php
																	foreach($machinaryRequirements as $res)
																	{
																		echo '<option value="'.$res->id.'"';
																		if($row->machine == $res->id)
																		{
																			echo ' selected="selected"';
																		}
																		echo '>'.$res->machineryname.'</option>';
																	}
																	?>
																</select>
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurOperationFields op_smv_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="SMV" value="<?php echo $row->smv; ?>">
															</td>
															<td>
																<input type="text" class="form-control op_noOfWorkers_<?php echo $cnt; ?>" placeholder="No. Of Workers" value="<?php echo $row->no_of_workers; ?>" disabled="">
															</td>
															<td>
																<input type="text" class="form-control numeric onBlurOperationFields op_balancedWorkers_<?php echo $cnt; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Balanced Workers" value="<?php echo $row->balanced_workers; ?>" disabled="">
															</td>
															<td>
																<input type="text" class="form-control op_secPerUnit_<?php echo $cnt; ?>" placeholder="Sec / Unit" value="<?php echo $row->sec_per_unit; ?>" disabled="">
															</td>
															<td>
																<input type="text" class="form-control op_folders_<?php echo $cnt; ?>" placeholder="Folders / Clips / Guides" value="<?php echo $row->folders_clips_guides; ?>">
															</td>
															<td>
																<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delOperationDtl"><i class="fa fa-close"></i></button>
															</td>
														</tr>
														<?php
														}
													}
												}
			                        			?>
				                        		</tbody>
				                        	</table>
			                        	</div>
		                        	</div>
		                        </div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="form-group">
		                        	<div class="col-md-12">
		                        		<div class="table-responsive">
			                        		<table class="table table-bordered">
				                        		<thead>
				                        			<tr>
				                        				<th width="10%">#</th>
				                        				<th width="10%">Machinery Requirement</th>
				                        				<th width="10%">No's</th>
				                        				<th width="10%">SMV</th>
				                        			</tr>
				                        		</thead>
				                        		<tbody class="machineryDetailsTBody">
			                        			<?php
			                        			if(count($machinaryRequirements) > 0)
												{
													$cnt = 0;
													foreach($machinaryRequirements as $row)
													{
														$cnt++;
													?>
													<tr class="machineryDetailsTR" machinaryId="<?php echo $row->id; ?>">
														<td>
															<input type="checkbox" class="form-control onCheckMachinaryField onCheckMachinaryField_<?php echo $row->id; ?>" machinaryId="<?php echo $row->id; ?>" />
														</td>
														<td class="mc_machineryRequirement_<?php echo $row->id; ?>">
															<?php echo $row->machineryname; ?>
														</td>
														<td>
															<input type="text" class="form-control numeric mc_numbers_<?php echo $row->id; ?>" rowNo="<?php echo $cnt; ?>" placeholder="Numbers" value="" disabled="">
														</td>
														<td>
															<input type="text" class="form-control numeric mc_smv_<?php echo $row->id; ?>" rowNo="<?php echo $cnt; ?>" placeholder="SMV" value="" disabled="">
														</td>
													</tr>
													<?php
													}
												}
			                        			?>
				                        		</tbody>
				                        	</table>
			                        	</div>
			                        	<div class="row">
			                        		<div class="col-md-6">
			                        			<div class="form-group">
			                        				<label class="col-sm-5 control-label">
														Total Numbers&nbsp;<span style="color: red;">*</span>
													</label>
						                            <div class="col-sm-7">
						                                <input type="text" class="form-control numeric" id="mc_TotalNumbers" name="mc_TotalNumbers" placeholder="Total Numbers" disabled="" value="<?php echo $mc_TotalNumbers; ?>" />
						                            </div>
			                        			</div>
			                        		</div>
			                        		<div class="col-md-6">
			                        			<div class="form-group">
			                        				<label class="col-sm-5 control-label">
														Total SMV&nbsp;<span style="color: red;">*</span>
													</label>
						                            <div class="col-sm-7">
						                                <input type="text" class="form-control numeric" id="mc_TotalSMV" name="mc_TotalSMV" placeholder="Total SMV" disabled="" value="<?php echo $mc_TotalSMV; ?>" />
						                            </div>
			                        			</div>
			                        		</div>
			                        	</div>
		                        	</div>
		                        </div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="form-group">
		                        	<div class="col-md-12">
		                        		<div class="table-responsive">
			                        		<table class="table table-bordered">
				                        		<thead>
				                        			<tr>
				                        				<th width="10%">#</th>
				                        				<th width="20%">Manual Work</th>
				                        				<th width="10%">No's</th>
				                        				<th width="10%">SMV</th>
				                        			</tr>
				                        		</thead>
				                        		<tbody class="manualWorkDetailsTBody">
			                        			<?php
			                        			if(count($manualWorkRequirements) > 0)
												{
													$cnt = 0;
													foreach($manualWorkRequirements as $row)
													{
														$cnt++;
													?>
													<tr class="manualWorkDetailsTR" manualWorkId="<?php echo $row->id; ?>">
														<td>
															<input type="checkbox" class="form-control onCheckManualWorkField onCheckManualWorkField_<?php echo $row->id; ?>" manualWorkId="<?php echo $row->id; ?>" />
														</td>
														<td><?php echo $row->manualworkname; ?></td>
														<td>
															<input type="text" class="form-control numeric onBlurManualWorkFields mn_numbers_<?php echo $row->id; ?>" placeholder="Numbers" value="<?php echo $row->numbers; ?>">
														</td>
														<td>
															<input type="text" class="form-control numeric onBlurManualWorkFields mn_smv_<?php echo $row->id; ?>" manualWorkId="<?php echo $row->id; ?>" placeholder="SMV" value="">
														</td>
													</tr>
													<?php
													}
												}
			                        			?>
				                        		</tbody>
				                        	</table>
			                        	</div>
										<div class="row">
			                        		<div class="col-md-6">
			                        			<div class="form-group">
			                        				<label class="col-sm-5 control-label">
														Total Numbers&nbsp;<span style="color: red;">*</span>
													</label>
						                            <div class="col-sm-7">
						                                <input type="text" class="form-control numeric" id="mn_TotalNumbers" name="mn_TotalNumbers" placeholder="Total Numbers" disabled="" value="<?php echo $mn_TotalNumbers; ?>" />
						                            </div>
			                        			</div>
			                        		</div>
			                        		<div class="col-md-6">
			                        			<div class="form-group">
			                        				<label class="col-sm-5 control-label">
														Total SMV&nbsp;<span style="color: red;">*</span>
													</label>
						                            <div class="col-sm-7">
						                                <input type="text" class="form-control numeric" id="mn_TotalSMV" name="mn_TotalSMV" placeholder="Total SMV" disabled="" value="<?php echo $mn_TotalSMV; ?>" />
						                            </div>
			                        			</div>
			                        		</div>
			                        	</div>
		                        	</div>
		                        </div>
	                    	</div>
	                        <div class="row">
	                    		<div class="col-md-12">
			                        <div class="form-group">
			                            <div class="col-sm-offset-4 col-sm-8">
			                                <button type="submit" class="btn btn-success">
												<?php
												if($bulletinId > 0)
												{
													echo 'Update';
												}
												else
												{
													echo 'Create';
												}
												?>
											</button>
											<button type="reset" class="btn btn-info resetBtn">
												Reset
											</button>
			                            </div>
			                        </div>
			                    </div>
			                </div>
	                    </form>
	                </div>
	            </div>
			</div>
        </div>
    </div>
</div>

<div id="operationsDiv" style="display: none;"><?php echo json_encode($operations); ?></div>
<div id="machinaryRequirementsDiv" style="display: none;"><?php echo json_encode($machinaryRequirements); ?></div>

<script>
	
	var deleted_Operation_RowsCount = 0;
	
	var operations = $("#operationsDiv").html();
	operations = JSON.parse(operations);
	
	var machinaryRequirements = $("#machinaryRequirementsDiv").html();
	machinaryRequirements = JSON.parse(machinaryRequirements);
	
	$(document).ready(function()
	{
		$('#example').dataTable();
		$('select').select2();
		$('.numeric').numeric();
		
		var bulletinId = '<?php echo $bulletinId; ?>';
		if(bulletinId == "")
		{
			addNew_Operation_Row();
		}
		else
		{
			$("#styleId").select2('val','<?php echo $styleId; ?>');
			
			var machineryDtls = '<?php echo json_encode($machineryDtls); ?>';
			machineryDtls = JSON.parse(machineryDtls);
			
			var manualWorkDtls = '<?php echo json_encode($manualWorkDtls); ?>';
			manualWorkDtls = JSON.parse(manualWorkDtls);
			
			setMachinaryFields(machineryDtls);
			setManualFields(manualWorkDtls);
		}
	});
	
	function setMachinaryFields(machineryDtls)
	{
		for(var n=0; n<machineryDtls.length; n++)
		{
			$(".onCheckMachinaryField_"+machineryDtls[n].machinery_requirement).prop("checked",true);
			$(".mc_numbers_"+machineryDtls[n].machinery_requirement).val(machineryDtls[n].numbers);
			$(".mc_smv_"+machineryDtls[n].machinery_requirement).val(machineryDtls[n].smv);
		}
	}
	
	function setManualFields(manualWorkDtls)
	{
		for(var n=0; n<manualWorkDtls.length; n++)
		{
			$(".onCheckManualWorkField_"+manualWorkDtls[n].manualwork).prop("checked",true);
			$(".mn_numbers_"+manualWorkDtls[n].manualwork).val(manualWorkDtls[n].numbers);
			$(".mn_smv_"+manualWorkDtls[n].manualwork).val(manualWorkDtls[n].smv);
		}
	}
	
	$(".newEntry").click(function()
	{
		$("#listDetails").css('display', 'none');
		$("#entryDetails").css('display', 'block');
	});
	
	$(document).on('click','.addOperationRow',function()
	{
		addNew_Operation_Row();
	});
	
	function addNew_Operation_Row()
	{
		var rowNo = $(".operationDetailsTBody tr").length;
		rowNo = parseInt(rowNo) + parseInt(deleted_Operation_RowsCount) + 1;
		
		if(rowNo > 0)
		{
			var str = '';
		
			str += '<tr class="operationDetailsTR" rowNo="'+parseInt(rowNo)+'">';
			str += '<td>';
			str += '<select class="form-control op_operationId_'+parseInt(rowNo)+'" style="width: 100%;" data-placeholder="Select">';
			str += '<option value=""></option>';
			for(var k=0; k<operations.length; k++)
			{
				str += '<option value="'+operations[k].id+'"';
				str += '>'+operations[k].operationname+'</option>';
			}
			str += '</select>';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control op_frequency_'+parseInt(rowNo)+'" placeholder="Frequency" value="">';
			str += '</td>';
			str += '<td>';
			str += '<select class="form-control op_machine_'+parseInt(rowNo)+'" style="width: 100%;" data-placeholder="Select">';
			str += '<option value=""></option>';
			for(var k=0; k<machinaryRequirements.length; k++)
			{
				str += '<option value="'+machinaryRequirements[k].id+'"';
				str += '>'+machinaryRequirements[k].machineryname+'</option>';
			}
			str += '</select>';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurOperationFields op_smv_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="SMV" value="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control op_noOfWorkers_'+parseInt(rowNo)+'" placeholder="No. Of Workers" value="" disabled="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control numeric onBlurOperationFields op_balancedWorkers_'+parseInt(rowNo)+'" rowNo="'+parseInt(rowNo)+'" placeholder="Balanced Workers" value="" disabled="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control op_secPerUnit_'+parseInt(rowNo)+'" placeholder="Sec / Unit" value="" disabled="">';
			str += '</td>';
			str += '<td>';
			str += '<input type="text" class="form-control op_folders_'+parseInt(rowNo)+'" placeholder="Folders / Clips / Guides" value="">';
			str += '</td>';
			str += '<td>';
			str += '<button type="button" title="Delete" class="btn btn-danger btn-xs btn-perspective delOperationDtl"><i class="fa fa-close"></i></button>';
			str += '</td>';
			str += '</tr>';
			
			$(".operationDetailsTBody").append(str);
			
			$("select").select2();
			$(".numeric").numeric();
		}
	}
	
	$(document).on('click','.delOperationDtl',function()
	{
		var bool = confirm("Are you sure want to Remove this Operation Detail?");
		if(bool == true)
		{
			deleted_Operation_RowsCount++;
			$(this).closest('tr').remove();
		}
	});
	
	$("#operationBulletinForm").submit(function(e)
	{
		e.preventDefault();
		
		var bulletinId = '<?php echo $bulletinId; ?>';
		
		var styleId = $("#styleId").val();
		var stdNoOfWorkStations = $("#stdNoOfWorkStations").val();
		var stdNoOfOperators = $("#stdNoOfOperators").val();
		var stdNoOfHelpers = $("#stdNoOfHelpers").val();
		var totalSAM = $("#totalSAM").val();
		var machineSAM = $("#machineSAM").val();
		var manualSAM = $("#manualSAM").val();
		var possibleDailyOutput = $("#possibleDailyOutput").val();
		var expectedPeakEfficiency = $("#expectedPeakEfficiency").val();
		var expectedOutput = $("#expectedOutput").val();
		var expectedAvgEfficiency = $("#expectedAvgEfficiency").val();
		var expectedDailyOutput = $("#expectedDailyOutput").val();
		var avgOutputPerMachine = $("#avgOutputPerMachine").val();
		
		var mc_TotalNumbers = $("#mc_TotalNumbers").val();
		var mc_TotalSMV = $("#mc_TotalSMV").val();
		
		var mn_TotalNumbers = $("#mn_TotalNumbers").val();
		var mn_TotalSMV = $("#mn_TotalSMV").val();
		
		var operationDtlArr = [];
		var machineryDtlArr = [];
		var manualWorkDtlArr = [];
		
		var isError_Operation = false;
		var isError_Machinery = true;
		var isError_ManualWork = true;
		
		$(".operationDetailsTBody tr.operationDetailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			
			var operationId = $(".op_operationId_"+rowNo).val();
			var frequency = $(".op_frequency_"+rowNo).val();
			var machine = $(".op_machine_"+rowNo).val();
			var smv = $(".op_smv_"+rowNo).val();
			var noOfWorkers = $(".op_noOfWorkers_"+rowNo).val();
			var balancedWorkers = $(".op_balancedWorkers_"+rowNo).val();
			var secPerUnit = $(".op_secPerUnit_"+rowNo).val();
			var folders = $(".op_folders_"+rowNo).val();
			
			if(operationId > 0 && frequency > 0 && machine != "" && smv > 0 && noOfWorkers > 0 && balancedWorkers > 0 && secPerUnit > 0)
			{
				var cri = {};
				cri["operationId"] = operationId;
				cri["frequency"] = frequency;
				cri["machine"] = machine;
				cri["smv"] = smv;
				cri["noOfWorkers"] = noOfWorkers;
				cri["balancedWorkers"] = balancedWorkers;
				cri["secPerUnit"] = secPerUnit;
				cri["folders"] = folders;
				
				operationDtlArr.push(cri);
			}
			else
			{
				isError_Operation = true;
			}
		});
		
		$(".machineryDetailsTBody tr.machineryDetailsTR").each(function()
		{
			var rowNo = $(this).attr('machinaryId');
			
			var machineryRequirement = $(".onCheckMachinaryField_"+rowNo).is(":checked");
			var numbers = $(".mc_numbers_"+rowNo).val();
			var smv = $(".mc_smv_"+rowNo).val();
			
			if(machineryRequirement)
			{
				isError_Machinery = false;
				var cri = {};
				cri["machineryRequirement"] = rowNo;
				cri["numbers"] = numbers;
				cri["smv"] = smv;
				
				machineryDtlArr.push(cri);
			}
		});

		$(".manualWorkDetailsTBody tr.manualWorkDetailsTR").each(function()
		{
			var rowNo = $(this).attr('manualWorkId');
			
			var manualWork = $(".onCheckManualWorkField_"+rowNo).val();
			var numbers = $(".mn_numbers_"+rowNo).val();
			var smv = $(".mn_smv_"+rowNo).val();
			
			if(manualWork)
			{
				isError_ManualWork = false;
				
				var cri = {};
				cri["manualWork"] = rowNo;
				cri["numbers"] = numbers;
				cri["smv"] = smv;
				
				manualWorkDtlArr.push(cri);
			}
		});
		
		if(isError_Operation)
		{
			alert('Please Fill All Operation Details.');
			return;
		}
		if(isError_Machinery)
		{
			alert('Please Fill All Machinery Details.');
			return;
		}
		if(isError_ManualWork)
		{
			alert('Please Fill All Manual Work Details.');
			return;
		}
		
		if(styleId > 0 && operationDtlArr.length > 0 && machineryDtlArr.length > 0 && manualWorkDtlArr.length > 0)
		{
			$("#responseMsg").html('');
			
			if('<?php echo $this->session->userdata("userid"); ?>' > 0)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"bulletinId" : bulletinId,
					"styleId" : styleId,
					"stdNoOfWorkStations" : stdNoOfWorkStations,
					"stdNoOfOperators" : stdNoOfOperators,
					"stdNoOfHelpers" : stdNoOfHelpers,
					"totalSAM" : totalSAM,
					"machineSAM" : machineSAM,
					"manualSAM" : manualSAM,
					"possibleDailyOutput" : possibleDailyOutput,
					"expectedPeakEfficiency" : expectedPeakEfficiency,
					"expectedOutput" : expectedOutput,
					"expectedAvgEfficiency" : expectedAvgEfficiency,
					"expectedDailyOutput" : expectedDailyOutput,
					"avgOutputPerMachine" : avgOutputPerMachine,
					
					"mc_TotalNumbers" : mc_TotalNumbers, 
					"mc_TotalSMV" : mc_TotalSMV, 
					
					"mn_TotalNumbers" : mn_TotalNumbers, 
					"mn_TotalSMV" : mn_TotalSMV, 
					
					"operationDtlArr" : JSON.stringify(operationDtlArr), 
					"machineryDtlArr" : JSON.stringify(machineryDtlArr), 
					"manualWorkDtlArr" : JSON.stringify(manualWorkDtlArr)
				};
				req.url = "admin/saveOperationBulletin";
				RequestHandler(req, showResponse);
			}
			else
			{
				alert('Your Session Has Been Expired. Please Login..');
				location.href = '<?php echo base_url(); ?>';
				return;
			}
		}
		else
		{
			var str = '';
			
			str += '<div role="alert" class="alert alert-danger">';
			str += 'OOPS! Please Fill All Details.';
			str += '</div>';

			$("#responseMsg").html(str);
		}
	});
	
	$(".editEntry").click(function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			location.href = '<?php echo base_url(); ?>admin/operationbulletin/'+entryId;
		}
	});
	
	$(".delEntry").on("click",function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			var bool = confirm("Are You Sure Want To Remove This Entry?");
			if(bool)
			{
				var req = new Request();
				req.data = 
				{
					"menuId" : '<?php echo $menuId; ?>', 
					"entryId" : entryId, 
					"tableName" : "operationbulletin_hdr", 
					"columnName" : "id"
				};
				req.url = "admin/delEntry";
				RequestHandler(req, showResponse);
			}
		}
		else
		{
			return;
		}
	});
	
	$(".printEntry").click(function()
	{
		var entryId = $(this).attr('entryId');
		if(entryId > 0)
		{
			window.open('<?php echo base_url(); ?>admin/operationbulletinprint/'+entryId);
		}
	});
	
	function showResponse(data)
	{
		data = JSON.parse(data);
		
		var isError = data.isError;
		var msg = data.msg;
		
		var str = '';
		var redirectURL = '';
		
		if(isError)
		{
			str += '<div role="alert" class="alert alert-danger">';
			str += 'OOPS! ' + msg;
			str += '</div>';
		}
		else
		{
			str += '<div role="alert" class="alert alert-success">';
			str += 'WELL DONE! ' + msg;
			str += '</div>';
			
			redirectURL = '<?php echo base_url(); ?>admin/operationbulletin';
		}
		$("#responseMsg").html(str);
		if(!isError)
		{
			setTimeout(function()
			{
				location.href = redirectURL;
			},1000);
		}
	}
	
	$(".resetBtn").click(function()
	{
		$("#responseMsg").html('');
		location.href = '<?php echo base_url(); ?>admin/operationbulletin/';
	});
	
	//Calculations Starts Here
	
	$(document).on('blur','.onBlurOperationFields',function()
	{
		calculateNumberOfWorkers();
	});
	
	function calculateNumberOfWorkers()
	{
		var expectedOutput = $("#expectedOutput").val();
		
		$(".operationDetailsTBody tr.operationDetailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			if(rowNo > 0)
			{
				var smv = $(".op_smv_"+rowNo).val();
				
				var secPerUnit = 0;
				var noOfWorkers = 0;
				if(smv > 0)
				{
					noOfWorkers = (parseFloat(smv)*1.2)/(480/parseFloat(expectedOutput ? expectedOutput : 0));
					
					$(".op_noOfWorkers_"+rowNo).val(parseFloat(noOfWorkers).toFixed(2));
					
					var balancedWorkers = Math.round($(".op_noOfWorkers_"+rowNo).val());
					$(".op_balancedWorkers_"+rowNo).val(parseFloat(balancedWorkers).toFixed(2));
					
					secPerUnit = parseFloat(smv ? smv : 0) * 60;
					$(".op_secPerUnit_"+rowNo).val(parseFloat(secPerUnit).toFixed(2));
				}
			}
		});
		
		calculateMachinaryRequirements();
		calculateManualSAM();
	}
	
	$(document).on('click','.onCheckManualWorkField',function()
	{
		calculateManualFieldDetails();
	});
	
	$(document).on('click','.onBlurManualWorkFields',function()
	{
		calculateManualFieldDetails();
	});
	
	function calculateManualFieldDetails()
	{
		var totalNumbers = 0;
		var totalSMV = 0;
		
		$(".manualWorkDetailsTBody tr.manualWorkDetailsTR").each(function()
		{
			var rowNo = $(this).attr('manualWorkId');
			var numbers = $(".mn_numbers_"+rowNo).val();
			var smv = $(".mn_smv_"+rowNo).val();
			
			totalNumbers += parseFloat(numbers ? numbers : 0);
			totalSMV += parseFloat(smv ? smv : 0);
		});
		
		$("#mn_TotalNumbers").val(parseFloat(totalNumbers ? totalNumbers : 0).toFixed(2));
		$("#mn_TotalSMV").val(parseFloat(totalSMV ? totalSMV : 0).toFixed(2));
	}
	
	$(".calculateWorkStations").blur(function()
	{
		var stdNoOfOperators = $("#stdNoOfOperators").val();
		var stdNoOfHelpers = $("#stdNoOfHelpers").val();
		
		var stdNoOfWorkStations = parseFloat(stdNoOfOperators ? stdNoOfOperators : 0) + parseFloat(stdNoOfHelpers ? stdNoOfHelpers : 0);
		
		$("#stdNoOfWorkStations").val(parseFloat(stdNoOfWorkStations ? stdNoOfWorkStations : 0).toFixed(2));
	});
	
	$(".calculateExpectedDailyOutput").blur(function()
	{
		calculateExpectedDailyOutput();
	});
	
	function calculateExpectedDailyOutput()
	{
		var possibleDailyOutput = $("#possibleDailyOutput").val();
		var expectedAvgEfficiency = $("#expectedAvgEfficiency").val();
		
		var expectedDailyOutput = parseFloat(possibleDailyOutput ? possibleDailyOutput : 0) * (parseFloat(expectedAvgEfficiency ? expectedAvgEfficiency : 0)/100);
		$("#expectedDailyOutput").val(parseFloat(expectedDailyOutput ? expectedDailyOutput : 0).toFixed(2));
		
		calculateAvgOutputPerMachine();
	}
	
	function calculateAvgOutputPerMachine()
	{
		var stdNoOfOperators = $("#stdNoOfOperators").val();
		var expectedDailyOutput = $("#expectedDailyOutput").val();
		
		var avgOutputPerMachine = parseFloat(expectedDailyOutput ? expectedDailyOutput : 0)/parseFloat(stdNoOfOperators ? stdNoOfOperators : 0);
		$("#avgOutputPerMachine").val(parseFloat(avgOutputPerMachine ? avgOutputPerMachine : 0).toFixed(2));
	}

	function calculatePossibleDailyOutput()
	{
		var stdNoOfOperators = $("#stdNoOfOperators").val();
		var machineSAM = $("#machineSAM").val();
		
		var possibleDailyOutput = (480/parseFloat(machineSAM ? machineSAM : 0))*parseFloat(stdNoOfOperators ? stdNoOfOperators : 0);
		$("#possibleDailyOutput").val(Math.ceil(parseFloat(possibleDailyOutput ? possibleDailyOutput : 0).toFixed(2)));
		
		calculateExpectedOutput();
		calculateExpectedDailyOutput();
	}
	
	$(".calculateExpectedOutput").blur(function()
	{
		calculateExpectedOutput();
	});
	
	function calculateExpectedOutput()
	{
		var possibleDailyOutput = $("#possibleDailyOutput").val();
		var expectedPeakEfficiency = $("#expectedPeakEfficiency").val();
		
		var expectedOutput = parseFloat(possibleDailyOutput ? possibleDailyOutput : 0) * (parseFloat(expectedPeakEfficiency ? expectedPeakEfficiency : 0)/100);
		$("#expectedOutput").val(parseFloat(expectedOutput ? expectedOutput : 0).toFixed(2));
		calculateNumberOfWorkers();
	}
	
	function calculateManualSAM()
	{
		var tempArr = [];
		$(".operationDetailsTR").each(function()
		{
			var rowNo = $(this).attr('rowNo');
			if(rowNo > 0)
			{
				var smv = $(".op_smv_"+rowNo).val();
				if(smv > 0)
				{
					tempArr.push(smv);
				}
			}
		});
		
		var curSMV = tempArr.min();

		$("#manualSAM").val(curSMV ? curSMV : 0);
		
		calculateTotalSAM();
	}
	
	Array.prototype.min = function()
	{
		return Math.min.apply(null, this);
	};
	
	function calculateTotalSAM()
	{
		var machineSAM = $("#machineSAM").val();
		var manualSAM = $("#manualSAM").val();
		
		var totalSAM = parseFloat(machineSAM ? machineSAM : 0) + parseFloat(manualSAM ? manualSAM : 0);
		$("#totalSAM").val(parseFloat(totalSAM ? totalSAM : 0).toFixed(2));
	}
	
	$(document).on('click','.onCheckMachinaryField',function()
	{
		calculateMachinaryRequirements();
		calculateMachinaryFieldDetails();
	});
	
	function calculateMachinaryRequirements()
	{
		$(".machineryDetailsTR").each(function()
		{
			var machinaryId = $(this).attr("machinaryId");
			var machineryRequirement = $(".mc_machineryRequirement_"+machinaryId).text();
			machineryRequirement = machineryRequirement.trim();
			
			var totalNoOfWorkers = 0;
			var totalSMV = 0;
			
			$(".operationDetailsTR").each(function()
			{
				var op_rowNo = $(this).attr("rowNo");
				var op_machine = $(".op_machine_"+op_rowNo).select2('data')[0]['text'];
				op_machine = op_machine.trim();
				
				if(machineryRequirement.toLowerCase() === op_machine.toLowerCase())
				{
					var op_smv = $(".op_smv_"+op_rowNo).val();
					var op_balancedWorkers = $(".op_balancedWorkers_"+op_rowNo).val();
					
					totalNoOfWorkers += parseFloat(op_balancedWorkers ? op_balancedWorkers : 0);
					totalSMV += parseFloat(op_smv ? op_smv : 0);
				}
			});
			
			$(".mc_numbers_"+machinaryId).val(parseFloat(totalNoOfWorkers).toFixed(2));
			$(".mc_smv_"+machinaryId).val(parseFloat(totalSMV).toFixed(2));
		});
	}
	
	function calculateMachinaryFieldDetails()
	{
		var totalNumbers = 0;
		var totalSMV = 0;
		
		$(".machineryDetailsTBody tr.machineryDetailsTR").each(function()
		{
			var machinaryId = $(this).attr('machinaryId');
			if($(".onCheckMachinaryField_"+machinaryId).is(":checked"))
			{
				var numbers = $(".mc_numbers_"+machinaryId).val();
				var smv = $(".mc_smv_"+machinaryId).val();
				
				totalNumbers += parseFloat(numbers ? numbers : 0);
				totalSMV += parseFloat(smv ? smv : 0);
			}
		});
		
		$("#mc_TotalNumbers").val(parseFloat(totalNumbers ? totalNumbers : 0).toFixed(2));
		$("#mc_TotalSMV").val(parseFloat(totalSMV ? totalSMV : 0).toFixed(2));
		
		$("#machineSAM").val(parseFloat(totalSMV ? totalSMV : 0).toFixed(2));
		calculatePossibleDailyOutput();
		calculateTotalSAM();
	}
</script>