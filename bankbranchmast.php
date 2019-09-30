<? 	

	require_once('dashboard.php');
	include('readfile.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$rfObj = new ReadFile();
	$dsbObj = new Dashboard(); 
	$qryPath = "util/readquery/general/bankbranchmast.ini";
	$langPathOld = "util/language/";
	$lang = $_SESSION['LANG'];
	$menu_code = $_SESSION['MENU_CODE'];
	$langPath = $langPathOld."general/".strtolower($lang).'/'.$menu_code.".txt";
	$action = $_GET['view'];

	/*For bank Lov*/
	$oldLovFilter = array(':PCOMP_CODE', ':PSRNUM');
	$newLovFilter = array($_SESSION['COMP_CODE'],6);
	$bankLov = $dsbObj->getLovQry(6,$oldLovFilter,$newLovFilter);

	/*For Village Lov*/
	$oldLovFilter = array(':PCOMP_CODE', ':PSRNUM');
	$newLovFilter = array($_SESSION['COMP_CODE'],5);
	$villageLov = $dsbObj->getLovQry(5,$oldLovFilter,$newLovFilter);

	/*For Village Lov*/
	$oldLovFilter = array(':PCOMP_CODE', ':PSRNUM');
	$newLovFilter = array($_SESSION['COMP_CODE'],14);
	$accountLov = $dsbObj->getLovQry(14,$oldLovFilter,$newLovFilter);
	

	/*For Auto-Incremented code*/
	$oldCodeFilter = array(':PCOMP_CODE', ':PSRNUM',':PTBLNM');
	$newCodeFilter = array($_SESSION['COMP_CODE'],4,'BRANCHMAST');
	$br_code = $dsbObj->getLovQry(4,$oldCodeFilter,$newCodeFilter);

	$action = $_GET['view'];
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
		
		$HeaderdataQry = $qryObj->fetchQuery($qryPath,'Q001','GETDATAQRY',$oldfilter,$newfilter);
		$bbData = $dsbObj->getData($HeaderdataQry);

	}//END OF VIEW AND UPDATE
	$back_link = 'view_browse.php?menu_code='.$menu_code;
    $server_msg = 'main_msg_'.$lang.'.txt';
    $client_msg = $lang.'/'.$menu_code.'_msg.txt';
    
?>	
<? 	
	require_once("header.php");
	require_once("sidebar.php");
