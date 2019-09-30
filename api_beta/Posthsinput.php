<?php
session_start();
header('Content-type: application/json');
require_once( "../dashboard.php" );
require_once( "../query.php" );
class Posthsinput {
	 
private $lgs,$qryObj,$dsbObj,$rfObj,$xmlObj,$conn,$stid,$prefetch;
   
function __construct() 
	{	
        $filepath = '../util/readquery/general/DBINFO.ini';
        $ini_array = parse_ini_file($filepath, true);
        $dbid='MAIN';
        $host = $ini_array[$dbid]['HOSTIP'];
		$service_name = $ini_array[$dbid]['SERVICENAME'];
		$compCd = $ini_array[$dbid]['COMPCODE'];
		$server = $ini_array[$dbid]['SERVER'];

		define('DATABASE',"(DESCRIPTION =
					(ADDRESS_LIST =
						(ADDRESS = 
							(PROTOCOL = TCP)(HOST = ".$host.")(PORT = 1521))
			   			)
					(CONNECT_DATA =	(SERVER = DEDICATED)
					(SERVICE_NAME = ".$service_name."))				
	  	  		)");		

        $_SESSION['USER'] =strtoupper($_REQUEST['username']);
        $password =strtoupper($_REQUEST['password']);
        $_SESSION['IDTTY'] =$password.strrev($password);            

        if(isset($_SESSION['USER']) && isset($_SESSION['IDTTY'])){
       
        $this->conn = @oci_pconnect($_SESSION['USER'], $_SESSION['IDTTY'], DATABASE, AL32UTF8);
			if (!$this->conn) {
			   $m = oci_error();
			   //echo $m['message'];
			   echo json_encode(array('Success'=>'false','Message'=>'Login Fail'));
			   exit;
			}else {
				//echo json_encode(array('Success'=>'true','Message'=>'Login Success'));
			}
		}
    }

   

    public function postAlltransactionData($data)
	{
		$dsbObj = new Dashboard();
		$qry = new Query();	

		$filepath = 'ini/harvesting_slip.ini';
        $queries = parse_ini_file($filepath, true);

        //FOR JSON TO ARRAY
		$data =json_decode($data, true);			
		
		$response1 = print_r($data, true); 
		        $fp = fopen('oldFilter.txt', 'w');
		        fwrite($fp,  $response1);
		        fclose($fp); 


			foreach ($data as $key => $value) {
                if(gettype($value) == 'array'){
			       $data= $value;			        
                }
			}	    

		    $oldFilter=array();
	        $newFilter=array();
	        //FOR INSERT RECORD INTO TABLE 
	         
	        $total_keys=array();
            $total_values=array();
	        for ($i=0; $i < sizeof($data) ; $i++)
	             { 
	        	   foreach ($data[$i] as $key => $val) 
	        	        {
	        	          if($key == 'TXN_DATE1'){
	        	          	$key = 'LDATE';
	        	          }
		                  $keys=":P".strtoupper($key);

		                  $value=$val;
		                  array_push($oldFilter,$keys);
		                  array_push($newFilter,$value);
		                }//End of for each
	        	

		        //FOR GETTING NEXT SEQ
                $getseqQry = $qry->fetchQuery($filepath,'Q001','GET_SEQ',$oldFilter,$newFilter);
	            $next_seq =$dsbObj->getData($getseqQry);
                $date1 = date('Ymd');
				$date = new DateTime($date1);
				$perd_code = $date->format('Ym');
                array_push($oldFilter,':PTXN_SEQ',":PPERD_CODE",':PUSER_CODE');
                array_push($newFilter,$next_seq[0]['NEXT_TXNSEQ'],$perd_code,$_SESSION['USER']);                
             
             	$response1 = print_r($newFilter, true); 
		        $fp = fopen('newFilter.txt', 'w');
		        fwrite($fp,  $response1);
		        fclose($fp);      
                
                //HERE WE WRITE INSERT QUERY 
	            $insertQry = $qry->fetchQuery($filepath,'Q001','INSERT_HARVSLIP',$oldFilter,$newFilter);	            	            
	            $stid = oci_parse($this->conn, $insertQry);
	            $res=oci_execute($stid); 
	            $affected_rows = oci_num_rows($stid);
	            $r = oci_commit($this->conn);
	            	            
	            $total_data=array();
	            if($affected_rows > 0){
	            	 $postresponseQry = $qry->fetchQuery($filepath,'Q001','INSERT_HARVSLIPRESPONSE',$oldFilter,$newFilter);
	            	 /*print_r($postresponseQry);*/
	                 $post_result =$dsbObj->getData($postresponseQry);
	                 $total_data[]=$post_result;
	                 array_push($total_keys, 'txn_srno');
	                 array_push($total_values, $newFilter[0]);
	                 foreach ($post_result as $key => $value) {
	                 	$keys=array_keys($value);
	                 	$values =array_values($value);
	                 	
	                 	for ($k=0; $k < sizeof($value) ; $k++){
                            array_push($total_keys, strtolower($keys[$k]));
                            array_push($total_values, $values[$k]);                            
	                 	}
	                 }	                
	                 $total_insRecord[]=array_combine($total_keys,$total_values);

	            }else{
	            	echo "Query".$insertQry."Probleme in inserting record";
	            }
		        
		        $oldFilter=array();
		        $newFilter=array();		             
	        }
	       
            // $total_insRecord=array_combine($total_keys, $total_values);

            if(sizeof($post_result) > 0 ){
            	$result['Success']="true";
            }else{
            	$result['Success']="false";
            }
            
	        /*FOR SECMAST MAST*/
	        $result['mmmmast']=$total_insRecord;
	        echo json_encode($result);
	        die;	        

	}

}

$obj = new Posthsinput();
$data=$_REQUEST['data'];
$obj ->postAlltransactionData($data);

//http://203.127.5.7/tndsugar/api/Posthsinput.php?username=administrator&password=adm

?>
