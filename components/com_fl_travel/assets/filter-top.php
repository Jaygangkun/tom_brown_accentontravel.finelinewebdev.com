<?php

$db =& JFactory::getDBO();
$jinput = JFactory::getApplication()->input;

$query = "SELECT * FROM #__fl_travel_searchable_destinations WHERE show_in_search = 1 ORDER BY searchable_destination_name";
$db->setQuery($query);
$getAllDestinations = $db->loadObjectList();

$query = "
	SELECT * 
	FROM `gxqbn_fl_travel_suppliers` suppliers 
	WHERE
		supplier_type = 'cruise' 
		AND (SELECT COUNT(*) FROM gxqbn_fl_travel_ships ships WHERE ships.supplier_id = suppliers.supplier_id) > 0
	ORDER BY supplier_name";
$db->setQuery($query);
$getAllSuppliers = $db->loadObjectList();
?>
<form>
	<div class='search-bar mb-3'>
		<div class='row'>
			<div class='col-md-3'>
				<div class="form-group">
					<?php
					if(count($getAllDestinations)) {
						echo "<select name='searchable_destination_id' class='form-control'>";
							echo "<option value=''>- Destination -</option>";
							$currentVal = $jinput->getInt("searchable_destination_id", 0);
							foreach($getAllDestinations as $sd) {
								echo "<option value='$sd->searchable_destination_id'".($sd->searchable_destination_id == $currentVal ? "selected='selected'" : "").">$sd->searchable_destination_name</option>";
							}
						echo "</select>";
					}
					?>
				</div>
			</div>
			<div class='col-md-3'>
				<div class="form-group">
					<?php
					if(count($getAllDestinations)) {
						echo "<select name='supplier_id' class='form-control'>";
							echo "<option value=''>- Cruise Line -</option>";
							$currentVal = $jinput->getInt("supplier_id", 0);
							foreach($getAllSuppliers as $s) {
								echo "<option value='$s->supplier_id'".($s->supplier_id == $currentVal ? "selected='selected'" : "").">$s->supplier_name</option>";
							}
						echo "</select>";
					}
					?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<input class="form-control datepicker depart-day" id="depart_day" name="depart_day" placeholder="Departure Date" value="<?php echo ($_REQUEST['depart_day'] ? $_REQUEST['depart_day'] : "");?>">
				</div>
			</div>
			<div class='col-md-3'>
				<input type="submit" class="btn btn-primary" value="Search">
			</div>
		</div>
	</div>
</form>


<script>
	jQuery(".datepicker").datepicker(
    {
        minDate: 0,
    });
</script>