<?

defined('_JEXEC') or die;

class TemplateBuilderImages
{
	public function __construct($data) {
	}
	
	public static function getFirstImage($images, $categoryId, $itemImage = '', $itemId = 0, $show = '') {
		$img = $images[0];
		if(empty($img)) {
			$filename = $itemImage;
		} else {
			$filename = $img->filename;
			$itemId = $img->item_id;
		}
		if(empty($filename)) {
			$checkNoimage = glob(JPATH_BASE."/images/fl_items/".$categoryId."-noimage.*");
			$noimageImage = "noimage.png";
			if(count($checkNoimage)) {
				$noimagePath = $checkNoimage[0];
				$noimageImage = basename($noimagePath);
			}
			if($show == "escape") {
				return json_encode('<img src="/images/fl_items/'.$noimageImage.'" />');
			} else if($show == "path") {
				return "/images/fl_items/$noimageImage";
			}
			return '<img src="/images/fl_items/'.$noimageImage.'" />';
		} else {
			if($show == "escape") {
				return json_encode('<img src="/images/fl_items/'.$itemId."/original/". $filename .'" />');
			} else if($show == "path") {
				return '/images/fl_items/'.$itemId."/original/". $filename;
			}
			return '<img src="/images/fl_items/'.$itemId."/original/". $filename .'" />';
		}
		return "asdf";
	}
	
	public static function getGallery($images) {
		$getFirst = array_values($images);
		$itemId = $getFirst[0]->item_id;
		// Build the gallery
		$galleryOutput = "";
		$galleryOutput .= "<div id='slider-".$itemId."' class='flexslider'>";
			foreach ($images AS $image) 
			{
                $galleryOutput .= "<div class='slider-item'>";
				    $galleryOutput .= '<img src="/images/fl_items/'.$itemId."/original/".$image->filename .'" />';	
                $galleryOutput .= "</div>";
			}
		$galleryOutput .= "</div>";
		$galleryOutput .= "
		<script>
			jQuery(document).ready(function(){
				jQuery('#slider-".$itemId."').slick({autoplay: false, autoplaySpeed: 7000, adaptiveHeight: true}); 
			});
		</script>
		";
		
		return $galleryOutput;
	}
	
	public static function getGalleryThumbs($images, $thumbs) {
		$getFirst = array_values($images);
		$itemId = $getFirst[0]->item_id;
		if(!is_numeric($thumbs)) {
			$thumbs = 3;
		}
		// Build the gallery
		$galleryOutput = "";
		$thumbsOutput = "";
		$galleryOutput .= "<div id='slider-".$itemId."' class='slick-slider'>";
		$thumbsOutput .= "<div id='thumbs-".$itemId."' class='slick-thumbs".(count($images) > $thumbs ? " center-mode" : "")."'>";
			foreach ($images AS $image) 
			{
                $galleryOutput .= "<div class='slider-item'>";
				    $galleryOutput .= '<img src="/images/fl_items/'.$itemId."/original/".$image->filename .'" />';	
                $galleryOutput .= "</div>";
                $thumbsOutput .= "<div class='thumb-item'>";
				    $thumbsOutput .= '<img src="/images/fl_items/'.$itemId."/original/".$image->filename .'" />';	
                $thumbsOutput .= "</div>";
			}
		$galleryOutput .= "</div>";
		$thumbsOutput .= "</div>";
		$galleryOutput .= $thumbsOutput;
		$galleryOutput .= "
		<script>
			jQuery(document).ready(function(){
				jQuery('#slider-".$itemId."').slick(" . '{
					autoplay: false, 
					adaptiveHeight: true, 
					asNavFor: ".slick-thumbs" 
				}' . ");
				jQuery('#thumbs-".$itemId."').slick(" . '{
					autoplay: false, 
					adaptiveHeight: true, 
					asNavFor: ".slick-slider", 
					slidesToShow: '.$thumbs.', 
					focusOnSelect: true,
					'. (count($images) > $thumbs ? "centerMode: true, " : "") . '
					arrows: false,
					swipeToSlide: true
				}' . ");
			});
		</script>
		";
		return $galleryOutput;	
	}
	
