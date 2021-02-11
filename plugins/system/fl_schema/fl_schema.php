<?php
/**
* @version 1.4.0
* @package RSform!Pro 1.4.0
* @copyright (C) 2007-2013 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * RSForm! Pro system plugin
 */
class plgSystemFL_Schema extends JPlugin
{
	public function __construct( &$subject, $config ) {
		parent::__construct( $subject, $config );
	}

	public function onBeforeRender() {
	}
	
	private function getSchema($row) {
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$sitename = $app->getCfg('sitename');
		$baseurl = JUri::base();
		$page_title = $doc->getTitle();
		$meta_description = $doc->getMetaData("description"); 
		
		ob_start();
		?>
		<script type="application/ld+json">
		{
	  		"@context": "http://schema.org",
	  		"@type": "<?= $row->businessType; ?>",
	  		"name": "<?= $sitename; ?>",
	  		"description": "<?= $meta_description; ?>",
	  		"@id": "<?= $baseurl; ?>",
	  		<?php if($row->logo) { ?>
	  			"logo": "<?= $baseurl . $row->logo; ?>",
  			<?php } ?>
	  		<?php if($row->image || $row->logo) { ?>
		  		<?php if($row->image) { ?>
		  			"image": "<?= $baseurl . $row->image; ?>",
	  			<?php } else { ?>
		  			"image": "<?= $baseurl . $row->logo; ?>",
	  			<?php } ?>
  			<?php } ?>
	  		<?php if($row->phone) { ?>
		  		"contactPoint": [
	    			{
	    				"@type": "ContactPoint",
	      				"telephone": "+1-<?= $row->phone; ?>",
	      				"contactType": "Customer Service"
	    			}
	  			],
	  			"telephone": "+1-<?= $row->phone; ?>",
  			<?php } ?>
	  		<?php if($row->fax) { ?>
  				"faxNumber": "+1-<?= $row->fax; ?>",
  			<?php } ?>
	  		<?php if($row->email) { ?>
	  			"email": "<?= $row->email; ?>",
  			<?php } ?>
	  		<?php if($row->priceRange) { ?>
	  			"priceRange": "<?= $row->priceRange; ?>",
  			<?php } ?>
	  		<?php if($row->hours) {
	  			$allHours = explode(",", $row->hours);
				if(count($allHours)) { ?>
		  		"openingHoursSpecification" : [
		  			<?php
                    	$days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
		  				$hrs = array();
						$allHours = explode(",", $row->hours);
		  				foreach($allHours as $hr) {
		  					$split = explode("-", $hr);
							$day = $split[0];
							$open = $split[1];
							$close = $split[2];
		  					$hrs[] = '
		  					{
		  						"@type": "OpeningHoursSpecification",
		  						"dayOfWeek": [
									"'.$days[$day].'"
							    ],
							    "opens": "'.$open.'",
								"closes": "'.$close.'"
		  					}';
		  				}
						echo implode(",\n", $hrs) . "\n";
		  			?>
		  		],
  				<?php } ?>
  			<?php } ?>
	  		<?php if($row->sameAs) { ?>
		  		"sameAs": [
		  			<?php 
		  				echo '"' . implode("\",\n\t\t\t\t\"", explode(",", $row->sameAs)) . "\"\n";
	  				?>
		  		],
  			<?php } ?>
	  		<?php if($row->address && $row->city && $row->state && $row->zip) { ?>
		  		"address": {
					"@type": "PostalAddress",
		    		"streetAddress": "<?= $row->address; ?>",
		    		"addressLocality": "<?= $row->city; ?>",
		    		"addressRegion": "<?= $row->state; ?>",
		    		"postalCode": "<?= $row->zip;?>",
		    		"addressCountry": "US"
		  		},
  			<?php } ?>
	  		<?php if($row->googleMapLink) { ?>
	  			"hasMap": "<?= $row->googleMapLink; ?>",
  			<?php } ?>
	  		<?php if($row->latitude && $row->longitude) { ?>
		  		"geo": {
		    		"@type": "GeoCoordinates",
		    		"latitude": <?= $row->latitude; ?>,
		    		"longitude": <?= $row->longitude; ?>
		  		},
  			<?php } ?>
	  		<?php if($row->areasServed) { ?>
				"areaServed": [
					<?php
						$areas = array();
						$allAreas = explode(",", $row->areasServed);
						foreach($allAreas as $as) {
							$split = explode("::", $as);
							$type = $split[0];
							$name = $split[1];
							$areas[] = '{"@type": "'.$type.'", "name": "'.$name.'"}';
						}
						echo implode(",\n\t\t\t\t", $areas) . "\n";
					?>
				],
  			<?php } ?>
	  		<?php if(strlen($row->offerCatalog) > 2) { ?>
				"hasOfferCatalog": [
	  			<?php
	  				$categoryList = array();
					$row->offerCatalog = json_decode($row->offerCatalog);
	  				foreach($row->offerCatalog as $ocData) {
	  					$category = $ocData[0];
						$offers = $ocData[1];
	  					$thisOffers = array();
						foreach($offers as $k => $data) {
							$thisOffers[] = '
							{
								"@type": "Offer",
								"itemOffered": {"@type": "'.$data[0].'", "name": "'.$data[1].'"}
							}';
						}
						$offerList = implode(",", $thisOffers);
						
	  					$categoryList[] = '
	  					{
	  						"@type": "OfferCatalog",
			        		"name": "'.$category.'",
			        		"itemListElement": ['.$offerList.']
		        		}';
	  				}
					echo implode(",", $categoryList);
	  			?>
				],
  			<?php } ?>
	  		"url": "<?= $baseurl; ?>"
		}
		</script>
		
		<?php
		$schemaValue = ob_get_contents();
		ob_end_clean();
		return $schemaValue;
	}
	
