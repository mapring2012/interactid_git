<?php
/**
 * @Developer  Socheat
 * @Module     Type Equipement
 * @Descript   Add,Edit,Update and select
 * @Company   Mapring
 * @copyright 2013
 */
require_once 'Zend/Controller/Plugin/Abstract.php';
require_once 'Zend/function.php';
class FactoryController extends Zend_Controller_Action {
	public $getLibBaseUrl;
	private $_registerDb = NULL;
    private $_typeEquipement=null;
    
	public function init() {

		/* Initialize action controller here */
		
		$this->view->controller = $this->_request->getParam('controller');
		$this->view->action = $this->_request->getParam('action');
		$this->view->assign('actions', 'index');
		$this->_registerDb = new Application_Model_Register();
		
		$this->_typeEquipement = new Application_Model_ModFactory();
		$getCompanyName = $this->_registerDb->SelectComId();
		$this->view->getCompanyName = $getCompanyName;

		$this->getLibBaseUrl = new Zend_View_Helper_BaseUrl();

		// call function for dynamic sidebar
		$this->_Categories = new Application_Model_ModCatTerm();
		$parent_id = $this->_getParam('controller');
		$this->view->secondSideBar = $this->_Categories
				->showCateParent($parent_id);

	}
	public function indexAction() {
	   if ($this->_getParam('success') != ''
    			|| $this->_getParam('success') != null) {
    		$message = $this->_getParam('success');
    		if ($message =="add") {
    			$message = 'Factory  had been added! ';
    			$this->view->success = $message;
    		}else if($message=="delete"){
    			$message = 'Factory  had been deleted!';
    			$this->view->success = $message;
    		}else if($message=="update"){
    			$message = 'Factory  had been updated!';
    			$this->view->success = $message;
    		}
    			
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
               $getAllFactory=$this->_registerDb->selectAllFactory(); 
               $this->view->getAllFactory =$getAllFactory;
               }
               
            }
        }catch (ErrorException $ex) {
        		echo "Message Error:" . $ex->getMessage();
        }
        }
	/* Starting adding new for typeEquipement */





    /*Starting of doing basic search*/
    public function basicSearch(){
    	try{
    		$SearchNameFactory = $this->_getParam('seacrhnamefactory');
    		if(strlen(trim($SearchNameFactory))>0){
    			if(get_magic_quotes_gpc()){
    				$SearchNameFactory = stripslashes($SearchNameFactory);
    			}
    			$SearchNameFactory = mysql_real_escape_string($SearchNameFactory);
    			$getRecordFromFactory = $this->_registerDb->basicfactorySearch($SearchNameFactory);
    			$this->view->getAllFactory= $getRecordFromFactory;
    		}else{
    			$getAllFactory=$this->_registerDb->selectAllFactory(); 
               $this->view->getAllFactory =$getAllFactory;
    		}
    
    	}catch (ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
    }
    /*Ending of doing basic search*/
    public function advanceSearch(){
    	$id=$this->_getParam('id');
    	try{
    		$searchsociale 	= 	trim($this->_getParam('searchsociale'));
    		$searchemail 	=	trim($this->_getParam('searchemail'));
    		$searchstate 	= 	trim($this->_getParam('searchstate'));
    		$searchville 	= 	trim($this->_getParam('searchville'));
    		$searchwebsite 	= 	trim($this->_getParam('searchwebsite'));
    		$searchaddrees 	= 	trim($this->_getParam('searchaddrees'));
    		$codepostal 	= 	trim($this->_getParam('searchcodepostal'));
    		$searchphone 	= 	trim($this->_getParam('searchphone'));
    		$searchCountry 	= 	trim($this->_getParam('searchCountry'));
    		if($this->getAdvanceSearchPost()){
    			if(get_magic_quotes_gpc()){
    				$searchsociale 	= 	stripslashes($searchsociale);
    				$searchemail 	= 	stripslashes($searchemail);
    				$searchstate 	= 	stripslashes($searchstate);
    				$searchville 	= 	stripslashes($searchville);
    				$searchwebsite 	= 	stripslashes($searchwebsite);
    				$searchaddrees 	= 	stripslashes($searchaddrees);
    				$codepostal 	= 	stripslashes($codepostal);
    				$searchphone 	= 	stripslashes($searchphone);
    				$searchCountry 	= 	stripslashes($searchCountry);
    			}
    			$searchsociale 	= 	mysql_real_escape_string($searchsociale);
    			$searchemail 	= 	mysql_real_escape_string($searchemail);
    			$searchstate 	= 	mysql_real_escape_string($searchstate);
    			$searchville 	= 	mysql_real_escape_string($searchville);
    			$searchwebsite 	= 	mysql_real_escape_string($searchwebsite);
    			$searchaddrees 	= 	mysql_real_escape_string($searchaddrees);
    			$codepostal 	= 	mysql_real_escape_string($codepostal);
    			$searchphone 	= 	mysql_real_escape_string($searchphone);
    			$searchCountry 	= 	mysql_real_escape_string($searchCountry);
    			$dataAdvanceSearch = array(
    					$searchsociale,
    					$searchemail,
    					$searchstate,
    					$searchville,
    					$searchwebsite,
    					$searchaddrees,
    					$codepostal,
    					$searchphone,
    					$searchCountry,
    			);
    			$ShowingBasicSearch = $this->_registerDb->searchAvance($dataAdvanceSearch);
    			 $this->view->getAllFactory = $ShowingBasicSearch;
    		}else{
    			$getAllFactory=$this->_registerDb->selectAllFactory(); 
               $this->view->getAllFactory =$getAllFactory;
    		}
    			
    	}catch (ErrorException $ex){
    		echo "Message:".$ex->getMessage();
    	}
    }
    	
    public function getAdvanceSearchPost(){
    	try{
    		$searchsociale 		= 	strlen(trim($this->_getParam('searchsociale')));
    		$searchemail 		=	strlen(trim($this->_getParam('searchemail')));
    		$searchstate 		= 	strlen(trim($this->_getParam('searchstate')));
    		$searchville 		= 	strlen(trim($this->_getParam('searchville')));
    		$searchwebsite 		= 	strlen(trim($this->_getParam('searchwebsite')));
    		$searchaddrees 		= 	strlen(trim($this->_getParam('searchaddrees')));
    		$searchcodepostal 	= 	strlen(trim($this->_getParam('searchcodepostal')));
    		$searchphone 		= 	strlen(trim($this->_getParam('searchphone')));
    		$searchCountry 		= 	strlen(trim($this->_getParam('searchCountry')));
    		if($searchsociale>0||$searchemail>0||$searchstate>0||$searchville>0||$searchwebsite>0||$searchaddrees>0||$searchcodepostal>0||$searchphone>0||$searchCountry>0){
    			return true;
    		}
    	}catch (ErrorException $ex){
    		echo "Error Said:".$ex->getMessage();
    	}
    }
    /* end basic and advance search */
    /* This is function addfactory */
    public function addfactoryAction(){
    	if ($this->_getParam('error') != '' || $this->_getParam('error') != null) {
    		$message = $this->_getParam('error');
    		if ($message =="phoneExist") {
    			$message = 'Input Phone number is required by numeric! ';
    			$this->view->error= $message;
    		}
    		 
    	}
            if (!$this->CheckTransactionUser()) {
              $this->redirectToIndex(); 
            }else{
           	$GetPost = $this->getRequest();
           	$lan = $this->_getParam('lang');
            if($GetPost->getPost('SaveFac')){
               try{ 
                require_once 'Zend/ResizeClassImage.php';
				$fname = isset ( $_FILES ['fileupload'] ['name'] ) ? $_FILES ['fileupload'] ['name'] : '';
				$fsize = $_FILES ['fileupload'] ['size'];
				$ftmp = $_FILES ['fileupload'] ['tmp_name'];
				$ftype = $_FILES ['fileupload'] ['type'];
				$image = new ResizeClassImage ();
				$baseUpload = $this->basePathUpload ();
				$defaultLogo=basename($baseUpload."/profile_photo.jpg");
                date_default_timezone_set('UTC');
                $PhoneNumber = $GetPost->getPost('fac_telephone');
                //$this->isValidPhoneNumber($PhoneNumber);
                $FactoryData=array();
				if ($fname == "" || $fname == false) {
                    $FactoryData = array(
                   'Fou_Name'=>$GetPost->getPost('fac_name'),
                   'Fou_RaisonSocial'=>$GetPost->getPost('fac_social'),
                   'Fou_Logo'=>$defaultLogo,
                   'Fou_Adresse1'=>$GetPost->getPost('fac_address1'),
                   'Fou_ZipCode'=>$GetPost->getPost('fac_zipcode'),
                   'Fou_City'=>$GetPost->getPost('fac_city'),
                   'Fou_Country'=>$GetPost->getPost('fac_country'),
                   'Fou_Telephone'=>$PhoneNumber,
                   'Fou_Fax'=>$GetPost->getPost('fac_fax'),
                   'Fou_Mail'=>$GetPost->getPost('fac_mail'),
                   'Fou_Website'=>$GetPost->getPost('fac_website'),
                   'Fou_Type'=>$GetPost->getPost('fac_type'),
                   'Fou_createdate'=>date('d-m-Y'),
                   'Fou_Siret'=>$GetPost->getPost('fac_siret'),
                   'Fou_Statut'=>$GetPost->getPost('fac_statut')
                );
            			$capitalRef = substr($GetPost->getPost ( 'fac_name' ), 0, 3);
                        $GetFactoryLastId = $this->_registerDb->insertRecordFactory($FactoryData);
                        $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($GetFactoryLastId);
                        $datas = array('Fou_Refcode'=>$orgReferenceSubstring);
                        $this->_registerDb->updateRefer($datas,$GetFactoryLastId);
            }else{
                $check_type = $image->checkType ( $ftype ); // check		                                           
						$check_size = $image->checkSize ( $fsize );                             
						if (true == $check_type) {
							if (true == $check_size) {
								$image->load ( $ftmp );
								$image->resize ( 500, 500 );
								// resize to width = 500px and
								// height =500p
								if (file_exists ( $baseUpload . '/' . $fname )) {
									$fname = $image->random ( 5 ) . '-' . $fname;
									$image->save ( $baseUpload . '/' . $fname ); 
									$image->resize ( 70, 70 );
									$image->save ( $baseUpload . '/thumb/' . $fname );
								} else {
									$image->save ( $baseUpload . '/' . $fname ); 
									$image->resize ( 70, 70 );
									$image->save ( $baseUpload . '/thumb/' . $fname );
								}
								
								 $FactoryData = array(
                               'Fou_Name'=>$GetPost->getPost('fac_name'),
                               'Fou_RaisonSocial'=>$GetPost->getPost('fac_social'),
                               'Fou_Logo'=>$fname,
                               'Fou_Adresse1'=>$GetPost->getPost('fac_address1'),
                               'Fou_ZipCode'=>$GetPost->getPost('fac_zipcode'),
                               'Fou_City'=>$GetPost->getPost('fac_city'),
                               'Fou_Country'=>$GetPost->getPost('fac_country'),
                               'Fou_Telephone'=>$GetPost->getPost('fac_telephone'),
                               'Fou_Fax'=>$GetPost->getPost('fac_fax'),
                               'Fou_Mail'=>$GetPost->getPost('fac_mail'),
                               'Fou_Website'=>$GetPost->getPost('fac_website'),
                               'Fou_Type'=>$GetPost->getPost('fac_type'),
                               'Fou_Siret'=>$GetPost->getPost('fac_siret'),
                               'Fou_Statut'=>$GetPost->getPost('fac_statut') 
                            );
                       
                        $capitalRef = substr($GetPost->getPost ( 'fac_name' ), 0, 3);
                        $GetFactoryLastId = $this->_registerDb->insertRecordFactory($FactoryData);
                        $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($GetFactoryLastId);
                        $datas = array('Fou_Refcode'=>$orgReferenceSubstring);
                        $this->_registerDb->updateRefer($datas,$GetFactoryLastId);
							
						}
					}
                
            }
            $lan = $this->_getParam ( 'lang' );
			$this->_redirect ( $lan . '/factory/index?success=add' );
			throw new Exception ( 'Can not insert factory!' );  
        
        }catch (ErrorException $ex) {
        		echo "Message Error:" . $ex->getMessage();
        }
        }else{
               
            }    
    }
   }//function
    
    /* The end function addfactory */
    /* This is function editfactory */
 
     public function editfactoryAction(){
           
           $GetPost = $this->getRequest();
    	   $lan = $this->_getParam('lang');
           $idFactory=$GetPost->getPost('hiddenID');
           
            if (!$this->CheckTransactionUser()) {
              $this->redirectToIndex();  
            }else{
          
            if($GetPost->getPost('UpdateFactory')){
                 try{
                    require_once 'Zend/ResizeClassImage.php';
    				$fname = isset ( $_FILES ['fileupload'] ['name'] ) ? $_FILES ['fileupload'] ['name'] : '';
    				$fsize = $_FILES ['fileupload'] ['size'];
    				$ftmp = $_FILES ['fileupload'] ['tmp_name'];
    				$ftype = $_FILES ['fileupload'] ['type'];
    				$image = new ResizeClassImage ();
    				$baseUpload = $this->basePathUpload ();
    				$defaultLogo=basename($baseUpload."/profile_photo.jpg");
                    date_default_timezone_set('UTC');
                    $FactoryData=array();
    				if ($fname == "" || $fname == false) {
                        $FactoryData = array(
                       'Fou_Name'=>$GetPost->getPost('fac_name'),
                       'Fou_RaisonSocial'=>$GetPost->getPost('fac_social'),
                       'Fou_Adresse1'=>$GetPost->getPost('fac_address1'),
                       'Fou_ZipCode'=>$GetPost->getPost('fac_zipcode'),
                       'Fou_City'=>$GetPost->getPost('fac_city'),
                       'Fou_Country'=>$GetPost->getPost('fac_country'),
                       'Fou_Telephone'=>$GetPost->getPost('fac_telephone'),
                       'Fou_Fax'=>$GetPost->getPost('fac_fax'),
                       'Fou_Mail'=>$GetPost->getPost('fac_mail'),
                       'Fou_Website'=>$GetPost->getPost('fac_website'),
                       'Fou_Type'=>$GetPost->getPost('fac_type'),
                       'Fou_modifdate'=>date('d-m-Y'),
                       'Fou_Siret'=>$GetPost->getPost('fac_siret'),
                       'Fou_Statut'=>$GetPost->getPost('fac_statut')
                    );
                
                
                $capitalRef = substr($GetPost->getPost ( 'fac_name' ), 0, 3);
                 $this->_registerDb->updateRecordFactory($FactoryData,$idFactory);
                $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($idFactory);
                $datas = array('Fou_Refcode'=>$orgReferenceSubstring);
                $this->_registerDb->updateRefer($datas,$idFactory);
             
                }else{
                             $check_type = $image->checkType ( $ftype ); // check		                                           
    						 $check_size = $image->checkSize ( $fsize );  
    						 $results = $this->_registerDb->selectImageUnlink($idFactory);
    						  foreach($results as $result){
    						  	 $filename = $result['Fou_Logo'];
    						  	 unlink($baseUpload . '/' .$filename);
    						  	 unlink($baseUpload . '/thumb/'.$filename);
    						  }                        
    						if (true == $check_type) {
    							if (true == $check_size) {
    								$image->load ( $ftmp );
    								$image->resize ( 500, 500 );
    								
    								if (file_exists ( $baseUpload . '/' . $fname )) {
    									$fname = $image->random ( 5 ) . '-' . $fname;
    									$image->save ( $baseUpload . '/' . $fname ); 
    									$image->resize ( 70, 70 );
    									$image->save ( $baseUpload . '/thumb/' . $fname );
    								} else {
    									$image->save ( $baseUpload . '/' . $fname ); 
    									$image->resize ( 70, 70 );
    									$image->save ( $baseUpload . '/thumb/' . $fname );
    								}
    								
    								 $FactoryData = array(
                                   'Fou_Name'=>$GetPost->getPost('fac_name'),
                                   'Fou_RaisonSocial'=>$GetPost->getPost('fac_social'),
                                   'Fou_Logo'=>$fname,
                                   'Fou_Adresse1'=>$GetPost->getPost('fac_address1'),
                                   'Fou_ZipCode'=>$GetPost->getPost('fac_zipcode'),
                                   'Fou_City'=>$GetPost->getPost('fac_city'),
                                   'Fou_Country'=>$GetPost->getPost('fac_country'),
                                   'Fou_Telephone'=>$GetPost->getPost('fac_telephone'),
                                   'Fou_Fax'=>$GetPost->getPost('fac_fax'),
                                   'Fou_Mail'=>$GetPost->getPost('fac_mail'),
                                   'Fou_Website'=>$GetPost->getPost('fac_website'),
                                   'Fou_Type'=>$GetPost->getPost('fac_type'),
                                   'Fou_Siret'=>$GetPost->getPost('fac_siret'),
                                   'Fou_Statut'=>$GetPost->getPost('fac_statut') 
                                );
                          $capitalRef = substr($GetPost->getPost ( 'fac_name' ), 0, 3);
                          $GetFactoryLastId = $this->_registerDb->updateRecordFactory($FactoryData,$idFactory);
                          $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($idFactory);
                          $datas = array('Fou_Refcode'=>$orgReferenceSubstring);
                          $this->_registerDb->updateRefer($datas,$idFactory);
    						
    						}
    					}
                    
                }
                $lan = $this->_getParam ( 'lang' );
    			$this->_redirect ( $lan . '/factory/index?success=update' );
    			throw new Exception ( 'Can not update factory!' );
                
           
            }catch (ErrorException $ex) {
            		echo "Message Error:" . $ex->getMessage();
            }
        }else{
            $idFac=$this->_getParam ( 'id' );
               if($this->_getParam ( 'actions' ) == "edit"){
                 try{
                     $getEditFactory=$this->_registerDb->SelectEditFactory($idFac);
                     $this->view->getEditFactory=$getEditFactory;
                 }catch(ErrorException $ex){
                 	echo "Message Error:" . $ex->getMessage();}
                
               }
            }
       } 
    }
    
    /* The end function addfactory */
    public function unlinkImageFolder($id) {
    	try {
		$unlinkImagesFromFolder = $this->_registerDb->unlinkImagesFromFolderById ( $id );
		$pathName = $this->basePathUpload ();
		foreach ( $unlinkImagesFromFolder as $unlinkRows ) {
			$nameImage = $unlinkRows ['Fou_Logo'];
			unlink ( $pathName . '/' . $nameImage );
			unlink ( $pathName . '/' . "thumb/" . $nameImage );
		}
		} catch ( Zend_Session_Exception $e ) {
			echo "Message:" . $e->getMessage ();
		
		}
	}
    public function deletemulti() {
    	try {
    	$idCount = $this->getRequest ()->getPost ( "checkRows" );
    	$this->_registerDb->multipledelete ( $idCount );
    	} catch ( Zend_Session_Exception $e ) {
    		echo "Message:" . $e->getMessage ();
    		
    	}
	}
    public function deletefactoryAction(){
        $lan = $this->_getParam ( 'lang' );
        $id =  $this->_getParam ( 'ids' );
        $action=  $this->_getParam ( 'actions' );
        
        if(! $this->CheckTransactionUser ()) {
				try {
				    Zend_Session::start ();
					$this->redirectToIndex();
                    
				} catch ( Zend_Session_Exception $e ) {
					echo "Message:" . $e->getMessage ();
					session_start ();
				}
	   }else {
				try {
					 if($action=="deletefactory"){
					 	$this->unlinkImageFolder ( $id );
					 	$deleteFactory = $this->_registerDb->deleteFactory($id);
					 	if ($deleteFactory) {
					 		$lan = $this->_getParam ( 'lang' );
					 	    $this->_redirect ( $lan . '/factory/index?success=delete' );
					 	} 
					 }
				 /*Multiple delete*/
				if ($this->getRequest ()->getPost ( "BtnDelFactory" )) {
						if ($this->getRequest ()->getPost ( "multiActions" )=="Delete") {
							$idFactory = $this->getRequest ()->getPost ( "checkRows" );
							for($i = 0; $i < count ( $idFactory );$i++) {
								$id = $idFactory [$i];
								$this->unlinkImageFolder ( $id );
							}
							$this->deletemulti ();
							$lan = $this->_getParam ( 'lang' );
							$this->_redirect ( $lan . '/factory/index?success=delete' );
							exit ();
							
						} 
					}
					
				} catch ( ErrorException $exerr ) {
					echo "Messsage" . $exerr->getMessage ();
				}
	   }
    }
    public function viewfactoryAction(){
      try{
      	$actions = $this->_getParam('actions');
      	$ids     = $this->_getParam('id');
    	if(!$this->CheckTransactionUser()){
    		$this->redirectToIndex();
    	}else{
    		if($actions=="overview"){
    			$factoryOverview = $this->_registerDb->SelectOverViewFactory($ids);
    			$this->view->factoryOverview = $factoryOverview;
    		}
    	}
      }catch (ErrorException $ex){
      	echo "Message:".$ex->getMessage();
      }
        
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
    
    	public function basePathUpload() {
		$pathupload = realpath(APPLICATION_PATH . '/../public/data/uploads');
		return $pathupload;
	}
    
    /* END FACTORY*/
    
    
    /* Type Equipement Reference ,develop dim sidet*/
	public function typeequipementviewAction(){
			try{
				$actions = $this->_getParam('actions');
				$ids     = $this->_getParam('id');
				if(!$this->CheckTransactionUser()){
					$this->redirectToIndex();
				}else{
					if($actions=="overview"){
						$TypeOverview = $this->_typeEquipement->SelectOverViewTypeEquipement($ids);
						$this->view->TypeOverview = $TypeOverview;
					}
				}
			}catch (ErrorException $ex){
				echo "Message:".$ex->getMessage();	
		
	}
}
        public function typeequipementindexAction(){
        	if ($this->_getParam('success') != ''
        			|| $this->_getParam('success') != null) {
        		$message = $this->_getParam('success');
        		if ($message =="add") {
        			$message = 'Type Equipement  had been added! ';
        			$this->view->success = $message;
        		}else if($message=="delete"){
        			$message = 'Type Equipement  had been deleted!';
        			$this->view->success = $message;
        		}else if($message=="update"){
        			$message = 'Type Equipement  had been updated!';
        			$this->view->success = $message;
        		}
        		 
        	}
        
        	
        	if (!$this->CheckTransactionUser()) {
        		$this->redirectToIndex();
        	}else{
        		$GetPost = $this->getRequest();
        		$lan = $this->_getParam('lang');
        		if($GetPost->getPost('btnaddtype')){
		             try{
		             	settype($dataTypeEquipement,"array");
		             	$dtcreatedate=date('d-m-Y');
		             	$dataTypeEquipement=array(
		             			'type_name'=>$GetPost->getPost('txttypename'),
		             			'fac_id'=>$GetPost->getPost('cboFactoryID'),
		             			'type_description'=>$GetPost->getPost('txttypedescription'),
		             			'type_createdate'=>$dtcreatedate
		             			);
		             	
		             	
		             	$capitalRef = substr($GetPost->getPost ( 'txttypename' ),0, 3);
		             	$GetEquipementtypeLastId =$this->_typeEquipement->insertTypeEquipement($dataTypeEquipement);
		             	$orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($GetEquipementtypeLastId);
		             	$datas = array('type_ref'=>$orgReferenceSubstring);
		             	$this->_typeEquipement->updateRefer($datas,$GetEquipementtypeLastId);
		             	
		             	$this->_redirect ( $lan . '/factory/typeequipementindex?success=add' );
		             	throw new Exception ( 'Can not insert Type Equipement !' );
		             	
		             }catch ( ErrorException $ex){
		             	echo "Message:".$ex->getMessage();
             		}
        		}else{
        			 $getTypeEquipement=$this->_typeEquipement->selectAllTypeEquipement();
        			 $this->view->getTypeEquipement=$getTypeEquipement;
        			 /*Select Factory*/
        			 $getFactory=$this->_typeEquipement->selectFactory();
        			// print_r($getFactory);
        			 $this->view->getFactory=$getFactory;
        		}
        	}
        	
        	
        } 
        public function typeequipementeditAction(){
        	$id=$this->_getParam('id');
        	if (!$this->CheckTransactionUser()) {
        		$this->redirectToIndex();
        	}else{
        		$GetPost = $this->getRequest();
        		$lan = $this->_getParam('lang');
        		if($GetPost->getPost('btnupdatetype')){
        			$id = $this->getRequest()->getPost('editHidden');
        			try{
        				settype($dataTypeEquipement,"array");
        				$dtmodifydate=date('d-m-Y');
        				$dataTypeEquipement=array(
        						'type_name'=>$GetPost->getPost('txttypename'),
        						'fac_id'=>$GetPost->getPost('cboFactoryID'),
        						'type_description'=>$GetPost->getPost('txttypedescription'),
        						'type_modifdate'=>$dtmodifydate
        				);
        				
        				$capitalRef = substr($GetPost->getPost ( 'txttypename' ), 0, 3);
        				$this->_typeEquipement->updateTypeEquipement($dataTypeEquipement,$id);
        				$orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($id);
        				$datas = array('type_ref'=>$orgReferenceSubstring);
        				$this->_typeEquipement->updateRefer($datas,$id);
        				$this->_redirect ( $lan . '/factory/typeequipementindex?success=update' );
        				throw new Exception ( 'Can not update Type Equipement !' );
        	
        			}catch ( ErrorException $ex){
        				echo "Message:".$ex->getMessage();
        			}
        		}elseif($this->_getParam('actions')=='edit'){
        			$EditTypeEquipement=$this->_typeEquipement->SelectEditTypeEquipement($id);
        			$this->view->EditTypeEquipement=$EditTypeEquipement;
        			$getFactory=$this->_typeEquipement->selectFactory();
        			$this->view->getFactory=$getFactory;
        		}
        	}
        	 
        }
       
        public function deletemultiEquType() {
        	try {
        		$ids = $this->getRequest ()->getPost ( "checkRow" );
        		$this->_typeEquipement->multipledeleteEquipementType ( $ids);
        	} catch ( Zend_Session_Exception $e ) {
        		echo "Message:" . $e->getMessage ();
        
        	}
        }
        public function typeequipementdeleteAction(){
        	if (!$this->CheckTransactionUser()) {
        		$this->redirectToIndex();
        	}else{
        	 try{
        	 	$lan = $this->_getParam ( 'lang' );
        	 	$actions = $this->_getParam('actions');
        	 	$ids     = $this->_getParam('id');
        	 	if($actions=="delete"){
        	 		$sucess = $this->_typeEquipement->deletedEquipementType($ids);
        	 		if($sucess==TRUE){
        	 			$this->_redirect ($lan . '/factory/typeequipementindex?success=delete' );
        	 			exit();
        	 		}
        	 	}if ($this->getRequest ()->getPost ( "btnDeleteMulti" )) {
						if ($this->getRequest ()->getPost ( "multiAction" )=="Delete") {
							$this->deletemultiEquType ();
							$lan = $this->_getParam ( 'lang' );
							$this->_redirect ($lan . '/factory/typeequipementindex?success=delete' );
							exit ();
							
						} 
					}
					
        	 }catch ( ErrorException $ex){
        	   echo "Message:".$ex->getMessage();
        		}
        	}
        }
    
    /* End Type Equipement Reference*/
    
    
        /* Equipement Reference*/
        public function equipementindexAction(){
        	if ($this->_getParam('success') != ''
        			|| $this->_getParam('success') != null) {
        		$message = $this->_getParam('success');
        		if ($message =="add") {
        			$message = 'Factory  had been added! ';
        			$this->view->success = $message;
        		}else if($message=="delete"){
        			$message = 'Factory  had been deleted!';
        			$this->view->success = $message;
        		}else if($message=="update"){
        			$message = 'Factory  had been updated!';
        			$this->view->success = $message;
        		}
        		 
        	}
        	
          try{
          	if(! $this->CheckTransactionUser ()){
          		$this->redirectToIndex();
          	}else{
          		$basicsearch = $this->_getParam ( 'btnbasicsearch' );
          		$BasicsearchName = $this->_getParam ( 'txtnamebasicsearch' );
          		$Advancesearch = $this->_getParam ( 'btnadvancesearch' );
          		
          		if($basicsearch){
          			$this->equipbasicsearch($BasicsearchName);
          		}else{	
          		$getallrecordEquipement = $this->_typeEquipement->getallrecordEquipement();
          		$this->view->getallrecordEquipement=$getallrecordEquipement;
          		}
          	}
          }catch(ErrorException $ex){
          	echo "Message:".$ex->getMessage();
          }
        }
        
        /*Starting of doing basic search*/
        public function equipbasicsearch($BasicsearchName){
        	try{
        		if(strlen(trim($BasicsearchName))>0){
        			if(get_magic_quotes_gpc()){
        				$BasicsearchName = stripslashes($BasicsearchName);
        			}
        			$BasicsearchName = mysql_real_escape_string($BasicsearchName);
        			$basicsearchEquipement = $this->_typeEquipement->basicsearchEquipement($BasicsearchName);
          			$this->view->getallrecordEquipement=$basicsearchEquipement;
        		}else{
        			$getallrecordEquipement = $this->_typeEquipement->getallrecordEquipement();
          			$this->view->getallrecordEquipement=$getallrecordEquipement;
        		}
        
        	}catch (ErrorException $ex){
        		echo "Message:".$ex->getMessage();
        	}
        }
        /*Ending of doing basic search*/
        
        public function equipementviewAction(){
        	if (! $this->CheckTransactionUser ()) {
        		try {
        			$this->redirectToIndex();
        		} catch ( Zend_Session_Exception $e ) {
        			echo "Message:" . $e->getMessage ();
        	
        		}
        	}else{
        		try {
        			$ids = $this->_getParam('id');
        			$Actions = $this->_getParam('actions');
        			if($Actions=="overview"){
        				$getrecordOverviewEquip = $this->_typeEquipement->getrecordOverviewEquip($ids);
        				$this->view->getrecordOverviewEquip= $getrecordOverviewEquip;
        				
        			}
        		} catch (Exception $e) {
        			echo "Message:" . $e->getMessage ();
        		}
        	}
        }
        
        public function equipementaddAction(){
        	if ($this->_getParam('error') != ''
        			|| $this->_getParam('error') != null) {
        		$message = $this->_getParam('error');
        		if ($message =="smallfile") {
        			$message = 'Please provide a smaller file [E/1].';
        			$this->view->error = $message;
        		}else if($message=="providefile"){
        			$message = 'Please provide another file type [E/2]!';
        			$this->view->error = $message;
        		}else if($message=="filetype"){
        			$message = 'Please provide another file type [E/3]!';
        			$this->view->error = $message;
        		}
        		 
        	}
        	
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
        			if($GetPost->getPost('btnaddequipement')){
        				$filedocxpdf = $this->uploadfileDocxPdf();
        				$fname = $this->uploadimages();
        				$getDataEquipement = array(
        						
        						'equip_Name'=>$GetPost->getPost('txtnamequipement'),
        						'equip_description'=>$GetPost->getPost('txtaequipement'),
        						'equip_createdate'=>date('d-m-Y'),
        						'equip_freqmaintance'=>$GetPost->getPost('txtFreqmaintenance'),
        						'equip_origine'=>$GetPost->getPost('txtOrigine'),
        						'equip_SKU'=>$GetPost->getPost('txtSKU'),
        						'equip_notice'=>$filedocxpdf,
        						'equip_datefab'=>$GetPost->getPost('dtpproduct'),
        						'equip_warranty'=>$GetPost->getPost('txtWarranty').' '.$GetPost->getPost('cboPeriod'),
        						'equip_warrantydate'=>$GetPost->getPost('dtpWarrantyDate'),
        						'type_id'=>$GetPost->getPost('cbotype'),
        						'equip_img'=>$fname
        				);
        				$addEquipementSucess = $this->_typeEquipement->addNewEquipement($getDataEquipement);
        			
        				if($addEquipementSucess==TRUE){
        					$this->_redirect ( $lan . '/factory/equipementindex?success=add' );
        					exit();
        				}
        			}else{
        			  $getProductType = $this->_typeEquipement->selectTypeTequipement();
        			 // print_r($getProductType);
        			  $this->view->getProductType=$getProductType;
        			}
        			
        		}catch (ErrorException $ex){
        			echo "Message".$ex->getMessage();
        		}
        	}
        }
        
        public function equipementeditAction(){
            try{
            	
            	if(! $this->CheckTransactionUser ()){
            		$this->redirectToIndex();
            	}else{
            		$GetPost = $this->getRequest();
            		$lan     = $this->_getParam('lang');
            		if($GetPost->getPost('btneditequipement')){
            			$ids = $this->getRequest()->getPost('equID');
            			$filedocxpdf = $this->UpdateuploadfileDocxPdf();
            			$fname = $this->uploadimages();
            			if($fname==""){
            			$getDataEquipement = array(
            		
            					'equip_Name'=>$GetPost->getPost('txtnamequipement'),
        						'equip_description'=>$GetPost->getPost('txtaequipement'),
        						'equip_modifdate'=>date('d-m-Y'),
        						'equip_freqmaintance'=>$GetPost->getPost('txtFreqmaintenance'),
        						'equip_origine'=>$GetPost->getPost('txtOrigine'),
        						'equip_SKU'=>$GetPost->getPost('txtSKU'),
        						'equip_notice'=>$filedocxpdf,
        						'equip_datefab'=>$GetPost->getPost('dtpproduct'),
        						'equip_warranty'=>$GetPost->getPost('txtWarranty').' '.$GetPost->getPost('cboPeriod'),
        						'equip_warrantydate'=>$GetPost->getPost('dtpWarrantyDate'),
        						'type_id'=>$GetPost->getPost('cbotype')
            			);
            			$UpdateEquipementSucess = $this->_typeEquipement->UpdateEquipement($getDataEquipement,$ids);
            			}elseif ($filedocxpdf==NULL){
            				$getDataEquipement = array(
            				
            						'equip_Name'=>$GetPost->getPost('txtnamequipement'),
            						'equip_description'=>$GetPost->getPost('txtaequipement'),
            						'equip_modifdate'=>date('d-m-Y'),
            						'equip_freqmaintance'=>$GetPost->getPost('txtFreqmaintenance'),
            						'equip_origine'=>$GetPost->getPost('txtOrigine'),
            						'equip_SKU'=>$GetPost->getPost('txtSKU'),
            						'equip_datefab'=>$GetPost->getPost('dtpproduct'),
            						'equip_warranty'=>$GetPost->getPost('txtWarranty').' '.$GetPost->getPost('cboPeriod'),
            						'equip_warrantydate'=>$GetPost->getPost('dtpWarrantyDate'),
            						'type_id'=>$GetPost->getPost('cbotype'),
            						'equip_img'=>$fname
            				);
            				$UpdateEquipementSucess = $this->_typeEquipement->UpdateEquipement($getDataEquipement,$ids);
            			}elseif($filedocxpdf==NULL AND $fname==""){
            				$getDataEquipement = array(
            				
            						'equip_Name'=>$GetPost->getPost('txtnamequipement'),
            						'equip_description'=>$GetPost->getPost('txtaequipement'),
            						'equip_modifdate'=>date('d-m-Y'),
            						'equip_freqmaintance'=>$GetPost->getPost('txtFreqmaintenance'),
            						'equip_origine'=>$GetPost->getPost('txtOrigine'),
            						'equip_SKU'=>$GetPost->getPost('txtSKU'),
            						'equip_datefab'=>$GetPost->getPost('dtpproduct'),
            						'equip_warranty'=>$GetPost->getPost('txtWarranty').' '.$GetPost->getPost('cboPeriod'),
            						'equip_warrantydate'=>$GetPost->getPost('dtpWarrantyDate'),
            						'type_id'=>$GetPost->getPost('cbotype')
            				);
            				$UpdateEquipementSucess = $this->_typeEquipement->UpdateEquipement($getDataEquipement,$ids);
            			}elseif($filedocxpdf!=NULL AND $fname!=""){
            				$getDataEquipement = array(
            				
            						'equip_Name'=>$GetPost->getPost('txtnamequipement'),
            						'equip_description'=>$GetPost->getPost('txtaequipement'),
            						'equip_modifdate'=>date('d-m-Y'),
            						'equip_freqmaintance'=>$GetPost->getPost('txtFreqmaintenance'),
            						'equip_origine'=>$GetPost->getPost('txtOrigine'),
            						'equip_SKU'=>$GetPost->getPost('txtSKU'),
            						'equip_notice'=>$filedocxpdf,
            						'equip_datefab'=>$GetPost->getPost('dtpproduct'),
            						'equip_warranty'=>$GetPost->getPost('txtWarranty').' '.$GetPost->getPost('cboPeriod'),
            						'equip_warrantydate'=>$GetPost->getPost('dtpWarrantyDate'),
            						'type_id'=>$GetPost->getPost('cbotype'),
            						'equip_img'=>$fname
            				);
            				$UpdateEquipementSucess = $this->_typeEquipement->UpdateEquipement($getDataEquipement,$ids);
            			} 
            			if($UpdateEquipementSucess==TRUE){
            				$this->_redirect ( $lan . '/factory/equipementindex?success=update' );
            				exit();
            			}
            		}else{
            			 $ids = $this->_getParam('id');
            			$selectEquipementForUpdate = $this->_typeEquipement->selectEquipementForUpdate($ids);
            			$this->view->selectEquipementForUpdate = $selectEquipementForUpdate;
            			
            		}
            	}
            	$getProductType = $this->_typeEquipement->selectTypeTequipement();
            	$this->view->getProductType=$getProductType;
            }catch (ErrorException $ex){
        			echo "Message".$ex->getMessage();
        			
        		}
        }
        
        public function equipementdeleteAction(){
        	try {
        	if(! $this->CheckTransactionUser ()){
            		$this->redirectToIndex();
            	}else{
            		$lan = $this->_getParam ( 'lang' );
            		$ids = $this->_getParam('id');
            		$actions= $this->_getParam('actions');
            		$buttonMulitDelete = $this->getRequest()->getPost('btnDeleteMultiequip');
            		$txtmultiAction = $this->getRequest()->getPost('multiAction');
            		if($actions=="delete"){
            			$successdel = $this->_typeEquipement->deleteEquipement($ids);
            			if($successdel==TRUE){
            				$this->_redirect ( $lan . '/factory/equipementindex?success=delete' );
            				exit();
            			}
            		}elseif($buttonMulitDelete){
            			if($txtmultiAction=="delete"){
            				$getIDS =  $this->getRequest()->getPost('checkRow');
            				$this->_typeEquipement->multipledeleteEquipement($getIDS);
            				$this->_redirect ( $lan . '/factory/equipementindex?success=delete' );
            				exit();
            			}
            		}
            	}
          }catch(ErrorException $ex){
          	echo "Message:".$ex->getMessage();
          }
        }
    
    /* End Equipement Reference*/
        public function basePathUploadFileDocxPdf() {
        	$pathupload = realpath(APPLICATION_PATH . '/../public/data/documents');
        	return $pathupload;
        }
    
    /*CHECK UER */
    /*Upload images*/
    public function uploadimages(){
    	require_once 'Zend/ResizeClassImage.php';
    	$fname = isset ( $_FILES ['fileupload'] ['name'] ) ? $_FILES ['fileupload'] ['name'] : '';
    	$fsize = $_FILES ['fileupload'] ['size'];
    	$ftmp = $_FILES ['fileupload'] ['tmp_name'];
    	$ftype = $_FILES ['fileupload'] ['type'];
    	$image = new ResizeClassImage ();
    	$baseUpload = $this->basePathUpload ();
    	$defaultLogo=basename($baseUpload."/profile_photo.jpg");
    	$check_type = $image->checkType ( $ftype ); // check
    	$check_size = $image->checkSize ( $fsize );
    	if ($fname==""){
    		$fname ="";
    	}elseif (true == $check_type) {
    		if (true == $check_size) {
    			$image->load ( $ftmp );
    			$image->resize ( 500, 500 );
    			if (file_exists ( $baseUpload . '/' . $fname )) {
    				$fname = $image->random ( 5 ) . '-' . $fname;
    				$image->save ( $baseUpload . '/' . $fname );
    				$image->resize ( 70, 70 );
    				$image->save ( $baseUpload . '/thumb/' . $fname );
    			} else {
    				$image->save ( $baseUpload . '/' . $fname );
    				$image->resize ( 70, 70 );
    				$image->save ( $baseUpload . '/thumb/' . $fname );
    			}
    		}
    		
    	}
       return $fname;
    }
    /*Upload file docx-pdf*/
    public function uploadfileDocxPdf(){
    	$allowedExts = array(
    			"pdf",
    			"doc",
    			"docx"
    	);
    	
    	$allowedMimeTypes = array(
    			'application/msword',
    			'application/pdf',
    			'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    	);
    	//print_r($_FILES["filedocxpdf"]["type"]);
    	if($_FILES["filedocxpdf"]["name"]==""){
    		$filedocxpdf=" ";
    	}else{
    	$baseDocxPdf= $this->basePathUploadFileDocxPdf();
    	$extension = end(explode(".", $_FILES["filedocxpdf"]["name"]));
    	
    	if ( 20000*20000 < $_FILES["filedocxpdf"]["size"]  ) {
    		$lan = $this->_getParam ( 'lang' );
    		$this->_redirect ( $lan . '/factory/equipementadd?error=smallfile' );
    		exit();
    		//die( 'Please provide a smaller file [E/1].' );
    	}
    	
    	if ( ! ( in_array($extension, $allowedExts ) ) ) {
    		$lan = $this->_getParam ( 'lang' );
    		$this->_redirect ( $lan . '/factory/equipementadd?error=providefile' );
    		exit();
    		///die( 'Please provide another file type [E/2]' );
    	}
    	if(file_exists($baseDocxPdf."/".$_FILES["filedocxpdf"]["name"])){
    		$resources = $this->random().'-'.$_FILES["filedocxpdf"]["name"];
    		move_uploaded_file($_FILES["filedocxpdf"]["tmp_name"],$baseDocxPdf."/".$resources);
    		$filedocxpdf = $resources;
    	}elseif ( in_array( $_FILES["filedocxpdf"]["type"], $allowedMimeTypes ) )
    	{
    		move_uploaded_file($_FILES["filedocxpdf"]["tmp_name"],$baseDocxPdf."/". $_FILES["filedocxpdf"]["name"]);
    		$filedocxpdf = $_FILES["filedocxpdf"]["name"];
    	}
    	
    	else
    	{
    		$lan = $this->_getParam ( 'lang' );
    		$this->_redirect ( $lan . '/factory/equipementadd?error=filetype' );
    		exit();
    		//die( 'Please provide another file type [E/3]' );
    	}
    	}
       return $filedocxpdf;
    }
    
    public function UpdateuploadfileDocxPdf(){
    	$allowedExts = array(
    			"pdf",
    			"doc",
    			"docx"
    	);
    	 
    	$allowedMimeTypes = array(
    			'application/msword',
    			'application/pdf',
    			'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    	);
    	if($_FILES["filedocxpdf"]["name"]==""){
    		$filedocxpdf=NULL;
    	}else{
    		$baseDocxPdf= $this->basePathUploadFileDocxPdf();
    		$extension = end(explode(".", $_FILES["filedocxpdf"]["name"]));
    		 
    		if ( 20000*20000 < $_FILES["filedocxpdf"]["size"]  ) {
    			$lan = $this->_getParam ( 'lang' );
    			$this->_redirect ( $lan . '/factory/equipementedit?error=smallfile' );
    			exit();
    		}
    		 
    		if ( ! ( in_array($extension, $allowedExts ) ) ) {
    			$lan = $this->_getParam ( 'lang' );
    			$this->_redirect ( $lan . '/factory/equipementedit?error=providefile' );
    			exit();
    		}
    		if(file_exists($baseDocxPdf."/".$_FILES["filedocxpdf"]["name"])){
    			$resources = $this->random().'-'.$_FILES["filedocxpdf"]["name"];
    			move_uploaded_file($_FILES["filedocxpdf"]["tmp_name"],$baseDocxPdf."/".$resources);
    			$filedocxpdf = $resources;
    		}elseif ( in_array( $_FILES["filedocxpdf"]["type"], $allowedMimeTypes ) )
    		{
    			move_uploaded_file($_FILES["filedocxpdf"]["tmp_name"],$baseDocxPdf."/". $_FILES["filedocxpdf"]["name"]);
    			$filedocxpdf = $_FILES["filedocxpdf"]["name"];
    		}
    		 
    		else
    		{
    			$lan = $this->_getParam ( 'lang' );
    			$this->_redirect ( $lan . '/factory/equipementedit?error=filetype' );
    			exit();
    		}
    	}
    	return $filedocxpdf;
    }
    /*phone validation*/
     
       protected function isValidPhoneNumber($PhoneNumber){
       	 try{
       	 	if($PhoneNumber!=""){
       	 		$lan = $this->_getParam('lang');
       	 		$pattern = "/^[\(\)\.\- ]{0,}[0-9]{3}[\(\)\.\- ]{0,}[0-9]{3}[\(\)\.\- ]{0,}[0-9]{3,10}[\(\)\.\- ]{0,}$/";
       	 		if(preg_match($pattern,$PhoneNumber)){
       	 			return TRUE;
       	 		}else {
       	 			$this->_redirect($lan."/factory/addfactory?error=phoneExist");
       	 		}
       	 	}
       	 	
       	 }catch(ErrorException $ex){
       	 	echo "Message:".$ex->getMessage();
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
		 $lan = $this->_getParam('lang');
         $this->_redirect($lan . '/index/index');
         exit();
         
     
	}
    
	function random($length=20) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$size = strlen($chars);
		$str = '';
		for ($i = 0; $i < $length; $i++) {
			$str .= $chars[rand(0, $size - 1)];
		}
		return $str;
	}
	/* develope: dim sidet  */
	public function __destruct(){
		$this->getLibBaseUrl;
		$this->_Categories;
		$this->_registerDb;
		session_write_close();
	}
    
    /*END CHECK USER*/	
}


