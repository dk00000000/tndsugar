<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
	<link rel="stylesheet" href="try/css/bootstrap.min.css">
	<script src="try/js/bootstrap.min.js"></script>
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<style>
		.chart {
		  width: 100%; 
		  min-height: 450px;
		}
	</style> 
<body>
	<div class="row">
	  <div class="col-md-12 text-cen ter">
		<h1>Make Google charts responsive</h1>
	  </div>
	  <div class="col-md-4 col-md-offset-4">
		<hr />
	  </div>
	  <div class="clearfix"></div>
	  <div class="col-md-6">
		<div id="chart_div1" class="chart"></div>
	  </div>
	  <div class="col-md-6">
		<div id="chart_div2" class="chart"></div>
	  </div>
	</div>
<?php

$db="(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=203.127.5.12)(PORT=1521)))(CONNECT_DATA=(SID=MSL)
(SERVER=DEDICATED)))";
$conn = oci_connect('nis','nis',  $db);

if (!$conn) {
   $m = oci_error();
   echo "Problem With Connection ";
   exit;
}
else {
//echo "conected";

$query= "SELECT PERD,
sum(decode(itc,'CL',decode(substr(srno,2,4),'IVTX',round(saleval/100000,2),0))) CL_Local_Sales,
sum(decode(itc,'CL',decode(substr(srno,2,4),'IVEX',round(saleval/100000,2),0))) CL_Export_Sales,
sum(decode(itc,'CL',round(saleval/100000,2),0)) CL_Total_Sales,
sum(decode(itc,'UJ',decode(substr(srno,2,4),'IVTX',round(saleval/100000,2),0))) UJ_Local_Sales,
sum(decode(itc,'UJ',decode(substr(srno,2,4),'IVEX',round(saleval/100000,2),0))) UJ_Export_Sales,
sum(decode(itc,'UJ',round(saleval/100000,2),0)) UJ_Total_Sales,
sum(decode(substr(srno,2,4),'IVTX',round(saleval/100000,2),0)) Total_Local_Sales,
sum(decode(substr(srno,2,4),'IVEX',round(saleval/100000,2),0)) Total_Export_Sales,
sum(round(saleval/100000,2)) Total_Sales
from
(SELECT
MMMMAST.TXN_DIVN DIV,MMMMAST.TXN_SRNO SRNO,
MMMMAST.PERD_CODE PERD,
ITMAST.IT_CLASS itc,
MMDMAST.TXD_QTY7 QTY,
ROUND(NIS.F_SALESVAL(MMDMAST.COMP_CODE,MMDMAST.TXD_SEQ,MMDMAST.TXD_RUNO),2) SALEVAL
FROM NIS.MMMMAST, NIS.MMDMAST, NIS.ITMAST
WHERE MMMMAST.COMP_CODE = 'MS'
AND MMMMAST.TXN_DOC = 'IV'
AND instr(MMMMAST.POSTED_BY,'---')=0
AND MMMMAST.TXN_DIVN='D001'
AND TO_CHAR(MMMMAST.TXN_DATE,'YYYYMMDD') >= 20170401
AND TO_CHAR(MMMMAST.TXN_DATE,'YYYYMMDD') <=20180213
AND MMMMAST.SSEG_CODE IN ('IV01','IV02','IV03','IV04','IV51')
AND MMDMAST.TXD_SEQ = MMMMAST.TXN_SEQ
AND (ITMAST.COMP_CODE = MMDMAST.COMP_CODE
AND  ITMAST.IT_CODE = MMDMAST.TXD_ITEM))
group by PERD
union all
SELECT 'TOTAL' PERD,
sum(decode(itc,'CL',decode(substr(srno,2,4),'IVTX',round(saleval/100000,2),0))) CL_Local_Sales,
sum(decode(itc,'CL',decode(substr(srno,2,4),'IVEX',round(saleval/100000,2),0))) CL_Export_Sales,
sum(decode(itc,'CL',round(saleval/100000,2),0)) CL_Total_Sales,
sum(decode(itc,'UJ',decode(substr(srno,2,4),'IVTX',round(saleval/100000,2),0))) UJ_Local_Sales,
sum(decode(itc,'UJ',decode(substr(srno,2,4),'IVEX',round(saleval/100000,2),0))) UJ_Export_Sales,
sum(decode(itc,'UJ',round(saleval/100000,2),0)) UJ_Total_Sales,
sum(decode(substr(srno,2,4),'IVTX',round(saleval/100000,2),0)) Total_Local_Sales,
sum(decode(substr(srno,2,4),'IVEX',round(saleval/100000,2),0)) Total_Export_Sales,
sum(round(saleval/100000,2)) Total_Sales
from
(SELECT
MMMMAST.TXN_DIVN DIV,MMMMAST.TXN_SRNO SRNO,
MMMMAST.PERD_CODE PERD,
ITMAST.IT_CLASS itc,
MMDMAST.TXD_QTY7 QTY,
ROUND(NIS.F_SALESVAL(MMDMAST.COMP_CODE,MMDMAST.TXD_SEQ,MMDMAST.TXD_RUNO),2) SALEVAL
FROM NIS.MMMMAST, NIS.MMDMAST, NIS.ITMAST
WHERE MMMMAST.COMP_CODE = 'MS'
AND MMMMAST.TXN_DOC = 'IV'
AND instr(MMMMAST.POSTED_BY,'---')=0
AND MMMMAST.TXN_DIVN='D001'
AND TO_CHAR(MMMMAST.TXN_DATE,'YYYYMMDD') >='20170401'
AND TO_CHAR(MMMMAST.TXN_DATE,'YYYYMMDD') <= '20180213'
AND MMMMAST.SSEG_CODE IN ('IV01','IV02','IV03','IV04','IV51')
AND MMDMAST.TXD_SEQ = MMMMAST.TXN_SEQ
AND (ITMAST.COMP_CODE = MMDMAST.COMP_CODE
AND  ITMAST.IT_CODE = MMDMAST.TXD_ITEM))
order by perd";

$data= oci_parse($conn,$query);
$result = oci_execute($data);
//$row = oci_fetch_array($data);

$nrows = oci_fetch_all($data, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
$chart_data = json_encode($res);

foreach ($chart_data as $key => $value) {
	echo $value['PERD'];
}

/*
$alldtlArr = array();
for($i=0;$i<sizeof($res);$i++)
	{
		array_push($alldtlArr,$res[$i]['PERD']);
	 	$json_result[]=array_values($res[$i]);
    }
	    $_SESSION['oldruno']=$alldtlArr; 
		$json_result=json_encode($json_result,JSON_PRETTY_PRINT.';');
*/
}


?>	

</body>
<script>
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart1);
	function drawChart1() {
	var php_data = <?php echo $chart_data; ?>;
	var php_data1 = JSON.stringify(php_data);
	//alert(php_data1);
	$.each(php_data, function(i, value) {

	});

	var data1 = [
		['Year', 'CL Local Sales', 'CL Export Sales', 'CL Total Sales', 'UJ Local Sales', 'UJ Export Sales', 'UJ Total Sales', 'Total Local Sales', 'Total Export Sales', 'Total Sales'],
		["201704",838.45,8.71,847.16,1847.59,826.49,2674.08,2686.04,835.2,3521.24],
		["201704",838.45,8.71,847.16,1847.59,826.49,2674.08,2686.04,835.2,3521.24],
		["201704",838.45,8.71,847.16,1847.59,826.49,2674.08,2686.04,835.2,3521.24],
		["201704",838.45,8.71,847.16,1847.59,826.49,2674.08,2686.04,835.2,3521.24]
		
	  ];
	
	  var data = google.visualization.arrayToDataTable(data1);
	  var options = {
		title: 'Company Performance',
		hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
	 };
	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
	  chart.draw(data, options);
	}
		
	$(window).resize(function(){
	  drawChart1();
	  //drawChart2();
	});
	
	// Reminder: you need to put https://www.google.com/jsapi in the head of your document or as an external resource on codepen //
</script>
</html>

