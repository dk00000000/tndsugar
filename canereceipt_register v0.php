
<?php 
  //session_start();
  require_once('dashboard.php');
 
  include('readfile.php');
  //require_once('header_menu.php');
  
  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/canereceipt_register.ini";

   $menucode = $_GET['menu_code'];
  //$compcode = $_SESSION['COMP_CODE'];
   $compcode = 'DS';
 
    /*if(isset($_POST['season'])){    
      $season=$_POST['season'];
    } 
    else{
       $season='';
     } 
      if(isset($_POST['date'])){    
       $fromdate = $_POST['date'];
    } 
    else{
       $fromdate='';
     } 
      if(isset($_POST['shift'])){    
      $shiftcode = $_POST['shift'];
    } 
    else{
       $shiftcode='';
     } 
    if(isset($_POST['series'])){    
      $series = $_POST['series'];
    } 
    else{
       $series='';
     } */

    $divison='SUGR';
    $location='JSML';
    $season='2017-18';
    $fromdate = '20170101';
    $todate = '20171110';
    $section = ''; //G03
    $farmer = ''; //G004315

  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  //GET PRINT DATA
  $oldFilter = array(':PCOMP_CODE',':PLOC_CODE',':PTXN_DIVN',':PSC_CODE',':PPRT_CODE',':PFR_DATE',':PTO_DATE',':PTXN_SEASON');
  $newFilter = array( $compcode,$location,$divison,$section,$farmer,$fromdate,$todate,$season);
 
  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY',$oldFilter,$newFilter);
  $printdataRes = $dsbObj->getData($printdataQry);
  $lgs->lg->trace("In cane recipt report php  query: ".$printdataQry);
  $lgs->lg->trace("In cane recipt php  result: ".json_encode($printdataRes));

  //echo $printdataQry;
  //print_r($printdataRes);

 /* $sectionQry = $qryObj->fetchQuery($qryPath,'Q001','SECTIONQRY',$oldFilter,$newFilter);
  $sectionRes = $dsbObj->getData($sectionQry);
  $lgs->lg->trace("In shift report php  query: ".$sectionQry);
  $lgs->lg->trace("In shift report php  result: ".json_encode($sectionRes));*/


  //echo $sectionQry;
  //print_r($sectionRes);
 
 
  $rowcnt = sizeof($printdataRes);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jqu   ery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 1500px}
    
    /* Set gray background color and 100% height */
    .sidenav {
/*      background-color: #f1f1f1;*/
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;} 
    }

    table {
    table-layout: auto;
    }
  </style>
  
<title>Day Paan Report </title>
</head>
<body style="font-size:13px">
<!-- <div class="col-md-12 col-sm-12 col-xs-12"> -->
<h3 align="center"><? echo $compnameaRes[0]['COMP_NAME'];  ?></h3>
<h4 align="center" style="border:medium; border-color:#000000"> Day Paan Report </h4>
<h4 align="center" style="border:medium; border-color:#000000"> <? echo $title; ?>,   </h4>

<table align="center" border=1 cellpadding=0 cellspacing=0 width=900 style='border-collapse:
 collapse;table-layout:fixed;width:1057pt;box-sizing: border-box;border-spacing: 0px;
 font-variant-ligatures: normal;font-variant-caps: normal;orphans:2;
 text-align:start;widows: 2;-webkit-text-stroke-width: 0px;text-decoration-style: initial;
 text-decoration-color: initial'>
 <col width=70 style='mso-width-source:userset;mso-width-alt:2560;width:53pt'>
 <col width=70 style='mso-width-source:userset;mso-width-alt:6473;width:53pt'>
 <col class=xl65 width=64 style='width:48pt; text-align: center;'>
 <col width=115 style='mso-width-source:userset;mso-width-alt:4205;width:86pt'>
 <col width=81 style='mso-width-source:userset;mso-width-alt:2962;width:61pt'>
 <col width=88 style='mso-width-source:userset;mso-width-alt:3218;width:66pt'>
 <col width=178 style='mso-width-source:userset;mso-width-alt:6509;width:134pt'>
 <col width=88 style='mso-width-source:userset;mso-width-alt:3218;width:66pt'>
 <col width=178 style='mso-width-source:userset;mso-width-alt:6509;width:134pt'>

 <thead> 
  <tr>
    <th class=xl66 height=32 class=xl65 width=70 style='height:24.0pt;width:53pt'>गट</th>
    <th class=xl66 height=32 class=xl65 width=70 style='height:24.0pt;width:53pt'>स्लिप नं.</th>
    <th class=xl66 height=32 class=xl65 width=70 style='height:24.0pt;width:53pt'>वाहन प्रकार</th>
    <th class=xl66 width=177 style='border-left:none;width:133pt'>निव्वळ वजन</th>
    <th align="center">कोड  नं.</th>
    <th class=xl66 scope="col" align="center">वाहतूकदार /मुकादम</th>
    <th class=xl66 scope="col" align="center">वाहन /टायर नंबर </th>
    <th class=xl66 scope="col" align="center">कोड  नं.</th>
    <th class=xl66>तोडणीदार </th>
  </tr>
