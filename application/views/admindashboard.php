<script src="<?php echo base_url(); ?>assets/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/highcharts-3d.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/modules/exporting.js"></script>

<style>
	.myBoxes .col-md-3 .panel
	{
		padding: 25px;
		max-height: 135px;
	}
</style>

<div class="page-inner">
    <div class="page-title">
        <h3>
			<a href="<?php echo base_url(); ?>admin/" style="text-decoration: none;">
				Dashboard
			</a>
		</h3>
    </div>
	<div id="main-wrapper">
		<div class="row myBoxes">
			<div class="col-md-12">
				<div class="col-md-6" id="pieceLogChartDiv" style="display: none;">
					<div id="pieceLogsMovementsContainer" style="height: 400px;"></div>
				</div>
				<div class="col-md-6" id="lineEfficiencyChartDiv" style="display: none;">
					<div id="lineWiseEfficiencyContainer" style="height: 400px;"></div>
				</div>
			</div>
			<div class="col-md-12" id="noProgressDiv" style="display: none;">
				<div role="alert" class="alert alert-danger">
					OOPS! No Progress In Work Today.
				</div>
			</div>
		</div>
    </div>
</div>

<div id="pieceLogsMovementsDiv" style="display: none;"><?php echo json_encode($pieceLogsMovements); ?></div>
<div id="lineWiseEfficiencyDiv" style="display: none;"><?php echo json_encode($lineWiseEfficiency); ?></div>

<script>

$(document).ready(function()
{	
	var userType = "<?php echo $this->session->userdata('usertype'); ?>";
	if(userType == "admin")
	{
		var pieceLogsMovements = $("#pieceLogsMovementsDiv").html();
		pieceLogsMovements = JSON.parse(pieceLogsMovements);
		
		var lineWiseEfficiency = $("#lineWiseEfficiencyDiv").html();
		lineWiseEfficiency = JSON.parse(lineWiseEfficiency);
		
		if(pieceLogsMovements.length > 0 || lineWiseEfficiency.length > 0)
		{
			renderPieceLogsMovementsChart(pieceLogsMovements);
			renderLineWiseEfficiencyChart(lineWiseEfficiency);
		}
		else
		{
			$("#noProgressDiv").css('display','block');
		}
	}
});

function renderPieceLogsMovementsChart(pieceLogsMovements)
{
	if(pieceLogsMovements.length > 0)
	{
		$("#pieceLogChartDiv").css('display','block');
		
		var xAxisArr = [];
		var yAxisArr = [];
		var colorArr = getRandomColor(pieceLogsMovements.length);
		
		for(var n=0; n<pieceLogsMovements.length; n++)
		{
			xAxisArr.push(pieceLogsMovements[n].lineid + ' - ' + pieceLogsMovements[n].tablename);
			yAxisArr.push(parseFloat(pieceLogsMovements[n].cnt));
		}
		
		$('#pieceLogsMovementsContainer').highcharts(
		{
	        chart: 
			{
	            type: 'column',
				height: 350,
	            margin: 75,
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
	            text: 'Piecelog Table Hanger Moved Counts'
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
	            categories: xAxisArr,
				labels: 
				{
					useHTML: true, 
					formatter: function()
					{
						return '<div title="'+this.value+'" style="width: 60px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">'+this.value+'</div>'; 
					}
				}
	        },
	        yAxis: 
			{
	            title: 
				{
	                text: null
	            }
	        },
			legend:
			{
				enabled: false
			},
		    series: 
		    [{
		        type: 'column',
		        name: 'Pieces Moved',
		        colorByPoint: true,
				colors: colorArr,
		        data: yAxisArr
		    }]
	    });
	}
}

function renderLineWiseEfficiencyChart(lineWiseEfficiency)
{
	if(lineWiseEfficiency.length > 0)
	{
		$("#lineEfficiencyChartDiv").css('display','block');
		
		var xAxisArr = [];
		var yAxisArr = [];
		var colorArr = getRandomColor(lineWiseEfficiency.length);
		
		for(var n=0; n<lineWiseEfficiency.length; n++)
		{
			var totalWorkers = parseInt(lineWiseEfficiency[n].operators_in_line) + parseInt(lineWiseEfficiency[n].helpers_in_line);
			var lineEff = (parseInt(lineWiseEfficiency[n].output_cnt) * parseFloat(lineWiseEfficiency[n].total_sam) * 100)/(parseInt(totalWorkers) * parseInt(lineWiseEfficiency[n].producedmin));
			xAxisArr.push(lineWiseEfficiency[n].linename);
			yAxisArr.push(parseFloat(lineEff).toFixed(2));
		}
		
		$('#lineWiseEfficiencyContainer').highcharts(
		{
	        chart: 
			{
	            type: 'column',
				height: 350,
	            margin: 75,
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
	            text: 'Line Wise Efficiency'
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
	            categories: xAxisArr,
				labels: 
				{
					useHTML: true, 
					formatter: function()
					{
						return '<div title="'+this.value+'" style="width: 60px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">'+this.value+'</div>'; 
					}
				}
	        },
	        yAxis: 
			{
	            title: 
				{
	                text: null
	            }
	        },
			legend:
			{
				enabled: false
			},
		    series: 
		    [{
		        type: 'column',
		        name: 'Line Efficiency',
		        colorByPoint: true,
				colors: colorArr,
		        data: yAxisArr
		    }]
	    });
	}
}

function getRandomColor(cnt = 2)
{
	var colorArr = [];
	for(var n=0; n<cnt; n++)
	{
		var letters = '0123456789ABCDEF';
	    var color = '#';
	    for (var i = 0; i < 6; i++ )
	    {
	        color += letters[Math.floor(Math.random() * 16)];
	    }
	    colorArr.push(color);
	}
    return colorArr;
}

</script>