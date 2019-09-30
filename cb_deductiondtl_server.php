<?php
require_once('dashboard.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$lgs->lg->info("--START Deduction DTL SERVER FILE--");
$qryPath = $_SESSION['QRYPATH'];
$qryPath = $qryPath."general/cb_deductiondtl.ini";
$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];
$action_flag = $_REQUEST['action'];
$flag = $_REQUEST['flag'];

//For Insert and Update data
if($action_flag == 'fullform')
{  
		$oldInsertFilter = array();
		$newInsertFilter = array();
		
		$crate_code = $_REQUEST['crate_code'];
		$season = $_SESSION['SEASON'];
		$billtype = $_REQUEST['billtype'];
		$fortnight = $_REQUEST['fortnight'];
		$tn_detail = $_REQUEST['tn_detail'];
		$tnDataSet = $_REQUEST['tnDataSet'];

		//For Add Insert New Record              
		if($flag=="add")
		{
			/*Insert Into Main Table*/
			$lgs->lg->info("--I'm in Add--");
			$oldFilter = array();
			$newFilter = array();

			$lgs->lg->info("--Detail Row--".json_encode($tn_detail));	

			array_push($oldFilter,':PCOMP_CODE',':PCD_SRNO',':PCD_SCODE',':PCD_FNNO',':PCD_BTYPE');
			array_push($newFilter,$comp_code,$crate_code,$season,$fortnight,$billtype);			

			$crateQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_HEADER',$oldFilter,$newFilter);
			$lgs->lg->info("--Header Qry--".$crateQry);
		    $crateRes = $dsbObj->updateData($crateQry);

			$cnt = 0;
			for($i=0;$i<sizeof($tn_detail);$i++)
			{
				$oldTnFilter = array();
			    $newTnFilter = array();	
				$cnt = $cnt+1;
				if($tn_detail[$i][5] == 'Bal Based')
						{
							$cat = 'B';		
						}else{
							$cat = 'C';
						}
					if($tn_detail[$i][6] == 'Percentage'){
							$rule = 'P';		
						}else if($tn_detail[$i][6] == 'Amount'){
							$rule = 'A';
						}else{
							$rule = 'R';
						}	
		array_push($oldTnFilter,':PCOMP_CODE',':PCDD_SRNO',':PCDD_RUNO',':PCDD_SORT',':PCDD_DCODE',':PCDD_CAT',':PCDD_RULE',':PCDD_VALUE');
		array_push($newTnFilter,$comp_code,$crate_code,$cnt,$tn_detail[$i][1],$tn_detail[$i][2],$cat,$rule,$tn_detail[$i][7]);			
			$tnQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_DETAIL',$oldTnFilter,$newTnFilter);
			$lgs->lg->info("--Detail Qry--".$tnQry);
		    $tnRes = $dsbObj->updateData($tnQry);
			}//for
			echo $tnRes; 
			exit(0);
		}//end if

        if($flag == 'update')
		{
		 
		  $lgs->lg->info("--I'm in update--".json_encode($tn_detail));
		  $lgs->lg->info("--I'm in update--".json_encode($tn_detail));
		  $lgs->lg->info("--Updated Data--".json_encode($tnDataSet));
	   	    $oldUpdFilter = array();
			$newUpdFilter = array();	

			array_push($oldUpdFilter,':PCOMP_CODE',':PCD_SRNO',':PCD_SCODE',':PCD_FNNO',':PCD_BTYPE');
			array_push($newUpdFilter,$comp_code,$crate_code,$season,$fortnight,$billtype);			

			$crateUpdQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_HEADER',$oldUpdFilter,$newUpdFilter);
			$lgs->lg->info("--Header DED DTL Upd Qry--".$crateUpdQry);
		    $crateUpdRes = $dsbObj->updateData($crateUpdQry);
		    $lgs->lg->info("--Header DED DTL Upd Qry--".$crateUpdRes);

		  /*Update row of detail table*/
		  $lgs->lg->info("--Cane DED DTL Updated data--".json_encode($tnDataSet));	
		  if(isset($tnDataSet))
		  {
		  		for($i=0;$i<sizeof($tnDataSet);$i++)
				{
					$oldUpdFilter = array();
				    $newUpdFilter = array();	

				    if($tnDataSet[$i][5] == 'Bal Based')
						{
							$cat = 'B';		
						}else{
							$cat = 'C';
						}
					if($tnDataSet[$i][6] == 'Percentage'){
							$rule = 'P';		
						}else if($tnDataSet[$i][6] == 'Amount'){
							$rule = 'A';
						}else{
							$rule = 'R';
						}	

		array_push($oldUpdFilter,':PCOMP_CODE',':PCDD_SRNO',':PCDD_RUNO',':PCDD_SORT',':PCDD_DCODE',':PCDD_CAT',':PCDD_RULE',':PCDD_VALUE');
		array_push($newUpdFilter,$comp_code,$crate_code,$tnDataSet[$i][0],$tnDataSet[$i][1],$tnDataSet[$i][2],$cat,$rule,$tnDataSet[$i][7]);
					$updQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_DETAIL',$oldUpdFilter,$newUpdFilter);
					$lgs->lg->info("--Cane DED DTL Update Qry--".$updQry);
					$updRes = $dsbObj->updateData($updQry);
					$lgs->lg->info("--Cane DED DTL Update Res--".$updRes);
					echo $updRes;
				}//for    
		  }

		  /*Add new row in detail */
		  $lgs->lg->info("--New Row--".json_encode($tn_detail));	
		  for($i=0;$i<sizeof($tn_detail);$i++)
			{
				$oldTnFilter = array();
			    $newTnFilter = array();	
			    $upd_flag = $tn_detail[$i][8];
			    if($upd_flag == 'ins')
			    {

			    	if($tn_detail[$i][5] == 'Bal Based')
						{
							$cat = 'B';		
						}else{
							$cat = 'C';
						}
					if($tn_detail[$i][6] == 'Percentage'){
							$rule = 'P';		
						}else if($tn_detail[$i][6] == 'Amount'){
							$rule = 'A';
						}else{
							$rule = 'R';
						}	

	array_push($oldTnFilter,':PCOMP_CODE',':PCDD_SRNO',':PCDD_RUNO',':PCDD_SORT',':PCDD_DCODE',':PCDD_CAT',':PCDD_RULE',':PCDD_VALUE');
	array_push($newTnFilter,$comp_code,$crate_code,$tn_detail[$i][0],$tn_detail[$i][1],$tn_detail[$i][2],$cat,$rule,$tn_detail[$i][7]);

					$tnQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_DETAIL',$oldTnFilter,$newTnFilter);
				    $lgs->lg->info("--Cane DED DTL Insert Qry--".$tnQry);
				    $tnRes = $dsbObj->updateData($tnQry);
				    $lgs->lg->info("--Cane DED DTL Insert Res--".$tnRes);
				    echo $tnRes;
			    }//inner if
			}//for

			/*Delete row from detail table*/
			$diffArr = array();
			for($i=0;$i<sizeof($tn_detail);$i++){
				array_push($diffArr,$tn_detail[$i][0]);
			}

			$deleteArr=array_diff($_SESSION['oldruno'],$diffArr);
			if(sizeof($deleteArr)>0)
			{
				foreach($deleteArr as $delarr)
				{
					$oldDelFilter = array(':PCOMP_CODE',':PCD_SRNO',':PCDD_RUNO');
					$newDelFilter = array($_SESSION['COMP_CODE'],$crate_code,$delarr['CDD_RUNO']);	
					$delQry = $qryObj->fetchQuery($qryPath,'Q001','DELETE_DETAIL',$oldDelFilter,$newDelFilter);
					$delRes = $dsbObj->updateData($delQry);
					$lgs->lg->trace("--Cane Rate Delete QUERY --:".$delQry);
				}
				$lgs->lg->info("--Cane Rate Delete--".$delRes);
				echo $delRes;
			}    
		}//end main if


		
}//end if


if($action_flag == 'fortnight')
{
   $oldLovFilter = array(':PCOMP_CODE',':PSEASON',':PBTYPE');
   $newLovFilter = array($_SESSION['COMP_CODE'],$_REQUEST['season'],$_REQUEST['billtype_code']);
   $fortnight_lov=$dsbObj->getLovQry(137,$oldLovFilter,$newLovFilter);
   echo json_encode($fortnight_lov);
   exit(0);
}


?>