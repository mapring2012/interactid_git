<?php
require_once 'Zend/function.php';
require_once 'Zend/Controller/Plugin/Abstract.php';
class MaintenanceadminController extends Zend_Controller_Action {
	public $ModMaintenance = NULL;
	public function init() {
	    $this->view->controller = $this->_request->getParam ( 'controller' );
		$this->view->action = $this->_request->getParam ( 'action' );
		$this->GetModelSite = new Application_Model_ModSite ();
		$this->_Categories = new Application_Model_ModCatTerm ();
		$this->ModMaintenance = new Application_Model_ModMaintenance();
		$parent_id = $this->_getParam ( 'controller' );
		$this->view->secondSideBar = $this->_Categories->showCateParent ( $parent_id );
		$this->_helper->ajaxContext->addActionContext('deleteSite', 'json')->initContext();
	}
	public function indexAction() {
		if(!$this->CheckTransactionUser())
		{
			$this->redirectToIndex();
		}
		$success=$this->_getParam ( 'success' );
		$error = $this->_getParam ( 'error' );
		if($success=='add'){
			$message='Maintenance has been added successfully !';
			$this->view->success=$message;
		}else if($success=='update'){
			$message='Maintenance has been updated successfully !';
			$this->view->success=$message;
		}else if($success=='delete'){
			$message='Maintenance has been deleted successfully !';
			$this->view->success=$message;
        }
		if(!$this->CheckTransactionUser())
	     {
		  $this->redirectToIndex();
	     }else{
		 	$GetRecordMaintenance = $this->ModMaintenance->GetRecordMaintenance();
			$this->view->getMaintenace = $GetRecordMaintenance;
		 }
	}
   public function overviewAction(){
   		try{
		if(!$this->CheckTransactionUser())
		{
			$this->redirectToIndex();
		}else{
			$lan = $this->_getParam ( 'lang' );
			$id = $this->_getParam ( 'id' );
			$actions = $this->_getParam ( 'actions' );
			if($actions=="overview"){
				$MaintenanceOverview = $this->ModMaintenance->MaintenanceOverview($id);
			    $this->view->getMaintenaceOverview = $MaintenanceOverview;
				$MaintenanceService = $this->ModMaintenance->GetMaintenanceService($id);
			    $this->view->MaintenanceService = $MaintenanceService;
			}
		}
		}catch(ErrorException $ex){
		echo "Message".$ex->getMessage();
		}
     }
   public function addAction()
   {
   	try{
		if(!$this->CheckTransactionUser())
	{
		$this->redirectToIndex();
	}
	else
	{
        $getCateQuipment = $this->ModMaintenance->getCateQuipment();
        $this->view->getCateQuipment = $getCateQuipment;
		$ArrCatMaintenance	 = array();
		$ArrMaintenance		 = array();	
		$ArrMixCatequipmentCatmaintenance = array();			
		$GetPost = $this->getRequest();
		$lan = $this->_getParam ( 'lang' );
        $step= ($this->_getParam ( 'step' )) ? $this->_getParam ( 'step' ) : '';
         if($step==2){
            $this->view->tabActive=$step;
            
         }
		if($GetPost->getPost('btnCatMain'))
		  {
            $Fid = $GetPost->getPost('txtCategoryEquipment');
            $getFournitureId = $this->ModMaintenance->getFournitureId($Fid);
            foreach($getFournitureId as $valuesFid){
                $valuesFids = $valuesFid['fac_id'];
            }
        
			$ArrCatMaintenance = array(
			  'catmain_name'=>$GetPost->getPost('txtCatMaintenanceName'),
			  'catmain_description'=>$GetPost->getPost('txtDescriptionCat'), 
			);
			$idCatMaintenance = $this->ModMaintenance->addCatMaintenance($ArrCatMaintenance); 
			$ArrMixCatequipmentCatmaintenance = array(
			 'catmain_id'=>$idCatMaintenance,
             'catequi_id'=>$GetPost->getPost('txtCategoryEquipment'),
             'four_id'=>$valuesFids
			);
			$this->ModMaintenance->addJunctionTableCatmain($ArrMixCatequipmentCatmaintenance);
            $this->_redirect ( $lan . '/maintenanceadmin/add?success=add&step=2' );
            
           } 
            
            
          if($GetPost->getPost('btnMain'))
		  {  
			$ArrMaintenance = array(
			  'main_name'=>$GetPost->getPost('txtmaintenance'),
			  'main_description'=>$GetPost->getPost('txtdescriptionMain'),
			  'catmain_id'=>$idCatMaintenance,
			  'main_createdate'=>date('d-m-Y'),
			  'main_type_usage'=>$GetPost->getPost('UsageMaintenance'),
			  'main_type'=>$GetPost->getPost('MaintenanceType')
			);
			$idMaintenance = $this->ModMaintenance->addMaintenance($ArrMaintenance);
            $this->_redirect ( $lan . '/maintenanceadmin/index?success=add&step=2' );
        }
            
                if($GetPost->getPost('btnMain'))
		  { 
			    $ArrLabelName = $GetPost->getPost('txtLabel');
				$order = $this->getRequest()->getPost('txtOrderby');
				$code  = $this->getRequest()->getPost('txtCode');
				$description = $this->getRequest()->getPost('txtDescription');
				//echo count($ArrLabelName);die();
				for($i=0;$i<count($ArrLabelName);$i++){
				$arAllservice = array(
				 'refmain_order'=>$order[$i],
				 'refmain_code'=>$code[$i],
				 'refmain_label'=>$ArrLabelName[$i],
				 'refmain_description'=>$description[$i],
				 'refmain_createdate'=>date('d-m-Y'),
				 'main_id'=>$idMaintenance,
				 'catmain_id'=>$idCatMaintenance,
                 'refmain_level'=>$GetPost->getPost('MaintenanceType')
				);
			  $this->ModMaintenance->addServiceMaintenance($arAllservice );
              
			
			 }
            $this->_redirect ( $lan . '/maintenanceadmin/index?success=add' );
        }		 

	}
	}catch(ErrorException $ex){
		echo "Message".$ex->getMessage();
	}
   	
   }
   public function editAction(){
   	try{
	 if(!$this->CheckTransactionUser()){
			$this->redirectToIndex();
		}else{
            $getCateQuipment = $this->ModMaintenance->getCateQuipment();
            $this->view->getCateQuipment = $getCateQuipment;
			$GetPost = $this->getRequest();
			$lan = $this->_getParam ( 'lang' );
			$id = $this->_getParam ( 'id' );
			$actions = $this->_getParam ( 'actions' );
			$submitButtonEdit = $this->getRequest()->getPost('UpdateMaintenance');
			$hid = $this->getRequest()->getPost('hddid');
			if($submitButtonEdit){
			
            $Fid = $GetPost->getPost('txtCategoryEquipment');
            $getFournitureId = $this->ModMaintenance->getFournitureId($Fid);
            $catIdEquiptment = $GetPost->getPost('hddidcat');
            foreach($getFournitureId as $valuesFid){
                $valuesFids = $valuesFid['fac_id'];
            }
				$ArrCatMaintenance = array(
				'catmain_name'=>$GetPost->getPost('txtCatMaintenanceName'),
				'catmain_description'=>$GetPost->getPost('txtDescriptionCat'), 
				);
			   $this->ModMaintenance->updateCatMaintenance($ArrCatMaintenance,$hid);
               $ArrMixCatequipmentCatmaintenance = array(
             'catequi_id'=>$GetPost->getPost('txtCategoryEquipment'),
             'four_id'=>$valuesFids
			);
			$this->ModMaintenance->updateJunctionTableCatmain($ArrMixCatequipmentCatmaintenance,$catIdEquiptment);
			 $ArrMaintenance = array(
			  'main_name'=>$GetPost->getPost('txtmaintenance'),
			  'main_description'=>$GetPost->getPost('txtdescriptionMain'),
			  'main_createdate'=>date('d-m-Y'),
			  'main_type_usage'=>$GetPost->getPost('UsageMaintenance'),
			  'main_type'=>$GetPost->getPost('MaintenanceType')
			);
			$this->ModMaintenance->updateMaintenance($ArrMaintenance,$hid);
			    $ArrLabelName = $GetPost->getPost('txtLabel');
				$order = $this->getRequest()->getPost('txtOrderby');
				$code  = $this->getRequest()->getPost('txtCode');
				$description = $this->getRequest()->getPost('txtDescription');
				$this->ModMaintenance->serviceMaintenanceDelete($hid);
				for($i=0;$i<count($ArrLabelName);$i++){
				$arAllservice = array(
				 'refmain_order'=>$order[$i],
				 'refmain_code'=>$code[$i],
				 'refmain_label'=>$ArrLabelName[$i],
				 'refmain_description'=>$description[$i],
				 'refmain_modifdate'=>date('d-m-Y'),
				 'main_id'=>$hid,
				 'catmain_id'=>$GetPost->getPost('hddidcat'),
                 'refmain_level'=>$GetPost->getPost('MaintenanceType')
				);
				$this->ModMaintenance->updateMaintenanceService($arAllservice);
				}
				$this->_redirect($lan . '/maintenanceadmin/index?success=update');
				 exit();
			 
			}else if($actions=="edit"){
				$getmaintenanceEdit = $this->ModMaintenance->selectEditMaintenance($id);
				$this->view->getmaintenanceEdit=$getmaintenanceEdit;
				$getmaintenanceEditService = $this->ModMaintenance->selectEditMaintenanceService($id);
				$this->view->getmaintenanceEditService=$getmaintenanceEditService;
			}
		}
	}catch(ErrorException $ex){
		echo "Message".$ex->getMessage();
	}
   	
   }
   
  
   public function deleteAction(){
   	   try{
	   	if(!$this->CheckTransactionUser()){
			$this->redirectToIndex();
		}else{
			$lan = $this->_getParam ( 'lang' );
			$id = $this->_getParam ( 'id' );
			$actions = $this->_getParam ( 'actions' );
			$MultipleAction = $this->getRequest()->getPost('BtnDelMaintenance');
			$MultipleSelectDelete = $this->getRequest()->getPost('multiAction');
			if($actions=="delete"){
				$sucessDelete = $this->ModMaintenance->deleteMaintenance($id);
				if($sucessDelete){
				 $this->_redirect($lan . '/maintenanceadmin/index?success=delete');
				 exit();
				}
			}
		   if($MultipleAction){
		   	 if($MultipleSelectDelete=="Delete"){
			 	$idCheck= $this->getRequest()->getPost('checkRow');
			 	if($idCheck==true){
					$this->ModMaintenance->multipleDelete($idCheck);
					$this->_redirect($lan . '/maintenanceadmin/index?success=delete');
					 exit();
			 	}else if(!$idCheck){
			 		$this->_redirect($lan . '/maintenanceadmin/index');
			 		exit();
			 	}
			
			 }
			 
		   }
		}
	   }catch(ErrorException $ex){
		echo "Message".$ex->getMessage();
	   }
   }
   function redirectToIndex(){
   	try{
		if (! $this->CheckTransactionUser ()) {
		$lan = $this->_getParam ( 'lang' );
		$this->_redirect ( $lan . '/index/index' );
		exit ();
		
	}
	}catch(ErrorException $ex){
		echo "Message".$ex->getMessage();
	}
	
	}
  /* Starting function check session */
	public function CheckTransactionUser() {
		try{
			if (isset ( $_SESSION ['UserSession'] )) {
			return true;
		} else {
			return false;
		}
		}catch(ErrorException $ex){
		echo "Message".$ex->getMessage();
		}
	}
	/* Ending function check session */
	
	public function __destruct(){
		try{
			$this->GetModelSite;
			$this->ModMaintenance;
			$this->_Categories;
			session_write_close();
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
	
		}
	}
}