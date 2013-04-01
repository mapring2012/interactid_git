<?php
class Application_Model_Register {
	private $_dbTable;
	private $_dbcompany;
	private $_tbladminHirachieUser;
	private $_refCod;
    private $_tableFactory;
    
	public function __construct() {
		$this->_dbTable = new Application_Model_DbTable_cuser ();
        $this->_tbladminHirachieUser = new Application_Model_DbTable_hierarchyuser ();
		$this->_dbcompany = new Application_Model_DbTable_company ();
		$this->_refCod    = new Application_Model_DbTable_refcodes();
        $this->_tableFactory = new Application_Model_DbTable_fournisseur();
	}
	public function addOrganism($dataOrganism) {
		$this->_dbcompany->insert($dataOrganism);
		$insertID=$this->_dbcompany->getAdapter()->lastInsertId();
		return $insertID;
	}
	public function addRegister($array) {
		$this->_dbTable->insert($array);
		$insertID = $this->_dbTable->getAdapter()->lastInsertId();
		return $insertID;
	}
    public function addSubUser($subUser){
        	$this->_tbladminHirachieUser->insert($subUser);
    }
	public function loginActionPro($UserName, $UserPassword) {
		$ql = $this->_dbTable->select ()->from ( $this->_dbTable, array (
				'User_login',
				'User_password',
                'User_id' 
		) )->where ( 'User_login=?', $UserName )->where ( 'User_password=?', $UserPassword )->where('User_Statut=?','Actif');
		return $this->_dbTable->fetchAll ( $ql )->toArray ();
	}
	
	// org
	public function selectProfile($UserNameget) 

	{
		$db = Zend_Db_Table::getDefaultAdapter ();
		$select = $db->select ()->from ( 'company' )->joinInner ( array (
				'users' => 'cuser' 
		), 'users.Com_Id = company.Com_Id' )->where ( 'User_Login=?', $UserNameget );
		$rows = $db->fetchAll ( $select );
		return $rows;
	}
	// org
	public function selectAllUsersName() {
		$sql = $this->_dbTable->select ()->from ( $this->_dbTable, array (
				'User_login','User_Mail'
		) );
		$getRows = $this->_dbTable->fetchAll( $sql )->toArray ();
		return $getRows;
	}
	
	//select for check and confirm 
	//select for check and confirm
	public function selectUsersConfirm() {
	
		$sql = $this->_dbTable->select ()->from ( $this->_dbTable, array (
				'User_Mail',
				'activated'
		) );
	
		$getRows = $this->_dbTable->fetchAll( $sql )->toArray ();
		return $getRows;
	}
	
	public function selectUsersCheck($data,$where) {
		$sql = $this->_dbTable->select ()->from ( $this->_dbTable,$data) ->where ($where );
		$getRows = $this->_dbTable->fetchAll( $sql )->toArray ();
		return $getRows;
	}
	
