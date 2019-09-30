<? 
  require_once('dashboard.php');
    include('readfile.php');
    $lgs = new Logs();
    $qryObj = new Query();
    $dsbObj = new Dashboard(); 
    $rfObj = new ReadFile();
    $lang=strtolower($_SESSION['LANG']);
    $qryPath = "util/readquery/general/bar_chart.ini";

  $barChartQry = $qryObj->fetchQuery($qryPath,'Q001','BAR_CHART');
  $barChartQryRes = $dsbObj->getData($barChartQry); 
    
  //Bar Chart
  for($i=0;$i<sizeof($barChartQryRes);$i++) 
  {
    $jsonRes[]=array_values($barChartQryRes[$i]);
  }
    $jsonRes=json_encode($jsonRes,JSON_PRETTY_PRINT.';');  //return json data
    $result_decode =json_decode($jsonRes);  //Convert String to Array
    $data='';
    for ($row = 0; $row < sizeof($result_decode); $row++) 
    {
      for ($col = 0; $col <= 3; $col++) 
      {
        if($col == 0 )
        {
           $data.='["'.$result_decode[$row][$col].'"';
        }else if($col == 1){
        $data.= ','.$result_decode[$row][$col].'';  
        }
        else if($col == 3)
        {
          $data.= $result_decode[$row][$col].'],';
        }
        else
        {
          $data.=','.$result_decode[$row][$col];//add commo bet'n two values
        }
      }
    }
   $barchartString1 = mb_substr($data, 0, -1);


//Pie Chart -1
$pieChartQry = $qryObj->fetchQuery($qryPath,'Q001','PIE_CHART');
$firstGraphRes = $dsbObj->getData($pieChartQry);   

for($i=0;$i<=sizeof($firstGraphRes);$i++)
  {
    $firstjsonRes[]=array_values($firstGraphRes[$i]);
  }
    $firstjsonRes=json_encode($firstjsonRes,JSON_PRETTY_PRINT.';');  //return json data
    $result_decode =json_decode($firstjsonRes);  //Convert String to Array
    $data='';
    for ($row = 0; $row < sizeof($result_decode) - 1; $row++) 
    {
      for ($col = 0; $col <= 2; $col++) 
      {
         if($col == 0 )
          {
            $data.='["'.$result_decode[$row][$col].'"';
            //echo $data;
          }
         else if($col == 2)
         {
           $data.=$result_decode[$row][$col].'],';
         }
         else
         {
           $data.=','.$result_decode[$row][$col];//add commo bet'n two values
         }
      }
    }
    $firstGraphString = mb_substr($data, 0, -1);

//Pie Chart-2
$pieChartQry1 = $qryObj->fetchQuery($qryPath,'Q001','PIE_CHART1');
$secondGraphRes = $dsbObj->getData($pieChartQry1);
//print_r($secondGraphRes) ;
for($i=0;$i<=sizeof($secondGraphRes);$i++)
 {
    $secondjsonRes[]=array_values($secondGraphRes[$i]);
 }
 $secondjsonRes=json_encode($secondjsonRes,JSON_PRETTY_PRINT.';');  //return json data
 $result_decode_second =json_decode($secondjsonRes);  //Convert String to Array
 $data1='';
    for ($row1 = 0; $row1 < sizeof($result_decode_second) - 1; $row1++) 
    {
      for ($col1 = 0; $col1 <= 2; $col1++) 
      {
        if($col1 == 0 )
        {
          $data1.='["'.$result_decode_second[$row1][$col1].'"';
          //echo $data;
        }
        else if($col1 == 2)
        {
          $data1.=$result_decode_second[$row1][$col1].'],';
        }
        else
        {
          $data1.=','.$result_decode_second[$row1][$col1];//add commo bet'n two values
        }
      }
    }
    $secondGraphString = mb_substr($data1, 0, -1);
 
require_once("header.php");
include("sidebar.php");
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Dashboard Graph</title>
</head>
  <link rel="stylesheet" href="try/css/bootstrap.min.css">
  <script src="try/js/bootstrap.min.js"></script>
  <!-- <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<style>
.linechart {
      width: 80%; 
      min-height: 400px;
    }   
</style>

 <!-- page content -->
  <div class="right_col" role="main">
        <div class="page-title">
          <div class="title_left">
            <h3>Dashboard Graph</h3>
          </div>  <!-- title_left -->
        </div>    <!-- page-title -->
            
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="chart_div1" class="linechart"></div>                 
              </div>  <!-- col-md -->
            </div>    <!-- x_content -->
        </div> <!-- x panel -->
      </div>      <!-- col -->
             
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <!-- <div class="x_content"> -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="piechart" class="linechart" align="center"></div>                 
              </div>    <!-- col-md -->
           <!--  </div> -->    <!-- x_content -->
        </div>      <!-- x panel -->
      </div>      <!-- col -->

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <!-- <div class="x_content"> -->
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div id="piechart1" class="linechart" align="center"></div>
            </div>
          <!-- </div> -->  <!-- x_content -->
        </div>  <!-- x_panel -->
      </div>  <!-- col -->

</div>  <!-- right_col -->  
  
<?php include("footer.php");?>  
           
<script>
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart1);
  function drawChart1() {
    var data = google.visualization.arrayToDataTable([
                  ['Section','Registration'],
                  <?php echo $barchartString1; ?>
                  ]);
  
  
    var options = {
                  title: 'Section wise Chart',chartArea: {center:80},
                  hAxis: {title: 'Section', titleTextStyle: {color: 'red'}},
                  vAxis: {title: 'Registration', titleTextStyle: {color: 'red'}},
                  legend: 'left', width:800,height:400,chartArea: {width: '80%'},
                  //colors: ['green'],legend: {position:'left'},
                  };
  
  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
  chart.draw(data, options);
  } 
  $(window).resize(function(){
  drawChart1();   
  }); 
  // Reminder: you need to put https://www.google.com/jsapi in the head of your document or as an external resource on codepen //
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
google.charts.setOnLoadCallback(drawChart1);

function drawChart() {
    var data = google.visualization.arrayToDataTable([['Year', ''],
            <?php echo $firstGraphString; ?>]);
    // Optional; add a title and set the width and height of the chart
    var options = {'title':'Cane Type Wise Chart', 'width':550, 'height':400,
                    chartArea: {width:'100%'},
                    legend: {position:'left'},
                  };
    //Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);
    }

function drawChart1() {
      var data1 = google.visualization.arrayToDataTable([['Year', ''],
                <?php echo $secondGraphString; ?>]);
      // Optional; add a title and set the width and height of the chart
      var options1 = {'title':'Cane Variety Wise Graph','width1':550,'height1':400,
                      chartArea: {width:'100%'},
                      legend: {position:'left'},  
                     };
      //Display the chart inside the <div> element with id="piechart1"
      var chart1 = new google.visualization.PieChart(document.getElementById('piechart1'));
      chart1.draw(data1, options1);
      }

</script>



      
