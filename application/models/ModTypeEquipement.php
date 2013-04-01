<?php 
/**
 * @Developer  Doeun
 * @Module     Type Equipement
 * @Descript Add,Edit,Update and select
 * @company   Mapring
 * @copyright 2013
 */
class Application_Model_Modtypeequipement{
	public $Tabletypeequipement;
	private $_tblrefcodes;
	private $_tblsite;
	private $_tblcompany;
	
	public function __construct(){
		$this->Tabletypeequipement = new Application_Model_DbTable_typeequipement();
		$this->_tblrefcodes = new Application_Model_DbTable_refcodes ();
		$this->_tblsite=new Application_Model_DbTable_site();
		$this->_tblcompany=new Application_Model_DbTable_company();
	}
	public function SelectRefCode() {
		$sql = $this->_tblrefcodes->select ()
		->from ( $this->_tblrefcodes, array (
				"ref_code_lib",
				"ref_Code",
				"ref_Id"
		) )->where ( "ref_Num=?", "TYP001" );
		$rows =$this->_tblrefcodes->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectsiteName() {
		$data=array ("site_Id","site_Name");
		$sql = $this->_tblsite->select ()
		->from ( $this->_tblsite,$data  );
		$rows =$this->_tblsite->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectcompanyName() {
		$data=array ("Com_Id","Com_Name");
		$sql = $this->_tblcompany->select ()
		->from ( $this->_tblcompany,$data  );
		$rows =$this->_tblcompany->fetchAll ( $sql )->toArray ();
		return (! empty ( $rows )) ? $rows : null;
	}
	public function SelectOverViewtypeequipement( $idEdit ){
		try{
			$db = Zend_Db_Table::getDefaultAdapter ();
			$select = $db->select ()->from ('typeequipement',array('*') )
			->where('Type_id = ?',$idEdit);
			$rows = $db->fetchAll ($select );
			return (!empty($rows))?$rows:null;
			throw new Exception('Can not select!');
		}catch (ErrorException $e){
			echo "Message:".$e->getMessage();
		}
	}
	
	/*Starting select all record from table type Equipement*/
	public function GetAllRecordtypeequipement(){
		try{
		$ql = $this->Tabletypeequipement->select()->from($this->Tabletypeequipement,array('*'));
		$rows = $this->Tabletypeequipement->fetchAll ( $ql )->toArray ();
		return (!empty($rows))?$rows:null;
		}catch (ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	/*Ending select all record from table type Equipement*/
	/*Starting add new Equipement type*/
	public function addEquipementType($datatypeequipement){
		try{
			$SucessAdd = $this->Tabletypeequipement->insert($datatypeequipement);
			if($SucessAdd){
				return true;
			}else{
			    return false;	
			}
			
		}catch(ErrorException $excep){
			echo "Message:".$excep->getMessage();
		}
	}
	/*Ending add new Equipement type*/
	/*Startind edit of equipement type*/
	public function equipementEdit($idEdit){
		try{
			$ql = $this->Tabletypeequipement->select()->from($this->Tabletypeequipement,array('*'))
			->where('Type_id = ?',$idEdit)->limit(1);
			$rows = $this->Tabletypeequipement->fetchAll ( $ql )->toArray ();
			return (!empty($rows))?$rows:null;
		}catch(ErrorException $ex){
		 echo "Message".$ex->getMessage();	
		}
	}
	/*Ending edit of equipement type*/
	/*Starting update equipement type*/
	public function saveUpdate($datatypeequipementUpdate,$idHidden){
		try{
		$where = $this->Tabletypeequipement->getAdapter()->quoteInto('Type_id = ?', $idHidden);
		$getRows = $this->Tabletypeequipement->update($datatypeequipementUpdate, $where);
		if ($getRows){
			return true;
		}else{
			return false;
		}
		}catch (ErrorException $ex){
			echo "Message".$ex->getMessage();
		}
	}
	/*Ending update equipement type*/
	/*Starting deleting typeequipement*/
	public function deletetypeequipement($id){
		try{
		$idDelete=$this->Tabletypeequipement->getAdapter()->quoteInto(' Type_id= ? ',$id) ;
		$successDel = $this->Tabletypeequipement->delete($idDelete);
		if($successDel){
			return true;
		}else{
		    return false;	
		}
		}catch(ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
		
	}
	/*Endingg deleting typeequipement*/
	/*starting multiple delete*/
	public function multiDelete($id){
		try{
		for($i=0;$i<count($id);$i++){
			$idLoop = $id[$i];
			$idMultiDelete=$this->Tabletypeequipement->getAdapter()->quoteInto(' Type_id= ? ',$idLoop) ;
			$successMultiDel = $this->Tabletypeequipement->delete($idMultiDelete);
		}
		
		}catch(ErrorException $ex){
			echo "Message:".$ex->getMessage();
		}
	}
	/*Ending multiple delete*/
}