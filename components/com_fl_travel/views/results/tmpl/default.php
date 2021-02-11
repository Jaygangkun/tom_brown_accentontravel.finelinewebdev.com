<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework');


include(JPATH_BASE . '/components/com_fl_travel/assets/filter-top.php');
?>

<div class="row search-results">
	<?
	foreach($this->getAllItem as $offer) {
		//Build URL
		$cleanTitle = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%-]/s', '', $offer->title);
		$url = JRoute::_("index.php?view=detail&id=$offer->offer_id&name=$cleanTitle");
		
		// Get Ship Data
		$ship = $this->allShips[$offer->ship_id];
		
		// Get Dates
		$startDate = date("F j, Y", strtotime($offer->depart_day));
		$endDate = date("F j,Y", strtotime($offer->depart_day. "+ $offer->length days"));
		
		// Get Pricing
		$minimumPrice = $offer->minimum_price;
		if($minimumPrice == "999999999") {
			$minimumPrice = "Call For Pricing";
		} else {
			$minimumPrice = "$".number_format($offer->minimum_price,2)." <small>Per Person</small>";
		}
		
		// Get Ports
		$stopDestinationIds = array(0,1);
		$ignoreDestinationIds = array(8528);
		$currentDeparture = $this->allDestinations[$offer->departure_port_id];
		$initialDeparture = $currentDeparture->destination_name;
		$departureDestinations = array();
		while(!in_array($currentDeparture->parent_id, $stopDestinationIds)) {
			if(!in_array($currentDeparture->destination_id, $ignoreDestinationIds)) {
				$departureDestinations[] = $currentDeparture->destination_name;
			}
			$currentDeparture = $this->allDestinations[$currentDeparture->parent_id];
		}
		
		$currentEnd = $this->allDestinations[$offer->end_port_id];
		$initialEnd = $currentEnd->destination_name;
		$endDestinations = array();
		while(!in_array($currentEnd->parent_id, $stopDestinationIds)) {
			if(!in_array($currentEnd->destination_id, $ignoreDestinationIds)) {
				$endDestinations[] = $currentEnd->destination_name;
			}
			$currentEnd = $this->allDestinations[$currentEnd->parent_id];
		}
		
		echo "<div class='col-md-4'>";
			echo "<div class='offer-wrapper mb-5'>";
				echo "<a href='$url'>";
					echo "<div class='offer-ship-image-bg mb-3' style='background-image: url(\"$ship->ship_photo\");'></div>";
					echo "<h3 class='offer-title'>$offer->title</h3>";
				echo "</a>";
				echo "<div class='offer-from-price'><small>from</small> $minimumPrice</div>";
				// echo "<div class='offer-date'><small>Departs</small> " . $startDate . " <em>through</em> " . $endDate . "</div>";
				echo "<div class='offer-date'><small>Departs</small> $startDate</div>";
				// echo "<div class='offer-nights'>Nights: $offer->length</div>";
				// echo "<div class='offer-ship-name'>Ship: $ship->ship_name</div>";
				// echo "<div class='offer-ports'>" . implode(", ", $departureDestinations) ." <small>to</small> " . implode(", ", $endDestinations) ."</div>";
				// echo "<div class='offer-ports'>" . implode(", ", $departureDestinations) ." <small>to</small> " . implode(", ", $endDestinations) ."</div>";
				if($initialDeparture == $initialEnd) {
					echo "<div class='offer-ports'><small>Roundtrip from</small> $initialDeparture <small>on the</small> $ship->ship_name</div>";
				} else {
					echo "<div class='offer-ports'>$initialDeparture <small>to</small> $initialEnd <small>on the</small> $ship->ship_name</div>";
				}
				// echo "<div class='offer-ship-description'>$ship->ship_description</div>";
				// echo "<div class='offer-action'>";
					// echo "<a href='$url' class='btn btn-primary'>Learn More</a>";
				// echo "</div>";
			echo "</div>";
		echo "</div>";
		// print_r($departures);
		// echo "<div class='alert alert-info'>".print_r($offer, true). "</div>";
		// echo "<hr>";
	}
	?>
</div>