<?php
/**
 * @Developer Doeun
 * @Module  addnew site
 * @Company Mapring
 * @copyright 2013
 */
require_once 'Zend/function.php';
require_once 'Zend/Controller/Plugin/Abstract.php';
class SiteController extends Zend_Controller_Action {
	public $getLibBaseUrl = null;
	public $GetModelSite = null;
	public function init() {
		$this->view->controller = $this->_request->getParam ( 'controller' );
		$this->view->action = $this->_request->getParam ( 'action' );
		$this->GetModelSite = new Application_Model_ModSite ();
		// call function for dynamic sidebar
		$this->_Categories = new Application_Model_ModCatTerm ();
		$parent_id = $this->_getParam ( 'controller' );
		$this->view->secondSideBar = $this->_Categories->showCateParent ( $parent_id );
		$this->_helper->ajaxContext->addActionContext('deleteSite', 'json')->initContext();
	}
	public function indexAction() {
	   // MESSAGE ***************************
		if ($this->_getParam('success') != ''
				|| $this->_getParam('success') != null) {
			$message = $this->_getParam('success');
			if ($message == 'delete') {
				$message = 'The site has been deleted!.';
				$this->view->success = $message;
			}
            if ($message == 'update') {
				$message = 'The site has been updated!.';
				$this->view->success = $message;
			}
            if ($message == 'add') {
				$message = 'The site has been added!.';
				$this->view->success = $message;
			}
		}

		if ($this->_getParam('error') != ''
				|| $this->_getParam('error') != null) {
			$message = $this->_getParam('error');
			if ($message == 1) {
				$message = 'Your email is already in use, please put the new one';
				$this->view->error = $message;
			}			
		}
		// end MESSAGE ************************
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
			if($success=='add'){
				$message='Site is added successfully !';
				$this->view->success=$message;
			}else if($success=='update'){
				$message='Site is updated successfully !';
				$this->view->success=$message;
			}
			else if($success=="delete"){
				$message='Site is deleted successfully !';
				$this->view->success=$message;
			
			}	
			try {
				if($this->_getParam("btnBasicSearch")){
					$this->basicSearch();
				}else if($this->_getParam('btnAdvanceSearch')){
				    $this->advanceSearch();
				}else{
				$getRecordFromSite = $this->GetModelSite->selectAllViewSiteModule ();
				$this->view->getRecordFromSite = $getRecordFromSite;
				}
				
			} catch ( Exception $ex ) {
				echo "Message:" . $ex->getMessage ();
			}
		}
	}
	/*Starting of doing basic search*/
	public function basicSearch(){
		try{
			$SearchNameSite = $this->_getParam('txtsitename');
			if(strlen(trim($SearchNameSite))>0){
				if(get_magic_quotes_gpc()){
					$SearchNameSite = stripslashes($SearchNameSite);
				}
				$SearchNameSite = mysql_real_escape_string($SearchNameSite);
				$getRecordFromSite = $this->GetModelSite->basicsiteSearch($SearchNameSite);
				$this->view->getRecordFromSite = $getRecordFromSite;
			}else{
				$getRecordFromSite = $this->GetModelSite->selectAllViewSiteModule ();
				$this->view->getRecordFromSite = $getRecordFromSite;
			}
	
		}catch (ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	/*Ending of doing basic search*/
	/*Starting of doing Advance search*/
	public function advanceSearch(){
		try{
			$searchtxtsitename 		= 	trim($this->_getParam('txt_sitename'));
			$searchaddress 			=	trim($this->_getParam('txt_address'));
			$searchcodepostal 		= 	trim($this->_getParam('txt_codepostal'));
			$searchcity 			= 	trim($this->_getParam('txt_city'));
			$searchpay 				= 	trim($this->_getParam('txt_pay'));
			//$searchtelephone 		= 	trim($this->_getParam('txt_telephone'));
			//$codemobilephone 		= 	trim($this->_getParam('txt_mobilephone'));
			if($this->getAdvanceSearchPost()){
				if(get_magic_quotes_gpc()){
			$searchwebsite 			= 	stripslashes($searchtxtsitename);
			$searchaddress 			= 	stripslashes($searchaddress);
			$searchcodepostal 		= 	stripslashes($searchcodepostal);
			$searchcity 			= 	stripslashes($searchcity);
			$searchpay 		 		= 	stripslashes($searchpay);
			$searchtelephone 		= 	stripslashes($searchtelephone);
			$codemobilephone 		= 	stripslashes($codemobilephone);
				}
			$searchwebsite 			= 	mysql_real_escape_string($searchtxtsitename);
			$searchaddress 			= 	mysql_real_escape_string($searchaddress);
			$searchcodepostal 		= 	mysql_real_escape_string($searchcodepostal);
			$searchcity 			= 	mysql_real_escape_string($searchcity);
			$searchpay 				= 	mysql_real_escape_string($searchpay);
			//$searchtelephone 		= 	mysql_real_escape_string($searchtelephone);
			//$codemobilephone 		= 	mysql_real_escape_string($codemobilephone);
				$dataAdvanceSearch = array(
						$searchtxtsitename,
						$searchaddress,
						$searchcodepostal,
						$searchcity,
						$searchpay
				);
				$ShowingAdvanceSearch = $this->GetModelSite->searchAvance($dataAdvanceSearch);
				$this->view->getRecordFromSite = $ShowingAdvanceSearch;
			}else{
				$getRecordFromSite = $this->GetModelSite->selectAllViewSiteModule ();
				$this->view->getRecordFromSite = $getRecordFromSite;
			}
				
		}catch (ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
		
	public function getAdvanceSearchPost(){
		try{
		$searchwebsite 			= 	strlen(trim($this->_getParam('txt_sitename')));
		$searchaddress 			=	strlen(trim($this->_getParam('txt_address')));
		$searchcodepostal 		= 	strlen(trim($this->_getParam('txt_codepostal')));
		$searchcity 			= 	strlen(trim($this->_getParam('txt_city')));
		$searchpay 				= 	strlen(trim($this->_getParam('txt_pay')));
		//$searchtelephone 		= 	strlen(trim($this->_getParam('txt_telephone')));
		//$codemobilephone 		= 	strlen(trim($this->_getParam('txt_mobilephone')));
			if($searchwebsite>0||$searchaddress>0||$searchcodepostal>0||$searchcity>0||$searchpay>0){
				return true;
			}
		}catch (ErrorException $ex){
			echo "Error Said:".$ex->getMessage();
		}
	}
	/*Ending of doing Advance search*/
	public function addAction() {
		if (! $this->CheckTransactionUser ()) {
			try {
				Zend_Session::start ();
				$this->redirectToIndex();
			} catch ( Zend_Session_Exception $e ) {
				echo "Message:" . $e->getMessage ();
				session_start ();
			}
		} else {
			
			try {
				
				  $GetPosts = $this->getRequest ();
				if ($GetPosts->getPost ( 'AddSubmit' )) {
					$DataSite = array (
							'site_Name' => $GetPosts->getPost ( 'txt_sitename' ),
							'site_Description' => $GetPosts->getPost ( 'txt_sitediscription' ),
							'site_Codefunction' => $GetPosts->getPost ( 'txt_sitecodefunction' ),
							'site_Adresse1' => $GetPosts->getPost ( 'txt_siteaddress1' ),
							'site_City' => $GetPosts->getPost ( 'txt_sitecity' ),
							'site_Zipcode' => $GetPosts->getPost ( 'txt_sitezipcode' ),
							'site_Type' => $GetPosts->getPost ( 'txt_sitetype' ),
							'site_Statut' => $GetPosts->getPost ( 'txt_sitestatus' ),
							'site_Createdate' => date('d-m-Y'),
							'Com_Id' => $GetPosts->getPost ( 'txt_Company' ),
							'site_GPSLatit' => $GetPosts->getPost ( 'txt_gpslatit' ),
							'site_GPSLong' => $GetPosts->getPost ( 'txt_gpslong' ),
							'site_GPSAlt' => $GetPosts->getPost ( 'txt_gpsalt' ),
							'site_GPSDateTime' => $GetPosts->getPost ( 'txt_gpsdatetime' ),
							'site_GPSCt' => $GetPosts->getPost ( 'txt_gasct' ),
							'site_Refcode' => $GetPosts->getPost ( 'txt_referencecode' ),
							'site_Level' => $GetPosts->getPost ( 'txt_sitlevel' ),
                            'User_id' => $_SESSION['UserId'] 
					);
					
					$GetLastIdSite = $this->GetModelSite->InsertRecordToTableSite ( $DataSite );
					$lan = $this->_getParam ( 'lang' );
					$this->_redirect ( $lan . '/Site/index?success=add' );
					exit ();
					
				}else{
					$RefComType=$this->GetModelSite->SelectRefComType();
					$this->view->RefComType=$RefComType;
					
					$RefStatus=$this->GetModelSite->SelectRefStatus();
					$this->view->RefStatus=$RefStatus;
					
					$RefCode=$this->GetModelSite->SelectRefCode();
					$this->view->RefCode=$RefCode;
					
					$RefLevel=$this->GetModelSite->SelectRefLevel();
					$this->view->RefLevel=$RefLevel;
                    
                    $SelectCompany = $this->GetModelSite->selectCompanyName();
                    $this->view->CompanyName = $SelectCompany; 
					
				}
			} catch ( ErrorException $exe ) {
				echo "Message:" . $exe->getMessage ();
			}
		}
	}
	/* Starting edit of Module site */
    
    /* view site */
    public function overviewAction ()
    {
        if(!$this->CheckTransactionUser()){
            $this->redirectToIndex();
        }else{
            $id =$this->_getParam ( 'id' );
            $userid =$this->_getParam ( 'userids' );
            $process =$this->_getParam ( 'actions' );
            if($process=="overview"){
                $getValueSiteView = $this->GetModelSite->selectSiteOverview($id,$userid);
                $this->view->siteView=$getValueSiteView;
            }
        }
    }
    /* end view site */
    
    
    /* for edit site */
	public function editAction() {
		if (! $this->CheckTransactionUser ()) {
			try {
				Zend_Session::start ();
				$this->redirectToIndex();
			} catch ( Zend_Session_Exception $e ) {
				echo "Message:" . $e->getMessage ();
				session_start ();
			}
		} else {
			$lan = $this->_getParam ( 'lang' );
			$idForEditSite = $this->_getParam ( 'id' );
			if ($this->_getParam ( 'actions' ) == "edit") {
				$idForEditSite = $this->_getParam ( 'id' );
				$idusers       = $this->_getParam('userids');
				$SelectCompany = $this->GetModelSite->selectCompanyName();
				$this->view->CompanyName = $SelectCompany;
				$DisplayResultSite = $this->GetModelSite->SelectAllFromSite ( $idForEditSite,$idusers);
				//var_dump($DisplayResultSite);
				if ($DisplayResultSite) {
					$this->view->displayResultSite = $DisplayResultSite;
				}
			}
			if ($this->getRequest ()->getPost ( 'EditSubmit' )) {
				$GetPosts = $this->getRequest ();
				$lan = $this->_getParam ( 'lang' );
				$userid = $GetPosts->getPost ( 'idForUser' );
				//echo $userid;die();
				$id = $GetPosts->getPost ( 'siteHiddenId' );
				$editSiteData = array (
						'site_Name' => $GetPosts->getPost ( 'txt_sitename' ),
							'site_Description' => $GetPosts->getPost ( 'txt_sitediscription' ),
							'site_Codefunction' => $GetPosts->getPost ( 'txt_sitecodefunction' ),
							'site_Adresse1' => $GetPosts->getPost ( 'txt_siteaddress1' ),
							'site_City' => $GetPosts->getPost ( 'txt_sitecity' ),
							'site_Zipcode' => $GetPosts->getPost ( 'txt_sitezipcode' ),
							'site_Type' => $GetPosts->getPost ( 'txt_sitetype' ),
							'site_Statut' => $GetPosts->getPost ( 'txt_sitestatus' ),
							'site_Createdate' => date('d-m-Y'),
							'Com_Id' => $GetPosts->getPost ( 'txt_Company' ),
							'site_GPSLatit' => $GetPosts->getPost ( 'txt_gpslatit' ),
							'site_GPSLong' => $GetPosts->getPost ( 'txt_gpslong' ),
							'site_GPSAlt' => $GetPosts->getPost ( 'txt_gpsalt' ),
							'site_GPSDateTime' => $GetPosts->getPost ( 'txt_gpsdatetime' ),
							'site_GPSCt' => $GetPosts->getPost ( 'txt_gasct' ),
							'site_Refcode' => $GetPosts->getPost ( 'txt_referencecode' ),
							'site_Level' => $GetPosts->getPost ( 'txt_sitlevel' ),
                            'User_id' => $_SESSION['UserId']  
				);
				
				$this->GetModelSite->updateRecordSite ( $editSiteData, $id );
				/*if ($GetPosts->getPost ( 'txt_sitlevel' ) =="1") {
					$EditDataSubsite = array (
							'hsite_Codefunction' => $GetPosts->getPost ( 'txt_hcodefuntion' ),
							'hsite_secondaire' => $GetPosts->getPost ( 'txt_hsecondaire' ),
							'hsite_Createdate' => $GetPosts->getPost ( 'txt_hcreatedate' ),
							'hsite_Modifdate' => $GetPosts->getPost ( 'txt_hmodifydate' ) 
					);
					$this->GetModelSite->UpdateRecordToTableSubSite ( $EditDataSubsite, $id );
				}*/
				$this->_redirect ( $lan . '/Site/index?success=update' );
				exit ();
			}else{
				$RefComType=$this->GetModelSite->SelectRefComType();
				$this->view->RefComType=$RefComType;
					
				$RefStatus=$this->GetModelSite->SelectRefStatus();
				$this->view->RefStatus=$RefStatus;
					
				$RefCode=$this->GetModelSite->SelectRefCode();
				$this->view->RefCode=$RefCode;
					
				$RefLevel=$this->GetModelSite->SelectRefLevel();
				$this->view->RefLevel=$RefLevel;
                
                
				
			}
		}
	}
	/* Ending edit of Module site */
	/**
	 * Starting Delete module site*
	 */
	public function deleteAction() {
		if (! $this->CheckTransactionUser ()) {
			try {
				Zend_Session::start ();
				$this->redirectToIndex();
			} catch ( Zend_Session_Exception $e ) {
				echo "Message:" . $e->getMessage ();
				session_start ();
			}
		} else {
			$lan = $this->_getParam ( 'lang' );
			$actions = $this->_getParam ( 'actions' );
			//$this->getRequest ()->getPost ( 'process' )==1
			if ( $actions=='delete') {
				$idSite = $this->_getParam ( 'id' );
				$SuccessDelete = $this->GetModelSite->deleteSites($idSite );
				if ($SuccessDelete) {
					$this->_redirect ( $lan . '/Site/index?success=delete' );
				}
			}
			/* In cases of multiple delete */
			if ($this->getRequest ()->getPost ( 'BtnDelete' )) {
				if ($this->getRequest ()->getPost ( 'multiAction' )) {
					$idMultiDelSite = $this->getRequest ()->getPost ( 'checkRow' );
					$MultiDeleteSuccess = $this->GetModelSite->multiDeleteSite ( $idMultiDelSite );
					if ($MultiDeleteSuccess) {
						$this->_redirect ( $lan . '/Site/index?success=delete' );
					}
				}
			}
		}
	}
	/**
	 * Ending Delete module site*
	 */
	
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
		try{
			$this->GetModelSite;
			$this->_Categories;
			session_write_close();
		}catch(ErrorException $ex){
			echo "Message:" . $ex->getMessage ();
	
		}
	}
	
	
}

