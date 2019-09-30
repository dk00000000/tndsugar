<? 
   //echo ""
	require_once('dashboard.php');
	include('readfile.php');
	//require_once('header_menu.php');
	
	$lgs = new Logs();
	$qryObj = new Query();
	$dsbObj = new Dashboard(); 
	$rfObj = new ReadFile();
	$lang=strtolower($_SESSION['LANG']);
    $qryPath = "util/readquery/general/manual_weighingslip.ini";
	$langPath = "util/language/";
	$menu_code=$_SESSION['MENU_CODE'];
	$langPath = $langPath."general/".$lang.'/'.$menu_code.".txt";
	
  	
	/* For generate dynamic back links*/	
    $back_link = 'view_browse.php?menu_code='.$menu_code;
 
	//get validation messages
	$server_msg = strtolower($lang).'/main_msg.txt';
	$client_msg = strtolower($lang).'client_msg.txt';
	$more_option = $_GET['OPTN'];
	$action = $_GET['view'];

	if($action=='view' || $action=='update' || $more_option == 'hs_edit' || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero')
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
		$ws_seq = $newfilter[2];

		//For Change Harvesting Slip
		if($more_option == 'hs_edit'){
			header("Location:harvestingslip.php?flag=hs_edit&ws_seq=$ws_seq");
		}


		

		$lgs->lg->trace("--Row Data--:".json_encode($newfilter));
		//FOR HEADER DATA
		
		$HeaderdataQry = $qryObj->fetchQuery($qryPath,'Q001','GETDATAQRY_CANCELWS',$oldfilter,$newfilter);
		//echo "ajdj niojdasd iojjasdas".$HeaderdataQry;
		$HeaderdataRes = $dsbObj->getData($HeaderdataQry);
		//print_r($HeaderdataRes);
       
        $txn_erefseq=$HeaderdataRes[0]['ENTRY_SRNO']." ".$HeaderdataRes[0]['TXN_VHNO']." ".$HeaderdataRes[0]['PRT_NAME']." ".$HeaderdataRes[0]['JOINT_NAME'];
		
		if(sizeof($HeaderdataRes) ==1){
			$oldFilter = array(':PSERIES',':PTXN_VTYPE');
	        $newFilter = array($HeaderdataRes[0]['SSEG_CODE'],$HeaderdataRes[0]['TXN_VTYPE']);
            $getPrintFileQry = $qryObj->fetchQuery($qryPath,'Q001','GET_PRINTFILE',$oldFilter,$newFilter);
           // echo $getPrintFileQry;
            $printFileRes = $dsbObj->getData($getPrintFileQry);
            //print_r($printFileRes);
		}

	}//END OF VIEW AND UPDATE
   

    if($action=="add" || $action=="update" ||$action=="view"  || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero'){
	  $oldLovFilter = array(':PCOMP_CODE',':PDOC_CODE',':PUSER_CODE');
	  $newLovFilter = array($_SESSION['COMP_CODE'],$_SESSION['DOC_CODE'],$_SESSION['USER']);
	  $div_lov=$dsbObj->getLovQry(24,$oldLovFilter,$newLovFilter);
	  //For Season
	  $snLOVres=$dsbObj->getLovQry(28,$oldLovFilter,$newLovFilter);
      //For Contractor
	  $contractLovres=$dsbObj->getLovQry(39,$oldLovFilter,$newLovFilter);
	  //For Sub Contractor
	  $Subcontract=$dsbObj->getLovQry(40,$oldLovFilter,$newLovFilter);
	  //print_r($Subcontract);
	 // For Vehicle Type
	  $VehTypeLov =$dsbObj->getLovQry(41,$oldLovFilter,$newLovFilter);
	  //For harvesting Type
	  $HrTypeLov=$dsbObj->getLovQry(42,$oldLovFilter,$newLovFilter);
      $master_lov=$dsbObj->getLovQry(61,$oldLovFilter,$newLovFilter);/* use for main section */
      $_SESSION['LOC_CODE']=$master_lov[0]['PLOC_CODE'];
	  //print_r($HrTypeLov);
      $pumpLOVres=$dsbObj->getLovQry(65,$oldLovFilter,$newLovFilter);// use for Pump

      //For Get 
	   
     }
   

   //For getting SEQ
   if($action=="add"){
	$next_seq=$dsbObj->getLovQry(26,$oldLovFilter,$newLovFilter);
	//For Getting Currunt Time
        $getTime = $qryObj->fetchQuery($qryPath,'Q001','GET_TIME',$oldfilter,$newfilter);
		$timeRes = $dsbObj->getData($getTime); 

	}
	date_default_timezone_set('Asia/Calcutta');
?>

<? require_once("header.php");?> 

<? include("sidebar.php");?> 
 
