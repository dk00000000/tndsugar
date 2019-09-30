<?php
require_once('dashboard.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$lgs->lg->info("--START cane Rate Mast SERVER FILE--");
$qryPath = $_SESSION['QRYPATH'];
$qryPath = $qryPath."general/caneratemast.ini";
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
		$factory = $_REQUEST['factory'];
		$tn_detail = $_REQUEST['tn_detail'];
		$tnDataSet = $_REQUEST['tnDataSet'];

		//For Add Insert New Record              
		if($flag=="add")
		{
			/*Insert Into Main Table*/
			$lgs->lg->info("--I'm in Add--");
			$oldFilter = array();
			$newFilter = array();	

			array_push($oldFilter,':PCOMP_CODE',':PCR_SRNO',':PCR_SCODE',':CR_FNNO',':PCR_BTYPE',':PCR_FACTORY');
			array_push($newFilter,$comp_code,$crate_code,$season,$fortnight,$billtype,$factory);			

			$crateQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_HEADER',$oldFilter,$newFilter);
			$lgs->lg->info("--Header Qry--".$crateQry);
			//echo '*****************'.$crateQry;
		    $crateRes = $dsbObj->updateData($crateQry);

			$cnt = 0;
			for($i=0;$i<sizeof($tn_detail);$i++)
			{
				$oldTnFilter = array();
			    $newTnFilter = array();	
				$cnt = $cnt+1;
		
		array_push($oldTnFilter,':PCOMP_CODE',':PCRD_SRNO',':PCRD_RUNO',':CRD_CVARITY',':CRD_SECTION',':CRD_DIVISION',':CRD_FRKM',':CRD_TOKM',':CRD_RATE');
		array_push($newTnFilter,$comp_code,$crate_code,$cnt,$tn_detail[$i][1],$tn_detail[$i][3],$tn_detail[$i][5],$tn_detail[$i][7],$tn_detail[$i][8],$tn_detail[$i][9]);			

			$tnQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_DETAIL',$oldTnFilter,$newTnFilter);
			//echo '+++++++++++++'.$tnQry;
			$lgs->lg->info("--Detail Qry--".$tnQry);
		    $tnRes = $dsbObj->updateData($tnQry);
			}
			echo $tnRes; 
			exit(0);

		}//end if

        if($flag == 'update')
		{
		    $lgs->lg->info("--I'm in update--".json_encode($tn_detail));
	   	    $oldUpdFilter = array();
			$newUpdFilter = array();	

			array_push($oldUpdFilter,':PCOMP_CODE',':PCR_SRNO',':PCR_SCODE',':PCR_FNNO',':PCR_BTYPE',':PCR_FACTORY');
			array_push($newUpdFilter,$comp_code,$crate_code,$season,$fortnight,$billtype,$factory);			
			
			$crateUpdQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_HEADER',$oldUpdFilter,$newUpdFilter);
			
			$lgs->lg->info("--Header Upd Qry--".$crateUpdQry);
		    $crateUpdRes = $dsbObj->updateData($crateUpdQry);
		    $lgs->lg->info("--Header Upd Qry--".$crateUpdRes);

		  /*Update row of detail table*/
		  $lgs->lg->info("--Cane Rate Updated data--".json_encode($tnDataSet));	
		  //if($crateUpdRes==1){
		  if(sizeof($tnDataSet)>0)
		  {
		  		for($i=0;$i<sizeof($tnDataSet);$i++)
				{
					$oldUpdFilter = array();
				    $newUpdFilter = array();	

		array_push($oldUpdFilter,':PCOMP_CODE',':PCRD_SRNO',':PCRD_RUNO',':CRD_CVARITY',':CRD_SECTION',':CRD_DIVISION',':CRD_FRKM',':CRD_TOKM',':CRD_RATE');
		array_push($newUpdFilter,$comp_code,$crate_code,$tnDataSet[$i][0],$tnDataSet[$i][1],$tnDataSet[$i][3],$tnDataSet[$i][5],$tnDataSet[$i][7],$tnDataSet[$i][8],$tnDataSet[$i][9]);

			//print_r($oldUpdFilter);
			//print_r($newUpdFilter);
					$updQry = $qryObj->fetchQuery($qryPath,'Q001','UPDATE_DETAIL',$oldUpdFilter,$newUpdFilter);
					//echo '****************'.$updQry;
					$lgs->lg->info("--Cane Rate Update Qry--".$updQry);
					$updRes = $dsbObj->updateData($updQry);
					$lgs->lg->info("--Cane Rate Update Res--".$updRes);
					//echo $updRes;
				}//for    
		  }
		

		  /*Add new row in detail */
		  for($i=0;$i<sizeof($tn_detail);$i++)
			{
				$oldTnFilter = array();
			    $newTnFilter = array();	

			    $upd_flag = $tn_detail[$i][10];
			    if($upd_flag == 'ins')
			    {
	array_push($oldTnFilter,':PCOMP_CODE',':PCRD_SRNO',':PCRD_RUNO',':CRD_CVARITY',':CRD_SECTION',':CRD_DIVISION',':CRD_FRKM',':CRD_TOKM',':CRD_RATE');
	array_push($newTnFilter,$comp_code,$crate_code,$tn_detail[$i][0],$tn_detail[$i][1],$tn_detail[$i][3],$tn_detail[$i][5],$tn_detail[$i][7],$tn_detail[$i][8],$tn_detail[$i][9]);

					$tnQry = $qryObj->fetchQuery($qryPath,'Q001','INSERT_DETAIL',$oldTnFilter,$newTnFilter);
				    $lgs->lg->info("--Cane Rate Insert Qry--".$tnQry);
				    $tnRes = $dsbObj->updateData($tnQry);
				    $lgs->lg->info("--Cane Rate Insert Res--".$tnRes);
				    //echo $tnRes;
			    }//inner if
			}//for

			/*Delete row from detail table*/
			$diffArr = array();
			for($i=0;$i<sizeof($tn_detail);$i++){
				array_push($diffArr,$tn_detail[$i][0]);
			}

			$deleteArr=array_diff($_SESSION['oldruno'],$diffArr);
			/*echo 'delete';
			print_r($deleteArr);*/
			if(sizeof($deleteArr)>0)
			{
				foreach($deleteArr as $delarr)
				{
					$oldDelFilter = array(':PCOMP_CODE',':PCR_SRNO',':PCRD_RUNO');
					$newDelFilter = array($_SESSION['COMP_CODE'],$crate_code,$delarr['CRD_RUNO']);	
					$delQry = $qryObj->fetchQuery($qryPath,'Q001','DELETE_DETAIL',$oldDelFilter,$newDelFilter);
					$delRes = $dsbObj->updateData($delQry);
					//echo '**'.$delQry;
					$lgs->lg->trace("--Cane Rate Delete QUERY --:".$delQry);
				}
				$lgs->lg->info("--Cane Rate Delete--".$delRes);
				//echo $delRes;
			}  
			
		//}
		if($updRes == 1 || $tnRes == 1 || $delRes == 1)
		{
			$lgs->lg->info("--I'm in if--".$delRes);
			echo 1;
		}
		else
		{
			echo $crateUpdRes;
			$lgs->lg->info("--I'm in else--".$delRes);
		}

		//}//end main if
	}//update if	
}//end if


