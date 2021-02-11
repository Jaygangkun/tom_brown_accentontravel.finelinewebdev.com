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
class FL_MenuMakerControllerMenu extends JControllerLegacy
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
        $db =& JFactory::getDBO();
		
		$sql = "SELECT extension_id FROM #__extensions WHERE element = 'com_fl_items' LIMIT 1";
        $db->setQuery($sql);
        $flItemComponentId = $db->loadResult();
		
		if($flItemComponentId) {
	        $sql = "SELECT name, item_category_id FROM #__fl_items_category WHERE showCategory=1";
	        $db->setQuery($sql);
	        $lists['itemTypes'] = $db->loadObjectList();
		} else {
			$lists['itemTypes'] = array();
		}

        require_once(JPATH_COMPONENT.'/views/menu.php');
        FL_MenumakerViewMenu::menu( $lists );
	}

	function build() {

        $db =& JFactory::getDBO();

        $idMap = array();
        $postItems = array();

        $moduleSource = "101,";

        $sql = "SELECT MIN(id) FROM #__users";
        $db->setQuery($sql);
        $userId = $db->loadResult();
        if(empty($userId)) {
            echo "User not found.";
            return false;
        }
		
		$sql = "SELECT extension_id FROM #__extensions WHERE element = 'com_fl_items' LIMIT 1";
        $db->setQuery($sql);
        $flItemComponentId = $db->loadResult();

        foreach($_POST as $key => $val) {
            if(strpos($key, "menu-") === 0 ) {
                $split = explode("---", $val);
                $id = substr($key, 5);
                $title = $split[0];
                $level = $split[1];
                $parent = $split[2];
                $type = $split[3];
                $postItems[$id] = array(
                    "id" => $id,
                    "title" => $title,
                    "level" => $level,
                    "parent" => $parent,
                    "type" => $type
                );
            }
        }

        foreach($postItems as $p) {
            $alias = JFilterOutput::stringURLSafe($p['title']);
			
			$sql = "SELECT COUNT(*) FROM #__menu WHERE alias = ".$db->quote($alias);
			$db->setQuery($sql);
			$db->execute();
            $aliasExists = $db->loadResult();
			$aliasC = 2;
			while($aliasExists) {
				$alias = "$alias-$aliasC";
				$sql = "SELECT COUNT(*) FROM #__menu WHERE alias = ".$db->quote($alias);
				$db->setQuery($sql);
				$db->execute();
                $aliasExists = $db->loadResult();
				$aliasC++;
				if($aliasC > 200) {
					echo "You got alias probz.";
					exit;
				}
			}
			
            $path = $alias;
            if($p['parent'] > 0) {
                $currentParent = $p['parent'];
                while($currentParent > 0) {
                    $path = JFilterOutput::stringURLSafe($postItems[$currentParent]['title']) . "/" . $path;
                    $currentParent = $postItems[$currentParent]['parent'];
                }
            }
            $lft = 0;
            $rgt = 0;

            // Create Article
            if($p['type'] == 1) {
            	
                // Insert Article
                $sql = "INSERT INTO #__content (title, alias, state, catid, created, created_by, publish_up, images, urls, attribs, version, ordering, access, metadata, language )
                    VALUES (
                        ".$db->quote($p[title]).",
                        ".$db->quote($alias).",
                        '1',
                        '2',
                        '".date("Y-m-d")."',
                        $userId,
                        '".date("Y-m-d")."',
                        '{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}',
                        '{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}',
                        '{\"show_title\":\"\",\"link_titles\":\"\",\"show_tags\":\"\",\"show_intro\":\"\",\"info_block_position\":\"\",\"info_block_show_title\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',
                        '1',
                        '1',
                        '1',
                        '{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',
                        '*'
                    )
                ";
                $db->setQuery($sql);
                $db->execute();
                $articleId = $db->insertid();

                // Insert Asset
                $sql = "INSERT INTO #__assets ( parent_id, level, name, title, rules )
                    VALUES (
                        27,
                        3,
                        'com_content.article.$articleId',
                        ".$db->quote($p[title]).",
                        '{\"core.admin\":{\"7\":1},\"core.options\":[],\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1},\"core.delete\":[],\"core.edit\":{\"4\":1},\"core.edit.state\":{\"5\":1},\"core.edit.own\":[]}'
                    )
                ";
                $db->setQuery($sql);
                $db->execute();
                $assetId = $db->insertid();

                // Update Article
                $sql = "UPDATE #__content SET asset_id = $assetId WHERE id = $articleId";
                $db->setQuery($sql);
                $db->execute();
            }

			if(strpos($p['type'], "2=") === 0) {
				// FL Item
				$categoryId = substr($p['type'], 2);
				$link = "index.php?option=com_fl_items&view=results";
				$params = '{"item_category_id":"'.$categoryId.'","top_title":"'.str_replace('"', '\"', $p[title]).'","search_params":"","order_by":"","limit":"30","featured_only":"0","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_image_css":"","menu_text":1,"menu_show":1,"page_title":"","show_page_heading":"","page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}';
				$componentId = $flItemComponentId;
			} else {
				// Article
				$link = "index.php?option=com_content&view=article&id=$articleId";
				$params = '{"show_title":"","link_titles":"","show_intro":"","info_block_position":"","info_block_show_title":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_vote":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"","show_tags":"","show_noauth":"","urls_position":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"menu_show":1,"page_title":"","show_page_heading":"","page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}';
				$componentId = 22;
			}

            // Insert Menu-Item
            $sql = "INSERT INTO #__menu (menutype, title, alias, path, link, type, published, parent_id, level, component_id, access, params, lft, rgt, language, client_id )
                VALUES (
                    'mainmenu',
                    ".$db->quote($p[title]).",
                    ".$db->quote($alias).",
                    '$path',
                    '$link',
                    'component',
                    '1',
                    '1',
                    '$p[level]',
                    '$componentId',
                    '1',
                    '$params',
                    '$lft',
                    '$rgt',
                    '*',
                    '0'
                )
            ";
            $db->setQuery($sql);
            $db->execute();
            $insertId = $db->insertid();

            if($p[level] == 1) {
                $moduleSource .= $insertId.",";
            }

            $idMap[$p['id']] = $insertId;
        }

        // Update parents!
        foreach($postItems as $p) {
            if($p['level'] > 1) {
                $menuId = $idMap[$p['id']];
                $parentMenuId = $idMap[$p['parent']];

                $sql = "UPDATE #__menu SET parent_id = $parentMenuId WHERE id = $menuId";
                $db->setQuery($sql);
                $db->execute();
            }
        }

        // Update Menu Module!
        $menuModuleId = 94;
        $sql = "SELECT params FROM #__modules WHERE id = $menuModuleId";
        $db->setQuery($sql);
        $oldParams = $db->loadResult();
        $newParams = str_replace("101", substr($moduleSource,0,strlen($moduleSource)-1), $oldParams);

        $sql = "UPDATE #__modules SET params = '$newParams' WHERE id = $menuModuleId";
        $db->setQuery($sql);
        $db->execute();


        $link = 'index.php?option=com_fl_menumaker' ;
        $this->setRedirect( $link, JText::_( 'Menu Created! Remove this component to be safe!' ) );
    }

	function edit()
	{
		
	}

	/**
	 * Save METHOD
	 */
	function save()
	{
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $this->setRedirect( 'index.php?option=com_fl_menumaker&c=galleries' );

        // Initialize variables
        $db	=& JFactory::getDBO();
        $post	= JRequest::get( 'post' );
        $row    =& JTable::getInstance('galleries', 'fl_menumakerTable');
        $row->bind( $post );
        $row->checkin();
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_menumaker&c=galleries' );

		// Initialize variables
		$db	=& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row    =& JTable::getInstance('galleries', 'fl_menumakerTable');
		$row->bind( $post );
		$row->checkin();
	}

	function publish()
	{
		
	}

	function remove()
	{
		
	}

}
