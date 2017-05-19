<script src="<?php echo base_url(); ?>assets/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/highcharts-3d.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/exporting.js"></script>

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
		$("#pieceLogChartDiv").css('display','block');
		
		var xAxisData = [];
		var yAxisData = [];
		
		var inCntArr = [];
		var outCntArr = [];
			
		for(var n=0; n<lineWiseEfficiency.length; n++)
		{
			xAxisData.push(lineWiseEfficiency[n].lineid);
			
			var cri = {};
			cri["y"] = parseFloat(lineWiseEfficiency[n].input_cnt);
			cri["color"] = getRandomColor(1, 'Str');
			inCntArr.push(cri);
			
			var cri = {};
			cri["y"] = parseFloat(lineWiseEfficiency[n].output_cnt);
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
		
		/*yAxisData = [{
				name: 'John',
				data: [{y: 5, color: "red"}, {y: 5, color: "yellow"}, {y: 5, color: "pink"}, {y: 5, color: "orange"}, {y: 5, color: "violet"}],
				stack: 'male'
				}, {
				name: 'Jane',
				data: [2, 5, 6, 2, 1],
				stack: 'female'
				}];*/
		
		$('#pieceLogsMovementsContainer').highcharts(
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
			series: yAxisData
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
		var colorArr = getRandomColor(lineWiseEfficiency.length, 'Arr');
		
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

function getRandomColor(cnt = 2, returnType = 'Arrs')
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