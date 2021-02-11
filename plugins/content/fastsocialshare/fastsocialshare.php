<?php
/**
 * @package FASTSOCIALSHARE
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * Social Share Buttons Plugin
 *
 * @package		Social Share Buttons Plugins
 * @subpackage	Social
 * @since 		1.5
 */
class plgContentFastSocialShare extends JPlugin {

	/**
	 * Default lang tags
	 * @var string
	 * @access private
	 */
	private $langTag = "en_US";
	
	/**
	 * Default lang starttag
	 * @var string
	 * @access private
	 */
	private $langStartTag = 'en';
	
	/**
	 * Component dispatch view
	 * @var string
	 * @access private
	 */
	private $componentView = null;

	/**
	 * Singleton for the FB SDK
	 * @var string
	 * @access private
	 */
	private static $FBSDKInjected;

	/**
	 * Static singleton function to inject the Facebook SDK in the page
	 * 
	 * @param 
	 */
	private function getFacebookSDK($appID) {
		$html = null;

		if(!self::$FBSDKInjected) {
			$html = <<<JS
					<script>
					var loadAsyncDeferredFacebook = function() {
						(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/{$this->langTag}/sdk.js#xfbml=1&version=v3.0$appID";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					}
	
			  		if (window.addEventListener)
						window.addEventListener("load", loadAsyncDeferredFacebook, false);
					else if (window.attachEvent)
						window.attachEvent("onload", loadAsyncDeferredFacebook);
					else
				  		window.onload = loadAsyncDeferredFacebook;
					</script>
JS;
			self::$FBSDKInjected = true;
		}
			
		return $html;
	}
	
	/**
	 * Generate content
	 * @param   object      The article object.  Note $article->text is also available
	 * @param   object      The article params
	 * @param   boolean     Modules context
	 * @return  string      Returns html code or empty string.
	 */
	private function getContent(&$article, &$params, $moduleContext = false) {

		$doc = JFactory::getDocument();
		/* @var $doc JDocumentHtml */

		$doc->addStyleSheet(JUri::root() . "plugins/content/fastsocialshare/style/style.css");

		$uriInstance = JUri::getInstance();
		
		if(!$moduleContext) {
			if(!class_exists('ContentHelperRoute')) {
				include_once JPATH_SITE . '/components/com_content/helpers/route.php';
			}
			if(!isset($article->slug)) {
				$url = JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->language), false);
			} else {
				$url = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->language), false);
			}
			$root = rtrim($uriInstance->getScheme() . '://' . $uriInstance->getHost(), '/');
			$url = $root . $url;
			$title = htmlentities($article->title, ENT_QUOTES, "UTF-8");
		} else {
			$url = JUri::current();
			$title = htmlentities($doc->title, ENT_QUOTES, "UTF-8");
			$article->id = rand(1000, 10000);
		}

		// Force the http domain?
		if($this->params->get('use_http_domain', 0)) {
			$url = str_replace('https://', 'http://', $url);
		}
		
		// Reset FB SDK singleton
		self::$FBSDKInjected = null;
		
		$headerHtml = trim($this->params->get('headerText'));
		$headerHtml = (strlen($headerHtml) > 0) ? '<div class="fastsocialshare-text">' . JText::_($headerHtml) . '</div>' : '';
		
		$html = trim($this->getFacebookLike($this->params, $url, $title));
		$html .= trim($this->getFacebookShareMe($this->params, $url, $title));
		$html .= trim($this->getTwitter($this->params, $url, $title));
		$html .= trim($this->getLinkedIn($this->params, $url, $title));
		$html .= trim($this->getPinterest($this->params, $url, $title));
		$html .= trim($this->getWhatsapp($this->params, $url, $title));
		$html .= trim($this->getXing($this->params, $url, $title));
		
		$alignment = $this->params->get('alignment');
		$alignClass = ' fastsocialshare-align-';
		
		switch($alignment){
			case 0:
				$alignClass .= 'left';
				break;
			case 1:
				$alignClass .= 'center';
				break;
			case 2:
				$alignClass .= 'right';
				break;
			default:
				$alignClass .= 'left';
		}
		
		$openerHtml = '';
		$toggledClass = '';
		if($this->params->get('togglermode', 0)) {
			$toggledClass = ' fastsocialshare_toggled';
			$togglericonsBackgroundColor = $this->params->get("togglericons_background_color", "#1877f2");
			$togglerIconsText = JText::_($this->params->get("togglericons", "Toggle sharing icons"));
			$togglerIconColor = $this->params->get("togglericons_icon_color", "light") == 'light' ? 'fastsocialshare-opener-white' : 'fastsocialshare-opener-black';
			$openerHtml = '<input type="checkbox" class="fastsocialshare-control" id="fastsocialshare-control-' . $article->id . '"><label style="background-color:' . $togglericonsBackgroundColor . '" class="fastsocialshare-opener ' . $togglerIconColor . '" title="' . $togglerIconsText . '" for="fastsocialshare-control-' . $article->id . '"></label>';
		}
		
		return '<div class="fastsocialshare_container' . $alignClass . $toggledClass . '">' . $openerHtml . $headerHtml . '<div class="fastsocialshare-subcontainer">' . $html . '</div></div>';
	}

	private function getFacebookLike($params, $url, $title) {
		$html = null;
		$appID = null;
		if ($params->get("facebookLikeButton", true)) {
			$layout = $params->get("facebookLikeType", "button_count");
			if (strcmp("box_count", $layout) == 0) {
				$height = "80";
			} else {
				$height = "25";
			}

			if ($params->get("facebookLikeAppId")) {
				$appID = '&appId=' . $params->get("facebookLikeAppId");
			}

			// Get the Facebook SDK only the very first time
			$html = $this->getFacebookSDK($appID);

			$html .= '<div class="fastsocialshare-share-fbl fastsocialshare-' . $layout . '">';
			$html .= '
				<div class="fb-like"
					data-href="' . $url . '"
					data-layout="' . $layout . '"
                	data-width="' . $params->get("facebookLikeWidth", "450") . '"
					data-action="' . $params->get("facebookLikeAction", 'like') . '"
					data-show-faces="' . $params->get("facebookLikeShowfaces", 'true') . '"
					data-share="false">
				</div>';
			$html .= '</div>';
		}

		return $html;
	}
	
	private function getFacebookShareMe($params, $url, $title) {
		$html = null;
		$appID = null;
		
		if (!$params->get("facebookShareMeButton", 1)) {
			return $html;
		}
		
		// Evaluate the button type
		$buttonType = $params->get('facebookShareMeButtonType', 'core');
		
		switch($buttonType) {
			case 'custom':
				// Get the number of shares for this URL
				$sharesCounterCode = null;
				if ($params->get("facebookShareMeCounter", 0)) {
					$encodedUrl = rawurlencode($url);
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/?id=' . $encodedUrl . '&fields=og_object{engagement}');
					// Get cURL resource
					$curl = curl_init();
					// Set some options - we are passing in a useragent too here
					curl_setopt_array($curl, array(
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => 'https://graph.facebook.com/?id=' . $encodedUrl . '&fields=og_object{engagement}',
							CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36',
							CURLOPT_POST => 0
					));
					// Send the request & save response to $resp
					$sharesJsonData = curl_exec($curl);
					// Close request to clear up some resources
					curl_close($curl);

					if($sharesJsonData) {
						$sharesJsonData = json_decode($sharesJsonData);
						if(isset($sharesJsonData->og_object)) {
							if(isset($sharesJsonData->og_object->engagement) && isset($sharesJsonData->og_object->engagement->count)) {
								$numberOfShares = $sharesJsonData->og_object->engagement->count;
								$sharesCounterCode = 
											'<div class="fbshare_container_counter">
												<div class="pluginCountButton pluginCountNum">
													<span>
														<span class="pluginCountTextConnected">' . $numberOfShares . '</span>
													</span>
												</div>
												<div class="pluginCountButtonNub">
													<s></s>
													<i></i>
												</div>
											 </div>';
							}
						}
					}
				}

				
				$colorText = $params->get("facebookShareMeBadgeText", "#C0C0C0");
				$badgeColor = $params->get("facebookShareMeBadge", "#CC00FF");
				$badgeLabel = JText::_($params->get("facebookShareMeBadgeLabel", "Share"));
				$encodedUri = rawurlencode($url);
				$html = <<<JS
							<div class="fastsocialshare-share-fbsh">
	    					<a style="background-color:$badgeColor; color:$colorText !important;" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=$encodedUri','fbshare','width=480,height=100')" href="javascript:void(0)"><span>f</span><span>$badgeLabel</span></a>
	    					$sharesCounterCode
							</div>
JS;
			break;
			
			case 'core':
				if ($params->get("facebookLikeAppId")) {
					$appID = '&appId=' . $params->get("facebookLikeAppId");
				}
				$html = $this->getFacebookSDK($appID);
				$layout = $params->get("facebookShareMeCounter", 0) ? 'button_count' : 'button';
				$html .= '
					<div class="fastsocialshare-share-fbsh fb-shareme-core">
					<div class="fb-share-button fb-shareme-core"
						data-href="' . $url . '"
						data-layout="' . $layout . '"
						data-size="small">
					</div>';
				$html .= '</div>';
			break;
		}
		
		return $html;
	}
	
	private function getTwitter($params, $url, $title) {
		$twitterCounter = $params->get("twitterCounter", 'none');
		$twitterName = $params->get("twitterName", '');
		$twitterSize = null;
		if($params->get("twitterSize", 0)) {
			$twitterSize = 'data-size="large"';
		}

		$html = "";
		if($params->get("twitterButton", true)) {
			$html = <<<JS
						<div class="fastsocialshare-share-tw">
						<a href="https://twitter.com/intent/tweet" data-dnt="true" class="twitter-share-button" $twitterSize data-text="$title" data-count="$twitterCounter" data-via="$twitterName" data-url="$url" data-lang="{$this->langStartTag}">Tweet</a>
						</div>
						<script>
							var loadAsyncDeferredTwitter =  function() {
	            						var d = document;
	            						var s = 'script';
	            						var id = 'twitter-wjs';
					            		var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){
						        		js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}
					        		}
						
							if (window.addEventListener)
								window.addEventListener("load", loadAsyncDeferredTwitter, false);
							else if (window.attachEvent)
								window.attachEvent("onload", loadAsyncDeferredTwitter);
							else
								window.onload = loadAsyncDeferredTwitter;
						</script>
JS;
		}

		return $html;
	}

	private function getLinkedIn($params, $url, $title) {
		$language = "lang: " . $this->langTag;
		
		$html = "";
		if ($params->get("linkedInButton", true)) {
			$dataCounter = $params->get("linkedInType", 'right');
			$html = <<<JS
						<div class="fastsocialshare-share-lin">
						<script type="text/javascript">
							var loadAsyncDeferredLinkedin =  function() {
								var po = document.createElement('script');
								po.type = 'text/javascript';
								po.async = true;
								po.src = 'https://platform.linkedin.com/in.js';
								po.innerHTML = '$language';
								var s = document.getElementsByTagName('script')[0];
								s.parentNode.insertBefore(po, s);
							};
		
							 if (window.addEventListener)
							  window.addEventListener("load", loadAsyncDeferredLinkedin, false);
							else if (window.attachEvent)
							  window.attachEvent("onload", loadAsyncDeferredLinkedin);
							else
							  window.onload = loadAsyncDeferredLinkedin;
						</script>
						<script type="in/share" data-url="$url" data-counter="$dataCounter"></script>
						</div>
JS;
		}
	
		return $html;
	}
	
	private function getWhatsapp($params, $url, $title) {
		$html = "";
		if ($params->get("whatsappButton", false)) {
			$colorText = $params->get("whatsappBadgeText", "#FFFFFF");
			$badgeColor = $params->get("whatsappBadge", "#3B5998");
			$badgeLabel = $params->get("whatsappBadgeLabel", "Whatsapp");
			$encodedUri = rawurlencode($url) . ' - ' . rawurlencode($title);
			$svgIcon = '<svg style="vertical-align:text-bottom" fill="#fff" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 2 40 40"><g><path d="m25 21.7q0.3 0 2.2 1t2 1.2q0 0.1 0 0.3 0 0.8-0.4 1.7-0.3 0.9-1.6 1.5t-2.2 0.6q-1.3 0-4.3-1.4-2.2-1-3.8-2.6t-3.3-4.2q-1.6-2.3-1.6-4.3v-0.2q0.1-2 1.7-3.5 0.5-0.5 1.2-0.5 0.1 0 0.4 0t0.4 0.1q0.4 0 0.6 0.1t0.3 0.6q0.2 0.5 0.8 2t0.5 1.7q0 0.5-0.8 1.3t-0.7 1q0 0.2 0.1 0.3 0.7 1.7 2.3 3.1 1.2 1.2 3.3 2.2 0.3 0.2 0.5 0.2 0.4 0 1.2-1.1t1.2-1.1z m-4.5 11.9q2.8 0 5.4-1.1t4.5-3 3-4.5 1.1-5.4-1.1-5.5-3-4.5-4.5-2.9-5.4-1.2-5.5 1.2-4.5 2.9-2.9 4.5-1.2 5.5q0 4.5 2.7 8.2l-1.7 5.2 5.4-1.8q3.5 2.4 7.7 2.4z m0-30.9q3.4 0 6.5 1.4t5.4 3.6 3.5 5.3 1.4 6.6-1.4 6.5-3.5 5.3-5.4 3.6-6.5 1.4q-4.4 0-8.2-2.1l-9.3 3 3-9.1q-2.4-3.9-2.4-8.6 0-3.5 1.4-6.6t3.6-5.3 5.3-3.6 6.6-1.4z"></path></g></svg>';
			$html = <<<JS
						<div class="fastsocialshare-share-whatsapp">
    					<a style="text-decoration:none; border-radius: 2px; padding:2px 5px; font-size:14px; background-color:$badgeColor; color:$colorText !important;" onclick="window.open('https://api.whatsapp.com/send?text=$encodedUri','whatsappshare','width=640,height=480')" href="javascript:void(0)"><span class='fastsocialshare-share-whatsappicon'  style='margin-right:4px'>$svgIcon</span><span class='fastsocialshare-share-whatsapptext'>$badgeLabel</span></a>
						</div>
JS;
		}

		return $html;
	}
	
	private function getPinterest($params, $url, $title) {
		$html = "";
		if($params->get("pinterestButton", true)) {
			$html = <<<JS
						<div class="fastsocialshare-share-pinterest">
						<a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark"  data-pin-color="red"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_red_20.png" alt="Pin It" /></a>
						<script type="text/javascript">
							(function (w, d, load) {
							 var script, 
							 first = d.getElementsByTagName('SCRIPT')[0],  
							 n = load.length, 
							 i = 0,
							 go = function () {
							   for (i = 0; i < n; i = i + 1) {
							     script = d.createElement('SCRIPT');
							     script.type = 'text/javascript';
							     script.async = true;
							     script.src = load[i];
							     first.parentNode.insertBefore(script, first);
							   }
							 }
							 if (w.attachEvent) {
							   w.attachEvent('onload', go);
							 } else {
							   w.addEventListener('load', go, false);
							 }
							}(window, document, 
							 ['//assets.pinterest.com/js/pinit.js']
							));    
							</script>
						</div>
JS;
		}
	
		return $html;
	}
	
	private function getXing($params, $url, $title) {
		$xingCounter = $params->get("xingCounter", 0) ? 'data-counter="right"' : null;
		$xingShape = $params->get("xingShape", 'rectangular');
		$xingLanguage = $this->langStartTag == 'de' ? 'de' : 'en';

		$html = "";
		if($params->get("xingButton", 0)) {
			$html = <<<JS
						<div class="fastsocialshare-share-xing">
						<div data-type="xing/share" data-shape="$xingShape" $xingCounter data-url="$url" data-lang="$xingLanguage"></div>
						<script>
						  ;(function (d, s) {
						    var x = d.createElement(s),
						      s = d.getElementsByTagName(s)[0];
						      x.src = "https://www.xing-share.com/plugins/share.js";
						      s.parentNode.insertBefore(x, s);
						  })(document, "script");
						</script>
						</div>
JS;
		}
	
		return $html;
	}
	
	/**
	 * Add social buttons into the article
	 *
	 * Method is called by the view
	 *
	 * @param   string  The context of the content being passed to the plugin.
	 * @param   object  The content object.  Note $article->text is also available
	 * @param   object  The content params
	 * @param   int     The 'page' number
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart) {
		$app = JFactory::getApplication();
		/* @var $app JApplication */
	
		if ($app->isAdmin()) {
			return;
		}
		
		if(!$article instanceof stdClass || $context == 'com_content.categories') {
			return;
		}
	
		$doc = JFactory::getDocument();
		/* @var $doc JDocumentHtml */
		$docType = $doc->getType();
	
		// Check document type
		if (strcmp("html", $docType) != 0) {
			$article->text = str_replace('{fastsocialshare}', '', $article->text);
			return;
		}
		// Output JS APP nel Document
		if($app->input->get('print')) {
			$article->text = str_replace('{fastsocialshare}', '', $article->text);
			return;
		}
	
		$this->componentView = $app->input->get("view");
		$isValidContext = !!preg_match('/com_content/i', $context);
		$isModuleContext = !!preg_match('/mod_custom/i', $context);
		
		// Check if it's a mod_custom context and manage as page URL sharing
		if($isModuleContext) {
			// Get plugin contents
			$content = $this->getContent($article, $params, true);
			$article->text = str_replace('{fastsocialshare}', $content, $article->text);
			return;
		}
		
		// Opengraph meta, extract the first image from the article-entity/first article-entity text html
		$og_incats = $this->params->get('og_incats', false);
		if($article->text && ($context == 'com_content.article' || (in_array($context, array('com_content.category', 'com_content.featured')) && $og_incats))) {
			$property = version_compare(JVERSION, '3.6', '>=') ? 'property' : false;
			if($this->params->get('ogimage_detection', 1) && !$doc->getMetaData('og:image')) {
				$firstImageFound = false;
				$imageDetectionType = $this->params->get('ogimage_detection_type', 'image_fulltext');
				
				// Get the full article image if any
				if($context == 'com_content.article' && isset($article->images)) {
					$imagesDecoded = json_decode($article->images);
					if(isset($imagesDecoded->{$imageDetectionType}) && $imagesDecoded->{$imageDetectionType}) {
						$firstImageFound = true;
						$firstImage = JUri::root(false) . ltrim($imagesDecoded->{$imageDetectionType}, '/');
						$doc->setMetaData('og:image', $firstImage, $property);
						$doc->setMetaData('twitter:image', $firstImage, $property);
					}
				}
				
				// Not found an image in the fulltext image, fallback to the first article image
				if(!$firstImageFound) {
					$firstImageFound = preg_match('/(<img)([^>])*(src=["\']([^"\']+)["\'])([^>])*/i', $article->text, $matches);
					if($firstImageFound) {
						$firstImage = $matches[4];
						$firstImage = preg_match('/^http/i', $firstImage) ? $firstImage : JUri::root(false) . ltrim($firstImage, '/');
						$doc->setMetaData('og:image', $firstImage, $property);
						$doc->setMetaData('twitter:image', $firstImage, $property);
					}
				}
			}
			if($this->params->get('ogtitle_detection', 1) && isset($article->title) && !$doc->getMetaData('og:title')) {
				$doc->setMetaData('og:title', $article->title, $property);
				$doc->setMetaData('twitter:title', $article->title, $property);
			}
			if($this->params->get('ogdescription_detection', 1) && !$doc->getMetaData('og:description')) {
				if(!isset($article->metadesc)) {
					$article->metadesc = null;
				}
				if(!trim($article->metadesc)) {
					$dots = JString::strlen($article->text) > 300 ? '...' : '';
					$description = JString::substr(strip_tags($article->text), 0, 300);
					$description = str_replace(PHP_EOL, '', $description);
					$description = str_replace('{fastsocialshare}', '', $description);
					$description .= $dots;
				} else {
					$description = trim($article->metadesc);
				}
				$doc->setMetaData('og:description', $description, $property);
				$doc->setMetaData('twitter:description', $description, $property);
			}
			
			// Additional Twitter cards tags
			if($this->params->get('twitter_card_enable', 0)) {
				$doc->setMetaData('twitter:card', 'summary');
				$twitterCardSite = trim($this->params->get('twitter_card_site', ''));
				if($twitterCardSite) {
					$doc->setMetaData('twitter:site', $twitterCardSite);
				}
				$twitterCardCreator = trim($this->params->get('twitter_card_creator', ''));
				if($twitterCardCreator) {
					$doc->setMetaData('twitter:creator', $twitterCardCreator);
				}
			}
		}
			
		if (!$isValidContext || !isset($this->params)) {
			$article->text = str_replace('{fastsocialshare}', '', $article->text);
			return;
		}
	
		$custom = $this->params->get('custom', 0);
		if ($custom) {
			$foundReplace = strstr($article->text, '{fastsocialshare}');
		}
	
		/** Check for selected views, which will display the buttons. **/
		/** If there is a specific set and do not match, return an empty string.**/
		$showInArticles = $this->params->get('showInArticles', 1);
		$showInFrontpage = $this->params->get('showInFrontPage', 1);
	
		if (!$showInArticles && ($this->componentView == 'article')) {
			return "";
		}
		
		if (!$showInFrontpage && ($this->componentView == 'featured')) {
			return "";
		}
	
		// Check for category view
		$showInCategories = $this->params->get('showInCategories');
	
		if (!$showInCategories && ($this->componentView == 'category')) {
			return;
		}
	
		if (!isset($article) OR empty($article->id)) {
			return;
		}
	
		$excludeArticles = $this->params->get('excludeArticles', array());
		if (!empty($excludeArticles)) {
			$excludeArticles = explode(',', $excludeArticles);
			JArrayHelper::toInteger($excludeArticles);
		}
	
		// Exluded categories
		$excludedCats = $this->params->get('excludeCats', array());
		if (!empty($excludedCats)) {
			$excludedCats = explode(',', $excludedCats);
			JArrayHelper::toInteger($excludedCats);
		}
	
		// Included Articles
		$includedArticles = $this->params->get('includeArticles', array());
		if (!empty($includedArticles)) {
			$includedArticles = explode(',', $includedArticles);
			JArrayHelper::toInteger($includedArticles);
		}
	
		if (!in_array($article->id, $includedArticles)) {
			// Check exluded places
			if (in_array($article->id, $excludeArticles) || in_array($article->catid, $excludedCats)) {
				return "";
			}
		}
	
		// Get plugin contents
		$content = $this->getContent($article, $params);
	
		if ($custom) {
			if ($foundReplace) {
				$article->text = str_replace('{fastsocialshare}', $content, $article->text);
			}
		} else {
			$position = $this->params->get('position');
	
			switch ($position) {
				case 0:
					$article->text = $content . $article->text . $content;
					break;
				case 1:
					$article->text = $content . $article->text;
					break;
				case 2:
					$article->text = $article->text . $content;
					break;
				default:
					break;
			}
		}
		return;
	}
	
	/**
	 * Override registers Listeners to the Dispatcher
	 * It allows to stop a plugin execution based on the registered listeners
	 *
	 * @override
	 * @return  void
	 */
	public function registerListeners() {
		// Ensure compatibility excluding Joomla 4
		if(version_compare(JVERSION, '4', '>=')) {
			// Check for Joomla compatibility
			JFactory::getApplication()->enqueueMessage ("The plugin package that is installed doesn't match your actual Joomla version and is not fully compatible. The package for Joomla 3.x is currently installed but you are running Joomla 4.x, if you have just upgraded your Joomla website from the version 3.x to the version 4.x the plugin must also be upgraded accordingly.<br/>To upgrade the plugin, visit our store at <a target='_blank' href='https://storejextensions.org'>https://storejextensions.org</a>, download the package for Joomla 4.x  and install it over the current one", 'error');
			return;
		} elseif (method_exists(get_parent_class($this), 'registerListeners')) {
			parent::registerListeners();
		}
	}
	
	/**
	 * Class Constructor
	 *
	 * @param object $subject The object to observe
	 * @param array  $config  An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'group', 'params', 'language'
	 * (this list is not meant to be comprehensive).
	 * @since 1.5
	 */
	public function __construct(&$subject, $config = array()) {
		// Ensure compatibility excluding Joomla 4
		if(version_compare(JVERSION, '4', '>=')) {
			return false;
		}
		
		parent::__construct($subject, $config);
		$lang = JFactory::getLanguage();
		$locale = $lang->getTag();
		$this->langTag = str_replace("-", "_", $locale);
		$this->langStartTag = @array_shift(explode('-', $locale));
	}
}