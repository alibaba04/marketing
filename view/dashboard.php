<?php
defined( 'validSession' ) or die( 'Restricted access' ); 
?>
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
<div class="box-body">
  <input type="hidden" name="kodeuser" id="kodeuser" value="<?php echo $_SESSION["my"]->id ?>">
  <div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-info"></i> Alert!</h4>
    Welcome to Marketing PT AKI &#x1F609;
  </div>
  <div class="box-header">
    <div class="box box-solid">
      <div class="box-body">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Area Chart Affiliate</h3>
            <div class="box-tools pull-right">
              <select name="cboAffiliate" id="cboAffiliate" class="form-control select2">
                <?php
                echo '<option value="Web Qoobah Official">Web Qoobah Official</option>';
                echo '<option value="Web Contractor">Web Contractor</option>';
                echo '<option value="Representative">Representative</option>';
                echo '<option value="Offline">Offline</option>';
                echo '<option value="Edy">Edy</option>';
                echo '<option value="Ibnu">Ibnu</option>';
                echo '<option value="Sigit">Sigit</option>';
                echo '<option value="Isaq">Isaq</option>';
                echo '<option value="Fendy">Fendy</option>';
                echo '<option value="Habibi">Habibi</option>';
                echo '<option value="Rizal">Rizal</option>';
                echo '<option value="Bekasi">Bekasi</option>';
                ?>
              </select>
            </div>
          </div>
          <div class="box-body">
            <div class="chart">
              <canvas id="areaChartLine" style="height:250px"></canvas>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Chart SPH <span style="text-transform:uppercase"><?php echo $_SESSION["my"]->id ?></span></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="areaChartUser" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Data SPH</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive ">
              <div class="chart " id="sales-chart" style="height: 300px; position: relative;"></div>
            </div>
          </div>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Leaderboard</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-striped">
                <tr>
                  <th style="width: 20px">#</th>
                  <th>Provinsi</th>
                  <th style="width: 40px">Qty</th>
                </tr>
                <?php 
                $q = "SELECT p.name,count(s.idSph) as jml FROM `aki_sph` s left join provinsi p on s.provinsi=p.id group by s.provinsi order by jml desc";
                $rs = new MySQLPagedResultSet($q, 10, $dbLink);
                $rowCounter=1;
                while ($query_data = $rs->fetchArray()) {
                  echo '<tr><td>'.$rowCounter.'.</td>
                  <td>'.$query_data['name'].'</td>
                  <td><span class="badge bg-red">'.$query_data['jml'].'</span></td>
                  </tr>';
                  $rowCounter++;
                }
                ?>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Chart SPH All Sales</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
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
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Leaderboard</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-striped">
                <tr>
                  <th style="width: 20px">#</th>
                  <th>Kota/Kabupaten</th>
                  <th style="width: 40px">Qty</th>
                </tr>
                <?php 
                $q = "SELECT k.name,count(s.idSph) as jml FROM `aki_sph` s left join kota k on s.kota=k.id group by s.kota order by jml desc";
                $rs = new MySQLPagedResultSet($q, 10, $dbLink);
                $rowCounter=1;
                while ($query_data = $rs->fetchArray()) {
                  echo '<tr><td>'.$rowCounter.'.</td>
                  <td>'.$query_data['name'].'</td>
                  <td><span class="badge bg-red">'.$query_data['jml'].'</span></td>
                  </tr>';
                  $rowCounter++;
                }
                ?>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="./plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="./plugins/chartjs/Chart.min.js"></script>
<script language="JavaScript" TYPE="text/javascript">

  $(function () {
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
    //- AREA CHART -
    var areaChartOptions = {
      showScale: true,
      scaleShowGridLines: false,
      scaleGridLineColor: "rgba(0,0,0,.05)",
      scaleGridLineWidth: 1,
      scaleShowHorizontalLines: true,
      scaleShowVerticalLines: true,
      bezierCurve: true,
      bezierCurveTension: 0.3,
      pointDot: false,
      pointDotRadius: 4,
      pointDotStrokeWidth: 1,
      pointHitDetectionRadius: 20,
      datasetStroke: true,
      datasetStrokeWidth: 2,
      datasetFill: true,
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      maintainAspectRatio: true,
      responsive: true
    };
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    $.post("function/ajax_function.php",{ fungsi: "getcountSPHm",user:'-'},function(data)
    {
      var areaChart = new Chart(areaChartCanvas);
      var areaChartData = {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
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
      areaChart.Line(areaChartData, areaChartOptions);
    },"json"); 

    var areaChartCanvasUser = $("#areaChartUser").get(0).getContext("2d");
    $.post("function/ajax_function.php",{ fungsi: "getcountSPHm",user:$('#kodeuser').val()},function(data)
    {
      var areaChart = new Chart(areaChartCanvasUser);
      var areaChartData = {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
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
      
      areaChart.Line(areaChartData, areaChartOptions);
    },"json");

    //area chart4
    aff()
    $("#cboAffiliate").change(function(){
      aff()
    });
    function aff(){
      $.post("function/ajax_function.php",{ fungsi: "getcountAffm",aff:$('#cboAffiliate').val()},function(data)
      {
        var areaChartCanvas4 = $('#areaChartLine').get(0).getContext('2d')
        var areaChartData = {
          labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
          datasets: [
          {
            label: data[0][0],
            fillColor: "#00c0ef",
            strokeColor: "rgba(210, 214, 222, 1)",
            pointColor: "rgba(210, 214, 222, 1)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [data[0][1],data[0][2],data[0][3],data[0][4],data[0][5],data[0][6],data[0][7],data[0][8],data[0][9],data[0][10],data[0][11],data[0][12]]
          }
          ]
        };
        var areaChart = new Chart(areaChartCanvas4);
        areaChart.Line(areaChartData, areaChartOptions);
      },"json");
    }

    
  });
</script>