if($action_flag == 'fortnight')
{
   $oldLovFilter = array(':PCOMP_CODE',':PSEASON',':PBTYPE');
   $newLovFilter = array($_SESSION['COMP_CODE'],$_REQUEST['season'],$_REQUEST['billtype_code']);
   //$Res = $dsbObj->getLovQry(63,$oldLovFilter,$newLovFilter);	
   $fortnight_lov=$dsbObj->getLovQry(137,$oldLovFilter,$newLovFilter);
   echo json_encode($fortnight_lov);
   exit(0);
}

if($action_flag == 'divn')
{
   $oldLovFilter = array(':PCOMP_CODE',':PDV_CODE');
   $newLovFilter = array($_SESSION['COMP_CODE'],$_REQUEST['gat']);
   $divQry = $qryObj->fetchQuery($qryPath,'Q001','DIV_DROPDOWN',$oldLovFilter,$newLovFilter);
   $divRes = $dsbObj->getData($divQry);
   //$Res = $dsbObj->getLovQry(68,$oldLovFilter,$newLovFilter);	
   echo json_encode($divRes);
   exit(0);
}

if($action_flag == 'divi')
{
   $oldLovFilter = array(':PCOMP_CODE',':PDV_CODE');
   $newLovFilter = array($_SESSION['COMP_CODE'],$_REQUEST['gat']);
   $Res = $dsbObj->getLovQry(68,$oldLovFilter,$newLovFilter);	
   echo json_encode($Res);
   exit(0);
}

?>