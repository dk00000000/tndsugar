<? 
require_once('dashboard.php');
include('readfile.php');
//require_once('header_menu.php');
$lgs = new Logs();  //use for log creation
$qryObj = new Query(); //user for read ini files
$dsbObj = new Dashboard(); //For user CRUD Operation
$rfObj = new ReadFile();  //Use fro Read text files
$lang=strtolower($_SESSION['LANG']);
$qryPath = "util/readquery/general/asn_dc.ini";
$langPath = "util/language/";
$menu_code=$_SESSION['MENU_CODE'];
$langPath = $langPath."general/".$lang.'/'.$menu_code.".txt"; 
$sp_code=$_SESSION['USER'];

include('AdvanceShipmentNotePO.php');//class file included
$soObj =new AdvanceShipmentNote();
   
	
$oldLovFilter = array(":PCOMP_CODE",":PMENU_CODE",":PSP_CODE",":PTXD_SEQ",":PTXN_SRNO");
$newLovFilter = array($_SESSION['COMP_CODE'],$menu_code,$_SESSION['USER'],$txn_seq,'');

 $action  = $_GET['view'];
 if($action=='add')
 {
  $orderQry = $qryObj->fetchQuery($qryPath,'Q001','ORDER_LIST',$oldLovFilter,$newLovFilter);
  $orderRes = $dsbObj->getData($orderQry);
	
	$dtlSize1 = sizeof($orderRes);
      for($i=0;$i<sizeof($orderRes);$i++)
          {
            $orderJsonRes[]=array_values($orderRes[$i]);
          }
          $orderJsonRes=json_encode($orderJsonRes,JSON_PRETTY_PRINT.';');
  }
  
$addQry = $qryObj->fetchQuery($qryPath,'Q001','ASN_ADD',$oldLovFilter,$newLovFilter);
$addRes = $dsbObj->getData($addQry);
$dtlSize1 = sizeof($addRes);
      for($i=0;$i<sizeof($addRes);$i++)
          {
            $productJsonRes[]=array_values($addRes[$i]);
          }
          $productJsonRes=json_encode($productJsonRes,JSON_PRETTY_PRINT.';');
  
//For Getting LOV Result
$nextvalRes = $dsbObj->getLovQry(62,$oldLovFilter,$newLovFilter);
$transpoterRes = $dsbObj->getLovQry(63,$oldLovFilter,$newLovFilter);
$divisionRes = $dsbObj->getLovQry(64,$oldLovFilter,$newLovFilter);
/* For generate dynamic back links*/	
$back_link = 'view_browse.php?menu_code='.$menu_code;
//get validation messages
$server_msg = strtolower($lang).'/main_msg.txt';
$client_msg = strtolower($lang).'client_msg.txt';

//echo "Action".$action;
if($action=='view' || $action=='update')
{
$array1 = $_GET['column_names'];
$colnames = explode(',', $array1);
$lgs->lg->trace("--Column Names--:".json_encode($colnames));
$oldfilter = array();
for($i = 0; $i < sizeof($colnames); $i++)
{
$oldfilter[$i] = ":".$colnames[$i];
}
$array2 = $_GET['rowdata'];
//$newfilter = explode(',', $array2);
$newfilter = json_decode($array2);
$lgs->lg->trace("--Row Data--:".json_encode($newfilter));
//FOR HEADER DATA
$HeaderdataQry = $qryObj->fetchQuery($qryPath,'Q001','GETDATAQRY',$oldfilter,$newfilter);
$HeaderdataRes = $dsbObj->getData($HeaderdataQry);

$orderQry = $qryObj->fetchQuery($qryPath,'Q001','ORDER_LIST',$oldfilter,$newfilter);
  $orderRes = $dsbObj->getData($orderQry);
	
	$dtlSize1 = sizeof($orderRes);
      for($i=0;$i<sizeof($orderRes);$i++)
          {
            $orderJsonRes[]=array_values($orderRes[$i]);
          }
          $orderJsonRes=json_encode($orderJsonRes,JSON_PRETTY_PRINT.';');

}//END OF VIEW AND UPDATE
$langPath1 = $_SESSION['LANGPATH'];
$langPath1 = trim($langPath1."general/colorscheme.txt");
$lgs->lg->trace("LANG PATH1 :".$langPath1);
$headingColor = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('BRWSHDRCLR',$langPath1)));
$textColor = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('BRWSTXTCLR',$langPath1))); 

	 $seq = $_GET['seq'];//get seq for edit records
    $mode = $_GET['mode'];
   

    if($seq == '')
    {
      /*Get Next TXNSEQ*/
    //$txn_seq='14967150';/*Hard code values only use for testing purpose*/
    $txn_seq = $soObj->getSeq($_SESSION['USER']);
    $lgs->lg->trace("--Seq--:".$txn_seq);  
    }
    else
    {
      $txn_seq = $seq;
      //$mode = 'view';
    }

    /*get price list of login cutomer*/
    $sp_code=$_SESSION['USER'];
	
