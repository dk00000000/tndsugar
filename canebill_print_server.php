<?php
require_once('dashboard.php');

$lgs = new Logs();
$qryObj = new Query();
$dsbObj = new Dashboard();

$qryPath = $_SESSION['QRYPATH'];
$qryPath = $qryPath."general/canebill_print.ini";
$comp_code = $_SESSION['COMP_CODE'];
$user_code = $_SESSION['USER'];
$action_flag = $_REQUEST['action'];


if($action_flag =='farmer_rpt')
{
	$oldLovFilter = array($oldLovFilter,":PCOMP_CODE",":PSEARCH");
    $newLovFilter = array($newLovFilter,$_SESSION['COMP_CODE'],"'%".$_REQUEST['type_string']."%'");
    $updQry = $qryObj->fetchQuery($qryPath,'Q001','REPORTINPUT',$oldLovFilter,$newLovFilter);
	$queryRes = $dsbObj->getData($updQry);
	$lgs->lg->trace("--FARMER REPORT GET QUERY-:".$updQry);
	$lgs->lg->trace("--FAMER REPORT RESULT--:".json_encode($queryRes));
?>
	<ul class="farmer-list">
		<?php
		foreach($queryRes as $row) {
		?>
		<li onClick="Farmer('<?php echo $row["PRT_CODE"]; ?>')"><?php echo $row["PRT_CODE"]; ?></li>
		<?php } ?>
	</ul>
<?php 
} 

?>