<!-- page content -->
<div class="right_col" role="main">
<div class="">

	<!-- <div class="row"> -->
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
				<form id="sample_tran" name="sample_tran" class="form-horizontal form-label-left" onsubmit="return false;">
				<ul class="contactus-list">
				<span class="section"><?=$rfObj->readData('TRAN_NAME',$langPath); 
				
                
                	 
                	?>
				 </span>
				
             <div class="panel panel-primary">
				<div class="panel-heading"><?=$rfObj->readData(1,$langPath); ?></div>
					<div class="panel-body">
					
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
							<label class="control-label"><?=$rfObj->readData('DIV',$langPath); ?>*</label>
							</div>
							
							<li><div class="col-md-7 col-sm-7 col-xs-12">
							<select class="form-control" id="div_code" name="div_code" valid="required" errmsg="Please Select Division.">
							<option value="">Select</option>
							<? for($i=0;$i<sizeof($div_lov);$i++) {?>
							<option value="<?=$div_lov[$i]['DIV_CODE']?>" <? if($HeaderdataRes[0]['TXN_DIVN'] == $div_lov[$i]['DIV_CODE'] || $master_lov[0]['TXN_DIVN'] == $div_lov[$i]['DIV_CODE']) {?> selected="selected" <? }?>><?=$div_lov[$i]['DIV_CODE']
							.'-'. $div_lov[$i]['DIV_DESC']?>
							
							</option>
							<? } ?>
							</select>
							<span id="divcode_error" style="color: red;display: none;">Please Select Division</span>
							</div></li>
							
						</div>
						
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
							<label class="control-label "><?=$rfObj->readData('LOC',$langPath); ?>*</label>
						   </div>
							<li><div class="col-md-7 col-sm-7 col-xs-12">
							<?php if($action=='add'){ ?>
							<select class="form-control" id="location" name="location" valid="required" errmsg="Please Select Location.">
							<option value="">--Select --</option>
							
							</select>
							<span id="sht_type_error" style="color: red;"></span>
							<?php  }else {?>
							<input type="text" id="location" name="location" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $HeaderdataRes[0]['PLOC_CODE']."_".$HeaderdataRes[0]['PLOC_NAME']; ?>" readonly="readonly" />
							<?php }?>
							<span id="location_error" style="color: red; display: none;">Please Select Location</span>
							</div></li>
							
						</div>
					</div><!--//row-->
					
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
							<label class="control-label "><?=$rfObj->readData('DOC',$langPath); ?>*</label>
							
							</div>
							<div class="col-md-7 col-sm-7 col-xs-12">
							<input type="text" id="doc_code" name="doc_code" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $_SESSION['DOC_CODE']; ?>" readonly="readonly">
							</div>
						</div>
						
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
							<label class="control-label"><?=$rfObj->readData('SER',$langPath); ?>*</label>
							</div>
							<li>
							<div class="col-md-7 col-sm-7 col-xs-12">
							<?php if($action=='add'){ ?>
							<select class="form-control" id="series" name="series" valid="required" errmsg="Please Select Series.">
							<option value="">--Select --</option>
							</select>
							<span id="sht_type_error" style="color: red;"></span>
							<?php  }else {?>
							<input type="text" id="series" name="series" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $HeaderdataRes[0]['SSEG_CODE']."_".$HeaderdataRes[0]['SSEG_NAME']; ?>" readonly="readonly" />
							<?php }?>
							<span id="series_error" style="color: red; display: none;">Please Select  Series</span>
							</div></li>

						</div>
					</div><!--//row-->
					
					
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
							<label class="control-label "><?=$rfObj->readData('SEQ',$langPath); ?>*</label>
						</div>
							<div class="col-md-7 col-sm-7 col-xs-12">
							
							
							<input type="text" id="seq_number" name="seq_number" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php if($action=='add'){echo $next_seq[0]['NEXT_TXNSEQ']; }if(isset($HeaderdataRes[0]['TXN_SEQ'])){ echo $HeaderdataRes[0]['TXN_SEQ'];}?>" <? if($action=='view' || $action=='update') {?> readonly="readonly"<? }?> />
							<span id="pht_type_error" style="color: red;"></span>
							</div>
						</div>
						
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
							<label class="control-label "><?=$rfObj->readData('DATE',$langPath); ?>*</label></div>
							<div class="col-md-7 col-sm-7 col-xs-12">
							<input type="text" id="header_date" name="header_date" required="required" class="form-control col-md-7 col-xs-12" value="<?php if($action =='add') 
							  { echo date("d/m/Y"); } else { echo $HeaderdataRes[0]['TXN_DATE']; }?>" readonly="readonly">
							<span id="pht_type_error" style="color: red;"></span>
							</div>
						</div>
					</div><!--//row-->
					
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
							<label class="control-label "><?=$rfObj->readData('SR_NU',$langPath); ?>*</label>
						</div>
							<div class="col-md-7 col-sm-7 col-xs-12">
							<input type="text" id="serial_number" name="serial_number" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php if($action=='add'){echo $next_seq[0]['NEXT_TXNSEQ']; }if(isset($HeaderdataRes[0]['TXN_SRNO'])){ echo $HeaderdataRes[0]['TXN_SRNO'];}?>" <? if($action=='view' || $action=='update') {?> readonly="readonly"<? }?>>
							
							</div>
						</div>
					</div><!--//row-->
					
					</div><!--//panel-body-->
				</div><!--//panel-->
				
				<div class="panel panel-primary" >
				<div class="panel-heading"><?=$rfObj->readData('2',$langPath); ?></div>
					<div class="panel-body">
						  <div class="form-group ">
							<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label">Unload Date*</label>
							</div>
								<li>
								<div class="col-md-4 col-sm-4 col-xs-12">
								  <input type="text" id="txn_amdt" name="txn_amdt" valid="required" errmsg="Please Enter Date and Time ." required="required" class="form-control col-md-7 col-xs-12 calendar" value="<?php if(isset( $HeaderdataRes[0]['TXN_AMDT'])) { echo $HeaderdataRes[0]['TXN_AMDT']; }?>"  <? if($action=='view' || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero') {?> readonly="readonly"<? }?>>
                                
                                </div></li>   
								<li>
								<div class="col-md-3 col-sm-3 col-xs-12">
								  <input type="text" id="txn_lrdt" name="txn_lrdt" valid="required" errmsg="Please Enter Date and Time ." required="required" class="form-control col-md-7 col-xs-12" value="<?php 
								  if($action =='add')
								  	  {
								  	   echo $timeRes[0]['TIME'];
								  	  }else{
                                       echo $HeaderdataRes[0]['TXN_TIME'];
								  	  }?>" <? if($action=='view' || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero') {?> readonly="readonly"<? }?>>
								
								</div></li>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label">Unload Shift</label>
							</div>
								<li><div class="col-md-7 col-sm-7 col-xs-12">
							  <select class="form-control" name="shift" id="shift" valid="required" errmsg="Please Select Shift .">
							   
							  </select>
							</div></li>
							</div>
						</div><!--//row-->
						<div class="form-group">
                          	<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('SEASON',$langPath); ?>*</label>
						     	</div>
								<li><div class="col-md-7 col-sm-7 col-xs-12">
								<select class="form-control" id="txn_season" name="txn_season" valid="required" errmsg="Please Select ">
								
								<option value="">Select</option>
								<? for($i=0;$i<sizeof($snLOVres);$i++) {?>
								<option value="<?=$snLOVres[$i]['SN_CODE']?>" <? if($HeaderdataRes[0]['TXN_SEASON'] == $snLOVres[$i]['SN_CODE']
								     || $master_lov[0]['TXN_SEASON'] == $snLOVres[$i]['SN_CODE']) {?> selected="selected" <? }?>><?=$snLOVres[$i]['SN_CODE']?>
								
								</option>
								<? } ?>
								</select>
								</div></li>
							</div> 
							<div class="col-md-6 col-sm-6 col-xs-12"> 
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('REG_NO',$langPath); ?>*</label>
							    </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								<input type="text" id="reg_no" name="reg_no" valid="required" errmsg="Please Enter Serial Number ." required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php if($action=='add'){echo $next_seq[0]['NEXT_TXNSEQ']; }if(isset($HeaderdataRes[0]['TXN_SEQ'])){ echo $HeaderdataRes[0]['TXN_SEQ'];}?>" <? if($action=='view' || $action=='update') {?> readonly="readonly"<? }?>>
								</div>
							</div>
						</div><!--//row-->
						
						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('FARM_CODE',$langPath); ?>*</label>
							     </div>
                                 <?php if($action !='add'){?>
							     <div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="txn_erefsequpdate" name="txn_erefsequpdate" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $txn_erefseq; ?>">
							    </select>
								</div>
                                <?php }else { ?>
								<li><div class="col-md-7 col-sm-7 col-xs-12">
								<select class="form-control" id="txn_erefseq" name="txn_erefseq" valid="required" errmsg="Please Select .">
							   </select>
								</div></li>
								<?php } ?>
							</div>
							
						</div><!--//row-->
						
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('VILLAGE',$langPath); ?>*</label>
							   </div>
								<li><div class="col-md-7 col-sm-7 col-xs-12">
								<input type="text" id="txn_refseq" name="txn_refseq" readonly="readonly" errmsg="Please Enter Harvesting Slip No."  required="required" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_REFSEQ'])){ echo $HeaderdataRes[0]['TXN_REFSEQ'];}?>" <? if($action=='view' || $action=='update') {?> readonly="readonly"<? }?> />
								
                        <input type="hidden" id="hs_seq" name="hs_seq" readonly="readonly">
                        <input type="hidden" id="entry_seq" name="entry_seq" readonly="readonly" value="<?php if($action=='view' || $action=='update' || $action =='uwt_zero'){ echo $HeaderdataRes[0]['TXN_EREFSEQ'];}?>">
                     
                     
								</div> </li>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('SUB_CONT',$langPath); ?></label>
							    </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								<input type="text" id="txn_accd" name="txn_accd" readonly="readonly"   required="required" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_ACCD'])){ echo $HeaderdataRes[0]['TXN_ACCD']." ".$HeaderdataRes[0]['PRT_NAME'];}?>" <? if($action=='view' || $action=='update' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
						</div><!--//row-->
						
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('VEH_TYPE',$langPath); ?></label>
							    </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								<input type="text" id="txn_date1" name="txn_date1" readonly="readonly"   required="required" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_DATE1'])){ echo $HeaderdataRes[0]['TXN_DATE1'];}?>" <? if($action=='view' || $action=='update') {?> readonly="readonly"<? }?> />
								</div>
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('VEH_NO',$langPath); ?></label>
							    </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								
								
								<input type="text" id="txn_ref2" name="txn_ref2" readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_REF2'])){ echo $HeaderdataRes[0]['TXN_REF2'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
						</div><!--//row-->
						
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('TRN_1',$langPath); ?></label>
							   </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								<input type="text" id="txn_ctype" name="txn_ctype" readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_CTYPE'])){ echo $HeaderdataRes[0]['TXN_CTYPE']." ".$HeaderdataRes[0]['CT_NAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('TRN_2',$langPath); ?></label>
							    </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								<input type="text" id="txn_cvar" name="txn_cvar"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_CVAR'])){ echo $HeaderdataRes[0]['TXN_CVAR']." ".$HeaderdataRes[0]['VARIETY_NAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>


						</div><!--//row-->
						
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('HR_TYPE',$langPath); ?> </label>
							   </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								<input type="text" id="txn_shivar" name="txn_shivar"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_SHIVAR'])){ echo $HeaderdataRes[0]['TXN_SHIVAR'].' '.$HeaderdataRes[0]['SHIVAR_NAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
						   </div>

							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('NO_LBR',$langPath); ?></label>
							   </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								<input type="radio" name="txn_flg" value="N"  class="radiobtn1" <? if(isset($HeaderdataRes)){if($HeaderdataRes[0]['TXN_FLG']=='N') {?> checked <? }?>  value="<?=$HeaderdataRes[0]['TXN_FLG']?>" <? } ?>> Nearest  
                                <input type="radio" name="txn_flg"  value="L" class="radiobtn2" <? if(isset($HeaderdataRes)){if($HeaderdataRes[0]['TXN_FLG']=='L') {?> checked <? }?>  value="<?=$HeaderdataRes[0]['TXN_FLG']?>" <? } ?>> Longest 
								</div>
							</div>
						</div><!--//row-->
						
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('NO_TYR',$langPath); ?></label>
							</div>
								<div class="col-md-7 col-sm-7 col-xs-12"> 
								
								<input type="text" id="txn_cons" name="txn_cons" readonly="readonly"  class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_CONS'])){ echo $HeaderdataRes[0]['TXN_CONS'].' '.$HeaderdataRes[0]['HRV_NAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> /> 
                                 <!-- For  Party Type hidden-->
								<input type="hidden" id="txn_flag2" name="txn_flag2" readonly="readonly"  class="form-control col-md-7 col-xs-12 " value="<?php if($action=='view' || $action=='update'){ echo $HeaderdataRes[0]['TXN_FLAG2'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> /> 
								</div>
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('NO_BLS',$langPath); ?></label>
							</div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								
								
								<input type="hidden" id="txn_trns" name="txn_trns"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if($action=='view' || $action=='update'){ echo $HeaderdataRes[0]['TXN_TRNS'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />

								<input type="text" id="trns_name"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_TRNS'])){ echo $HeaderdataRes[0]['TXN_TRNS']." ".$HeaderdataRes[0]['TRNS_NAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />

								 <!-- For  Party Type hidden-->
								<input type="hidden" id="txn_flag3" name="txn_flag3" readonly="readonly"  class="form-control col-md-7 col-xs-12 " value="<?php if($action=='view' || $action=='update'){ echo $HeaderdataRes[0]['TXN_FLAG3'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> /> 
								</div>
							</div>
						</div><!--//row-->
				         
				         <div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('TYR_BL',$langPath); ?>*</label>
							   </div>
								<div class="col-md-7 col-sm-7 col-xs-12"> 
								<input type="hidden" id="txn_htype" name="txn_htype"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if($action=='view' || $action=='update'){ echo $HeaderdataRes[0]['TXN_HTYPE'].' '.$HeaderdataRes[0]['HRV_TYPENAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />

								<input type="text" id="htype_name"   readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_HTYPE'])){ echo $HeaderdataRes[0]['TXN_HTYPE'].' '.$HeaderdataRes[0]['HRV_TYPENAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('NOD',$langPath); ?></label>
							   </div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								
								
								<input type="text" id="txn_cname" name="txn_cname"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_CNAME'])){ echo $HeaderdataRes[0]['TXN_CNAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
						</div><!--//row-->
					  
					     <div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('VT',$langPath); ?>*</label>
							   </div>
								<div class="col-md-7 col-sm-7 col-xs-12"> 
								<input type="text" id="txn_vtype" name="txn_vtype"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_VTYPE'])){ echo $HeaderdataRes[0]['TXN_VTYPE'].' '.$HeaderdataRes[0]['VEHICLE_TYPENAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('VRN',$langPath); ?></label>
							</div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								<input type="text" id="txn_vhno" name="txn_vhno"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_VHNO'])){ echo $HeaderdataRes[0]['TXN_VHNO'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
						</div><!--//row-->


						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('JOINT',$langPath)."1"; ?></label>
							</div>
								<div class="col-md-7 col-sm-7 col-xs-12"> 
								<input type="text" id="txn_vjoint" name="txn_vjoint"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_VJOINT'])){ echo $HeaderdataRes[0]['TXN_VJOINT'].' '.$HeaderdataRes[0]['JOINT_NAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
							
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('CQ',$langPath); ?></label>
							</div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								
								
								<input type="text" id="txn_caneq" name="txn_caneq"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_CANEQ'])){ echo $HeaderdataRes[0]['TXN_CANEQ']." ".$HeaderdataRes[0]['CQ_MNAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
						</div><!--//row-->

						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('F_CODE',$langPath); ?></label>
							</div>
								<div class="col-md-7 col-sm-7 col-xs-12"> 
								<input type="text" id="txn_factory" name="txn_factory"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_FACTORY'])){ echo $HeaderdataRes[0]['TXN_FACTORY'].' '.$HeaderdataRes[0]['FACT_NAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('LW',$langPath); ?>*</label>
							</div>
								<li><div class="col-md-7 col-sm-7 col-xs-12"> 
								<input type="text" id="txn_grwt" name="txn_grwt" valid="required" errmsg="Please Enter Loaded Weight ."  class="form-control col-md-7 col-xs-12 weight"  value="<?php if(isset($HeaderdataRes[0]['TXN_GRWT'])){ echo $HeaderdataRes[0]['TXN_GRWT'];}?>"  <? if($action=='view' || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero' ) {?> readonly="readonly"<? }?> />
								<font color="red"><span id="err_weight"></span></font>
								<font color="red"><span id="netwt_err"></span></font>
								<font color="red"><span id="load_err"></span></font>
								</div> </li>
								<?php if($action=='add' ) { ?>
								<!-- <div class="col-md-3 col-sm-3 col-xs-12"> 
								  <button id="btn_calwt" type="button" class="btn btn-warning">Take Weight</button>
								</div> -->
								<?php } ?> 
							</div>
						</div><!--//row-->

						<div class="form-group">
                           <div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label">Joint 2</label>
							</div>
								<div class="col-md-7 col-sm-7 col-xs-12"> 
								<input type="text" id="txn_newjoint" name="txn_newjoint"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_VJOINT1'])){ echo $HeaderdataRes[0]['TXN_VJOINT1'].' '.$HeaderdataRes[0]['JOINT_MNAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />

								
								</div>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="col-md-5 col-sm-5 col-xs-12">
								<label class="control-label ">Transporter Type</label>
							</div>
								<div class="col-md-7 col-sm-7 col-xs-12">
								
								
								<input type="text" id="txn_ttype" name="txn_ttype"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset( $HeaderdataRes[0]['TXN_TTYPE'])){ echo $HeaderdataRes[0]['TXN_TTYPE'].' '.$HeaderdataRes[0]['TRAN_TYPENAME'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
							
							
						</div><!--//row-->
                   <!-- Only For View Mode-->
                  
                   <div class="panel panel-primary">
				       <div class="panel-heading"><?=$rfObj->readData(3,$langPath); ?></div>
					     <div class="panel-body">
                          
                           <div class="form-group ">
							<div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('TYPE_1',$langPath); ?></label>
							</div>
							<li>
								<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="txn_netwt" name="txn_netwt" required="required"   valid="required" errmsg="Please Enter Unloaded Weight ."  class="form-control col-md-7 col-xs-12 unloadweight" value="<?php if(isset($HeaderdataRes[0]['TXN_NETWT'])){ echo $HeaderdataRes[0]['TXN_NETWT'];}?>" <? if($action=='view' || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero' ) {?> readonly="readonly"<? }?>  />
								<font color="red"><span id="err_unweight"></span></font>
								<font color="red"><span id="netwt1_err"></span></font>
							
								</div>
							</li>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('FRQ_1',$langPath); ?></label>
							</div>
								<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="hidden" id="txn_recvp" name="txn_recvp">
								<!-- <input type="text" id="txn_nett" name="txn_nett" required="required" class="form-control col-md-7 col-xs-12 " value="<?php //if($action=='view' || $action=='update'){ echo $HeaderdataRes[0]['TXN_NETT'];}?>"/> -->
								<input type="text" id="txn_nett" name="txn_nett"  readonly="readonly" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_NETT'])){ echo $HeaderdataRes[0]['TXN_NETT'];}?>" <? if($action=='view' ) {?> readonly="readonly"<? }?> />
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label col-md-3 col-sm-3 col-xs-12"><?=$rfObj->readData('QTY_1',$langPath); ?></label>
							</div>
								<div class="col-md-9 col-sm-9 col-xs-12 input-group">
								<input type="text" id="txn_billwt" name="txn_billwt" readonly="readonly"   required="required" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_BILLWT'])){ echo $HeaderdataRes[0]['TXN_BILLWT'];}?>" <? if($action=='view' || $action=='update') ?> />
                                 
								</div>
							</div>
						</div><!--//row-->
                       </div>
                    </div>

                      <div class="panel panel-primary">
				       <div class="panel-heading"><?=$rfObj->readData(4,$langPath); ?></div>
					     <div class="panel-body">
                          
                           <div class="form-group">
							<div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('TYPE',$langPath); ?></label>
							</div>
								<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="dsl_type" name="dsl_type" readonly="readonly"   required="required" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['DSL_ISSTYPE'])){ echo $HeaderdataRes[0]['DSL_ISSTYPE'];}?>" <? if($action=='view' || $action=='update') {?> readonly="readonly"<? }?> />
								</div>
							</div>
						<div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('FRQ',$langPath); ?></label>
							  </div>
								<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="dsl_freq" name="dsl_freq" readonly="readonly"   required="required" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['DSL_FREQUENCE'])){ echo $HeaderdataRes[0]['DSL_FREQUENCE'];}?>" <? if($action=='view' || $action=='update') {?> readonly="readonly"<? }?> />
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label "><?=$rfObj->readData('QTY',$langPath); ?></label>
							</div>
								<div class="col-md-9 col-sm-9 col-xs-12 input-group">
								<input type="text" id="txn_qty" name="txn_qty" readonly="readonly"   required="required" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['DSL_ISSQTY'])){ echo $HeaderdataRes[0]['DSL_ISSQTY'];}?>" <? if($action=='view' || $action=='update') {?> readonly="readonly"<? }?> />
                                  <span class="input-group-addon" id="basic-addon2">Ltr</span>
								</div>
							</div>
						</div><!--//row-->

					    <div class="form-group row">
							<div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('LKM',$langPath); ?></label>
							</div>
								<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="txn_totkm" name="txn_totkm"    required="required" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_TOTKM'])){ echo $HeaderdataRes[0]['TXN_TOTKM'];}?>" <? if($action=='view' || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero') {?> readonly="readonly"<? }?> />
								</div>
							</div>
					        <div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('ISSUE',$langPath); ?></label>
							</div>

								<div class="col-md-9 col-sm-9 col-xs-12 input-group">
								<input type="text" id="txn_advn" name="txn_advn" class="form-control col-md-7 col-xs-12 " value="<?php if(isset($HeaderdataRes[0]['TXN_ADVN'])){ echo $HeaderdataRes[0]['TXN_ADVN'];}?>" <? if($action=='view' || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero' ) {?> readonly="readonly"<? }?> />
								    <span class="input-group-addon" id="basic-addon2">Ltr</span>
								</div>
							      
							</div>
							
							<div class="col-md-4 col-sm-4 col-xs-12"> 
								<div class="col-md-3 col-sm-3 col-xs-12">
								<label class="control-label"><?=$rfObj->readData('SUPP_PUMP',$langPath); ?></label>
							</div>
								<div class="col-md-9 col-sm-9 col-xs-12">
								
                                 <select class="form-control" id="txn_toprt" name="txn_toprt" >
								
								<option value="">Select</option>
								<? for($i=0;$i<sizeof($pumpLOVres);$i++) {?>
								<option value="<?=$pumpLOVres[$i]['PRT_CODE']?>" <? if($HeaderdataRes[0]['TXN_TOPRT'] == $pumpLOVres[$i]['PRT_CODE']) {?> selected="selected" <? }?>><?=$pumpLOVres[$i]['PRT_NAME']?>
								
								</option>
								<? } ?>
								</select>

								</div> 
								<font color="red" ><span id="pump_error" ></span> </font>
								</div>
							</div>
						</div><!--//row-->
                        
                        <?php if(isset($HeaderdataRes[0]['TXN_RMRK']) && $action =='view') {?>
						<div class="form-group ">
							   <div class="col-md-8 col-sm-8 col-xs-12"> 
								 <div class="col-md-3 col-sm-3 col-xs-12">
								    <label class="control-label ">Remark</label>
							     </div>
							     <div class="col-md-9 col-sm-9 col-xs-12">
								   <input type="text" id="remark" value ="<?php echo $HeaderdataRes[0]['TXN_RMRK'] ;?>" name="remark" placeholder="Please Enter Remark"  class="form-control col-md-7 col-xs-12 " readonly="readonly"/>
								 </div>
							
							    </div>
							
						    </div><!--//row-->
                        <?php } ?>

                        <?php if($action=='ws_cancel') {?>
						<div class="form-group ">
							   <div class="col-md-8 col-sm-8 col-xs-12"> 
								 <div class="col-md-3 col-sm-3 col-xs-12">
								    <label class="control-label ">Remark</label>
							     </div>
							     <div class="col-md-9 col-sm-9 col-xs-12">
								   <input type="text" id="remark"  name="remark" placeholder="Please Enter Remark"  class="form-control col-md-7 col-xs-12 " />
								 </div>
							
							    </div>
							    <font color="red" ><span id="remark_error" ></span> </font>
						    </div><!--//row-->
                        <?php } ?>
                         </div>
                    </div>  


                   
                  

						<!-- For Ajax Loader -->
			  

			   <div id="wait" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;"><img src="images/loader.gif" width="64" height="64" />
	            <br>Loading..
	          </div>
				
				<div class="form-group">
					<div class="col-md-4 col-md-offset-4">
						
						<?php if($action=='add') { ?>
						<button id="btn_submit" type="button" name="submit" class="btn btn-success" value="Submit">Submit</button>
						
						<button type="reset" name="reset" class="btn btn-info" id="btn_reset">Reset</button>
						<button id="btn_cancel" type="button" class="btn btn-danger">Cancel</button>
						<?php  } ?>
						<?php if($action=='update') { ?>
						<button id="btn_submit" type="button" class="btn btn-success">Update</button>
						<button id="btn_cancel" type="button" class="btn btn-danger">Cancel</button>
						<?php  } if($action=='view' || $action=='ws_cancel' || $action=='uwt_zero' || $action=='wt_zero') {?>
						<button id="btn_back" type="button" class="btn btn-info">Back</button>
						<?php  } if($action=='ws_cancel' ) {?>
						<button id="btn_cancelws" type="button" class="btn btn-success">Revoke Weighing Slip

</button>
<?php  } if($action=='uwt_zero' ) {?>
						<button id="btn_makezerounloadwt" type="button" class="btn btn-success">Make Unload Wt Zero

</button>
<?php  } if($action=='wt_zero' ) {?>
						<button id="btn_makezeroloadwt" type="button" class="btn btn-success">Make Load Wt Zero

</button>
						<?php  } ?>
					</div>
				</div>
					
					</div><!--//PANEL BODY-->
				</div><!--//PANEL PRIMARY-->
								
				</ul>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>		
<? include("footer.php");?>


<script>
$(document).ready(function() {
	var back_link = '<?php echo $back_link ?>';
	var valFileName = '<?php echo $server_msg ?>';
	var action ="<?php echo $action; ?>";
	

$("#txn_erefseq").contractor();/*use to make autocomplete*/
$("#txn_toprt").subcontractor();
//For Disabled Slip Number in Edit and View Mode
if(action =='view' || action =='update' || action=='ws_cancel' || action=='uwt_zero' || action=='wt_zero'){
	var txn_erefseqview="<?php echo $txn_erefseq; ?>";
    $('.contractor').val(txn_erefseqview);
    $('.contractor').attr("disabled",true);
    $('.subcontractor').attr("disabled",true);

    
}

//For Readonly in View Mode
if(action =='view' || action=='ws_cancel' || action=='uwt_zero' || action=='wt_zero'){
    var printfile_name ='<?php echo $printFileRes[0]['PRINT_REP']; ?>';
    //alert(printfile_name);
    var txn_erefseq = "<?php echo $HeaderdataRes[0]['TXN_EREFSEQ'];?>";
    var entry_slipno="<?php echo $HeaderdataRes[0]['TXN_SRNO'];?>";
    var txn_vhno="<?php echo $HeaderdataRes[0]['TXN_VHNO'];?>";
     var prt_name="<?php echo $HeaderdataRes[0]['PRT_NAME'];?>";
      var joint_name="<?php echo $HeaderdataRes[0]['JOINT_NAME'];?>";
	  $('#txn_erefseq').append($('<option selected="selected">').text(entry_slipno+" "+txn_vhno+" "+prt_name+" "+joint_name).attr('value',txn_erefseq));
	$('#div_code').attr("disabled", true);
	$('#season_code').attr("disabled",true);
	$('#prt_code').attr("disabled",true);
	$('#sub_contract').attr("disabled",true);
	$('#veh_type').attr("disabled",true);
	$('#hr_type').attr("disabled",true);
	$('#txn_erefseq').attr("disabled",true);
	$('#txn_season').attr("disabled",true);
	$('#3_gnt').attr("disabled",true);
	$('#shift').attr("disabled", true);
	
   
}

//modified by mangesh 

if(action =='add')
{
var loc = '<?php echo $master_lov[0]['PLOC_CODE']; ?>';
var ser = '<?php echo $master_lov[0]['SSEG_CODE']; ?>';
var shift = '<?php echo $master_lov[0]['TXN_FLAG1']; ?>';
}else{
var loc = '<?php echo $HeaderdataRes[0]['PLOC_CODE']; ?>';
var ser = '<?php echo $master_lov[0]['SSEG_CODE']; ?>';
var shift = '<?php echo $HeaderdataRes[0]['TXN_FLAG1']; ?>';
}

//alert(shift);
$.ajax({
		  url: "unloadsliptran_server.php",
		  data:{action:'shift',loc_code:loc},
		  datatype: "json",
		  success: function(data){
			//alert(data);
			  //set shift
			  result = data.split('*');
			  data = $.parseJSON(result[0]);
			  $('#shift').append($('<option>').text('Select').attr('value',''));
			  $.each(data, function(i, value) {
			  	//alert(JSON.stringify(value));
			  	if(shift == value['SHF_CODE'])
			  	{
			  	// /	alert('In if');
			  		$('#shift').append($('<option selected="selected">').text(value['SHF_DESC']).attr('value', value['SHF_CODE'])); 
			  	}else{
				$('#shift').append($('<option>').text(value['SHF_DESC']).attr('value', value['SHF_CODE']));
			   }
			  });
		  }  
	 });//ajax

//alert(loc);
var div_code=$("#div_code").val();
	$("#location").empty();
	$("#series").empty();
	$("#wait").show();
    $.ajax({
            url:"trans_server.php",
            data:{'action':'location',div_code:div_code},
            datatype: "json",
            success: function(data){
            $("#wait").hide();
            data = $.parseJSON(data);
			  
			  $.each(data, function(i, value) {
			  	if(value['PLOC_CODE'] == loc){
			  		$('#location').append($('<option selected="selected">').text(value['LOCATION']).attr('value', value['PLOC_CODE']));
			  	}else{
			  		$('#location').append($('<option>').text(value['LOCATION']).attr('value', value['PLOC_CODE']));
			  	}
              });
            }  
         });
//alert("2");
var loc_code=$("#location").val();
 	$("#series").empty();
	$.ajax({
              url: "trans_server.php",
              data:{'action':'series',loc_code:loc_code},
			  datatype: "json",
              success: function(res){
              res = $.parseJSON(res);
			  $.each(res, function(i, value) {
			  if(value['SSEG_CODE'] == ser){
			  		$('#series').append($('<option selected="selected">').text(value['SSEG_NAME']).attr('value', value['SSEG_CODE']));
			  	}else{
			  		$('#series').append($('<option>').text(value['SSEG_NAME']).attr('value', value['SSEG_CODE']));
			  	}
              });
				  
            }  
         });


	
    
    //For Auto Select Season
    var txn_season=$("#txn_season").val();
	var location=loc//.val().split("-");
	var div_code=$("#div_code").val();
	var series=ser;
	//console.log(txn_season+""+location+""+div_code+""+series);
    $.ajax({
              url: "manualweighingslip_server.php",
              data:$.param({'action':'getslip'})+'&'+$.param({'div_code':div_code})+'&'+$.param({'location':location})
              +'&'+$.param({'series':series})+'&'+$.param({'txn_season':txn_season}),
			  datatype: "json",
              success: function(data){
			  data = $.parseJSON(data);
			  $('#txn_erefseq').append($('<option>').text('--Select--').attr('value',''));
			  if(data.length ==0)
			  {
			  	
                  
			  }else{
			  $.each(data, function(i, value) {
               $('#txn_erefseq').append($('<option>').text(value['ENTRY_SRNO']+" "+value["VHL_REGNO"]+" "+value["FNAME"]+" "+value["TRNS_NAME"]+" "+value["JOINT_NAME"]+" "+value["JOINT_MNAME"]).attr('value', value['ENTRY_SEQ']+"_"+value["HS_SRNO"]+"_"+value["FARMER"]+"_"+value["FNAME"]+"_"+value["PLANTATION_DT"]+"_"+value["SURVEY_NO"]+"_"+value["CANE_TYPE"]+"_"+value["CTYPE_NAME"]+"_"+value["CANE_VARIETY"]+"_"+value["CVAR_NAME"]+"_"+value["SHIVAR_CODE"]+"_"+value["SHIVAR_NAME"]+"_"+value["HARV_CODE"]+"_"+value["HRV_NAME"]+"_"+value["TRNS_CODE"]+"_"+value["TRNS_NAME"]+"_"+value["HAV_TYPE"]+"_"+value["HAV_TYPENAME"]+"_"+value["DRIVER_NAME"]+"_"+value["VHL_TYPE"]+"_"+value["VEHICLE_TYPENAME"]+"_"+value["VHL_REGNO"]+"_"+value["TXN_VJOINT"]+"_"+value["JOINT_NAME"]+"_"+value["CQ_CODE"]+"_"+value["CQ_NAME"]+"_"+value["FACT_CODE"]+"_"+value["FACT_NAME"]+"_"+value["TXN_FLAG2"]+"_"+value["TXN_FLAG3"]+"_"+value["HS_SEQ"]+"_"+value["F_MNAME"]+"_"+value["HRV_MNAME"]+"_"+value["TRNS_MNAME"]+"_"+value["CT_MNAME"]+"_"+value["TXN_VJOINT1"]+"_"+value["JOINT_MNAME"]+"_"+value["TRAN_TYPE"]+"_"+value["TRAN_TYPENAME"]
                   	+"_"+value["DSL_ISSQTY"]+"_"+value["BINDING_PERC"]
               	+"_"+value["DSL_ISSTYPE"]+"_"+value["DSL_FREQUENCE"]+"_"+value["VT_DISTANCE"]+"_"+value["TOT_KM"]
               	)); 
              });  
			 }//else	  ,,
            }  
         }); //Ajax

if(action =='view' || action=='update'){
	 var fwt =$('#txn_grwt').val();
    fwt =parseFloat(fwt);
  // alert("In Updae"+fwt);
   $('#txn_grwt').val(fwt.toFixed(3));
   
    var ftxn_netwt =$('#txn_netwt').val();
    ftxn_netwt =parseFloat(ftxn_netwt);
  // alert("In Updae"+fwt);
   $('#txn_netwt').val(ftxn_netwt.toFixed(3));

    var ftxn_billwt =$('#txn_billwt').val();
    ftxn_billwt =parseFloat(ftxn_billwt);
   // alert(ftxn_billwt);
   $('#txn_billwt').val(ftxn_billwt.toFixed(3));

    var ftxn_nett =$('#txn_nett').val();
    ftxn_nett =parseFloat(ftxn_nett);
   // alert(ftxn_billwt);
   $('#txn_nett').val(ftxn_nett.toFixed(3));

}

//For Readonly in View Mode
if(action =='update'){
 
	$('#div_code').attr("disabled", true);
	$('#season_code').attr("disabled",true);
    var txn_erefseq = "<?php echo $HeaderdataRes[0]['TXN_EREFSEQ'];?>";
    var entry_slipno="<?php echo $HeaderdataRes[0]['TXN_SRNO'];?>";
    var txn_vhno="<?php echo $HeaderdataRes[0]['TXN_VHNO'];?>";
     var prt_name="<?php echo $HeaderdataRes[0]['PRT_NAME'];?>";
      var joint_name="<?php echo $HeaderdataRes[0]['JOINT_NAME'];?>";
    
   //TXN_VHNO PRT_NAME JOINT_NAME
   // alert(txn_erefseq+" "+entry_slipno);
	$('#txn_season').attr("disabled",true);

    //alert("In Update"+txn_erefseq);
    var txn_season=$("#txn_season").val();
	var location=$("#location").val().split("_");//.val().split("-");
	var div_code=$("#div_code").val();
	var series=$("#series").val().split("_");
	
	$.ajax({
              url: "manualweighingslip_server.php",
              data:$.param({'action':'getslip'})+'&'+$.param({'div_code':div_code})+'&'+$.param({'location':location[0]})
              +'&'+$.param({'series':series[0]})+'&'+$.param({'txn_season':txn_season}),
			  datatype: "json",
              success: function(data){
			  data = $.parseJSON(data);
			    $('#txn_erefseq').append($('<option selected="selected">').text(entry_slipno+" "+txn_vhno+" "+prt_name+" "+joint_name).attr('value',txn_erefseq));
			  if(data.length ==0)
			  {
			  	console.log("data not found");
			  }else{
			  $.each(data, function(i, value) {
               $('#txn_erefseq').append($('<option>').text(value['ENTRY_SRNO']+" "+value["VHL_REGNO"]+" "+value["FNAME"]+" "+value["JOINT_NAME"]).attr('value', value['ENTRY_SEQ']+"_"+value["HS_SRNO"]+"_"+value["FARMER"]+"_"+value["FNAME"]+"_"+value["PLANTATION_DT"]+"_"+value["SURVEY_NO"]+"_"+value["CANE_TYPE"]+"_"+value["CTYPE_NAME"]+"_"+value["CANE_VARIETY"]+"_"+value["CVAR_NAME"]+"_"+value["SHIVAR_CODE"]+"_"+value["SHIVAR_NAME"]+"_"+value["HARV_CODE"]+"_"+value["HRV_NAME"]+"_"+value["TRNS_CODE"]+"_"+value["TRNS_NAME"]+"_"+value["HAV_TYPE"]+"_"+value["HAV_TYPENAME"]+"_"+value["DRIVER_NAME"]+"_"+value["VHL_TYPE"]+"_"+value["VEHICLE_TYPENAME"]+"_"+value["VHL_REGNO"]+"_"+value["TXN_VJOINT"]+"_"+value["JOINT_NAME"]+"_"+value["CQ_CODE"]+"_"+value["CQ_NAME"]+"_"+value["FACT_CODE"]+"_"+value["FACT_NAME"]+"_"+value["TXN_FLAG2"]+"_"+value["TXN_FLAG3"]+"_"+value["HS_SEQ"]+"_"+value["F_MNAME"]+"_"+value["HRV_MNAME"]+"_"+value["TRNS_MNAME"]+"_"+value["CT_MNAME"]+"_"+value["TXN_VJOINT1"]+"_"+value["JOINT_MNAME"]+"_"+value["TRAN_TYPE"]+"_"+value["TRAN_TYPENAME"]+"_"+value["DSL_ISSQTY"]+"_"+value["BINDING_PERC"]
               	+"_"+value["DSL_ISSTYPE"]+"_"+value["DSL_FREQUENCE"]+"_"+value["VT_DISTANCE"]
               	+"_"+value["TOT_KM"])); 
              });  
			 }//else	  FACT_CODE
            }  
         }); //Ajax
	
}


//For Print
 $('#btn_print').on('click', function() {
 	 var seq_number =$('#seq_number').val();
 	 if(printfile_name !=""){
     window.open(printfile_name+'?txn_seq='+seq_number, '_blank');
     }else{
     	swal({
		     title: "Probleme In Print",
			 text: "Please check ssegprint table .",
			 type: 'error',
			 confirmButtonColor: '#3085d6',
			 cancelButtonColor: '#d33',
			 confirmButtonText: 'OK'
			}).then(function () {
			window.location.href = back_link;
		 })
     }
 });


 
  

//Submit data and insert into tabl
 $('#btn_submit').on('click', function() {
 	var btn_action =$(this).val();
 	//alert(btn_action);
 
 	//return 0;
	var res = validKeyInd();
	var txn_qty =$("#txn_qty").val();
	var txn_toprt =$("#txn_toprt").val();
	var txn_advn=$("#txn_advn").val();
   //alert(txn_toprt+" "+txn_advn+"txn_qty"+txn_qty);
	if( txn_toprt =="" && txn_advn != 0){
    //if(txn_advn != 0 && action=='add'){
		//alert("Please");
		$("#pump_error").html("Please Select Pump");
		return 0;
	}else{
		$("#pump_error").html("");
		//return 0;
	}
	
	if(errCOUNT == 0)
	{ 
		$("#wait").show();
	 $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#sample_tran').serialize()+'&'+$.param({'action':'fullform'})+
              '&'+$.param({'flag':action}),
			  datatype: "json",
              success: function(data){
              $("#wait").hide();
			  if(data == 1 && action =='add')
				   {
				    swal({
					  title: msg = getMsg(1,valFileName),//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
					window.location.href = back_link;
				   }else if(data == 1 && action =='update')
				   {
				     swal({
					  title: msg = getMsg(2,valFileName),//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
					window.location.href = back_link;
				   }else 
				   {
				   	 var msg = data.trim();
				     swal(msg, "", "error");
				      
				   }
				  
				
				//location.href = "view_browse.php?menu_code=L00010";
			  }
         });
	}
	
});


$("#txn_netwt,#txn_grwt").keyup(function(){
	 var txn_grwt=$("#txn_grwt").val();
     var txn_recvp=$("#txn_recvp").val();
     //alert(txn_recvp);
     //console.log(txn_grwt);
     if(txn_grwt =='' || txn_grwt < 1){
     	$("#load_err").html("Please Enter Loaded Weight");
     	 $("#txn_grwt").focus();
     }else{
     	 $("#load_err").html("");
     }
	var txn_netwt=$(this).val();
	//alert(txn_recvp);
    console.log(txn_netwt+" "+txn_grwt+""+txn_recvp);
    //alert(txn_recvp);
    if (txn_recvp == 0) 
	   {
	     txn_recvp=1;
	   }
	 //  alert(txn_recvp);
	var txn_nett= (parseFloat(txn_grwt) - parseFloat(txn_netwt) )* (parseFloat(txn_recvp) / 100) ;
	txn_nett =parseFloat(txn_nett.toFixed(3));
    var txn_billwt =parseFloat(txn_grwt) - parseFloat(txn_netwt) - parseFloat(txn_nett);
	//	console.log(txn_billwt+" "+txn_nett);	   
	$("#txn_nett").val(txn_nett.toFixed(3).trim());
    $("#txn_billwt").val(txn_billwt.toFixed(3).trim());

    var div_code=$("#div_code").val();
    var txn_season =$("#txn_season").val();

     //For Dsl Issue
    /* $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#sample_tran').serialize()+'&'+$.param({'action':'issue_dsl'})+
              '&'+$.param({'div_code':div_code})+
              '&'+$.param({'txn_season':txn_season}),
			  datatype: "json",
              success: function(data){
               //console.log("Data after ajax success"+data);
               $("#txn_advn").val(data.trim());
			  }
         });*/
});

//FOR CANCEL BUTTon
$('#btn_back').on('click', function() {
 window.location.href = back_link;	
});//Cancel	

$("#txn_grwt").keyup(function(){
	var txn_netwt=$(this).val();
	if(txn_netwt == 0){
		$("#netwt_err").html("Please enter greater than 0");
	}else{
        $("#netwt_err").html("");
	}
	});//Cancel	

$("#txn_netwt").keyup(function(){

var txn_netwt=$(this).val();
	if(txn_netwt == 0){
		$("#netwt1_err").html("Please enter greater than 0");
	}else{
        $("#netwt1_err").html("");
	}

});//Cancel	



//FOR CANCEL BUTTon
$('#btn_calwt').on('click', function() {
	 $.ajax({
              url: "manualweighingslip_server.php",
              data:$.param({'action':'getweight'}),/*Send action flag parameter to server file*/
			  datatype: "json",
              success: function(data){
			   var weight =data.trim();
               var fwt =parseFloat(weight);
              // alert(fwt);
			   if(weight ==""){
			   	swal("Probleme in getting weight", "", "error");
			   }
			   $('#txn_grwt').val(fwt.toFixed(3));
              }
				  
            
         }); //end of Ajax
	 
});//Cancel	

/*$("#txn_grwt").keyup(function(){
	var txn_grwt=$(this).val();
	 var fwt =parseFloat(weight);
    
});*/

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
			window.location.href = back_link;
		})
});//Cancel	





//For getting Location 
$("#div_code").change(function(){
	var div_code=$("#div_code").val();
    $("#location").empty();
	  $("#series").empty();
    $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#itmast-form').serialize()+'&'+$.param({'action':'location'})+'&'+$.param({'div_code':div_code}),/*Send action flag parameter to server file*/
			  datatype: "json",
              success: function(data){
			  data = $.parseJSON(data);
			  $('#location').append($('<option>').text('Select').attr('value',''));
			  $.each(data, function(i, value) {
			  $('#location').append($('<option>').text(value['LOCATION']).attr('value', value['PLOC_CODE']));
              });
				  
            }  
         });
  
    });

