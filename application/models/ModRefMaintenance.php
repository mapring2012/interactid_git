<?php
class Application_Model_ModRefMaintenance {
	public $TblCatmaintenance = NULL;
	public $TblRefmaintenance = NULL;
	public $TblMaintenance = NULL;
    public function __construct(){
    	$this->TblMaintenance 		= new  Application_Model_DbTable_maintenance();
    	$this->TblCatmaintenance 	= new  Application_Model_DbTable_catmaintenance();
    	$this->TblRefmaintenance 	= new  Application_Model_DbTable_refmaintenance();
    	
    }
    public function add($arra){
    	for ($i=0;$i<count($arra);$i++){
    		echo $arra[$i]."</br>";
    		
    	}
    }

}