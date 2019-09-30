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
	//print_r($compQryRes);
	$compResCnt=sizeof($compQryRes);
	$lgs->lg->trace("Company RES COUNT: ".$compResCnt);

	
	// BIRTHDAY QUERY 
	$bdayQry = $obj_Query->fetchQuery($qryPath,'Q002','BDAY_SELECT');
	$bdayQryRes = $obj_Dashboard->getData($bdayQry);
	$lgs->lg->trace("Birthday Query : ".$bdayQry);
	$bdayCnt=sizeof($bdayQryRes);
	$lgs->lg->trace("Birthday Count : ".$bdayCnt);
	
	//IF BIRTHDAY NOT AND ONLY ONE COMPONY IS THERE
    if($compResCnt ==1 && $bdayCnt == 0){
		$comp_code=$compQryRes[0]['COMP_CODE'];
		$_SESSION['COMP_CODE'] = $comp_code;
		//$lgs->lg->trace("Company RES COUNT: ".json_encode($compQryRes));
		$lgs->lg->trace("COMP CODE IN IF: ".$compQryRes[0]['COMP_CODE']);
		header("Location:dash.php");	
	}

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
		$lgs->lg->trace("-- USER CODE IN IF of  --: ".$user_code);
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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Welcome</title>

<link href="css/wcss.css" rel="stylesheet">
<link href="css/flexslider.css" rel="stylesheet">

</head>

<body class="InnerPage" oncontextmenu="return false;">

<div class="container">


    <div class="row CompanySelection">
        <div class="col-md-12">
        	<h2>Company Selection</h2>
			
          
          	<section class="slider CompanyDiv">
					 
                <div class="flexslider carousel">
				 <ul class="slides">
				
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
									
					<li>		
					<article>
						<a href="birthday.php?SELCOMP=<?PHP echo $compQryRes[$i1]['COMP_CODE'];?>">
						<img src="<?php echo $url; ?>"  style="width:80px;height:60px;" alt=""/>
						<font size="1"  ><b><?PHP echo $compQryRes[$i1]['COMP_NAME'];?></b></font></a>
					</article>
					</li>
					<?php } }?> 
                       
                  </ul>
				
                </div>
       
     	 </section>
            
        </div>
    </div>

<?php if($bdayCnt != 0) { ?>
<div class="BirtdayAnniversaryDiv">
    <h2 class="h2 color-white">Best Wishes</h2>
	
	<div class="MyTble">
    	<div class="MyTblRow">
     
            <div class="MyTblCol PurpulColorBk brdlRght tblwd1 imgleftDiv">
            <img src="images/img-bouquet.jpg" alt=""/>
            </div><!-- Extra Div For Flower IMAGE REMOVE THIS DIV IF THERE IS NO IMAGE-->
            
       	  <div class="MyTblCol GreeColorBk">
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
			?> 
			<?php
			$bdayQry = $obj_Query->fetchQuery($qryPath,'Q002','BDAY_SELECT');
		$bdayQryRes = $obj_Dashboard->getData($bdayQry);
		
		$lgs->lg->trace("User Code : ".$user_code);
		$lgs->lg->trace("Query Path : ".$qryPath);
		$lgs->lg->trace("B.day Query : ".$bdayQry);
		// END OF BIRTHDAY QUERY 
		
		$oldFilter = array(":PUSER_CODE");
		$newFilter = array($user_code);
		
		// SELF BIRTHDAY QUERY AND IT'S EXECUTION
		$sbdayQry = $obj_Query->fetchQuery($qryPath,'Q002','SELF_BDAY_QRY',$oldFilter,$newFilter);
		$sbdayQryRes = $obj_Dashboard->getData($sbdayQry);
		$ecode = $sbdayQryRes[0]['EM_CODE'];
		
		$lgs->lg->trace("Self Birthday Query : ".$sbdayQry);
		$lgs->lg->trace("Self Birthday Query - ecode : ".$ecode);
		// END OF SELF BIRTHDAY QUERY
				
		for ($i =0; $i < count($bdayQryRes); $i++)
		{
			if($ecode == $bdayQryRes[$i]['EMCODE'])
			{
				$isUserBday = true;
			}	
		}
		if($isUserBday)
		{   
			$bdayQry = $bdayQry." AND E.EM_CODE NOT IN(".$ecode.")";
		}
		
		$bdayQryRes = $obj_Dashboard->getData($bdayQry);
		
		// GETTING RECORDS (LISTACTION)
		
				$rows = array();
				foreach($bdayQryRes as $row) 
				{
					$row['NUM']=(int)$row['NUM'];
					$rows[] = $row;
				}
				// RETURN RESULT TO JTABLE
				$jTableResult = array();
				$jTableResult['Result'] = "OK";
				$jTableResult['Records'] = $rows;
				//print stripslashes(json_encode($jTableResult));
			//print_r($rows);
			//echo "data".sizeof($rows);
		
			?>
			<?php
			for($i=0;$i<sizeof($rows);$i++)
			{
			?>
    <div class="row">
    	<div class="col-lg-3 col-md-3 col-sm-6">
    				<div class="namePersonBx" >
					
						<?php
						if($rows[$i]['COMM_FILE']!=''){
						?>
						<img src="<?php echo $rows[$i]['COMM_FILE']; ?>" alt="">
						<?php
						}
						else {
						 if($rows[$i]['EM_SEX']=='M') {?>
						<p>
							<img src="images/img-person2.jpg" alt="">
						</p>
						<?php } 
						else{
						 ?>
						 <p>
							<img src="images/img-person.jpg" alt="">
						</p>
						 <?php }} ?>
						<div class="caption">
							<div class="blur"></div>
							<div class="caption-text">
								<h3>
		
		<?PHP echo $rows[$i]['EMPNAME'];?>
		
							</h3>
								<p>
				<?PHP echo $rows[$i]['COMPNAME'];?>
			<br/>
				<?PHP echo $rows[$i]['DPTNAME'];?>
			<br/>
				<?PHP echo $rows[$i]['PLOCNAME'];?>
								</p>
								
							</div>
						</div>
					</div>
				
	    </div>
                

<?php } }?>	
            
          </div>        
        </div>
</div>
 <? } ?>
</div>
</div>

<? require_once("footer.php"); ?>

<? //require_once("jsfiles.php"); ?>

<script src="js/jquery.flexslider-min.js"></script> 

<!-- Script to Activate the Carousel --> 
<script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
	
$(function() {
  $('a[href*="#AboutGalaxly"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});


   $(window).load(function(){
      $('.flexslider').flexslider({
        animation: "slide",
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 5,
        minItems: 1,
        maxItems: 5,
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
	
	
	(function() {
 
  // store the slider in a local variable
  var $window = $(window),
      flexslider = { vars:{} };
 
  // tiny helper function to add breakpoints
  function getGridSize() {
    return (window.innerWidth < 600) ? 2 :
           (window.innerWidth < 900) ? 3 : 4;
  }
 
  $(function() {
    SyntaxHighlighter.all();
  });
 
  $window.load(function() {
    $('.flexslider').flexslider({
      animation: "slide",
      animationLoop: false,
      itemWidth: 210,
        itemMargin: 5,
        minItems: 1,
        maxItems: 5,
      minItems: getGridSize(), // use function to pull in initial value
      maxItems: getGridSize() // use function to pull in initial value
    });
  });
 
  // check grid size on resize event
  $window.resize(function() {
    var gridSize = getGridSize();
 
    flexslider.vars.minItems = gridSize;
    flexslider.vars.maxItems = gridSize;
  });
}());

    </script>
</body>
</html>
