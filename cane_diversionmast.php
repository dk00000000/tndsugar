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
	
	$action = $_GET['view'];
	
	if(isset($_GET['getCode'])){
		$type = $_GET['type'];
		
		//To get auto generated code from dashbord's getLov function
		$oldLovFilter = array(':PCOMP_CODE', ':PTBLNM', ':PPARAM');
		$newLovFilter = array($_SESSION['COMP_CODE'], 'CANEDTMAST', $type);
		$CodeRes = $dsbObj->getLovQry(4,$oldLovFilter,$newLovFilter);
		$code = $CodeRes[0]['CODE'];
		echo $code;
		exit();
	}	
	
	$type = '';
	$english_name = '';
	$marathi_name = '';
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
		
		$oldFilter = array($oldFilter[2],':PCOMP_CODE');
		$newFilter = array($newFilter[2],$_SESSION['COMP_CODE']);
		$filename = 'cane_diversion.ini';
		$query = 'GETUPDDATA';
		$res = $curd->GetSelData($oldFilter,$newFilter,$filename,$query);
		
		$code = $res[0]['CDT_CODE'];
		$type = $res[0]['CDT_TYPE'];
		
		$english_name = $res[0]['CDT_NAME'];
		$marathi_name = $res[0]['CDT_MNAME'];	
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
		$("#code").prop("readonly", true);
		$('#btn_back').hide();
		$('#btn_submit').show();
		$('#btn_submit').text('Submit');
		$('#btn_cancel').show();
		$('#btn_reset').show();
	}
	if(action == 'update'){
		$("#addpanel").text('Update');
		$("#type").prop("disabled","disabled");
		$("#code").prop("readonly", true);
		$('#btn_back').hide();
		$('#btn_submit').show();
		$('#btn_submit').text('Update');
		$('#btn_cancel').show();
		$('#btn_reset').show();
	}
	if(action == 'view'){
		$('#canedivform :input').attr('readonly','readonly');
		$("#addpanel").text('View');
		$('#btn_back').show();
		$('#btn_submit').hide();
		$('#btn_cancel').hide();
		$('#btn_reset').hide();
	}
	
	//Submit data and insert into table
	$('#btn_submit').on('click', function() {
		
		jQuery.ajax({ 
			type: "GET",
			datatype: "json",
			async: false,
			url: "cane_diversionserver.php",
			data:$('#canedivform').serialize()+'&'+$.param({action:action}),
			success:function(data)
			{
				if(data.trim()=='add'){
					swal({
					  title: msg = getMsg(1,valFileName),//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
					location.href = back_link;
				}	
				if(data.trim()=='update'){
					swal({
					  title: msg = getMsg(2,valFileName),//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
					location.href = back_link;
				}			 
			}
		});//end of ajax call
		
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

function getFrcode(code){
	jQuery.ajax({ 
		type: "GET",
		datatype: "json",
		async: false,
		url: "cane_diversionmast.php",
		data:({getCode:'Y',type:code}),
		success:function(data)
		{
			$('#code').val(data.trim());
		}
	});//end of ajax call
}
</script>	 
	 
<section>	
	<!-- page content --> 
	<div class="right_col" role="main">
	  <div class="">
	   <div class="clearfix"></div>
	   
			<div class="row">
			  <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
				
				  <div class="x_content">
				   <form id="canedivform" class="form-horizontal form-label-left" action="#">
				   <span class="section"><?=$rfObj->readData('CANDIV',$langPath); ?></span>
				
					<div class="panel panel-primary">
					<div class="panel-heading" id="addpanel"><?=$rfObj->readData('ADD',$langPath); ?></div>
					  <div class="panel-body">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('CD',$langPath); ?><span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input id ="code" name="code" class="form-control col-md-7 col-xs-12"  placeholder="Enter Code" type="text"  value="<?=$code?>"> 
							</div>
						 </div>
					 
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('TYP',$langPath); ?><span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <select id ="type" name="type" class="form-control col-md-7 col-xs-12" onchange="getFrcode(this.value)">
							  	<option value="">Select</option>
								<option value="D" <? if($type == 'D'){?> selected="selected"<? } ?>>Cane Diversion</option>
								<option value="T" <? if($type == 'T'){?> selected="selected"<? } ?>>Cane Transfer</option>
							  </select>
							</div>
						</div>	 
											 
						 <div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('NME',$langPath); ?><span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input id ="txtEnglish" name="txtEnglish" class="form-control col-md-7 col-xs-12 txtEnglish" placeholder="Enter Name in English"  type="text" value="<?=$english_name?>"/>
							</div>
						</div>	 
						
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('NMM',$langPath); ?><span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							  <input id ="txtMarathi" name="txtMarathi" class="form-control col-md-7 col-xs-12 txtMarathi"  placeholder="Enter Name in Marathi" type="text" value="<?=$marathi_name?>"> 
							</div>
						 </div>
					 	
						<div class="ln_solid"></div>
						  <div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
								<button type="submit" name="submit" class="btn btn-success" id="btn_submit">Submit</button>	
								<button type="reset" name="reset" class="btn btn-info" id="btn_reset">Reset</button>	
								<button type="button" name="cancel" class="btn btn-danger" id="btn_cancel">Cancel</button>	
								<button type="button" name="back" class="btn btn-danger" id="btn_back">Back</button>	
							</div>
						  </div>	
							
					</div><!--panel-body-->
				  </div><!--panel panel-primary--> 
			  
			</form>		  
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
$(document).ready(function() {
	var valFileName = '<?php echo $client_msg ?>';

    $('#canedivform').bootstrapValidator({
        fields: {
            code: {
				row: '.col-md-6 col-sm-6 col-xs-12',
                validators: {
                    notEmpty: {
                      	message: getMsg(1,valFileName)
					 }
                }	
            },
			type: {
				row: '.col-md-6 col-sm-6 col-xs-12',
                validators: {
                    notEmpty: {
                        message: getMsg(1,valFileName)
					 }
                }
            },
            txtEnglish: {
				row: '.col-md-6 col-sm-6 col-xs-12',
                validators: {
                    notEmpty: {
                        message: getMsg(1,valFileName)
					 }
                }
            },
			txtMarathi: {
				row: '.col-md-6 col-sm-6 col-xs-12',
                validators: {
                    notEmpty: {
                       message: getMsg(1,valFileName)
                    }
                }
			}		
		   
        }//fields
    });//bootstrapValidator
	

});//function
</script>