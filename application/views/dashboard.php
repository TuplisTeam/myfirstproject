<!--<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>-->

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
				<?php
				if($this->session->userdata('usertype') != "admin")
				{
					echo ' - '.$this->session->userdata('store').' Section';
				}
				?>
			</a>
		</h3>
    </div>
	<div id="main-wrapper">
		<div class="row myBoxes">
            <?php
			if($this->session->userdata('usertype') != "admin")
			{
				if($this->session->userdata('store') == "Cutting")
				{
				?>
				<div class="col-md-3 myMenu" pageName="scan">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Scan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="barcodegeneration">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Generate
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="deliverynote">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Delivery Challan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="receivedgoods">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Received Goods
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Attendance
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="skillmatrixreport">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Skill Matrix Report
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Production Tracking Report
							</h2>
		                </div>
		        	</div>
				</div>
				<?php
				}
				if($this->session->userdata('store') == "Sewing")
				{
				?>
				<div class="col-md-3 myMenu" pageName="assemblyloading">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Line Assembly Loading
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="hourlyproductionreport">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Hourly Production Report (Employee Wise)
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="hourlyproduction_linewise_report">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Hourly Production Report (Line Wise)
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Delivery Challan (Send / Receive)
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Attendance
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="skillmatrixreport">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Skill Matrix Report
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="individualperformancereport">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Individual Performance Report
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Nowork / Breakdown Report
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="operationbulletin">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Operation Bulletin
							</h2>
		                </div>
		        	</div>
				</div>
				<?php
				}
				if($this->session->userdata('store') == "Storage_Procurement")
				{
				?>
				<div class="col-md-3 myMenu" pageName="scan">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Scan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="barcodegeneration">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Generate
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="deliverynote">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Delivery Challan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Attendance
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="rackdisplay">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Rack Information
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Pending Report
							</h2>
		                </div>
		        	</div>
				</div>
				<?php
				}
				if($this->session->userdata('store') == "Ironing_Packing")
				{
				?>
				<div class="col-md-3 myMenu" pageName="scan">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Scan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="barcodegeneration">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Generate
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="deliverynote">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Delivery Challan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Attendance
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="skillmatrixreport">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Skill Matrix Report
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Production Report
							</h2>
		                </div>
		        	</div>
				</div>
				<?php
				}
				if($this->session->userdata('store') == "Printing")
				{
				?>
				<div class="col-md-3 myMenu" pageName="scan">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Scan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="barcodegeneration">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Generate
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="deliverynote">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Delivery Challan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Attendance
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="skillmatrixreport">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Skill Matrix Report
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Production Report
							</h2>
		                </div>
		        	</div>
				</div>
				<?php
				}
				if($this->session->userdata('store') == "Embroidary")
				{
				?>
				<div class="col-md-3 myMenu" pageName="scan">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Scan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="barcodegeneration">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Barcode Generate
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="deliverynote">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Delivery Challan
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Attendance
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="skillmatrixreport">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Skill Matrix Report
							</h2>
		                </div>
		        	</div>
				</div>
				<div class="col-md-3 myMenu" pageName="">
					<div class="panel panel-green" style="cursor: pointer;">
		                <div class="panel-body">
		                    <h2 class="no-m m-b-md text-center">
								Production Report
							</h2>
		                </div>
		        	</div>
				</div>
				<?php
				}
			}
			else
			{
			?>
			<div class="col-md-12">
				<div id="pieceLogsMovementsContainer" style="height: 400px;"></div>
			</div>
			<?php
			}
			?>
		</div>
    </div>
</div>

<div id="pieceLogsMovementsDiv" style="display: none;"><?php echo json_encode($pieceLogsMovements); ?></div>

<script>

var pieceLogsMovements = $("#pieceLogsMovementsDiv").html();
pieceLogsMovements = JSON.parse(pieceLogsMovements);

$(document).ready(function()
{
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
});

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

$(".myMenu").click(function()
{
	var pageName = $(this).attr('pageName');
	if(pageName != "")
	{
		location.href = '<?php echo base_url(); ?>admin/'+pageName;
	}
});

</script>