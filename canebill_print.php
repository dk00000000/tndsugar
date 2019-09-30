<?php 
  require_once('dashboard.php');
  include('readfile.php');
  include("./mpdf7/mpdf.php");
  //require_once('header_menu.php');
  //For number to marathi word conversion
  require_once("numbertomarathiword.php");
  $marathinumber = new NumbertoMarathi;
  //echo $marathinumber->getIndianCurrency(115665);

  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/canebill_print.ini";  
 

  $oldFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORT_NIGHT',':PSECTION',':PBILL_TYPE',':PFARMER');
  $newFilter = array($_SESSION['COMP_CODE'],$_REQUEST['season'],$_REQUEST['fornight'],$_REQUEST['section'],$_REQUEST['bill_type'],$_REQUEST['farmer']); 
  

  $procedure = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldFilter,$newFilter);
  //echo $procedure;
  $procedureRes = $dsbObj->getData($procedure);
  
  //GET DATA
  $getAllData = $qryObj->fetchQuery($qryPath,'Q001','GET_DATA',$oldFilter,$newFilter);
  //echo "<br>".$getAllData;
  $allDataRes = $dsbObj->getData($getAllData);
 
 
?>

 <?php 
	//$i = 0;

	foreach ($allDataRes as $key => $value)
    {
       //$srno=$i+1;
          
          //Set Filters
          $oldFilter = array(':PCOMP_CODE',':PTXN_SEQ');
          $newFilter = array($_SESSION['COMP_CODE'],$value['TXN_SEQ']);
          //For Header Data
          $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','HEADERPRINTQRY',$oldFilter,$newFilter);

          $printdataRes = $dsbObj->getData($printdataQry);
          
          //For Details Data 
          $detailQry = $qryObj->fetchQuery($qryPath,'Q001','DEATAILPRINTQRY',$oldFilter,$newFilter);
           
          $detailRes = $dsbObj->getData($detailQry);
          //echo "<br>".sizeof($detailRes);
          

		  $html = '
<style>
.gradient {
     
      background-color: #f0f2ff;
      background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
}
h4 {
      font-family: sans;
      font-weight: bold;
      margin-top: 1em;
      margin-bottom: 0.5em;
}
div {
      padding:0px 0px 0px 0px; //top right bottom left
      margin: 0px 0px 0px 0px;
      text-align:justify;
}
.header { position: absolute;
      overflow: visible;
    top:0;
      left: 0;
      height: 21mm;
    max-height: 21mm;
    
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-fvs { position: absolute;
      overflow: visible;
      top:25mm;
      left: 0mm;
      height: 20mm;
      max-height: 20mm;
    width: 150mm;
    
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-b { position: absolute;
      overflow: visible;
      top:30mm;
      left: 170mm;
    width: 40mm;
      height: 10mm;
      max-height: 10mm;
     
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-ft { position: absolute;
      overflow: visible;
      top:45mm;
      left: 0mm;
      width:110mm;
      height: 10mm;
      max-height: 10mm;
      padding: 10px 0px 0px 25px;
      font-family:sans;
      margin:  0px 0px 0px -5px;
}
.particulars-wra { position: absolute;
      overflow: visible;
      top:50mm;
      left:118mm;
      height: 10mm;
      max-height: 10mm;
      width: 100mm;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.deductions-l { position: absolute;
      overflow: visible;
        top:60mm;
      left: 0;
      height: 45mm;
      max-height: 45mm;
      width: 125mm;
      padding: 15px 0px 0px 30px;
      font-family:sans;
      margin: 0px 0px 0px -5px;

}
.deductions-r { position: absolute;
      overflow: visible;
        top:60mm;
      left: 126mm;
      height: 45mm;
        max-height: 45mm;
        width: 130mm;
     
     
      padding: 15px 0px 0px 8px;
      font-family:sans;
      margin: 0px 0px 0px -5px;
}
.summary { position: absolute;
      overflow: visible;
      left: 0;
        max-height: 15mm;
     
   
      padding: 0px 0px 0px 30px;
      font-family:sans;
      margin:  0px 0px 0px -5px;
}
</style>
<body>
<div class="header">
<table width="100%">
<tr> 
<td style="width:70%"></td>
<td halign="bottom" align="left" style="height:130px; width=30%">'.$printdataRes[0]['SEASON'].'</td>
</tr>
</table>
</div>
<div class="particulars-fvs">
<table>

<tr>
<td style="width:22%" colspan=2>&nbsp;</td>
<td height="10mm" valign="top" style="width:88%" colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['FARMER']." ".$printdataRes[0]['PRT_MNAME'].'</td>
</tr>

<tr>
<td style="width:7%">&nbsp;</td>
<td align="left" style="width:43%;" colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['SR_MNAME'].'</td>
<td align="left" style="width:50%;" colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['SC_MNAME'].'</td>
</tr>
</table>
</div>
<div class="particulars-b">
<table>
<tr>
<td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['BILL_NO'].'</td>
</tr>
</table>
</div>
<div class="particulars-ft">
<table width="100%">
<tr>
<td align="center" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['SEASON_START_DT'].'</td>
<td align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['SEASON_END_DT'].'</td>
</tr>
</table>
</div>
<div class="particulars-wra">
<table width="100%">
<tr>
<td align="right">'.$printdataRes[0]['TONNAGE'].'</td>
<td align="right">'.$printdataRes[0]['RATE'].'</td>
<td align="right">'.$printdataRes[0]['TXD_AMT'].'</td>
</tr>
</table>
</div>
<div class="deductions-l" style="float: left; width: 125mm;">
<table style="width:93%">

<tr>
<td align="left" valign="top" style="width:80%;">'. $detailRes[0]['SR']." ".$detailRes[0]['DED_NAME'].'</td>
<td align="right" valign="top" style="width:20%;">'.$detailRes[0]['TXD_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">'. $detailRes[2]['SR']." ".$detailRes[2]['DED_NAME'].'</td>
<td align="right" valign="top" style="width:20%;">'.$detailRes[2]['TXD_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">'. $detailRes[4]['SR']." ".$detailRes[4]['DED_NAME'].'</td>
<td align="right" valign="top" style="width:20%;">'.$detailRes[4]['TXD_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">'. $detailRes[6]['SR']." ".$detailRes[6]['DED_NAME'].'</td>
<td align="right" valign="top" style="width:20%;">'.$detailRes[6]['TXD_AMT'].'</td>
</tr>
</table>
</div>

<div class="deductions-r" style="float: left; width: 130mm;">
<table align="left" style="width:70%">
<tr>
<td align="left" valign="top" style="width:80%;">'. $detailRes[1]['SR']." ".$detailRes[1]['DED_NAME'].'</td>
<td align="right" valign="top" style="width:20%;">'.$detailRes[1]['TXD_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">'. $detailRes[3]['SR']." ".$detailRes[3]['DED_NAME'].'</td>
<td align="right" valign="top" style="width:20%;">'.$detailRes[3]['TXD_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">'. $detailRes[5]['SR']." ".$detailRes[5]['DED_NAME'].'</td>
<td align="right" valign="top" style="width:20%;">'.$detailRes[5]['TXD_AMT'].'</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">'. $detailRes[7]['SR']." ".$detailRes[7]['DED_NAME'].'</td>
<td align="right" valign="top" style="width:20%;">'.$detailRes[7]['TXD_AMT'].'</td>
</tr>
</table>
</div>
<div class="summary" style="width: 100%; top: 105mm; height:16mm;">
<table align="left" style="width:84%">
<tr>
<td style="width:55%"></td>
<td style="width:26%"></td>
<td align="right" style="width:19%;">'.$printdataRes[0]['DEDUCTION'].'</td>
</tr>
<tr>
<td valign="top" align="left" style="width:55%;">'.$marathinumber->getIndianCurrency($printdataRes[0]['NETT_AMT']).' फक्त</td>
<td style="width:26%"></td>
<td valign="top" align="right" style="width:19%;">'.$printdataRes[0]['NETT_AMT'].'</td>
</tr>
</table>
</div>
<div class="summary" style="width: 100%; top: 121mm; height:16mm;">
<table align="left" style="width:84%">
<tr><td colspan=3></td></tr>
<tr><td colspan=3></td></tr>
<tr>
<td valign="top" align="left" style="width:55%;">'.$printdataRes[0]['BANK_BRANCH_NAME'].'</td>
<td style="width:26%"></td>
<td style="width:19%"></td>
</tr>
</table>
</div>
<div class="summary" style="width: 100%; top: 136mm; height:8mm;">
<table align="left" style="width:84%">
<tr><td colspan=3></td></tr>
<td align="left" style="width:55%;">'.$printdataRes[0]['PRT_ACNO'].'</td>
<td style="width:26%"></td>
<td style="width:19%"></td>
</tr>
</table>
</div>
';
//==============================================================
//==============================================================
//==============================================================
$i = $i + 1; 
$lgs->lg->trace("Generating bill: ".$i);
if ($i == 1) {
	// For first bill, create PDF
	$mpdf=new mPDF('utf-8', array(250, 152.4));
	$mpdf->debug = true;
	$mpdf->autoScriptToLang = true;
	$mpdf->autoLangToFont = true;
	$mpdf->allow_output_buffering=true;
	//echo "Generating bill: ".$i;
}
else {
    // For subsequent bills, add page
	//echo "Generating bill: ".$i;
	$mpdf->AddPage();
}
$mpdf->WriteHTML($html);   // Separate Paragraphs  defined by font
}
// Show PDF with all bills
$mpdf->Output('Bills.pdf');
//==============================================================
//==============================================================
//==============================================================
?>