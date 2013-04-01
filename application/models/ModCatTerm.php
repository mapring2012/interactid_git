<?php
class Application_Model_ModCatTerm {
    public $GetTableTerm            = null;
    public $TermRelationships       = null;
    public $cattermtaxonomy         = null;
    
	public function __construct() {
        $this->GetTableTerm         = new Application_Model_DbTable_catterm();
        $this->cattermtaxonomy      = new Application_Model_DbTable_cattermtaxonomy();
        
    }
    
    public function insertcat($insertCat){        
       $this->GetTableTerm->insert($insertCat);
   	    $insertID = $this->GetTableTerm->getAdapter()->lastInsertId();
		return $insertID;        
    }
    
        //insert into cattermtaxonomy table
    public function insertTax ($insertTax)
    {
        $this->cattermtaxonomy->insert($insertTax);
    }
    public function showCateAll()
    {
   	    $db = Zend_Db_Table::getDefaultAdapter ();
        $select = $db->select ()->from ( array ('tax' => 'cattermtaxonomy' ))
        ->joinInner ( array ('term' => 'catterm' ), 'tax.term_id = term.term_id' )
        ->where ( 'parent=?', '0' );
		$rows = $db->fetchAll ( $select );
        return $rows;
    }

    public function showCateInList()
    {
        foreach ($this->showCateAll() as $row)
        {
            $id = $row['term_id'];            
            $db = Zend_Db_Table::getDefaultAdapter ();
            $select = $db->select ()->from ( array ('tax' => 'cattermtaxonomy' ))
            ->joinInner ( array ('term' => 'catterm' ), 'tax.term_id = term.term_id' )->where ( 'parent=?', $id );
    		$row = $db->fetchAll ( $select );
            foreach ($row as $value)
            {
                
            }
            $menu_array[$row['term_id']] = array('term_id' => $row['term_id'],'name' => $row['name'],'parent' => $row['parent'],'image' => $row['image']);
            return $row;
        }   	    
       
    }
    
    public function showInParent($parentId)
    {
   	    $db = Zend_Db_Table::getDefaultAdapter ();
        $select = $db->select ()->from ( array ('tax' => 'cattermtaxonomy' ))
        ->joinInner ( array ('term' => 'catterm' ), 'tax.term_id = term.term_id' )
        ->where ( 'parent=?', $parentId )->where( 'tax.taxonomy=?', 'category' );
		$rows = $db->fetchAll ( $select );
		return $rows;        
    }

    
    public function showCateParent($parent_id)
    {
   	    $db = Zend_Db_Table::getDefaultAdapter ();
        $select = $db->select ()->from ( array ('term' => 'catterm'))
         ->joinInner (array ('tax' => 'cattermtaxonomy' ), 'tax.term_id = term.term_id' )
        ->where ( 'slug=?', $parent_id ) ->where ( 'parent=?',0 );
		$rows = $db->fetchAll ( $select );
        foreach ($rows as $row)
        {
            $row=$this->showInParent($row['term_id']);
        }
        return $row; 
    }
    

}