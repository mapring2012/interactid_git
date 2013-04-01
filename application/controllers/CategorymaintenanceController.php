<?php
require_once 'Zend/function.php';
require_once 'Zend/Controller/Plugin/Abstract.php';
class CategorymaintenanceController extends Zend_Controller_Action {
	private $_cateMaintenace;
	public function init() {
		/* Initialize action controller here */
		
		$this->view->controller = $this->_request->getParam('controller');
		$this->view->action = $this->_request->getParam('action');
		$this->view->assign('actions', 'index');
		
		$this->_cateMaintenace = new  Application_Model_ModCatMaintenance();
		
		//$this->_typeEquipement = new Application_Model_ModFactory();
		//$getCompanyName = $this->_registerDb->SelectComId();
		//$this->view->getCompanyName = $getCompanyName;
		
		$this->getLibBaseUrl = new Zend_View_Helper_BaseUrl();
		
		// call function for dynamic sidebar
		//$this->_Categories = new Application_Model_ModCatTerm();
		//$parent_id = $this->_getParam('controller');
		//$this->view->secondSideBar = $this->_Categories->showCateParent($parent_id);
	}
	public function indexAction() {
		// action body
		 $request=$this->_getParam("success");
		 if($request or $request!=''){
		 	if($request=="add"){
		 		$message = 'Category Maintenance has been added!';
		 		$this->view->success = $message;	
		 	}elseif($request=="update"){
		 		$message = 'Category Maintenance has been update!';
		 		$this->view->success = $message;
		 	}elseif($request=="delete"){
		 		$message = 'Category Maintenance has been delete!';
		 		$this->view->success = $message;
		 	}
		 }
		 
		 if (! $this->CheckTransactionUser ()) {
		 	try {
		 		$this->redirectToIndex();
		 	} catch ( Zend_Session_Exception $e ) {
		 		echo "Message:" . $e->getMessage ();
		 		session_start ();
		 	}
		 }else{
		 	$getAllCategoryMaintenace=$this->_cateMaintenace->selectAllCategoryMaintenance();
		 	$this->view->getAllCategoryMaintenace=$getAllCategoryMaintenace;
		 }
		 

		 
	}
	public function existUserName() {
		//$getTableUser = $this->_cateMaintenace->selectAllCategoryMaintenanceName();
		//return $getTableUser;
	}
	public function addAction() {
		$GetPost = $this->getRequest();
		$lan     = $this->_getParam('lang');
		if (! $this->CheckTransactionUser ()) {
				try {
					$this->redirectToIndex();
				} catch ( Zend_Session_Exception $e ) {
					echo "Message:" . $e->getMessage ();
					session_start ();
				}
			} else {
				try{
					
					settype($addCateMaintenace,"array");
					if($GetPost->getPost('BtnAddCategoryMaintenance')){
						/*foreach ($this->existUserName() as $reRows) {
							$UserNames = $reRows['catmain_name'];
							if ($UserNames== $this->getRequest()->getPost('txtCatMaintenanceName')) {
								$_SESSION['duplicateUserName'] = "<p style='color:red;'>UserName can not duplicated</p>";
								$lan = $this->_getParam('lang');
								$this->_redirect($lan . '/categorymaintenance/add?error=2');
								exit();
							}
					
						} */
						
						
						$addCateMaintenace = array(
								'catmain_name'=>$GetPost->getPost('txtCatMaintenanceName'),
								'catmain_description'=>$GetPost->getPost('txtDescription')
								);
						$GetFactoryLastId=$this->_cateMaintenace->insertCategoryMaintenance($addCateMaintenace);
					   /*  $capitalRef = substr($GetPost->getPost ( 'txtName' ), 0, 3);
					   $GetFactoryLastId = $this->ModEquipement->addNewEquipement($getDataEquipement);
					   $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($GetFactoryLastId);
					   $datas = array('equip_Reference'=>$orgReferenceSubstring);
					   $this->ModEquipement->updateRefer($datas,$GetFactoryLastId);
					   */
					   $this->_redirect ( $lan . '/categorymaintenance/index?success=add' );
					   	exit();
					  
					}else{
						//	
					}
				}catch (ErrorException $ex){
					echo "Message".$ex->getMessage();
				}
			}
	}
	public function editAction() {
		$GetPost 	= $this->getRequest();
		$lan     	= $this->_getParam('lang');
		$id     	= $this->_getParam('id');
		$actions    = $this->_getParam('actions');
		$hiddenid   =$GetPost->getPost('hddid');
		if (! $this->CheckTransactionUser ()) {
				try {
					$this->redirectToIndex();
				} catch ( Zend_Session_Exception $e ) {
					echo "Message:" . $e->getMessage ();
					session_start ();
				}
			} else {
				try{
					
					settype($addCateMaintenace,"array");
					if($GetPost->getPost('BtnUpdateCategoryMaintenance')){
						$addCateMaintenace = array(
								'catmain_name'=>$GetPost->getPost('txtCatMaintenanceName'),
								'catmain_description'=>$GetPost->getPost('txtDescription')
								);
						$this->_cateMaintenace->UpdateCategoryMaintenance($addCateMaintenace,$hiddenid);
					   	$this->_redirect ( $lan . '/categorymaintenance/index?success=update' );
					   	exit();
					  
					}else{
						if($actions=="edit"){
							$viewCateMaintenanceEdit=$this->_cateMaintenace->selectEditEquipement($id);
							$this->view->viewCateMaintenanceEdit=$viewCateMaintenanceEdit;
						}	
					}
				}catch (ErrorException $ex){
					echo "Message".$ex->getMessage();
				}
			}
	}
	public function deleteAction(){
		$GetPost 	= $this->getRequest();
		$lan     	= $this->_getParam('lang');
		$id     	= $this->_getParam('id');
		$actions    = $this->_getParam('actions');
		$hiddenid   =$GetPost->getPost('hddid');
		
		if(!$this->CheckTransactionUser()){
			$this->redirectToIndex();
		}else{
			try{
				if($actions=="delete"){
					$deleteCategoryMaintenance = $this->_cateMaintenace->deleteCategoryMaintenance($id);
					if($deleteCategoryMaintenance==TRUE){
						$this->_redirect ( $lan . '/categorymaintenance/index?success=delete' );
						exit();
					}
				}
				if($this->getRequest()->getPost('btnDeleteMulti')){
					if($this->getRequest()->getPost('multiAction')=="delete"){
						$id = $this->getRequest()->getPost('checkRow');
						$deleteCategoryMaintenanceMulti = $this->_cateMaintenace->deleteCategoryMaintenancetMulti($id);
						if($deleteCategoryMaintenanceMulti==TRUE){
							$this->_redirect ( $lan . '/categorymaintenance/index?success=delete' );
							exit();
						}
					}
				  
				}
			}catch (ErrorException $ex){
				echo "Message:".$ex->getMessage();
			}
		}
		
	}
	
	// this is function for check session
	public function CheckTransactionUser() {
		try{
			if (isset ( $_SESSION ['UserSession'] )) {
				return true;
			} else {
				return false;
			}
		}catch(ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	
	}
	
	/*Ending of editing user in administrator*/
	public function redirectToIndex() {
		try{
			Zend_Session::start ();
			$lan = $this->_getParam('lang');
			$this->_redirect($lan . '/index/index');
			exit();
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
		 
	}
	/* develope: dim sidet  */
	public function __destruct(){
		try{
			$this->_cateMaintenace;
			session_write_close();
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
		}
	}
	
	
}