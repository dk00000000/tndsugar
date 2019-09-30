<?php 
  //session_start();
  require_once('dashboard.php');
  include('readfile.php');
  //require_once('header_menu.php');
   
  //For number to marathi word conversion
  require_once("numbertomarathiword.php");
  $marathinumber = new NumbertoMarathi;

  setlocale(LC_MONETARY, 'en_IN');


  $lgs = new Logs();
  $qryObj = new Query();
  $dsbObj = new Dashboard(); 
  $rfObj = new ReadFile();
  $lang=strtolower($_SESSION['LANG']);
  $qryPath = "util/readquery/general/bank_payment.ini";

   $menu_name = $_GET['menu_name'];
  //$compcode = $_SESSION['COMP_CODE'];
   $compcode = 'DS'; 

    if(isset($_GET['season'])){    
       $season = $_GET['season'];
       $seasontext = $season;
    } 
    else{
       $season='';
       $seasontext = 'All';
    } 
    
    if(isset($_GET['fortnight'])){    
      $fornight = explode('||',$_GET['fortnight']);
      $fornighttext = $fornight[1];
    } 
    else{
       $fornight='';
       $fornighttext='All';
    } 
    if(isset($_GET['contract_type'])){    
      $contract_type = explode('||',$_GET['contract_type']);
      $contract_typetext = $contract_type[1];
    } 
    else{
      $contract_type='';
      $contract_typetext='All';
    } 
    if(isset($_GET['bill_type'])){    
      $bill_type = explode('||',$_GET['bill_type']);
      $bill_typetext = $bill_type[1];
    } 
    else{
       $bill_type='';
       $bill_typettext='All';
    } 

  $oldFilter = array(':PCOMP_CODE');
  $newFilter = array( $compcode);
  $compnameQry = $qryObj->fetchQuery($qryPath,'Q001','COMPNAME',$oldFilter,$newFilter);
  $compnameaRes = $dsbObj->getData($compnameQry);

  $oldRptFilter = array(':PCOMP_CODE',':PTXN_SEASON',':PFORTNIGHT',':PCT_CODE',':PBT_CODE');
  $newPrtFilter = array($compcode,$season,$fornight[0],$contract_type[0],$bill_type[0]);
 
  //Call Procedure
  $callProc = $qryObj->fetchQuery($qryPath,'Q001','PROCEDURE',$oldRptFilter,$newPrtFilter);
  $ProcRes = $dsbObj->getData($callProc);
  $lgs->lg->trace("In BANK PAYMENT EXCEL Procedure: ".$callProc);
   
  $printdataQry = $qryObj->fetchQuery($qryPath,'Q001','SELECTQUERY_EXCEL',$oldRptFilter,$newPrtFilter);
  $printdataRes = $dsbObj->getData($printdataQry);

  //echo $callProc." <br>*********************************************".$printdataQry;
  $lgs->lg->trace("In BANK PAYMENT EXCEL query: ".$printdataQry);
  $lgs->lg->trace("In BANK PAYMENT EXCEL result: ".json_encode($printdataRes));
 
  //echo json_encode($printdataRes);
  $rowcnt = sizeof($printdataRes);

  for($i=0;$i<sizeof($printdataRes);$i++)
  {
    $GetDataJsonRes[]=array_values($printdataRes[$i]);
  }
    
  $GetDataJsonRes=json_encode($GetDataJsonRes,JSON_PRETTY_PRINT.';');

  $colarr = array();
/*  array_push($colarr,'अनु क्र.','बागायतदार कोड','बागायतदार नाव','निव्वळ देय','आय एफ एस सी','खाते क्रमांक','शाखा कोड','शाखा नाव');*/

  array_push($colarr,'Sr No.','Contractor Code','Contractor Name','Amount to Pay','IFSC Code','Account Number','Branch Code','Branch Name');

  require_once("header.php");
  require_once("sidebar.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bank Payment Generation Export to Excel</title>

</head>
<body style="font-size:13px; font-family: verdana">  
<section> 
  <!-- page content --> 
  <div class="right_col" role="main">
    <div class="">
     <div class="clearfix"></div>
      <table id="example" class="table table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
          <? for($i=0;$i<sizeof($colarr);$i++){?>
            <th><?=$colarr[$i]?></th>
          <? }?>
          </tr>
        </thead>    
      </table>  
    </div>
  </div>
</section>

<script type="text/javascript">
 $(document).ready(function() {
   var table  = $('#example').DataTable({
        <?
          echo '"data":'.$GetDataJsonRes.',';
        ?>
        /*"stateSave": true,
      "deferRender": true,*/
      "dom": 'Blftirp',
    
      "stateSave": true,

      "buttons": [
      {
            "extend":'excel',
            "text":'',
            "exportOptions": {
                "columns": [
                 <? for($i=0;$i<sizeof($colarr);$i++){
                    echo $i.',';
                  } ?>
                ]
            },
            "className":'glyphicon glyphicon-download-alt',
            "title" : <?php echo"'".$menu_name."'" ?>,
            "filename":<?php echo"'".$menu_name."'" ?>,
            "orientation":'portrait',
            "titleAttr":'Export to Excel'
          },
          {
            "extend":'copy',
            "text":'',
            "exportOptions": {
                "columns": [ <? for($i=0;$i<sizeof($colarr);$i++){
                              echo $i.',';
                            } ?>
                       ]
            },
            "className":'glyphicon glyphicon-file',
            "titleAttr":'Copy'
          },
      ]
      
    });//Datatable
  }); //ready function
</script>
<? include("footer.php");?>
</body>
</html>