?>
<? require_once("header.php");?> 
<? include("sidebar.php");?> 
<section>	
  <!-- page content --> 
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
      </div>
      <div class="clearfix">
      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <?php //echo "Param List Name".$_SESSION['PARAM_LIST']; ?>
              <span class="section">
                <?=$rfObj->readData('STATEMAST',$langPath); ?>
              </span>
            </div>
            <div class="x_content">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <?php  echo ucfirst($action); ?>
                </div>
                <div class="panel-body">
                  <form action="#" id="form_asn_add" method="post">        
                    <div class="row">
                      <div class="form-group">  
                        <div class="col-md-6">
                          <label for="name" class="col-md-4">
                            <?php echo $rfObj->readData('no',$langPath); ?>
                            <span style="color:red;">
                              </label>
                            <div class="col-md-8">
                              <input type="text" class="form-control" name="txn_seq" id="txn_seq" 
                                    <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_SEQ']?>" <? } ?> value="<?=$nextvalRes[0]['TXN_SEQ']?>" readonly/>
									 <span id="pcode_error" style="color: red;"></span>
                            </div>
                            </div>
                          <div class="col-md-6">  
                            <label for="mobile" class="col-md-4">
                              <?php echo $rfObj->readData('fordt',$langPath); ?>
                              <span style="color:red;">
                                </label>
                              <div class="col-md-8">
                                <input class="form-control"  type="text" name="for_date" id="for_date" 
                                 <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_RFDT1']?>" <? } ?> value="<? echo date("d/m/o")?>" readonly/> 
                              </div>
                              </div>
                          </div>
                        </div>
                        </br>
                      <div class="row">
                        <div class="form-group">
                          <div class="col-md-6">
                            <label for="name" class="col-md-4">
                              <?php echo $rfObj->readData('pcn',$langPath); ?>
                            </label>
                            <div class="col-md-8">
                              <input type="text" class="form-control" name="prt_ch_no" id="prt_ch_no" 

                              <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_REF1']?>" <? } ?> >
                              <span id="pono_error" style="color: red;">
                              </span>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <label for="mobile" class="col-md-4">Challan Date
                              <span style="color:red;">
                                </label>
                              <div class="col-md-8">
                                <input class="form-control"  type="text" name="for_date" id="for_date1" 
                                 <? if(isset($HeaderdataRes)){ if($action=='view' )  {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_RFDT1']?>" <? } ?> value="<? echo date("d/m/o")?>" readonly/>  
                              </div>
                              </div>
                          </div>  
                        </div>  
                        </br>
                      <div class="row" style="display:none;">
                        <div class="col-md-6" >
                          <label for="mobile" class="col-md-4">
                            <?php echo $rfObj->readData('moamt',$langPath); ?>
                          </label>
                          <div class="col-md-8">
                            <input type="tel" class="form-control" name="modvat_amt" id="modvat_amt" placeholder="">
                          </div>
                        </div>    
                      </div>
                      </br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="mobile" class="col-md-4">
                          <?php echo $rfObj->readData('trans',$langPath); ?>
                        </label>
                        <div class="col-md-8">
                          <select name="city" id="trans_code" name="trans_code" class="form-control" <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> disabled="disabled"<? }}?>>
                            <? for($i=0;$i<sizeof($transpoterRes);$i++){?>
                            <option value="<?=$transpoterRes[$i]['PRT_CODE']?>">
                              <?=$transpoterRes[$i]['PRT_CODE'].'||'.$transpoterRes[$i]['PRT_NAME']?>
                            </option>
                            <? } ?> 
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="mobile" class="col-md-4">Vehicle
                          <span style="color:red;">
                            </label>
                          <div class="col-md-8">
                            <input type="tel" class="form-control" name="vch_no" id="vch_no" 

                               <? if(isset($HeaderdataRes)){ if($action=='view'  ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_VHNO']?>" <? } ?> >

                           
                            <span id="vch_error" style="color: red;">
                            </span>
                          </div>
                          </div>  
                      </div>
                      </br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="mobile" class="col-md-4">L R Number
                        </label>
                        <div class="col-md-8">
                          <input type="tel" class="form-control" name="lrno" id="lrno" 

                               <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_LRNO']?>" <? } ?> > 
                              
                         
                          <span id="lrno_error" style="color: red;">
                          </span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="mobile" class="col-md-4">
                          <?php echo $rfObj->readData('lrdt',$langPath); ?>
                          <span style="color:red;">
                            </label>
                          <div class="col-md-8">
                            <input class="form-control"  type="text" name="for_date" id="for_date2" 
                                 <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_RFDT1']?>" <? } ?> value="<? echo date("d/m/o")?>" readonly/> 
                            <span id="error_lrdt" style="color: red;">
                            </span>
                          </div>
                          </div>    
                      </div>
                      </br>
                    <div class="row">
                      <div class="col-md-6">
                        <label class="col-md-4">E-way Bill no*
                        </label>
                        <div class="col-md-8">
                          <input type="text" id="e_way_billno" name="e_way_billno" class="form-control"
                                 <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_REF2']?>" <? } ?>>

                          <span id="ewb_error" style="color: red;">
                          </span>
                        </div>
                      </div>                                     
                      <!-- This filed added by Amit Dubey dated on 06-06-2018 -->
                      <div class="col-md-6">
                        <label class="col-md-4">E-Way Date*
                        </label>
                        <div class="col-md-8"> 
                        	<input class="form-control"  type="text" name="for_date" id="for_date3" 
                                 <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['TXN_RFDT1']?>" <? } ?>value="<? echo date("d/m/o")?>"  readonly/> 
                          <span id="error_e_way" style="color: red;">
                          </span>
                        </div>
                      </div> 
                    </div>
                    </br> 
                  <div class="row">
                    <div class="col-md-6">
                      <label class="col-md-4">
                        <?php echo $rfObj->readData('loc',$langPath); ?>
                      </label>
                      <div class="col-md-8">
                        <select name="city" id="div_code" name="div_code" class="form-control" <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> disabled="disabled"<? }}?>>
                          <? for($i=0;$i<sizeof($divisionRes);$i++){?>
                          <option value="<?=$divisionRes[$i]['DIV_DESC']?>">
                            <?=$divisionRes[$i]['DIV_DESC']?>
                          </option>
                          <? } ?> 
                        </select>
                      </div>
                    </div> 
                  </div>
                  </br>
                </form>
            </div>
          </div>
		  
          <!-- Add New Form Start -->
		  <? if($action!='view'){ ?>
          <div class="panel panel-success" id="form_view">
            <div class="panel-heading">
              <strong>Advance Shipment Note
              </strong>
            </div>
            <div class="panel-body">
              <div class="row" id="itemList">
                <div class="form-group"> 
                  <span id="count_error" style="color: red; margin-left: 800px;">
                  </span>
                  <table id="asnList" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Sr
                        </th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Number
                        </th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">TXD_RUNO
                        </th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Pending
                        </th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Dispatch Qty
                        </th>
						<th  style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Action</th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Item Code
                        </th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Unit
                        </th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="25%">Item Description
                        </th>
                      </tr>
                    </thead>   
                  </table>
                </div>
              </div>
            </div>
          </div>
		  
		  <? }?>
		  
          <div class="panel panel-default">
                  <div class="panel-body">
                   <table id="asnOrderDel" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Sr
                        </th>
						<th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Item Code
                        </th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Number
                        </th>
						 <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Unit
                        </th>
						<th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Dispatch Qty
                        </th>
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="50%">Item Description
                        </th>           
                        <th style="background-color:<?php echo $headingColor;?>; color:<?php echo $textColor;?>; text-align:left" width="5%">Action
                        </th>
                      </tr>
                    </thead>
                        </table>
                  </div>
                </div>  
				   
          <div class="panel panel-default" id="ans_action">
            <div class="panel-body">
              <div class="row">
                <div class="form-group">
                  <div class="col-md-12" style="text-align: center;">
                    <input class="btn btn-success" type="submit" name="add" id="btn_submit" value="Submit">
                    <input class="btn btn-info" type="button" name="add" id="btn_cancel" value="Cancel">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>   
      </div>
    </div>
  </div>
  </div>
