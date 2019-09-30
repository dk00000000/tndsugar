<?php 
session_start();
//require('dashboard1.php');
//require('readfile.php');
//session_start();
class Brwsnavigation
	{
		var $comp_code,$menu_code,$user_code;
		var $lgs ;
		var $isMenuAll;
		var $isMenuQry;
		var $isMenuUpd;
		var $isMenuDel;
		var $isMenuAdd;
		var $isupdate;
		var $isadd,$isQry,$isAll;
		var $mnucd,$chkFontBold,$chkFontItalic;
		var $isdelete;
		//
		function __construct()
		{
			$this->lgs = new Logs();
	  		$this->qryObj = new Query();
			$rfObj = new ReadFile();
			$this->dsbObj = new Dashboard();
			$this->comp_code =$_SESSION['COMP_CODE'];
			$this->user_code =$_SESSION['USER'];
			$this->menu_code =$_SESSION['menu_code'];
			
		}

		public function resetData()
		{
			
		}//END OF RESETDATA

	 public function setIsMenuAll($isMenuAll)
    {
        $this->isMenuAll = $isMenuAll;
		$this->lgs->lg->trace("ismenuall in set : ".$this->isMenuAll);
    }

    public function getIsMenuAll()
    {
        return $this->isMenuAll;
    }

    public function setIsMenuQry($isMenuQry)
    {
          $this->isMenuQry = $isMenuQry ;
    }

    public function getIsMenuQry()
    {
        return $this->isMenuQry;
    }

    public function setIsMenuAdd($isMenuAdd)
    {
         $this->isMenuAdd = $isMenuAdd ;
    }

    public function getIsMenuAdd()
    {
        return $this->isMenuAdd;
    }

    public function setIsMenuUpd($isMenuUpd)
    {
       $this->isMenuUpd = $isMenuUpd ;
    }

    public function getIsMenuUpd()
    {
        return $this->isMenuUpd;
    }

    public function setIsMenuDel($isMenuDel)
    {
         $this->isMenuDel = $isMenuDel ;
    }

    public function getIsMenuDel()
    {
        return $this->isMenuDel;
    }
	
	 public function setIsDelete($isdelete)
	 {
	 	 $this->isdelete = $isdelete ;
	 }
	   public function getIsDelete()
	 {
	 	return $this->isdelete;
	 }
	 
	  public function setIsUpdate($isupdate)
	 {
	 	 $this->isupdate = $isupdate ;
	 }
	  public function getIsUpdate()
	 {
	 	return $this->isupdate;
	 }
	 
	  public function setIsAdd($isadd)
	 {
	 	 $this->isadd = $isadd ;
	 }
	 
	  public function getIsAdd()
	 {
	 	return $this->isadd;
	 }
	 
	   public function setIsQry($isQry)
    {
          $this->isQry = $isQry ;
    }

    public function getIsQry()
    {
        return $this->isQry;
    }
	
	   public function setIsAll($isAll)
    {
          $this->isAll = $isAll ;
    }

    public function getIsAll()
    {
        return $this->isAll;
    }
	 
	public function setMenuCd($mnucd)
	 {
	 	$this->mnucd = $mnucd;
		$_SESSION['MNUCD']=$this->mnucd ;
		$this->lgs->lg->trace("set mnucd--".$this->mnucd);
	 }
	   public function getMenuCd()
	 {
	 	$this->lgs->lg->trace("mnucd--".$this->mnucd);
	 	return $_SESSION['MNUCD'];
	 }
	 
	   public function setFontBold($chkFontBold)
	 {
	 	$this->chkFontBold = $chkFontBold;
		//$_SESSION['MNUCD']=$this->mnucd ;
		$this->lgs->lg->trace("set font style bold--".$this->chkFontBold);
	 }
	   public function getFontBold()
	 {
	 	$this->lgs->lg->trace("get font style bold--".$this->chkFontBold);
	 	return $this->chkFontBold;
	 }
 	
	  public function setFontItalic($chkFontItalic)
	 {
	 	$this->chkFontItalic = $chkFontItalic;
		//$_SESSION['MNUCD']=$this->mnucd ;
		$this->lgs->lg->trace("set font style italic--".$this->chkFontItalic);
	 }
	   public function getFontItalic()
	 {
	 	$this->lgs->lg->trace("get font style italic--".$this->chkFontItalic);
	 	return $this->chkFontItalic;
	 }
	 
	   public function transaction($res_tran)
	  {
	  //print_r($res_tran);
	  
		    if($res_tran[0]['MENU_ALL'] == "Y")
			{
				$this->setIsMenuAll(true);
				$this->setIsMenuQry(true);
				$this->setIsMenuAdd(true);
				$this->setIsMenuUpd(true);
				$this->setIsMenuDel(true);
					$this->lgs->lg->trace("MENU ALL TRUE");
			}
			else
			{
				$this->setIsAll(false);
				if($res_tran[0]['MENU_QRY'] == 'Y')
				{
					$this->setIsMenuQry(true);
					$this->lgs->lg->trace("MENU QRY TRUE");
				}
				else
				{
					$this->setIsMenuQry(false);
						$this->lgs->lg->trace("MENU ALL FALSE");
				}
				
				
			    if($res_tran[0]['MENU_ADD'] == 'Y')
				{
					$this->setIsMenuAdd(true);
					$this->setIsMenuUpd(false);
					$this->setIsMenuDel(false);
					$this->lgs->lg->trace("set menu add true");
				}
				else
				{
					$this->setIsMenuAdd(false);
					$this->lgs->lg->trace("set menu add false");
				}
				
				
				if($res_tran[0]['MENU_UPD'] == 'Y')
				{
					$this->setIsMenuAdd(false);
					$this->setIsMenuUpd(true);
				}
				else
				{
					$this->setIsMenuUpd(false);
				}
			
				if($res_tran[0]['MENU_DEL'] == 'Y')
				{
					$this->setIsMenuAdd(false);
					$this->setIsMenuUpd(false);
					$this->setIsMenuDel(true);
				}
				else
				{
					$this->setIsMenuDel(false);
				}
			}
			
		 
	  }//end of transaction function
}//end of Class	

?>


