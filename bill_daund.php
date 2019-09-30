<?php

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
      padding:0px 0px 0px 0px; //top right bottom left
      margin: 0px 0px 0px 0px;
      text-align:justify;
}
.header { position: absolute;
      overflow: visible;
      top:0;
      left: 0;
      height: 25mm;
      max-height: 25mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 10px 0px 0px 25px;
      font-family:sans;
      margin:  0px 0px 0px -5px;
}
.particulars { position: absolute;
      overflow: visible;
      top:26mm;
      left: 0;
      height: 32mm;
      max-height: 32mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 30px;
      font-family:sans;
      margin:  0px 0px 0px -5px;
}
.deductions-l { position: absolute;
      overflow: visible;
        top:60mm;
      left: 0;
      height: 45mm;
        max-height: 45mm;
        width: 125mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
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
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 15px 0px 0px 8px;
      font-family:sans;
      margin: 0px 0px 0px -5px;
}
.summary { position: absolute;
      overflow: visible;
      left: 0;
        max-height: 15mm;
      border: 1px solid #880000;
      background-color: #FFEEDD;
      background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
      padding: 0px 0px 0px 30px;
      font-family:sans;
      margin:  0px 0px 0px -5px;
}
</style>
<body>
<div class="header" style="width: 100%;margin= 5px 0px 0px 0px;">
<table width="100%">
<tr> 
<td style="width:70%"></td>
<td halign="bottom" align="left" style="height:130px; width=30%;">2017-18</td>
</tr>
</table>
</div>
<div class="particulars" style="width: 100%;">
<table>
<tr>
<td style="width:17%"></td>
<td halign="bottom" valign="bottom" align="left" style="width:20%;" colspan=5>Code and Description of Farmer</td>
</tr>
<tr>
<td style="width:17%"></td>
<td style="width:20%"></td>
<td style="width:19%"></td>
<td style="width:15%"></td>
<td style="width:16%"></td>
<td align="left" style="width:14%;">अंकुश </td>
</tr>
<tr>
<td align="center" style="width:17%;" colspan=2>Code and Name of Village</td>
<td align="center" style="width:19%;" colspan=2>Code and Name of Section</td>
<td style="width:16%;"></td>
<td style="width:14%;"></td>
</tr>
<tr><td colspan=6></td></tr>
<tr><td colspan=6></td></tr>
<tr><td colspan=6></td></tr>
<tr><td colspan=6></td></tr>
<tr>
<td align="right" style="width:17%;">From Date</td>
<td align="right" style="width:20%;">To Date</td>
<td style="width:19%"></td>
<td align="right" valign="bottom" style="width:15%;">Weight</td>
<td align="right" valign="bottom" style="width:16%;">Rate</td>
<td align="right" valign="bottom" style="width:14%;">Amount</td>
</tr>
</table>
</div>
<div class="deductions-l" style="float: left; width: 125mm;">
<table style="width:93%">
<tr>
<td align="left" valign="top" style="width:80%;">1. Deduction Details</td>
<td align="right" style="width:20%;">10,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">2. Deduction Details for Column 2  with Extended Description for Second Deduction</td>
<td align="right" valign="top" style="width:20%;">20,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">3. Deduction Details</td>
<td align="right" valign="top" style="width:20%;">30,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">4. Deduction Details</td>
<td align="right" valign="top" style="width:20%;">40,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">5. Deduction Details</td>
<td align="right" valign="top" style="width:20%;">50,000</td>
</tr>
</table>
</div>
<div class="deductions-l" style="float: left; width: 125mm;">
<table style="width:93%">
<tr>
<td align="left" valign="top" style="width:80%;">1. Deduction Details</td>
<td align="right" style="width:20%;">10,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">2. Deduction Details for Column 2  with Extended Description for Second Deduction</td>
<td align="right" valign="top" style="width:20%;">20,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">3. Deduction Details</td>
<td align="right" valign="top" style="width:20%;">30,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">4. Deduction Details</td>
<td align="right" valign="top" style="width:20%;">40,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">5. Deduction Details</td>
<td align="right" valign="top" style="width:20%;">50,000</td>
</tr>
</table>
</div>
<div class="deductions-r" style="float: left; width: 130mm;">
<table align="left" style="width:70%">
<tr>
<td align="left" valign="top" style="width:80%;">1. Deduction Details</td>
<td align="right" style="width:20%;">10,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">2. Deduction Details for Column 2  with Extended Description for Second Deduction</td>
<td align="right" valign="top" style="width:20%;">20,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">3. Deduction Details</td>
<td align="right" valign="top" style="width:20%;">30,000</td>
</tr>
<tr>
<td align="left" valign="top" style="width:80%;">4. Deduction Details</td>
<td align="right" valign="top" style="width:20%;">40,000</td>
</tr>
<tr>
<td valign="top" align="left" style="width:80%;">5. Deduction Details</td>
<td valign="top" align="right" style="width:20%;">50,000</td>
</tr>
</table>
</div>
<div class="summary" style="width: 100%; top: 105mm; height:16mm;">
<table align="left" style="width:84%">
<tr>
<td style="width:55%"></td>
<td style="width:26%"></td>
<td align="right" style="width:19%;">Deductions</td>
</tr>
<tr>
<td valign="top" align="left" style="width:55%;">Amount In Words.</td>
<td style="width:26%"></td>
<td valign="top" align="right" style="width:19%;">Total Due</td>
</tr>
</table>
</div>
<div class="summary" style="width: 100%; top: 121mm; height:16mm;">
<table align="left" style="width:84%">
<tr><td colspan=3></td></tr>
<tr><td colspan=3></td></tr>
<tr>
<td valign="top" align="left" style="width:55%;">Bank and Branch.</td>
<td style="width:26%"></td>
<td style="width:19%"></td>
</tr>
</table>
</div>
<div class="summary" style="width: 100%; top: 136mm; height:8mm;">
<table align="left" style="width:84%">
<tr><td colspan=3></td></tr>
<td align="left" style="width:55%;">Account Number</td>
<td style="width:26%"></td>
<td style="width:19%"></td>
</tr>
</table>
</div>
';
//==============================================================
//==============================================================
//==============================================================
include("./mpdf7/mpdf.php");

 
$mpdf=new mPDF('utf-8', array(250, 152.4));
$mpdf->debug = true;
$mpdf->autoScriptToLang = true;
$mpdf->autoLangToFont = true;
$mpdf->allow_output_buffering=true;
$mpdf->WriteHTML($html);   // Separate Paragraphs  defined by font
$mpdf->Output();
//exit;

//===========================================================
//==============================================================
//==============================================================
?>