<? 	

	require_once('dashboard.php');
	include('readfile.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$rfObj = new ReadFile();
	$dsbObj = new Dashboard(); 
	$qryPath = "util/readquery/general/bankmast.ini";
	$langPath = "util/language/";
	$lang = $_SESSION['LANG'];
	$menu_code = $_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".strtolower($lang).'/'.$menu_code.".txt";
	$action = $_GET['view'];

	$oldLovFilter = array(':PCOMP_CODE', ':PSRNUM');
	$newLovFilter = array($_SESSION['COMP_CODE'],49);
	$bankLov = $dsbObj->getLovQry(49,$oldLovFilter,$newLovFilter);
	/*For Auto-Incremented code*/
	$oldCodeFilter = array(':PCOMP_CODE', ':PSRNUM',':PTBLNM');
	$newCodeFilter = array($_SESSION['COMP_CODE'],4,'BANKMAST');
	$bk_code = $dsbObj->getLovQry(4,$oldCodeFilter,$newCodeFilter);

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
		//FOR HEADER DATA
		$HeaderdataQry = $qryObj->fetchQuery($qryPath,'Q001','GETDATAQRY',$oldfilter,$newfilter);
		$bankData = $dsbObj->getData($HeaderdataQry);
		

	}//END OF VIEW AND UPDATE

	$back_link = 'view_browse.php?menu_code='.$menu_code;
    $server_msg = 'main_msg_'.$lang.'.txt';
    $client_msg = $menu_code.'_msg_'.$lang.'.txt';
	
	
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
				   <form id="bankform" class="form-horizontal form-label-left" onsubmit="return false;">
				   <span class="section"><?=$rfObj->readData('BANKMST',$langPath); ?></span>
					<ul class="contactus-list">
					<div class="panel panel-primary">
					<div class="panel-heading"><?php  echo ucfirst($action); ?></div>
					  <div class="panel-body">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('CD',$langPath); ?><span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input id ="bk_code" name="bk_code" class="form-control col-md-7 col-xs-12"  placeholder="Enter Code" type="text" maxlength="4" value="<?php if($action == 'add')
												 {	echo $bk_code[0]['CODE']; }
												 else 
												 {	echo $bankData[0]['BK_CODE']; } ?>" readonly/>
							</div>
						 </div>
					 
						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('NME',$langPath); ?><span class="required">*</span></label>
							<li><div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12 txtEnglish" placeholder="Name In English" name="bk_name" id="txtEnglish" value="<?php echo $bankData[0]['BK_NAME']; ?>" valid="required" errmsg="<?=$rfObj->readData('VNE',$langPath); ?>"/> <!-- onkeypress = "return ValidateAlpha(event);" <span id="alpha_error" style="color:red;"></span> -->
							</div>
							</li>
						</div>	 
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('NMM',$langPath); ?><span class="required">*</span></label>
							<li>
							  <div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12 txtMarathi" placeholder="Name In Marathi" name="bk_mname" id="txtMarathi" value="<?php echo $bankData[0]['BK_MNAME']; ?>" valid="required" errmsg="<?=$rfObj->readData('VNM',$langPath); ?>"/> 
							  </div>
							</li>  
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('SNAME',$langPath); ?><span class="required">*</span></label>
							<li><div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Short Name" name="bk_sname" id="bk_sname" value="<?php echo $bankData[0]['BK_SNAME']; ?>" valid="required" errmsg="<?=$rfObj->readData('VSHNM',$langPath); ?>" />  <!-- onkeypress = "return ValidateAlpha(event);" -->
							</div>
							</li>
						 </div>
					 
						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('BTYPE',$langPath); ?><span class="required">*</span></label>
							<li><div class="col-md-6 col-sm-6 col-xs-12">
							  <select id ="bk_btype" name="bk_btype" class="form-control col-md-7 col-xs-12" valid="required" errmsg="<?=$rfObj->readData('VBTYPE',$langPath); ?>">
							  	<option value="">--Select Bank--</option>
								<? for($i=0;$i<sizeof($bankLov);$i++)
								{?>
								<option value="<?=$bankLov[$i]['BT_CODE']?>" 
									<? if($bankData[0]['BK_BTYPE'] == $bankLov[$i]['BT_CODE']) {?> selected="selected" <? }?>><?=$bankLov[$i]['BT_NAME']?> 
								</option>
								<? } ?>	
							  </select>
							</div>
							</li>
						</div>	
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('ADDR',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Address"  id="bk_addr" name="bk_addr" value="<?php echo $bankData[0]['BK_ADDR']; ?>"/> 
							</div>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('PNO',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Phone Number"  id="bk_phone" name="bk_phone" value="<?php echo $bankData[0]['BK_PHONE']; ?>" onchange="return isNumber(event);" onkeyup="return isNumber(event);" onkeypress="return isNumber(event)" maxlength="10"/> 
							</div>
						 </div>

						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('EMAIL',$langPath); ?></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input type="text" class="form-control col-md-7 col-xs-12" placeholder="Email" name="email"  id="email" value="<?php echo $bankData[0]['BK_EMAIL']; ?>"/> 
							</div>
						<font color="red"> <span id="email_error" style="display: none"> Please Enter Valid Email </span> </font> 	
						 </div>
						
					<div id="wait" class="ui-autocomplete" style="display:none;width:69px;height:89px;border:0px solid black;position:absolute;top:70%;left:50%;padding:2px;">
					<img src='images/lodding.gif' width="64" height="64" /><br>Loading..</div>	
					
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

<script type="text/javascript">
$(document).ready(function()
 {
var action ="<?php echo $action; ?>";
var back_link = '<?php echo $back_link ?>';

$('#btn_submit').on('click', function() {
var res = validKeyInd();
if(errCOUNT == 0)
{ 	
	$("#wait").show();
 	$.ajax({
            url: "bankmast_server.php",
            data:$('#bankform').serialize()+'&'+$.param({'action':'fullform'})+
            '&'+$.param({'flag':action}),
			datatype: "json",
             success: function(data){
             	$("#wait").hide();
			  if(data == 1 && action =='add')
				   {
				     var msg="Record Added Successfully";
				    swal({
					  title: msg,
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
				    location.href = back_link;
				   }else if(data == 1 && action =='update')
				   {
					    var msg="Record Updated Successfully";
					    swal({
						  title: msg,
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
			  }
         });
 }//if
 });

if(action == 'view'){
	$("#txtEnglish").attr('readonly',true)
	$("#txtMarathi").attr('readonly',true)
	$("#bk_sname").attr('readonly',true)
	$("#bk_btype").prop('disabled',true)
	$("#bk_addr").attr('readonly',true)
	$("#bk_phone").attr('readonly',true)
	$("#email").attr('readonly',true)
	$("#btn_submit").hide();
	$("#btn_reset").hide()
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
/**************************************************FORM VALIDATION**************************************************************************/


});//ready
</script>	 
<? include("footer.php");?>