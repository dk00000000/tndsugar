<?php
	require_once('dashboard.php');
		
	$lgs = new Logs();
	$qryObj = new Query();
	$dsbObj = new Dashboard();
	$lang = strtolower($_SESSION['LANG']);
	$qryPath = "util/readquery/general/bullockcart_checklist.ini";
	
	//company details
	$oldFilter = array(':PCOMP_CODE',':PSEASON');
	$newFilter = array($_SESSION['COMP_CODE'],$_SESSION['SEASON']);
	$compQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
	$compRes = $dsbObj->getData($compQry);
	
	// fetch query
	$bullockcartQry = $qryObj->fetchQuery($qryPath,'Q001','BULLOCKCART_QUERY',$oldFilter,$newFilter);
	$bullockcartRes = $dsbObj->getData($bullockcartQry);
	
	$lgs->lg->trace("In Bullockcart checklist Query :".$bullockcartQry);
	$lgs->lg->trace("In Bullockcart checklist Result :".json_encode($bullockcartRes));
	
	require_once('header.php');		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />  -->
<title>Bullockcart Checklist</title>
<style>
	table {
		border:1px solid black;
		font-family:verdana;
	}
	th {
		border-right:1px solid black;
		border-bottom:1px solid black;
		padding:5px;
		font-size:13px;
		font-weight:bold;
		color:black;
		font-family:verdana;
	}

	td {
		border-right:1px solid black;
		border-bottom:1px dotted black;
		padding:5px;
		color:black;
		font-size:13px;
		font-family:verdana;
	}
	h1,h2,h3,p {
		color: black;
	}
</style>
</head>

<div class="container-body">
<div class="main_container">
<? include('sidebar.php');?>
	<div class = "right_col" role="main">
		<div align="center">
			<input type="button" name="print" class="btn btn-success" id="btn_print" value="Print" onclick='printFunc();'>
		</div>
		<div id='printarea'>
		<h2 align="center"><? echo $compRes[0]['COMP_NAME'];?></h2>
		<h3 align="center">बैलगाडी कंत्राटदार यादी</h3>
		<p align="center">हंगाम : <?=$_SESSION['SEASON']?></p>

<table cellspacing="0" border="0" width="100%" align="center">
<tr>
<th align='left' width="3%">अ.न.</th>
<th align='left' width="9%">कोड</th>
<th align='left' width="20%">नाव</th>
<th align='left' width="12%">वाहन प्रकार</th>
<th align='left' width="30%">बँक शाखा नाव</th>
<th align='left' width="14%">यु आयडी नंबर</th>
<th align='left' width="14%">दूरध्वनी नंबर</th>
</tr>
<? for($i=0; $i<sizeof($bullockcartRes); $i++) { ?>
<tr>
<td align="left"><?=$bullockcartRes[$i]["SR"]?></td>
<td align="left"><?=$bullockcartRes[$i]["PRT_CODE"]?></td>
<td align="left"><?=$bullockcartRes[$i]["PRT_MNAME"]?></td>
<td align="left"><?=$bullockcartRes[$i]["VT_MNAME"]?></td>
<td align="left"><?=$bullockcartRes[$i]["BR_MNAME"]?></td>
<td align="left"><?=$bullockcartRes[$i]["PRT_UID"]?></td>
<td align="left"><?=$bullockcartRes[$i]["PRT_TEL"]?></td>
</tr>
<? } ?>
</table>
</div>

			<? include('footer.php');?>
		</div>
	</div>
</div>
<script type="text/javascript">
	function printFunc() {
		
    var divToPrint = document.getElementById('printarea');
    var htmlToPrint = '' +
        '<style type="text/css">' +
        'table {' +
        'border:1px solid black;'+
        'font-family:verdana;'+
        '}' +
        'th {' +
        'border:1px solid black;'+
		'padding:5px;'+
		'font-size:13px;'+
		'font-family:verdana;'+
		'font-weight:bold;'+
		'color:black;'  +
        '}' +
        'td {' +
        'border-left: 1px solid black;'+
		'border-bottom:1px dotted black;'+
		'color:black;'+
		'font-size:13px;'  +
		'font-family:verdana;'+
        '}' +
        'h1,h2,h3,p {'+
		'color: black;'+
		'font-size:13px;'  +
		'font-family:verdana;'+
		'}'+
        '</style>';
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    //newWin.print();
    //newWin.close();
    }
</script>
</html>