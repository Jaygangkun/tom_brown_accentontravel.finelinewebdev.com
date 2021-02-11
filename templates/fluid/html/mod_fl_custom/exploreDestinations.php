<?php
$db = JFactory::getDBO();

$maxLevels = 2;
$currentLevel = 1;
$currentParentIds = array(1);

$destinations = array();

while($currentLevel <= $maxLevels) {
	$query = "SELECT destination_id, parent_id, destination_name FROM #__fl_travel_destinations WHERE parent_id IN (".implode(",",$currentParentIds).")";
	$db->setQuery($query);
	$getDestinations = $db->loadObjectList();
	$newParentIds = array();
	
	foreach($getDestinations as $d) {
		$d->level = $currentLevel;
		$destinations[$d->parent_id][] = $d;
		$newParentIds[] = $d->destination_id;
	}
	
	$currentParentIds = $newParentIds;
	$currentLevel++;
}

?>

<div class="sub-heading toggle-destinations">Where do you want to go? <span class="glyphicons glyphicons-chevron-down"></span></div>
<div class="destination-list">
	<?php
		foreach($destinations[1] as $d) {
			echo "<strong>$d->destination_name</strong> <br>";
			if($destinations[$d->destination_id]) {
				foreach($destinations[$d->destination_id] as $sd) {
					echo " - $sd->destination_name<br>";
				}
			}
		}
	?>
</div>
<script>
	jQuery(".toggle-destinations").click(function() {
		jQuery(".destination-list").toggle();
	});
</script>
