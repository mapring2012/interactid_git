<?php
class Application_Model_ModCatMaintenance {

    public $_catmaintenance= null;
    
	public function __construct() {
        $this->_catmaintenance= new Application_Model_DbTable_catmaintenance(); 
    }
    public function insertCategoryMaintenance($addCategory){
    	try{
    		$this->_catmaintenance->insert($addCategory);
    		$LastIdInsert = $this->_catmaintenance->getAdapter ()->lastInsertId ();
    		return $LastIdInsert;
    	}catch(ErrorException $ex){
    		echo "Message say:".$ex->getMessage();
    	}
    }
    
    public function UpdateCategoryMaintenance($addCateMaintenace,$hiddenid){
    	try{
    		$idEdit=$this->_catmaintenance->getAdapter()->quoteInto('catmain_id= ? ',$hiddenid) ;
    		$this->_catmaintenance->update($addCateMaintenace, $idEdit);
    	}catch(ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
    }
    
    public function selectEditEquipement($id){
    	try{
    		$db = Zend_Db_Table::getDefaultAdapter ();
    		$select = $db->select ()->from (array('catemain'=>'catmaintenance') )
    		///->joinInner (array('typeEquipt'=>'equipementtyperef'),'cquipt.Type_id= typeEquipt.type_id' )
    		->where('catemain.catmain_id=?',$id);
    		$rows = $db->fetchAll ($select );
    		return (!empty($rows))?$rows:null;
    			
    	}catch (ErrorException $e){
    		echo "Message:".$e->getMessage();
    	}
    }
    public function selectAllCategoryMaintenanceName (){
    	try{
    		$db = Zend_Db_Table::getDefaultAdapter ();
    		$selectfieldName=array('catmain_name');
    		$select = $db->select ()->from (array('catemain'=>'catmaintenance'),$selectfieldName );
    		//->joinInner (array('typeEquipt'=>'equipementtyperef'),'cquipt.Type_id= typeEquipt.type_id' )\
    		$rows = $db->fetchAll ($select );
    		return (!empty($rows))?$rows:null;
    	}catch(ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
    }
    public function selectAllCategoryMaintenance (){
    	try{
    		$db = Zend_Db_Table::getDefaultAdapter ();
    		$select = $db->select ()->from (array('catemain'=>'catmaintenance') );
    		//->joinInner (array('typeEquipt'=>'equipementtyperef'),'cquipt.Type_id= typeEquipt.type_id' )\
    		$rows = $db->fetchAll ($select );
    		return (!empty($rows))?$rows:null;
    	}catch(ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
    }
    
    public function deleteCategoryMaintenance($ids){
    	try{
    		$sucess = TRUE;
    		$idwhere = $this->_catmaintenance->getAdapter ()->quoteInto ( 'catmain_id = ?', $ids );
    		$sucess = $this->_catmaintenance->delete ( $idwhere );
    		if($sucess==TRUE){
    			return TRUE;
    		}else{
    			return  FALSE;
    		}
    	}catch(ErrorException $ex){
    		echo "Message say:".$ex->getMessage();
    	}
    }
    
    public function deleteCategoryMaintenancetMulti($id){
    	try{
    		$sucess = TRUE;
    		for($i=0;$i<count($id);$i++){
    			$idwhere = $this->_catmaintenance->getAdapter ()->quoteInto ( 'catmain_id = ?', $id[$i] );
    			$sucess = $this->_catmaintenance->delete ( $idwhere );
    		}
    		return $sucess;
    	}catch(ErrorException $ex){
    		echo "Message say:".$ex->getMessage();
    	}
    }
    
    
    
   
    
public function selectAllDataForOrganismeMenu() {
		$db = Zend_Db_Table::getDefaultAdapter ();
		$select = $db->select ()-> distinct()->from (array('com'=>'company'),array('Com_Id','Com_Name'));
		$rows = $db->fetchAll ( $select );
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectAllFromsite(){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$data=array('site_Id','site_Name');
			$select = $db->select ()->from (array('sites'=>'site'),$data);
			$rows = $db->fetchAll ($select);
			return (!empty($rows))?$rows:null;
		}catch (ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	public function selectAllFactory(){
		try{
			$data=array('Fou_Id','Fou_Name');
			$sql = $this->_tableFactory->select ()->from ( $this->_tableFactory,$data );
			$getRows = $this->_tableFactory->fetchAll( $sql )->toArray ();
			return (! empty ( $getRows )) ? $getRows : null;
		}catch(ErrorException $e){
			echo "Message:".$e->getMessage();
		}
	
	}
	public function selectAllTypeEquipement(){
		try{
			$data=array('type_id','type_name');
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('eqt'=>'equipementtyperef'),$data );
			$rows = $db->fetchAll ($select );
			return (! empty ( $rows )) ? $rows : null;
		}catch(ErrorException $e){
			echo "Message:".$e->getMessage();
		}
		 
	}
	public function SelectOverviewEquipement ($id){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('cquipt'=>'cequipement') )
			->joinInner (array('typeEquipt'=>'equipementtyperef'),'cquipt.Type_id= typeEquipt.type_id' )
			->joinInner (array('sites'=>'site'),'cquipt.site_Id= sites.site_Id' )
			->joinInner (array('com'=>'company'),'cquipt.Org_Id= com.Com_Id' )
			->joinInner (array('four'=>'fournisseur'),'cquipt.Fou_Id= four.Fou_Id' )
			->where('cquipt.equip_Id=?',$id);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	
	public function basicSearch($SearchName){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('cquipt'=>'cequipement') )
			->joinInner (array('typeEquipt'=>'equipementtyperef'),'cquipt.Type_id= typeEquipt.type_id' )
		    ->joinInner (array('sites'=>'site'),'cquipt.site_Id= sites.site_Id' )
		    ->joinInner (array('com'=>'company'),'cquipt.Org_Id= com.Com_Id' )
		    ->joinInner (array('four'=>'fournisseur'),'cquipt.Fou_Id= four.Fou_Id' )
			->where('cquipt.equip_Name LIKE ?',"%{$SearchName}%");
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
			throw new Exception('Can not select!');
		}catch (ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	
	public function searchAvance($dataAdvanceSearch){
		try {
			list(
						$searchname,
						$searchFournisseur,
						$searchReference,
						$searchDateCommiss,
						$searchDateManufacture,
			)=$dataAdvanceSearch;
			if($searchname !=''){$searchname = " AND cquipt.equip_Name LIKE '%".$searchname."%'";}else {$searchname = 'AND cquipt.equip_Name  LIKE "*"';}
			if($searchFournisseur !=''){$searchFournisseur = "OR four.Fou_Name  LIKE '%".$searchFournisseur."%'";}else {$searchFournisseur = '';}
			if($searchReference !=''){$searchReference = "OR cquipt.equip_Reference  LIKE '%".$searchReference."%'";}else {$searchReference = '';}
			if($searchDateCommiss !=''){$searchDateCommiss = "OR cquipt.equip_Name  LIKE '%".$searchDateCommiss."%'";}else {$searchDateCommiss = '';}
			if($searchDateManufacture !=''){$searchDateManufacture = "OR cquipt.equip_Name LIKE '%".$searchDateManufacture."%'";}else {$searchDateManufacture = '';}
			
			$db = Zend_Db_Table::getDefaultAdapter ();
			$status =1;
			$select = $db->select ()->from (array('cquipt'=>'cequipement') )
			->joinInner (array('typeEquipt'=>'equipementtyperef'),'cquipt.Type_id= typeEquipt.type_id' )
			->joinInner (array('sites'=>'site'),'cquipt.site_Id= sites.site_Id' )
			->joinInner (array('com'=>'company'),'cquipt.Org_Id= com.Com_Id' )
			->joinInner (array('four'=>'fournisseur'),'cquipt.Fou_Id= four.Fou_Id' )
			->where("cquipt.status=$status $searchname $searchname $searchFournisseur $searchReference $searchDateCommiss $searchDateManufacture ");
			$rows = $db->fetchAll ( $select );
			return (! empty ( $rows )) ? $rows : null;
		}catch(ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	
	
	public function searchFormAddNew($searchname){
		try{
		  $db = Zend_Db_Table::getDefaultAdapter ();
            if(!empty($searchname))
            {
            $select = $db->select ()->from (array('eqRef'=>'equipementref') )
			->joinInner (array('eqt'=>'equipementtyperef'),'eqRef.type_id= eqt.type_id')
			->joinInner (array('fac'=>'fournisseur'),'fac.Fou_Id= eqt.fac_id')
			->where('eqRef.equip_name LIKE ?',"%{$searchname}%");
            }
            else
            {
            $select = $db->select ()->from (array('eqRef'=>'equipementref') )
			->joinInner (array('eqt'=>'equipementtyperef'),'eqRef.type_id= eqt.type_id')
			->joinInner (array('fac'=>'fournisseur'),'fac.Fou_Id= eqt.fac_id');			
            }
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message say:".$ex->getMessage();
		}
	}
	
	public function updateRefer($datas,$GetEquipementtypeLastId){
		try{
			$idEquiptement=$this->TableEquipement->getAdapter()->quoteInto('equip_Id= ? ',$GetEquipementtypeLastId) ;
			$this->TableEquipement->update($datas,$idEquiptement);
		}catch(ErrorException $e){
			echo "Message:".$e->getMessage();
		}
	
	}
	
	

	
	

	
	public function __destruct(){
		
		
	}
}
    

