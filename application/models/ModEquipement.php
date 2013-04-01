<?php 
/**
 * @Developer  Doeun,sidet
 * @Module     Type Equipement
 * @Descript Add,Edit,Update and select
 * @company   Mapring
 * @copyright 2013
 */
class Application_Model_ModEquipement{
	public $TableEquipement;
	private $_tblrefcodes;
	private $_tblsite;
	private $_tblcompany;
	private $_tableFactory;
	private $_equipementtyperef;
	private $_equipementref;
    public $TblCatquiptmentCatmaintenance = NULL;
    public $TblCatmaintenance = NULL;
    public $TblRefmaintenance = NULL;
	
	public function __construct(){
		$this->TableEquipement = new Application_Model_DbTable_cequipement();
		$this->_tblrefcodes = new Application_Model_DbTable_refcodes ();
		$this->_tblsite=new Application_Model_DbTable_site();
		$this->_tblcompany=new Application_Model_DbTable_company();
		$this->_tableFactory = new Application_Model_DbTable_fournisseur();
		$this->_equipementtyperef = new Application_Model_DbTable_equipementtyperef();
		$this->_equipementref     = new Application_Model_DbTable_equipementref();
		$this->TblCatquiptmentCatmaintenance = new Application_Model_DbTable_catequipmentcatmaintenance();
        $this->TblCatmaintenance             = new  Application_Model_DbTable_catmaintenance();
        $this->TblRefmaintenance             = new  Application_Model_DbTable_refmaintenance();
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
	
	public function GetAllRecordEquipement (){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('cquipt'=>'cequipement') )
			->joinInner (array('typeEquipt'=>'equipementtyperef'),'cquipt.Type_id= typeEquipt.type_id' )
		    ->joinInner (array('sites'=>'site'),'cquipt.site_Id= sites.site_Id' )
		    ->joinInner (array('com'=>'company'),'cquipt.Org_Id= com.Com_Id' )
		    ->joinInner (array('four'=>'fournisseur'),'cquipt.Fou_Id= four.Fou_Id' )
			->order('cquipt.equip_id DESC');
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
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
	
	public function addNewEquipement($getDataEquipement){
		try{
		
			$this->TableEquipement->insert($getDataEquipement);
			$LastIdInsert = $this->TableEquipement->getAdapter ()->lastInsertId ();
			return $LastIdInsert;
		}catch(ErrorException $ex){
			echo "Message say:".$ex->getMessage();
		}
	}
	public function selectEditEquipement($id){
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
			
		}catch (ErrorException $e){
			echo "Message:".$e->getMessage();
		}
	}
	public function UpdateEquipement($getDataEquipement,$id){
		try{
		$idEdit=$this->TableEquipement->getAdapter()->quoteInto('equip_Id= ? ',$id) ;
		$this->TableEquipement->update($getDataEquipement, $idEdit);
		}catch(ErrorException $ex){
			echo "Message say:".$ex->getMessage();
		}
	}
	
	public function deleteEquipement($ids){
		
		try{
			$sucess = TRUE;
			$idwhere = $this->TableEquipement->getAdapter ()->quoteInto ( 'equip_Id = ?', $ids );
		    $sucess = $this->TableEquipement->delete ( $idwhere );
			if($sucess==TRUE){
				return TRUE;
			}else{
				return  FALSE;
			}
		}catch(ErrorException $ex){
			echo "Message say:".$ex->getMessage();
		}
	}
    

    public function maintenanceView($catid){
        try{
            $db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('catmaincatequ'=>'catequipmentcatmaintenance') )
			//->joinInner (array('rfmain'=>'refmaintenance'),'rfmain.catmain_id= catmaincatequ.catmain_id' )
		    ->joinInner (array('catmain'=>'catmaintenance'),'catmain.catmain_id= catmaincatequ.catmain_id' )
			->where('catmaincatequ.catequi_id =?',$catid);
			  $rows = $db->fetchAll ($select );
            return (!empty($rows))?$rows:null;
        }catch(ErrorException $ex){
            echo "Message".$ex->getMessage();
        }
    }
    
    public function level1($catm)
    {
        $db = Zend_Db_Table::getDefaultAdapter ();
        $selects = $db->select ()->from (array('refmain'=>'refmaintenance') )
        ->where('catmain_id=?',$catm)
        ->where('refmain_level=?',1);
         $row = $db->fetchAll ($selects );
         //var_dump($row);
        return (!empty($row))?$row:null;
    }
    public function level2($catm)
    {
        $db = Zend_Db_Table::getDefaultAdapter ();
        $selects = $db->select ()->from (array('refmain'=>'refmaintenance') )
        ->where('catmain_id=?',$catm)
        ->where('refmain_level=?',2);
         $row = $db->fetchAll ($selects );
         //var_dump($row);
        return (!empty($row))?$row:null;
    }
   
    public function getCatEq($catid){
        try{
            $db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('eqref'=>'equipementtyperef') )			
			->where('type_id =?',$catid);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
        }catch(ErrorException $ex){
            echo "Message".$ex->getMessage();
        }
    }
    
    
	public function deleteEquipementMulti($id){
		try{
			$sucess = TRUE;
			for($i=0;$i<count($id);$i++){
			$idwhere = $this->TableEquipement->getAdapter ()->quoteInto ( 'equip_Id = ?', $id[$i] );
			$sucess = $this->TableEquipement->delete ( $idwhere );
			}
			return $sucess;
		}catch(ErrorException $ex){
			echo "Message say:".$ex->getMessage();
		}
	}
	public function __destruct(){
		$this->TableEquipement;
		$this->_tblrefcodes;
		$this->_tblsite;
		$this->_tblcompany;
		$this->_tableFactory;
		$this->_equipementtyperef;
		$this->_equipementref;
		
	}
}