//For Series Dropdown
 $("#location").change(function(){
	var loc_code=$("#location").val();
	//alert(loc_code);
    $("#series").empty();
    $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#itmast-form').serialize()+'&'+$.param({'action':'series'})+'&'+$.param({'location':loc_code}),
			  datatype: "json",
              success: function(data){
			  data = $.parseJSON(data);
			  $('#series').append($('<option>').text('Select').attr('value',''));
			  
			  $.each(data, function(i, value) {
			  $('#series').append($('<option>').text(value['SSEG_NAME']).attr('value', value['SSEG_CODE']));
              });
				  
            }  
         });

    //FOr Shift
    $("#shift").empty();
	$.ajax({
		  url: "unloadsliptran_server.php",
		  data:{action:'shift',loc_code:loc},
		  datatype: "json",
		  success: function(data){
			//alert(data);
			  //set shift
			  result = data.split('*');
			  data = $.parseJSON(result[0]);
			  $('#shift').append($('<option>').text('Select').attr('value',''));
			  $.each(data, function(i, value) {
				$('#shift').append($('<option>').text(value['SHF_DESC']).attr('value', value['SHF_CODE']));
			  });
		  }  
	 });//ajax

    });

//For Reg Nu .





//For First Guranter
$("#txn_season").change(function(){
	$('#txn_erefseq').attr("disabled",false);
	$("#txn_erefseq").empty();
	$("#divcode_error").hide();
	var txn_season=$(this).val();
	var location=$("#location").val();//.val().split("-");
	var div_code=$("#div_code").val();
	var series=$("#series").val();
	//console.log(location+" "+div_code+" "+series);
    if(div_code == "") {
    	 $("#div_code").focus();
    	$("#divcode_error").show();
       }
       else if(location =="") {
       	 $("#location").focus();
       	 $("#divcode_error").hide()
       $("#location_error").show();
       }else if(series =="") {
       	 $("#series").focus();
       	 $("#location_error").hide();
       $("#series_error").show();
       }
        else {
        $("#series_error").hide();
    //var sub_contract=$("#sub_contract").val();

	$.ajax({
              url: "manualweighingslip_server.php",
              data:$.param({'action':'getslip'})+'&'+$.param({'div_code':div_code})+'&'+$.param({'location':location})
              +'&'+$.param({'series':series})+'&'+$.param({'txn_season':txn_season}),
			  datatype: "json",
              success: function(data){
			  data = $.parseJSON(data);
			  $('#txn_erefseq').append($('<option>').text('--Select--').attr('value',''));
			  if(data.length ==0)
			  {
			  	swal({
					  title:"Entry Slip Not Exits !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'error',
					  showConfirmButton: false
					});
                  
			  }else{
			  $.each(data, function(i, value) {
               $('#txn_erefseq').append($('<option>').text(value['ENTRY_SRNO']+" "+value["VHL_REGNO"]+" "+value["FNAME"]+" "+value["JOINT_NAME"]).attr('value', value['ENTRY_SEQ']+"_"+value["HS_SRNO"]+"_"+value["FARMER"]+"_"+value["FNAME"]+"_"+value["PLANTATION_DT"]+"_"+value["SURVEY_NO"]+"_"+value["CANE_TYPE"]+"_"+value["CTYPE_NAME"]+"_"+value["CANE_VARIETY"]+"_"+value["CVAR_NAME"]+"_"+value["SHIVAR_CODE"]+"_"+value["SHIVAR_NAME"]+"_"+value["HARV_CODE"]+"_"+value["HRV_NAME"]+"_"+value["TRNS_CODE"]+"_"+value["TRNS_NAME"]+"_"+value["HAV_TYPE"]+"_"+value["HAV_TYPENAME"]+"_"+value["DRIVER_NAME"]+"_"+value["VHL_TYPE"]+"_"+value["VEHICLE_TYPENAME"]+"_"+value["VHL_REGNO"]+"_"+value["TXN_VJOINT"]+"_"+value["JOINT_NAME"]+"_"+value["CQ_CODE"]+"_"+value["CQ_NAME"]+"_"+value["FACT_CODE"]+"_"+value["FACT_NAME"]+"_"+value["TXN_FLAG2"]+"_"+value["TXN_FLAG3"]+"_"+value["HS_SEQ"]+"_"+value["F_MNAME"]+"_"+value["HRV_MNAME"]+"_"+value["TRNS_MNAME"]+"_"+value["CT_MNAME"]+"_"+value["TXN_VJOINT1"]+"_"+value["JOINT_MNAME"]+"_"+value["TRAN_TYPE"]+"_"+value["TRAN_TYPENAME"]+"_"+value["DSL_ISSQTY"]+"_"+value["BINDING_PERC"]
               	+"_"+value["DSL_ISSTYPE"]+"_"+value["DSL_FREQUENCE"]+"_"+value["VT_DISTANCE"]
               	+"_"+value["TOT_KM"])); 
              });  
			 }//else	  FACT_CODE VT_DISTANCE
            }  
         }); //Ajax
      } //else       
 }); //On change