	private function getMeta($row) {
		$app = JFactory::getApplication();
		$sitename = $app->getCfg('sitename');
		$output = '
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="format-detection" content="telephone=yes">
		<meta name="author" content="Fine Line Websites &amp; IT Consulting">
		<meta name="dcterms.rightsHolder" content="&copy;'.date('Y').' '.$sitename.'">
		<meta name="robots" content="follow">
		';
		if($row->state) {
        	$output.= '<meta name="geo.region" content="US-'.$row->state.'">'."\n";
        }
        if($row->city) {
        	$output.= '<meta name="geo.placename" content="'.$row->city.'">'."\n";
        }
        if($row->latitude && $row->longitude) {
        	$output.= '<meta name="geo.position" content="'.$row->latitude.';'.$row->longitude.'">'."\n";
        }
		return $output;
	}
	
	private function getTwitterOG() {
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$sitename = $app->getCfg('sitename');
		$template = $app->getTemplate();
		$baseurl = JUri::base();
		
		ob_start();
		?>
		<meta name="twitter:card" content="summary">
		<meta name="twitter:image:src" content="<?= JURI::base();?>templates/fluid/images/ico/social-card.png">
		<meta property="og:site_name" content="<?= $sitename; ?>">
		<meta property="og:url" content="<?= JUri::getInstance(); ?>">
		<meta property="og:title" content="<?= $doc->getTitle(); ?>">
		<meta property="og:description" content="<?= $doc->getMetaData("description") ?>">
		<meta property="og:locale" content="en_US">
		<meta property="og:type" content="article">
		
		<link rel="icon" href="<?= $baseurl ?>templates/<?= $template ?>/images/ico/favicon.ico">
		<link rel="apple-touch-icon" href="<?= $baseurl ?>templates/<?= $template ?>/images/ico/touch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?= $baseurl ?>templates/<?= $template ?>/images/ico/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?= $baseurl ?>templates/<?= $template ?>/images/ico/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?= $baseurl ?>templates/<?= $template ?>/images/ico/touch-icon-ipad-retina.png">
		<link rel="shortcut icon" href="<?= $baseurl ?>templates/<?= $template ?>/images/ico/favicon.png">
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function onAfterRender() {
		$mainframe 	= JFactory::getApplication();
		$doc 		= JFactory::getDocument();
		if ($doc->getType() != 'html' || $mainframe->isAdmin()) {
			return;
		}

		$content = JResponse::getBody();

		if (strpos($content, '{flschema-json}') === false && strpos($content, '{flschema-meta}') === false && strpos($content, '{flschema-twitter-og}') === false)
			return true;
			
		$db	=& JFactory::getDBO();
		$query = 'SELECT * FROM #__fl_schema WHERE fl_schema_id = 1';
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		if (strpos($content, '{flschema-image}') !== false) {
			if($row->logo) {
				$output = '<img itemprop="url" src="'.JUri::base().$row->logo.'"/>';
			} else if($row->image) {
				$output = '<img itemprop="url" src="'.JUri::base().$row->image.'"/>';
			} else {
				$output = "";
			}
			$content = str_replace("{flschema-image}", $output, $content);
		}
		if (strpos($content, '{flschema-json}') !== false) {
			$schemaValue = $this->getSchema($row);
			$content = str_replace("{flschema-json}", $schemaValue, $content);
		}
		
		if (strpos($content, '{flschema-meta}') !== false) {
			$schemaValue = $this->getMeta($row);
			$content = str_replace("{flschema-meta}", $schemaValue, $content);
		}
		
		if (strpos($content, '{flschema-twitter-og}') !== false) {
			$schemaValue = $this->getTwitterOG($row);
			$content = str_replace("{flschema-twitter-og}", $schemaValue, $content);
		}
		
		JResponse::setBody($content);
	}
}