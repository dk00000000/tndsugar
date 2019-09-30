<?php 
	
	session_start();
	require_once('header_menu.php');
	
	$lgs = new Logs();					// TO PRINT LOGS
	$obj_Query = new Query();			// TO FETCH QUERY
	$obj_Dashboard = new Dashboard();	// TO EXECUTE QUERY
	$obj_ReadFile = new ReadFile();		// TO FETCH DATA
	
	$IsEmCode = 0;
		
	$lgs->lg->debug("===== START - BIRTHDAY FILE =====");
	
	
	$lgs->lg->trace("In Session Language : ".$_SESSION['LANG'].", Language Path : ".$_SESSION['LANGPATH']);
	$lgs->lg->trace("In Session Query Path : ".$_SESSION['QRYPATH']);
	$lgs->lg->trace("In Session Button Path : ".$_SESSION['BTNPATH']);
	
	$lang = $_SESSION['LANG'];
	$langPath = $_SESSION['LANGPATH'];
	$langPath = $langPath."general/birthday_".strtolower($lang).".txt";
	
	$btnPath=$_SESSION['BTNPATH'];
	$btnPath = $btnPath."_".$_SESSION['LANG'].".txt";
	
	//$qryPath = $_SESSION['QRYPATH'];
	//$lgs->lg->trace("Query Path OF SESSION : ".$qryPath);
//	$qryPath = $qryPath."general/birthday.ini";
	$qryPath = "util/readquery/general/birthday.ini";
	
	// BIRTHDAY QUERY CODE STARTS HERE
	
	$user_code ="'".$_SESSION['USER']."'";
	// GET COMP CODE FROM SESSION FOR COMP SEL CASE
	$comp_code ="'".$_SESSION['COMP_CODE']."'";

	$oldFilter = array(":PUSER_CODE");
	$newFilter = array($user_code);
	$userSetupQry = $obj_Query->fetchQuery($qryPath,'Q002','BDAY_USR_PROF_SELECT1',$oldFilter,$newFilter);
	$userSetupRes = $obj_Dashboard->getData($userSetupQry);
	
	
	//$_SESSION['COMP_CODE'] = $userSetupRes[0]['USER_LOCN']; 
	// ABOVE LINE COMMENTED BY PADMRAJ, ON 14 APR. 2014, BECAUSE WE GETTING 02(USER_LOCN) DUE TO THIS VAL., UNEXPECTED RES. GETS

	$lgs->lg->trace("Language Path : ".$langPath);
	$lgs->lg->trace("Query Path : ".$qryPath);
	$lgs->lg->trace("User Code : ".$user_code);
	$lgs->lg->trace("User Setup Query : ".$userSetupQry);
	$lgs->lg->trace("Set Comp Code in session : ".$_SESSION['COMP_CODE']);
	
	
	// COMPANY QUERY
	$oldFilter = array(":PUSER_CODE");
	$newFilter = array($user_code);
	$compQry = $obj_Query->fetchQuery($qryPath,'Q002','MULTI_COMP_QRY',$oldFilter,$newFilter);
	$lgs->lg->trace("Company Query : ".$compQry);
	$compQryRes = $obj_Dashboard->getData($compQry);
	$compResCnt=sizeof($compQryRes);
	$lgs->lg->trace("Company RES COUNT Ankush: ".$compResCnt);
	
	if($compResCnt != 2){
        $lgs->lg->trace("Comp Code in IF: ".json_encode($compQryRes[0]['COMP_CODE']));
	}
	// BIRTHDAY QUERY 
	$bdayQry = $obj_Query->fetchQuery($qryPath,'Q002','BDAY_SELECT');
	$bdayQryRes = $obj_Dashboard->getData($bdayQry);
	$lgs->lg->trace("Birthday Query : ".$bdayQry);
	$bdayCnt=sizeof($bdayQryRes);
	$lgs->lg->trace("Birthday RES COUNT Ankush: ".$bdayCnt);
		
	// SELF BIRTHDAY QUERY
	$sbdayQry = $obj_Query->fetchQuery($qryPath,'Q002','SELF_BDAY_QRY',$oldFilter,$newFilter);
	$sbdayQryRes = $obj_Dashboard->getData($sbdayQry);
	$ecode = $sbdayQryRes[0]['EM_CODE'];
	$lgs->lg->trace("Self Birthday Query : ".$sbdayQry);	
	
	$oldFilter = array(":PUSER_CODE");
	$newFilter = array($user_code);
	$userSetupQry = $obj_Query->fetchQuery($qryPath,'Q002','USR_PROF_SELECT',$oldFilter,$newFilter);
	$lgs->lg->trace("user prof QRY".$userSetupQry);
	$userSetupRes = $obj_Dashboard->getData($userSetupQry);
	$lgs->lg->trace("RES OF user profile QRY".json_encode($userSetupRes));	
	$askComp=$userSetupRes[0]['USR_ASKCOMP'];
		
	if($compResCnt == 1 && $bdayCnt == 0)
	{	
		$lgs->lg->trace("-- USER CODE IN IF --: ".$user_code);
		$obj_Dashboard->initDisp($qryPath,$user_code);
	}
	if(($askComp == 'N' || $askComp == "") && $bdayCnt == 0)
	{	
		$lgs->lg->trace("USER CODE IN IF: ".$user_code);
		$obj_Dashboard->initDisp($qryPath,$user_code);
	}
	if(!(empty($_POST['BT_SUBMIT'])))
	{
		$lgs->lg->trace("COMP OF SESSSION : ".$_SESSION['COMP_CODE']);
		$obj_Dashboard->initDisp($qryPath,$user_code);
	}
	// CODE TO SHOW NEWS, BY PADMRAJ, 21 NOV. 2015
	array_push($oldFilter,":PUSER_CAT",":PCOMP_CODE");
	array_push($newFilter,$_SESSION['USERINFO'][0]['USER_CAT'],$_SESSION['COMP_CODE']);
	$lgs->lg->trace("B.day - User Cat : ".$_SESSION['USERINFO'][0]['USER_CAT']);
	
	$isNewsQry = $obj_Query->fetchQuery($qryPath,'Q002','IS_NEWSQRY',$oldFilter,$newFilter);
	$rplcQry = $obj_Query->fetchQuery($qryPath,'Q002','RPLC_STR',$oldFilter,$newFilter);
	$lgs->lg->trace("Is News Query : ".$isNewsQry);
	$lgs->lg->trace("Is rplc Query : ".$rplcQry);
	
	// END OF CODE TO SHOW NEWS, BY PADMRAJ, 21 NOV. 2015
	
	if(isset($_GET['SELCOMP']))
	{
		if($askComp == 'Y')
		{
			$lgs->lg->trace("SEL COMP : ".$_GET['SELCOMP']);
			$_SESSION['COMP_CODE'] = $_GET['SELCOMP'];
		}
		// BY PADMRAJ, 21 NOV. 2015, FOR SHOW NEWS
		$isNewsQry = str_replace('RPLC_STR', '', $isNewsQry);			
		$lgs->lg->trace("In Else - ADMIN Case - Is News Query : ".$isNewsQry);			
		//header("Location:view_browse.php?menu_code=Z03171&firsttime=Y&call=frmlogin");		
		header("Location:dash.php");
		/*// COMMENTED AND ADDED BELOW IF CONDITION TO SHOW INIT DISP., BY AMIT, 20 NOV. 2015
		$lgs->lg->trace("COMP OF SESSSION : ".$_SESSION['COMP_CODE']);
		$obj_Dashboard->initDisp($qryPath,$user_code);*/
	}

	/*if($_SESSION['USERINFO'][0]['USER_CAT'] != 'U')
	{
		$isNewsQry = str_replace('RPLC_STR', $rplcQry, $isNewsQry);
		$lgs->lg->trace("In If - W/O ADMIN - Is News Query : ".$isNewsQry);
		header("location:view_browse.php?menu_code=Z03171&firsttime=Y&call=login");
	}*///Commented by Amit on 9/2/2016
	// END OF CODE TO SHOW NEWS, BY PADMRAJ, 20 NOV. 2015
	
	if(isset($_GET['FRMBRWS']))
	{
		$lgs->lg->trace("COMP OF SESSSION : ".$_SESSION['COMP_CODE']);
		$obj_Dashboard->initDisp($qryPath,$user_code);
	}
	