	public static function getGridGallery($images, $cols) {
		$getFirst = array_values($images);
		$itemId = $getFirst[0]->item_id;
		if(!is_numeric($cols)) {
			$cols = 3;
		}
		switch($cols) {
			case 4:
				$colClass = "col-md-6 col-lg-4 col-xl-3";
				break;
			case 3:
				$colClass = "col-md-6 col-lg-4";
				break;
			default:
				$colClass = "col-md-6";
				break;
		}
		
		$document = JFactory::getDocument();  
		$document->addStyleSheet('/components/com_fl_items/assets/fl-item-grid.css');
		
		// Build the Grid
		if(count($images) > 0) {
			$galleryOutput .= '<div class="fl-item-grid-gallery row">';
			$c = 0;
			foreach ($images as $image) {
				$altTag = $item['item']['name'] . " - " . ($c+1);
				$galleryOutput .= '<div class="grid-item '.$colClass.'" data-index="'.$c.'">';
					$galleryOutput .= '<img alt="'.$altTag.'" src="/images/fl_items/'.$image->item_id.'/original/'.$image->filename.'" />';
					$galleryOutput .= "<div class='grid-caption'>$image->caption</div>";
				$galleryOutput .= '</div>';
				$c++;
			}
			$galleryOutput .= '</div>';
		}
		
		// Javascript
		$galleryOutput .= "
		<script>
			jQuery(window).load(function() {
				buildGrid();
			});
			jQuery(window).resize(function() {
				buildGrid();
			});
			
			function buildGrid() {
				var containerWidth = jQuery('.fl-item-grid-gallery').outerWidth();
				var firstWidth = jQuery('.fl-item-grid-gallery .grid-item').first().outerWidth();
				var cols = Math.floor((containerWidth + 5) / firstWidth);
				// Build our column heights
				var heights = [];
				for(var i = 0 ; i < cols ; i++ ){
					heights[i] = 0;
				}
				
				var colWidth = containerWidth / cols;
				
				jQuery('.fl-item-grid-gallery .grid-item').each(function() {
					jQuery(this).css('top', jQuery(this).offset().top - jQuery('.fl-item-grid-gallery').offset().top);
					jQuery(this).css('left', jQuery(this).offset().left - jQuery('.fl-item-grid-gallery').offset().left);
				});
				jQuery('.fl-item-grid-gallery .grid-item').css('position', 'absolute');
					
				jQuery('.fl-item-grid-gallery .grid-item').each(function() {
					var thisHeight = jQuery(this).outerHeight();
					
					var minHeight = 99999;
					var minId = 0;
					for(var i = 0 ; i < cols ; i++ ){
						if(heights[i] < minHeight) {
							minHeight = heights[i];
							minId = i;
						}
					}
					
					// jQuery(this).css('width', colWidth);
					jQuery(this).css('top', heights[minId]);
					jQuery(this).css('left', minId * colWidth);
					
					heights[minId] += thisHeight;
				});
				
				var maxHeight = 0;
				for(var i = 0 ; i < cols ; i++ ){
					if(heights[i] > maxHeight) {
						maxHeight = heights[i];
					}
				}
				jQuery('.fl-item-grid-gallery').height(maxHeight);
			}
		</script>
		";
		return $galleryOutput;
	}
	
