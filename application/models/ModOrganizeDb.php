<?php
class Application_Model_ModOrganizeDb {
	private $_Dbrefcodes;
	public $_Tblcompany;
	private $_TblHirachiecompany;
	private $_Tblcuser;
	private $_TblHirachieUser;

	public function __construct() {
		$this->_Dbrefcodes = new Application_Model_DbTable_refcodes ();
		$this->_Tblcompany = new Application_Model_DbTable_company ();
		$this->_TblHirachiecompany = new Application_Model_DbTable_hierarchieorganisme ();
		$this->_Tblcuser = new Application_Model_DbTable_cuser ();
		$this->_TblHirachieUser = new Application_Model_DbTable_hierarchyuser ();
	
	}
	public function SelectRefLevel() {
		$sql = $this->_Dbrefcodes->select ()
		->from ( $this->_Dbrefcodes, array (
				"ref_code_lib",
				"ref_Code",
				"ref_Description"
		) )->where ( "ref_Num=?", "SIT004" )->order('ref_Code ASC');
		$rows = $this->_Dbrefcodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectRefStatus() {
		$sql = $this->_Dbrefcodes->select ()
		->from ( $this->_Dbrefcodes, array (
				"ref_code_lib",
				"ref_Code",
				"ref_Description"
		) )->where ( "ref_Num=?", "SIT001" )->order('ref_Code ASC');
		$rows =$this->_Dbrefcodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectRefCode() {
		$sql = $this->_Dbrefcodes->select ()->from ( $this->_Dbrefcodes, array (
				"ref_code_lib",
				"ref_Code",
				"ref_Description" 
		) )->where ( "ref_Num=?", "COM002" )->order('ref_Code ASC');
		return $this->_Dbrefcodes->fetchAll ( $sql )->toArray ();
	}
	public function SelectRefComType() {
		$sql = $this->_Dbrefcodes->select ()->from ( $this->_Dbrefcodes, array ("ref_code_lib",
				"ref_Code",
				"ref_Description" 
		) )->where ( "ref_Num=?", "SIT003" )->order('ref_Code ASC');
		$rows = $this->_Dbrefcodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectRefCodeFunction() {
		$sql = $this->_Dbrefcodes->select ()->from ( $this->_Dbrefcodes, array ("ref_code_lib",
				"ref_Code",
				"ref_Description"
		) )->where ( "ref_Num=?", "COM001" )->order('ref_Code ASC');
		$rows = $this->_Dbrefcodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	/* basic and advance search  */
	public function searchbasic($SearchNameOrganisme){
		try {
			$db = Zend_Db_Table::getDefaultAdapter ();
			$data=array("comid"=>"hor.Com_Id");
			$select = $db->select ()-> distinct()->from (array('com'=>'company'))
			->joinInner (array('hor'=>'hierarchyuser'),'com.Com_Id= hor.Com_Id',$data )
			->joinInner (array('user'=>'cuser'),'hor.User_id= user.User_id' )
			->where('com.Com_Name LIKE ?',"%{$SearchNameOrganisme}%")
			->where('user.User_id=?',$_SESSION['UserId']);
			$rows = $db->fetchAll ( $select );
			return (! empty ( $rows )) ? $rows : null;
	     }catch(ErrorException $ex){
	     	echo "Message:".$ex->getMessage();
	     }
	}
	/*advandce search*/
	public function searchAvance($dataAdvanceSearch){
		try {
			list(
			   $searchsociale,
			   $searchemail,
			   $searchstate,
			   $searchville,
			   $searchwebsite,
			   $searchaddrees,
			   $codepostal,
			   $searchphone,
			   $searchCountry
			   )=$dataAdvanceSearch;
			if($searchsociale !=''){$searchsociale = "AND com.Com_Name LIKE '%".$searchsociale."%'";}else {$searchsociale = 'AND com.Com_Name LIKE "*"';}
			if($searchemail !=''){$searchemail = "OR com.Com_Mail LIKE '%".$searchemail."%'";}else {$searchemail = '';}
			if($searchville !=''){$searchville = "OR com.Com_City LIKE '%".$searchville."%'";}else {$searchville = '';}
			if($searchwebsite !=''){$searchwebsite = "OR com.Com_Website LIKE '%".$searchwebsite."%'";}else {$searchwebsite = '';}
			if($searchaddrees !=''){$searchaddrees = "OR com.Com_Address1 LIKE '%".$searchaddrees."%'";}else {$searchaddrees = '';}
			if($codepostal !=''){$codepostal = "OR com.Com_ZipCode LIKE '%".$codepostal."%'";}else {$codepostal = '';}
			if($searchphone !=''){$searchphone = "OR com.Com_Telephone LIKE '%".$searchphone."%'";}else {$searchphone = '';}
			if($searchCountry !=''){$searchCountry = "OR com.Com_Country LIKE '%".$searchCountry."%'";}else {$searchCountry = '';}	
			$db = Zend_Db_Table::getDefaultAdapter ();
			$data=array("comid"=>"hor.Com_Id");
			$userid = $_SESSION['UserId'];
			 $select = $db->select ()
			->from (array('com'=>'company'))
			->joinInner (array('hor'=>'hierarchyuser'),'com.Com_Id= hor.Com_Id',$data )
			->joinInner (array('user'=>'cuser'),'hor.User_id= user.User_id' )
			->where("user.User_id= $userid $searchsociale $searchemail $searchville $searchwebsite $searchaddrees $codepostal $searchphone $searchCountry");
			$rows = $db->fetchAll ( $select );
			return (! empty ( $rows )) ? $rows : null;
		}catch(ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	/* end  basic and advance search  */
	public function addSubUser($subUser){
		$this->_TblHirachieUser->insert($subUser);
	}
	
	public function InsertRecordTocompany($dataAddcompany) {
		$this->_Tblcompany->insert ( $dataAddcompany );
		$LastIdInsert = $this->_Tblcompany->getAdapter ()->lastInsertId ();
		return $LastIdInsert;
	}
	public function InsertRecodeToHierachiecompany($dataAddHirachiecompany) {
		$hirachieCom = $this->_TblHirachiecompany->insert ( $dataAddHirachiecompany );
		return $hirachieCom;
	}
	
	public function InsertRecordToUser($dataAddUser) {
		$this->_Tblcuser->insert ( $dataAddUser );
		$rowsUserLastId = $this->_Tblcuser->getAdapter ()->lastInsertId ();
		return $rowsUserLastId;
	}
	public function InsertRecordToHirachieUser($dataAddHirachieUser) {
		$getHirachie = $this->_TblHirachieUser->insert ( $dataAddHirachieUser );
		return $getHirachie;
	}
	public function AdvandeSearch($id){
		try{
			$search=array("Com_Refcode","Com_RaisonSocial","Com_City");
			$sql = $this->_Tblcompany->select ()->from ( $this->_Tblcompany,$search)->where ("Com_RaisonSocial like ?",$id."%");
			$rows = $this->_Tblcompany->fetchAll ( $sql )->toArray ();
			return (! empty ( $rows )) ? $rows : null;
		}catch (ErrorException $ex){
			echo "MessageError".$ex->getMessage();
		}
		
	}
	public function CompareComparenameAndEmail() {
		$data=array ('Com_Name','Com_Mail');
		$sql = $this->_Tblcompany->select ()->from ( $this->_Tblcompany, $data);
		$getRows = $this->_Tblcompany->fetchAll( $sql )->toArray ();
		return $getRows;
	}
	
	// select all data from company,Hirachiecompany,cuser,HirachieUser table for
	// Organisme menu
	public function selectAllDataForOrganismeMenu() {
			$db = Zend_Db_Table::getDefaultAdapter ();
            $data=array("comid"=>"hor.Com_Id");
            $select = $db->select ()-> distinct()->from (array('com'=>'company'))
    		->joinInner (array('hor'=>'hierarchyuser'),'com.Com_Id= hor.Com_Id',$data )
    		->joinInner (array('user'=>'cuser'),'hor.User_id= user.User_id' )
    		->where('user.User_id=?',$_SESSION['UserId'])
    		->order('com.Com_Id DESC');
     	 /*$select = $db->select ()->from (array('com'=>'company') )
    		->joinInner (array('hor'=>'hierarchieorganisme'),'com.Com_Id= hor.Com_Id' ); */
			//	->joinInner ( array ('ref' => 'refcodes' ), 'ref.ref_Id=com.Com_Refcode' )
			//	->joinInner ( array ('hus' => 'hierarchyuser' ), 'user.User_id=hus.User_id' );
		$rows = $db->fetchAll ( $select );
		return (! empty ( $rows )) ? $rows : null;
	}
	//Starting of selecting  data to update on form in Organisme menu
	
	public function SelectEditOrganisme($idEdit){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('com'=>'company') )
    		->joinInner (array('hor'=>'hierarchyuser'),'com.Com_Id= hor.Com_Id' )
    		->joinInner (array('horOrg'=>'hierarchieorganisme'),'com.Com_Id= horOrg.Com_Id' )
			->where('com.Com_Id=?',$idEdit);
			/*->joinInner (array('hor'=>'hierarchieorganisme'),'com.Com_Id= hor.Com_Id' )
			 ->joinInner (array('ref'=>'refcodes'),'ref.ref_Id=com.Com_Refcode' )
			->joinInner (array('hus'=>'hierarchyuser' ),'user.User_id=hus.User_id' ) ; */
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
			throw new Exception('Can not select!');
			
			/*$select = $db->select ()->from (array('com'=>'company') )
			->joinInner (array('hor'=>'hierarchieorganisme'),'com.Com_Id= hor.Com_Id' )
			->where('com.Com_Id=?',$idEdit);*/
		}catch (ErrorException $e){
			echo "Message:".$e->getMessage();
		}
	}
	 
	//Ending of seleting
	/*Select overview edit*/
	public function SelectOverViewEdit( $idEdit,$idUser ){
	
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('com'=>'company') )
    		->joinInner (array('hor'=>'hierarchyuser'),'com.Com_Id= hor.Com_Id' )
    		->joinInner (array('user'=>'cuser'),'hor.User_id= user.User_id' )
    		->where('user.User_id=?',$idUser)
			->where('com.Com_Id=?',$idEdit);
			/*->joinInner (array('hor'=>'hierarchieorganisme'),'com.Com_Id= hor.Com_Id' )
			 ->joinInner (array('ref'=>'refcodes'),'ref.ref_Id=com.Com_Refcode' )
			->joinInner (array('hus'=>'hierarchyuser' ),'user.User_id=hus.User_id' ) ; */
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
			throw new Exception('Can not select!');
		}catch (ErrorException $e){
			echo "Message:".$e->getMessage();
		}
	}
	/*Ending overview edit*/
	
	// Starting delete data from company,Hirachiecompany,cuser,HirachieUser table
	
	public function deleteAllDataFromOrganismeMenu($id) {
		
		$idcompany = $this->_Tblcompany->getAdapter ()->quoteInto ( 'Com_Id = ?', $id );
		$idParent = $this->_Tblcompany->delete ( $idcompany );
		
		$idHOrganisme = $this->_TblHirachiecompany->getAdapter ()->quoteInto ( 'Com_Id = ?', $id );
		$HOrganisme = $this->_TblHirachiecompany->delete ( $idHOrganisme );
		
		 $idcuser = $this->_Tblcuser->getAdapter ()->quoteInto ( 'Com_Id = ?', $id );
		$cuser = $this->_Tblcuser->delete ( $idcuser ); 
		
		$idHUser = $this->_TblHirachieUser->getAdapter ()->quoteInto ( 'Com_Id = ?', $id );
		$HUser = $this->_TblHirachieUser->delete ( $idHUser );
		
		if ($idParent ) {
			return true;
		} else {
			return false;
		}
	}
	/*
	 * @Developer: Doeun
	 * Module    : Delete image from folder
	 */
	public function unlinkImagesFromFolderById($id){
		try{
		$sql = $this->_Tblcompany->select ()->from ( $this->_Tblcompany, array (
				"Com_Logo"
		) )->where ("Com_Id=?",$id);
		$rows = $this->_Tblcompany->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
		}catch (ErrorException $ex){
			echo "MessageError".$ex->getMessage();
		}
	}
	//End of deleting 
	
	/*
	 * @module : mulitple delete
	 * @Deloper: Doeun
	 */
	public function multipledelete($idCount){
		//print_r($idCount);
	   // $id = (is_array($idCount))?implode(',',$idCount):$idCount;
	    for($i=0;$i<count($idCount);$i++){
	    	$id =$idCount[$i];
	    	$whereMulti = $this->_Tblcompany->getAdapter ()->quoteInto ( 'Com_Id = ?', $id );
	    	$this->_Tblcompany->delete ( $whereMulti );
	    	
	    	$idHOrganisme = $this->_TblHirachiecompany->getAdapter ()->quoteInto ( 'Com_Id = ?', $id );
	    	$HOrganisme = $this->_TblHirachiecompany->delete ( $idHOrganisme );
	    	
	    	 $idcuser = $this->_Tblcuser->getAdapter ()->quoteInto ( 'Com_Id = ?', $id );
	    	$cuser = $this->_Tblcuser->delete ( $idcuser );
	    	
	    	$idHUser = $this->_TblHirachieUser->getAdapter ()->quoteInto ( 'Com_Id = ?', $id );
	    	$HUser = $this->_TblHirachieUser->delete ( $idHUser ); 
	    }
	}
	

 
    	
    	//update Hirachiecompany table by Com_Id  column
    	public function UpdateRecordHirachiecompany($fielnames,$idEdit){
    		$idEdit=$this->_TblHirachiecompany->getAdapter()->quoteInto(' Com_Id= ? ',$idEdit) ;
    		$this->_TblHirachiecompany->update($fielnames, $idEdit);
    	}
    	//update cuser table by Com_Id  column
    	public function UpdateRecordcuser($fielnames,$idEdit){
    		$idEdit=$this->_Tblcuser->getAdapter()->quoteInto(' Com_Id= ? ',$idEdit) ;
    		$this->_Tblcuser->update($fielnames, $idEdit);
    	}
    	//update HirachieUser table by Com_Id  column
    	public function UpdateRecordHirachieUser($fielnames,$idEdit){
    		$idEdit=$this->_TblHirachieUser->getAdapter()->quoteInto(' Com_Id= ? ',$idEdit) ;
    		$this->_TblHirachieUser->update($fielnames, $idEdit);
    	}
    	//updata company table by Com_Id  column
    	public function UpdateRecordcompany($dataUpdatecompany,$id){
    		$idEdit=$this->_Tblcompany->getAdapter()->quoteInto(' Com_Id= ? ',$id) ;
    		$row=$this->_Tblcompany->update($dataUpdatecompany, $idEdit);
    		return $row;
    	}
        public function updateRefer($datas,$GetcompanyLastId){
            try{
            $idEdit=$this->_Tblcompany->getAdapter()->quoteInto(' Com_Id= ? ',$GetcompanyLastId) ;
    		$row=$this->_Tblcompany->update($datas, $idEdit);
            }catch ( ErrorException $exc ) {
			 echo "Message:" . $exc->getMessage ();
			}
        }
    	
}//end class