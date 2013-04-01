<?php 
/**
 * @Developer  Doeun
 * @Module   site
 * @Descript Add,Edit,Update and select
 * @company   Mapring
 * @copyright 2013
 */
class Application_Model_Modsite {
	public $Tablesite = null;
	public $TableHirachysite =null;
	private $_tblRefCodes;
	/**Create contractor**/
	function __construct(){
	 $this->Tablesite = new Application_Model_DbTable_site();	
	 $this->TableHirachysite= new Application_Model_DbTable_hierarchiesite();
	 $this->_tblRefCodes = new Application_Model_DbTable_refcodes ();
	}
	public function SelectRefComType() {
		$sql = $this->_tblRefCodes->select ()
		->from ( $this->_tblRefCodes, array (
				"ref_code_lib",
				"ref_Code",
				"ref_Id"
		) )->where ( "ref_Num=?", "SIT003" );
		$rows = $this->_tblRefCodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectRefStatus() {
		$sql = $this->_tblRefCodes->select ()
		->from ( $this->_tblRefCodes, array (
				"ref_code_lib",
				"ref_Code",
				"ref_Id"
		) )->where ( "ref_Num=?", "SIT001" );
		$rows =$this->_tblRefCodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectRefCode() {
		$sql = $this->_tblRefCodes->select ()
		->from ( $this->_tblRefCodes, array (
				"ref_code_lib",
				"ref_Code",
				"ref_Id"
		) )->where ( "ref_Num=?", "SIT002" );
		$rows =$this->_tblRefCodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	
	public function SelectRefLevel() {
		$sql = $this->_tblRefCodes->select ()
		->from ( $this->_tblRefCodes, array (
				"ref_code_lib",
				"ref_Code",
				"ref_Id"
		) )->where ( "ref_Num=?", "SIT004" );
		$rows = $this->_tblRefCodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	
	
    /**Starting Module adding to site**/
   public function InsertRecordToTablesite($Datasite){
   	try{
   	 $this->Tablesite->insert ( $Datasite );
	 $LastIdInsert = $this->Tablesite->getAdapter ()->lastInsertId ();
	 return $LastIdInsert;
    }catch (ErrorException $ex){
     	echo "Message:".$ex->getMessage();
     }
   }
   /**Ending Module adding to site**/
   /**Starting Module adding to Sub site**/
   public function InsertRecordToTableSubsite($DataSubsite){
   	try{
   	$this->TableHirachysite->insert($DataSubsite);
   	}catch (ErrorException $ex){
     	echo "Message:".$ex->getMessage();
     }
   }
   /**Ending Module adding to Sub site**/
   /*Starting delete site*/
   public function deletesites($idsite){
   	try{
   		$idsitewhere = $this->Tablesite->getAdapter ()->quoteInto ( 'site_Id = ?', $idsite );
   		$siteDel = $this->Tablesite->delete ( $idsitewhere );
   		
   		//$idSubsitewhere = $this->TableHirachysite->getAdapter ()->quoteInto ( 'site_Id = ?', $idsite );
   		//$siteDel        =$this->TableHirachysite->delete ( $idSubsitewhere );
   		
   		if($siteDel){
   			return true;
   		}else{
   			return false;
   		}
   	}catch(ErrorException $ex){
   		echo "Message:".$ex->getMessage();
   	}
   }
   /*Ending of delete site*/
   
   /*Starting multidelete site*/
   public function multiDeletesite($idMultiDelsite){
   	  try{
   	  for($i=0;$i<count($idMultiDelsite);$i++){
   	  	$id= $idMultiDelsite[$i];
   	    $idsitewhere = $this->Tablesite->getAdapter ()->quoteInto ( 'site_Id = ?', $id );
   	  	$this->Tablesite->delete ( $idsitewhere );
   	  	$idSubsitewhere = $this->TableHirachysite->getAdapter ()->quoteInto ( 'site_Id = ?', $id );
   	  	$this->TableHirachysite->delete ( $idSubsitewhere );
   	  }
   	  return true;
   	 }catch (ErrorException $error){
   	 	echo "".$error->getMessage();
   	 }
   }
   /*Ending multidelete site*/
   /*Starting of Selecting all record from site for editing*/
   public function SelectAllFromsite($idForEditsite,$idusers){
   	try{
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$data=array("userid"=>"users.User_id");
           	$select = $db->select ()->from (array('sites'=>'site') )
           	->joinInner (array('users'=>'cuser' ),'users.User_id = sites.User_id',$data )
            ->joinInner (array('com'=>'company' ),'com.Com_Id = sites.Com_Id' )
            ->where('sites.site_Id=?',$idForEditsite)
            ->where('users.User_id=?',$idusers);
   		$rows = $db->fetchAll ($select);
   		return (!empty($rows))?$rows:null;
   	}catch (ErrorException $ex){
   		echo "Message:".$ex->getMessage();
   	}
   }
   /*Ending of Selecting all record from site for editing*/
   /*Starting update site module*/
   public function updateRecordsite($editsiteData,$id){
   	$idEdit=$this->Tablesite->getAdapter()->quoteInto(' site_Id= ? ',$id) ;
   	$this->Tablesite->update($editsiteData,$idEdit);
   }
   /*Ending update site module*/
   /*Starting update subsite*/
   public function UpdateRecordToTableSubsite($EditDataSubsite,$id){
   	$idEdit=$this->TableHirachysite->getAdapter()->quoteInto(' site_Id= ? ',$id) ;
   	$this->TableHirachysite->update($EditDataSubsite,$idEdit);
   }
   /*Ending update subsite*/
   /*Starting select view site module*/
   public function selectAllViewsiteModule(){
   	try{
   	$db = Zend_Db_Table::getDefaultAdapter ();
    $data=array("userid"=>"users.User_id");
   	$select = $db->select ()->from (array('sites'=>'site') )
   	->joinInner (array('users'=>'cuser' ),'users.User_id = sites.User_id',$data )
    ->joinInner (array('com'=>'company' ),'com.Com_Id = sites.Com_Id' )
    ->where('users.User_id=?',$_SESSION['UserId'])
   	->order('sites.site_Id DESC');

   	$rows = $db->fetchAll ($select );
   	return (!empty($rows))?$rows:null;
     }catch (ErrorException $ex){
     echo "Message:".$ex->getMessage();
     }
   }
   
   /*Ending  select view site module*/
   /*Basic search module*/
   public function basicsiteSearch($SearchNamesite){
   	try{
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$data=array("userid"=>"users.User_id");
   		$select = $db->select ()->from (array('sites'=>'site') )
   		->joinInner (array('users'=>'cuser' ),'users.User_id = sites.User_id',$data )
   		->joinInner (array('com'=>'company' ),'com.Com_Id = sites.Com_Id' )
   		->where('sites.site_Name LIKE ?',"%{$SearchNamesite}%")
   		->where('users.User_id=?',$_SESSION['UserId']);
   	    
   		$rows = $db->fetchAll ($select );
   		return (!empty($rows))?$rows:null;
   	}catch (ErrorException $ex){
   		echo "Message:".$ex->getMessage();
   	}
   }
   /*Ending basic search module*/
   /*Starting of doing advansearch*/
   public function searchAvance($dataAdvanceSearch){
   	  try{
   	  
   	list(
   			$searchtxtsitenames,
			$searchaddress,
			$searchcodepostal,
			$searchcity,
			$searchpay
   	)=$dataAdvanceSearch;
   	if($searchtxtsitenames !=''){$searchtxtsitenames = "AND sites.site_Name LIKE '%".$searchtxtsitenames."%'";}else {$searchtxtsitenames = 'AND sites.site_Name LIKE "*"';}
   	if($searchaddress !=''){$searchaddress = "OR sites.site_Adresse1 LIKE '%".$searchaddress."%'";}else {$searchaddress = '';}
   	if($searchcodepostal !=''){$searchcodepostal = "OR sites.site_Zipcode LIKE '%".$searchcodepostal."%'";}else {$searchcodepostal = '';}
   	if($searchcity !=''){$searchcity = "OR sites.site_City LIKE '%".$searchcity."%'";}else {$searchcity = '';}
   	if($searchpay !=''){$searchpay = "OR sites.site_Country LIKE '%".$searchpay."%'";}else {$searchpay = '';}
   	$db = Zend_Db_Table::getDefaultAdapter ();
   	$data=array("userid"=>"users.User_id");
   	$userid = $_SESSION['UserId'];
    $select = $db->select ()->from (array('sites'=>'site') )
   	->joinInner (array('users'=>'cuser' ),'users.User_id = sites.User_id',$data )
   	->joinInner (array('com'=>'company' ),'com.Com_Id = sites.Com_Id' )
   ->where("users.User_id= $userid $searchtxtsitenames $searchaddress $searchcodepostal $searchcity $searchpay");
   	$rows = $db->fetchAll ($select );
   	return (!empty($rows))?$rows:null;
     }catch (ErrorException $ex){
   		echo "Message:".$ex->getMessage();
   	}
   }
   /*Ending of doing advancesearch*/
   /*Starting site overview*/
   public function selectsiteOverview($id,$userid){
       	try{
           	$db = Zend_Db_Table::getDefaultAdapter ();
            $data=array("userid"=>"users.User_id");
           	$select = $db->select ()->from (array('sites'=>'site') )
           	->joinInner (array('users'=>'cuser' ),'users.User_id = sites.User_id',$data )
            ->joinInner (array('com'=>'company' ),'com.Com_Id = sites.Com_Id' )
            ->where('sites.site_Id=?',$id)
            ->where('users.User_id=?',$userid);
           	$rows = $db->fetchAll ($select);
           	return (!empty($rows))?$rows:null;
             }catch (ErrorException $ex){
             echo "Message:".$ex->getMessage();
             }  
   }
   /*Ending site overview*/
   /*Starting select company*/
     public function selectcompanyName(){
          try{
                $db = Zend_Db_Table::getDefaultAdapter ();
                $select = $db->select ()->from (array('Com'=>'company') );
                $rows = $db->fetchAll ($select );
                return (!empty($rows))?$rows:null;
          }catch (ErrorException $ex){
                echo "Message:".$ex->getMessage();
           }  
          
     }
   /*Ending select company*/
   
}
