<?php
require_once 'Zend/function.php';
require_once 'Zend/Controller/Plugin/Abstract.php';
class ReferencemaintenanceController extends Zend_Controller_Action {
	public $ModMaintenance = NULL;
	public function init() {
	    $this->view->controller = $this->_request->getParam ( 'controller' );
		$this->view->action = $this->_request->getParam ( 'action' );
		$this->GetModelSite = new Application_Model_ModSite ();
		$this->_Categories = new Application_Model_ModCatTerm ();
		$this->ModMaintenance = new Application_Model_ModRefMaintenance();
		$parent_id = $this->_getParam ( 'controller' );
		$this->view->secondSideBar = $this->_Categories->showCateParent ( $parent_id );
		$this->_helper->ajaxContext->addActionContext('deleteSite', 'json')->initContext();
	}
	public function indexAction() {
		if(!$this->CheckTransactionUser())
		{
			$this->redirectToIndex();
		}
		if ($this->getRequest()->getPost('SaveReference')){
			 $arra = $this->getRequest()->getPost('txtOrderby');
			 $this->ModMaintenance->add($arra);
			 
			
		}
	}
   public function addAction()
   {
   	
   }
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