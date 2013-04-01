<?php 
/**
 * @Developer  Doeun
 * @Module     Type Equipement
 * @Descript Add,Edit,Update and select
 * @Company   Mapring
 * @copyright 2013
 */
class Application_Model_ModFactory {
	private $_refCod;
    private $_tableFactory;
    private $_equipementtyperef;
    private $_equipementref;
	
	public function __construct(){
		$this->_refCod    = new Application_Model_DbTable_refcodes();
        $this->_tableFactory = new Application_Model_DbTable_fournisseur();
        $this->_equipementtyperef = new Application_Model_DbTable_equipementtyperef();
        $this->_equipementref     = new Application_Model_DbTable_equipementref();
	}
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
    	try{
        $data=array ('*');
    	$sql = $this->_tableFactory->select ()->from ( $this->_tableFactory,$data );
    	$getRows = $this->_tableFactory->fetchAll( $sql )->toArray ();
    	return (! empty ( $getRows )) ? $getRows : null;
    	}catch(ErrorException $e){
    		echo "Message:".$e->getMessage();
    	}
        
    }
    public function updateRecordFactory($FactoryData,$idFactory){
    	try{
        $idFactory=$this->_tableFactory->getAdapter()->quoteInto(' Fou_Id= ? ',$idFactory) ;
   	    $this->_tableFactory->update($FactoryData, $idFactory);
   	    }catch(ErrorException $e){
   	    	echo "Message:".$e->getMessage();
   	    }
        
        
    }
    public function SelectEditFactory($idEdit){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('fou'=>'fournisseur') )
    		//->joinInner (array('hor'=>'HierarchyUser'),'com.Com_Id= hor.Com_Id' )
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
    		//->joinInner (array('hor'=>'HierarchyUser'),'com.Com_Id= hor.Com_Id' )
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
			//->joinInner (array('hor'=>'HierarchyUser'),'com.Com_Id= hor.Com_Id',$data )
			//->joinInner (array('user'=>'CUser'),'hor.User_id= user.User_id' )
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
   
   /* insert TypeEquipement ,sidet */
   public function insertTypeEquipement($dataTypeEquipement){
   	   try{
   	   		$this->_equipementtyperef->insert($dataTypeEquipement);
   	   		$LastIdInsert = $this->_equipementtyperef->getAdapter ()->lastInsertId ();
   	   		return $LastIdInsert;
   	   }catch (ErrorException $ex){
   			echo "MessageError".$ex->getMessage();
   	   }
   	
   }
   public function updateRefer($datas,$GetEquipementtypeLastId){
   	try{
   		$idEquiptement=$this->_equipementtyperef->getAdapter()->quoteInto('type_id= ? ',$GetEquipementtypeLastId) ;
   		$this->_equipementtyperef->update($datas,$idEquiptement);
   	}catch(ErrorException $e){
   		echo "Message:".$e->getMessage();
   	}
   	
   }
   public  function updateTypeEquipement($dataTypeEquipement,$idType){
   	try{
   		$idType=$this->_equipementtyperef->getAdapter()->quoteInto(' type_id= ? ',$idType) ;
   		$this->_equipementtyperef->update($dataTypeEquipement, $idType);
   	}catch (ErrorException $ex){
   		echo "MessageError".$ex->getMessage();
   	}
   }
   public function selectAllTypeEquipement(){
   	try{
   		$data=array ('*');
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$select = $db->select ()->from (array('eqt'=>'equipementtyperef') )
   		->joinInner (array('for'=>'fournisseur'),'eqt.fac_id= for.Fou_Id' )
   		->order('type_id DESC');
   		$rows = $db->fetchAll ($select );
   		return (! empty ( $rows )) ? $rows : null;
   	}catch(ErrorException $e){
   		echo "Message:".$e->getMessage();
   	}
   
   }
   
   public function SelectOverViewTypeEquipement($ids){
   	try{
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$select = $db->select ()->from (array('eqt'=>'equipementtyperef') )
   		->joinInner (array('for'=>'fournisseur'),'eqt.fac_id= for.Fou_Id' )
   		->where('eqt.type_id=?',$ids);
   		$rows = $db->fetchAll ($select );
   		return (!empty($rows))?$rows:null;
   		throw new Exception('Can not select!');
   	}catch (ErrorException $e){
   		echo "Message:".$e->getMessage();
   	}
   }
   public function deletedEquipementType($ids){
   	try{
   		$id= $this->_equipementtyperef->getAdapter ()->quoteInto ( 'type_id = ?', $ids );
   		$idParent = $this->_equipementtyperef->delete ( $id);
   		$sucess =   ($idParent)? TRUE : FALSE;
   		return $sucess;
   	}catch (ErrorException $ex){
   		echo "MessageError".$ex->getMessage();
   	}
   }
   public function multipledeleteEquipementType($ids){
   	for($i=0;$i<count($ids);$i++){
   		$idProcess =$ids[$i];
   		$whereMulti = $this->_equipementtyperef->getAdapter ()->quoteInto ( 'type_id = ?', $idProcess );
   		$this->_equipementtyperef->delete ( $whereMulti );
   	}
   }
   public function selectFactory(){
   	try{
   		
   		$data=array ('Fou_Id','Fou_Name');
   		$sql = $this->_tableFactory->select ()->from ( $this->_tableFactory,$data );
   		$getRows = $this->_tableFactory->fetchAll( $sql )->toArray ();
   		return (! empty ( $getRows )) ? $getRows : null;
   	}catch(ErrorException $e){
   		echo "Message:".$e->getMessage();
   	}
   
   }
   
   public function SelectEditTypeEquipement($idType){
   	try{
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$select = $db->select ()->from (array('eqt'=>'equipementtyperef') )
   		->joinInner (array('for'=>'fournisseur'),'eqt.fac_id= for.Fou_Id')
   		->where('eqt.type_id=?',$idType);
   		$rows = $db->fetchAll ($select );
   		return (!empty($rows))?$rows:null;
   		throw new Exception('Can not select!');
   			
   	}catch (ErrorException $e){
   		echo "Message:".$e->getMessage();
   	}
   }
   
   
   /* develop: dim sidet
    
    * destroy object ,close connection,close session,... */
   /*Starting of Equipement*/
   public function selectTypeTequipement(){
   	try{
   		$data=array ('type_id','type_name');
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$select = $db->select ()->from (array('eqt'=>'equipementtyperef'),$data );
   		$rows = $db->fetchAll ($select );
   		return (! empty ( $rows )) ? $rows : null;
   	}catch(ErrorException $e){
   		echo "Message:".$e->getMessage();
   	}
   }
   public function addNewEquipement($getDataEquipement){
   	try{
   		$sucess = TRUE;
   		$sucess = $this->_equipementref->insert($getDataEquipement);
   		if($sucess==TRUE){
   			return TRUE;
   		}else{
   			return  FALSE;
   		}
   	}catch(ErrorException $ex){
   		echo "Message say:".$ex->getMessage();
   	}
   }
   
   public function UpdateEquipement($getDataEquipement,$ids){
   	try{
   		$sucess = TRUE;
   		$idequit=$this->_equipementref->getAdapter()->quoteInto(' equip_id= ? ',$ids) ;
   		$sucess = $this->_equipementref->update($getDataEquipement, $idequit);
   		if($sucess==TRUE){
   			return TRUE;
   		}else{
   			return FALSE;
   		}
   	}catch (ErrorException $ex){
   		echo "MessageError".$ex->getMessage();
   	}
   }
   
   public function getallrecordEquipement(){
   	try{
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$select = $db->select ()->from (array('eqRef'=>'equipementref') )
   		->joinInner (array('eqt'=>'equipementtyperef'),'eqRef.type_id= eqt.type_id')
   		->joinInner (array('fac'=>'fournisseur'),'fac.Fou_Id= eqt.fac_id')
   		->order('equip_id DESC');
   		$rows = $db->fetchAll ($select );
   		return (!empty($rows))?$rows:null;
   	}catch(ErrorException $ex){
   		echo "Message say:".$ex->getMessage();
   	}
   }
   public function selectEquipementForUpdate($ids){
   	try{
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$select = $db->select ()->from (array('eqRef'=>'equipementref') )
   		->joinInner (array('eqt'=>'equipementtyperef'),'eqRef.type_id= eqt.type_id')
   		->joinInner (array('fac'=>'fournisseur'),'fac.Fou_Id= eqt.fac_id')
   		->where('eqRef.equip_id=?',$ids);
   		$rows = $db->fetchAll ($select );
   		return (!empty($rows))?$rows:null;
   	}catch(ErrorException $ex){
   		echo "Message say:".$ex->getMessage();
   	}
   }
   public function getrecordOverviewEquip($ids){
   	try{
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$select = $db->select ()->from (array('eqRef'=>'equipementref') )
   		->joinInner (array('eqt'=>'equipementtyperef'),'eqRef.type_id= eqt.type_id')
   		->joinInner (array('fac'=>'fournisseur'),'fac.Fou_Id= eqt.fac_id')
   		->where('eqRef.equip_id=?',$ids);
   		$rows = $db->fetchAll ($select );
   		return (!empty($rows))?$rows:null;
   	}catch(ErrorException $ex){
   		echo "Message say:".$ex->getMessage();
   	}
   }
   
   public function deleteEquipement($ids){
   	try{
   		$sucess = TRUE;
   		$idequit=$this->_equipementref->getAdapter()->quoteInto(' equip_id= ? ',$ids) ;
   		$sucess = $this->_equipementref->delete($idequit);
   		if($sucess==TRUE){
   			return TRUE;
   		}else{
   			return FALSE;
   		}
   	}catch (ErrorException $ex){
   		echo "MessageError".$ex->getMessage();
   	}
   }
   public function multipledeleteEquipement($getIDS){
   	try{
   		for($i=0;$i<count($getIDS);$i++){
   			$ids = $getIDS[$i];
   		 $idequit=$this->_equipementref->getAdapter()->quoteInto(' equip_id= ? ',$ids) ;
   		 $this->_equipementref->delete($idequit);
   		}
   	}catch (ErrorException $ex){
   		echo "MessageError".$ex->getMessage();
   	}
   }
   /*Basic search equipement*/
   public function basicsearchEquipement($basicsearch){
   	try{
   		$db = Zend_Db_Table::getDefaultAdapter ();
   		$select = $db->select ()->from (array('eqRef'=>'equipementref') )
   		->joinInner (array('eqt'=>'equipementtyperef'),'eqRef.type_id= eqt.type_id')
   		->joinInner (array('fac'=>'fournisseur'),'fac.Fou_Id= eqt.fac_id')
   		->where('eqRef.equip_name LIKE ?',"%{$basicsearch}%");
   		$rows = $db->fetchAll ($select );
   		return (!empty($rows))?$rows:null;
   	}catch(ErrorException $ex){
   		echo "Message say:".$ex->getMessage();
   	}
   }
  
   /*Ending of Equipement*/
   public function __destruct(){
   	$this->_refCod ;
   	$this->_tableFactory;
   	$this->_equipementtyperef;
   	
   }
   /* end of destroy object ,close connection,close session,... */
}