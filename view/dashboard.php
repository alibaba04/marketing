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
              <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                  <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                  <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                </ol>
                <div class="carousel-inner">
                  <div class="item active">
                    <img src="http://placehold.it/1800x700/39CCCC/ffffff&text=qoobah" alt="First slide">

                    <div class="carousel-caption">
                      
                    </div>
                  </div>
                  <div class="item">
                    <img src="http://placehold.it/1800x700/3c8dbc/ffffff&text=Mahal" alt="Second slide">

                    <div class="carousel-caption">
                      
                    </div>
                  </div>
                  <div class="item">
                    <img src="http://placehold.it/1800x700/f39c12/ffffff&text=300 M" alt="Third slide">

                    <div class="carousel-caption">
                      
                    </div>
                  </div>
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                  <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                  <span class="fa fa-angle-right"></span>
                </a>
              </div>
              <!-- <div class="col-md-6">
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
                    <h3 class="box-title">Area Chart</h3>

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
              </div> -->
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
        colors: ["#3c8dbc", "#f56954", "#00a65a", "#cbef08"],
        data: [
        {label: "Mr. Reza", value: data.reza},
        {label: "Mr. Antok", value: data.antok},
        {label: "Mr. Udin", value: data.udin},
        {label: "Mrs. Tina", value: data.tina}
        ],
        hideHover: 'auto'
      });
    },"json"); 

    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(areaChartCanvas);
    var areaChartData =areaChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July","August","September","October","November","December"],
            datasets: [
            {
              label: "Digital Goods",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [28, 48, 40, 19, 86, 27, 90]
              
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

  });
</script>