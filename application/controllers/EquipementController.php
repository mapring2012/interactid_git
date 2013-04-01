<?php
/**
 * @Developer  dim sidet
 * @Module     Type Equipement
 * @Descript   Add,Edit,Update and select
 * @Company   Mapring
 * @copyright 2013
 */
require_once 'Zend/Controller/Plugin/Abstract.php';
require_once 'Zend/function.php';
class EquipementController extends Zend_Controller_Action {
	public $ModEquipement = null;
	public function init() {
		/* Initialize action controller here */
		$this->view->controller = $this->_request->getParam ( 'controller' );
		$this->view->action = $this->_request->getParam ( 'action' );
		// call function for dynamic sidebar
		$this->_Categories = new Application_Model_ModCatTerm ();
		$parent_id = $this->_getParam ( 'controller' );
		$this->view->secondSideBar = $this->_Categories->showCateParent ( $parent_id );
		$this->ModEquipement = new Application_Model_ModEquipement();
	
	}
	public function indexAction() {
		if (! $this->CheckTransactionUser ()) {
			try {
				Zend_Session::start ();
				$this->redirectToIndex();
			} catch ( Zend_Session_Exception $e ) {
				echo "Message:" . $e->getMessage ();
				session_start ();
			}
		} else {
				
			$success=$this->_getParam ( 'success' );
				
			//$success1=['success'];
			if($success=='add'){
				$message='Type Equipement is added successfully !';
				$this->view->success=$message;
			}else if($success=='update'){
				$message='Type Equipement is updated successfully !';
				$this->view->success=$message;
			}
			else if($success=='delete'){
				$message='Type Equipement is deleted successfully !';
				$this->view->success=$message;
			}
				
			
			
		try{
            if (!$this->CheckTransactionUser()) {
              $this->redirectToIndex();   
            }else{
            	
            	$searchBasicSubmit 		= 	$this->_getParam('btnsearchBasic');
            	$searchAdvanceSubmit 	=   $this->_getParam('btnAdvanceSearch');
               if($searchBasicSubmit){
               	  $this->basicSearch();
               }else if($searchAdvanceSubmit){
               	$this->advanceSearch();
               }else{
               $getAllEquipement=$this->ModEquipement->GetAllRecordEquipement(); 
               $this->view->getResultEquipement =$getAllEquipement;
               }
               
            }
        }catch (ErrorException $ex) {
        		echo "Message Error:" . $ex->getMessage();
        }
			
			
		}
        
         
	}
	public function basicSearch(){
		try{
			$SearchName= $this->_getParam('txtnamebasic');
			if(strlen(trim($SearchName))>0){
				if(get_magic_quotes_gpc()){
					$SearchName = stripslashes($SearchName);
				}
				$SearchName = mysql_real_escape_string($SearchName);
				$getRecordFromEquipement = $this->ModEquipement->basicSearch($SearchName);
				$this->view->getResultEquipement= $getRecordFromEquipement;
			}else{
				$getRecordFromEquipement=$this->ModEquipement->GetAllRecordEquipement();
				$this->view->getResultEquipement =$getRecordFromEquipement;
			}
	
		}catch (ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	
	
	public function advanceSearch(){
		$id=$this->_getParam('id');
		try{
			$searchname 	= 	trim($this->_getParam('txtname'));
			$searchFournisseur 	=	trim($this->_getParam('txtTournisseur'));
			$searchReference 	= 	trim($this->_getParam('txtReference'));
			$searchDateCommiss 	= 	trim($this->_getParam('txtDateCommiss'));
			$searchDateManufacture 	= 	trim($this->_getParam('txtDateManufacture'));
			
			if($this->getAdvanceSearchPost()){
				if(get_magic_quotes_gpc()){
					$searchname 	= 	stripslashes($searchname);
					$searchFournisseur 	= 	stripslashes($searchFournisseur);
					$searchReference 	= 	stripslashes($searchReference);
					$searchDateCommiss 	= 	stripslashes($searchDateCommiss);
					$searchDateManufacture 	= 	stripslashes($searchDateManufacture);
					
				}
				$searchname 	= 	mysql_real_escape_string($searchname);
				$searchFournisseur 	= 	mysql_real_escape_string($searchFournisseur);
				$searchReference 	= 	mysql_real_escape_string($searchReference);
				$searchDateCommiss 	= 	mysql_real_escape_string($searchDateCommiss);
				$searchDateManufacture 	= 	mysql_real_escape_string($searchDateManufacture);
				
				$dataAdvanceSearch = array(
						$searchname,
						$searchFournisseur,
						$searchReference,
						$searchDateCommiss,
						$searchDateManufacture,
						
				);
				$ShowingBasicSearch = $this->ModEquipement->searchAvance($dataAdvanceSearch);
				$this->view->getResultEquipement = $ShowingBasicSearch;
			}else{
				$getRecordFromEquipement=$this->ModEquipement->GetAllRecordEquipement();
				$this->view->getResultEquipement =$getRecordFromEquipement;
			}
			 
		}catch (ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	 
	public function getAdvanceSearchPost(){
		try{
			$searchname 		= 	strlen(trim($this->_getParam('txtname')));
			$searchFournisseur 		=	strlen(trim($this->_getParam('txtTournisseur')));
			$searchReference 		= 	strlen(trim($this->_getParam('txtReference')));
			$searchDateCommiss 		= 	strlen(trim($this->_getParam('txtDateCommiss')));
			$searchDateManufacture 		= 	strlen(trim($this->_getParam('txtDateManufacture')));
			
			if($searchname>0||$searchFournisseur>0||$searchReference>0||$searchDateCommiss>0||$searchDateManufacture>0){
				return true;
			}
		}catch (ErrorException $ex){
			echo "Error Said:".$ex->getMessage();
		}
	}
	
    /*search for add data*/
    public function searchAction() {
    	try{
    		$searchname=$this->_getParam('name');
    		if($searchname)
    		{
    			if(strlen(trim($searchname))>0){
    				if(get_magic_quotes_gpc()){
    					$searchname = stripslashes($searchname);
    				}
    				$searchname = mysql_real_escape_string($searchname);
    				$getRecordEquipement = $this->ModEquipement->searchFormAddNew($searchname);
    				$this->view->searchname=$getRecordEquipement;
    			}
    		}
            else
            {
                /*if no value */
                $searchname = mysql_real_escape_string($searchname);
  				$getRecordEquipement = $this->ModEquipement->searchFormAddNew($searchname);
  				$this->view->searchname=$getRecordEquipement;
            }
    	}catch (Exception $ex){
    		echo "Message".$ex->getMessage();
    	}
       
       
        }
    /* end search for add data*/
	/* Starting adding new for typeEquipement */
	public function addAction() {
	   $searchname = '';
        $getRecordEquipement = $this->ModEquipement->searchFormAddNew($searchname);
        $this->view->searchname=$getRecordEquipement;
	   	$GetPost = $this->getRequest();
		$lan     = $this->_getParam('lang');
		$getDataEquipement = array();
		if (! $this->CheckTransactionUser ()) {
			try {
				Zend_Session::start ();
				$this->redirectToIndex();
			} catch ( Zend_Session_Exception $e ) {
				echo "Message:" . $e->getMessage ();
				session_start ();
			}
		} else {
			try{
				if($GetPost->getPost('BtnAddEquipement')){
					$getDataEquipement = array(
							
							'equip_Name'=>$GetPost->getPost('txtName'),
							'equip_SKU'=>$GetPost->getPost('txtSKU'),
							'equip_Freqmaintnancedate'=>$GetPost->getPost('txtFrequenceMaintenancedate'),
							'equip_Warranty'=>$GetPost->getPost('txtWarranty'),
							'equip_Warrantydate'=>$GetPost->getPost('txtWarrantyDate'),
							'equip_Description'=>$GetPost->getPost('txtDescription'),
							'equip_Notice'=>$GetPost->getPost('txtNotice'),
							'equip_GPSLong'=>$GetPost->getPost('txtGPSlongitude'),
                            'equip_GPSLatit'=>$GetPost->getPost('txtGPSlatitude'),
                            'equip_GPSAlt'=>$GetPost->getPost('txtGPSaltitude'),
							'equip_Price'=>$GetPost->getPost('txtPriceEquipement'),
							'equip_Statut'=>$GetPost->getPost('cboStatus'),
							'equip_Createdate'=>date('d-m-Y'),
							'equip_Notice'=>$GetPost->getPost('txtnote'),
							'Org_Id'=>$GetPost->getPost('cboOrganisme'),
							'site_Id'=>$GetPost->getPost('cboSite'),
							'Fou_Id'=>$GetPost->getPost('cboFournisseur'),
							'Type_id'=>$GetPost->getPost('cboTypeEquipement'),
							'equip_Freqmaintnance'=>$GetPost->getPost('txtFrequentMaintenance').' '.$GetPost->getPost('cboPeriodFrequentMaintenance')
							
							);
				   $capitalRef = substr($GetPost->getPost ( 'txtName' ), 0, 3);
				   $GetFactoryLastId = $this->ModEquipement->addNewEquipement($getDataEquipement);
				   $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($GetFactoryLastId);
				   $datas = array('equip_Reference'=>$orgReferenceSubstring);
				   $this->ModEquipement->updateRefer($datas,$GetFactoryLastId);
				   
				   $this->_redirect ( $lan . '/equipement/index?success=add' );
				   	exit();
				  
				}else{
					$getOrganism=$this->ModEquipement->selectAllDataForOrganismeMenu();
					$this->view->getOrganism=$getOrganism;
					
					$getSite=$this->ModEquipement->SelectAllFromSite();
					$this->view->getSite=$getSite;
					
					$getFactory=$this->ModEquipement->selectAllFactory();
					$this->view->getFactory=$getFactory;
					
					$getTypeEquipement=$this->ModEquipement->selectAllTypeEquipement();
					$this->view->getTypeEquipement=$getTypeEquipement;
					
					
				}
			}catch (ErrorException $ex){
				echo "Message".$ex->getMessage();
			}
		}
	}
	public  function editAction(){
		$GetPost = $this->getRequest();
		$lan     = $this->_getParam('lang');
		$idP     = $this->_getParam('id');
		$getDataEquipement = array();
		if (! $this->CheckTransactionUser ()) {
			try {
				Zend_Session::start ();
				$this->redirectToIndex();
			} catch ( Zend_Session_Exception $e ) {
				echo "Message:" . $e->getMessage ();
				session_start ();
			}
		} else {
			try{
				
				if($GetPost->getPost('BtnUpdateEquipement')){
					$id=$GetPost->getPost('txtid');
					$getDataEquipement = array(
							'Org_Id'=>$GetPost->getPost('cboOrganisme'),
							'site_Id'=>$GetPost->getPost('cboSite'),
							'Fou_Id'=>$GetPost->getPost('cboFournisseur'),
							'Type_id'=>$GetPost->getPost('cboTypeEquipement'),
							'equip_Name'=>$GetPost->getPost('txtName'),
							'equip_SKU'=>$GetPost->getPost('txtSKU'),
							'equip_Freqmaintnancedate'=>$GetPost->getPost('txtFrequenceMaintenancedate'),
							'equip_Warranty'=>$GetPost->getPost('txtWarranty'),
							'equip_Warrantydate'=>$GetPost->getPost('txtWarrantyDate'),
							'equip_Description'=>$GetPost->getPost('txtDescription'),
							'equip_Notice'=>$GetPost->getPost('txtnote'),
							'equip_GPSLong'=>$GetPost->getPost('txtGPSlongitude'),
                            'equip_GPSLatit'=>$GetPost->getPost('txtGPSlatitude'),
                            'equip_GPSAlt'=>$GetPost->getPost('txtGPSaltitude'),
							'equip_Price'=>$GetPost->getPost('txtPriceEquipement'),
							'equip_Statut'=>$GetPost->getPost('cboStatus'),
							'equip_Createdate'=>date('d-m-Y'),
							'equip_Freqmaintnance'=>$GetPost->getPost('txtFrequentMaintenance').' '.$GetPost->getPost('cboPeriodFrequentMaintenance')
					);
					$capitalRef = substr($GetPost->getPost ( 'txtName' ), 0, 3);
					$this->ModEquipement->UpdateEquipement($getDataEquipement,$id);
					$orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($id);
					$datas = array('equip_Reference'=>$orgReferenceSubstring);
					$this->ModEquipement->updateRefer($datas,$id);
					$this->_redirect ( $lan . '/equipement/index?success=update' );
						exit();
					
				}else{
					$actions = $this->_getParam('actions');
					 if($actions=="edit"){
					 	$selectEdit = $this->ModEquipement->selectEditEquipement($idP);
					 	$this->view->selectEdit = $selectEdit;
					 }
					$getOrganism=$this->ModEquipement->selectAllDataForOrganismeMenu();
					$this->view->getOrganism=$getOrganism;
						
					$getSite=$this->ModEquipement->SelectAllFromSite();
					$this->view->getSite=$getSite;
						
					$getFactory=$this->ModEquipement->selectAllFactory();
					$this->view->getFactory=$getFactory;
						
					$getTypeEquipement=$this->ModEquipement->selectAllTypeEquipement();
					$this->view->getTypeEquipement=$getTypeEquipement;
						
						
				}
			}catch (ErrorException $ex){
				echo "Message".$ex->getMessage();
			}
		}
	
	}
	/* Ending Adding new for typeEquipement */
	public  function overviewAction(){
		try{
		$this->redirectToIndex();
		$ids = $this->_getParam('id');
        $catid = $this->_getParam('catid');
		$actions = $this->_getParam('actions');
		if($actions=="overview"){
			$getToViews = $this->ModEquipement->SelectOverviewEquipement ($ids);
			$this->view->getToViews=$getToViews;
            $getCatEq = $this->ModEquipement->getCatEq($catid);    
            $getMaintenanceViews = $this->ModEquipement->maintenanceView($catid);
            if(count($getMaintenanceViews)>0){
            $i=0;
             foreach($getMaintenanceViews as $row){ 
                $i++;             
                    if($i==1)
                    {
                        $catm = $row['catmain_id'];
                        $level1=$this->ModEquipement->level1($catm);
                        $this->view->level1= $level1;
                       
                    }
                    if($i==2){
                        $catm = $row['catmain_id'];
                        $level2=$this->ModEquipement->level2($catm);
                        $this->view->level2= $level2;
                    }
                }
              }  
            //$this->view->getMaintenanceViews=$getMaintenanceViews;

		}
		}catch(ErrorException $ex){
			echo "Message".$ex->getMessage();
		}
		
	}
   /*Starting delete Equipement*/
	public function deleteAction(){
		  $lan = $this->_getParam('lang');
		  $ids = $this->_getParam('id');
		  $actions = $this->_getParam('actions');
		  if(!$this->CheckTransactionUser()){
		  	$this->redirectToIndex();
		  }else{
		  	try{
		  	if($actions=="delete"){
		  		$deleteEquipement = $this->ModEquipement->deleteEquipement($ids);
		  		if($deleteEquipement==TRUE){
		  			$this->_redirect ( $lan . '/equipement/index?success=delete' );
		  			exit();
		  		}
		  	 }
		  	 if($this->getRequest()->getPost('btnDeleteMulti')){
		  	 	 if($this->getRequest()->getPost('multiAction')=="delete"){
		  	 	 	$id = $this->getRequest()->getPost('checkRow');
		  	 	 	$deleteEquipementMulti = $this->ModEquipement->deleteEquipementMulti($id);
		  	 	 	if($deleteEquipementMulti==TRUE){
		  	 	 		$this->_redirect ( $lan . '/equipement/index?success=delete' );
		  	 	 		exit();
		  	 	 	}
		  	 	 }
		  	 	
		  	 }
		  	}catch (ErrorException $ex){
		  	 echo "Message:".$ex->getMessage();
		  	}
		  }
	}
	public function equipementviewtab2Action(){
		
		
	}
	public  function createDigit($digit){
		try{
			if (strlen($digit) == 1) {
				$new_digit = "000".$digit;
			}
			elseif (strlen($digit) == 2) {
				$new_digit = "00".$digit;
			}
			elseif (strlen($digit) == 3) {
				$new_digit = "0".$digit;
			}
			else {
				$new_digit = $digit;
			}
	
			return $new_digit;
			 
		}catch(ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	/* Starting function check session */
	public function CheckTransactionUser() {
		if (isset ( $_SESSION ['UserSession'] )) {
			return true;
		} else {
			return false;
		}
	}
	/* Ending function check session */
	function redirectToIndex(){
		if (! $this->CheckTransactionUser ()) {
			$lan = $this->_getParam ( 'lang' );
			$this->_redirect ( $lan . '/index/index' );
			exit ();
			
		}
	}
	public function __destruct(){
		session_write_close();
		$this->ModEquipement;
	}
	
	
}


