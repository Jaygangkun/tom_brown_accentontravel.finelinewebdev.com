<?php // no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.framework');

$config = JFactory::getConfig();

$recordsPerPage = 9;
$recordsPerRow = 3;
$spanSize = floor(12 / $recordsPerRow);
?>

<?php

if($this->showBackLink) {
	$backUrl = JRoute::_('index.php?category[]='. $this->parentCategoryList);
	print '<a href="'.$backUrl.'">Back</a>';
}

if(count($this->getSubCategories)) {
	print '<div class="flic-subcategories">';
	print '<div class="flic-subcategories-title">Categories</h3>';
	
	foreach($this->getSubCategories AS $subcat) {
		$thisUrl = JRoute::_('index.php?category[]='.$this->currentCategoryList.'&category[]='.$subcat->alias);
		print '<ul class="flic-subcategory">';
			print '<li><a href="'.$thisUrl.'">' . $subcat->name . '</a></li>';
		print '</ul>';
	}
	
	print '</div>';
}

?>

<div class="gallery-result-wrapper fluid-grid clearfix">
<?php 
$count = 0;
foreach($this->getAllGallery AS $getAllGalleryRow) {
	
	$thisGalleryDetailURL = JRoute::_('index.php?view=detail&gallery='.$getAllGalleryRow->alias);
	$featuredImagePath = '/images/flic/galleries/'. $getAllGalleryRow->flic_id . '/original/';
			
	if (count($this->getAllImages[$getAllGalleryRow->flic_id])) { // If gallery has pictures
		$thisMainImage = '/images/flic/galleries/'. $getAllGalleryRow->flic_id . '/'.$this->getAllImages[$getAllGalleryRow->flic_id][0]->filename;
	} else { // ELSE use filler picture
		/////////////////////// NEED NO IMAGE IMAGE //////////////////
		$thisMainImage = '/templates/fluid/images/layout/NoLogo.png';
	}
	
	if($count % $recordsPerRow == 0) {
		echo '<div class="row">';
	}
	echo '<div class="gallery-result col-md-'.$spanSize.' fluid-grid-cell">';
		echo '<div class="gallery-result-image">';
			echo '<a href="'.$thisGalleryDetailURL.'"><img src="'.$thisMainImage.'" alt="'.$thisTitle.'" /></a>';
		echo "</div>";
		
		
		echo '<div class="gallery-list-info">';
			echo '<a title="'. $config->get( 'sitename' ) . ': '.$getAllGalleryRow->client . " " .$getAllGalleryRow->name.'" href="'.$thisGalleryDetailURL.'" class="gallery-result-title">';
				echo '<div class="gallery-result-name">'.$getAllGalleryRow->name.'</div>';
			echo '</a>';
		echo "</div>";
	echo '</div>';
	
	$count++;
	if($count % $recordsPerRow == 0) {
		echo '</div>';
	}
}
if($count % $recordsPerRow != 0) {
	echo '</div>';
}

if($count == 0) {
	echo '<p>No galleries found.</p>';
}

?>
</div>

