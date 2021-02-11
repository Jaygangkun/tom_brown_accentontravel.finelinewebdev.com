<?
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework');

$showGrid = $params->get( 'showGrid', '0');

if($showGrid) {
	$showImageCaption = false;

	$document = JFactory::getDocument();
	$document->addStyleSheet('/components/com_flic/assets/flic-grid.css'); 
	
	$columns = $params->get( 'gridColumns', '3');
	if($columns == 3) {
		$sizes = "col-sm-6 col-md-4 col-lg-4";
	} else if($columns == 4) {
		$sizes = "col-sm-6 col-md-4 col-lg-3";
	} else if($columns == 2) {
		$sizes = "col-sm-6";
	} else if($columns == 6) {
		$sizes = "col-sm-2";
	}
	
	
	print "<div class='flic-lightbox' style='display: none;'>";
		print "<div class='flic-lightbox-close'></div>";
		if(count($getAllFLICImage) == 0) {
			echo '<img src="/templates/fluid/images/layout/nophoto.png" />';
		} else if(count($getAllFLICImage) > 0) {
			// If we need a slider
			$doc = JFactory::getDocument();
			$doc->addStyleSheet('/components/com_flic/assets/slick.css'); 
			$doc->addScript('/components/com_flic/assets/slick.min.js', 'text/javascript');
			print '<div id="carousel" class="grid-slider">';
	
				foreach ($getAllFLICImage as $image)
				{
					echo '<div class="slider-item">';
						echo '<div class="slider-image" style="background-image:URL(' . $galleryFolderPath . "/original/" . $image->filename . ')"></div>';
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
		print "<div class='gallery-details-image col-sm-12'>";
			if(count($getAllFLICImage) == 0) {
				echo '<img src="/templates/fluid/images/layout/nophoto.png" />';
			} else if(count($getAllFLICImage) > 0) {
				// If we need a slider
				print '<div class="grid-gallery">';
					$c = 0;
					foreach ($getAllFLICImage as $image)
					{
						$altTag = $image->altTag;
						if(empty($altTag)) {
							$altTag = $image->captionTitle;
						}
						if(empty($altTag)) {
							$altTag = "";
						}
						echo '<div class="grid-item '.$sizes.'" style="padding: 5px;" data-index="'.$c.'">';
							echo '<img  alt="' . $altTag . '" src="' . $galleryFolderPath . "/original/" . $image->filename. '" />';
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
			var containerWidth = jQuery(".grid-gallery").outerWidth() + 30;
			var firstWidth = jQuery(".grid-gallery .grid-item").first().outerWidth();
			console.log(containerWidth + " - " + firstWidth);
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
} else {
	// SLIDER GALLERY //
	$messagePositions[1] = "top-left";
	$messagePositions[2] = "top-center";
	$messagePositions[3] = "top-right";
	$messagePositions[4] = "bottom-left";
	$messagePositions[5] = "bottom-center";
	$messagePositions[6] = "bottom-right";
	$messagePositions[7] = "middle-left d-md-flex justify-content-start align-items-center";
	$messagePositions[8] = "middle-center d-md-flex justify-content-center align-items-center";
	$messagePositions[9] = "middle-right d-md-flex justify-content-end align-items-center";
	?>
	<div id="flic-carousel" class="carousel">
	    <?
	    $doc = JFactory::getDocument();
	    $doc->addStyleSheet('/components/com_flic/assets/slick.css');
	    $doc->addScript('/components/com_flic/assets/slick.min.js', 'text/javascript');
	    $galleryFolderPath = "/images/flic/galleries/";
	    print '<div id="carousel-' . $module->id . '" class="slider">';
		
	    $c = 0;
	    foreach($getAllFLICImage AS $image)
	    {
	        $c++;
	        $altTag = $image->altTag;
	        if(empty($altTag)) {
	            $altTag = $image->captionTitle;
	        }
	        if(empty($altTag)) {
	            $altTag = $galleryName . " - " . $c;
	        }
	        echo '<div class="slider-item">';
	        if($image->url) {
	            if($image->newWindow) {
	                echo '<a target="_BLANK" href="' . $image->url . '">';
	            } else {
	                echo '<a href="' . $image->url . '">';
	            }
	        }
	        
	        if($params->get( 'backgroundImages', '0')) {
	        	echo '<div class="slider-bg-image" style="background-image: url(' . $galleryFolderPath . $image->flic_id . "/original/" . $image->filename. ')"></div>';
			} else {
				echo '<img alt="' . $altTag . '" src="' . $galleryFolderPath . "" . $image->flic_id . "/original/" . $image->filename. '" />';
			}
			
	        if( strlen($image->captionTitle) || strlen($image->captionMessage) ) {
	            echo '<div class="carousel-caption '.$messagePositions[$image->messagePosition].'">';
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
	        if($image->url) {
	            echo '</a>';
	        }
	
	        echo '</div>';
	    }
	    print '</div>';
	
	    $sliderOptions = 'slide: ".slider-item"';
	    // DOTS
	    if($params->get( 'navigationIndicators', '0')) {
	        $sliderOptions .= ', dots: true';
	    } else {
	        $sliderOptions .= ', dots: false';
	    }
	    // ARROWS
	    if($params->get( 'navigationNextPrev', '0')) {
	        $sliderOptions .= ', arrows: true';
	    } else {
	        $sliderOptions .= ', arrows: false';
	        $sliderOptions .= ', draggable: false';
	    }
	    // AUTO PLAY
	    if($params->get( 'autoPlay', '0')) {
	        $sliderOptions .= ', autoplay: true';
	        $sliderOptions .= ', autoplaySpeed: ' . $params->get( 'autoplaySpeed', '5000');
	    } else {
	        $sliderOptions .= ', autoplay: false';
	    }
	    // Slides to show
	    if($params->get( 'slidesToShowLG', '1')) {
	        $sliderOptions .= ', slidesToShow: ' . $params->get( 'slidesToShowLG', '1');
			
			if($params->get( 'slidesToShowMD', '0') || $params->get( 'slidesToShowSM', '0') || $params->get( 'slidesToShowXS', '0')) {
				$responsiveSet = array();
				if($params->get( 'slidesToShowMD', '0')) {
					$responsiveSet[] = "
					{
			      		breakpoint: 1199,
				      	settings: {
				        	slidesToShow: " . $params->get( 'slidesToShowMD', '0') . "
				      	}
				    }";
				}
				if($params->get( 'slidesToShowSM', '0')) {
					$responsiveSet[] = "
					{
			      		breakpoint: 991,
				      	settings: {
				        	slidesToShow: " . $params->get( 'slidesToShowSM', '0') . "
				      	}
				    }";
				}
				if($params->get( 'slidesToShowXS', '0')) {
					$responsiveSet[] = "
					{
			      		breakpoint: 767,
				      	settings: {
				        	slidesToShow: " . $params->get( 'slidesToShowXS', '0') . "
				      	}
				    }";
				}
				$responsive = "responsive: [" . implode(",", $responsiveSet) . "]";
				$sliderOptions .= ', ' . $responsive;
			}
	    }
	    // Slides to scroll
	    if($params->get( 'slidesToScroll', '1')) {
	        $sliderOptions .= ', slidesToScroll: ' . $params->get( 'slidesToScroll', '1');
	    }
	    // Fade
	    if($params->get( 'fade', '0')) {
	        $sliderOptions .= ', fade: true';
	    }
	    // Center Mode
	    if($params->get( 'centerMode', '0')) {
	        $sliderOptions .= ', centerMode: true';
	    }
	
	    print '
	        <script>
	            jQuery("#carousel-'.$module->id.'").slick({
	                adaptiveHeight: true,
	                ' . $sliderOptions . '
	            });
		        jQuery(document).ready(function(){
					jQuery("#carousel-'.$module->id.' img").first().one("load", function() {
						jQuery("#carousel-'.$module->id.'.slick-initialized").slick("slickSetOption", "autoplay", false, true);
					});
				});
	        </script>
	    ';
	    ?>
	
	</div>
<?php } ?>


