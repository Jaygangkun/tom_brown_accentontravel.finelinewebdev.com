<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework');

$offer = $this->getOne['offer'];
$ship = $this->getOne['ship'];
$supplier = $this->getOne['supplier'];

// Get pricing
$minimumPrice = $offer->minimum_price;
if($minimumPrice == "999999999") {
	$minimumPrice = "Call For Pricing";
} else {
	$minimumPrice = "<small>From</small> $".number_format($offer->minimum_price,2);
}

$overviewRight = "
	<h3>Overview</h3>
	<div class='offer-overview'>
		<div class='overview-item'>
			<strong>Ports: </strong> $offer->itinerary_description
		</div>
		<div class='overview-item'>
			<strong>Cruise Line: </strong> $supplier->supplier_name
		</div>
		<div class='overview-item'>
			<strong>Ship: </strong> $ship->ship_name
		</div>
		<div class='overview-item'>
			<strong>Departs: </strong> ".date('m/d/Y', strtotime($offer->depart_day))."
		</div>
		<div class='overview-item'>
			<strong>Offer ID: </strong> $offer->offer_id
		</div>
		<div class='offer-map'>
			<img class='offer-map-img' src='$offer->large_map_path'>
		</div>
	</div>
";

?>

<div class="offer-detail">
	<div class="offer-header">
		<h2 class="offer-name">
			<?php echo $offer->title;?>
		</h2>
		<div class="offer-price">
			<?php echo $minimumPrice;?>
		</div>
		<?php if($offer->signature_amenities) { ?>
			<div class="offer-special-tag">
				Special Offer
			</div>
		<?php } ?>
	</div>
	<div class="row no-gutters offer-tabs mb-4">
		<div class="col tab active" data-tab="general">
			Overview
		</div>
		<div class="col tab" data-tab="itinerary">
			Itinerary
		</div>
		<div class="col tab" data-tab="pricing">
			Pricing
		</div>
		<div class="col tab" data-tab="ship">
			Ship Info
		</div>
		<div class="col tab" data-tab="reviews">
			Reviews
		</div>
	</div>
	<?php ///////// General Tab ////////////// ?>
	<div class="offer-tab tab-general active">
		<div class="row">
			<div class="col-md-8">
				<?php if($offer->signature_amenities) { ?>
					
					<div class="offer-amenities">
						<h3>Exclusive Amenities</h3>
						<?php echo $offer->signature_amenities; ?>
					</div>
				<?php } ?>
				<?php if($offer->private_collection) { ?>
					
					<div class="offer-private-collection">
						<h3>A Hosted Sailing</h3>
						Hosted Sailings offer Outstanding Value, Exclusive Amenities, and an Experienced Personable Host on board to ensure your cruise is more enjoyable.
					</div>
				<?php } ?>
				
				<?php if($offer->package_inclusions) { ?>
					
					<div class="offer-inclusions">
						<h3>Cruise Inclusions</h3>
						<?php echo $offer->package_inclusions; ?>
					</div>
				<?php } ?>
			</div>
			<div class="col-md-4">
				<?php echo $overviewRight;?>
			</div>
		</div>
	</div>
	<?php ///////// Itinerary Tab ////////////// ?>
	<div class="offer-tab tab-itinerary">
		<div class="row">
			<div class="col-md-8">
				<div class="offer-itinerary">
					<?php
					foreach($offer->itinerary_items as $ii) {
						echo "<div class='itinerary-item'>";
							echo "<div class='itinerary-date'>";
								echo "<div class='row align-items-center'>";
									echo "<div class='col-sm-3'>";
										echo "<div class='itinerary-date-wrapper'>";
											echo "<div class='day'>";
												echo date("D", strtotime($ii->itinerary_arrival_day));
											echo "</div>";
											echo "<div class='date'>";
												echo date("M j", strtotime($ii->itinerary_arrival_day));
											echo "</div>";
										echo "</div>";
									echo "</div>";
									echo "<div class='col'>";
										echo "<div class='itinerary-name'>$ii->destination_name</div>";
									echo "</div>";
									echo "<div class='col-sm-5 col-lg-4 col-xl-3'>";
										if(date("H:i", strtotime($ii->itinerary_arrival_day)) != "00:00") {
											echo "<div class='arrival-departure-time'><strong>Arrival:</strong> " . date("g:i a", strtotime($ii->itinerary_arrival_day)) . "</div>";
										}
										if(date("H:i", strtotime($ii->itinerary_depart_day)) != "00:00") {
											echo "<div class='arrival-departure-time'><strong>Departure:</strong> " . date("g:i a", strtotime($ii->itinerary_depart_day)) . "</div>";
										}
									echo "</div>";
									echo "<div class='col-auto'>";
										if(count($ii->excursions)) {
											echo "<div class='itinerary-expand'><span class='glyphicons glyphicons-plus'></span></div>";
										} else {
											echo "<div class='itinerary-expand invisible'><span class='glyphicons glyphicons-plus'></span></div>";
										}
									echo "</div>";
								echo "</div>";
							echo "</div>";
							echo "<div class='itinerary-dropdown' style='display:none;'>";
								foreach($ii->excursions as $ex) {
									echo "<div class='excursion-wrapper'>";
										echo "<div class='excursion-title'>";
											echo $ex->excursion_title;
										echo "</div>";
										echo "<div class='excursion-details' style='display: none;'>";
											echo "<div class='col text-right'>";
												echo "Offered by: $ex->specialist_name";
											echo "</div>";
											echo $ex->excursion_description;
											echo "<div class='excursion-book'>";
												echo "<div><strong>Booking Instructions:</strong></div>";
												echo $ex->booking_instructions;
											echo "</div>";
										echo "</div>";
									echo "</div>";
								}
								// print_r($ii);
							echo "</div>";
						echo "</div>";
						echo "<hr>";
						// print_r($ii);
						// exit;
					}
					?>
				</div>
			</div>
			<div class="col-md-4">
				<?php echo $overviewRight; ?>
			</div>
		</div>
	</div>
	<?php ///////// Pricing Tab ////////////// ?>
	<div class="offer-tab tab-pricing">
		Pricing
	</div>
	<?php ///////// Ship Info Tab ////////////// ?>
	<div class="offer-tab tab-ship">
		<?php
		// unset($ship->reviews);
		// unset($ship->staterooms);
		// unset($ship->overview_sections);
		// unset($ship->options);
		// unset($ship->deckplans);
		// unset($ship->cabin_categories);
		// unset($ship->activities);
		
		$shipStats = array(
			"year_built" => "Year Built",
			"year_refurbished" => "Year Refurbished",
			"year_entered_present_fleet" => "Year Entered Present Fleet",
			"country_of_registry" => "Country of Registry",
			"pax" => "Guests (Lowers)",
			"max_pax" => "Total Guests",
			"number_of_crew" => "Number of Crew",
			"officers_nationality" => "Officers' Nationality",
			"Staff Nationality" => "staff_nationality"
		);
		
		?>
		<div class="row">
			<div class="col-md-8">
				<h3><?php echo $supplier->supplier_name . ": " . $ship->ship_name;?></h3>
				<div class="ship-image">
					<img src="<?php echo $ship->ship_photo;?>">
				</div>
				<div class="ship-description">
					<?php echo $ship->ship_description;?>
				</div>
			</div>
			<div class="col-md-4">
				<h3>Ship Statistics</h3>
				<?php if($ship->statistics_image) { ?>
					<div class="ship-stats-image">
						<img src="<?php echo $ship->statistics_image;?>">
					</div>
				<?php } ?>
				<?php
				foreach($shipStats as $varName => $varCaption) {
					if($ship->{$varName}) {
						echo "<div class='row d-flex align-items-center'>";
							echo "<div class='col'>";
								echo "<strong>$varCaption:</strong>";
							echo "</div>";
							echo "<div class='col-md-5'>";
								echo $ship->{$varName};
							echo "</div>";
						echo "</div>";
					}
				} 
				?>
			</div>
		</div>
		<div class="ship-floorplans">
			<?php
			$deckplans = json_decode($ship->deckplans);
			$cabinCategories = json_decode($ship->cabin_categories);
			$allCabinCategories = array();
			
			foreach($cabinCategories as $cc) {
				$deckplanIds = $cc->associated_deckplans;
				$ccData = array(
					"id" => $cc->cabin_category_id,
					"name" => $cc->cabin_category_name,
					"category" => $cc->category_code,
					"description" => $cc->category_description,
					"images" => $cc->stateroom_images,
					"sqft" => $cc->square_footage,
					"endDate" => $cc->stateroom_end_date,
					"startDate" => $cc->stateroom_start_date,
					"color" => $cc->color_code_hex
				);
				foreach($deckplanIds as $did) {
					$allCabinCategories[$did][] = (object) $ccData;
				}
			}
			?>
			<div class="row">
				<div class="col-md-5">
					<?php
					if(ship_deckplan_overview_image) {
						echo "<div class='deckplan-overview'>";
							echo "<img src='$ship->ship_deckplan_overview_image'>";
						echo "</div>";
					}
					foreach($deckplans as $dp) {
						if($dp->deckplan_image 
							&& (is_null($dp->deckplan_end_date) || $dp->deckplan_end_date >= $offer->depart_day) 
							&& (is_null($dp->deckplan_start_date) || $dp->deckplan_start_date <= $offer->depart_day)
						) {
							echo "<h5 class='deckplan-tab' data-deckplan='$dp->deckplan_number'>$dp->deckplan_title</h5>";
						}
					}
					?>
				</div>
				<div class="col-md-7">
					<?php
					foreach($deckplans as $dp) {
						echo "<div class='deckplan-wrapper deckplan-$dp->deckplan_number' style='display: none;'>";
							echo "<div class='floorplan-description'>";
								echo $dp->deckplan_description;
							echo "</div>";
							echo "<div class='floorplan-image'>";
								echo "<div class='row'>";
									echo "<div class='col'>";
										echo "<img src='$dp->deckplan_image'>";
									echo "</div>";
									echo "<div class='col-12 col-sm'>";
										if(count($allCabinCategories[$dp->deckplan_id])) {
											foreach($allCabinCategories[$dp->deckplan_id] as $cc) {
												echo "<div class='row d-flex align-items-center'>";
													echo "<div class='col-auto'>";
														echo "<div class='cabin-key' style='background: #$cc->color;'></div>";
													echo "</div>";
													echo "<div class='col'>";
														echo "<h5 class='cabin-type'>$cc->name</h5>";
														echo "<h6 class='cabin-category'>Category $cc->category</h6>";
													echo "</div>";
													echo "<div class='col-auto'>";
														echo "<a href='#cabin-modal-$cc->id' rel='modal:open' class='open-cabin'>";
															echo "<span class='glyphicons glyphicons-plus'></span>";
														echo "</a>";
													echo "</div>";
												echo "</div>";
												
												echo "<div id='cabin-modal-$cc->id' class='cabin-info' style='display: none;'>";
													echo "<div class='row'>";
														echo "<div class='col'>";
															echo "<div class='row d-flex align-items-center'>";
																echo "<div class='col-auto'>";
																	echo "<div class='cabin-key' style='background: #$cc->color;'></div>";
																echo "</div>";
																echo "<div class='col'>";
																	echo "<h5 class='cabin-type'>$cc->name</h5>";
																	echo "<h6 class='cabin-category'>Category $cc->category</h6>";
																	if($cc->sqft) {
																		echo "<div class='cabin-sqft'><strong>Square Footage:</strong> $cc->sqft</div>";
																	}
																echo "</div>";
															echo "</div>";
														echo "</div>";
														echo "<div class='col-12 col-md-auto'>";
															if(count($cc->images)) {
																echo "<div class='cabin-image-list'>";
																	foreach($cc->images as $ccImg) {
																		echo "<div class='cabin-image pr-5'>";
																			echo "<img src='$ccImg->stateroom_image_filename'>";
																		echo "</div>";
																	}
																echo "</div>";
															}
														echo "</div>";
													echo "</div>";
													echo "<hr>";
													echo "<div class='cabin-description'>$cc->description</div>";
													echo "<hr>";
													echo "<p><a href='#' rel='modal:close'>Close</a></p>";
												echo "</div>";
												echo "<hr>";
											}
										}
										echo "<img src='$dp->deckplan_key_image'>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					}
					?>
				</div>
			</div>
		</div>
		<?php //print_r($ship);?>
	</div>
	<?php ///////// Reviews Tab ////////////// ?>
	<div class="offer-tab tab-reviews">
		<div class="review-list">
			<?php
			$reviews = json_decode($ship->reviews);
			$max = 20;
			$c = 0;
			foreach($reviews as $review) {
				$c++;
				echo "<div class='review'>";
					echo "<h4>$review->title</h4>";
					echo "";
					echo "<div class='review-date text-uppercase small mb-3'>".date("M Y", strtotime($review->date_submit))."<span class=' float-right stars stars-$review->rate_overall'></span></div>";
					echo "<div class='review-pros mb-3'><strong>Pros:</strong> $review->pros</div>";
					echo "<div class='review-cons mb-3'><strong>Cons:</strong> $review->cons</div>";
					echo "<div class='review-recommended mb-3'><strong>Recommended:</strong> ".($review->is_recommended ? "Yes" : "No")."</div>";
					echo "<div class='review-summary mb-3'>$review->review_text</div>";
				echo "</div>";
				echo "<hr>";
				if($c >= $max) {
					break;
				}
			}
			?>
		</div>
	</div>
</div>

<script>
	jQuery(".offer-tabs .tab").click(function() {
		jQuery(".offer-tabs .tab").removeClass("active");
		jQuery(this).addClass("active");
		var thisTab = jQuery(this).attr("data-tab");
		jQuery(".offer-tab").removeClass("active");
		jQuery(".tab-"+thisTab).addClass("active");
	});
	
	jQuery(".itinerary-expand").click(function() {
		jQuery(this).toggleClass("open");
		jQuery(this).parents(".itinerary-date").next().slideToggle();
	});
	
	jQuery(".excursion-title").click(function() {
		jQuery(this).next().slideToggle();
	});
	
	jQuery(".deckplan-tab").click(function() {
		jQuery(".deckplan-tab").removeClass("active");
		jQuery(this).addClass("active");
		var deckplanId = jQuery(this).attr("data-deckplan");
		jQuery(".deckplan-wrapper").hide();
		jQuery(".deckplan-"+deckplanId).show();
	});
	jQuery(".deckplan-tab").first().click();
</script>


<?
// echo "<h3>Offer</h3>";
// echo "<div class='alert alert-info'>".print_r($offer, true). "</div>";
// echo "<h3>Ship</h3>";
// echo "<div class='alert alert-info'>".print_r($ship, true). "</div>";
// echo "<h3>Supplier</h3>";
// echo "<div class='alert alert-info'>".print_r($supplier, true). "</div>";
?>

