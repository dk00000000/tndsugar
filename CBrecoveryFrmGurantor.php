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

$lgs->lg->trace("--START CANE BILL DEDUCTION CLAIM / ADJUSTMENT FORM--");

$user_accd = $_SESSION['USER_ACCD'];
$langPath = $_SESSION['LANGPATH'];
$lang = $_SESSION['LANG'];
$menu_code = $_SESSION['MENU_CODE'];
/*langPath for labels*/
$langPath = $langPath."general/".strtolower($lang)."/".$menu_code.".txt";
$lgs->lg->trace("LANG PATH :".$langPath);
$msgPath = 'util/readmsgs/'.strtolower($lang).'/client_msg.txt';
$error_msg = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('REQUIRED',$msgPath)));
//$rfObj->readData('REQUIRED',$msgPath);
//echo "Error msg : ".$error_msg;
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
	$mainRes = $curd->GetSelData($oldfilter, $newfilter, 'CBrecoveryFrmGurantor.ini', 'GETQRY');
	$lgs->lg->trace("--DEDUCTION CLAIM / ADJUSTMENT RESULT OF SELECTED RECORD--".json_encode($mainRes));
	//print_r($mainRes);
}


$oldLovFilter = array(':PCOMP_CODE', ':PAD_DOC');
$newLovFilter = array($_SESSION['COMP_CODE'], 'CANE');

$billType_lov = $dsbObj->getLovQry(102,$oldLovFilter,$newLovFilter);
$AlnDed_lov = $dsbObj->getLovQry(103,$oldLovFilter,$newLovFilter);
$branch_lov = $dsbObj->getLovQry(18,$oldLovFilter,$newLovFilter);
$society_lov = $dsbObj->getLovQry(104,$oldLovFilter,$newLovFilter);
$farmerCd_lov = $dsbObj->getLovQry(105,$oldLovFilter,$newLovFilter);


//langPath for color details
$langPath1 = $_SESSION['LANGPATH'];
$langPath1 = trim($langPath1."general/colorscheme.txt");
$headingColor = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('BRWSHDRCLR',$langPath1)));
$textColor = trim(preg_replace("/[\\n\\r]+/", " ", $rfObj->readData('BRWSTXTCLR',$langPath1)));
//End langPath for color details
?>

<script src="autocomp/autocomplete.css"></script>
<script type="text/javascript" src="autocomp/typeahead.js" ></script>
<script type="text/javascript">

