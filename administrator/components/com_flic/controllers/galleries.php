<?php
/**
 * @version		$Id: banner.php 10878 2008-08-30 17:29:13Z willebil $
 * @package		Joomla
 * @subpackage	Banners
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * @package		Joomla
 * @subpackage	Banners
 */
class FLICControllerGalleries extends JControllerLegacy
{
	/**
	 * Constructor
	 */
	function __construct( $config = array() )
	{
		parent::__construct( $config );
		// Register Extra tasks
		$this->registerTask( 'add',			'edit' );
		$this->registerTask( 'apply',		'save' );
		$this->registerTask( 'unpublish',	'publish' );
	}

	function display()
	{
		$mainframe = JFactory::getApplication();
		$db =& JFactory::getDBO();

		$context			= 'com_flic.gallery.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'g.name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',			'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',			'word' );

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 1, 'int' );

		$where = array();

		if ( $filter_state )
		{
			if ( $filter_state == 'P' ) {
				$where[] = 'g.showGallery = 1';
			}
			else if ($filter_state == 'U' ) {
				$where[] = 'g.showGallery = 0';
			}
		}

		$where		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;
		
		// get the total number of records
		$query = 'SELECT COUNT(*)'
		. ' FROM #__flic AS g'
		. $where
		;
		$db->setQuery( $query );
		$total = $db->loadResult();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT g.*, u.name AS editor,' // , pc.name AS galleryCategoryName
		. ' ( SELECT count(*) FROM #__flic_image img WHERE img.flic_id = g.flic_id) AS countOfImages'
		. ' FROM #__flic AS g'
		. ' LEFT JOIN #__users AS u ON u.id = g.checked_out'
		. $where
		. $orderby
		;
		
		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();
		
		$query = "
			SELECT g.flic_id, c.`name`
			FROM `#__flic_category_gallery` g
			LEFT JOIN `#__flic_category` c
				ON c.flic_category_id = g.flic_category_id
			ORDER BY g. flic_id;
		";
		$db->setQuery( $query );
		$categoryRows = $db->loadObjectList();
		
		$currentCategories = array();
		foreach ($categoryRows as $cat) {
			if(empty($currentCategories[$cat->flic_id])) {
				$currentCategories[$cat->flic_id] = $cat->name;
			} else {
				$currentCategories[$cat->flic_id] .= ", " . $cat->name;
			}
			
		}
		
