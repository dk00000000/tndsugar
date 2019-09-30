<?php

require('curdClass.php');
require_once("header.php");
require_once('readfile.php');
include("sidebar.php");

$lgs = new Logs();
$rfObj = new ReadFile();
$qryObj = new Query();
$dsbObj = new Dashboard();
$curd = new CURD();

$lgs->lg->trace("--START CREDIT FUEL SALE FORM--");

$user_accd = $_SESSION['USER_ACCD'];
$langPath = $_SESSION['LANGPATH'];
$lang = $_SESSION['LANG'];
$menu_code = $_SESSION['MENU_CODE'];
/*langPath for labels*/
$langPath = $langPath."general/".strtolower($lang)."/".$menu_code.".txt";
$lgs->lg->trace("LANG PATH :".$langPath);

/*langPath for messages*/
$langPathMsg = strtolower($lang)."/".$menu_code."_msg.txt";
$lgs->lg->trace("LANG PATH :".$langPathMsg);
$langPathMsg1 = strtolower($lang)."/main_msg.txt";
$lgs->lg->trace("LANG PATH :".$langPathMsg1);
//echo $langPathMsg1;
/*back link*/
$backlnk = 'view_browse.php?menu_code='.$menu_code;

$action = $_GET['view'];/*get the action from comman browse*/

if($action == 'update' || $action == 'view')
{
	$array1 = $_GET['column_names'];
	$colnames = explode(',', $array1);
	$lgs->lg->trace("--Column Names--:".json_encode($colnames));
	$oldfilter = array();
	for($i = 0; $i < sizeof($colnames); $i++)
	{
		$oldfilter[$i] = ":".$colnames[$i];
	}
	$lgs->lg->trace("--Old Filter--:".json_encode($oldfilter));
	//$count = sizeof($oldfilter);

	$array2 = $_GET['rowdata'];
	$lgs->lg->trace("--Row Data Before --:".$array2);
	//$array2 = utf8_decode(rawurldecode($_GET['rowdata']));
	//$newfilter = explode(',', $array2);
	$newfilter = json_decode($array2);
	//print_r($newfilter);
	$lgs->lg->trace("--Row Data--:".json_encode($newfilter));
	
	$lgs->lg->trace("--I'm in update--".$action."Comp Code".$_SESSION['COMP_CODE']);
	//$mainRes = $scmastObj->getScmastData('SELECT_QRY',$oldfilter, $newfilter);
	array_push($oldfilter,':PCOMP_CODE');
	array_push($newfilter,$_SESSION['COMP_CODE']);
	$mainRes = $curd->GetSelData($oldfilter, $newfilter, 'cashFuelSale.ini', 'GETQRY');
	$lgs->lg->trace("--CREDIT FUEL SALE RESULT OF SELECTED RECORD--".json_encode($mainRes));
	//print_r($mainRes);
}

if($_SESSION['USER_CAT'] == 'U')
{
	$oldLovFilter = array(':PCOMP_CODE');
	$newLovFilter = array($_SESSION['COMP_CODE']);

	//$supp_lov = $curd->GetSelData($oldLovFilter, $newLovFilter, 'creditFuelSale.ini', 'SUPPLOV');
	$supp_lov = $dsbObj->getLovQry(65,$oldLovFilter,$newLovFilter);
	$lgs->lg->trace("--SUPPLIER LOV RESULT--".json_encode($supp_lov));
}

$getDateTime = $curd->GetSelData($oldLovFilter,$newLovFilter,'cashFuelSale.ini', 'GET_DT_TIME');
$lgs->lg->trace("--DATE/TIME--".json_encode($getDateTime));
$sale_date = $getDateTime[0]['SALE_DATE'];

//langPath for color details
$langPath1 = $_SESSION['LANGPATH'];
$langPath1 = trim($langPath1."general/colorscheme.txt");
$headingColor = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('BRWSHDRCLR',$langPath1)));
$textColor = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('BRWSTXTCLR',$langPath1)));
//End langPath for color details
?>