?> 
<section>	
	<!-- page content --> 
	<div class="right_col" role="main">
	  <div class="">
	   <div class="clearfix"></div>
	   
			<div class="row">
			  <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
				
				  <div class="x_content">
				   <form id="bankbranchform" class="form-horizontal form-label-left" onsubmit="return false;">
				   <span class="section"><?=$rfObj->readData('BANKBRNMST',$langPath); ?></span>
					<ul class="contactus-list">
					<div class="panel panel-primary">
					<div class="panel-heading"><?php  echo ucfirst($action); ?></div>
					  <div class="panel-body">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('CD',$langPath); ?><span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input id ="br_code" name="br_code" class="form-control col-md-7 col-xs-12"  placeholder="Enter Code" type="text" maxlength="4" value="<?php if($action == 'add')
												 {	echo $br_code[0]['CODE']; }
												 else 
												 {	echo $bbData[0]['BR_CODE']; } ?>"  readonly tabindex="1"/>
							</div>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('BTYPE',$langPath); ?>
							<span class="required">*</span></label>
							<li><div class="col-md-6 col-sm-6 col-xs-12">
							  <select id ="br_bank" name="br_bank" class="form-control col-md-7 col-xs-12" tabindex="5" valid="required" errmsg="<?=$rfObj->readData('VBTYPE',$langPath); ?>" />
							  	<option value="">--Select Bank--</option>
								<? for($i=0;$i<sizeof($bankLov);$i++)
								{?>
								<option value="<?=$bankLov[$i]['BK_CODE']?>" 
									<? if($bbData[0]['BR_BANK'] == $bankLov[$i]['BK_CODE']) {?> selected="selected" <? }?>><?=$bankLov[$i]['BK_NAME'].'-'.$bankLov[$i]['BK_MNAME']?> 
								</option>
								<? } ?>	
							  </select>
							</div>
							</li>
						</div>	

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('BVILLAGE',$langPath); ?><span class="required">*</span></label>
							<li><div class="col-md-6 col-sm-6 col-xs-12">
							  <select id ="br_village" name="br_village" class="form-control col-md-7 col-xs-12" tabindex="6" valid="required" errmsg="<?=$rfObj->readData('VVL',$langPath); ?>">
							  	<option value="">--Select Village--</option>
								<? for($i=0;$i<sizeof($villageLov);$i++)
								{?>
									<option value="<?=$villageLov[$i]['VL_CODE']?>" 
									<? if($bbData[0]['BR_VILLAGE'] == $villageLov[$i]['VL_CODE']) {?> selected="selected" <? }?>><?=$villageLov[$i]['VL_NAME'].'-'.$villageLov[$i]['VL_MNAME']?> 
								</option>			
								<? } ?>
							  </select>
							</div>
							</li>
						</div>	
					 
						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('NME',$langPath); ?><span class="required">*</span></label>
							<li><div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12 txtEnglish" placeholder="Name In English" name="br_name" id="txtEnglish" value="<?php echo $bbData[0]['BR_NAME']; ?>" valid="required" errmsg="<?=$rfObj->readData('VNE',$langPath); ?>"/>
							</div>
							</li>
						</div>	 
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('NMM',$langPath); ?><span class="required">*</span></label>
							<li><div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12 txtMarathi" placeholder="Name In Marathi" name="br_mname" id="txtMarathi" value="<?php echo $bbData[0]['BR_MNAME']; ?>" tabindex="3" valid="required" errmsg="<?=$rfObj->readData('VNM',$langPath); ?>"/> 
							</div>
							</li>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('SNAME',$langPath); ?><span class="required">*</span></label>
							<li><div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Short Name" name="br_sname" id="br_sname" value="<?php echo $bbData[0]['BR_SNAME']; ?>" tabindex="4" valid="required" errmsg="<?=$rfObj->readData('VNE',$langPath); ?>"/> 
							</div></li>
						 </div>
					 


						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('ADDR',$langPath); ?><span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea rows="2" cols="50" class="form-control col-md-7 col-xs-12" placeholder="Address"  id="br_addr" name="br_addr" tabindex="7"><?php echo $bbData[0]['BR_ADDR']; ?></textarea>		
							</div>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('PNONE',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Phone Number"  id="br_phone" name="br_phone" value="<?php echo $bbData[0]['BR_PHONE']; ?>" onchange="return isNumber(event);" onkeyup="return isNumber(event);" onkeypress="return isNumber(event)" tabindex="8"/> 
							</div>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('EMAIL',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Email" name="br_email"  id="br_email" value="<?php echo $bbData[0]['BR_EMAIL']; ?>" tabindex="9" maxlength="50"/> 
							</div>
						 </div>
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('ACCODE',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <select id ="br_accd" name="br_accd" class="form-control col-md-7 col-xs-12" tabindex="10">
							  	  	<option value="">--Select Account--</option>
									<? for($i=0;$i<sizeof($accountLov);$i++)
									{?>
									<option value="<?=$accountLov[$i]['AC_CODE']?>" 
									<? if($bbData[0]['BR_ACCD'] == $accountLov[$i]['AC_CODE']) {?> selected="selected" <? }?>><?=$accountLov[$i]['AC_DESC']?> 
									<? } ?>	
									</option>
							</select>
							</div>
						</div>	

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('ACNO',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Account Number" name="br_acno"  id="br_acno" value="<?php echo $bbData[0]['BR_ACNO']; ?>" maxlength="15" tabindex="11"/> 
							</div>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('IFSC',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="IFSC " name="br_ifsc"  id="br_ifsc" value="<?php echo 
							  $bbData[0]['BR_IFSC']; ?>" tabindex="12" maxlength="15"/> 
							</div>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('MNAME',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Manager Name" name="br_manager_name"  id="br_manager_name" value="<?php echo $bbData[0]['BR_MANAGER']; ?>" tabindex="13" maxlength="50"/> 
							</div>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('CCHARGE',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12 input-group"">
							  <input type="text" class="form-control col-md-7 col-xs-12 amount" placeholder="Collection Charges" name="br_crate"  id="br_crate" value="<?php echo $bbData[0]['BR_CRATE']; ?>" tabindex="14" maxlength="18" /> 
							<span id="err_amount" style="color: red;"></span>	
							<span class="input-group-addon" id="">Rs</span>
							</div>

						 </div>


					<div id="wait" class="ui-autocomplete" style="display:none;width:69px;height:89px;border:0px solid black;position:absolute;top:70%;left:50%;padding:2px;">
					<img src='images/loading.gif' width="64" height="64" /><br>Loading..</div>	
					
					<div class="ln_solid"></div>
					  <div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
						 <button id="btn_submit" type="button" class="btn btn-success">
										  		<?php
										  		if($action=='add' && $action != 'update')
										  		{  ?>Submit
										  		<?php }else{ ?>Update
										  		<?php } ?>
										  		</button>
										  	<button type="reset" name="reset" class="btn btn-info" id="btn_reset">Reset</button>	
				                            <button type="button" id="btn_cancel" class="btn btn-danger btn_cancel">
				                         	 <?php
										  		if($action =='add' || $action == 'update')
										  		{	?>
										  			Cancel
										  		<?php }else{ ?>
										  			Back
										  		<?php } ?>
										  	</button>
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
    </div>
   </div>
   <!-- /page content -->
</section>
<?php include("footer.php");?>

<script type="text/javascript">
$(document).ready(function()
{
var action ="<?php echo $action; ?>";
var back_link = '<?php echo $back_link ?>';
var valFileName = '<?php echo $client_msg ?>';

$('#btn_submit').on('click', function() {

var res = validKeyInd();
	if(errCOUNT == 0)
	{

	$("#wait").show();
 	$.ajax({
            url: "bankbranchmast_server.php",
            method:"POST",
            data:$('#bankbranchform').serialize()+'&'+$.param({'action':'fullform'})+
            '&'+$.param({'flag':action}),
			datatype: "json",
             success: function(data){
             	$("#wait").hide();
			  if(data == 1 && action =='add')
				   {
				    var msg="Record Added Successfully";
				    swal({
					  title: msg,//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
				    location.href = back_link;
				   }else if(data == 1 && action =='update')
				   {
				      var msg="Record Updated Successfully";
				      swal({
					  title: msg,//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
				    location.href = back_link;
				   }else
				   {
				      var msg = data.trim();
					  swal(msg,"","error");
				   }
			  }//success
         });
 }//if
 
});

if(action == 'view'){
	$("#btn_submit").hide();
	$(".btn_reset").hide();
	$("#txtEnglish").attr('readonly',true);
	$("#txtMarathi").attr('readonly',true);
	$("#br_sname").attr('readonly',true);
	$("#br_bank").prop('disabled',true);
	$("#br_addr").attr('readonly',true);
	$("#br_phone").attr('readonly',true);
	$("#br_email").attr('readonly',true);
	$("#br_accd").prop('disabled',true);
	$("#br_village").prop('disabled',true);
	$("#br_acno").attr('readonly',true);
	$("#br_ifsc").attr('readonly',true);
	$("#br_manager_name").attr('readonly',true);
	$("#br_crate").attr('readonly',true);
	$("#btn_submit").hide();
	$("#btn_reset").hide();

}

/*On Click of Cancel button*/
$(".btn_cancel").on('click',function(){
	location.href=back_link;
	if(action_flag == 'view')
		{
		location.href=back_link;
		}else{
		swal({
			  title: 'Are you sure?',
			  text: "You won't be able to revert this action!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes'
			}).then(function () {
				location.href=back_link;
			})
	}

});

$("#br_village").change(function(){
	setData();
});

$("#br_bank").change(function(){
	setData();
});

function setData(){
		var bank_name = $("#br_bank option:selected").text();
	var vl_name = $("#br_village option:selected").text();
	var bank_ename = bank_name.split('-');
	var vl_ename = vl_name.split('-');
	var enm_vl = bank_ename[0].trim()+','+vl_ename[0].trim();
	var mnm_vl = bank_ename[1].trim()+','+vl_ename[1].trim();
	$("#txtEnglish").val(enm_vl);
	$("#txtMarathi").val(mnm_vl);
}

});//ready
</script>	 