	public function update($data ,$where)
	{
		//$db = Zend_Db_Table::getDefaultAdapter ();
		$data = array('User_Statut' => $data);
		$where = $this->_dbTable->getAdapter()->quoteInto('activated = ?', $where);
		$getRows = $this->_dbTable->update($data, $where);
		return $getRows;
	}	
	// end select for check and confirm 
    public function updateUser($data,$where){
    	$getRows = $this->_dbTable->update($data, $where);
    	return $getRows;
    }
    public function selectForUserManagement() {
    	$data=array ('*');
    	$sql = $this->_dbTable->select ()->from ( $this->_dbTable,$data )->order('User_Id DESC');
    	//->where ('User_login=?',$_SESSION['UserSession']);
    	$getRows = $this->_dbTable->fetchAll( $sql )->toArray ();
    	return (! empty ( $getRows )) ? $getRows : null;
    }
    
    
    public function addAdminUser($adminuser) {
			$this->_dbTable->insert($adminuser);
			$LastIdInsert = $this->_dbTable->getAdapter ()->lastInsertId ();
		return $LastIdInsert;
	}
	public function addToHirachieUser($dataAddHirachieUser) {
		$getHirachie = $this->_tbladminHirachieUser ->insert ( $dataAddHirachieUser );
		return $getHirachie;
	}
	public function SelectComId() {
		$sql = $this->_dbcompany->select ()
		->from ( $this->_dbcompany, array ("Com_Id","Com_Name") );
		return $this->_dbcompany->fetchAll ( $sql )->toArray ();
	}
    /*Select all reference code*/
	public function selectReferenceCode(){
		$sql = $this->_refCod->select()
		->from( $this->_refCod,array('*'))
		->where('ref_Num=?','COM002');
		return $this->_refCod->fetchAll ( $sql )->toArray ();
	}
	/*Ending all reference code*/
	/*Select selectReferencecompanyType*/
	public function selectReferencecompanyType(){
		$sql = $this->_refCod->select()
		->from( $this->_refCod,array('*'))
		->where('ref_Num=?','COM001');
		return $this->_refCod->fetchAll ( $sql )->toArray ();
	}
	/*Ending of selecting selectReferencecompanyType*/
	/*Starting select sex*/
	public function selectSex(){
		$sql = $this->_refCod->select()
		->from( $this->_refCod,array('*'))
		->where('ref_Num=?','USR001');
		return $this->_refCod->fetchAll ( $sql )->toArray ();
	}
	/*Ending select sex*/
	/*Starting select code function*/
	public function selectCodeFunction(){
		$sql = $this->_refCod->select()
		->from( $this->_refCod,array('*'))
		->where('ref_Num=?','USR005');
		return $this->_refCod->fetchAll ( $sql )->toArray ();
	}
	/*Ending select code function*/
	/*Starting user status*/
	public function selectUserStatus(){
		$sql = $this->_refCod->select()
		->from( $this->_refCod,array('*'))
		->where('ref_Num=?','USR002');
		return $this->_refCod->fetchAll ( $sql )->toArray ();
	}
	/*Ending user status*/
	/*Starting select userReference code*/
	public function selectUserRecode(){
	$sql = $this->_refCod->select()
	->from( $this->_refCod,array('*'))
	->where('ref_Num=?','USR003');
	return $this->_refCod->fetchAll ( $sql )->toArray ();
	}
	/*Ending select*/ 
	/*Starting user level*/
	public function selectUserLevel(){
		$sql = $this->_refCod->select()
		->from( $this->_refCod,array('*'))
		->where('ref_Num=?','USR004');
		return $this->_refCod->fetchAll ( $sql )->toArray ();
	}
	/*Ending user level*/
    
    /*** Select company Level ***/
    public function selectcompanyLevel(){
        $sql = $this->_refCod->select()
        ->from( $this->_refCod,array('*'))
        ->where('ref_Num=?','COM004');
        return $this->_refCod->fetchAll($sql)->toArray();
    }    
    /*** End Select company Level ***/
    
    /*** select company name ***/
    public function selectcompany(){
        $sql = $this->_dbcompany->select()
        ->from($this->_dbcompany,array('*'));
        return $this->_dbcompany->fetchAll($sql)->toArray();
    }
    /*** End select company name ***/
    