<script type="text/javascript">
$(function() {
  $('#date').datetimepicker({ format : 'DD/MM/YYYY HH:mm'});
});
</script>

<script type="text/javascript">

$(document).ready(function() {
var action_flag = "<?php echo $action; ?>";
console.log('action flag : '+action_flag);

<?php /*?>var count = <?php echo $count; ?>;<?php */?>

var msg_FileName = '<?=$langPathMsg1; ?>';
var gs_valFileName = '<?=$langPathMsg; ?>';// USE GET VALIDATION MESSAGES BY PASSING HARDCODED NUMBERS
var backlnk = '<?=$backlnk; ?>';
var seq = '<?php echo $mainRes[0]['TXN_SEQ']; ?>';
var srno = '<?php echo $mainRes[0]['TXN_SRNO']; ?>';
var div = '<?php echo $mainRes[0]['TXN_DIVN']; ?>';
var loc = '<?php echo $mainRes[0]['PLOC_CODE']; ?>';
var series = '<?php echo $mainRes[0]['SSEG_CODE']; ?>';
var inv_loctn = '<?php echo $mainRes[0]['TXN_FLOCN']; ?>';
var txn_date = '<?php echo $mainRes[0]['TXN_DATE']; ?>';
var user_cat = '<?php echo $_SESSION['USER_CAT']; ?>';

$("#seq").prop("readonly", true);
$("#fuel").prop("readonly", true); 
$("#alwdqty").prop("readonly", true); 
$("#rate").prop("readonly", true); 
$("#amt").prop("readonly", true); 
$("#suppcd").autoselect(); 

if (user_cat != 'U') 
{
	$("#suppcd, .independant_class").prop("disabled", true);
}

if(action_flag == 'update')
{
	console.log("in update mode");
	$("#sncode").prop("disabled", true);
	$("#btn_submit").text('Update');
	$("#btn_reset").hide();
}

if(action_flag == 'add')
{
	var loctn = $('#location').val();
	var series = $('#series').val();
	var divn = $('#div_code').val();
	$('#inv_loctn').empty();
	 $.ajax(
			{
				url: "cashFuelSaleServer.php",
				data: {action:'inv_loctn',loctn:loctn,divn:divn,series:series},
				datatype: "json",
				success: function(response)
				{
					console.log(response);
					var response = response.split('***');
					result1 = $.parseJSON(response[0]);
					result2 = response[1];
				
					$.each(result1, function(i, value) 
					{
						$('#inv_loctn').append($('<option>').text(value['L_PLOCMAST_PLOC_NAME']).attr('value', value['TXD_FLCCD']));
					});
				}
			} ); //End of ajax
}
if(action_flag == 'view' || action_flag == 'update')
{
	$("#seq_number").val(seq);
	$("#serial_number").val(srno);
	$("#header_date").val(txn_date);

	$options = $('#div_code option');
		$options.filter('[value="'+div+'"]').prop('selected', true);
	$options = $('#location option');
		$options.filter('[value="'+loc+'"]').prop('selected', true);
	$options = $('#series option');
		$options.filter('[value="'+series+'"]').prop('selected', true);
	
	$("#location").prop('disabled',true);
	$("#series").prop('disabled',true);
	$("#div_code").prop('disabled',true);

	var loctn = $('#location').val();
	var series = $('#series').val();
	var flocn = '<?php echo $mainRes[0]['TXN_FLOCN']; ?>';
	//alert('location : '+loctn+'series : '+series);
	$.ajax(
			{
				url: "cashFuelSaleServer.php",
				data: {action:'inv_loctn1',loctn:loctn,series:series},
				datatype: "json",
			
				success: function(response)
				{
					console.log(response);
					//alert(response);
					var result = $.parseJSON(response);
					$('#inv_loctn').empty();
					$.each(result, function(i, value) 
					{
						var flocn1 = value['TXD_FLCCD'];
						if(flocn1 == flocn)
						{
							$('#inv_loctn').append($('<option selected="selected">').text(value['L_PLOCMAST_PLOC_NAME']).attr('value', value['TXD_FLCCD']));
						}
						else
						{
							$('#inv_loctn').append($('<option>').text(value['L_PLOCMAST_PLOC_NAME']).attr('value', value['TXD_FLCCD']));
						}
					});
				}
			
			} ); //End of ajax
}

if(action_flag == 'view')
{
	console.log("in view mode");
	$("#sncode").prop("disabled", true);
		
	$("#btn_submit").hide();
	$("#btn_reset").hide();
	$("#btn_cancel").text('Back');
	
	$("#vhno").prop("readonly", true);
	$("#sn_code").prop("readonly", true);
	$("#slipno").prop("disabled", true);
	$("#isdqty").prop("readonly", true);
	$("#suppcd, .independant_class").prop("disabled", true);
	$("#inv_loctn").prop("disabled", true);
	$("#date").prop("disabled", true);
}

$("#btn_submit").click(function(event){

	event.preventDefault();
	var suppcode = $('#suppcd1').val();
	if(suppcode ==""){
      $('#suppcd_error').html("This Field is required and cannot be empty");
	}else{
     $('#suppcd_error').html("");
	}

	//alert($('#form_cashFuelSale').serialize());
	var res = validKeyInd();
	if(errCOUNT == 0)
	{ 
	   $("#sncode").prop('disabled',false);
		$.ajax({
			url: "cashFuelSaleServer.php",
			data:$('#form_cashFuelSale').serialize()+'&'+$.param({'action':action_flag}),
			datatype: "json",
			success: function(response){
				var ls_result = response;
				if(ls_result == 1) 
				{ 
					console.log("Record Inserted Successfully !!"+ls_result);
					if(action_flag == 'update')
					{
						var msg = getMsg(2,msg_FileName);
					}
					else
					{
						var msg = getMsg(1,msg_FileName);
					}
					swal(msg, "", "success");
					location.href = backlnk;
				}
				else
				{
					console.log("Ohh :"+ls_result); 
					var msg = getMsg(3,msg_FileName);
					var ls_result = ls_result.trim();
					swal(ls_result, "", "error");
				}
			}  
		});//ajax
	}//if

});//btn_submit

/*Code on Cancle Button*/
$('#btn_cancel').on('click',function(){
  $(function() {
    location.href = backlnk;
      });
});//cancel

$("#date").on('change blur ', function(e) {
	//alert('xbvkjdbxk');
	getDate();
});

$('#sncode').on('change',function(){
	var loctn = $('#location').val();
	var series = $('#series').val();
	var divn = $('#div_code').val();
	var sn_code = $(this).val();
	var txn_date = $('#date').val();
	var fuel = $("input[name='fuel']:checked").val();

	 $.ajax(
			{
				url: "cashFuelSaleServer.php",
				data: {action:'inv_loctn',sn_code:sn_code,loctn:loctn,divn:divn,series:series,txn_date:txn_date,fuel:fuel},
				datatype: "json",
				
				success: function(response)
				{
					console.log(response);
					//alert(response);
					var result = response.split('***');
					$('#rate').val(result[1]);
					//$('#inv_loctn').html(result[0]);
					var result1 = $.parseJSON(result[0]);
					//alert(result1);
					$('#inv_loctn').empty();
					$.each(result1, function(i, value) 
					{
						$('#inv_loctn').append($('<option>').text(value['L_PLOCMAST_PLOC_NAME']).attr('value', value['TXD_FLCCD']));
					});
				}
			
			} ); //End of ajax
});


$('#suppcd, .independant_class').on('change',function(){
	//alert('changed');
	var sn_code = $('#sncode').val() || $('#sn_code').val();
	var suppcd = $(this).val().split(' || ');
	$('#suppcd1').val(suppcd[0].trim());
	//alert("Season Code"+sn_code+"Supp"+suppcd[0]);
	var fuel = $("input[name='fuel']:checked").val();
	var txn_date = $('#date').val();
	//alert('fuel'+fuel);
	
	 $.ajax(
			{
				url: "cashFuelSaleServer.php",
				data: {action:'rate',sn_code:sn_code,suppcd:suppcd[0].trim(),fuel:fuel,txn_date:txn_date},
				datatype: "json",
			
				success: function(response)
				{
					console.log(response);
					//alert(response);
					var result = response.split('***');
					$('#rate').val(result[0]);
				}
			
			} ); //End of ajax
});

$('#isdqty').on('blur',function(){
      var isdqty = $(this).val();
	  var rate = $('#rate').val();
	  //alert('isdqty :'+isdqty+'alwdqty : '+alwdqty+'rate : '+rate);
	  if(rate != '')
	  {
	  	var amt = parseFloat(rate) * parseFloat(isdqty); 
		//alert('amount : '+amt);
		$('#amt').val(amt.toFixed(2));
	  }

    });


} ); //End of ready 