</div>
<!-- /page content -->
</section>		
<? include("footer.php");?>
<script>
  var back_link = '<?php echo $back_link ?>';
  var valFileName = '<?php echo $server_msg ?>';
  var action ="<?php echo $action; ?>";
  
  
  //Cancel	
  //FOR CANCEL BUTTon
  $('#btn_back').on('click', function() {
    location.href = back_link;
  }
                   );
  //Cancel
</script>
<script>
  var error_cnt = 0;
  $(document).ready(function(){
  
  /*Get order list data*/					   
  var count = '<?php echo $cnt_product; ?>';
  var item_count=0; 
  var asnTable = $('#asnList').DataTable({
                        "displayLength":8,
                        "ordering": false ,
                        "responsive":true,
                        "data":<?php echo $productJsonRes; ?>,
                        columnDefs: [
                          { 
						   "targets": 4,
                          "data": null,
                          "render": function (data,type,row,meta) 
                            {
                              return "<input type='text' class='form-control' name='DISHPATCHQTY' onkeypress='return isNumber(event)' maxlength='10' value='' id = '"+data[0]+"'>";
                            }
                          },
                          {
                          "orderable": false,
                          "targets": 5,
                          "data": null,
                          "defaultContent":"<button class='btn btn-success'>ADD</button>"
                          },
                        ]
                 });
				 
				  var orderListTable = $('#asnOrderDel').DataTable({
                            "displayLength":5,
                            "responsive":true,
                            "destroy":true,
                            "data":<?php echo $orderJsonRes;?>,
                            columnDefs: [
                                	 {
                          "orderable": false,
                          "visible":true,
                          "targets": 6,
                          "data": null,
                          "defaultContent":"<button class='btn btn-danger'>Delete</button>"
                          },
                          ]
                          });
				
	/* on Add Button*/
	var cnt = 0;
  var itemCount = '<?php echo $cnt_item; ?>';
  cnt = '<?php echo $cnt_product; ?>';
  $('#asnList').on('click', 'button', function (e) {
       e.preventDefault();
        
        var item_data = asnTable.row($(this).parents('tr')).data();
		var item_rdno = item_data[4]; 
		var item_rono = item_data[5];
		var item_seq = item_data[1];
		var item_refno = item_data[3];
		var item_runo = item_data[0];
        var item_ser = item_data[1];
	    var item_runo1 = item_data[2];
        var item_desc = item_data[8];
        var item_unit = item_data[7];
		var item_code = item_data[6];
	    var pending_qty = parseFloat(item_data[3]);
        var id = "#"+item_runo; //set id to every selected textbox
        var item_qty = $(id).val(); //get item qty 
		
		var txn_seq = $("#txn_seq").val();
        var so_date = $("#for_date").val();
        var party_code = $("#prt_ch_no").val();
        var chln_date = $("#for_date1").val();
        var transporter = $("#trans_code").val();
        var vehicle = $("#vch_no").val();
    	var lrno = $("#lrno").val();
    	var lrdate = $("#for_date2").val();
    	var ewaybill = $("#e_way_billno").val();
    	var ewaydate = $("#for_date3").val();
    	var loaction = $("#div_code").val();

       if($("#prt_ch_no").val()==''){
          $("#pono_error").html("Please enter Party Challan No.");
          $("#prt_ch_no").focus();
          return false;
        }
        if($("#vch_no").val()=='')
        {
          $("#vch_error").html("Please enter Vehicle No.");
          $("#vch_no").focus();
          $("#pono_error").html("");
          return false;
        }
        
        if($("#e_way_billno").val()==''){
          $("#ewb_error").html("Please enter E-way bill number.");
          $("#e_way_billno").focus();
          return false;
        }

        
       /* if(Date.parse(txn_date2) < Date.parse(for_date3)){
          alert("Please select a different End Date.");
        }*/
       
        if(item_qty == '')
        {
          $(myid).css('border','1px solid red');
          return false;
        }else{
          $(id).css('border','');
        }
    console.log("Dis Qty "+item_qty+"\n Pending Qty"+pending_qty);
        if(item_qty>pending_qty)
        {
          swal({
              title: "Dispach Qty should less than pending Qty",
              timer: 1000,
              showConfirmButton: false
            });
          return false;
        }
        else
        {
          if(error_cnt==0)
          {
		      //alert("count"+error_cnt);
          var item_count = +count + +1;
		      count = item_count;
          $('#asnOrderDel').DataTable({
                            "displayLength":5,
                            "responsive":true,
                            "destroy":true,
                            "ajax":
                            {
                            'data':{action:'save',txn_seq:txn_seq,so_date:so_date,party_code:party_code,chln_date:chln_date,transporter:transporter,vehicle:vehicle,lrno:lrno,lrdate:lrdate,ewaybill:ewaybill,ewaydate:ewaydate,loaction:loaction,item_runo:item_runo,item_ser:item_ser,item_desc:item_desc,item_unit:item_unit,item_runo1:item_runo1,item_qty:item_qty,item_code:item_code,item_rdno:item_rdno,item_rono:item_rono,item_seq:item_seq,item_refno:item_refno,item_count:item_count},  
                            'url' : 'asndc_server.php',
                            'type': 'POST'
                            },
						columnDefs: [
                          {
                          "orderable": false,
                          "targets": 6,
                          "data": null,
                          "defaultContent":"<button class='btn btn-danger'>Delete</button>"
                          },
                          ]
                        });
           }//inner if
        }//else
    });	 
  
   
   $('#asnOrderDel').on('click', 'button', function (e) {
        e.preventDefault();  
		alert('on delete');
        var del_count = count-1;
        count=del_count;
        console.log("Count"+count);
		//alert(cnt)
        var item_data = orderListTable.row($(this).parents('tr')).data(); //return only tr data
		alert(count);
        console.log('Row data: '+item_data);
        var item_runo = item_data[0];
        console.log("Item Runo"+item_runo);
        var txn_seq = $("#txn_seq").val();
        //orderListTable.ajax.reload();

        orderListTable = $('#asnOrderDel').DataTable({
                            "displayLength":5,
                            "responsive":true,
                            "destroy":true,
                            "ajax":
                            {
                            'data':{action:'delete',txn_seq:txn_seq,item_runo:item_runo},  
                            'url' : 'asndc_server.php',
                            'type': 'POST'
                            },
                            columnDefs: [
                                  {
                                  "orderable": false,
                                  "targets": 6,
                                  "data": null,
                                  "defaultContent":"<button class='btn btn-danger'>Delete</button>"
                                  },
                                ]
                          });
    });
	
	var mode= '<?php echo $action; ?>';
	
   $("#btn_submit").on('click',function(event){
    event.preventDefault();
    var txn_seq = $("#txn_seq").val();
	 alert(mode);
	if(mode =='update')
    {
      if(error_cnt==0)
        {
        var txn_seq=$("#txn_seq").val();
        var sp_code = "<?php echo $sp_code; ?>";
        var prt_ch_no=$("#prt_ch_no").val();
        var lrno=$("#lrno").val();
        var lr_date=$("#for_date2").val();
        var for_date=$("#for_date").val();
        var today_date=$("#for_date1").val();
        var trans_code=$("#trans_code").val();
        var div_code=$("#div_code").val();
        var vch_no=$("#vch_no").val();
        var e_way_billno=$("#e_way_billno").val();
        var txn_date2=$("#for_date2").val();
        
      $.ajax({
              type:"POST",
              url:"asndc_server.php",
              data:{action:'update',txn_seq,sp_code:sp_code,prt_ch_no:prt_ch_no,lrno:lrno,lr_date:lr_date,for_date:for_date,today_date:today_date,div_code:div_code,vch_no:vch_no,trans_code:trans_code,e_way_billno:e_way_billno,txn_date2:txn_date2},
              datatype: "json",
                success: function(response)
                   {
                    var result = response;
                    if(result ='true')
                    {
                      swal({
                          title: "ASN Updated Successfully !",
                          timer: 2000,
                          showConfirmButton: false
                        });
              location.href = "view_browse.php?menu_code=E03071&MENU_STATUS:&menu_name=Advance Shipment Note";
                    }else{
                      swal({
                          title: "Try Again",
                          timer: 2000,
                          showConfirmButton: false
                        });
                    }
                   }//sucess
              });//ajax 
          }
        
    }//end of if
else
{
  if(count <=0 )
  {
    swal({
        title: "Atleast add one item...",
        timer: 2000,
        showConfirmButton: false
      });
    return false;
  }
  else
  {
    swal({
        title: "ASN Done Successfully !",
        timer: 2000,
        showConfirmButton: false
      });
    location.href = "view_browse.php.php?menu_code=E03071&MENU_STATUS:&menu_name=Advance Shipment Note-DC";
  }
}//end of 

     
});//btn_submit  
	  
	  $('#btn_cancel').on('click', function() {
    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this action!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }
        ).then(function () {
      location.href = back_link;
    }
              )
  }
     );
	  
	  
});//Ready
  

  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
  }
  //function
</script>
