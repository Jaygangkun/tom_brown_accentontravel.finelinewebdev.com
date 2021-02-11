<?php // no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework');

$showImageCaption = false;

$document = JFactory::getDocument();

print '<div class="gallery-detail">';
	print "<div class='gallery-details-image col-sm-6'>";
		if(count($this->getAllImages) == 0) {
			echo '<img src="/templates/fluid/images/layout/nophoto.png" />';
		} else if(count($this->getAllImages) > 0) {
			// If we need a slider
			$doc = JFactory::getDocument();
			$doc->addStyleSheet('/components/com_flic/assets/slick.css'); 
			$doc->addScript($this->baseurl . '/components/com_flic/assets/slick.min.js', 'text/javascript');
			print '<div id="carousel" class="slider">';
	
				foreach ($this->getAllImages as $image)
				{
					$altTag = $image->altTag;
					if(empty($altTag)) {
						$altTag = $image->captionTitle;
					}
					if(empty($altTag)) {
						$altTag = $this->getOneGallery['name'];
					}
					echo '<div class="slider-item">';
						echo '<img alt="' . $altTag . '" src="' . $this->galleryFolderPath. $image->filename. '" />';
						if( (strlen($image->captionTitle) || strlen($image->captionMessage)) && $showImageCaption ) {
							echo '<div class="carousel-caption '.$image->messagePosition.'">';
								echo '<div class="carousel-caption-background">';
									if( strlen($image->captionTitle) ) 
									{
										echo '<h3 class="carousel-caption-title">'.$image->captionTitle.'</h3>';
									}
									if( strlen($image->captionMessage) ) 
									{
										echo '<div class="carousel-caption-message">'.$image->captionMessage.'</div>';
									}
								echo '</div>';
							echo '</div>';
						}
					echo '</div>'."\n";
				}
			print '</div>';
			print '
				<script>
				jQuery(document).ready(function(){
				  	jQuery(".slider").slick({
				  		dots: false 
				  	});
				});
				
				</script>
			';
		}
	print "</div>";
	?>
	
	<?php
	
	print '<div class="gallery-content col-sm-6" itemprop="text">';
		echo '<h2 class="gallery-title">'.$this->getOneGallery['name'].'</h2>';
		print "<div class='gallery-details-lead-in clearfix'>";
			echo '<meta name="keywords" content="'.$this->getOneGallery['metaKeywords'].'">';
			echo '<meta name="description" content="'.$this->getOneGallery['metaDescription'].'">';
		
			echo '<div class="gallery-description">';
				if(strlen($this->getOneGallery['description']) ) {
					echo '<div class="description">'.$this->getOneGallery['description'].'</div>';
				}
			echo '</div>';
		print "</div>";
	print "</div>";
print "</div>";


// $params->get( 'navigationIndicators', '0')
?>
