<? 
	require_once('dashboard.php');
	include('readfile.php');
	//require_once('header_menu.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$dsbObj = new Dashboard(); 
	$rfObj = new ReadFile();
	$lang=strtolower($_SESSION['LANG']);
    $qryPath = "util/readquery/general/advance_paymast.ini";
	$langPath = "util/language/";
	$menu_code=$_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".$lang.'/'.$menu_code.".txt";

	$oldLovFilter = array(':PCOMP_CODE',':PMENU_CODE',':PSEASON');
  	$newLovFilter = array($_SESSION['COMP_CODE'],$_GET['menu_code'],$_SESSION['SEASON']);

	$seasonRes = $dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
	$vtypeRes = $dsbObj->getLovQry(41,$oldLovFilter,$newLovFilter);
 
	
	/* For generate dynamic back links*/	
    $back_link = 'view_browse.php?menu_code='.$menu_code;
 
	//get validation messages
	$server_msg = strtolower($lang).'/main_msg.txt';
	$client_msg = strtolower($lang).'client_msg.txt';

	/*for get action  */
	$action = $_GET['view'];
	
   /* view or update data display*/	
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
		$lgs->lg->trace("--Advance Payment DATA QUERY-:".$HeaderdataQry);
		$lgs->lg->trace("--Advance Payment QUERY RESULT--:".$HeaderdataRes);	

	}//END OF VIEW AND UPDATE

?>

<? require_once("header.php");?> 
<? include("sidebar.php");?>  
<style>
.ui-autocomplete{
	z-index:1500;
}
</style>
<section>	
	<!-- page content --> 
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
	</div>
