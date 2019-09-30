
<?php
require('logs.php');	
require('query.php');
require('createtextfile.php');

session_start();
ini_set('max_execution_time', 10000);

//echo " Hello from 1 config file ";
//echo "<br> ";

/**
* ac_cred.inc.php: Secret Connection Credentials for a database class
* @package Oracle
*/
/**
* DB user name
*/
//define('SCHEMA', 'NIS');
/**
* DB Password.
*
* Note: In practice keep database credentials out of directories
* accessible to the web server.
*/
//define('PASSWORD', 'NIS');

/*
* DB connection identifier
*/
	//Looger initialization
  	$logs = new Logs();
  	//object of Query class to fetch query from ini file  
  	$qryObj = new Query();
  	//Path for ini file
	$logs->lg->trace("-- custArr in ac_cred--".json_encode($_SESSION['CUSTARR']));
	$logs->lg->trace("-- custArr in ac_cred--".json_encode($_SESSION['CUSTARR']["P024"]));
	if(DBNAME == 'mysql' )
	{
		$qrypath = "util/readquery/general/DBINFOMYSQL.ini";  
	}
	else
	{
		$qrypath = "util/readquery/general/DBINFO.ini";  
		//$qrypath = "util/readquery/general/".$_SESSION['CUSTARR']["P024"];  
	}

	if(isset($_GET['DB'])){
		$dbname = strtoupper($_GET['DB']);
		$_SESSION['DBNM'] = $dbname;
	}

	if($_SESSION['DBNM'] == 'JNSPL' || $_SESSION['DBNM'] == 'ASTORIA' || $_SESSION['DBNM'] == 'ASPL'){
		$dbid = $_SESSION['DBNM'];
	}
	else{
		$dbid = 'MAIN';
	}
	
  	$host = $qryObj->fetchQuery($qrypath,$dbid,'HOSTIP');
	$username = $qryObj->fetchQuery($qrypath,$dbid,'USERNAME');
	$password = $qryObj->fetchQuery($qrypath,$dbid,'PASSWORD');
  	$service_name = $qryObj->fetchQuery($qrypath,$dbid,'SERVICENAME');
	$_SESSION['SERVICENAME']=$service_name;
	$compCd = $qryObj->fetchQuery($qrypath,$dbid,'COMPCODE'); 	
	$server = $qryObj->fetchQuery($qrypath,$dbid,'SERVER'); // ADDED BY PADMRAJ, FOR SERVER TYPE, 23 AUG. 2014 	
	$_SESSION['SERVER']=$server;
	$localRepIp = $qryObj->fetchQuery($qrypath,$dbid,'ERPLOCALIP'); 
	$_SESSION['ERPLOCALIP']=$localRepIp;
	$webRepIp = $qryObj->fetchQuery($qrypath,$dbid,'ERPWEBIP');
	$_SESSION['ERPWEBIP']=$webRepIp;
	$repDesPath = $qryObj->fetchQuery($qrypath,$dbid,'REPDESTPATH');
	$_SESSION['REPDESTPATH']=$repDesPath ;
	$repSrvrNm = $qryObj->fetchQuery($qrypath,$dbid,'REPSERVERNM');
	$_SESSION['REPSERVERNM']=$repSrvrNm;
	
	
  	$logs->lg->trace("Connection Host  : ".$host);
  	$logs->lg->trace("Service Name  : ".$service_name);
  	$logs->lg->trace("Company Code  : ".$compCd);	
  	$logs->lg->trace("Server  : ".$server);	// ADDED BY PADMRAJ, FOR SERVER TYPE, 23 AUG. 2014 		
  	/*echo "Connection Host  : ".$host;
  	echo "Service Name  : ".$service_name;
  	echo "Company Code  : ".$compCd;	
  	echo "Server  : ".$server;*/

	define('DATABASE',"(DESCRIPTION =
				(ADDRESS_LIST =
					(ADDRESS = 
						(PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
		   			)
				(CONNECT_DATA =	(SERVER = DEDICATED)
				(SERVICE_NAME = ".$service_name.")
			)
			
  	  )");
	
	//$logs->lg->trace("Connection String  : ". $service_name);
/**
* DB character set for returned data
*/
define('CHARSET', 'UTF8');
/**
* Client Information text for DB tracing
*/
define('CLIENT_INFO', 'AnyCo Corp.');
define('COMP_CODE',$compCd); // ADDED BY PADMRAJ, 4 APRIL 2014
define('SERVER',$server); // ADDED BY PADMRAJ, FOR SERVER TYPE, 23 AUG. 2014 	
define( 'WP_MAX_MEMORY_LIMIT' , '1024M' );// ADDED BY PADMRAJ, FOR MAX. SIZE ISSUE, 14 OCT. 2014	
define('HOST',$host);
define('USERNAME',$username);
define('PASSWORD',$password);
/*
echo "Data Variable is <br>  ";
echo "SCHEMA : ".SCHEMA; echo "<br> ";
echo "PASSWORD : ".PASSWORD; echo "<br> ";
echo "DATABASE : ".DATABASE; echo "<br> ";
echo "CHARSET : ".CHARSET; echo "<br> ";
echo "SCHCHARSETEMA : ".CHARSET; echo "<br> ";
*/
/*class mySecure
{
	private $usrName;
	private $pssWrd;
	
	function setUsrNm($name)
	{
		$this->usrName=$name;
	}
	function getUsrNm()
	{
		return $this->userName;
	}
	function setPsswrd($psswrd)
	{
		$this->pssWrd=$psswrd;
	}
	function getPsswrd()
	{
		return $this->pssWrd;
	}
}*/

?>


