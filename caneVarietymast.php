<?php

//require('SECMAST.php');
require('curdClass.php');
require_once("header.php");
require_once('readfile.php');
include("sidebar.php");

$lgs = new Logs();
$rfObj = new ReadFile();
$qryObj = new Query();
$dsbObj = new Dashboard();
//$scmastObj = new Secmast();
$curd = new CURD();

$lgs->lg->trace("--START CANEVMAST FORM--");

$langPath = $_SESSION['LANGPATH'];
$lang = $_SESSION['LANG'];
$menu_code = $_SESSION['MENU_CODE'];
/*langPath for labels*/
$langPath = $langPath."general/".strtolower($lang)."/".$menu_code."_lbl.txt";
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
	$mainRes = $curd->GetSelData($oldfilter, $newfilter, 'CANEVMAST.ini', 'GETUPDDATA');
	$lgs->lg->trace("--CANEVMAST RESULT OF SELECTED RECORD--".json_encode($mainRes));
	//print_r($mainRes);
}

$oldLovFilter = array(':PCOMP_CODE', ':PTBLNM');
$newLovFilter = array($_SESSION['COMP_CODE'],'CANEVMAST');
$cvCodeRes = $dsbObj->getLovQry(4,$oldLovFilter,$newLovFilter);
$lgs->lg->trace("--cvCodeRes--".json_encode($cvCodeRes));

?>

<script>

$(document).ready(function() {

var action_flag = "<?php echo $action; ?>";
console.log('action flag : '+action_flag);

<?php /*?>var count = <?php echo $count; ?>;<?php */?>

var msg_FileName = '<?=$langPathMsg1; ?>';
var gs_valFileName = '<?=$langPathMsg; ?>';// USE GET VALIDATION MESSAGES BY PASSING HARDCODED NUMBERS
var backlnk = '<?=$backlnk; ?>';

$("#cv_code").prop("readonly", true); 
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
	$("#cv_name").prop("readonly", true); 
	$("#cv_cat").prop("readonly", true);
	$("#cv_mat").prop("disabled", true); 
	$("#cv_mprd").prop("readonly", true); 
	
	$("#btn_submit").hide();
	$("#btn_reset").hide();
	$("#btn_cancel").text('Back');
	$(".panel-heading").text('View');
}

$("#btn_submit").click(function(event){

	    event.preventDefault();
		
	var res = validKeyInd();
	if(errCOUNT == 0)
	{  
		$.ajax({
              url: "caneVarietymastserver.php",
              //data:$('#form_fgmast').serialize()+'&'+$.param({'action':action_flag, oldfilter:oldfilterArr}),
			  data:$('#form_canevmast').serialize()+'&'+$.param({'action':action_flag}),
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
					swal(msg, "", "success");
					location.href = backlnk;
				  }
				  else
				  {
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
				<form id="form_canevmast" name="form_canevmast" class="form-horizontal form-label-left" onsubmit="return false;">
				<ul class="contactus-list">
				<span class="section"><?=$rfObj->readData('CNVMST',$langPath); ?></span>
				<div class="panel panel-primary">
				<div class="panel-heading"><?=$rfObj->readData('ADD',$langPath); ?></div>
					<div class="panel-body">
					
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $rfObj->readData('CD',$langPath); ?></label>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<input type="text" id="cv_code" name="cv_code" class="form-control col-md-7 col-xs-12" placeholder="Enter Code" value="<?php if($action == 'add')
												 {	echo $cvCodeRes[0]['CODE']; }
												 else 
												 {	echo $mainRes[0]['CV_CODE']; } ?>">
							</div>
						</div><!--//form-group-->
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('NME',$langPath); ?></label>
							<li><div class="col-md-3 col-sm-3 col-xs-6">
								<input type="text" id="cv_name" name="cv_name" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Enter Name." value="<?php echo $mainRes[0]['CV_NAME']; ?>">
							</div></li>
						</div><!--//form-group-->
						
						<?php /*?><div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('CAT',$langPath); ?></label>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<input type="text" id="cv_cat" name="cv_cat" class="form-control col-md-7 col-xs-12" value="<?php echo $mainRes[0]['CV_CAT']; ?>">
							</div>
						</div><!--//form-group--><?php */?>
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('MAT',$langPath); ?></label>
							<li><div class="col-md-3 col-sm-3 col-xs-6">
								<select id ="cv_mat" name="cv_mat" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Select Type of Maturity.">
							  	<option value="">Select</option>
								<option value="E"<?php if($mainRes[0]['CV_MAT'] == 'E'){ ?>  selected="selected" <?php }?>>Early</option>
                            <option value="M"<?php if($mainRes[0]['CV_MAT'] == 'M'){ ?>  selected="selected" <?php }?>>Middle Age</option>
							<option value="L"<?php if($mainRes[0]['CV_MAT'] == 'L'){ ?>  selected="selected" <?php }?>>Late</option>
							  </select>
							</div></li>
						</div><!--//form-group-->
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('MPRD',$langPath); ?></label>
							<li><div class="col-md-3 col-sm-3 col-xs-12 input-group">
								<input type="text" id="cv_mprd" name="cv_mprd" class="form-control col-md-7 col-xs-12" valid="required" errmsg="Please Enter Period of Maturity." value="<?php echo $mainRes[0]['CV_MPERD']; ?>">
								<span class="input-group-addon" id="basic-addon2">Days</span>
							</div></li>
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
				</ul>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
			
			
<? include("footer.php");?>		