</thead>

<?php
$section = '';
$date = '';
$date1 = '';
$wt = '';
$lastdate = array();
$dateArr = array();


      echo '
          <tr rowspan="3">
            <td colspan="9" scope="row"><div align="left"><strong>'.$printdataRes[0]['SC_CODE'].'&nbsp;'.$printdataRes[0]['SC_MNAME'].' </strong></div></td>
          </tr>';
         
      for ($i=0; $i <sizeof($printdataRes); $i++) { 
          if ($printdataRes[$i]['SC_CODE'] == $printdataRes[$i+1]['SC_CODE']) {
             $section = $section.$printdataRes[$i]['SC_CODE'].',';
  			     $date = $date.$printdataRes[$i]['WS_DATE'].',';  
             $wt = $wt.$printdataRes[$i]['TOTAL_RECEIVED_WT'].','; 
          }  
		  }
      $sectionstr = rtrim(implode(',',array_unique(explode(',', $section))),',');  
      $str = rtrim(implode(',',array_unique(explode(',', $date))),',');     
      $wtstr = rtrim(implode(',',array_unique(explode(',', $wt))),',');  
     // echo $wtstr;
      $Arr = explode(',', $str);
     // echo "string".$sectionstr;

      $sectionArr = explode(',', $sectionstr);

      for ($x=0; $x <sizeof($Arr) ; $x++) { 
         echo '
              <tr> <th></th>
                 <th>'.$Arr[$x].'</th>
              </tr>'; 
                 

      for ($y=0; $y <sizeof($sectionArr) ; $y++) { 
        // for ($x=0; $x <sizeof($Arr) ; $x++) { 
          for ($i=0; $i <sizeof($printdataRes); $i++) { 
              if ($sectionArr[$y] == $printdataRes[$i]['SC_CODE']) {
                  echo $sectionArr[$y]. '==' .$printdataRes[$i]['SC_CODE'].'<br/>';
                    if ($Arr[$x]  == $printdataRes[$i]['WS_DATE']) {      
                    echo $Arr[$x]. '==' .$printdataRes[$i]['WS_DATE'].'<br/>';
                         
                          echo '
                                <tr> <th></th>
                                    <th></th>
                                    <td>'.$printdataRes[$i]['VEHICLE_TYPE'] .'</td>  
                                    <td>'.$printdataRes[$i]['TOTAL_RECEIVED_WT'] .'</td>
                                    <td>'.$printdataRes[$i]['TRANS_CODE'] .'</td>
                                    <td>'.$printdataRes[$i]['TRANS_NAME'] .'</td> 
                                    <td>'.$printdataRes[$i]['TXN_VHNO'] .'</td> 
                                    <td>'.$printdataRes[$i]['HARV_CODE'] .'</td>
                                    <td>'.$printdataRes[$i]['HARV_NAME'] .'</td>
                                </tr>';  
                    } //date compare if  
              } // section compare    
          }

        } // all data for loop  
      } //date for loop

      for ($i=0; $i <sizeof($printdataRes); $i++) { 
        
          if ($printdataRes[$i]['SC_CODE'] != $printdataRes[$i+1]['SC_CODE']) {
              echo '
                  <tr rowspan="3">
                    <td colspan="9" scope="row"><div align="left"><strong>'.$printdataRes[$i+1]['SC_CODE'].'&nbsp;'.$printdataRes[$i+1]['SC_MNAME'].' </strong></div></td>
                  </tr>';

              echo '
                <tr> <th></th>
                   <th>'.$printdataRes[$i+1]['WS_DATE'].'</th>
                </tr>';

               echo '
                    <tr> <th></th>
                        <th></th>
                        <td>'.$printdataRes[$i+1]['VEHICLE_TYPE'] .'</td>  
                        <td>'.$printdataRes[$i+1]['TOTAL_RECEIVED_WT'] .'</td>
                        <td>'.$printdataRes[$i+1]['TRANS_CODE'] .'</td>
                        <td>'.$printdataRes[$i+1]['TRANS_NAME'] .'</td> 
                        <td>'.$printdataRes[$i+1]['TXN_VHNO'] .'</td> 
                        <td>'.$printdataRes[$i+1]['HARV_CODE'] .'</td>
                        <td>'.$printdataRes[$i+1]['HARV_NAME'] .'</td>
                    </tr>';  
          }

      } 

?>
  
</table>
  
<!-- </div>
 -->
<p id="data">  </p>


<br />
<br />
<br /> 

   <div class="ln_solid"></div>
      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">  
         <a href='javascript:history.back(1);'>Back</a>
      </div>
   </div>  
   
 </body>
</html>
<!-- <script type="text/javascript">
  function back(){
    alert('**');
  }

</script> -->
