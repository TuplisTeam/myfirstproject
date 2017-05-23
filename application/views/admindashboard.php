<script src="<?php echo base_url(); ?>assets/plugins/jquery-counterup/jquery.counterup.min.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/highcharts-3d.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/exporting.js"></script>

<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/" style="text-decoration: none;">
				Dashboard
			</a>
		</h3>
    </div>
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
            <div class="col-lg-3 col-md-6">
                <div class="panel info-box panel-white">
                    <div class="panel-body">
                        <div class="info-box-stats">
                            <p class="counter">
                            	<?php echo $empCount; ?>
                            </p>
                            <span class="info-box-title">
                            	Working Employees
                            </span>
                        </div>
                        <div class="info-box-icon">
                            <i class="icon-users"></i>
                        </div>
                        <div class="info-box-progress">
                            <div class="progress progress-xs progress-squared bs-n">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel info-box panel-white">
                    <div class="panel-body">
                        <div class="info-box-stats">
                            <p class="counter">
                            	<?php echo $linesOperating; ?>
                            </p>
                            <span class="info-box-title">
                            	Lines Operating
                            </span>
                        </div>
                        <div class="info-box-icon">
                            <i class="icon-eye"></i>
                        </div>
                        <div class="info-box-progress">
                            <div class="progress progress-xs progress-squared bs-n">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel info-box panel-white">
                    <div class="panel-body">
                        <div class="info-box-stats">
                            <p class="counter">
                            	<?php echo $todayOpenIssues; ?>
                            </p>
                            <span class="info-box-title">
                            	Open Issues
                            </span>
                        </div>
                        <div class="info-box-icon">
                            <i class="icon-basket"></i>
                        </div>
                        <div class="info-box-progress">
                            <div class="progress progress-xs progress-squared bs-n">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel info-box panel-white">
                    <div class="panel-body">
                        <div class="info-box-stats">
                            <p class="counter">
                            	<?php echo $todayClosedIssues; ?>
                            </p>
                            <span class="info-box-title">
                            	Closed Issues
                            </span>
                        </div>
                        <div class="info-box-icon">
                            <i class="icon-envelope"></i>
                        </div>
                        <div class="info-box-progress">
                            <div class="progress progress-xs progress-squared bs-n">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-6" id="pieceLogChartDiv" style="display: none;">
					<div class="panel panel-white">
						<div class="panel-heading clearfix">
		                    <h4 class="panel-title">
		                    	Linewise Piecelog Movements
		                    </h4>
		                </div>
		                <div class="panel-body">
							<div id="pieceMovementsContainer"></div>
						</div>
					</div>
				</div>
				<div class="col-md-6" id="lineEfficiencyChartDiv" style="display: none;">
					<div class="panel panel-white">
						<div class="panel-heading clearfix">
		                    <h4 class="panel-title">
		                    	Linewise Efficiency
		                    </h4>
		                </div>
		                <div class="panel-body">
							<div id="lineWiseEfficiencyContainer"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="panel panel-white">
		                <div class="panel-heading clearfix">
		                    <h4 class="panel-title">
		                    	Linewise Piecelog Movements
		                    </h4>
		                </div>
		                <div class="panel-body">
		                	<?php
							if(count($lineWiseEfficiency) > 0)
							{
							?>
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<thead>
										<tr>
											<th>Line Name</th>
											<th>In Count</th>
											<th>Out Count</th>
											<th>WIP</th>
											<th>Line Efficiency</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach($lineWiseEfficiency as $row)
										{
											$totalWorkers = intval($row->operators_in_line) + intval($row->helpers_in_line);
											$lineEfficiency = (intval($row->output_cnt)*floatval($row->total_sam)*100)/(intval($totalWorkers)*floatval($row->producedmin));
										?>
										<tr>
											<td><?php echo $row->lineid; ?></td>
											<td><?php echo $row->input_cnt; ?></td>
											<td><?php echo $row->output_cnt; ?></td>
											<td><?php echo $row->wip; ?></td>
											<td><?php echo number_format($lineEfficiency, 2, '.', ''); ?></td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
							<?php
							}
							else
							{
							?>
							<div class="col-md-12">
								<div role="alert" class="alert alert-danger">
									OOPS! No Pieces Moved.
								</div>
							</div>
							<?php
							}
							?>
		                </div>
		            </div>
		        </div>
			</div>
	    </div>
	    
	    <div class="row">
	    	<div class="col-md-12">
	    		<div class="col-md-12">
					<div class="panel panel-white">
	                    <div class="panel-heading clearfix">
	                        <h4 class="panel-title">Issue Details</h4>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped">
		                            <thead>
		                                <tr>
		                                    <th>Line Name</th>
		                                    <th>Line Location</th>
		                                    <th>Table Assert Name</th>
		                                    <th>Table Name</th>
		                                    <th>Issue Started Time</th>
		                                    <th>Issue Closed Time</th>
		                                    <th>Issue Occured On</th>
		                                    <th>Issue Status</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                            <?php
									if(count($issueDls) > 0)
									{
										foreach($issueDls as $row)
										{
											$bgColor = '';
											if($row->issuestatus == "Closed")
											{
												$bgColor = '#95f995';//Green
											}
											if($row->issuestatus == "Active")
											{
												$bgColor = '#ff9191';//Red
											}
										?>
										<tr style="background-color: <?php echo $bgColor; ?>;">
											<td><?php echo $row->lineid; ?></td>
											<td><?php echo $row->linelocation; ?></td>
											<td><?php echo $row->table_slno; ?></td>
											<td><?php echo $row->table_name; ?></td>
											<td><?php echo $row->in_time; ?></td>
											<td><?php echo $row->out_time; ?></td>
											<td><?php echo $row->createddt; ?></td>
											<td><?php echo $row->issuestatus; ?></td>
										</tr>
										<?php
										}
									}
									else
									{
									?>
									<tr>
										<td colspan="8">
											No Issues Found.
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
	    </div>

		<div class="row" id="noProgressDiv" style="display: none;">
			<div class="col-md-12">
				<div role="alert" class="alert alert-danger">
					OOPS! No Progress In Work Today.
				</div>
			</div>
		</div>
		
    </div>
</div>

<div id="lineWiseEfficiencyDiv" style="display: none;"><?php echo json_encode($lineWiseEfficiency); ?></div>

<script>

$(document).ready(function()
{	
	var userType = "<?php echo $this->session->userdata('usertype'); ?>";
	if(userType == "admin")
	{
		$('.counter').counterUp(
		{
	        delay: 10,
	        time: 500
	    });
	    
		var lineWiseEfficiency = $("#lineWiseEfficiencyDiv").html();
		lineWiseEfficiency = JSON.parse(lineWiseEfficiency);
		
		if(lineWiseEfficiency.length > 0)
		{
			$("#pieceLogChartDiv").css('display','block');
			$("#lineEfficiencyChartDiv").css('display','block');
			
			renderPieceLogsMovementsChart(lineWiseEfficiency);
			renderLineWiseEfficiencyChart(lineWiseEfficiency);
		}
		else
		{
			$("#noProgressDiv").css('display','block');
		}
	}
});

function renderPieceLogsMovementsChart(lineWiseEfficiency)
{
	if(lineWiseEfficiency.length > 0)
	{
		var xAxisData = [];
		var yAxisData = [];
		
		var inCntArr = [];
		var outCntArr = [];
			
		for(var n=0; n<lineWiseEfficiency.length; n++)
		{
			xAxisData.push(lineWiseEfficiency[n].lineid);
			
			var cri = {};
			cri["y"] = parseInt(lineWiseEfficiency[n].input_cnt);
			cri["color"] = getRandomColor(1, 'Str');
			inCntArr.push(cri);
			
			var cri = {};
			cri["y"] = parseInt(lineWiseEfficiency[n].output_cnt);
			cri["color"] = getRandomColor(1, 'Str');
			outCntArr.push(cri);
		}
		
		var cri = {};
		cri["name"] = "In Count";
		cri["data"] = inCntArr;
		cri["stack"] = "In Count";
		yAxisData.push(cri);
		
		var cri = {};
		cri["name"] = "Out Count";
		cri["data"] = outCntArr;
		cri["stack"] = "Out Count";
		yAxisData.push(cri);
		
		$('#pieceMovementsContainer').highcharts(
		{
			chart:
			{
				type: 'column',
				options3d:
				{
					enabled: true,
					alpha: 15,
					beta: 15,
					viewDistance: 25,
					depth: 40
				}
			},
			title:
			{
				text: 'Linewise Piece Movements'
			},
			xAxis:
			{
				categories: xAxisData
			},
			yAxis:
			{
				allowDecimals: false,
				min: 0,
				title:
				{
					text: 'Linewise Piece Movements'
				}
			},
			tooltip:
			{
				headerFormat: '<b>{point.key}</b><br>',
            pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y:.0f}'
			},
			plotOptions:
			{
				column:
				{
					stacking: 'normal',
					depth: 40
				}
			},
			credits: 
			{
		      enabled: false
		  	},
			series: yAxisData
		});
	}
}

function renderLineWiseEfficiencyChart(lineWiseEfficiency)
{
	if(lineWiseEfficiency.length > 0)
	{
		var xAxisData = [];
		var yAxisData = [];
		var tempArr = [];
		
		for(var n=0; n<lineWiseEfficiency.length; n++)
		{
			var totalWorkers = parseInt(lineWiseEfficiency[n].operators_in_line) + parseInt(lineWiseEfficiency[n].helpers_in_line);
			var lineEff = (parseInt(lineWiseEfficiency[n].output_cnt) * parseFloat(lineWiseEfficiency[n].total_sam) * 100)/(parseInt(totalWorkers) * parseFloat(lineWiseEfficiency[n].producedmin));
			
			xAxisData.push(lineWiseEfficiency[n].lineid);
			
			var lineEffVal = 0;
			if(parseFloat(lineEff) > 0)
			{
				lineEffVal = parseFloat(lineEff).toFixed(2);
			}
			else
			{
				lineEffVal = null;
			}
			var cri = {};
			cri["y"] = parseFloat(lineEffVal);
			cri["color"] = getRandomColor(1, 'Str');
			tempArr.push(cri);
		}
		
		var cri = {};
		cri["name"] = "Line Efficiency";
		cri["data"] = tempArr;
		yAxisData.push(cri);
		
		$('#lineWiseEfficiencyContainer').highcharts(
		{
			chart:
			{
				type: 'column',
				options3d:
				{
					enabled: true,
					alpha: 10,
					beta: 25,
					depth: 70
				}
			},
			title:
			{
				text: 'Linewise Efficiency'
			},
			subtitle:
			{
				text: ''
			},
			plotOptions:
			{
				column:
				{
					depth: 25
				}
			},
			credits: 
			{
		      enabled: false
		  	},
			xAxis:
			{
				categories: xAxisData
			},
			yAxis:
			{
				title:
				{
					text: null
				}
			},
			series: yAxisData
	    });
	}
}

function getRandomColor(cnt = 2, returnType = 'Arr')
{
	var colorArr = [];
	var colorStr = '';
	for(var n=0; n<cnt; n++)
	{
		var letters = '0123456789ABCDEF';
	    var color = '#';
	    for (var i = 0; i < 6; i++ )
	    {
	        color += letters[Math.floor(Math.random() * 16)];
	    }
	    colorArr.push(color);
	    colorStr += ','+color;
	}
	if(returnType == "Str")
	{
		colorStr = colorStr.substr(1);
		return colorStr;
	}
	if(returnType == "Arr")
	{
		return colorArr;
	}
}

</script>