//$("#txn_erefseq").contractor();/*use to make autocomplete*/
//For Item Description
 $("#txn_erefseq,.contractor").blur(function(){
 	//$(".contractor").blur(function(){
 	//$("#txn_erefseq").empty(); parseInt(age) || 0;
  var data1=$('#txn_erefseq').val().split("_");
    
   //var data1=$(this).val().split("_");
   var data = data1.map(function(val, i) {
    return val === 'null' ? '' : val;
   });
   console.log(JSON.stringify(data));
   if(data ==""){
   	return 0;
   }else {
   
   $('#entry_seq').val(data[0]);
   $('#txn_refseq').val(data[1]);
   $('#txn_accd').val(data[2]+" "+data[3]);
   $('#txn_date1').val(data[4]);
   $('#txn_ref2').val(data[5]);
   $('#txn_ctype').val(data[6]+" "+data[7]);
   $('#txn_cvar').val(data[8]+" "+data[9]);
   $('#txn_shivar').val(data[10]+" "+data[11]);
   $('#txn_cons').val(data[12]+" "+data[13]);
   $('#txn_trns').val(data[14]+" "+data[15]);
   $('#txn_htype').val(data[16]+" "+data[17]);
   $('#txn_cname').val(data[18]);
   $('#txn_vtype').val(data[19]+" "+data[20]);
   $('#txn_vhno').val(data[21]);
   $('#txn_vjoint').val(data[22]+" "+data[23]);
   $('#txn_caneq').val(data[24]+" "+data[25]);
   $('#txn_factory').val(data[26]+" "+data[27]);
   $('#txn_flag2').val(data[28]);
   $('#txn_flag3').val(data[29]);
   $('#hs_seq').val(data[30]);
   $('#txn_newjoint').val(data[35]+" "+data[36]);
   $('#txn_ttype').val(data[37]+" "+data[38]);
   $('#trns_name').val(data[15]+" "+data[14]); 
   $('#htype_name').val(data[17]+" "+data[16]); 
   $('#txn_recvp').val(data[40]);
   $('#txn_qty').val(data[39]);
   $('#dsl_type').val(data[41]);
   $('#dsl_freq').val(data[42]);
   $('#txn_totkm').val(data[44]);
   $('#txn_erefseq').attr("disabled",true);
    //For Radio 
    var radioval=data[43];
    if(radioval =='N'){
    $('.radiobtn1').prop('checked','checked');
   }else{
     $('.radiobtn2').prop('checked','checked');
   }
   
    //For Dsl Issue
     $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#sample_tran').serialize()+'&'+$.param({'action':'issue_dsl'})+
              '&'+$.param({'div_code':div_code})+
              '&'+$.param({'txn_season':txn_season}),
			  datatype: "json",
              success: function(data){
               //console.log("Data after ajax success"+data);
               $("#txn_advn").val(data.trim());
			  }
         });

   }
 
 });
 
 

