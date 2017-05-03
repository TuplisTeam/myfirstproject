<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/skillmatrixreport" style="text-decoration: none;">
				Skill Matrix Report
			</a>
		</h3>
    </div>
	<div id="main-wrapper">
        <div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
	                <div class="panel-heading clearfix">
	                    <h4 class="panel-title">Skill Matrix Report</h4>
	                </div>
	                <div class="panel-body">
	                    <form class="form-horizontal" id="skillMatrixReportForm" method="POST" action="<?php echo base_url(); ?>admin/getSkillMatrixReport" target="_blank">
	                    	<div class="row">
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											From Date
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control datePicker" id="fromDate" name="fromDate" placeholder="From Date">
			                            </div>
			                        </div>
	                    		</div>
	                    		<div class="col-md-6">
	                    			<div class="form-group">
			                            <label class="col-sm-3 control-label">
											To Date
										</label>
			                            <div class="col-sm-6">
			                                <input type="text" class="form-control datePicker" id="toDate" name="toDate" placeholder="To Date">
			                            </div>
			                        </div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Employee
										</label>
			                            <div class="col-sm-6">
			                                <select class="form-control" style="width: 100%;" id="employeeId" name="employeeId">
												<option value="">All</option>
												<?php
												foreach($empDtls as $row)
												{
													echo '<option value="'.$row->id.'"';
													echo '>'.$row->empno.' - '.$row->empname.'</option>';
												}
												?>
											</select>
			                            </div>
			                        </div>
			                    </div>
			                    <!--<div class="col-md-6">
									<div class="form-group">
			                            <label class="col-sm-3 control-label">
											Filter By&nbsp;<span style="color: red;">*</span>
										</label>
			                            <div class="col-sm-6">
			                                <select class="form-control" style="display: none; width: 100%" id="filterBy" name="filterBy" required="">
												<option value="EmployeeWise">Employee Wise</option>
												<option value="OperationWise">Operation Wise</option>
											</select>
			                            </div>
			                        </div>
			                    </div>-->
			                </div>
	                        <div class="form-group">
	                            <div class="col-sm-offset-2 col-sm-10">
									<input type="checkbox" id="exportAsCSV" name="exportAsCSV" />Export As CSV&nbsp;
									<input type="hidden" id="checkValue" name="checkValue" />
	                                <button type="submit" class="btn btn-success">
										Display Report
									</button>
									<button type="reset" class="btn btn-info resetBtn">
										Reset
									</button>
	                            </div>
	                        </div>
	                    </form>
	                </div>
	            </div>
			</div>
        </div>
    </div>
</div>

<script>
	
	$(document).ready(function()
	{
		$('.datePicker').datepicker(
		{
			format: 'dd/mm/yyyy', 
			autoclose: true
		});
		$("select").select2();
	});
	
	$("#exportAsCSV").click(function()
	{
		if($("#exportAsCSV").is(':checked'))
		{
			$("#checkValue").val(1);
		}
		else
		{
			$("#checkValue").val(0);
		}
	});
	
	$(".resetBtn").click(function()
	{
		location.href = "<?php echo base_url(); ?>admin/skillmatrixreport";
	});
	
</script>