	public static function getGridGalleryLightbox($images, $cols) {
		$getFirst = array_values($images);
		$itemId = $getFirst[0]->item_id;
		if(!is_numeric($cols)) {
			$cols = 3;
		}
		switch($cols) {
			case 4:
				$colClass = "col-md-6 col-lg-4 col-xl-3";
				break;
			case 3:
				$colClass = "col-md-6 col-lg-4";
				break;
			default:
				$colClass = "col-md-6";
				break;
		}
		
		$document = JFactory::getDocument();  
		$document->addStyleSheet('/components/com_fl_items/assets/fl-item-grid-lightbox.css');
		
		// Build the lightbox
		$galleryOutput = "<div class='fl-item-lightbox' style='display: none;'>";
			$galleryOutput .= "<div class='fl-item-lightbox-close'></div>";
			$galleryOutput .= "<div id='lightbox-slider-".$itemId."' class='slickslider'>";
				foreach ($images AS $image) 
				{
                    $galleryOutput .= "<div class='slider-item' style='background: #000 url(\"/images/fl_items/".$image->item_id."/original/".$image->filename ."\") no-repeat center center/contain;'>";
                    	$galleryOutput .= "<div class='lightbox-caption'>$image->caption</div>";
					$galleryOutput .= "</div>";
				}
			$galleryOutput .= "</div>";
		$galleryOutput .= "</div>";
		
		// Build the Grid
		if(count($images) > 0) {
			$galleryOutput .= '<div class="fl-item-grid-gallery row">';
			$c = 0;
			foreach ($images as $image) {
				$altTag = $item['item']['name'] . " - " . ($c+1);
				$galleryOutput .= '<div class="grid-item '.$colClass.'" data-index="'.$c.'">';
					$galleryOutput .= '<img alt="'.$altTag.'" src="/images/fl_items/'.$image->item_id.'/original/'.$image->filename.'" />';
					$galleryOutput .= "<div class='grid-caption'>$image->caption</div>";
				$galleryOutput .= '</div>';
				$c++;
			}
			$galleryOutput .= '</div>';
		}
		
		// Javascript
		$galleryOutput .= "
		<script>
			jQuery(document).ready(function(){
				jQuery('#lightbox-slider-".$itemId."').slick(" . '{autoplay: false, autoplaySpeed: 7000, adaptiveHeight: true
				}' . ");

				jQuery('#slider-".$itemId." .slider-item').click(function() {
					var index = jQuery(this).attr('data-index');
					jQuery('#lightbox-slider-".$itemId."').slick('slickGoTo', index);
					jQuery('.fl-item-lightbox').show();
				});

				jQuery('.fl-item-lightbox-close').click(function() {
					jQuery('.fl-item-lightbox').hide();
				});
			});
			jQuery(window).load(function() {
				jQuery('.grid-slider').slick({
			  		dots: false,
					lazyLoad: 'progressive'
			  	});
				buildGrid();
				jQuery('.fl-item-grid-gallery .grid-item').click(function() {
					var index = jQuery(this).attr('data-index');
					jQuery('#lightbox-slider-".$itemId."').slick('slickGoTo', index);
					jQuery('.fl-item-lightbox').show();
				});
				jQuery('.featured-image').click(function() {
					var index = 0;
					jQuery('#lightbox-slider-".$itemId."').slick('slickGoTo', index);
					jQuery('.fl-item-lightbox').show();
				});
				jQuery('.fl-item-lightbox-close').click(function() {
					jQuery('.fl-item-lightbox').hide();
				});
			});
			jQuery(window).resize(function() {
				buildGrid();
			});
			
			function buildGrid() {
				var containerWidth = jQuery('.fl-item-grid-gallery').outerWidth();
				var firstWidth = jQuery('.fl-item-grid-gallery .grid-item').first().outerWidth();
				var cols = Math.floor((containerWidth + 5) / firstWidth);
				// Build our column heights
				var heights = [];
				for(var i = 0 ; i < cols ; i++ ){
					heights[i] = 0;
				}
				
				var colWidth = containerWidth / cols;
				
				jQuery('.fl-item-grid-gallery .grid-item').each(function() {
					jQuery(this).css('top', jQuery(this).offset().top - jQuery('.fl-item-grid-gallery').offset().top);
					jQuery(this).css('left', jQuery(this).offset().left - jQuery('.fl-item-grid-gallery').offset().left);
				});
				jQuery('.fl-item-grid-gallery .grid-item').css('position', 'absolute');
					
				jQuery('.fl-item-grid-gallery .grid-item').each(function() {
					var thisHeight = jQuery(this).outerHeight();
					
					var minHeight = 99999;
					var minId = 0;
					for(var i = 0 ; i < cols ; i++ ){
						if(heights[i] < minHeight) {
							minHeight = heights[i];
							minId = i;
						}
					}
					
					// jQuery(this).css('width', colWidth);
					jQuery(this).css('top', heights[minId]);
					jQuery(this).css('left', minId * colWidth);
					
					heights[minId] += thisHeight;
				});
				
				var maxHeight = 0;
				for(var i = 0 ; i < cols ; i++ ){
					if(heights[i] > maxHeight) {
						maxHeight = heights[i];
					}
				}
				jQuery('.fl-item-grid-gallery').height(maxHeight);
			}
		</script>
		";
		return $galleryOutput;
	}
}