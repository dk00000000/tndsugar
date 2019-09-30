<?php

//require('DIVMAST.php');
require('curdClass.php');
require_once("header.php");
require_once('readfile.php');
include("sidebar.php");

$lgs = new Logs();
$rfObj = new ReadFile();
$qryObj = new Query();
$dsbObj = new Dashboard();
//$dvmastObj = new Dvmast();
$curd = new CURD();

$lgs->lg->trace("--START HTBLTMAST FORM--");

$langPath = $_SESSION['LANGPATH'];
$lang = $_SESSION['LANG'];
$menu_code = $_SESSION['MENU_CODE'];
$comp_code = $_SESSION['COMP_CODE'];
/*langPath for labels*/
$langPath = $langPath."general/".strtolower($lang)."/".$menu_code."_lbl.txt";
$lgs->lg->trace("LANG PATH :".$langPath);

/*langPath for messages*/
$langPathMsg = strtolower($lang)."/".$menu_code."_msg.txt";
$lgs->lg->trace("LANG PATH :".$langPathMsg);
$langPathMsg1 = strtolower($lang)."/main_msg.txt";
$lgs->lg->trace("LANG PATH :".$langPathMsg1);
//echo $langPathMsg1;

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
	//$mainRes = $dvmastObj->getDvmastData('SELECT_QRY',$oldfilter, $newfilter);
	array_push($oldfilter,':PCOMP_CODE');
	array_push($newfilter,$comp_code);
	$mainRes = $curd->GetSelData($oldfilter, $newfilter, 'HTBLTMAST.ini', 'GETUPDDATA');
	$lgs->lg->trace("--HTBLTMAST RESULT OF SELECTED RECORD--".json_encode($mainRes));
	//print_r($mainRes);
}

$oldLovFilter = array(':PCOMP_CODE', ':PTBLNM');
$newLovFilter = array($_SESSION['COMP_CODE'],'HTBLTMAST');
$btCodeRes = $dsbObj->getLovQry(4,$oldLovFilter,$newLovFilter);
$lgs->lg->trace("--btCodeRes--".json_encode($dvCodeRes));

?>

<script>

$(document).ready(function() {

var action_flag = "<?php echo $action; ?>";
console.log('action flag : '+action_flag);

<?php /*?>var count = <?php echo $count; ?>;<?php */?>

var msg_FileName = '<?=$langPathMsg1; ?>';
var gs_valFileName = '<?=$langPathMsg; ?>';
var backlnk = '<?=$backlnk; ?>';

$("#bt_code").prop("readonly", true); 
if(action_flag == 'update')
{
	console.log("in update mode");
	$("#btn_submit").text('Update');
	$("#btn_reset").hide();
	$(".panel-heading").text('Edit');
	
}

if(action_flag == 'view')
{
	console.log("in view mode");
	$("#bt_name").prop("readonly", true); 
	$("#bt_mname").prop("readonly", true);
	
	$("#btn_submit").hide();
	$("#btn_reset").hide();
	$("#btn_cancel").text('Back');
	$(".panel-heading").text('View');
}

$("#btn_submit").click(function(event){

	    event.preventDefault();
		
        		  
		$.ajax({
              url: "billtypeserver.php",
              //data:$('#form_fgmast').serialize()+'&'+$.param({'action':action_flag, oldfilter:oldfilterArr}),
			  data:$('#form_bltmast').serialize()+'&'+$.param({'action':action_flag}),
			  //use $_REQUEST for values of textboxes and $_GET for action
              datatype: "json",
              success: function(response){
              var ls_result = response;
				  if(ls_result == 1) 
				  { 
					console.log("Record Inserted Successfully !!"+ls_result);
					if(action_flag == 'update')
					{
						var msg = getMsg(2,msg_FileName);
						//var msg = 'Record Updated Successfully !';
					}
					else
					{
						var msg = getMsg(1,msg_FileName);
						//var msg = 'Record Inserted Successfully !';
					}
					swal({
						 title: msg,
						 timer: 2000,
						 showConfirmButton: false
						});
					//swal(msg, "Please Click Ok !", "success");
					location.href = backlnk;
				  }
				  else
				  {
					console.log("Ohh :"+ls_result); 
					var msg = getMsg(3,msg_FileName);
					//var msg = 'Something went wrong!';
					/*swal({
						 title: msg,//"Oops there is some problem !",
						 timer: 2000,
						 showConfirmButton: false
						});*/
					swal(msg, "Please Click Ok !", "Failure");
				  }
              }  
            });
                
});//btn_submit

/*Code on Cancle Button*/
  $('#btn_cancel').on('click',function(){
      $(function() {
        location.href = backlnk;
          });
    });//cancel

} ); //End of ready 

</script>

<!-- page content -->
<div class="right_col" role="main">
<div class="">

	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
				<form id="form_bltmast" name="form_bltmast" class="form-horizontal form-label-left" novalidate>
				<span class="section"><?=$rfObj->readData('BLTMST',$langPath); ?></span>
				<div class="panel panel-primary">
				<div class="panel-heading"><?=$rfObj->readData('ADD',$langPath); ?></div>
					<div class="panel-body">
					
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $rfObj->readData('CD',$langPath); ?></label>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<input type="text" id="bt_code" name="bt_code" class="form-control col-md-7 col-xs-12" placeholder="Enter Code" value="<?php if($action == 'add')
												 {	echo $btCodeRes[0]['CODE']; }
												 else 
												 {	echo $mainRes[0]['HT_CODE']; } ?>">
								<span id="dv_code_error" style="color: red;"></span>
							</div>
						</div><!--//form-group-->
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('NME',$langPath); ?></label>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<input type="text" id="txtEnglish" name="bt_name" class="form-control col-md-7 col-xs-12 txtEnglish" placeholder="Enter Name in English" value="<?php echo $mainRes[0]['HT_NAME']; ?>">
								<span id="dv_name_error" style="color: red;"></span>
							</div>
						</div><!--//form-group-->
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('NMM',$langPath); ?></label>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<input type="text" id="txtMarathi" name="bt_mname" class="form-control col-md-7 col-xs-12 txtMarathi" placeholder="Enter Name in Marathi" value="<?php echo $mainRes[0]['HT_MNAME']; ?>">
								<span id="dv_name_error" style="color: red;"></span>
							</div>
						</div><!--//form-group-->
						
						<div class="ln_solid"></div>
						<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<button class="btn btn-success" type="submit" id="btn_submit">Submit</button>
						<button class="btn btn-info" type="reset" id="btn_reset">Reset</button>
						<button class="btn btn-danger" type="button" id="btn_cancel">Cancel</button>
						</div>
						</div>
						
					</div><!--//panel-body-->
				</div><!--//panel-->
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
			
			
<? include("footer.php");?>		