		$lists['currentCategories'] = $currentCategories;
		
		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		require_once(JPATH_COMPONENT.'/views/gallery.php');
		FLICViewGallery::galleries( $rows, $pageNav, $lists );
	}

	function edit()
	{
		$db	=& JFactory::getDBO();
		$user	=& JFactory::getUser();
	
		$task = JRequest::getCmd( 'task' );
		if ($task == 'edit') {
			$flic_id	= JRequest::getVar('flic_id', array(0), 'method', 'array');
			$flic_id	= array((int) $flic_id[0]);
		} else {
			$flic_id	= array( 0 );
		}

		$option = JRequest::getCmd('option');

		$lists = array();

		$row =& JTable::getInstance('galleries', 'FLICTable');
		$row->load( $flic_id[0] );
		
		if(empty($row->ordering)) {
			$query = "SELECT MAX(ordering) FROM #__flic";
			$db->setQuery($query);
			$row->ordering = $db->loadResult()+1;
		}
		
		if ($flic_id[0]) {
			$row->checkout( $user->get('id') );
		} else {
			$row->showGallery = 1;
		}
		
		// Load Images
		$query = 'SELECT pi.* '
		. ' FROM #__flic_image AS pi'
		. ' WHERE flic_id = ' . $flic_id[0]
		. ' ORDER BY pi.ordering, pi.flic_image_id'
		;
		$db->setQuery( $query );
		$lists['galleryImage']  = $db->loadObjectList();
		
		// Load current categories
		$currentCategories = array();
		if($row->flic_id){
			$sql = 'SELECT flic_category_id'
			. ' FROM #__flic_category_gallery'
			. ' WHERE flic_id = ' . $row->flic_id
			;
			$db->setQuery($sql);
			$currentCategories = $db->loadColumn();
		}
		
		// Load category options
		$sql = 'SELECT flic_category_id, name, treeLevel '
		. ' FROM #__flic_category ORDER BY treeLeft'
		;
		$db->setQuery($sql);
		$galleryCategoryList = $db->loadObjectList();
		
		$categoryHtml = '<select id="flic_category_id[]" name="flic_category_id[]" multiple class="selectize" placeholder="Category">';
		foreach($galleryCategoryList as $category) {
			$categoryHtml .= '<option value="' . $category->flic_category_id . '" ';
			if(in_array($category->flic_category_id, $currentCategories)) {
				$categoryHtml .= ' selected ';
			}
			$categoryHtml .= " >" . str_repeat(" |-- ", $category->treeLevel) . $category->name . '</option>';
		}
		$categoryHtml .= '</select>';
		$lists['GalleryCategory'] = $categoryHtml;
	
		// published
		$lists['showGallery'] = JHTML::_('select.booleanlist',  'showGallery', '', $row->showGallery );

		// published
		$lists['isFeatured'] = JHTML::_('select.booleanlist',  'isFeatured', '', $row->isFeatured );

		require_once(JPATH_COMPONENT.'/views/gallery.php');
		FLICViewGallery::gallery( $row, $lists );
	}

	/**
	 * Save method
	 */
	function save()
	{
		global $mainframe;
		
		require_once(JPATH_COMPONENT.'/helpers/resize.php');

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic' );
		// Initialize variables
		$db =& JFactory::getDBO();
		jimport('joomla.filesystem.file');

		$post	= JRequest::get( 'post' );
		
		// Deafult Banner
		if($post['isFeatured']) {
			// Remove other defaults
			$sql = "UPDATE #__flic SET isFeatured = 0";
			$db->setQuery( $sql );
			$db->execute();
			// Clear menu item selects
			// $post['menuItems'] = "";
		}
		
		if($post['menuItems'] && is_array($post['menuItems'])) {
			$post['menuItems'] = ",".implode(",", $post['menuItems']).",";
		}
		
		if(empty($post['alias'])) {
			$post['alias'] = $post['name'];
		}
		$post['alias'] = JFilterOutput::stringURLSafe($post['alias']);
		
		$post['description'] = JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWHTML ); 
		$post['shortDescription'] = JRequest::getVar( 'shortDescription', '', 'post', 'string', JREQUEST_ALLOWHTML ); 
		
		// Basic url check
		if(strpos($post['url'], "http://") === false && strpos($post['url'], "https://") === false ) {
			$post['url'] = "http://" . trim($post['url']);
		}

		// Quick alias check
		if(empty($post['alias'])) {
			$post['alias'] = $post['name'];
		}
		$post['alias'] = JFilterOutput::stringURLSafe($post['alias']);
		
		$row =& JTable::getInstance('galleries', 'FLICTable');
		
		if (!$row->bind( $post )) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if (!$row->check()) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if($row->flic_id ) {
			$isUpdate = 1;
		} else {
			$isUpdate = 0;
		}
		
		if (!$row->store()) {
			return JError::raiseWarning( $row->getError() );
		}

		$row->checkin();
		
		$galleryCategories = $post['flic_category_id'];
		
		// Build categories SQL
		$insertCategories ="";
		foreach ($galleryCategories as $category) {
			$insertCategories .= "(" . $row->flic_id . ", " . $category . "), ";
		}
		$insertCategories = substr($insertCategories, 0, strlen($insertCategories) - 2);
		
		// Delete old categories
		$query = "DELETE FROM #__flic_category_gallery WHERE flic_id = " . $row->flic_id;
		$db->setQuery( $query );
		$db->execute();
		
		// Add new categories
		if($insertCategories) {
			$query = "INSERT INTO #__flic_category_gallery ( flic_id, flic_category_id ) VALUES $insertCategories";
			$db->setQuery( $query );
			$db->execute();
		}
		
		// image stuff
		$resizeImageWidth = 0;
		$resizeImageHeight = 0;
		
		if($row->resizeImageWidth && $row->resizeImageHeight) {
			$resizeImageWidth = $row->resizeImageWidth;
			$resizeImageHeight = $row->resizeImageHeight;
		}
		
		jimport('joomla.filesystem.folder');
		
		$imageFileTypes = array('image/png', 'image/jpeg', 'video/mp4');
		$thisFolderPath = JPATH_ROOT .  '/images/flic/galleries/' . $row->flic_id;
		$thisOriginalFolderPath = $thisFolderPath .  '/original';
		if( !JFolder::exists($thisFolderPath) ) {
			JFolder::create($thisFolderPath);
		}
		if( !JFolder::exists($thisOriginalFolderPath) ) {
			JFolder::create($thisOriginalFolderPath);
		}

		$imagetable	=& JTable::getInstance('images', 'FLICTable');
		$image_resizer = new resizeImage();

		// Image Reordering
		$orderingIds = $post['img-ordering'];
		$split = explode(",", $orderingIds);
		$currentOrdering = 1;
		foreach($split as $id) {
			$imagetable->load( $id );
			$imagetable->ordering = $currentOrdering;
			$imagetable->store();
			$currentOrdering++;
		}
		
		//// End Reordering ////

		if($isUpdate) { 
			$query = 'SELECT pi.* '
			. ' FROM #__flic_image AS pi'
			. ' WHERE flic_id = ' . $row->flic_id
			;
			$db->setQuery( $query );
			$imagelist = $db->loadObjectList();

			for ($iImage=0, $nImage=count( $imagelist ); $iImage < $nImage; $iImage++) {
				$imagelistrow = &$imagelist[$iImage];
				$id = $imagelistrow->flic_image_id;

				if( JRequest::getVar( 'delete_gallery_image_'.$id) ) {
					$query = 'DELETE FROM #__flic_image'
					. ' WHERE flic_image_id = ' .$id
					;
					$db->setQuery( $query );
					if (!$db->query()) {
						JError::raiseWarning( 500, $db->getError() );
					}
					JFile::delete($thisFolderPath . '/' . $imagelistrow->filename);
					JFile::delete($thisOriginalFolderPath . '/' . $imagelistrow->filename);
				} else if ($imagetable->load( (int)$id ))
				{
					$this_imageUpload = JRequest::getVar('filename_'.$id, null, 'files', 'array');
					if (isset($this_imageUpload) && in_array($this_imageUpload['type'], $imageFileTypes)) {
						JFile::delete($thisFolderPath . '/' . $imagelistrow->filename);
						JFile::delete($thisOriginalFolderPath . '/' . $imagelistrow->filename);
						$this_filename = $id.'_'.JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])). '.' . JFile::getExt($this_imageUpload['name']);
						JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFolderPath . '/' . $this_filename);
						
						if($row->resizeImageWidth && $row->resizeImageHeight) {
							$image_resizer->resize($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
						} else {
							copy($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename);
						}
						$imagetable->filename = $this_filename;
					
					}
					
					$imagetable->captionTitle = JRequest::getVar( 'captionTitle_'.$id);
					
					$post['captionMessage_'.$id] = JRequest::getVar( 'captionMessage_'.$id, '', 'post', 'string', JREQUEST_ALLOWHTML ); 
					$imagetable->captionMessage = $post['captionMessage_'.$id];
					// $imagetable->ordering = JRequest::getVar( 'ordering_'.$id);
					$imagetable->showGalleryImage = JRequest::getVar( 'showGalleryImage_'.$id);
					$imagetable->url = JRequest::getVar( 'url_'.$id);
					$imagetable->newWindow = JRequest::getVar( 'newWindow_'.$id);
					$imagetable->messagePosition = JRequest::getVar( 'messagePosition_'.$id);

					if (!$imagetable->store()) {
						return JError::raiseWarning( $imagetable->getError() );
					}
				}
				else {
					return JError::raiseWarning( 500, $imagetable->getError() );
				}
			}
		}

		$this_imageZipUpload = JRequest::getVar('uploadZipFile', null, 'files', 'array');
		if (isset($this_imageZipUpload) && strpos($this_imageZipUpload['type'], "zip") ) {
			$thisTempFolderPath = JPATH_ROOT .  '/images/flic/galleries/temp';
			if( !JFolder::exists($thisTempFolderPath) ) {
				JFolder::create($thisTempFolderPath);
			}

			JFile::upload($this_imageZipUpload['tmp_name'], $thisTempFolderPath . '/' . $this_imageZipUpload['name'], false, true);
			JArchive::extract($thisTempFolderPath . '/' . $this_imageZipUpload['name'], $thisTempFolderPath);
			$listOfImages = JFolder::files($thisTempFolderPath, '.jpg', true, true);
			print_r($listOfImages);
			foreach($listOfImages AS $thisImage ) {
				if(JFile::getExt($thisImage) == "zip") {
					continue;
				}
				$imagetable->flic_image_id = 0;
	 			$imagetable->flic_id = $row->flic_id;
				$imagetable->captionTitle = "";
				$imagetable->captionMessage = "";
				$imagetable->newWindow = 0;
				$imagetable->url = "";
 				$imagetable->store();
				$this_filename = $imagetable->flic_image_id.'_'.JFilterOutput::stringURLSafe(JFile::stripExt(JFile::getName($thisImage))). "." . JFile::getExt($thisImage);
				JFile::move($thisImage, $thisOriginalFolderPath . '/' . $this_filename);
				
				if($row->resizeImageWidth && $row->resizeImageHeight) {
					$image_resizer->resize($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
				} else {
					copy($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename);
				}
				$imagetable->filename = $this_filename;
				$imagetable->store();
			}
			JFolder::delete($thisTempFolderPath);
		}

		for($iNewImage=1; $iNewImage <= 5; $iNewImage++) {
			$this_imageUpload = JRequest::getVar('new_filename_'.$iNewImage, null, 'files', 'array');
			if (isset($this_imageUpload) && in_array($this_imageUpload['type'], $imageFileTypes)) {
				$imagetable->flic_image_id = 0;
				$imagetable->flic_id = $row->flic_id;
				$imagetable->ordering = $currentOrdering;
				$imagetable->captionTitle = "";
				$imagetable->captionMessage = "";
				$imagetable->newWindow = 0;
				$imagetable->showGalleryImage = 1;
				$imagetable->url = "";
				
				$imagetable->store();
				$this_filename = $imagetable->flic_image_id.'_'.JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])). '.' . JFile::getExt($this_imageUpload['name']);
				JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFolderPath . '/' . $this_filename);
						
				if($row->resizeImageWidth && $row->resizeImageHeight) {
					$image_resizer->resize($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
				} else {
					copy($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename);
				}
				
				$imagetable->filename = $this_filename;
				$imagetable->store();

				$currentOrdering++;
			}
		}
			
		$task = JRequest::getCmd( 'task' );
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_flic&c=galleries&task=edit&flic_id[]='. $row->flic_id ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_flic&c=galleries';
				break;
		}
		$this->setRedirect( $link, JText::_( 'Item Saved' ) );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic&c=galleries' );

		// Initialize variables
		$db	=& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row    =& JTable::getInstance('galleries', 'FLICTable');
		$row->bind( $post );
		$row->checkin();
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic&c=galleries' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$flic_id	= JRequest::getVar( 'flic_id', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $flic_id );

		if (empty( $flic_id )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		JArrayHelper::toInteger( $flic_id );
		$flic_ids = implode( ',', $flic_id); 

		$query = 'UPDATE #__flic'
		. ' SET showGallery = ' . (int) $publish
		. ' WHERE flic_id IN ( '. $flic_ids .'  )'
		. ' AND isFeatured = 0'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $db->getError() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished <small><em>(Default Banner can not be unpublished)</em></small>', $n ) );
	}


	/**
	 * Save the new order given by user
	 */
	function saveOrder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic' );

		// Initialize variables
		$db			=& JFactory::getDBO();
		$flic_id		= JRequest::getVar( 'flic_id', array(), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		$row 		=& JTable::getInstance('galleries', 'FLICTable');
		$total		= count( $flic_id );
		$conditions	= array();

		if (empty( $flic_id )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		// update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load( (int) $flic_id[$i] );
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					return JError::raiseError( 500, $db->getErrorMsg() );
				}
			}
		}


		$this->setMessage( JText::_('New ordering saved') );
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$flic_id = JRequest::getVar( 'flic_id', array(), 'post', 'array' );
		$n		= count( $flic_id );
		JArrayHelper::toInteger( $flic_id );
		
		if ($n)
		{
			$query = 'DELETE FROM #__flic'
			. ' WHERE flic_id = ' . implode( ' OR flic_id = ', $flic_id )
			. ' AND isFeatured = 0'
			;
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}

			// $query = 'DELETE FROM #__flic'
			// . ' WHERE flic_id = ' . implode( ' OR flic_id = ', $flic_id )
			// ;
			// $db->setQuery( $query );