?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
<title>Birthday</title>
 <script>
  $(function() {
    $( "#selectable" ).selectable();
  });
  </script>
	<script> <!-- SCRIPT FOR ACCORDIAN, BY PADMRAJ ON 23 OCT. 2013 -->
	$(function() 
	 {
	 	$( "#accordion" ).accordion();
	  });
    </script>
	
	<script type="text/javascript">
	//jquery.noConflict();
		$(document).ready(function () {
			$('#BdayTableContainer').jtable({
				title: '<?php echo preg_replace("/\r?\n/", "", addslashes($obj_ReadFile->readData('HPYBDAY',$langPath))); ?>',
				actions: {
					listAction:'bdaytest.php?action=list'						               
				},			
				fields: {
					NUM: {
						//title: 'Number',
						key: true,
						list: false
					},
					COMPNAME: {
					title: '<?php echo preg_replace("/\r?\n/", "", addslashes($obj_ReadFile->readData('CMPNM',$langPath))); ?>',
						width: '90px',
						//create: false					                     					
					},
				   PLOCNAME: {
				   		title: '<?php echo preg_replace("/\r?\n/", "", addslashes($obj_ReadFile->readData('LCN',$langPath))); ?>',
						width: '50px',
					},
					DPTNAME: {
						title: '<?php echo preg_replace("/\r?\n/", "", addslashes($obj_ReadFile->readData('DPTNM',$langPath))); ?>',
						width: '20px',
					},
					EMPNAME: {
						title: '<?php echo preg_replace("/\r?\n/", "", addslashes($obj_ReadFile->readData('EMPNM',$langPath))); ?>',
						width: '80px',
					}
				}
			});
			//Load person list from server
				$('#BdayTableContainer').jtable('load');
		});
	</script>		
	
	</head>
	
	
	<!--PROTOTYPE CODE STARTS HERE-->
	
	<body topmargin="0" leftmargin="0" oncontextmenu="return false;">
	<form id="FRM_BIRTHDAY" name="FRM_BIRTHDAY" method="post">
	<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber1">
		<tr>
			<td width="100%">&nbsp;</td>
		</tr>
		<tr>
			<td width="100%">
			<div align="center">
	<!--			 <table border="0" cellpadding="2" cellspacing="1" style="border-collapse: collapse" bordercolor="#111111" id="AutoNumber2" width="50%">-->
				 <?php 
					$cnt = count($compQryRes);			 
					if($cnt >1 && $askComp == 'Y')
					{
					?>
					<div id="accordion"  style="width:700px;height:auto;">
								<h3 style="padding-top:5px; text-align:center; text-indent:inherit">
									<font face="<?php echo $font; ?>" size="<?php echo $fontsize; ?>">
									<?php
										echo $obj_ReadFile->readData('COMPSEL',$langPath); 
									?>
									</font>								</h3>
							<div>
						<table border="0" cellpadding="2" cellspacing="1" style="border-collapse: collapse" 
				bordercolor="#111111"  id="AutoNumber3">
									<?php 
									
									$sizeCmpRes=sizeof($compQryRes);
									$lgs->lg->trace("C".$sizeCmpRes);
									if($sizeCmpRes % 6 == 0)
									{
										$lmt1=($sizeCmpRes / 6);
										$lgs->lg->trace("lmt in if".$lmt1);
									}
									else
									{
										$lmt1=floor($sizeCmpRes / 6);
										$lgs->lg->trace("lmt1e".$lmt1);
										$lmt1=$lmt1+1;
										$lgs->lg->trace("lmt in else".$lmt1);
									}
									$i1=0;
									for($i=0;$i<$lmt1;$i++) 
									{
										$i1=$i1; 
										$j=$i1+6;
									?>
									<tr>
									<?php
									for($i1=$i1;$i1<$j;$i1++)
									{ 
										$url="images/company/logo/".$compQryRes[$i1]['COMP_LOGO'];
										$lgs->lg->trace("img url : ".$url);
										if($compQryRes[$i1]['COMP_NAME'] == "")
										{
											$disp="none";
										}
										else
										{
											$disp="block";
										}
									$URLVAR="birthday.php?SELCOMP=".$compQryRes[$i1]['COMP_CODE'];
									$did=$compQryRes[$i1]['COMP_CODE'];
									?>
		 <td style="display:<?php echo $disp;?>">
		 <div 	id="<?php echo $did; ?>" 
		 		class="ui-state-default"
				style="width:80px;height:60px;display:<?php echo $disp;?>;">		 
		 <a href="birthday.php?SELCOMP=<?PHP echo $compQryRes[$i1]['COMP_CODE'];?>">
		 <img src="<?php echo $url; ?>"  width="80px" height="60px"  />
		 </div><br />
		  <span style="width:80px;height:60px;display:<?php echo $disp;?>">
		  <font size="1"  ><b><?PHP echo $compQryRes[$i1]['COMP_NAME'];?></b></font></a>
		  </span>
		<!--  </div>-->
		 </td>
		 
		 <td style="width:20px">
		  
		 </td>
		
		
		 <?php } ?>
		 </tr>
	      <?php 
				}
		  ?>
		  </table>
		  </div>
		  </div>
		  <br />
		  	<?php
				  }//END OF if
				?>
			<!--</table>-->
			</div>			
			</td>
			</tr>
	 <tr>
		<td width="100%">
		<div align="center">
			<?php 
					$cnt1 = count($bdayQryRes);
					$bdayFLG=$_GET['bdayFLG'];
					$lgs->lg->trace("BDAY FLAG : ".$bdayFLG);
					if($bdayFLG == 'Y')
					{
						$disp="none";
						$lgs->lg->trace("BDAY FLAG IN IF : ".$bdayFLG);
					}
					else 
					{
						$disp="block";
						$lgs->lg->trace("BDAY FLAG IN ELSE : ".$bdayFLG);
					}
					if($cnt1 >=1)
					{
			?>  <table border="0" cellpadding="2" cellspacing="1" style="border-collapse: collapse" 
				bordercolor="#111111" width="60%" id="AutoNumber3">
					<tr>
						<td width="100%">
							<div id="BdayTableContainer" style="width:700px;height:250px; display:<?php echo $disp; ?>"></div>											
						</td>
					</tr>
				 </table>
				<?php } ?>	
				</div>					
		</td>
	 </tr>
		
	  <tr>
		<td width="100%">&nbsp;</td>
	  </tr>
	 
	<tr>
	  <td>
	  <div align="center">
	  <?PHP if($cnt <=1)
	  {
	  ?>
	  <input type="submit" name="BT_SUBMIT" id="BT_SUBMIT"
		  value="Next" />
		  <?php } ?>
		  </div>
	 </td>
	</tr>
</table>
	</form>
	</body>
	
	<!--END OF PROTOTYPE CODE -->
	</html>
