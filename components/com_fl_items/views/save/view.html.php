<?php
/**
 * HTML View for Rentals Barefoot Component
 * 
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Rentals Barefoot Component
 *
 */
class FlItemsViewSave extends JViewLegacy
{
	function display($tpl = null)
	{
		$model =& $this->getModel('fl_items');
		$document = &JFactory::getDocument();

		$user = JFactory::getUser();
		if($user->guest == 1) {
			$app = JFactory::getApplication();
			$message = "You must log in to access this page.";
			$app->redirect(JRoute::_('index.php?option=com_users', false), $message, 'error');
		}

		$this->getEditItems = $model->getEditItems($user->id);

		$urlId = JRequest::getInt('id', 0);

		if(count($this->getEditItems)) {
			// Check if save is submitted
			$post = JRequest::get('post');

			if($post['item_id']) {
				$this->getOneItem = $model->getOneForEdit($post['item_id'], $user->id);

				if(!$this->getOneItem) {
					return "An error occurred.";
					exit;
				}

				
				jimport('joomla.filesystem.folder');
				jimport('joomla.filesystem.file');

				$imageOrdering = 1;

				$db = &JFactory::getDBO();
				$row = &JTable::getInstance('items', 'FLItemsTable');
				$row->load($post['item_id']);
				// Update Name
				if($post['name']) {
					$row->name = $post['name'];
				}
				if(isset($post['showItem'])) {
					$row->showItem = $post['showItem'];
				}
				$row->store();

				// Do image reordering first
				if($post['img-ordering']) {
					$split = explode(",", $post['img-ordering']);
					$imgRow = &JTable::getInstance('images', 'FLItemsTable');
					foreach($split as $imgId) {
						$imgRow->load($imgId);
						$imgRow->ordering = $imageOrdering;
						$imgRow->store();
						$imageOrdering++;
					} 
				} 
				foreach ($post as $key => $val) {
					$val = JRequest::getVar($key, '', 'post', 'string', JREQUEST_ALLOWHTML);

					if (strpos($key, "val-") !== false) {
						$prop = substr($key, 4);

						$query = 'SELECT item_property_id, type FROM #__fl_items_properties WHERE name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id;
						$db->setQuery($query);
						$propDetails = $db->loadObject();
						$propertyType = $propDetails->type;

						if($propertyType == "externalLink" && $val) {
							if(strpos($val, "http://") === false && strpos($val, "https://") === false) {
								$val = "http://$val";
								print $val;
							}
						}

						$query = 'SELECT items_item_property_id '
						. ' FROM #__fl_items_item_property AS pi'
						. ' WHERE item_id = ' . $row->item_id
						. ' AND item_property_id = ' . $propDetails->item_property_id
						;
						$db->setQuery($query);
						$isPropUpdate = $db->loadResult();

						$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id;
						$db->setQuery($query);
						$propId = $db->loadResult();

						$propRow = &JTable::getInstance('itemproperty', 'FLItemsTable');

						if ($isPropUpdate) {
							$propRow->load((int) $isPropUpdate);
							$propRow->value = $val;
							$propRow->store();
						} else {
							$propRow->item_id = $row->item_id;
							$propRow->item_property_id = $propId;
							$propRow->value = $val;
							$propRow->store();
						}
					} else if (strpos($key, "option-") !== false) {
						// $optionId = substr($key, 7);
						// $sql = "DELETE FROM #__fl_items_option_map WHERE item_property_id = $optionId AND item_id = " . $row->item_id;
						// $db->setQuery($sql);
						// $db->execute();

						// foreach ($val as $opt) {
						// 	$sql = "INSERT INTO #__fl_items_option_map VALUES(" . $row->item_id . ", $optionId, $opt)";
						// 	$db->setQuery($sql);
						// 	$db->execute();
						// }
					} else if (strpos($key, "del-img-") !== false) {
						$imgId = substr($key, 8);
						$sql = "DELETE FROM #__fl_items_image WHERE item_image_id = $imgId AND item_id = " . $row->item_id;
						$db->setQuery($sql);
						$db->execute();
					}
				}

				// Handle New Images
				$allowedFileTypes = array('image/png', 'image/jpeg', 'image/gif', 'image/bmp');
				$imagetable = &JTable::getInstance('images', 'FLItemsTable');

				// Check Image Folders Exist
				$thisFolderPath = JPATH_ROOT . '/images/fl_items/' . $row->item_id;
				$thisOriginalFolderPath = $thisFolderPath . '/original';
				$thisFilePath = JPATH_ROOT . '/images/fl_items/files/' . $row->item_id;
				$thisOriginalFilePath = $thisFilePath . '/original';
				if (!JFolder::exists($thisFolderPath)) {
					JFolder::create($thisFolderPath);
				}
				if (!JFolder::exists($thisOriginalFolderPath)) {
					JFolder::create($thisOriginalFolderPath);
				}
				if (!JFolder::exists($thisFilePath)) {
					JFolder::create($thisFilePath);
				}
				if (!JFolder::exists($thisOriginalFilePath)) {
					JFolder::create($thisOriginalFilePath);
				}

				// Upload new images
				for ($iNewImage = 1; $iNewImage <= 5; $iNewImage++) {
					$this_imageUpload = JRequest::getVar('new-img-' . $iNewImage, null, 'files', 'array');
					if (isset($this_imageUpload) && in_array($this_imageUpload['type'], $allowedFileTypes) && !empty($this_imageUpload['tmp_name'])) {
						$imagetable->item_image_id = 0;
						$imagetable->item_id = $row->item_id;
						$imagetable->showImage = 1;
						$imagetable->store();

						$this_filename = $imagetable->item_image_id . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '.' . JFile::getExt($this_imageUpload['name']);
						echo "Upload " . $this_imageUpload['tmp_name'] . " to " . $thisOriginalFolderPath . '/' . $this_filename . "<br>";
						JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFolderPath . '/' . $this_filename);

						$imagetable->filename = $this_filename;
						$imagetable->ordering = $imageOrdering;
						$imagetable->store();

						$imageOrdering++;
					} else if(isset($this_imageUpload) && !in_array($this_imageUpload['type'], $allowedFileTypes) && !empty($this_imageUpload['tmp_name'])) {
						JFactory::getApplication()->enqueueMessage('File type not supported: "'.$this_imageUpload['name'] .'"', "danger");
					}
				}

				$files = JRequest::get('files');
				foreach ($files as $key => $val) {
					if (strpos($key, "file-") !== false) {
						$prop = substr($key, 5);

						$this_imageUpload = JRequest::getVar($key, null, 'files', 'array');

						if (isset($this_imageUpload) && (in_array($this_imageUpload['type'], $allowedFileTypes) || in_array(JFile::getExt($this_imageUpload['name']), array("pdf")))) {

							$query = 'SELECT items_item_property_id, `value` '
							. ' FROM #__fl_items_item_property AS pi'
							. ' WHERE item_id = ' . $row->item_id
							. ' AND item_property_id = (SELECT item_property_id FROM #__fl_items_properties WHERE  name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id . ')'
							;
							$db->setQuery($query);
							$res = $db->loadObjectList();
							$isPropUpdate = $res[0]->items_item_property_id;
							$oldFilename = $res[0]->value;

							$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id;
							$db->setQuery($query);
							$propId = $db->loadResult();

							$propRow = &JTable::getInstance('itemproperty', 'FLItemsTable');

							$this_filename = $propId . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '.' . JFile::getExt($this_imageUpload['name']);

							if ($isPropUpdate) {
								JFile::delete($thisFilePath . '/' . $oldFilename);
								JFile::delete($thisOriginalFilePath . '/' . $oldFilename);
								$propRow->load((int) $isPropUpdate);
								$propRow->value = $this_filename;
								$propRow->store();
							} else {
								$propRow->item_id = $row->item_id;
								$propRow->item_property_id = $propId;
								$propRow->value = $this_filename;
								$propRow->store();
							}

							JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFilePath . '/' . $this_filename);
						}
					}
				}
			}
			// redirect
			$app = JFactory::getApplication();
			$message = "Save Successful!";
			$app->redirect(JRoute::_('index.php?option=com_fl_items&view=update&id=' . $this->getOneItem['item']['item_id'], false), $message, 'success');
		}

		exit;
	}

	function getSEO() {
		return null;
	}

}
?>
