<? 	require_once("curdClass.php"); 
	require_once('readfile.php');
	
	$curd = new CURD();
	$lgs = new Logs();
	$qryObj = new Query();
	$rfObj = new ReadFile();
	$dsbObj = new Dashboard(); 
	$langPath = "util/language/";
	//$lang="english";
	$lang = $_SESSION['LANG'];
	$menu_code = $_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".strtolower($lang).'/'.$menu_code.".txt";
	$msgPath = 'util/readmsgs/'.strtolower($lang).'/client_msg.txt';

	$action = $_GET['view'];
	$crop = 'N';
	
	$oldLovFilter = array(':PCOMP_CODE');
	$newLovFilter = array($_SESSION['COMP_CODE']);
	
	//To get BANK NRANCH LOV from dashbord's getLov function
	$BankbranchLOVres = $dsbObj->getLovQry(18,$oldLovFilter,$newLovFilter);
	
	$farmer_code = '';
	$farmer_name = '';
	$village = '';
	$branch = '';
	$chequename = '';
	$accountno = '';
	
	if($action == 'view' || $action == 'update'){
		$array1 = $_GET['column_names'];
		$colnames = explode(',', $array1);
		$oldFilter = array();
		for($i = 0; $i < sizeof($colnames); $i++)
		{
			$oldFilter[$i] = ":".$colnames[$i];
		}
	
		$array2 = $_GET['rowdata'];
		//$newFilter = explode(',', $array2);
		$newFilter = json_decode($array2);
		$code = $newFilter[2];
		
		$oldF = array($oldFilter[2],':PDOC_CODE',':PCOMP_CODE');
		$newF = array($newFilter[2],$newFilter[10],$_SESSION['COMP_CODE']);
	
		$filename = 'accountinfo.ini';
		$query = 'ACCOUNTINFO';
		$AccountinfoRes = $curd->GetSelData($oldF, $newF, $filename, $query);

		$village = ltrim($newFilter[4]);
		$farmer_name = ltrim($AccountinfoRes[0]['PRT_NAME']);
		$branch = $AccountinfoRes[0]['PRT_BANKBR'];
		$prt_uid = $AccountinfoRes[0]['PRT_BANKBR'];
		$aadhar = $AccountinfoRes[0]['PRT_UID'];
		$mobile = $AccountinfoRes[0]['PRT_TEL'];


		if($AccountinfoRes[0]['PRT_CNAME'] == ''){
			$chequename = ltrim($newFilter[3]);
		}
		else{
			$chequename = ltrim($AccountinfoRes[0]['PRT_CNAME']);
		}	
	
		$accountno = $AccountinfoRes[0]['PRT_ACNO'];
	}
									  					  
	//get validation messages
	$server_msg = strtolower($lang).'/main_msg.txt';
	$client_msg = strtolower($lang).'/client_msg.txt';
	
	$back_link = 'view_browse.php?menu_code='.$menu_code;
	
 	require_once("header.php");
	require_once("sidebar.php");?> 

<script type="text/javascript">

$(document).ready(function() {
	

	
	var action = '<?php echo $action ?>';
	var back_link = '<?php echo $back_link ?>';	
	var valFileName = '<?php echo $server_msg ?>';
	var clientval = '<?php echo $client_msg ?>';
	
	if(action == 'add'){
		swal({
		  title: "Addition of Farmer or Bank Information is not allowed from this option!",
		  type: 'warning',
		}).then(function () {
			location.href = back_link;
		})
	}
	if(action == 'update'){
		$("#code").prop("readonly", true);
		$('#btn_back').hide();
		$('#btn_submit').show();
		$('#btn_submit').text('Update');
		$('#btn_cancel').show();
		$('#btn_reset').show();
		$("#branch").autoselect();
	}
	if(action == 'view'){
		$('#accountinfoform :input').attr('readonly','readonly');
		$('#btn_back').show();
		$('#btn_submit').hide();
		$('#btn_cancel').hide();
		$('#btn_reset').hide();
	}
	
	//Submit data and insert into table
	$('#btn_submit').on('click', function() {
		
	
		var res = validKeyInd();
		
		if(errCOUNT == 0)
		{
			jQuery.ajax({ 
				type: "POST",
				datatype: "json",
				async: false,
				url: "account_infoserver.php",
				data:$('#accountinfoform').serialize()+'&'+$.param({action:action}),
				success:function(data)
				{
					if(data==1 && action=='update'){
						swal({
						  title: msg = getMsg(2,valFileName),//call getMsg function with message number and file name
						  timer: 10000,
						  type: 'success',
						  showConfirmButton: false
						});
						location.href = back_link;
					}	
					else 
				 	{
						var msg = data.trim();
						swal(msg, "", "error");
				 	}	 
				}
			});//end of ajax call
		}
	});//Submit

	$("#btn_cancel").on('click',function(){
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
	});//Cancel
	
	$("#btn_back").on('click',function(){
		location.href=back_link;
	});//back
	
});

