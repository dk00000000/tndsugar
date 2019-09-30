<? 
  require_once('dashboard.php');
    include('readfile.php');
    $lgs = new Logs();
    $qryObj = new Query();
    $dsbObj = new Dashboard(); 
    $rfObj = new ReadFile();
    $lang=strtolower($_SESSION['LANG']);
    $qryPath = "util/readquery/general/bar_chart.ini";

    //FOR Line Graph QUERY
    //$oldFilter= array(':PCOMP_CODE',':PUSER_CODE',':PUSER_ACCD',':PUSER_CAT');
    //$newFilter= array($_SESSION['COMP_CODE'],$_SESSION['USER'],$_SESSION['USER_ACCD'],$_SESSION['USER_CAT']);
	
	$barChartQry = $qryObj->fetchQuery($qryPath,'Q001','BAR_CHART');
    //echo $barChartQry;
	$barChartQryRes = $dsbObj->getData($barChartQry); 
 	//print_r($barChartQryRes);
	
	$total_keys=array();
    $total_values=array();
	$total_insRecord=array();
	foreach ($barChartQryRes as $key => $value) {
	         $keys=array_keys($value);
	         $values =array_values($value);
	            for ($k=0; $k < sizeof($value) ; $k++){
				     
				     array_push($total_keys, strtolower($keys[$k]));
                     array_push($total_values, $values[$k]);
					}
			
	$total_insRecord[]=array_combine($total_keys, $total_values);
	}
	
	if(sizeof($barChartQryRes) == 0){
    echo "<h3 align='center'>Data Not Found !</h3>";
    exit();
    }
	
	//$dataPoints=$barChartQryRes;
  ?>
 <!DOCTYPE HTML>
    <html>
    <head>
    <script>
    window.onload = function () {
     
	 
    var chart = new CanvasJS.Chart("chartContainer", {
    	animationEnabled: true,
    	theme: "light2", // "light1", "light2", "dark1", "dark2"
    	title: {
    		text: "Section Vise Field Registration"
    	},
    	axisY: {
    		title: "Field Registration",
    		includeZero: false	
    	},
		axis: {
    		title: "Section",
    		includeZero: false	
    	},
    	data: [{
    		type: "column",
    		dataPoints: <?php echo json_encode($total_insRecord, JSON_NUMERIC_CHECK); ?>
    	}]
    });
    chart.render();
     
    }
    </script>
    </head>
    <body>
    <div id="chartContainer" style="height: 370px; width:100%;"></div>
    <script src="js/canvasjs.min.js"></script>
    </body>
    </html>                              

