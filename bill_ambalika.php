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
      border:0.1mm solid #220044;
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
      padding:1mm 0px 0px 0px; //top right bottom left
      margin: 0px 0px 0px 0px;
      text-align:justify;
}
.header { position: absolute;
      overflow: visible;
        top:20mm;
      left: 173mm;
      height: 6mm;
        max-height: 6mm;
        width:15mm;
      border: 1px solid #880000;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-f { position: absolute;
      overflow: visible;
      top:28mm;
      left: 42mm;
      height: 9mm;
      max-height: 9mm;
        width: 120mm;
      border: 1px solid #000088;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-v { position: absolute;
      overflow: visible;
      top:37mm;
      left: 18mm;
      height: 6mm;
      max-height: 6mm;
        width: 60mm;
      border: 1px solid #000088;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-s { position: absolute;
      overflow: visible;
      top:37mm;
      left: 80mm;
      height: 6mm;
      max-height: 6mm;
        width: 75mm;
      border: 1px solid #000088;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-b { position: absolute;
      overflow: visible;
      top:33mm;
      left: 176mm;
        width: 40mm;
      height: 6mm;
      max-height: 6mm;
      border: 1px solid #880000;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-fd { position: absolute;
      overflow: visible;
      top:49mm;
      left: 27mm;
        width:25mm;
      height: 6mm;
      max-height: 6mm;
      border: 1px solid #880000;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-td { position: absolute;
      overflow: visible;
      top:49mm;
      left: 59mm;
        width:25mm;
      height: 6mm;
      max-height: 6mm;
      border: 1px solid #880000;
      padding: 00px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.particulars-wra { position: absolute;
      overflow: visible;
      top:53mm;
      left:115mm;
      height: 6mm;
      max-height: 6mm;
        width: 94mm;
      border: 1px solid #880000;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.deductions-l { position: absolute;
      overflow: visible;
        top:67mm;
      left: 8mm;
      height: 38mm;
      max-height: 38mm;
      width: 114mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin: 0px 0px 0px 0px;
}
.deductions-r { position: absolute;
      overflow: visible;
        top:67mm;
      left: 123mm;
      height: 38mm;
        max-height: 38mm;
        width: 90mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin: 0px 0px 0px 0px;
}
.summary-aw { position: absolute;
      overflow: visible;
        top: 108mm;
      left: 8mm;
      height: 12mm;
        max-height: 12mm;
        width: 125mm;
      border: 1px solid #880088;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.summary-bb { position: absolute;
      overflow: visible;
        top: 121mm;
      left: 8mm;
      height: 12mm;
        max-height: 12mm;
        width: 125mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.summary-ba { position: absolute;
      overflow: visible;
        top: 133mm;
      left: 8mm;
      height: 8mm;
        max-height: 8mm;
        width: 125mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.summary-ded { position: absolute;
      overflow: visible;
        top: 107mm;
      left: 187mm;
      height: 6mm;
        max-height: 6mm;
        width:26mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
.summary-np { position: absolute;
      overflow: visible;
        top: 115mm;
      left: 187mm;
      height: 6mm;
        max-height: 6mm;
        width:26mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 0px;
      font-family:sans;
      margin:  0px 0px 0px 0px;
}
</style>
<body>
<div class="header">
<table width="100%">
<tr> 
<td align="left">'.$printdataRes[0]['SEASON'].'</td>
</tr>
</table>
</div>
<div class="particulars-f">
<table style="width:100%">
<tr>
<td align="left">&nbsp;&nbsp;'.$printdataRes[0]['FARMER']." ".$printdataRes[0]['PRT_MNAME'].'</td>
</tr>
</table>
</div>
<div class="particulars-v">
<table style="width:100%">
<tr>
<td align="left">'.$printdataRes[0]['SR_MNAME'].'</td>
</tr>
</table>
</div>
<div class="particulars-s">
<table style="width:100%">
<tr>
<td align="left">'.$printdataRes[0]['SC_MNAME'].'</td>
</tr>
</table>
</div>
<div class="particulars-b">
<table>
<tr>
<td align="left">'.$printdataRes[0]['BILL_NO'].'</td>
</tr>
</table>
</div>
<div class="particulars-fd">
<table style="width:100%">
<tr>
<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['SEASON_START_DT'].'</td>
</tr>
</table>
</div>
<div class="particulars-td">
<table style="width:100%">
<tr>
<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$printdataRes[0]['SEASON_END_DT'].'</td>
</tr>
</table>
</div>
<div class="particulars-wra">
<table style="width:100%">
<tr>
<td align="right" style="width:33%;">'.$printdataRes[0]['TONNAGE'].'</td>
<td align="right" style="width:33%;">'.$printdataRes[0]['RATE'].'</td>
<td align="right" style="width:33%;">'.$printdataRes[0]['TXD_AMT'].'</td>
</tr>
</table>
</div>
<div class="deductions-l">
<table style="width:100%">
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
<div class="deductions-r">
<table style="width:100%">
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
<div class="summary-ded">
<table style="width:100%">
<tr>
<td align="right" valign="top">'.$printdataRes[0]['DEDUCTION'].'</td>
</tr>
</table>
</div>
<div class="summary-aw">
<table style="width:100%">
<tr>
<td valign="top" align="left">'.$marathinumber->getIndianCurrency($printdataRes[0]['NETT_AMT']).'</td>
</tr>
</table>
</div>
<div class="summary-np">
<table style="width:100%">
<tr>
<td align="right" valign="top">'.$printdataRes[0]['NETT_AMT'].'</td>
</tr>
</table>
</div>
<div class="summary-bb">
<table style="width:100%">
<tr>
<td align="left">'.$printdataRes[0]['BANK_BRANCH_NAME'].'</td>
</tr>
</table>
</div>
<div class="summary-ba">
<table style="width:100%">
<tr>
<td align="left">'.$printdataRes[0]['PRT_ACNO'].'</td>
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
$mpdf->Output();
//==============================================================
//==============================================================
//==============================================================
?>