<?php 
  require_once('dashboard.php');
  include('readfile.php');
  include("./mpdf7/mpdf.php");
  //require_once('header_menu.php');
  //For number to marathi word conversion
  require_once("numbertomarathiword.php");
  $marathinumber = new NumbertoMarathi;
  //echo $marathinumber->getIndianCurrency(115665);

 /*$lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/canebill_print_daund.ini";  
 

  $oldFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORT_NIGHT',':PSECTION',':PBILL_TYPE',':PFARMER');
  $newFilter = array($_SESSION['COMP_CODE'],$_REQUEST['season'],$_REQUEST['fornight'],$_REQUEST['section'],$_REQUEST['bill_type'],$_REQUEST['farmer']); 
  

  $procedure = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);
  //echo $procedure;
  $procedureRes = $dsbObj->getData($procedure);
  
  //GET DATA
  $getAllData = $qryObj->fetchQuery($qryPath,'Q001','GET_DATA',$oldFilter,$newFilter);
  //echo "<br>".$getAllData;
  $allDataRes = $dsbObj->getData($getAllData);*/

?>

 <?php 
	/*$i = 0;
	foreach ($allDataRes as $key => $value)
    {
          $srno=$i+1;
          
          //Set Filters
          $oldFilter = array(':PCOMP_CODE',':PTXN_SEQ');
          $newFilter = array($_SESSION['COMP_CODE'],$value['TXN_SEQ']);
          //For Header Data
          $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','HEADERPRINTQRY',$oldFilter,$newFilter);

          $printdataRes = $dsbObj->getData($printdataQry);
          //For Details Data 
          $detailQry = $qryObj->fetchQuery($qryPath,'Q001','DEATAILPRINTQRY',$oldFilter,$newFilter);
           
          $detailRes = $dsbObj->getData($detailQry);*/
          //echo "<br>".sizeof($detailRes);
          

		 $html='<style>
		*{
			margin:0;
			padding: 0;
		}
		body{
			background: #e8e8e8;
			font-family: vardana;
		}
		#cardwrapper{
			margin: 1px;
		}
		#cardwrapper > img{
			height: 50px;
			display: inline-block;
			vertical-align: middle;
		}
		#cardcontent{
			width: 100px;
			height: 50px;
			background: lightgray;
			padding: 10px;
			box-sizing: border-box; 
			display: inline-block;
			vertical-align: middle;
		}
		.cardcontent > h5{
			font-size: .9em;			
		}
		.cardcontent > p{			
			line-height: 0.2;
			height : 30px;
		}
		
	</style>
	<div id="cardwrapper">
		<img src="/opt/lampp/htdocs/tndsugar/business_card/58195.jpg" height="40" width="120" >
		<div id="cardcontent">
			<h5>NSCPL Galaxy</h5>
			<p style="font-size:10px;">Plot No. 12, C. S. No. 16/1-A, Opposite Minatai Thakare Vidya Mandir, Modkeshwar Nagar, Kamatwade, Nashik, Maharashtra 422008</p>	
		</div>
	</div>';

//==============================================================
//==============================================================
//==============================================================
//$i = $i + 1; 
//$lgs->lg->trace("Generating bill: ".$i);
//if ($i == 1) {
// For first bill, create PDF

	$mpdf=new mPDF('utf-8', array(190, 236));
	$mpdf->debug = true;
	$mpdf->autoScriptToLang = true;
	$mpdf->autoLangToFont = true;
	$mpdf->allow_output_buffering=true;
	//echo "Generating bill: ".$i;
//}
//else {
    // For subsequent bills, add page
	//echo "Generating bill: ".$i;
	$mpdf->AddPage();
//}
$mpdf->WriteHTML($html);   // Separate Paragraphs  defined by font
//}
// Show PDF with all bills
$mpdf->Output();
//==============================================================
//==============================================================
//==============================================================
?>