//For Getting Season Code
var season_code ="<?php echo $HeaderdataRes[0]['TXN_SEASON']; ?>";

//For Make Load Weight Zero
$('#btn_makezeroloadwt').on('click', function() {
	 $("#wait").show();
	 //alert(season_code);
    $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#sample_tran').serialize()+'&'+$.param({'action':'wt_zero'})+'&'+$.param({'txn_season':season_code}),
			  datatype: "json",
              success: function(data){
              $("#wait").hide();  
			    if(data.trim() == 'lock'){
			    	swal({
					  title:"Season Period is lock !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'error',
					  showConfirmButton: false
					});
			    }else if(data.trim() == 1){
			    	swal({
					  title:"Load Weight Zero Set !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
					window.location.href = back_link;
			    }else if(data.trim() == 'bill_wt'){
			    	  swal({
					  title: 'Unload weight found !',
					  text: "Do you want to set unload weight as zero?",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes'
					}).then(function () {
						setload_wtzero();
					})
			    }
			    else{
			    	swal({
					  title:"Something Wrong !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
			    }
			  }
         });


 });//Make Load Weight Zero  


function setload_wtzero() {
	 $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#sample_tran').serialize()+'&'+$.param({'action':'confirmwt_zero'}),
			  datatype: "json",
              success: function(data){
              if(data.trim() == 1){
               swal({
					  title:"Load Weight Zero Set !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
               window.location.href = back_link;
                }else{

                	swal({
					  title:"Something Wrong !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});

                }
               
			 }//Ajax Success
     });
}


//For Make Un Load Weight Zero
$('#btn_makezerounloadwt').on('click', function() {
   $("#wait").show();
	 //alert(season_code);
    $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#sample_tran').serialize()+'&'+$.param({'action':'unloadwt_zero'})+'&'+$.param({'txn_season':season_code}),
			  datatype: "json",
              success: function(data){
              $("#wait").hide();  
			    if(data.trim() == 'lock'){
			    	swal({
					  title:"Season Period is lock !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'error',
					  showConfirmButton: false
					});
			    }else if(data.trim() == 1){
			    	swal({
					  title:"UnLoad Weight Zero Set !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
					window.location.href = back_link;
			    }
			    else{
			    	swal({
					  title:"Something Wrong !",//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
			    }
			  }
         });

 });//Make Un Load Weight Zero  

//For Cancel Weighing Slip
$('#btn_cancelws').on('click', function() {
   var remark =$('#remark').val();
    if(remark ==''){
       $("#remark_error").html("Please Enter Remark");
       $('#remark').focus();
		return 0;
    }else{
    	$("#remark_error").html("");
    	$("#wait").show();
    $.ajax({
              url: "manualweighingslip_server.php",
              data:$('#sample_tran').serialize()+'&'+$.param({'action':'revoke_wslip'})+'&'+$.param({'txn_season':season_code}),
			  datatype: "json",
              success: function(data){
              $("#wait").hide();  
			    if(data.trim() == 'SUCCESS'){
			    	swal({
					  title:'Transaction Revoke Successfully.',//data.trim(),//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'success',
					  showConfirmButton: false
					});
					 //For set time out
					setTimeout(function(){// wait for 5 secs(2)
                      window.location.href = back_link; // then reload the page.(3)
                    }, 5000); 
					

			    }
			    else{
			    	swal({
					  title:data.trim(),//call getMsg function with message number and file name
					  timer: 10000,
					  type: 'error',
					  showConfirmButton: true
					});
					//For set time out
					setTimeout(function(){// wait for 5 secs(2)
                      window.location.href = back_link; // then reload the page.(3)
                    }, 5000); 
					
			    }
			  }
         });
    }
});//Cancel	 Weighing Slip 

});//ready

	

</script>

