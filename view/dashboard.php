<?php
defined( 'validSession' ) or die( 'Restricted access' ); 
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Dashboard
    <small></small>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
</ol>
</section>
<br>
<!-- Main content -->
<div class="box-body">
  <div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-info"></i> Alert!</h4>
    Welcome to Marketing PT AKI &#x1F609;
  </div>
  <div class="box-header">
          <div class="box box-solid">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-6">
                <div class="box box-danger">
                  <div class="box-header with-border">
                    <h3 class="box-title">Data SPH</h3>

                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  <div class="box-body chart-responsive">
                    <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Area Chart SPH</h3>

                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  <div class="box-body">
                    <div class="chart">
                      <canvas id="areaChart" style="height:250px"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="box box-danger">
                  <div class="box-header with-border">
                    <h3 class="box-title">Data Affiliate</h3>

                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  <div class="box-body chart-responsive">
                    <div class="chart" id="aff-chart" style="height: 300px; position: relative;"></div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>

<script src="./plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="./plugins/chartjs/Chart.min.js"></script>

<script language="JavaScript" TYPE="text/javascript">
  $(function () {
    $.post("function/ajax_function.php",{ fungsi: "getcountSPH"},function(data)
    {
      var donut = new Morris.Donut({
        element: 'sales-chart',
        resize: true,
        colors: ["#F45091", "#EA40A7", "#D420D3", "#BF00FF"],
        data: [
        {label: "Mr. Reza", value: data.reza},
        {label: "Mr. Antok", value: data.antok},
        {label: "Mr. Agus", value: data.agus},
        {label: "Mrs. Tina", value: data.tina}
        ],
        hideHover: 'auto'
      });
    },"json"); 
    $.post("function/ajax_function.php",{ fungsi: "getcountAffiliate"},function(data)
    {
      var donut = new Morris.Donut({
        element: 'aff-chart',
        resize: true,
        colors: ["#F45091", "#EA40A7", "#D420D3", "#BF00FF", "#833ab4", "#fd1d1d", "#fcb045", "#FEAC5E", "#C779D0", "#fcb045", "#6441A5", "#2a0845"],
        data: [
        {label: "Web Qoobah Official", value: data.office},
        {label: "Web Contractor", value: data.contr},
        {label: "Representative", value: data.repre},
        {label: "Offline", value: data.offline},
        {label: "Edy", value: data.edy},
        {label: "Ibnu", value: data.ibnu},
        {label: "Sigit", value: data.sigit},
        {label: "Isaq", value: data.isaq},
        {label: "Fendy", value: data.fendy},
        {label: "Habibi", value: data.habibi},
        {label: "Rizal", value: data.rizal},
        {label: "Bekasi", value: data.bekasi}
        ],
        hideHover: 'auto'
      });
    },"json"); 

    //- AREA CHART -
    //--------------
    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    
    $.post("function/ajax_function.php",{ fungsi: "getcountSPHm"},function(data)
    {
      var areaChart = new Chart(areaChartCanvas);
      var areaChartData = {
        labels: ["January", "February", "March", "April", "May", "June", "July","August","September","October","November","December"],
        datasets: [
        {
          label: "Digital Goods",
          fillColor: "#00c0ef",
          strokeColor: "rgba(60,141,188,0.8)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: [data.jan,data.feb,data.maret,data.april,data.mei,data.jun,data.jul,data.agus,data.sep,data.okt,data.nov,data.des]
        }
        ]
      };
      var areaChartOptions = {
        //Boolean - If we should show the scale at all data: [data.jan, data.feb, data.maret, data.april, data.jun, data.jul, data.agus, data.sep, data.okt, data.nov, data.des]
        showScale: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: false,
        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - Whether the line is curved between points
        bezierCurve: true,
        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.3,
        //Boolean - Whether to show a dot for each point
        pointDot: false,
        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,
        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,
        //Boolean - Whether to fill the dataset with a color
        datasetFill: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true
      };
      areaChart.Line(areaChartData, areaChartOptions);
    },"json"); 

  });
</script>