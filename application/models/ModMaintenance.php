<?php
class Application_Model_ModMaintenance {
    public $TblCatmaintenance = NULL;
	public $TblRefmaintenance = NULL;
	public $TblMaintenance = NULL;
	public $TblCatquiptmentCatmaintenance = NULL;
    public $TblCatEquipment=NULL;
    public function __construct(){
    	$this->TblMaintenance 		         = new  Application_Model_DbTable_maintenance();
    	$this->TblCatmaintenance             = new  Application_Model_DbTable_catmaintenance();
    	$this->TblRefmaintenance             = new  Application_Model_DbTable_refmaintenance();
		$this->TblCatquiptmentCatmaintenance = new  Application_Model_DbTable_catequipmentcatmaintenance();
        $this->TblCatEquipment               = new  Application_Model_DbTable_equipementtyperef();
    	
    }
    public function getCateQuipment(){
        try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ('*')->from (array("equipementtyperef"));
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
    }
    public function getFournitureId($Fid){
         try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ('*')->from (array("equipementtyperef"))
            ->where('type_id=?',$Fid);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
    }
    public function updateJunctionTableCatmain($ArrMixCatequipmentCatmaintenance,$catIdEquiptment){
        try{
		$where = $this->TblCatquiptmentCatmaintenance->getAdapter()->quoteInto('catmain_id= ? ',$catIdEquiptment);
		$this->TblCatquiptmentCatmaintenance->update($ArrMixCatequipmentCatmaintenance,$where);
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
		
    }
	public function GetRecordMaintenance(){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('main'=>'maintenance') )
    		->joinInner (array('catMain'=>'catmaintenance'),'main.catmain_id= catMain.catmain_id' )
			->order('main.main_id DESC');
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	public function MaintenanceOverview($id){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('main'=>'maintenance') )
    		->joinInner (array('catMain'=>'catmaintenance'),'main.catmain_id= catMain.catmain_id' )
			->order('main_id DESC')
			->where('main.main_id=?',$id);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	public function GetMaintenanceService($id){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('main'=>'maintenance') )
    		->joinInner (array('refMain'=>'refmaintenance'),'main.main_id= refMain.main_id' )
			->where('main.main_id=?',$id);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	public function addCatMaintenance($ArrCatMaintenance){
		try{
		$this->TblCatmaintenance->insert($ArrCatMaintenance);
		$LastIdCatMaintenance = $this->TblCatmaintenance->getAdapter ()->lastInsertId ();
		return $LastIdCatMaintenance;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
		
	}
	public function addMaintenance($ArrMaintenance){
		try{
		$this->TblMaintenance->insert($ArrMaintenance);
		$LastIdMaintenance = $this->TblMaintenance->getAdapter ()->lastInsertId ();
		return $LastIdMaintenance;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
		
	}
	public function addServiceMaintenance($arAllservice){
		try{
			
				$this->TblRefmaintenance->insert($arAllservice);
			
			
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
    public function addJunctionTableCatmain($ArrMixCatequipmentCatmaintenance){
		try{
			$this->TblCatquiptmentCatmaintenance->insert($ArrMixCatequipmentCatmaintenance);
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	public function selectEditMaintenance($id){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('main'=>'maintenance') )
    		->joinInner (array('catMain'=>'catmaintenance'),'main.catmain_id= catMain.catmain_id' )
			->order('main_id DESC')
			->where('main.main_id=?',$id);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	public function selectEditMaintenanceService($id){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from (array('Main'=>'maintenance') )
    		->joinInner (array('refMain'=>'refmaintenance'),'Main.main_id= refMain.main_id' )
			->where('Main.main_id=?',$id);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	public function updateCatMaintenance($ArrCatMaintenance,$hid){
		try{
		$where = $this->TblCatmaintenance->getAdapter()->quoteInto('catmain_id= ? ',$hid);
		$this->TblCatmaintenance->update($ArrCatMaintenance,$where);
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
		
	}
	public function updateMaintenance($ArrMaintenance,$hid){
		try{
			
		$where = $this->TblMaintenance->getAdapter()->quoteInto('main_id= ? ',$hid);
		$this->TblMaintenance->update($ArrMaintenance,$where);
			
			
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	public function updateMaintenanceService($arAllservice){
		try{
			
				$this->TblRefmaintenance->insert($arAllservice);
			
			
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	public function deleteMaintenance($id){
		try{
			$sucess = TRUE;
			$where = $this->TblMaintenance->getAdapter()->quoteInto('main_id= ? ',$id);
			$wherecate = $this->TblCatmaintenance->getAdapter()->quoteInto('catmain_id= ? ',$id);
			$this->TblRefmaintenance->delete($where);
			$this->TblCatmaintenance->delete($wherecate);
			$sucess= $this->TblMaintenance->delete($where);
			if($sucess==TRUE){
				return TRUE;
			}else{
				return FALSE;
			}
			
		}catch(ErrorException $ex){
			echo "Message".$ex->getMessage();
		}
	}
	public function multipleDelete($id){
		try{
		 for($i=0;$i<count($id);$i++){
		 	$where = $this->TblMaintenance->getAdapter()->quoteInto('main_id= ? ',$id[$i]);
			$wherecate = $this->TblCatmaintenance->getAdapter()->quoteInto('catmain_id= ? ',$id[$i]);
			$this->TblRefmaintenance->delete($where);
			$this->TblCatmaintenance->delete($wherecate);
		    $this->TblMaintenance->delete($where);
		 }	
		}catch(ErrorException $ex){
			echo "Message".$ex->getMessage();
		}
	}
	public function serviceMaintenanceDelete($hid){
		try{
			$where = $this->TblRefmaintenance->getAdapter()->quoteInto('main_id= ? ',$hid);
			$this->TblRefmaintenance->delete($where);
			
		}catch(ErrorException $ex){
			echo "Message".$ex->getMessage();
		}
	}
}