<div class="clearfix"></div>
 <div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <?php //echo "Param List Name".$_SESSION['PARAM_LIST']; ?>
        <span class="section"><?=$rfObj->readData('ADDPAYMAST',$langPath); ?></span>
      </div>
      <div class="x_content">

      <div class="panel panel-primary">
        <div class="panel-heading"><?php  echo ucfirst($action); ?></div>
           <div class="panel-body">
               <form class="form-horizontal form-label-left" id="harvest_scmaster" onsubmit="return false;">
                   <ul class="contactus-list">
                   <!--  <span class="section">State Master</span> -->
                   
	    <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('SESON',$langPath); ?> 
               <span class="required">*</span>
            </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<select  class="form-control col-md-7 col-xs-12" name="season" id="season"  tabindex="2" valid="required" errmsg="<?=$rfObj->readData('SESON',$langPath); ?>"  disabled>
							  
								  <?php
								   for($i=0;$i<sizeof($seasonRes);$i++) {?>
									<option value="<?=$seasonRes[$i]['SN_CODE']?>" 
										<? if($action =='view' || $action =='update'){ if($HeaderdataRes[0]['HA_SCODE'] == $seasonRes[$i]['SN_CODE']){ ?> selected="selected"<? } }?> <? if($action =='add'){ if($_SESSION['SEASON']==$seasonRes[$i]['SN_CODE']) {?> selected="selected" <? } }?>><?=$seasonRes[$i]['SN_CODE']?> 
									</option>
	  								<? } ?>
	  						</select>

            </div>
        </div>
		
		 <div class="form-group">
	    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$rfObj->readData('DT',$langPath); ?>     <span class="required">*</span>
	    </label>
	    <div class="col-md-6 col-sm-6 col-xs-12 ">
		   <input type="text" class="form-control" <?php 
      if($action =='view'){?> readonly="true" <? }?>  name="date" id="date" placeholder="Select Date." valid="required" errmsg="Please Select Date" <?php if($action =='view' || $action =='update'){?> value="<? echo $HeaderdataRes[0]['HA_DATE'];}?>">
		
		</div>
	</div>					  

	   <div class="form-group">
           <label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('TYP',$langPath); ?> 
               <span class="required">*</span>
	    </label>
     <div class="col-md-6 col-sm-6 col-xs-12">
        <select id ="vtype" name="vtype" valid="required" errmsg="Please Enter Vehicle Type" class="form-control col-md-7 col-xs-12"<?php 
      if($action =='view'){?> disabled <? }?>
        >
         <option value="">Select</option> 
        <? for($i=0;$i<sizeof($vtypeRes);$i++) {?>
		<option value="<?=$vtypeRes[$i]['VT_CODE'];?>" <? if($HeaderdataRes[0]['HA_VTYPE']==$vtypeRes[$i]['VT_CODE']) {?> selected="selected" <? }?>><?=$vtypeRes[$i]['VT_MNAME']?></option>
		<? } ?>

        </select>
      </div>
    </div> 	
	
	<!-- ajax loader -->
		<div id="wait" class="ui-autocomplete" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;"><img src="images/loader.gif" width="64" height="64" />
            <br>Loading..
        </div>							
        <!-- ajax loader --> 				  
					    
	      
				  <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$rfObj->readData('AMT',$langPath); ?>     <span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 ">
				   <input type="text" class="form-control" <?php 
			  if($action =='view'){?> readonly="true" <? }?>  name="amnt" id="amnt" placeholder="Enter Amount." valid="required" errmsg="Please Enter Amount" <?php if($action =='view' || $action =='update'){?> value="<? echo $HeaderdataRes[0]['HA_AMT'];}?>" onkeypress="javascript:return isNumber(event)" >
				
				</div>
			</div>
			
			
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$rfObj->readData('S1',$langPath); ?>     <span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 ">
				   <input type="text" class="form-control slb" <?php 
			  if($action =='view'){?> readonly="true" <? }?>  name="slb1" id="slb1" placeholder="Enter Slab1 ." valid="required" errmsg="Please Enter Slab 1" <?php if($action =='view' || $action =='update'){?> value="<? echo $HeaderdataRes[0]['HA_SLABP1'];}?>" onkeypress="javascript:return isNumber(event)">
				
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$rfObj->readData('S2',$langPath); ?>     <span class="required"></span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 ">
				   <input type="text" class="form-control slb" <?php 
			  if($action =='view'){?> readonly="true" <? }?>  name="slb2" id="slb2" placeholder="Enter Slab2." s<?php if($action =='view' || $action =='update'){?> value="<? echo $HeaderdataRes[0]['HA_SLABP2'];}?>" onkeypress="javascript:return isNumber(event)">
				
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$rfObj->readData('S3',$langPath); ?>     <span class="required"></span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 ">
				   <input type="text" class="form-control slb" <?php 
			  if($action =='view'){?> readonly="true" <? }?>  name="slb3" id="slb3" placeholder="Enter Slab3."  <?php if($action =='view' || $action =='update'){?> value="<? echo $HeaderdataRes[0]['HA_SLABP3'];}?>" onkeypress="javascript:return isNumber(event)">
				
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$rfObj->readData('S4',$langPath); ?>     <span class="required"></span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 ">
				   <input type="text" class="form-control slb" <?php 
			  if($action =='view'){?> readonly="true" <? }?>  name="slb4" id="slb4" placeholder="Enter Slab4." <?php if($action =='view' || $action =='update'){?> value="<? echo $HeaderdataRes[0]['HA_SLABP4'];}?>" onkeypress="javascript:return isNumber(event)">
				
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$rfObj->readData('S5',$langPath); ?>     <span class="required"></span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 slb">
				   <input type="text" class="form-control" <?php 
			  if($action =='view'){?> readonly="true" <? }?>  name="slb5" id="slb5" placeholder="Enter Slab5."  <?php if($action =='view' || $action =='update'){?> value="<? echo $HeaderdataRes[0]['HA_SLABP5'];}?>" onkeypress="javascript:return isNumber(event)">
				<span id="pono_error" style="color: red;" class="field1">
                              </span>
				</div>
			</div>
	
		 <div class="form-group">
                <div class="col-md-6 col-md-offset-6">
                  <?php if($action=='add') { ?>
                  <button id="btn_submit" type="submit" name="submit" class="btn btn-success">Submit</button>
                  <button type="reset" name="reset" class="btn btn-info" id="btn_reset">Reset</button>
                    <button id="btn_cancel" type="button" class="btn btn-danger">Cancel</button>
				  <?php  } ?>
				   <?php if($action=='update') { ?>
                  <button id="btn_submit" type="button" class="btn btn-success">Update</button>
                    <button id="btn_cancel" type="button" class="btn btn-danger">Cancel</button>
				  <?php  } if($action=='view') {?>
				  <button id="btn_back" type="button" class="btn btn-info">Back</button>
                 <?php  } ?> 
                </div>
              </div>
			   <div class="ln_solid"></div>
			   </ul>
            </form>
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
var stat = 0;	
var ajax_inprocess = false;
var ajaxRequest;
$(document).ready(function() {

$("#date").datetimepicker({format : 'DD/MM/YYYY'});
	
var back_link = '<?php echo $back_link ?>';
var valFileName = '<?php echo $server_msg ?>';
var action ="<?php echo $action; ?>";

$(".slb").keyup(function()
  { 
  var s1 = $("#slb1").val();
	if (s1=='')
	{
	var s1=0;	
	}
	var s2 = $("#slb2").val();
	if (s2=='')
	{
	var s2=0;	
	}
	var s3 = $("#slb3").val();
	if (s3=='')
	{
	var s3=0;	
	}
	var s4 = $("#slb4").val();
	if (s4=='')
	{
	var s4=0;	
	}
	var s5 = $("#slb5").val();
	if (s5=='')
	{
	var s5=0;	
	}
	
	var total = parseFloat(s1) + parseFloat(s2)+parseFloat(s3) + parseFloat(s4)+parseFloat(s5);
	
	if(total > 100)
	{
	$("#pono_error").html("Pecentage Total Can Not Be Greater Than 100%.");
          $("#prt_ch_no").focus();
		  $("#wait").hide();
          return false;
      }
	  if(total <= 100){
	 $("#pono_error").html("");
	  }
     
	  
 });
 
/*Submit data and insert into table*/
 $('#btn_submit').on('click', function() {

	$("#wait").show();
	
 
	var res = validKeyInd();
	if(errCOUNT == 0)
	{	
	$.ajax({
          url: "advancepaymast_server.php",
          data:$('#harvest_scmaster').serialize()+'&'+$.param({'action':'fullform'})+
          '&'+$.param({'flag':action}),
		  datatype: "json",
          success: function(data){
          console.log(data);
          $("#wait").hide();
		  if(data == 1 && action =='add')
			   {
			    swal({
				  title: msg = getMsg(1,valFileName).trim(),//call getMsg function with message number and file name
				  timer: 10000,
				  type: 'success',
				  showConfirmButton: false
				});
				location.href = back_link;
			   }else if(data == 1 && action =='update')
			   {
			     swal({
				  title: msg = getMsg(2,valFileName).trim(),//call getMsg function with message number and file name
				  timer: 10000,
				  type: 'success',
				  showConfirmButton: false
				});
				location.href = back_link;
			   }else 
			   {
			   	 var msg = data.trim();
			     swal(msg, "", "error");
			   }			
			  }
         });//ajax
  }//if	 
  else{
  $("#wait").hide();
  } 	
});//Submit

//FOR CANCEL BUTTON
$('#btn_cancel').on('click', function() {
 swal({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this action!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes'
		}).then(function () {
			location.href = back_link;
		})
 
});//button Cancel	
 
//FOR CANCEL BUTTon
$('#btn_back').on('click', function() 
{
 location.href = back_link;
});//button back

});// Ready function

  /* function validate(s) {
    var rgx =  /^[-+]?[0-9]*\.?[0-9]+$;/
    return s.match(rgx);
 }
  //function*/

 // WRITE THE VALIDATION SCRIPT.
    function isNumber(evt) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;

        return true;
    }    
</script>
