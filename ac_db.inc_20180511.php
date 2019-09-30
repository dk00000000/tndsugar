<?php
	/**
	* ac_db.inc.php: Database class using the PHP OCI8 extension
	* @package Oracle
	*/
	//namespace Oracle;
	require('ac_cred.inc.php');
	//require('encdecrypt.php');
	//global $global_user;
	//global $global_pwd;
	//ini_set('max_execution_time', 500); // ADDED
	ini_set('max_execution_time', 10000);
 	ini_set("memory_limit","5072M"); // set your memory limit in the case of memory problem
	
	
	
	/**
	* Oracle Database access methods
	* @package Oracle
	* @subpackage Db
	*/
	class Db 
	{

		/**
		* @var resource The connection resource
		* @access protected
		*/
//		protected $conn = null; // originally it's protected, modified by Padmraj on 28 Oct. 2013
		protected $conn = null;
		/**
		* @var resource The statement resource identifier
		* @access protected
		*/
		protected $stid = null;
		/**
		* @var integer The number of rows to prefetch with queries
		* @access protected
		*/
		protected $prefetch = 100;
		var $test;
		protected $user=null;
		protected $pwd=null;	
		
		 
		/**
		* Constructor opens a connection to the database
		* @param string $module Module text for End-to-End Application Tracing
		* @param string $cid Client Identifier for End-to-End Application Tracing
		*/
		/*function __construct($user, $pswd) 
		{
			//Looger initialization
  			$logs = new Logs();
  			 $logs->lg->trace("user Name in ac_db.inc file  : ". $user);
			 $logs->lg->trace("PWD in ac_db.inc file  : ". $pswd);
			 //$user=$user.strrev($user);
			//$this->conn = @oci_pconnect($this->user, $this->pwd, DATABASE, CHARSET); 
			if($user!=NULL && $pswd!=NULL)
			{
				$this->user=$user;
				$this->pwd=$pswd;
				$this->conn = @oci_pconnect($this->user, $this->pwd, DATABASE, CHARSET);
				//$this->conn = @oci_pconnect(SCHEMA, PASSWORD, DATABASE, CHARSET);
			}
			
			//echo "SCHEMA: ".SCHEMA;
			//echo "<br>----------conn in constructor - ".$this->conn;
			if (!$this->conn) 
			{
				$m = oci_error();
				throw new \Exception('Cannot connect to database: ' . $m['message']);
			}
			if($user!=NULL && $pswd!=NULL)
			{
				$global_user=$user;
				$global_pwd=$pswd;
				$_SESSION['PWD']=$pswd;
				$logs->lg->trace("global user Name in ac_db.inc file  : ". $global_user);
			 	$logs->lg->trace("global PWD in ac_db.inc file  : ". $global_pwd);
			}	
			// Record the "name" of the web user, the client info and the module.
			// These are used for end-to-end tracing in the DB.
				
			/* function just for test
			function put()
			{
				echo "<br>conn is ".$this->conn;
			}
			oci_set_client_info($this->conn, CLIENT_INFO);
			oci_set_module_name($this->conn, $module);
			oci_set_client_identifier($this->conn, $cid);
		}*/
		/**
		
		* Destructor closes the statement and connection
		*/
		
		/**
		* Run a SQL or PL/SQL statement
		*
		* Call like:
		* Db::execute("insert into mytab values (:c1, :c2)",
		* "Insert data", array(array(":c1", $c1, -1),
		* array(":c2", $c2, -1)))
		*
		* For returned bind values:
		* Db::execute("begin :r := myfunc(:p); end",
		* "Call func", array(array(":r", &$r, 20),
		* array(":p", $p, -1)))
		*
		* Note: this  not performs a commit.
		*
		* @param string $sql The statement to run
		* @param string $action Action text for End-to-End Application Tracing
		* @param array $bindvars Binds. An array of (bv_name, php_variable, length)
		*/

		public function execute($sql, $action, $bindvars=array()) 
		{
			
			$logs = new Logs();  			
			$logs->lg->trace("ac_db.inc - exe - un : ".$_SESSION['USER']);
			//$logs->lg->trace("bind var Array: ".json_encode($bindvars));
			//$logs->lg->trace("Procedure in ac_db: ".$sql);
			$outVar="";
			
			$this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8);
			$logs->lg->trace("ANKUSH CONNECTION CODE IN EXECUTE METHOD ".$this->conn); 
			///$this->conn = @oci_pconnect(SCHEMA, PASSWORD, DATABASE, CHARSET); 
			// Above line Added by Padmraj on 30-10-2013, because we get warning that 1 resource null etc.
			//echo"<br>------------------in execute function ".$this->conn;
			$this->stid = oci_parse($this->conn, $sql);
			/*if($this->stid)
			{
				$logs->lg->trace("-- in if stid ");
			}
			else
			{
				$logs->lg->trace("-- in else stid ");
			}*/
			if ($this->prefetch >= 0) 
			{
				//$logs->lg->trace(" in if prefetch ");
				oci_set_prefetch($this->stid, $this->prefetch);
			}	
			foreach ($bindvars as $bv) 
			{
				//$logs->lg->trace(" in foreach ");			
				// oci_bind_by_name(resource, bv_name, php_variable, length)
				oci_bind_by_name($this->stid, $bv[0], $bv[1], $bv[2]);
			}
			   
			oci_bind_by_name($this->stid,":outvar", $outVar, 10000);
           
			oci_set_action($this->conn, $action);
            //$r=oci_execute($this->stid); // will  auto commit
			$r=oci_execute($this->stid); // will notauto commit

			//$logs->lg->trace("-- size of array  ".gettype($r));
			//$rs = implode(", ",$r);
			//$logs->lg->trace("implode ".$rs);
			/*if($r)
			{
				$logs->lg->trace("-- size of if - succ");
			}
			else
			{
				$logs->lg->trace("-- size of else - fail ");			
			}*/
			/*if($outVar!="")
			{
				return $outVar;
			}
			else
			{
				return $r;
			}*/
			
			if(!$r)
			{
				$logs->lg->trace("Errors from SQL execute: ".json_encode(oci_error($this->stid )));
				//return 	oci_error($this->stid );	
				$_SESSION['ERRORS']="";		
				$_SESSION['ERRORS']=oci_error($this->stid);	
			}

			return $r;
		} // end of execute function 
		
		/*
			executeAtStrt() method use when $_SESSION['USER'] and $_SESSION['IDTTY'] is not set
			that time call these method.
			Added by Padmraj, On 25 Feb. 2014
		*/
		public function executeAtStrt($sql, $action, $bindvars = array()) 
		{
			//echo "<br>executeAtStrt method ";
			$logs = new Logs();  
			//$logs->lg->trace("Connecting with NIS");
			//$logs->lg->trace("in if: ".$_SESSION['USER'].' '.$_SESSION['IDTTY']);			
			//$objEncDec=new encdecrypt();   //object to encrypt & Decrypt Data
			//$as_Pwd=$objEncDec->decrypt($_SESSION['IDTTY'],$_SESSION['KEY']);
			//$logs->lg->trace("Password in ac_db_inc.php before decode : ". $_SESSION['IDTTY']);
			//$as_Pwd=trim(base64_encode($_SESSION['IDTTY']));
			 //$logs->lg->trace("Password in ac_db_inc.php after decode : ". $as_Pwd);
			$this->conn = @oci_pconnect("NIS", "NIS", DATABASE, AL32UTF8); 
			$logs->lg->trace("ANKUSH CONNECTION CODE IN EXECUTE AT STRT ".$this->conn); 
		    ///$this->conn = @oci_pconnect(SCHEMA, PASSWORD, DATABASE, CHARSET); 
			// Above line Added by Padmraj on 30-10-2013, because we get warning that 1 resource null etc.
			//echo"<br>------------------in execute function ".$this->conn;
			$this->stid = oci_parse($this->conn, $sql);
			if ($this->prefetch >= 0) 
			{
				oci_set_prefetch($this->stid, $this->prefetch);
			}	
			foreach ($bindvars as $bv) 
			{
				// oci_bind_by_name(resource, bv_name, php_variable, length)
				oci_bind_by_name($this->stid, $bv[0], $bv[1], $bv[2]);
			}
			oci_set_action($this->conn, $action);
            //$r=oci_execute($this->stid); // will  auto commit
			$r=oci_execute($this->stid, OCI_NO_AUTO_COMMIT); // will notauto commit
			
			return $r;
		} // end of executeAtStrt function 
		
		
		/**
		* Run a SQL or PL/SQL statement
		*
		* Call like:
		* Db::execute("insert into mytab values (:c1, :c2)",
		* "Insert data", array(array(":c1", $c1, -1),
		* array(":c2", $c2, -1)))
		*
		* For returned bind values:
		* Db::execute("begin :r := myfunc(:p); end",
		* "Call func", array(array(":r", &$r, 20),
		* array(":p", $p, -1)))
		*
		* Note: this performs a commit.
		*
		* @param string $sql The statement to run
		* @param string $action Action text for End-to-End Application Tracing
		* @param array $bindvars Binds. An array of (bv_name, php_variable, length)
		*/
		public function execCommit($sql, $action, $bindvars = array()) 
		{
			$logs = new Logs();
			//$objEncDec=new encdecrypt();   //object to encrypt & Decrypt Data
			//$as_Pwd=$objEncDec->decrypt($_SESSION['IDTTY'],$_SESSION['KEY']);
			//$as_Pwd=trim(base64_encode($_SESSION['IDTTY']));
			// BY PADMRAJ, 23 APR 2015, BECAUSE WHEN USER NOT LOGIN, THAT IT'S USE
			//$logs->lg->trace("Connection String : ".$this->conn);
			if((isset($_SESSION['USER'])) && (isset($_SESSION['IDTTY'])))
			{
				//$logs->lg->trace("in if: ".$_SESSION['USER'].' '.$_SESSION['IDTTY']);
				$this->conn = @oci_pconnect($_SESSION['USER'],$_SESSION['IDTTY'] , DATABASE, AL32UTF8); 
			}
			else
			{
				//$logs->lg->trace("in else: ".$_SESSION['USER'].' '.$_SESSION['IDTTY']);
				$this->conn = @oci_pconnect("NIS", "NIS", DATABASE, AL32UTF8); 
			}
			$logs->lg->trace("ANKUSH CONNECTION CODE EXECUTE COMMIT ".$this->conn); 
			//$this->conn = @oci_pconnect($_SESSION['USER'],$_SESSION['IDTTY'] , DATABASE, CHARSET); // BY PADMRAJ, 23 APR 2015, USE IN ABOVE IF
			//$logs->lg->trace("IN ac_db.inc.php - Step 1 : user - ".$_SESSION['USER']." idt : ".$_SESSION['IDTTY']);
			//$this->conn = @oci_pconnect(SCHEMA, PASSWORD, DATABASE, CHARSET); 
			// Above line Added by Padmraj on 30-10-2013, because we get warning that 1 resource null etc.
			//echo"<br>------------------in execute function ".$this->conn;
			$this->stid = oci_parse($this->conn, $sql);
			if ($this->prefetch >= 0) 
			{
				oci_set_prefetch($this->stid, $this->prefetch);
			}	
			foreach ($bindvars as $bv) 
			{
				// oci_bind_by_name(resource, bv_name, php_variable, length)
				oci_bind_by_name($this->stid, $bv[0], $bv[1], $bv[2]);
			}
			oci_set_action($this->conn, $action);
            
			$r=oci_execute($this->stid); 
			if (!$r) 
			{
				$e = oci_error($this->stid);
				$message =explode(": ", $e['message'])[1];
				$code =$e['code'];
				$logs->lg->trace("MESSAGE ".$message." CODE ".$code);
				return $code.':'.$message;
				//return json_encode($e);
				/*$sql = "SELECT MESSAGE_NO, MESSAGE_TEXT FROM NIS.NSCMESSAGES
					 WHERE MESSAGE_NO = '".$code."'";
				$res = $this->execFetchAll($sql);
				if(sizeof($res) > 0)
				{
					return $res[0]['MESSAGE_NO']." ".$res[0]['MESSAGE_TEXT'];
				}
				else
				{
					$sql = "INSERT INTO NIS.NSCMESSAGES (MESSAGE_NO, MESSAGE_TEXT)
						    VALUES ('".$code."', '".$message."')";
					$res = $this->execCommit($sql, "Update Data");
					return $message;
				}*/
			} // will auto commit
			
			//@oci_rollback($this->conn);
			$r = oci_commit($this->conn);
			if(!$r)
			{
				$logs->lg->trace("Errors from SQL in execCommit: ".json_encode(oci_error($this->stid )));	
				$_SESSION['ERRORS']="";			
				$_SESSION['ERRORS']=oci_error($this->stid);	
			}
			
			return $r;
		} // end of execute function
		
		/**
		* Run a query and return all rows.
		*
		* @param string $sql A query to run and return all rows
		* @param string $action Action text for End-to-End Application Tracing
		* @param array $bindvars Binds. An array of (bv_name, php_variable, length)
		* @return array An array of rows
		*/
		public function execFetchAll($sql, $action, $bindvars = array())
		 {
			$logs = new Logs();  
			$res=array();	
			//$logs->lg->trace("Connection String : ".$this->conn);
			//$logs->lg->trace("in if: ".$_SESSION['USER'].' '.$_SESSION['IDTTY']);
			//$this->stid = oci_parse($this->conn, $sql);
			if((isset($_SESSION['USER'])) && (isset($_SESSION['IDTTY'])))
			{
				$logs->lg->trace("Session check variables  ");
				$this->execute($sql, $action, $bindvars);
			}
			else
			{
				$this->executeAtStrt($sql, $action, $bindvars);
			}
			if($action=="PROC")
			{
				$this->execute($sql, $action, $bindvars);
			}
			try
			{
				//oci_fetch_all($this->stid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
				if(oci_fetch_all($this->stid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW))
				{
					$logs->lg->trace("-- execFetchAll - true  ");
					//$logs->lg->trace("-- execFetchAll - Data :  ".json_encode($res));
				}
				else
				{
					//$logs->lg->trace("-- execFetchAll - false  ");
					/*$this->stid = oci_parse($this->conn, $sql);
					$r=oci_execute($this->stid, OCI_NO_AUTO_COMMIT); 
					oci_fetch_all($this->stid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);*/
				}
				//oci_fetch_all($this->stid, $res);
				//$this->stid = null; // free the statement resource	
				
				/*$logs->lg->trace("-- execFetchAll - stid  ".$this->stid);
				$logs->lg->trace("-- execFetchAll - res is  ".gettype($res));	
				$logs->lg->trace("-- execFetchAll - res is  ".sizeof($res));		*/
			
				
			}
			catch(Exception $e)
			{
				$logs->lg->trace("-- execFetchAll - in catch exception is  ".$e->getMessage());		
			}
			
			//oci_free_statement($this->stid);
			//oci_close($this->conn);
			return($res);
		}
		
		/**
		* Run a query and return all rows.
		*
		* @param string $sql A query to run and return all rows
		* @param string $action Action text for End-to-End Application Tracing
		* @param array $bindvars Binds. An array of (bv_name, php_variable, length)
		* @return Satatement
		* Created by Amit Chadhari
		*/
		public function execFetchStatement($sql, $action, $bindvars = array())
		 {
		 	//$objEncDec=new encdecrypt();   //object to encrypt & Decrypt Data
			//$as_Pwd=$objEncDec->decrypt($_SESSION['IDTTY'],$_SESSION['KEY']);
			//$as_Pwd=trim(base64_encode($_SESSION['IDTTY']));
		 	$this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8); 
		 	//$this->conn = @oci_pconnect(SCHEMA, PASSWORD, DATABASE, CHARSET); 
		 	$this->stid = oci_parse($this->conn, $sql);
			$this->execute($sql, $action, $bindvars);
			//oci_fetch_all($this->stid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
			//$this->stid = null; // free the statement resource
			return($this->stid);
		}
		/*
		*
		*Function to check User Name & Password 
		*/
		public function validateLogin($userName,$userPwd)
		 {
		 	$logs = new Logs();
			$logs->lg->trace("--- In validateLogin method ---");
			//$logs->lg->trace("In validateLogin - username : ".$userName." password : ".$userPwd);
						
			$this->conn = @oci_pconnect($userName, $userPwd, DATABASE, AL32UTF8);
			//define('SCHEMA', $userName);
			//define('PASSWORD',$userPwd);
			
			
			//$this->user= $userName;
			$logs->lg->trace("User Name in ac_db.inc.php  : ".$userName);
			//$this->pwd=$userPwd;
		 	if($this->conn!=false)
			{
			   $lb_Valid=true;
			}
			else
			{
				$lb_Valid=false;
			}
			$logs->lg->trace("Return val - in ac_db.inc.php  : ".$lb_Valid);
			return($lb_Valid);
		}
		
		//Creted By Amit for proc executiuon
		public function executeProc($sql, $action, $bindvars) 
		{
			//echo "<br>===== Hello from execute funtion from ac_db.inc.php file ";
			$logs = new Logs();  			
			$logs->lg->trace("ac_db.inc - exe - un : ".$_SESSION['USER']);
			$logs->lg->trace("bind var Array: ".json_encode($bindvars));
			//$logs->lg->trace("Procedure in ac_db: ".$sql);
			$outVar="";
			$outVar1="";
			$outVar2="";
			
			
			$this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8); 
			
			$this->stid = oci_parse($this->conn, $sql);
		   $logs->lg->trace("ANKUSH CONNECTION CODE IN EXECUTE PROCUDURE ".$this->conn); 			
			/*oci_bind_by_name($this->stid, ":outvar", $outVar, 500);
			oci_bind_by_name($this->stid, ":outvar1", $outVar1, 500);
			oci_bind_by_name($this->stid, ":outvar2", $outVar2, 500);*/
			//$r=oci_execute($this->stid, OCI_NO_AUTO_COMMIT); // will notauto commit
			
			
			foreach ($bindvars as $key => $val)
			 {			
				// oci_bind_by_name($stid, $key, $val) does not work
				// because it binds each placeholder to the same location: $val
				// instead use the actual location of the data: $ba[$key]
				oci_bind_by_name($this->stid, $key,  $bindvars[$key],5000);
			}
			
			$r=oci_execute($this->stid); // will notauto commit
			
			/*$logs->lg->trace("Binded Value of outvar : ".$outVar);
			$logs->lg->trace("Binded Value of outvar1 : ".$outVar1);
			$logs->lg->trace("Binded Value of outvar2 : ".$outvar2);*/
			
			$logs->lg->trace("Binded Array in ac_db.inc : ".json_encode($bindvars));
			
			//$bindvars = array(':outvar' => '', ':outvar1' => '',':outvar2');
			
			
			
			/*if($outVar!="")
			{
				return $outVar;	
			}*/
			if(!$r)
			{
				$logs->lg->trace("Errors from SQL in execCommit: ".json_encode(oci_error($this->stid )));	
				$_SESSION['ERRORS']="";			
				$_SESSION['ERRORS']=oci_error($this->stid);	
			}
			if($outVar1!="" || $outVar2!=""||$outVar!="")
			{
				$tmp=array($outVar,$outVar1,$outVar2);
				return $tmp;	
			}
			if($bindvars!=NULL)
			{
				return $bindvars;
			}
				
		} // end of executeProc function


       //Creted By Ankush for proc executiuon
		public function executeOutProc($sql,$bindvars) 
		{
			

			$logs = new Logs();  
			$logs->lg->trace("In Out Parameter Procedure :".$sql."BindVar".$bindvars);
			//$logs->lg->trace("Procedure in ac_db: ".$sql);
			$aOutPara=$bindvars;
			
			$this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8); 
			$logs->lg->trace("ANKUSH CONNECTION CODE EXECUTE OUT PROCUDURE".$this->conn); 
			//$logs->lg->trace("Connection String : ".$this->conn);
			$this->stid = oci_parse($this->conn, $sql);
	        oci_bind_by_name($this->stid, ':aOutPara', $aOutPara, 5000);
			
			$r=oci_execute($this->stid); // will notauto commit
			
			$logs->lg->trace("Out Parameter Result : ".$aOutPara);
			
			if(!$r)
			{
				$logs->lg->trace("Errors from SQL in execCommit: ".json_encode(oci_error($this->stid )));	
				$_SESSION['ERRORS']="";			
				$e=oci_error($this->stid);
				
				return "Problem in Executing Procedure";	
			}else{
				return $aOutPara;
			}
			
				
		} // end of executeProc function

		
		//created By Amit Chuadhari for the ref cusrsor execution
		public function executeRefCursor($sql, $action, $bindvars=array()) 
		{
			//echo "<br>===== Hello from execute funtion from ac_db.inc.php file ";
			$logs = new Logs();  			
			$logs->lg->trace("ac_db.inc - exe - un : ".$_SESSION['USER']);
			$logs->lg->trace("bind var Array: ".json_encode($bindvars));
			//$logs->lg->trace("Ref Cursor in ac_db: ".$sql);
			$outVar="";
			$outVar1="";
			$outVar2="";
			
			
			$this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8); 
			$this->stid = oci_parse($this->conn, $sql);
			// Create a new cursor resource
			$curr_entries = oci_new_cursor($this->conn);
			 //oci_bind_by_name($this->stid, $rcname, $curr_entries, -1, OCI_B_CURSOR);
			// oci_bind_by_name($this->stid, ":outvar", $outVar, 100);
			 oci_bind_by_name($this->stid, ":outvar",$curr_entries, -1, OCI_B_CURSOR);
        		/*foreach ($otherbindvars as $bv)
				 {
            // oci_bind_by_name(resource, bv_name, php_variable, length)
            oci_bind_by_name($this->stid, $bv[0], $bv[1], $bv[2]);
        	}*/
			oci_set_action($this->conn, $action);
			
			oci_execute($this->stid);
			oci_execute($curr_entries); // run the ref cursor as if it were a statement id
			
			oci_fetch_all($curr_entries, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
			$this->stid = null;
			//$logs->lg->trace("Ref Cursor result in DB: ".json_encode($res));
			return($res);

						
			/*if($outVar!="")
			{
				return $outVar;	
			}
			if($outVar1!="" || $outVar2!="")
			{
				$tmp=array($outVar1,$outVar2);
				return $tmp;	
			}*/
				
		} // end of execute function 
		
		public function executeData($sql)
		{
			$tmp=array();
			$this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8); 
			$this->stid = oci_parse($this->conn, $sql);
			oci_execute($this->stid);
			oci_fetch_all($this->stid, $tmp, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
			//$tmp=oci_fetch_array($s, OCI_ASSOC);
			return $tmp;
		}
		
		
		//function to rllback changes
		function rollbackChng()
		{
			oci_rollback($this->conn);
		}
		
		//if(($this->dbName) == 'mysql')
		//{
			/*public function executeMysql($sqlQry)
			{
				$this->conn = mysql_pconnect($this->host, $this->username, $this->password);
				mysql_select_db("$this->database")or die("cannot select DB");
				$queryStmt = mysql_query($sqlQry,$this->conn);
				$resultArr = mysql_fetch_array($queryStmt);
				return	$resultArr;
			}
			
			public function execCommitMysql($sqlQry)
			{
				$this->conn = mysql_pconnect($this->host, $this->username, $this->password);
				mysql_select_db("$this->database")or die("cannot select DB");
				$queryStmt = mysql_query($sqlQry,$this->conn);
				$resultArr = mysql_fetch_array($queryStmt);
				if(! $resultArr ) 
				{
					  // die('Could not enter data: ' . mysql_error());
					  $_SESSION['ERROR']=mysql_error();
				}
				return	$resultArr;
			}
		*/
		//}
		/**
		* Run a query and return all rows.
		*
		* @param string $sql A query to run and return all rows
		* @param string $action Action text for End-to-End Application Tracing
		* @param array $bindvars Binds. An array of (bv_name, php_variable, length)
		* @return array An array of rows
		*/
		public function execFetchAllRowCol($sql, $action, $bindvars = array())
		 {
			$logs = new Logs();  
			$res=array();	
			$this->stid = oci_parse($this->conn, $sql);
			if((isset($_SESSION['USER'])) && (isset($_SESSION['IDTTY'])))
			{
				$logs->lg->trace("Session check variables  ");
				$this->execute($sql, $action, $bindvars);
			}
			else
			{
				$this->executeAtStrt($sql, $action, $bindvars);
			}
			if($action=="PROC")
			{
				$this->execute($sql, $action, $bindvars);
			}
			try
			{
				//oci_fetch_all($this->stid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
				if(oci_fetch_all($this->stid, $res, 0, -1))
				{
					$logs->lg->trace("-- execFetchAllRowCol - true  ");
					//$logs->lg->trace("-- execFetchAll - Data :  ".json_encode($res));
				}
				else
				{
					//$logs->lg->trace("-- execFetchAll - false  ");
					/*$this->stid = oci_parse($this->conn, $sql);
					$r=oci_execute($this->stid, OCI_NO_AUTO_COMMIT); 
					oci_fetch_all($this->stid, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);*/
				}
				//oci_fetch_all($this->stid, $res);
				//$this->stid = null; // free the statement resource	
				
				/*$logs->lg->trace("-- execFetchAll - stid  ".$this->stid);
				$logs->lg->trace("-- execFetchAll - res is  ".gettype($res));	
				$logs->lg->trace("-- execFetchAll - res is  ".sizeof($res));		*/
			
				
			}
			catch(Exception $e)
			{
				$logs->lg->trace("-- execFetchAll - in catch exception is  ".$e->getMessage());		
			}
			
			//oci_free_statement($this->stid);
			//oci_close($this->conn);
			return($res);
		}
		
	} // end of class Db

	//echo "<br>Hello from 2 config file ";
	//echo "<br> ";
	//echo "<br>before Object Creation ";
	//$obj = new Db("Test_Db", "Chris"); ///------
	//echo "<br>After Object Creation ";
	//$obj->put();

	/*class sample
	{
		var $sone = 7;
		public function sput()
		{
			echo "from sput - ".$this->sone;
		}	
	}
	$sobj = new sample();
	$sobj->sput();*/
?>