    public function deleteUserAdmin($idUser){	
    	try{
    		$idUserwhere = $this->_dbTable->getAdapter ()->quoteInto ( 'User_id = ?', $idUser );
    		$idUser= $this->_dbTable->delete ( $idUserwhere );
    	if($idUser){
    			return true;
    		}else{
    			return false;
    		}
    	}catch(ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
    }
    public function selectionEditUser($id){
    	$data=array ('*');
    	$sql = $this->_dbTable->select ()->from ( $this->_dbTable,$data )
    	->where('User_id=?',$id);
    	$getRows = $this->_dbTable->fetchAll( $sql )->toArray ();
    	return (! empty ( $getRows )) ? $getRows : null;
    	
    }
   /*Update user account*/
    public function updateUserInadmin($id, $AdminUserData){
    	try{
    		$idEdit=$this->_dbTable->getAdapter()->quoteInto('User_id= ? ',$id) ;
    		$this->_dbTable->update($AdminUserData, $idEdit);
    	}catch(ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
    }
    /*Ending update user account*/
    /*Starting of select overview of user*/
    public function userOverView($OverID){
    	$sql = $this->_dbTable->select()
    	->from( $this->_dbTable,array('*'))
    	->where('User_id=?',$OverID);
    	return $this->_dbTable->fetchAll($sql)->toArray();
    }
    /*Ending of selecting overview of user*/
    public function factorytype(){
            try{
                $sql = $this->_refCod->select()
	        	->from( $this->_refCod,array('*'))
	        	->where('ref_Num=?','USR004');
		return $this->_refCod->fetchAll ( $sql )->toArray ();
            }catch(ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
    }
    
    /*Insert factory */
    
    public function insertRecordFactory($FactoryData){
        try{
             $this->_tableFactory->insert($FactoryData);
             $LastIdInsert = $this->_tableFactory->getAdapter ()->lastInsertId ();
             return $LastIdInsert;
        }catch(ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
       
    }
    
    public function selectAllFactory(){
        $data=array ('*');
    	$sql = $this->_tableFactory->select ()->from ( $this->_tableFactory,$data )->order('Fou_Id DESC');
    	$getRows = $this->_tableFactory->fetchAll( $sql )->toArray ();
    	return (! empty ( $getRows )) ? $getRows : null;
        
    }
    public function updateRecordFactory($FactoryData,$idFactory){
        $idFactory=$this->_tableFactory->getAdapter()->quoteInto(' Fou_Id= ? ',$idFactory) ;
   	    $this->_tableFactory->update($FactoryData, $idFactory);
   	   
        
        
    }
    public function SelectEditFactory($idEdit){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('fou'=>'fournisseur') )
    		//->joinInner (array('hor'=>'hierarchyuser'),'com.Com_Id= hor.Com_Id' )
    		//->joinInner (array('horOrg'=>'HierarchieOrganisme'),'com.Com_Id= horOrg.Com_Id' )
			->where('fou.Fou_Id=?',$idEdit);
			
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
			throw new Exception('Can not select!');
			
		}catch (ErrorException $e){
			echo "Message:".$e->getMessage();
		}
	}
     public function SelectOverViewFactory($ids){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('fou'=>'fournisseur') )
    		//->joinInner (array('hor'=>'hierarchyuser'),'com.Com_Id= hor.Com_Id' )
    		//->joinInner (array('horOrg'=>'HierarchieOrganisme'),'com.Com_Id= horOrg.Com_Id' )
			->where('fou.Fou_Id=?',$ids);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
			throw new Exception('Can not select!');
		}catch (ErrorException $e){
			echo "Message:".$e->getMessage();
		}
	}
	/*Basic search*/
	public function basicfactorySearch($SearchNameFactory){
		try{
			
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('fou'=>'fournisseur'))
			->where('fou.Fou_Name LIKE ?',"%{$SearchNameFactory}%");
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
			throw new Exception('Can not select!');
		}catch (ErrorException $ex){
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
			if($searchsociale !=''){$searchsociale = " AND fou.Fou_Name LIKE '%".$searchsociale."%'";}else {$searchsociale = 'AND fou.Fou_Name  LIKE "*"';}
			if($searchemail !=''){$searchemail = "OR fou.Fou_Mail  LIKE '%".$searchemail."%'";}else {$searchemail = '';}
			if($searchville !=''){$searchville = "OR fou.Fou_City  LIKE '%".$searchville."%'";}else {$searchville = '';}
			if($searchwebsite !=''){$searchwebsite = "OR fou.Fou_Website  LIKE '%".$searchwebsite."%'";}else {$searchwebsite = '';}
			if($searchaddrees !=''){$searchaddrees = "OR fou.Fou_Adresse1 LIKE '%".$searchaddrees."%'";}else {$searchaddrees = '';}
			if($codepostal !=''){$codepostal = "OR fou.Fou_ZipCode  LIKE '%".$codepostal."%'";}else {$codepostal = '';}
			if($searchphone !=''){$searchphone = "OR fou.Fou_Telephone  LIKE '%".$searchphone."%'";}else {$searchphone = '';}
			if($searchCountry !=''){$searchCountry = "OR fou.Fou_Country  LIKE '%".$searchCountry."%'";}else {$searchCountry = '';}
			$db = Zend_Db_Table::getDefaultAdapter ();
			$status =1;
			$select = $db->select ()
			->from (array('fou'=>'fournisseur'))
			//->joinInner (array('hor'=>'hierarchyuser'),'com.Com_Id= hor.Com_Id',$data )
			//->joinInner (array('user'=>'cuser'),'hor.User_id= user.User_id' )
			->where("fou.status=$status $searchsociale $searchsociale $searchemail $searchville $searchwebsite $searchaddrees $codepostal $searchphone $searchCountry");
			$rows = $db->fetchAll ( $select );
			return (! empty ( $rows )) ? $rows : null;
		}catch(ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	/* end  basic and advance search  */
    public function deleteFactory($idFactory){
    	  try{
        	$idFactory= $this->_tableFactory->getAdapter ()->quoteInto ( 'Fou_Id = ?', $idFactory );
		    $idParent = $this->_tableFactory->delete ( $idFactory );
		    $sucess =   ($idParent)? TRUE : FALSE;
		    return $sucess;
    	  }catch (ErrorException $ex){
			echo "MessageError".$ex->getMessage();
		}
     }
    public function unlinkImagesFromFolderById($id){
		try{
		$sql = $this->_tableFactory->select ()->from ( $this->_tableFactory, array ("Fou_Logo") )
        ->where ("Fou_Id=?",$id);
		$rows = $this->_tableFactory->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
		}catch (ErrorException $ex){
			echo "MessageError".$ex->getMessage();
		}
	}
	//End of deleting 
	
	/*
	 * @module : mulitple delete
	 * @Deloper: sidet
	 */
	public function multipledelete($idCount){
	    for($i=0;$i<count($idCount);$i++){
	    	$id =$idCount[$i];
	    	$whereMulti = $this->_tableFactory->getAdapter ()->quoteInto ( 'Fou_Id = ?', $id );
	    	$this->_tableFactory->delete ( $whereMulti ); 
	    }
	}
	public function multiDeleteUser($id){
		try{
			for($i=0;$i<count($id);$i++){
				$ids =$id[$i];
				$whereMulti = $this->_dbTable->getAdapter ()->quoteInto ( 'User_id = ?', $ids );
				$this->_dbTable->delete ( $whereMulti );
			}
		}catch (ErrorException $ex){
   		echo "MessageError".$ex->getMessage();
   	}
	}
	/*Select image name*/
   public function selectImageUnlink($idFactory){
   	try{
   		$sql = $this->_tableFactory->select ()->from ( $this->_tableFactory, array ("Fou_Logo") )
   		->where ("Fou_Id=?",$idFactory);
   		$rows = $this->_tableFactory->fetchAll ( $sql )->toArray ();
   		return (! empty ( $rows )) ? $rows : null;
   	}catch (ErrorException $ex){
   		echo "MessageError".$ex->getMessage();
   	}
   	
   }
   public function updateRefer($datas,$GetFactoryLastId){
   	try{
   		$idEdit=$this->_tableFactory->getAdapter()->quoteInto(' Fou_Id= ? ',$GetFactoryLastId) ;
   		$row=$this->_tableFactory->update($datas, $idEdit);
   	}catch ( ErrorException $exc ) {
   		echo "Message:" . $exc->getMessage ();
   	}
   }
   
   /* develop: dim sidet
    * 
    * destroy object ,close connection,close session,... */
   public function __destruct(){
   	try{
   	$this->_dbTable;
   	$this->_tbladminHirachieUser;
   	$this->_dbcompany;
   	$this->_refCod;
   	$this->_tableFactory;
   	}catch(ErrorException $ex){
   		echo "Message:" . $ex->getMessage ();
   	}
   }
   
   /* end of destroy object ,close connection,close session,... */
    
}
