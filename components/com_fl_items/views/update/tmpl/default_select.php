<h3>Select an item to update. </h3>

<?

foreach($this->getEditItems as $item) {
	$url = JRoute::_('index.php?option=com_fl_items&view=update&id='.$item['item_id']);

	echo "<hr><a href='$url'>$item[name]</a>";
}