
<?php 

	require_once('dashboard.php');
	require_once('readfile.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$rfObj = new ReadFile();
	$dsbObj = new Dashboard(); 
	
	$qryPath = $_SESSION['QRYPATH']."/general/fileupload.ini";
	$langPath = "util/language/";
	$lang="english";
	$menu_code = $_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".strtolower($lang).'/'.$menu_code.".txt";
	
	if(isset($_GET['PK']) && $_GET['PK']!=""){
		$pk=$_GET['PK'];
	}
	if(isset($_GET['COMM_MENU'])){
		$comm_menu=$_GET['COMM_MENU'];
	}
	$type = $_GET['TYPE'];
	$action = $_GET['action'];
	
	if(isset($_GET['srno'])){
		$srno = $_GET['srno'];
		//Get Comm_pkval from ,mailcomm for back link
		$oldFilter = array(':PCOMM_SRNO');
		$newFilter = array($srno);
		$pkvalQry = $qryObj->fetchQuery($qryPath,'Q001','GETPKVAL',$oldFilter,$newFilter);
		$pkvalRes = $dsbObj->getData($pkvalQry);
		$pkval = $pkvalRes[0]['COMM_PKVAL'];
	}
	
	$subject = "";
	$msgbody = "";
	$date = "";
	$status = "";
	
	//GET ALL RECORDS FROM QUERY
	$oldfilter = array(":PCOMM_PKVAL",":PCOMP_CODE");
	$newfilter = array($pk,$_SESSION['COMP_CODE']);
	
	$dispQry = $qryObj->fetchQuery($qryPath,'Q001','DISPQRY',$oldfilter,$newfilter);
	$getdataRes = $dsbObj->getData($dispQry);
	
	for($i=0;$i<sizeof($getdataRes);$i++)
	{
		$GetDataJsonRes[]=array_values($getdataRes[$i]);
	}
	
	$GetDataJsonRes=json_encode($GetDataJsonRes,JSON_PRETTY_PRINT.';');
	
	$userQry = $qryObj->fetchQuery($qryPath,'Q001','USER_QRY');
	$userRes = $dsbObj->getData($userQry);
	
	//GET COMM_TYPE FROM QUERY
	$typeQry = $qryObj->fetchQuery($qryPath,'Q001','GETTYPE');
	$typeRes = $dsbObj->getData($typeQry);
	
	//get validation messages
	$server_msg = 'main_msg_'.$lang.'.txt';
	$client_msg = $menu_code.'_msg_'.$lang.'.txt';
	
	if($type == 'attach'){
		$title = 'Attach';
		$subtitle = 'Attachments';
	}
	if($type == 'msg'){
		$title = 'Message';
		$subtitle = 'Messages';
	}
	
	if($action == 'view'){
		$oldfilter = array(":PCOMM_SRNO");
		$newfilter = array($srno);
		
		$viewQry = $qryObj->fetchQuery($qryPath,'Q001','VIEWQRY',$oldfilter,$newfilter);
		$viewRes = $dsbObj->getData($viewQry);
	
		$status = $viewRes[0]['COMM_CTRL'];
		$date = date_format($viewRes[0]['COMM_DATE'],"m/d/Y");
		$subject = $viewRes[0]['COMM_SUBJECT'];
		$msgbody = $viewRes[0]['COMM_MTEXT'];	
	}
	
	//Update commd_flag to Y in mailperson
	if($action == 'view' && $type == 'all'){
		$oldFilter = array(':PCOMMD_SRNO', ':PUSER_CODE');
		$newFilter = array($srno, $_SESSION['USER']);
		$updflagQry = $qryObj->fetchQuery($qryPath,'Q001','UPD_MAILPRSN',$oldFilter,$newFilter);
		$updflagRes = $dsbObj->updateData($updflagQry);
	}
	
	$back_link = 'attach.php?PK='.$pk.'&COMM_MENU='.$comm_menu.'&TYPE='.$type;

?> 

<? require_once("header.php");

 include("sidebar.php");?>   
 <link rel="stylesheet" type="text/css" media="screen" href="jquery-ui-1.12.1.custom/jquery-ui.min.css"/>

 
<script type="text/javascript">
	var type = "<?php echo $type; ?>";
	var pk = "<?php echo $pk; ?>";
	var comm_menu = "<?php echo $comm_menu; ?>";
	var valFileName = '<?php echo $server_msg ?>';
	var back_link = "<?php echo $back_link; ?>";	
	var action = "<?php echo $action; ?>";
	var pkval = "<?php echo $pkval; ?>";

	$(document).ready(function() {
		
		var table  = $('#example').DataTable({
		 <? echo '"data":'.$GetDataJsonRes.','; ?>
		 
			"columnDefs": [									
					{
						"orderable": false,
						"targets": [7],
						"data": null,
						"defaultContent":
						"<a href='' id='delete' class='glyphicon glyphicon-trash' title='Delete'></a>",						
					},
					{ 
						"responsivePriority": -1,
						"targets": -1
					},
					
			],	
			
		});//Datatable
		
		$("#uploader").plupload({
		
			// General settings
			runtimes : 'html5,flash,silverlight,html4',
			url : 'fileAttach.php?upflag=Y&PK='+pk+'&COMM_MENU='+comm_menu,
			
			// User can upload no more then 20 files in one go (sets multiple_queues to false)
			max_file_count: 5,
			
			chunk_size: '1mb',
	
			// Resize images on clientside if we can
			resize : {
				width : 200, 
				height : 150, 
				quality : 90,
				crop: true // crop to exact dimensions
			},
			
			filters : {
				// Maximum file size
				max_file_size : '1000mb',
				// Specify what files to browse for
				mime_types: [
					{title : "Image files", extensions : "jpeg,jpg,gif,png,zip,pdf,txt,rtf,doc,docx,xls,xlsx,csv,php"},
					{title : "Zip files", extensions : "zip"}
				]
			},
	
			// Rename files by clicking on their titles
			rename: true,
			
			// Sort files
			sortable: true,
	
			// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
			dragdrop: true,
	
			// Views to activate
			views: {
				list: true,
				thumbs: true, // Show thumbs
				active: 'thumbs'
			},
	
			// Flash settings
			flash_swf_url : 'opt/lampp/htdocs/sugar/js/Moxie.swf',
	
			// Silverlight settings
			silverlight_xap_url : 'opt/lampp/htdocs/sugar/js/Moxie.xap'
		});//plupload
		
		$('#btn_send').on('click', function() {
		
			jQuery.ajax({ 
				type: "GET",
				url: "fileAttach.php",
				data:$('#addform').serialize()+'&'+$.param({insflag:'Y',PK:pk,COMM_MENU:comm_menu,TYPE:type}),
				async: false,
				datatype: "json",
				success:function(data)
				{
					if(data.trim()=='add'){
						swal({
						  title: msg ='Data Inserted',//call getMsg function with message number and file name
						  timer: 10000,
						  type: 'success',
						  showConfirmButton: false
						});//swal
						location.href = back_link;
					}//if			 
				}//success
			});//end of ajax call
		});//submit
		
		$('#btn_cancel').on('click',function(){
			location.href = back_link;
		});//cancel
		
		$('#btn_back').on('click',function(){
			location.href = back_link;
			if(type == 'msg'){
				location.href = 'attach.php?PK='+pkval+'&COMM_MENU='+comm_menu+'&TYPE='+type;
			}
			if(type == 'all'){
				location.href = 'attach.php?TYPE=all';
			}
		});//back
	});//function
		
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
		  <form id="addform" class="form-horizontal form-label-left">
		   <span class="section"><?=$title?></span>
			<div class="panel panel-primary">
       		 <div class="panel-heading">Add</div>
			   <div class="panel-body">
			  	<? if($type == 'msg' || $action == 'view') {?>
				   <div class="form-group row">
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">To*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select  class="form-control" name="to" id="to">
								  <option value="">Please Select</option>
								  <? for($i=0;$i<sizeof($userRes);$i++) {?>
									<option value="<?=$userRes[$i]['USER_CODE']?>"><?=$userRes[$i]['USER_CODE']."-".$userRes[$i]['USER_NAME']?></option>
								  <? } ?>
								  </select>
								<span id="div_code_error" style="color: red;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Cc*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
							 <select  class="form-control" name="cc" id="cc">
							   <option value="">Please Select</option>
								  <? for($i=0;$i<sizeof($userRes);$i++) {?>
									<option value="<?=$userRes[$i]['USER_CODE']?>"><?=$userRes[$i]['USER_CODE']."-".$userRes[$i]['USER_NAME']?></option>
								  <? } ?>
								  </select>
							</div>
						</div>
					</div><!--//row-->
					
					<div class="form-group row">
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Bcc*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select  class="form-control" name="bcc" id="bcc">
							  <option value="">Please Select</option>
								  <? for($i=0;$i<sizeof($userRes);$i++) {?>
									<option value="<?=$userRes[$i]['USER_CODE']?>"><?=$userRes[$i]['USER_CODE']."-".$userRes[$i]['USER_NAME']?></option>
								  <? } ?>
								  </select>
								<span id="pht_type_error" style="color: red;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Priority*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
							   <select class="form-control" name="priorty" id="priorty">
									<option value="">Select</option>
									<option value="C">Critical</option>
									<option value="H">High</option>
									<option value="M">Medium</option>
									<option value="L">Low</option>
								</select>
								<span id="sht_type_error" style="color: red;"></span>
							</div>
						</div>
					</div><!--//row-->
					
					<div class="form-group row">
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Status*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								  <select class="form-control" name="status" id="status">
									<option value="">Select</option>
									<option value="P">Public</option>
									<option value="PR">Private</option>
									<option value="C">Company</option>
								</select>
							</div>
						</div>
						
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Delete On*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" class="form-control" id="single_cal1" name="delon" value="<?=$date?>">
							</div>
						</div>
					</div><!--//row-->
					
					<div class="form-group row">
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Subject*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject"  value="<?=$subject?>">
							</div>
						</div>
						
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Message Body*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea name="msgbody" id="msgbody" class="form-control"><?=$msgbody?></textarea>	
							</div>
						</div>
					</div><!--//row-->
				<? } if($type == 'attach') {?>
					<div class="form-group row">
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Title*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								  <input type="text" class="form-control" name="title" value="" id="title" placeholder="Title">
							</div>
						</div>
						
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">type*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
                                <select id="type" name="type" class="form-control">
									<? for($i=0;$i<sizeof($typeRes);$i++){?>
										<option value="<?=$typeRes['CATG_CODE']?>"><?=$typeRes['CATG_CODE']?></option>
									<? } ?>
								</select>
							</div>
						</div>
					</div><!--//row-->
					
					<div class="form-group row">
						<div class="col-md-6">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Remark*</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea name="remark" id="remark" class="form-control"></textarea>	
							</div>
						</div>
					</div><!--//row-->
				<? } ?>
					
				</div><!--Panel Body -->
         	</div><!-- Panel-->
		
			<? if($action == 'add') {?>
				<div id="uploader" style="width:100%">
				
				</div>
			<? }?>
		
		  <div class="ln_solid"></div>
		  <div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
			<? if($action == 'add') {?>
				<button type="button" name="send" class="btn btn-success" id="btn_send">Submit</button>	
				<button type="reset" name="reset" class="btn btn-info" id="btn_reset">Reset</button>	
				<button type="button" name="cancel" class="btn btn-danger" id="btn_cancel">Cancel</button>
			<? } ?>
			<? if($action == 'view') {?>	
				<button type="button" name="back" class="btn btn-danger" id="btn_back">Back</button>	
			<? } ?>	
			</div>
		  </div>
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

   $('#addform').bootstrapValidator({
		
        fields: {
				
			//Attach
			title: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
			/*type: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },*/
			remark: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            }
			
			//Message
           /* to: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
						 message: 'The field is required and cannot be empty'
                    }
                }	
            },
            cc: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
			bcc: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
			subject: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
			msgbody: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
			priorty: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
			status: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
			delon: {
				row: '.col-md-9 col-sm-9 col-xs-12',
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            }
		
		   
        }*/
    });
});
</script>	