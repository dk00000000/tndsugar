<? 
	require_once('dashboard.php');
	include('readfile.php');
	//require_once('header_menu.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$dsbObj = new Dashboard(); 
	$rfObj = new ReadFile();
	$lang=strtolower($_SESSION['LANG']);
    $qryPath = "util/readquery/general/banktypemast.ini";
	$langPath = "util/language/";
	$menu_code=$_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".$lang.'/'.$menu_code.".txt";
	
  	
	/* For generate dynamic back links*/	
    $back_link = 'view_browse.php?menu_code='.$menu_code;
    
     //get validation messages
	$server_msg = strtolower($lang).'/main_msg.txt';
	$client_msg = strtolower($lang).'client_msg.txt';
	
	$action = $_GET['view'];
	//echo "Action".$action;
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
		
		//echo $GetDataJsonRes;
	}//END OF VIEW AND UPDATE

    if($action=='add'){
	//For getting Bank code
	$oldCodeFilter = array(':PCOMP_CODE', ':PSRNUM',':PTBLNM');
	$newCodeFilter = array($_SESSION['COMP_CODE'],4,'BANKTMAST');
	$bank = $dsbObj->getLovQry(4,$oldCodeFilter,$newCodeFilter);
	$bank_code=$bank[0]['CODE'];
    }
    
?>

<? require_once("header.php");?> 

<? include("sidebar.php");?> 
 
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
                     <span class="section"><?=$rfObj->readData('STATEMAST',$langPath); ?></span>
                  </div>
                  <div class="x_content">

                   <div class="panel panel-primary">
           <div class="panel-heading"><?php  echo ucfirst($action); ?></div>
            <div class="panel-body">

                    <form class="form-horizontal form-label-left" id="state_master" action="#">
                     <!--  <span class="section">State Master</span> -->
                   
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('CD',$langPath); ?> 
                        <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <input  id ="code" class="form-control col-md-7 col-xs-12" placeholder="Enter Bank Code" readonly="readonly"  <?php if($action=='add'){ ?> value="<?php echo $bank_code; ?>" <? } ?> type="text" name="st_code" <? if(isset($HeaderdataRes)){ if($action=='view' || $action=='update') {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['BT_CODE']?>" <? } ?> >
                        </div>
                      </div>
					  
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" ><?=$rfObj->readData('NME',$langPath); ?>
											 <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input  id ="txtEnglish" class="form-control col-md-7 col-xs-12 txtEnglish"   placeholder="Enter Bank Name"  type="text" name="st_name" <? if(isset($HeaderdataRes)){ if($action=='view' ) {?> readonly="readonly"<? }?> value="<?=$HeaderdataRes[0]['BT_NAME']?>" <? } ?>> <!-- onkeypress = "return ValidateAlpha(event);"  <span id="alpha_error" style="color:red;"></span>  -->
                        </div>
                      </div>
					  
					    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$rfObj->readData('NMM',$langPath); ?> <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="txtMarathi" class="form-control col-md-7 col-xs-12 txtMarathi"  name="st_mname" placeholder=" Bank Name in Marathi " required="required" type="text" <? if(isset($HeaderdataRes)){if($action=='view') {?> readonly="readonly"<? }?>  value="<?=$HeaderdataRes[0]['BT_MNAME']?>" <? } ?>>
                        </div>
                      </div>

                      <div id="wait" class="ui-autocomplete" style="display:none;width:69px;height:89px;border:0px solid black;position:absolute;top:70%;left:50%;padding:2px;">
					   <img src='images/ford_lodding.gif' width="64" height="64" /><br>Loading..</div>
			
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

var back_link = '<?php echo $back_link ?>';
var valFileName = '<?php echo $server_msg ?>';

var action ="<?php echo $action; ?>";






 //Submit data and insert into table
$('#btn_submit').on('click', function() {
	$("#wait").show();
	$.ajax({
              url: "banktypemast_server.php",
              data:$('#state_master').serialize()+'&'+$.param({'action':'fullform'})+
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
				   }else {
				   	 var msg = data.trim();
				     swal(msg, "", "error");
				      
				   }
				   
			  }
         });
  
  //  });		  
			
});//Submit 


		
		
//FOR CANCEL BUTTon
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

});//Cancel	

//FOR CANCEL BUTTon
$('#btn_back').on('click', function() {
  
			location.href = back_link;
	
});//Cancel

</script>
<script>
$(document).ready(function() {
	var valFileName = '<?php echo $client_msg ?>';

    $('#state_master').bootstrapValidator({
        fields: {
            st_code: {
				row: '.col-md-6 col-sm-6 col-xs-12',
                validators: {
                    notEmpty: {
                      	message: getMsg(1,valFileName)
					 }
                }	
            },
            st_name: {
				row: '.col-md-6 col-sm-6 col-xs-12',
                validators: {
                    notEmpty: {
                        message: getMsg(1,valFileName)
					 }
                }
            },
			st_mname: {
				row: '.col-md-6 col-sm-6 col-xs-12',
                validators: {
                    notEmpty: {
                       message: getMsg(1,valFileName)
                    }
                }
            }
			
		   
        }
    });
	
	

});//function
</script>