$(document).ready(function() {
var action_flag = "<?php echo $action; ?>";
console.log('action flag : '+action_flag);

var error_msg = "<?php echo $error_msg; ?>";

var msg_FileName = '<?=$langPathMsg1; ?>';
var gs_valFileName = '<?=$langPathMsg; ?>';// USE GET VALIDATION MESSAGES BY PASSING HARDCODED NUMBERS
var backlnk = '<?=$backlnk; ?>';
var seq = '<?php echo $mainRes[0]['TXN_SEQ']; ?>';
var div = '<?php echo $mainRes[0]['TXN_DIVN']; ?>';
var loc = '<?php echo $mainRes[0]['PLOC_CODE']; ?>';
var series = '<?php echo $mainRes[0]['SSEG_CODE']; ?>';
var srno = '<?php echo $mainRes[0]['TXN_SRNO']; ?>';
var fnno;

//For disabled season added by ankushsss
$("#sncode").prop("disabled", true);

$("#seq").prop("readonly", true);
$("#brnch").autoselect(); 
$("#soc").autoselectdepend(); 
$("#aln_ded").first_gnt(); 

$("#btype").change(function(){
	var btype = $(this).val();
	$("#fortnight").empty();
	$.ajax({
			url: "CBrecoveryFrmGurantorServer.php",
			data: {action:'frnghtno',btype:btype},
			datatype: "json",
			success: function(response)
			{
				console.log(response);
				result = $.parseJSON(response);//for fortnight lov
				$('#fortnight').append($('<option>').text('Select'));
				$.each(result, function(i, value) 
				{
					$('#fortnight').append($('<option>').text(value['FORTNIGHT']).attr('value', value['SND_FNNO']));						
				});
			}
			}); //End of ajax
});//change

if(action_flag == 'view' || action_flag == 'update')
{
	$("#seq_number").val(seq);
	$("#serial_number").val(srno);
	//$('#location').append($('<option selected="selected">').text(loc).attr('value',loc));
	$('#series').append($('<option selected="selected">').text(series).attr('value',series));
	//$('#div_code').append($('<option selected="selected">').text(div).attr('value',div));
	$options = $('#div_code option');
		$options.filter('[value="'+div+'"]').prop('selected', true);
	$options = $('#location option');
		$options.filter('[value="'+loc+'"]').prop('selected', true);
	/*$options = $('#series option');
		$options.filter('[value="'+series+'"]').prop('selected', true);*/
	
	$("#location").prop('disabled',true);
	$("#series").prop('disabled',true);
	$("#div_code").prop('disabled',true);
	var sn_code = $('#sn_code').val();  

	fnno = '<?php echo $mainRes[0]['TXN_FORMNO']; ?>';
	var btype = '<?php echo $mainRes[0]['TXN_BTYPE']; ?>';

	$('#fortnight').empty();
	 $.ajax(
			{
				url: "CBrecoveryFrmGurantorServer.php",
				data: {action:'frnghtno',sn_code:sn_code,view:action_flag,btype:btype},
				datatype: "json",
				success: function(response)
				{
					console.log(response);
					result = $.parseJSON(response);//for fortnight lov
					$('#fortnight').append($('<option>').text('Select').attr('value', ''));
					$.each(result, function(i, value) 
					{
						var fnno1 = value['SND_FNNO'];						
						if(fnno1 == fnno)
						{
							$('#fortnight').append($('<option selected="selected">').text(value['FORTNIGHT']).attr('value', value['SND_FNNO']));//+'||'+value['SND_LOCK']
						}
						else
						{
							/*$('#fortnight').append($('<option>').text(value['SND_FNNO']+' || '+value['DT']).attr('value', value['SND_FNNO']+'||'+value['SND_LOCK']));*/
							$('#fortnight').append($('<option>').text(value['FORTNIGHT']).attr('value', value['SND_FNNO']));			
						}						
					});

					farmerValue();
				}
			
			} ); //End of ajax

}

if(action_flag == 'update')
{
	console.log("in update mode");
	$("#sncode").prop("disabled", true);

	$("#btn_submit").text('Update');
	$("#btn_reset").hide();
}

if(action_flag == 'view')
{
	console.log("in view mode");
	$("#sncode").prop("disabled", true);
	$("#sn_code").prop("readonly", true);
	$("#date").prop("disabled", true);
	$("#fortnight").prop("disabled", true);
	$("#btype").prop("disabled", true);
	$("#aln_ded, .first_gnt").prop("disabled", true);
	$("#brnch, .independant_class").prop("disabled", true);
	$("#soc, .dependant_class").prop("disabled", true);
	$("#farmer").prop("disabled", true);
	$("#gurantor").prop("disabled", true);
	$("#amt").prop("readonly", true);
	$("#rmrk").prop("readonly", true);
	$("#effect").prop("disabled", true);
		
	$("#btn_submit").hide();
	$("#btn_reset").hide();
	$("#btn_cancel").text('Back');
}
//For Submit Form
$("#btn_submit").click(function(event){

	event.preventDefault();
	var society = $("#soc, .dependant_class").val();
	var branch = $("#brnch, .independant_class").val();
    $("#sncode").prop("disabled", false);

	var res = validKeyInd();
	if(errCOUNT == 0)
	{
		$("#wait").show();
		$('#btn_submit').attr("disabled",true);
		$.ajax({
			url: "CBrecoveryFrmGurantorServer.php",
			data:$('#form_deductionClaim').serialize()+'&'+$.param({'action':action_flag}),
			datatype: "json",
			success: function(response){
				$("#wait").hide();
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


/*No use this statement after SEASON change
Change dated on 19-06-2018
*/
/*$('#sncode').on('change',function(){
	var sn_code = $(this).val();  
	$('#fortnight').empty();
	 $.ajax(
			{
				url: "CBrecoveryFrmGurantorServer.php",
				data: {action:'frnghtno',sn_code:sn_code},
				datatype: "json",
				success: function(response)
				{
					console.log(response);
					result = $.parseJSON(response);//for fortnight lov
					$('#fortnight').append($('<option>').text('Select').attr('value', ''));
					$.each(result, function(i, value) 
					{
						$('#fortnight').append($('<option>').text(value['SND_FNNO']+' || '+value['SND_FRDT']+' to '+value['SND_TODT']).attr('value', value['SND_FNNO']));						
					});
				}
			
			});//End of ajax
}); */

$('#fortnight').on('change',function(){
	var fortnight = $('#fortnight').val();//.split('||');
	if(fortnight[1] == 'Y' && fortnight[0] != fnno)
		swal('Select Correct Fortnight', "", "error");
	else
		$('#fortnight_hidden').val(fortnight);
	farmerValue();
}); //Fortnight

/*Dependant autocomplete for getting Farmers */
$("#farmer").keyup(function(){
	$("#suggesstion-box").hide();
	if($("#farmer").val().length >= 3) 
	{
		var txn_cons = $("#gurantor_hidden").val();
		var key = $(this).val();
		
		$.ajax({
		type: "GET",
		url: "CBrecoveryFrmGurantorServer.php",
		data:'type_string='+key+'&prt_code='+txn_cons+'&action=farmer',
		success: function(data){
			//alert(data)
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#farmer").css("background","#FFF");
		}
		});
	}//main if
});

/*Dependant autocomplete for getting Farmers */
$("#gurantor").keyup(function(){
	$("#suggesstion-box").hide();
	if($("#gurantor").val().length >= 3) 
	{
		var txn_accd = $("#farmer_hidden").val();
		var key = $(this).val();
		
		$.ajax({
		type: "GET",
		url: "CBrecoveryFrmGurantorServer.php",
		data:'type_string='+key+'&prt_code='+txn_accd+'&action=gurantor',
		success: function(data){
			//alert(data)
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#gurantor").css("background","#FFF");
		}
		});
	}//main if
});


}); //End of ready 



</script>

<script type="text/javascript">

function selectField(val) 
{
	//alert('**'+val);
	var value = val.split('***');
	var farmer_gurantor = value[1];
	$("#suggesstion-box").hide();
	var farmer_gurantor_val = farmer_gurantor.split("||");
	if(value[0] == 'farmer')
	{
		$("#farmer").val(farmer_gurantor);
		$("#farmer_hidden").val(farmer_gurantor_val[0].trim());
	}
	else
	{
		$("#gurantor").val(farmer_gurantor);
		$("#gurantor_hidden").val(farmer_gurantor_val[0].trim());
	}
	
	farmerValue();
}

//var table;
function farmerValue()
{
	var farmer_val = $('#farmer').val();
	var data =  farmer_val.split('||');
	data = data[0].trim()
	$('#farmer_hidden').val(data);
	var data2 = $("#farmer_hidden").val();
	//alert("data 2"+data2);
	var gurantor_val = $('#gurantor').val();
	var gdata =  gurantor_val.split('||');
	gdata = gdata[0].trim()
	$('#gurantor_hidden').val(gdata);
	var gdata2 = $("#gurantor_hidden").val();

	var season = $("#sncode").val() || $("#sn_code").val();
	var farmer = $("#farmer_hidden").val() || data;
	var formno = $("#fortnight_hidden").val() || ($("#fortnight").val().split('||'))[0];
	//alert(season+farmer+formno);
	table  = $('#gurantor_tbl').DataTable({		
			
			"destroy": true,

			"ajax":
				  {
					'url' : 'CBrecoveryFrmGurantorServer.php',
					'type': 'GET',
					
					'data': {
							 action : 'farmerTable',
							 season : season,
							 farmer : farmer,
							 formno : formno
							},
					
				  },
		});//End of datatable
	//table.destroy();
}
</script>

<style type="text/css">
.farmer-list{float:left;list-style:none;margin-top:-3px;padding:0;width:250px;position: absolute;overflow: scroll;height: 300px;z-index: 200}
.farmer-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
.farmer-list li:hover{background:#ece3d2;cursor: pointer;}
</style>

<!-- page content -->
<div class="right_col" role="main">
<div class="">

	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
				<span class="section"><?=$rfObj->readData('CRDT',$langPath); ?></span>
				<form id="form_deductionClaim" name="form_deductionClaim" class="form-horizontal form-label-left" novalidate>
				<ul class="contactus-list">
					<?
						require_once("layouts/trans_header.php");				
					?>
					
				<div class="panel panel-primary">
				<div class="panel-heading"><?=ucfirst($action); ?></div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="col-md-5 col-sm-5 col-xs-12">		
										<label class="control-label"><?=$rfObj->readData('SEQ',$langPath); ?></label>
									</div>
								<li>
									<div class="col-md-7 col-sm-7 col-xs-12">
									<input type="text" id="seq" name="seq" class="form-control col-md-7 col-xs-12" value="<?php if($action =='add'){ echo $next_seq[0]['NEXT_TXNSEQ']; } echo $mainRes[0]['TXN_SEQ']; ?> ">
									<span id="seq_error" style="color: red;"></span>
									</div>
								</li>
							</div>
						
							<div class="col-md-6 col-sm-6 col-xs-12">
							   <div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('DATE',$langPath); ?></label>
							   </div>

								<li>
									<div class="col-md-7 col-sm-7 col-xs-12">
										<input type="text" id="date" name="date" class="form-control calendar" value="<?php if($action =='add'){ echo date("d/m/Y"); } echo $mainRes[0]['TXN_DATE']; ?>">
										<span id="srv_date_error" style="color: red;"></span>
									</div>
								</li>
							</div>
						</div> <!-- //form-group -->
						
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
									<label class="control-label"><?=$rfObj->readData('CD',$langPath); ?></label>
								</div>
								<li>
									<div class="col-md-7 col-sm-7 col-xs-12">
									<? if($action == 'add') { ?>
										<select id ="sncode" name="sncode" class="form-control col-md-7 col-xs-12" valid="required" errmsg="<?=$rfObj->readData('REQUIRED',$msgPath); ?>" >
										<? for($i=0;$i<sizeof($season_lov);$i++)
										   {
										?>
											<option value="<?=$season_lov[$i]['SN_CODE']?>" <? if($_SESSION['SEASON'] == $season_lov[$i]['SN_CODE']){ echo 'selected="selected"'; }?>><?=$season_lov[$i]['SN_CODE']?></option>
										<? 
											}
										?>	
									    </select>	
								 	<? }//if 
								 		else { ?>
								 	    <input type="text" id="sn_code" name="sn_code" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['TXN_SEASON']; ?>" />
								 	<? }//else ?>
									</div>
								</li>
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
									<label class="control-label"><?=$rfObj->readData('BTYPE',$langPath); ?></label>
								</div>
								<li>
									<div class="col-md-7 col-sm-7 col-xs-12">
									<select id ="btype" name="btype" class="form-control col-md-7 col-xs-12" valid="required" errmsg="<?=$rfObj->readData('REQUIRED',$msgPath); ?>" >
											<option value="">Select</option>
											<? for($i = 0; $i < sizeof($billType_lov); $i++)
											   {
											?>
												<option value="<?=$billType_lov[$i]['BT_CODE']?>" <?php if($mainRes[0]['TXN_BTYPE'] == $billType_lov[$i]['BT_CODE']) { ?> selected="selected" <?php } ?> ><?=$billType_lov[$i]['BT_NAME']?>		
												</option>
											<? 
												}
											?>	
									</select>
									</div>
								</li>
							</div>
						</div> <!-- //form-group -->


<!-- ajax loader -->
<div id="wait" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;"><img src="images/loader.gif" width="64" height="64" />
    <br>Loading..
</div>							
<!-- ajax loader -->
						
						
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
									<label class="control-label"><?=$rfObj->readData('FTNT',$langPath); ?></label>
								</div>
								<li>
									<div class="col-md-7 col-sm-7 col-xs-12">
										<select id ="fortnight" name="fortnight" class="form-control col-md-7 col-xs-12" valid="required" errmsg="<?=$rfObj->readData('REQUIRED',$msgPath); ?>" >
										<option value="">Select</option>
										</select>
										<input type="hidden" id="fortnight_hidden" name="fortnight_hidden" value="<?=$mainRes[0]['TXN_FORMNO']; ?>"/>
									</div>
								</li>
							</div>
							
							
							<div class="col-md-6 col-sm-6 col-xs-12">
							    <div class="col-md-5 col-sm-5 col-xs-12">
									<label class="control-label"><?=$rfObj->readData('ALNDED',$langPath); ?></label>
								</div>
								<li><div class="col-md-7 col-sm-7 col-xs-12">
									<select id ="aln_ded" name="aln_ded" class="form-control" valid="required" errmsg="<?=$rfObj->readData('REQUIRED',$msgPath); ?>" >
											<option value="">Select</option>
											<? for($i = 0; $i < sizeof($AlnDed_lov); $i++)
											   {
											?>
												<option value="<?=$AlnDed_lov[$i]['AD_CODE']?>" <?php if($mainRes[0]['TXN_UNIT'] == $AlnDed_lov[$i]['AD_CODE']) { ?> selected="selected" <?php } ?> ><?=$AlnDed_lov[$i]['AD_CODE'].' || '.$AlnDed_lov[$i]['AD_NAME']?>		
												</option>
											<? 
												}
											?>	
									</select>
								</div></li>
							</div>
						</div> <!-- //form-group -->

						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-3 col-sm-3 col-xs-12">
									<label class="control-label "><?=$rfObj->readData('FARM',$langPath); ?></label>
								</div>
								<li><div class="col-md-9 col-sm-9 col-xs-12">
									<input type="text" name="farmer" id="farmer" size="80" class="form-control" placeholder="Type To Search..."  value="<?php echo $mainRes[0]['TXN_ACCD'] ?>" autocomplete="off" valid="required" errmsg="<?=$rfObj->readData('REQUIRED',$msgPath); ?>" /> 
									<div id="suggesstion-box"></div><!--Lov Suggestion using autocomplete-->
									<input type="hidden" id="farmer_hidden" name="farmer_hidden" value=""/>
								</div></li>
							</div>		
						</div> <!-- //form-group -->

						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-3 col-sm-3 col-xs-12">
									<label class="control-label "><?=$rfObj->readData('GRNTR',$langPath); ?></label>
								</div>
								<li><div class="col-md-9 col-sm-9 col-xs-12">
									<input type="text" name="gurantor" id="gurantor" size="80" class="form-control" placeholder="Type To Search..."  value="<?php echo $mainRes[0]['TXN_CONS'] ?>" autocomplete="off" valid="required" errmsg="<?=$rfObj->readData('REQUIRED',$msgPath); ?>" /> 
									<div id="suggesstion-box"></div><!--Lov Suggestion using autocomplete-->
									<input type="hidden" id="gurantor_hidden" name="gurantor_hidden" value=""/>
								</div></li>
							</div>		
						</div> <!-- //form-group -->
							
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
									<label class="control-label"><?=$rfObj->readData('AMT',$langPath); ?></label>
								</div>
								<li>
									<div class="col-md-7 col-sm-7 col-xs-12">
									<input type="text" id="amt" name="amt" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['TXN_AMT']; ?>" valid="required" errmsg="<?=$rfObj->readData('REQUIRED',$msgPath); ?>" >
									<span id="rate_error" style="color: red;"></span>
									</div>
								</li>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
									<label class="control-label"><?=$rfObj->readData('RMRK',$langPath); ?></label>
								</div>
								<div class="col-md-7 col-sm-7 col-xs-12">
									<input type="text" id="rmrk" name="rmrk" class="form-control col-md-7 col-xs-12" value="<?=$mainRes[0]['TXN_RMRK']; ?>">
									<span id="amt_error" style="color: red;"></span>
								</div>
							</div>
						</div> <!-- //form-group -->

					</div><!--//panel-body-->
				</div><!--//panel-->
			</ul>
					
			<div class="panel panel-primary">
	   			<div class="panel-heading"><?=$rfObj->readData('TITLE',$langPath); ?></div>
		  			<div class="panel-body">
						<!--Data table-->
						<div class="row">
						  <div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
								<!-- <button type="button" class="btn btn-primary glyphicon glyphicon-refresh" id="btn_refresh"></button> -->
								<div class="clearfix" ></div>
								</div><!--//x_title-->
								<div class="x_content">
				<table id="gurantor_tbl" class="table table-bordered dt-responsive" cellspacing="0" width="100%">
					<thead>
						<tr>
						<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="4"><?=$rfObj->readData('NO1',$langPath); ?>
						</th>
						<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="4"><?=$rfObj->readData('DATE1',$langPath); ?>
						</th>
						<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="4"><?=$rfObj->readData('FTNT1',$langPath); ?>
						</th>
						<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="4"><?=$rfObj->readData('DED1',$langPath); ?>
						</th>
						<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="4"><?=$rfObj->readData('AMT1',$langPath); ?>
						</th>
						<th style="background-color:<?=$headingColor; ?> ; color:<?=$textColor; ?>" width="4"><?=$rfObj->readData('GRNTR1',$langPath); ?>
						</th>							
						</tr>
					</thead>		
				</table>	 
								</div><!--x_content-->
							</div><!--x_panel-->
						  </div>
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
						
				</form>
				
				</div>
			</div>
		</div>
	</div>
</div>
</div>
	
	
<? include("footer.php");?>		