// 
			// if (!$db->query()) {
				// JError::raiseWarning( 500, $db->getError() );
			// }
		}

		$this->setMessage( 'Items removed. <small><em>(Default Banner can not be deleted)</em></small>' );
	}
	
	function import() {
		$this->setRedirect( 'index.php?option=com_flic' );
		
		$db	=& JFactory::getDBO(); 
		
		// CATEGORIES
		$query = 'SELECT * FROM #__fl_gallery_category';
		$db->setQuery( $query );
		$oldCategories = $db->loadObjectList();
		
		$oldToNewIds = array();
		
		foreach($oldCategories as $cat) {
			$row =& JTable::getInstance('categories', 'FLICTable');
			$row->name = $cat->name;
			$row->alias = $cat->alias;
			$row->description = $cat->description;
			$row->showCategory = $cat->showGalleryCategory;
			$row->metaTitle = $cat->htmlTitle;
			$row->metaKeywords = $cat->metaKeywords;
			$row->metaDescription = $cat->metaDescription;
			
			if(empty($row->alias)) {
				$row->alias = JFilterOutput::stringURLSafe($row->name);
			}
			
			if (!$row->store()) {
				return JError::raiseWarning( $row->getError() );
			}
			$row->checkin();
			$oldToNewIds[$cat->fl_gallery_category_id] = $row->flic_category_id;
		}
		
		// GALLERIES
		$query = 'SELECT * FROM #__fl_gallery';
		$db->setQuery( $query );
		$oldGalleries = $db->loadObjectList();
		
		foreach($oldGalleries as $gal) {
			$row =& JTable::getInstance('galleries', 'FLICTable');
			$row->name = $gal->name;
			$row->alias = $gal->alias;
			$row->description = $gal->description;
			$row->resizeImageWidth = $gal->resizeWidth;
			$row->resizeImageHeight = $gal->resizeHeight;
			$row->showGallery = $gal->showGallery;
			$row->metaTitle = $gal->htmlTitle;
			$row->metaKeywords = $gal->metaKeywords;
			$row->metaDescription = $gal->metaDescription;
			
			if (!$row->store()) {
				return JError::raiseWarning( $row->getError() );
			}
			$row->checkin();
			
			$oldToNewFlicIds[$gal->fl_gallery_id] = $row->flic_id;
			
			$catRow =& JTable::getInstance('categorygallery', 'FLICTable');
			$catRow->flic_id = $row->flic_id;
			$catRow->flic_category_id = $oldToNewIds[$gal->fl_gallery_category_id];
			if (!$catRow->store()) {
				return JError::raiseWarning( $catRow->getError() );
			}
			$catRow->checkin();
		} 
		
		// IMAGES
		function copy_directory($src,$dst) {
		    $dir = opendir($src);
		    @mkdir($dst);
		    while(false !== ( $file = readdir($dir)) ) {
		        if (( $file != '.' ) && ( $file != '..' )) {
		            if ( is_dir($src . '/' . $file) ) {
		                recurse_copy($src . '/' . $file,$dst . '/' . $file);
		            }
		            else {
		                copy($src . '/' . $file,$dst . '/' . $file);
		            }
		        }
		    }
		    closedir($dir);
		}
		
		$query = 'SELECT * FROM #__fl_gallery_image';
		$db->setQuery( $query );
		$oldImages = $db->loadObjectList();
		
		jimport('joomla.filesystem.folder');
		if( !JFolder::exists(JPATH_ROOT."/images/flic/galleries/") ) {
			JFolder::create(JPATH_ROOT."/images/flic/galleries/");
		}
		
		foreach($oldImages as $img) {
			$row =& JTable::getInstance('images', 'FLICTable');
			$row->flic_id = $oldToNewFlicIds[$img->fl_gallery_id];
			$row->filename = $img->filename;
			$row->captionTitle = $img->captionTitle;
			$row->captionMessage = $img->captionMessage;
			$row->url = $img->url;
			$row->newWindow = $img->newWindow;
			$row->ordering = $img->ordering;
			$row->showGalleryImage = $img->showGalleryImage;
			
			$newPosition = 1;
			if($img->messagePosition == "top-right") 
				$newPosition = 3;
			if($img->messagePosition == "bottom-left") 
				$newPosition = 4;
			if($img->messagePosition == "bottom-right") 
				$newPosition = 6;
			$row->messagePosition = $newPosition;
			
			if (!$row->store()) {
				return JError::raiseWarning( $row->getError() );
			}
			$row->checkin();
			
			$oldFilename = JPATH_ROOT."/images/fl_gallery/" . $img->fl_gallery_id;
			$newFilename = JPATH_ROOT."/images/flic/galleries/" . $row->flic_id; 
			$newOriginalFilename = JPATH_ROOT."/images/flic/galleries/" . $row->flic_id."/original"; 
			
			if(file_exists($oldFilename)) {
				copy_directory($oldFilename, $newFilename); 
				copy_directory($oldFilename, $newOriginalFilename); 
			}
			
		} 
		
		$this->setMessage( "Import Successful!" );
	}

}
