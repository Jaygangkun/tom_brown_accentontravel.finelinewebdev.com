<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$showImageCaption = false;

$padding = $this->padding;

$document = JFactory::getDocument();
$document->addStyleSheet('/components/com_flic/assets/flic-grid.css'); 

if($this->columns == 3) {
	$sizes = "col-sm-6 col-md-4 col-lg-4";
} else if($this->columns == 4) {
	$sizes = "col-sm-6 col-md-4 col-lg-3";
} else if($this->columns == 2) {
	$sizes = "col-sm-6";
} else if($this->columns == 6) {
	$sizes = "col-sm-2";
}
$app = JFactory::getApplication();
$menuitem = $app->getMenu()->getActive();

echo "<h2>" . $menuitem->title . "</h2>";

print "<div class='flic-lightbox' style='display: none;'>";
	print "<div class='flic-lightbox-close'></div>";
	if(count($this->getAllImages) == 0) {
		echo '<img src="/templates/fluid/images/layout/nophoto.png" />';
	} else if(count($this->getAllImages) > 0) {
		// If we need a slider
		$doc = JFactory::getDocument();
		$doc->addStyleSheet('/components/com_flic/assets/slick.css'); 
		$doc->addScript($this->baseurl . '/components/com_flic/assets/slick.min.js', 'text/javascript', 'async');
		print '<div id="carousel" class="grid-slider">';

			foreach ($this->getAllImages as $image)
			{
				echo '<div class="slider-item">';
					echo '<div class="slider-image" style="background-image:URL(' . $this->galleryFolderPath. $image->filename. ')"></div>';
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
	}
print "</div>";

print '<div class="gallery-detail">';
	print "<div class='gallery-details-image'>";
		if(count($this->getAllImages) == 0) {
			echo '<img src="/templates/fluid/images/layout/nophoto.png" />';
		} else if(count($this->getAllImages) > 0) {
			// If we need a slider
			print '<div class="grid-gallery" style="margin-right: -'.$padding.'px; margin-left: -'.$padding.'px;">';
				$c = 0;
				foreach ($this->getAllImages as $image)
				{
					$altTag = $image->altTag;
					if(empty($altTag)) {
						$altTag = $image->captionTitle;
					}
					if(empty($altTag)) {
						$altTag = $this->getOneGallery['name'];
					}
					echo '<div class="grid-item '.$sizes.'" data-index="'.$c.'" style="padding: '.$padding.'px;">';
						echo '<img  alt="' . $altTag . '" src="' . $this->galleryFolderPath. $image->filename. '" />';
						if( false && (strlen($image->captionTitle) || strlen($image->captionMessage)) && $showImageCaption ) {
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
					$c++;
				}
			print '</div>';
		}
	print "</div>";
print "</div>";

?>

<script>
	jQuery(window).load(function() {
		jQuery(".grid-slider").slick({
	  		dots: false 
	  	});
		buildGrid();
		jQuery(".grid-gallery .grid-item").click(function() {
			var index = jQuery(this).attr("data-index");
			jQuery(".grid-slider").slick("slickGoTo", index);
			jQuery(".flic-lightbox").show();
		});
		jQuery(".flic-lightbox-close").click(function() {
			jQuery(".flic-lightbox").hide();
		});
	});
	jQuery(window).resize(function() {
		buildGrid();
	});
	
	function buildGrid() {
		jQuery(".grid-gallery .grid-item").css("position", "absolute");
		var containerWidth = jQuery(".grid-gallery").outerWidth() + <?php echo $padding;?>;
		var firstWidth = jQuery(".grid-gallery .grid-item").first().outerWidth();
		var cols = Math.floor((containerWidth + 5) / firstWidth);
		// Build our column heights
		var heights = [];
		for(var i = 0 ; i < cols ; i++ ){
			heights[i] = 0;
		}
		
		var colWidth = containerWidth / cols;
		jQuery(".grid-gallery .grid-item").each(function() {
			var thisHeight = jQuery(this).outerHeight();
			
			var minHeight = 99999;
			var minId = 0;
			for(var i = 0 ; i < cols ; i++ ){
				if(heights[i] < minHeight) {
					minHeight = heights[i];
					minId = i;
				}
			}
			
			// jQuery(this).css("width", colWidth);
			jQuery(this).css("top", heights[minId]);
			jQuery(this).css("left", minId * colWidth);
			
			heights[minId] += thisHeight;
		});
		
		var maxHeight = 0;
		for(var i = 0 ; i < cols ; i++ ){
			if(heights[i] > maxHeight) {
				maxHeight = heights[i];
			}
		}
		jQuery(".grid-gallery").height(maxHeight);
	}
</script>
	
	<?php
// 	
	// print '<div class="gallery-content col-sm-6" itemprop="text">';
		// echo '<h2 class="gallery-title">'.$this->getOneGallery['name'].'</h2>';
		// print "<div class='gallery-details-lead-in clearfix'>";
			// echo '<meta name="keywords" content="'.$this->getOneGallery['metaKeywords'].'">';
			// echo '<meta name="description" content="'.$this->getOneGallery['metaDescription'].'">';
// 		
			// echo '<div class="gallery-description">';
				// if(strlen($this->getOneGallery['description']) ) {
					// echo '<div class="description">'.$this->getOneGallery['description'].'</div>';
				// }
			// echo '</div>';
		// print "</div>";
	// print "</div>";
// print "</div>";


// $params->get( 'navigationIndicators', '0')
?>