function getDate()
{
	//alert("in function");
	var sn_code = $('#sncode').val() || $('#sn_code').val();
	var suppcd = $('#suppcd, .independant_class').val().split(' || ');
	$('#suppcd1').val(suppcd[0].trim());
	var fuel = $("input[name='fuel']:checked").val();
	var txn_date = $('#date').val();
	var loctn = $('#location').val();

	$("#wait").show();
	 $.ajax(
			{
				url: "cashFuelSaleServer.php",
				data: {action:'rate',sn_code:sn_code,suppcd:suppcd[0].trim(),fuel:fuel,txn_date:txn_date,loctn:loctn},
				datatype: "json",
			
				success: function(response)
				{
					$("#wait").hide();
					console.log(response);
					//alert(response);
					var result = response.split('***');
					$('#rate').val(result[0]);
					$('#header_date').val(result[1]);
				}
			
			} ); //End of ajax
}
</script>



<!-- page content -->
<div class="right_col" role="main">
<div class="">

	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
				<span class="section"><?=$rfObj->readData('CASH',$langPath); ?></span>
				<form id="form_cashFuelSale" name="form_cashFuelSale" class="form-horizontal form-label-left" novalidate>
				<ul class="contactus-list">
					<?
						require_once("layouts/trans_header.php");				
					?>
					
				<div class="panel panel-primary">
				<div class="panel-heading"><?=$rfObj->readData('ADD',$langPath); ?></div>
					<div class="panel-body">
						<div class="row">
						<div class="form-group row">
							<div class="col-md-6">
								<input type="hidden" id="txn_refseq" name="txn_refseq" value=""/>
							</div>
							<div class="col-md-6">
								<input type="hidden" id="txn_doc" name="txn_doc" value=""/>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('SEQ',$langPath); ?></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="seq" name="seq" class="form-control col-md-7 col-xs-12" value="<?php if($action =='add'){ echo $next_seq[0]['NEXT_TXNSEQ']; } echo $mainRes[0]['TXN_SEQ']; ?>">
								<span id="seq_error" style="color: red;"></span>
							</div>
							</div>
						
							<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('DATE',$langPath); ?></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="date" name="date" class="form-control col-md-7 col-xs-12" value="<?php if($action =='add'){ echo $sale_date; } echo $mainRes[0]['TXN_DATE1']; ?>">
								<span id="srv_date_error" style="color: red;"></span>
							</div>
							</div>
						</div><!--//form-group-->
						
						<div class="form-group row">
							<div class="col-md-6">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('CD',$langPath); ?></label>
								<li><div class="col-md-9 col-sm-9 col-xs-12">
									<? if($action == 'add') { ?>
										<select id ="sncode" name="sncode" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Select Season Year." disabled="disabled">
										<option value="">Select</option>
										<? for($i=0;$i<sizeof($season_lov);$i++)
										   {
										?>
											<option <? if($_SESSION['SEASON']==$season_lov[$i]['SN_CODE']) {?> selected="selected" <? } ?> value="<?=$season_lov[$i]['SN_CODE']?>"><?=$season_lov[$i]['SN_CODE']?></option>
										<? 
											}
										?>	
									    </select>	
								 	<? }//if 
								 		else { ?>
								 	    <input type="text" id="sn_code" name="sn_code" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['TXN_SEASON']; ?>" />
								 	<? }//else ?>
								</div></li>
							</div>
							
							<div class="col-md-6">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('VHNO',$langPath); ?></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input type="text" id="vhno" name="vhno" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['TXN_VHNO']; ?>">
								</div>
							</div>
						</div><!--//form-group-->
						
						<div class="form-group row">							
							<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('FTYPE',$langPath); ?></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="btn-group" data-toggle="buttons">
								<label><input type="radio" id="ftypeP" name="fuel" value="P" <? if($mainRes[0]['DR_FTYPE'] == 'P') {?> checked='checked' <?php } ?> > &nbsp; <?=$rfObj->readData('P',$langPath); ?> &nbsp;</label>
								<label><input type="radio" id="ftypeD" name="fuel" value="D" <? if($mainRes[0]['DR_FTYPE'] == 'D') {?> checked='checked' <?php } ?> checked="checked"> <?=$rfObj->readData('D',$langPath); ?></label>
								</div>
							</div>
							</div>
							<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('ISDQTY',$langPath); ?></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="isdqty" name="isdqty" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['TXN_BLEXRT']; ?>">
								<span id="isdqty_error" style="color: red;"></span>
							</div>
							</div>
						</div><!--//form-group-->
						
						<div class="form-group row">
							<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('RT',$langPath); ?></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="rate" name="rate" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['TXN_NETWT']; ?>">
								<span id="rate_error" style="color: red;"></span>
							</div>
							</div>
							
							<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('AMT',$langPath); ?></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="amt" name="amt" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['TXN_AMT']; ?>">
								<span id="amt_error" style="color: red;"></span>
							</div>
							</div>
						</div><!--//form-group-->
						
						<div class="form-group row">
							<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('LOCTN',$langPath); ?></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
							
								<select id ="inv_loctn" name="inv_loctn" class="form-control col-md-7 col-xs-12">
									<option>--Select--</option>
								</select>
							
								<span id="loctn_error" style="color: red;"></span>
							</div>
							</div>
							
							<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('SUPPCD',$langPath); ?></label>
							<div class="col-md-9 col-sm-9 col-xs-12">	
								<select id ="suppcd" name="suppcd" class="form-control col-md-7 col-xs-12" >
										<option value="">Select</option>
										<? for($i = 0; $i < sizeof($supp_lov); $i++)
										   {
										?>
											<option value="<?=$supp_lov[$i]['PRT_CODE']?>" <?php if($action != 'add' && $mainRes[0]['TXN_ACCD'] == $supp_lov[$i]['PRT_CODE']) { ?> selected="selected" <?php } ?> ><?=$supp_lov[$i]['PRT_CODE'].' || '.$supp_lov[$i]['PRT_NAME']?>		
											</option>
										<? 
											}
										?>	
								</select>
								<input type="hidden" id="suppcd1" name="suppcd1" value="<?=$mainRes[0]['TXN_ACCD']; ?>">
								<span id="suppcd_error" style="color: red;"></span>
							</div>
							</div>
						</div><!--//form-group-->

						</div>
					</div><!--//panel-body-->
				</div><!--//panel-->
						
				
					<div class="ln_solid"></div>
					<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
					<button class="btn btn-success" type="submit" id="btn_submit">Submit</button>
					<button class="btn btn-info" type="reset" id="btn_reset">Reset</button>
					<button class="btn btn-danger" type="button" id="btn_cancel">Cancel</button>
					</div>
					</div>
						
				</ul>
				</form>
				
				</div>
			</div>
		</div>
	</div>
</div>
</div>
	
	
<? include("footer.php");?>		