</script>	 
	
 
<section>	
	<!-- page content --> 
	<div class="right_col" role="main">
	  <div class="">
	   <div class="clearfix"></div>
	   <? if($action == 'update' || $action == 'view') {?>	
			<div class="row">
			  <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
				
				  <div class="x_content">
				   <form id="accountinfoform" class="form-horizontal form-label-left" action="#" onsubmit="return false;">
				    <ul class="contactus-list">
				   <span class="section"><?=$rfObj->readData('BNKINFO',$langPath); ?></span>
				
					<div class="panel panel-primary">
					<div class="panel-heading" id="addpanel"><?=$rfObj->readData('UPD',$langPath); ?></div>
					  <div class="panel-body">
						
						<div class="form-group">
								<label class="control-label col-md-3 col-sm-6 col-xs-12"><?=$rfObj->readData('CD',$langPath); ?></label>
								<li><div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" id="code" name="code" class="form-control col-md-7 col-xs-12" value="<?=$code?>" readonly="readonly">
								</div></li>
						</div><!--//form-group-->
						
						
						<div class="form-group">
								<label class="control-label col-md-3 col-sm-6 col-xs-12"><?=$rfObj->readData('NM',$langPath); ?></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" id="farmer_name" name="farmer_name" class="form-control col-md-7 col-xs-12" value="<?=$farmer_name?>" readonly="readonly">
								</div>
						</div><!--//form-group-->
						
						<div class="form-group">
								<label class="control-label col-md-3 col-sm-6 col-xs-12"><?=$rfObj->readData('VLG',$langPath); ?></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" id="village" name="village" class="form-control col-md-7 col-xs-12" value="<?=$village?>" readonly="readonly">
								</div>
						</div><!--//form-group-->
						
						<div class="form-group">
								<label class="control-label col-md-3 col-sm-6 col-xs-12"><?=$rfObj->readData('NMCHQUE',$langPath); ?></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" id="chequename" name="chequename" class="form-control col-md-7 col-xs-12" value="<?=$chequename?>" >
								</div>
						</div><!--//form-group-->
						
						
						
						<div class="form-group">
								<label class="control-label col-md-3 col-sm-6 col-xs-12"><?=$rfObj->readData('BNKBRNCH',$langPath); ?><!-- <span class="required">*</span> --></label>
								<div class="col-md-6 col-sm-6 col-xs-12">
								<? if($action == 'update'){?>
									 <select  class="form-control" name="branch" id="branch">
							   <option value="">Select</option>
								  <? for($i=0;$i<sizeof($BankbranchLOVres);$i++) {?>
									<option value="<?=$BankbranchLOVres[$i]['BR_CODE']?>" <? if($branch == $BankbranchLOVres[$i]['BR_CODE']) {?> selected="selected" <? }?>><?=$BankbranchLOVres[$i]['BR_CODE'].' '.$BankbranchLOVres[$i]['BR_NAME']?></option>
								  <? } ?>
							 </select>
							 <? } if($action == 'view') {
							 	for($i=0;$i<sizeof($BankbranchLOVres);$i++) {
							   	 if($branch == $BankbranchLOVres[$i]['BR_CODE']) {
								 	$branchname = $BankbranchLOVres[$i]['BR_NAME']; } }  ?>
							 <input type="text" id="branch" name="branch" class="form-control col-md-7 col-xs-12" value="<?=$branchname?>" ><? }?>
								</div>
						</div><!--//form-group-->
<div class="form-group">
		<label class="control-label col-md-3 col-sm-6 col-xs-12"><?=$rfObj->readData('ACCNO',$langPath); ?><!-- <span class="required">*</span> --></label>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<input type="text" id="accountno" name="accountno" class="form-control col-md-7 col-xs-12" value="<?=$accountno?>">
		</div>
</div><!--//form-group-->

<div class="form-group">
		<label class="control-label col-md-3 col-sm-6 col-xs-12">Mobile</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<input type="text" id="mobile" name="mobile" class="form-control col-md-7 col-xs-12" value="<?=$mobile?>">
		</div>
</div><!--//form-group-->

<div class="form-group">
		<label class="control-label col-md-3 col-sm-6 col-xs-12">Aadhar</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<input type="text" id="aadhar" name="aadhar" class="form-control col-md-7 col-xs-12" value="<?=$aadhar?>">
		</div>
</div><!--//form-group-->

					
					<div class="ln_solid"></div>
					  <div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
							<button type="button" name="submit" class="btn btn-success" id="btn_submit">Submit</button>	
							<button type="reset" name="reset" class="btn btn-info" id="btn_reset">Reset</button>	
							<button type="button" name="cancel" class="btn btn-danger" id="btn_cancel">Cancel</button>	
							<button type="button" name="back" class="btn btn-danger" id="btn_back">Back</button>	
						</div>
					  </div>	
							
					</div><!--panel-body-->
				  </div><!--panel panel-primary--> 
			 </ul>
			</form>		  
	  	</div>
	   </div>
 	  </div>
	 </div>
	 <? } ?>
    </div>
   </div>
   <!-- /page content -->
		
</section>

<? include("footer.php");?>

