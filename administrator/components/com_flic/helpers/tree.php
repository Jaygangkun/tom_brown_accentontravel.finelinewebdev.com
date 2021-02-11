<?

function rebuild_tree($parentId = 0, $left = 0, $level = -1) {
	$db =& JFactory::getDBO();   
    // the right value of this node is the left value + 1   
    $right = $left+1;   
	
    // get all children of this node   
    $query = 'SELECT flic_category_id FROM #__flic_category '.
    						'WHERE parent_flic_category_id="'.$parentId.'";';
    $db->setQuery( $query );
	$rows = $db->loadObjectList();	
	
	foreach ($rows as $row) {
        // recursive execution of this function for each   
        // child of this node   
        // $right is the current right value, which is   
        // incremented by the rebuild_tree function   
        $right = rebuild_tree($row->flic_category_id, $right, $level+1);   
    }   
    // we've got the left value, and now that we've processed   
    // the children of this node we also know the right value   
    print "SET " . $parentId . " - > " . $left . " -- " . $right . "<br><br>";
	
	$query = 'UPDATE #__flic_category SET treeLevel='.$level.', treeLeft='.$left.', treeRight='.$right.' WHERE flic_category_id="'.$parentId.'";';
    $db->setQuery( $query );
	$db->execute();
    // return the right value of this node + 1   	
    return $right+1;   
}   

?>