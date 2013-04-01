    <?php
				/*
				 * @Developer 	: Doeun @Copyright 	: 13-02-2013 Module : Create
				 * Organisme Description : Delete, update, insert and selete
				 * record Company : Mapring
				 */
				require_once 'Zend/Controller/Plugin/Abstract.php';
				require_once 'Zend/function.php';
				class OrganismeController extends Zend_Controller_Action {
					private $_registerDb  		=	null;
					public $getLibBaseUrl 		= 	null;
					public $basicSearch 	    =	null;
					public $GetModelOrganize 	= 	null;
					public function init() {
						/* Initialize action controller here */
						$this->view->controller = $this->_request->getParam ( 'controller' );
						$this->view->action = $this->_request->getParam ( 'action' );
						$this->getLibBaseUrl = new Zend_View_Helper_BaseUrl ();
						$this->GetModelOrganize = new Application_Model_ModOrganizeDb ();
						$this->_helper->ajaxContext->addActionContext('deleteOrganisme1','json')->initContext();
						// call function for dynamic sidebar
						$this->_Categories = new Application_Model_ModCatTerm ();
						$parent_id = $this->_getParam ( 'controller' );
						$this->view->secondSideBar = $this->_Categories->showCateParent ( $parent_id );
						
						
					}
					public function indexAction() {
						$success=$this->_getParam ( 'success' );
						if($success=='add'){
							$message='Organisme has been added successfully !';
							$this->view->success=$message;
						}else if($success=='update'){
							$message='Organisme has been updated successfully !';
							$this->view->success=$message;
						}else if($success=='delete'){
							$message='Organisme has been deleted successfully !';
							$this->view->success=$message;
						}
						
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
								
								/* Function selete Com_record */
								$getRefCodeToview = $this->GetModelOrganize->SelectRefCode ();
								$this->view->RefCodesFromController = $getRefCodeToview;
								
								/* Function selete Com_type */
								
								$getComTypeToview = $this->GetModelOrganize->SelectRefComType ();
								$this->view->RefCodeTypeFromController = $getComTypeToview;
							  if($this->_getParam('btnbasicsearch')){
							 	 $this->basicSearch();
							  }else if($this->_getParam('BtnadvanceSearch')){
							  	   $this->advanceSearch();
							  }else {
							 	$getAllDataToview = $this->GetModelOrganize->selectAllDataForOrganismeMenu ();
							 	$this->view->getfivetable = $getAllDataToview;
							    }
							   
							} catch ( ErrorException $exe ) {
								echo "Message:" . $exe->getMessage ();
							}
						}
					}
					public function unlinkImageFolder($id) {
						$this->redirectToIndex();
						$unlinkImagesFromFolder = $this->GetModelOrganize->unlinkImagesFromFolderById ( $id );
						$pathName = $this->basePathUpload ();
						foreach ( $unlinkImagesFromFolder as $unlinkRows ) {
							$nameImage = $unlinkRows ['Com_Logo'];
							unlink ( $pathName . '/' . $nameImage );
							unlink ( $pathName . '/' . "thumb/" . $nameImage );
						}
					}
					/* basic and advance search */
					public function basicSearch(){
						try{
							$SearchNameOrganisme = $this->_getParam('Basicseacrhname');
							if(strlen(trim($SearchNameOrganisme))>0){
								if(get_magic_quotes_gpc()){
									$SearchNameOrganisme = stripslashes($SearchNameOrganisme);
								}
								$SearchNameOrganisme = mysql_real_escape_string($SearchNameOrganisme);
								$ShowingBasicSearch = $this->GetModelOrganize->searchbasic($SearchNameOrganisme);
								$this->view->getfivetable = $ShowingBasicSearch;
							}else{
								$getAllDataToview = $this->GetModelOrganize->selectAllDataForOrganismeMenu ();
								$this->view->getfivetable = $getAllDataToview;
							}
						
						}catch (ErrorException $ex){
							echo "Message:".$ex->getMessage();
						}
					}
					
					public function advanceSearch(){
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
								$ShowingBasicSearch = $this->GetModelOrganize->searchAvance($dataAdvanceSearch);
								//var_dump($ShowingBasicSearch);
							   $this->view->getfivetable = $ShowingBasicSearch;
						}else{
							$getAllDataToview = $this->GetModelOrganize->selectAllDataForOrganismeMenu ();
							$this->view->getfivetable = $getAllDataToview;
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
					/* Start of Deleting data from Organisme*/
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
							try {
								$lan = $this->_getParam ( 'lang' );
								$id =  $this->_getParam ( 'id' );
                                $actions=  $this->_getParam ( 'actions' );
                                //$this->getRequest ()->getPost ( 'process' )==1
                                
								if ( $actions=='delete') {
									$this->unlinkImageFolder ( $id );
									$id =  $this->_getParam ( 'id' );
									$deleteOrganizms = $this->GetModelOrganize->deleteAllDataFromOrganismeMenu($id);
											if ($deleteOrganizms) {
												$lan = $this->_getParam ( 'lang' );
												$this->_redirect ( $lan . '/organisme/index?success=delete' );
											}
								}
								/* In cases of multiple delete */
								if ($this->getRequest ()->getPost ( "BtnDelOrg" )) {
									if ($this->getRequest ()->getPost ( "multiAction" ) == "Delete") {
										$idOrganisme = $this->getRequest ()->getPost ( "checkRow" );
										$lan = $this->_getParam ( 'lang' );
										for($i = 0; $i < count ( $idOrganisme ); $i ++) {
											$id = $idOrganisme [$i];
											$this->unlinkImageFolder ( $id );
										}
										$this->deletemulti ();
										$this->_redirect ( $lan . '/Organisme/index?success=delete' );
										exit ();
									} else {
										$lan = $this->_getParam ( 'lang' );
										?>
										
								<script type="text/javascript">
				                                function seleteDel(){
				                                alert("Please select on item to delete!");
				                                 return false;
				                                }
				                                seleteDel();
                                </script>
							<?php
										// $this->_redirect ( $lan
									// .'/Organisme/index/' );
										// exit();
									}
								}
							} catch ( ErrorException $exerr ) {
								echo "Messsage" . $exerr->getMessage ();
							}
						}
					}
					
					/*
					 * @Module: Multiple delte Desc : Delete record from
					 * database and remore images from folder
					 */
					public function deletemulti() {
						$idCount = $this->getRequest ()->getPost ( "checkRow" );
						$this->GetModelOrganize->multipledelete ( $idCount );
					}
					
					/*
					 * Starting for Editing organisme
					 */
					public function CompanynameEmailCompare() {
						$this->redirectToIndex();
						$getTableUser = $this->GetModelOrganize->CompareComparenameAndEmail();
						return $getTableUser;
					}
					
					public function editAction() {
						$IdOperation = $this->_getParam ( 'getidform' );
						if (! $this->CheckTransactionUser ()) {
							try {
								Zend_Session::start ();
								$this->redirectToIndex();
							} catch ( Zend_Session_Exception $e ) {
								echo "Message:" . $e->getMessage ();
								session_start ();
							}
						} else {
							if ($this->getRequest ()->getPost ( 'OrgUpdate' )) {
								try {
									$GetPost = $this->getRequest ();
									 //$ids = $this->_getParam ( 'getidform' );									
									//$user= $GetPost->getPost ( 'hiddenID');
									$lan = $this->_getParam ( 'lang' );
									$dataUpdateCompany = array();
									require_once 'Zend/ResizeClassImage.php';
									$fname = isset ( $_FILES ['fileupload'] ['name'] ) ? $_FILES ['fileupload'] ['name'] : '';
									echo $fname;
									$fsize = $_FILES ['fileupload'] ['size'];
									$ftmp = $_FILES ['fileupload'] ['tmp_name'];
									$ftype = $_FILES ['fileupload'] ['type'];
									$image = new ResizeClassImage ();
									$baseUpload = $this->basePathUpload ();
									if ($fname == "" || $fname == false) {
									$dataUpdateCompany = array (
											'Com_Name' => $GetPost->getPost ( 'Org_name' ),
											'Com_RaisonSocial' => $GetPost->getPost ( 'Org_social' ),
											'Com_Address1' => $GetPost->getPost ( 'Org_address1' ),
											'Com_ZipCode' => $GetPost->getPost ( 'Org_zipcode' ),
											'Com_City' => $GetPost->getPost ( 'Org_city' ),
											'Com_Country' => $GetPost->getPost ( 'Org_country' ),
											'Com_Telephone' => $GetPost->getPost ( 'Org_telephone' ),
											'Com_Fax' => $GetPost->getPost ( 'Org_fax' ),
											'Com_Mail' => $GetPost->getPost ( 'Org_mail' ),
											'Com_Website' => $GetPost->getPost ( 'Org_website' ),
											'Com_modifdate' => date('d-m-Y'),
											'Com_Type' => $GetPost->getPost ( 'Org_type' ),
											'Com_Siret' => $GetPost->getPost ( 'Org_siret' ),
											'Com_Status' => $GetPost->getPost ( 'Org_statut' ),
											'Com_description' => $GetPost->getPost ( 'Org_decription' ),
											'Com_Refcode' => $GetPost->getPost ( 'Org_RefCode' ),
											'Com_VAT' => $GetPost->getPost ( 'Org_vat' ),
											'Com_Level' => $GetPost->getPost ( 'Org_Level' )
									);
    								    $this->GetModelOrganize->UpdateRecordCompany($dataUpdateCompany,$IdOperation) ;
                                        $capitalRef = substr($GetPost->getPost ( 'Org_name' ), 0, 3);
                                        $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($IdOperation);
                                        $datas = array('Com_reference'=>$orgReferenceSubstring);
                                        $this->GetModelOrganize->updateRefer($datas,$IdOperation);
								}else{
									$imageunlink = $this->unlinkImageFolder ( $IdOperation );
									// check file type
									
									$check_type = $image->checkType ( $ftype );
									// check file size
									$check_size = $image->checkSize ( $fsize );
									if (true == $check_type) {
										if (true == $check_size) {
											$image->load ( $ftmp );
											$image->resize ( 500, 500 );
											// resize to width = 500px and
											// height =500p
											if (file_exists ( $baseUpload . '/' . $fname )) {
												$fname = $image->random ( 5 ) . '-' . $fname;
												$image->save ( $baseUpload . '/' . $fname ); // =
												// move_upload_file()
												// create
												// thumbnail
												$image->resize ( 70, 70 );
												$image->save ( $baseUpload . '/thumb/' . $fname );
											} else {
												$image->save ( $baseUpload . '/' . $fname ); // =
												// move_upload_file()
												// create
												// thumbnail
												$image->resize ( 70, 70 );
												$image->save ( $baseUpload . '/thumb/' . $fname );
											}
											
											$dataUpdateCompany = array (
													'Com_Name' => $GetPost->getPost ( 'Org_name' ),
													'Com_RaisonSocial' => $GetPost->getPost ( 'Org_social' ),
													'Com_Logo' =>$fname,
													'Com_Address1' => $GetPost->getPost ( 'Org_address1' ),
													'Com_ZipCode' => $GetPost->getPost ( 'Org_zipcode' ),
													'Com_City' => $GetPost->getPost ( 'Org_city' ),
													'Com_Country' => $GetPost->getPost ( 'Org_country' ),
													'Com_Telephone' => $GetPost->getPost ( 'Org_telephone' ),
													'Com_Fax' => $GetPost->getPost ( 'Org_fax' ),
													'Com_Mail' => $GetPost->getPost ( 'Org_mail' ),
													'Com_Website' => $GetPost->getPost ( 'Org_website' ),
													'Com_modifdate' => date('d-m-Y'),
													'Com_Type' => $GetPost->getPost ( 'Org_type' ),
													'Com_Siret' => $GetPost->getPost ( 'Org_siret' ),
													'Com_Status' => $GetPost->getPost ( 'Org_statut' ),
													'Com_description' => $GetPost->getPost ( 'Org_decription' ),
													'Com_Refcode' => $GetPost->getPost ( 'Org_RefCode' ),
													'Com_VAT' => $GetPost->getPost ( 'Org_vat' ),
													'Com_Level' => $GetPost->getPost ( 'Org_Level' )
											);
											$lastid=$this->GetModelOrganize->UpdateRecordCompany($dataUpdateCompany,$IdOperation) ;
                                            $capitalRef = substr($GetPost->getPost ( 'Org_name' ), 0, 3);
                                            $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($IdOperation);
                                            $datas = array('Com_reference'=>$orgReferenceSubstring);
                                        $this->GetModelOrganize->updateRefer($datas,$IdOperation);
											if ($GetPost->getPost ( 'Org_Level' ) == "Oui") {
												$HirachyFunction = $GetPost->getPost ( 'horg_codefuntion' );
												$Secondaire = $GetPost->getPost ( 'horg_secondaire' );
												$Description = $GetPost->getPost ( 'horg_description' );
												$StatusHorg = $GetPost->getPost ( 'Horg_level' );
												$dataUpdateHirachieCompany = array (
														'horg_Codefunction' => $HirachyFunction,
														'horg_Secondaire' => $Secondaire,
														'horg_Modifdate'=>date('d-m-Y'),
														'horg_description' => $Description,
														'horg_status' => $StatusHorg
												);
												$this->GetModelOrganize->UpdateRecordHirachieCompany ( $dataUpdateHirachieCompany, $IdOperation );
											}
										}
									}
									
								}
								$lan = $this->_getParam ( 'lang' );
								$this->_redirect ( $lan . '/organisme/index?success=update' );
								throw new Exception ( 'Can not update company!' );
									
								} catch ( ErrorException $exc ) {
									echo "Message:" . $exc->getMessage ();
								}
							} else {
								if ($this->_getParam ( 'actions' ) == "edit") {
									try {
										$idEdit=$this->_getParam ( 'id' );
										$idUser =  $this->_getParam ( 'userid' );
										$GetSelectViewCompany = $this->GetModelOrganize->SelectEditOrganisme($idEdit);
										
										$this->view->EditOrganisme = $GetSelectViewCompany;
										
										$this->view->getUserid=$idUser;
										
										/* Function selete Com_record */
										$getRefCodeToview = $this->GetModelOrganize->SelectRefCode ();
										$this->view->RefCodesFromController = $getRefCodeToview;
										
										/* Function selete Com_type */
										
										$getComTypeToview = $this->GetModelOrganize->SelectRefComType ();
									
										$this->view->RefCodeTypeFromController = $getComTypeToview;
										
										$getComStatus=$this->GetModelOrganize->SelectRefStatus();
										$this->view->RefStatusFromController = $getComStatus;
										
										$getComLevel=$this->GetModelOrganize->SelectRefLevel();
										$this->view->RefLevelFromController=$getComLevel ;
										
										$getCodeFunction=$this->GetModelOrganize->SelectRefCodeFunction();
										$this->view->RefCodeFunctionFromController=$getCodeFunction ;
										
									} catch ( ErrorException $ex ) {
										echo "Message:" . $ex->getMessage ();
									}
								}
							}
						}
						
						/*
						 * Module : Create AddNew Organisme Description : Insert
						 * record into database
						 */
				   /* Starting add new organisme*/
				}
				
				
				
					public function addAction() {
						$success=$this->_getParam ( 'error' );
						if($success=='companynameexist'){
							$message='Company Name is existed !';
							$this->view->error=$message;
						
						}elseif($success=='companyemailexist'){
						
							$message='Company Email is existed !';
							$this->view->error=$message;
						}
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
								if ($this->getRequest ()->getPost ( 'SaveOrg' )) {
									/* company name and email is existing  */
									
									
									/* start Compare company name and Email  */
									/*foreach ($this->CompanynameEmailCompare() as $reRows) {
										$UserNames = $reRows['Com_Name'];
										$userEmail = $reRows['Com_Mail'];
									
										if ($UserNames== $this->getRequest()->getPost('Org_name')) {
										
											$lan = $this->_getParam('lang');
											$this->_redirect($lan . '/organisme/add?error=companynameexist');
											exit();
										} else if ($userEmail== $this->getRequest()->getPost('txtemail')) {
											$lan = $this->_getParam('lang');
											$this->_redirect($lan . '/organisme/add?error=companyemailexist');
											exit();
										}
									}*/
								   
									/* end of Compare company name and Email  */
									/*
									 * insert data into table company
									 */
									$dataAddCompany = array ();
									$dataAddHirachieCompany = array ();
									$dataAddUser = array ();
									$dataAddHirachieUser = array ();
									$GetPost = $this->getRequest ();
									
									require_once 'Zend/ResizeClassImage.php';
									$fname = isset ( $_FILES ['fileupload'] ['name'] ) ? $_FILES ['fileupload'] ['name'] : '';
									$fsize = $_FILES ['fileupload'] ['size'];
									$ftmp = $_FILES ['fileupload'] ['tmp_name'];
									$ftype = $_FILES ['fileupload'] ['type'];
									$image = new ResizeClassImage ();
									$baseUpload = $this->basePathUpload ();
									$defaultLogo=basename($baseUpload."/profile_photo.jpg");
                                    //date_default_timezone_set('UTC');
									if ($fname == "" || $fname == false) {
									    
										$dataAddCompany = array (
												'Com_Name' => $GetPost->getPost ( 'Org_name' ),
												'Com_RaisonSocial' => $GetPost->getPost ( 'Org_social' ),
												'Com_Siret' => $GetPost->getPost ( 'Org_siret' ),
												'Com_Logo' =>	$fname,
												'Com_VAT' => $GetPost->getPost ( 'Org_vat' ),
												'Com_Address1' => $GetPost->getPost ( 'Org_address1' ),
												'Com_Address2' => $GetPost->getPost ( 'Org_address2' ),
												'Com_ZipCode' => $GetPost->getPost ( 'Org_zipcode' ),
												'Com_City' => $GetPost->getPost ( 'Org_city' ),
												'Com_Country' => $GetPost->getPost ( 'Org_country' ),
												'Com_Telephone' => $GetPost->getPost ( 'Org_telephone' ),
												'Com_Fax' => $GetPost->getPost ( 'Org_fax' ),
												'Com_Website' => $GetPost->getPost ( 'Org_website' ),
												'Com_Mail' => $GetPost->getPost ( 'txtemail' ),
												'Com_Status' => $GetPost->getPost ( 'Org_statut' ),
												'Com_Type' => $GetPost->getPost ( 'Org_type' ),
												'Com_createdate' => date('d-m-Y'),
												'Com_Level' => $GetPost->getPost ( 'Org_Level' ),
												'Com_Refcode' => $GetPost->getPost ( 'Org_RefCode' ),
												'Com_date_valie' => $GetPost->getPost ( 'Org_datevalide' ),
												'Com_Logo' =>$defaultLogo
										);
    					                    $capitalRef = substr($GetPost->getPost ( 'Org_name' ), 0, 3);
    										$GetCompanyLastId = $this->GetModelOrganize->InsertRecordToCompany ( $dataAddCompany );
                                            $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($GetCompanyLastId);
                                            $datas = array('Com_reference'=>$orgReferenceSubstring);
                                            $this->GetModelOrganize->updateRefer($datas,$GetCompanyLastId);
									} else {
										$check_type = $image->checkType ( $ftype ); // check
										                                            // file
										                                            // type
										$check_size = $image->checkSize ( $fsize ); // check
										                                            // file
										                                            // size
										if (true == $check_type) {
											if (true == $check_size) {
												$image->load ( $ftmp );
												$image->resize ( 500, 500 );
												// resize to width = 500px and
												// height =500p
												if (file_exists ( $baseUpload . '/' . $fname )) {
													$fname = $image->random ( 5 ) . '-' . $fname;
													$image->save ( $baseUpload . '/' . $fname ); // =
													                                             // move_upload_file()
													                                             // create
													                                             // thumbnail
													$image->resize ( 70, 70 );
													$image->save ( $baseUpload . '/thumb/' . $fname );
												} else {
													$image->save ( $baseUpload . '/' . $fname ); // =
													                                             // move_upload_file()
													                                             // create
													                                             // thumbnail
													$image->resize ( 70, 70 );
													$image->save ( $baseUpload . '/thumb/' . $fname );
												}
												
												$dataAddCompany = array (
														'Com_Name' => $GetPost->getPost ( 'Org_name' ),
														'Com_RaisonSocial' => $GetPost->getPost ( 'Org_social' ),
														'Com_Logo' =>$fname,
														'Com_Address1' => $GetPost->getPost ( 'Org_address1' ),
														'Com_ZipCode' => $GetPost->getPost ( 'Org_zipcode' ),
														'Com_City' => $GetPost->getPost ( 'Org_city' ),
														'Com_Country' => $GetPost->getPost ( 'Org_country' ),
														'Com_Telephone' => $GetPost->getPost ( 'Org_telephone' ),
														'Com_Fax' => $GetPost->getPost ( 'Org_fax' ),
														'Com_Mail' => $GetPost->getPost ( 'Org_mail' ),
														'Com_Website' => $GetPost->getPost ( 'Org_website' ),
														'Com_createdate' => date('d-m-Y'),
														'Com_Type' => $GetPost->getPost ( 'Org_type' ),
														'Com_Siret' => $GetPost->getPost ( 'Org_siret' ),
														'Com_Status' => $GetPost->getPost ( 'Org_statut' ),
														'Com_description' => $GetPost->getPost ( 'Org_decription' ),
														'Com_Refcode' => $GetPost->getPost ( 'Org_RefCode' ),
														'Com_VAT' => $GetPost->getPost ( 'Org_vat' ),
														'Com_Level' => $GetPost->getPost ( 'Org_Level' )
												);
												
											$capitalRef = substr($GetPost->getPost ( 'Org_name' ), 0, 3);
    										$GetCompanyLastId = $this->GetModelOrganize->InsertRecordToCompany ( $dataAddCompany );
                                            $orgReferenceSubstring =strtoupper($capitalRef).$this->createDigit($GetCompanyLastId);
                                            $datas = array('Com_reference'=>$orgReferenceSubstring);
                                            $this->GetModelOrganize->updateRefer($datas,$GetCompanyLastId);
											}
										}
									}
									
                                     $dataHirachiUser=array();
                                     $dataHirachiUser=array("Com_Id"=>$GetCompanyLastId,"User_id"=>$_SESSION['UserId']);
                                     $InsertHirachiUse=$this->GetModelOrganize->InsertRecordToHirachieUser($dataHirachiUser);
                                     /*
                                       if Yes insert data
                                     */
									if ($GetPost->getPost ( 'Org_Level' ) == "Oui") {
										$HirachyFunction = $GetPost->getPost ( 'horg_codefuntion' );
										$Secondaire = $GetPost->getPost ( 'horg_secondaire' );
										//$Createdate = $GetPost->getPost ( 'horg_createdate' );
										$Description = $GetPost->getPost ( 'horg_description' );
										$StatusHorg = $GetPost->getPost ( 'Horg_level' );
										$dataAddHirachieCompany = array (
												'horg_Codefunction' => $HirachyFunction,
												'horg_Secondaire' => $Secondaire,
												'horg_Createdate' => date('d-m-Y'),
												'horg_description' => $Description,
												'horg_status' => $StatusHorg,
												'Com_Id' => $GetCompanyLastId
										);
									
										$this->GetModelOrganize->InsertRecodeToHierachieCompany ( $dataAddHirachieCompany );
									}elseif($GetPost->getPost ( 'Org_Level' ) == "Non"){
										$dataAddHirachieCompany=array('Com_Id' => $GetCompanyLastId);
										$this->GetModelOrganize->InsertRecodeToHierachieCompany ( $dataAddHirachieCompany );
									}
									
									$lan = $this->_getParam ( 'lang' );
									$this->_redirect ( $lan . '/organisme/index?success=add' );
								} else {
									/*
									 * Function selete Com_record
									 */
									$getRefCodeToview = $this->GetModelOrganize->SelectRefCode ();
									$this->view->RefCodesFromController = $getRefCodeToview;
									/*
									 * Function selete Com_type
									 */
									$getComTypeToview = $this->GetModelOrganize->SelectRefComType ();
									$this->view->RefCodeTypeFromController = $getComTypeToview;
									
									$getComStatus=$this->GetModelOrganize->SelectRefStatus();
										
									$this->view->RefStatusFromController = $getComStatus;
									
									
									$getComLevel=$this->GetModelOrganize->SelectRefLevel();
									$this->view->RefLevelFromController=$getComLevel ;
									
									$getCodeFunction=$this->GetModelOrganize->SelectRefCodeFunction();
									$this->view->RefCodeFunctionFromController=$getCodeFunction ;
								}
							} catch ( ErrorException $exc ) {
								echo "Message:" . $exc->getMessage ();
							}
						}
					}
					
					/* Eding add new organisme */
					/*stating of overview*/
					public function overviewAction(){
						$this->redirectToIndex();
					   if($this->_getParam ( 'actions' )=="overview"){
					        $idOverview= $this->_getParam ( 'id' );
					        $idUser =  $this->_getParam ( 'userid' );
							$GetSelectViewCompanys = $this->GetModelOrganize->SelectOverViewEdit( $idOverview,$idUser );
					       $this->view->SelectOnFormEdit = $GetSelectViewCompanys;
					       
					   }
						$id = $this->_getParam ( 'id' );
                        $this->view->getIdView = $id;
                        
                        
					}
					/*Ending of overview*/
				
				/*
				 * Module      : Create checksession
				 * Description : if check session user = true, if check session user=false
				 */
                 /*format number */
                public  function createDigit($digit){
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
                }
				function redirectToIndex(){
					if (! $this->CheckTransactionUser ()) {
						$lan = $this->_getParam ( 'lang' );
						$this->_redirect ( $lan . '/index/index' );
						exit ();
						
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
				
				/*
				 * Module       : Create base path for uploading logo image
				 * Description  : Check for base path of uploading images
				*/
				/* Starting function basepath */
				public function basePathUpload() {
						$this->redirectToIndex();
						$pathupload = realpath ( APPLICATION_PATH . '/../public/data/uploads' );
						return $pathupload;
					}
					/* Ending function basepath */
                    
                    
					public function __destruct(){
						try{
							$this->GetModelOrganize;
							$this->getLibBaseUrl;
							$this->_Categories;
							session_write_close();
						}catch(ErrorException $ex){
							echo "Message:" . $ex->getMessage ();
							 
						}
					}
					
				}



