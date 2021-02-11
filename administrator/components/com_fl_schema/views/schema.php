<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class ViewSchema
{
	function setToolbar($galleryName = "")
	{
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		JToolBarHelper::title( 'Update Schema', 'generic.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply('apply');
		JToolBarHelper::cancel( 'cancel' );
	}

	function schema( &$row, &$lists )
	{
        jimport('joomla.application.component.helper');
 
		ViewSchema::setToolbar();
		JRequest::setVar( 'hidemainmenu', 1 );
		
		JHtml::_('jquery.framework');
		JHtml::_('formbehavior.chosen', 'select.select-search');
		
		$localBusinessTypes = array("LocalBusiness","AnimalShelter","ArchiveOrganization","AutomotiveBusiness"," --- AutoBodyShop"," --- AutoDealer"," --- AutoPartsStore"," --- AutoRental"," --- AutoRepair"," --- AutoWash"," --- GasStation"," --- MotorcycleDealer"," --- MotorcycleRepair","ChildCare","Dentist","DryCleaningOrLaundry","EmergencyService"," --- FireStation"," --- Hospital"," --- PoliceStation","EmploymentAgency","EntertainmentBusiness"," --- AdultEntertainment"," --- AmusementPark"," --- ArtGallery"," --- Casino"," --- ComedyClub"," --- MovieTheater"," --- NightClub","FinancialService"," --- AccountingService"," --- AutomatedTeller"," --- BankOrCreditUnion"," --- InsuranceAgency","FoodEstablishment"," --- Bakery"," --- BarOrPub"," --- Brewery"," --- CafeOrCoffeeShop"," --- Distillery"," --- FastFoodRestaurant"," --- IceCreamShop"," --- Restaurant"," --- Winery","GovernmentOffice"," --- PostOffice","HealthAndBeautyBusiness"," --- BeautySalon"," --- DaySpa"," --- HairSalon"," --- HealthClub"," --- NailSalon"," --- TattooParlor","HomeAndConstructionBusiness"," --- Electrician"," --- GeneralContractor"," --- HVACBusiness"," --- HousePainter"," --- Locksmith"," --- MovingCompany"," --- Plumber"," --- RoofingContractor","InternetCafe","LegalService"," --- Attorney"," --- Notary","Library","LodgingBusiness"," --- BedAndBreakfast"," --- Campground"," --- Hostel"," --- Hotel"," --- Motel"," --- Resort","MedicalBusiness"," --- CommunityHealth"," --- Dentist"," --- Dermatology"," --- DietNutrition"," --- Emergency"," --- Geriatric"," --- Gynecologic"," --- MedicalClinic"," --- Midwifery"," --- Nursing"," --- Obstetric"," --- Oncologic"," --- Optician"," --- Optometric"," --- Otolaryngologic"," --- Pediatric"," --- Pharmacy"," --- Physician"," --- Physiotherapy"," --- PlasticSurgery"," --- Podiatric"," --- PrimaryCare"," --- Psychiatric"," --- PublicHealth","ProfessionalService","RadioStation","RealEstateAgent","RecyclingCenter","SelfStorage","ShoppingCenter","SportsActivityLocation"," --- BowlingAlley"," --- ExerciseGym"," --- GolfCourse"," --- HealthClub"," --- PublicSwimmingPool"," --- SkiResort"," --- SportsClub"," --- StadiumOrArena"," --- TennisComplex","Store"," --- AutoPartsStore"," --- BikeStore"," --- BookStore"," --- ClothingStore"," --- ComputerStore"," --- ConvenienceStore"," --- DepartmentStore"," --- ElectronicsStore"," --- Florist"," --- FurnitureStore"," --- GardenStore"," --- GroceryStore"," --- HardwareStore"," --- HobbyShop"," --- HomeGoodsStore"," --- JewelryStore"," --- LiquorStore"," --- MensClothingStore"," --- MobilePhoneStore"," --- MovieRentalStore"," --- MusicStore"," --- OfficeEquipmentStore"," --- OutletStore"," --- PawnShop"," --- PetStore"," --- ShoeStore"," --- SportingGoodsStore"," --- TireShop"," --- ToyStore"," --- WholesaleStore","TelevisionStation","TouristInformationCenter","TravelAgency");
		
		?>
		
		<style>
			.key label {
				white-space: nowrap;
				line-height: 24px;
				font-weight: bold;
			}
			.adminlist th {
				background: #f5f5ff !important; padding-left: 20px;
			}
			.adminlist .btn-danger {
				margin-top: -10px;
			}
			.hours small {
				color: #888;
			}
			input.error {
				color: #C00;
				border-color: #C00;
			}
			.note {
				color: #777;
				margin-left: 10px;
			}
		</style>
		
		<h2>Manage Your Website's Schema</h2>
		<p>
			Enter the values below and click "Save." If any fields do not apply, leave them blank.
		</p>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			
			<table class="adminlist table table-striped">
				<tr>
					<th colspan="2">
						<h3>Your Business</h3>
					</th>
				</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="logo">
                            Business Type
                        </label>
                    </td>
                    <td width="80%">
                    	<select name="businessType" class="select-search">
                    	<?php
                    	$selectTypes = array();
                    	foreach($localBusinessTypes as $lbt) {
                    		$val = str_replace(" --- ", "", $lbt);
							$display = preg_replace('/(?<!\ )[A-Z]/', ' $0', $lbt);
                    		echo "<option value='$val' ". ($val == $row->businessType ? "selected='selected'" : "") . ">$display</option>";
							$selectTypes[] = JHTML::_('select.option', $val, $display);
                    	}
						?>
						</select>
                    </td>
            	</tr>
				<tr>
					<th colspan="2">
						<h3>Images</h3>
					</th>
				</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="logo">
                            Logo
                        </label>
                    </td>
                    <td width="80%">
                    	<?php
                    	if($row->logo) {
                    		echo "<img src='/$row->logo' style='max-width: 100px; max-height: 100px;'><br>";
                    	}
						?>
                        <input class="inputbox" type="file" name="new-logo" id="new-logo" />
                        <span class="note">Your logo</span>
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="logo">
                            Image
                        </label>
                    </td>
                    <td width="80%">
                    	<?php
                    	if($row->image) {
                    		echo "<img src='/$row->image' style='max-width: 100px; max-height: 100px;'><br>";
                    	}
						?>
                        <input class="inputbox" type="file" name="new-image" id="new-image" />
                        <span class="note">A photograph of your business. If you do not have a brick and mortar location, upload your logo instead.</span>
                    </td>
            	</tr>
				<tr>
					<th colspan="2">
						<h3>Contact Information</h3>
					</th>
				</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="phone">
                            Phone
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="123-456-7890" name="phone" id="phone" size="50" value="<?php echo htmlspecialchars($row->phone); ?>" />
                        <span class="note">Business phone number (ex. 123-456-7890)</span>
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="fax">
                            Fax
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="123-456-7890" name="fax" id="fax" size="50" value="<?php echo htmlspecialchars($row->fax); ?>" />
                        <span class="note">Business fax number (ex. 123-456-7890)</span>
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="logo">
                            Email
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="info@domain.com" name="email" id="email" size="50" value="<?php echo htmlspecialchars($row->email); ?>" />
                        <span class="note">Primary business email. Note: Releasing your email to the public may result in increased spam messages.</span>
                    </td>
            	</tr>
				<tr>
					<th colspan="2">
						<h3>Location</h3>
					</th>
				</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="address">
                            Address
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="123 Main Street" name="address" id="address" size="50" value="<?php echo htmlspecialchars($row->address); ?>" style="width: calc(75%);">
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="city">
                            City
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="Lewes" name="city" id="city" size="50" value="<?php echo htmlspecialchars($row->city); ?>" />
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="state">
                            State
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="DE" name="state" id="state" size="50" value="<?php echo htmlspecialchars($row->state); ?>" />
                        <span class="note">2 letter state abbreviation (ex. DE)</span>
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="zip">
                            Zip
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="19958" name="zip" id="zip" size="50" value="<?php echo htmlspecialchars($row->zip); ?>" />
                        <span class="note">5 digit Postal Code (ex. 19958)</span>
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="latitude">
                            Latitude
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="38.7517766" name="latitude" id="latitude" size="50" value="<?php echo htmlspecialchars($row->latitude); ?>" />
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="longitude">
                            Longitude
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="-75.1527114" name="longitude" id="longitude" size="50" value="<?php echo htmlspecialchars($row->longitude); ?>" />
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="googleMapLink">
                            Google Maps Link
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" placeholder="https://www.google.com/maps/place/Fine+Line+Websites+%26+IT+Consulting/@38.75176,-75.150335,17z/data=!3m1!4b1!4m2!3m1!1s0x89b8b76312333d93:0x53b24171281027a1" name="googleMapLink" id="googleMapLink" size="50" value="<?php echo htmlspecialchars($row->googleMapLink); ?>" style="width: calc(75%);" />
                    </td>
            	</tr>
				<tr>
					<th colspan="2">
						<h3>Areas Served</h3>
					</th>
				</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="areasServed">
                            Areas Served
                        </label>
                    </td>
                    <td width="80%">
                    	<div class="as-list">
                    		<?php
                			$split = explode(",", $row->areasServed);
                    		if($row->areasServed && count($split)) {
								foreach($split as $sa) {
									$typeValue = explode("::", $sa);
									$type = $typeValue[0];
									$as = $typeValue[1];
									echo "
                    					<div class='as-wrapper'>
											<select class='as-state' style='width: auto;'>
												<option>State</option>
												<option".($type=="City" ? " selected='selected'" : "").">City</option>
											</select>
											<input class='inputbox as-value' type='text' placeholder='Lewes' size='50' value=\"".htmlspecialchars($as)."\" />
				                        	<a href='javascript:;' class='btn btn-danger remove-as'>-</a>
                        				</div>	
		                        	";
								}
							} else {
								echo "
                					<div class='as-wrapper'>
										<select class='inputbox as-state' style='width: auto;'>
											<option>State</state>
											<option>City</option>
										</select>
										<input class='inputbox as-value' type='text' placeholder='Lewes' size='50' value='' />
			                        	<a href='javascript:;' class='btn btn-danger remove-as'>-</a>
                    				</div>	
	                        	";
							}
                    		?>
                    	</div>
                    	<a href="javascript:;" class="btn btn-success add-as">+ Add Additional</a>	
                    	<input type="hidden" id="areasServed" name="areasServed" value="<?php echo htmlspecialchars($row->areasServed); ?>" />
                    	
                    	<script>
                    		jQuery(".as-list").on("click", ".remove-as", function() {
                    			if(jQuery(".as-wrapper").length > 1) {
                    				jQuery(this).parent().remove();
                    			} else {
                    				jQuery(this).prev().val("");
                    			}
                    			buildAreasServed();
                    		});
                    		jQuery(".add-as").click(function() {
                    			jQuery(".as-list").append(jQuery(".as-wrapper").last().clone());
                    			jQuery(".as-wrapper").last().find("input").val("");
                    		});
                    		jQuery(".as-list").on("change", ".as-value", function() {
                    			buildAreasServed();
                    		});
                    		
                    		function buildAreasServed() {
                    			var sas = new Array();
                    			jQuery(".as-wrapper").each(function() {
                    				var state = jQuery(this).find(".as-state").val();
                    				var val = jQuery(this).find(".as-value").val();
                    				if(val.length) {
                    					sas.push(state+"::"+val);
                    				}
                    			});
                    			jQuery("#areasServed").val(sas.join(","));
                    		}
                    		buildAreasServed();
                    	</script>
                    </td>
            	</tr>
				<tr>
					<th colspan="2">
						<h3>Hours</h3>
					</th>
				</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="hours">
                            Hours of Operation
                        </label>
                    </td>
                    <td width="80%">
                    	<?php
                    	$days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
                    	$oldHours = array();
                    	$allHours = $row->hours;
						$allHours = explode(",", $allHours);
						if(count($allHours)) {
							foreach($allHours as $ah) {
								$split = explode("-", $ah);
								if(count($split) == 4) {
									$oldHours[$split[0]] = array($split[1], $split[2], $split[3]);
								}
							}
						}
						?>
						<div class="note">
							If your business is closed, leave Open and Close times blank.<br>
							If your business is open all day, check the 24hr box.<br><br>
						</div>
                    	<table class="table table-striped table-bordered hours" style="width: auto;">
                    		<tr>
                    			<th>Day</th>
                    			<th>Open <small>(24:00)</small></th>
                    			<th>Close <small>(24:00)</small></th>
                    			<th>24hrs</th>
                    		</tr>
                    		<?php
                    		for($i = 0; $i < 7; $i++) {
                    			$open = "";
								$close = "";
                    			if($oldHours[$i]) {
                    				$open = $oldHours[$i][0];
                    				$close = $oldHours[$i][1];
                    				$allDay = $oldHours[$i][2];
                    			}
								echo "
								<tr>
	                    			<td class='day-$i'>$days[$i]</td>
	                    			<td>
	                    				<input type='text' class='open-$i check-time' value='$open' ".($allDay ? "readonly=''" : "").">
	                    			</td>
	                    			<td>
	                    				<input type='text' class='close-$i check-time' value='$close' ".($allDay ? "readonly=''" : "").">
	                    			</td>
	                    			<td>
	                    				<input type='checkbox' class='all-day-$i all-day' data-id='$i' value='1' ".($allDay ? "checked='checked'" : "")."> 
	                    			</td>
	                    		</tr>
								";
							}
							?>
                    	</table>
                        <input type="hidden" id="hours" name="hours" value="<?php echo htmlspecialchars($row->hours); ?>" />
                        <script>
                        	function buildHours() {
                        		var hours = new Array();
                        		for( var i = 0; i < 7; i++ ) {
                        			var day = jQuery(".day-"+i).html();
                        			var open = jQuery(".open-"+i).val();
                        			var close = jQuery(".close-"+i).val();
                        			var allDay = 0;
                        			if(jQuery(".all-day-"+i).is(":checked")) {
                        				allDay = 1;
                        			}
                        			if(open.length && close.length) {
                        				hours.push(i+"-"+open+"-"+close+"-"+allDay);
                        			}
                        		}
                        		jQuery("#hours").val(hours.join(","));
                        	}
                        	jQuery(".hours input").change(function() {
                        		buildHours();
                        	});
                        	jQuery(".check-time").change(function() {
                        		if(!(jQuery(this).val().length == 0 || jQuery(this).val().length == 5)) {
                        			jQuery(this).addClass("error");
                        		} else {
                        			jQuery(this).removeClass("error")
                        		}
                        	});
                        	jQuery(".all-day").change(function() {
                        		var dayId = jQuery(this).attr("data-id");
                        		if(jQuery(this).is(":checked")) {
                        			jQuery(".open-"+dayId).val("00:00").prop('readonly', true);
                        			jQuery(".close-"+dayId).val("00:00").prop('readonly', true);
                				} else {
                        			jQuery(".open-"+dayId).val("").prop('readonly', false);
                        			jQuery(".close-"+dayId).val("").prop('readonly', false);
                				}
                        		buildHours();
                        	});
                        </script>
                    </td>
            	</tr>
				<tr>
					<th colspan="2">
						<h3>Products & Services</h3>
					</th>
				</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="priceRange">
                            Price Range
                        </label>
                    </td>
                    <td width="80%">
                    	<select class="inputbox" name="priceRange" id="priceRange">
                    		<option>$</option>
                    		<option>$$</option>
                    		<option>$$$</option>
                    		<option>$$$$</option>
                    	</select>
                    	<script>
                    		jQuery("#priceRange").val("<?php echo $row->priceRange;?>");
                    	</script>
                    </td>
            	</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="offerCatalog">
                            Catalog
                        </label>
                    </td>
                    <td width="80%">
                    	<div class="oc-list">
                    		<?php
                    		$json = json_decode($row->offerCatalog);
							if(empty($json)) {
								$json[] = array("",array(""));
							}
							foreach($json as $oc) {
								$categoryName = $oc[0];
								$products = $oc[1];
								echo "
                					<div class='oc-wrapper'>
										<input class='inputbox oc-value' type='text' placeholder='Category' size='50' value=\"".htmlspecialchars($categoryName)."\" style='width: calc(40%);' />
			                        	<a href='javascript:;' class='btn btn-danger remove-oc'>-</a> 
			                        	<div class='oc-children'>";
											foreach($products as $productData) {
												$type = $productData[0];
												$product = $productData[1];
			                        			echo "<div class='oc-child'>
					                        		 -------- 
					                        		 <select class='oc-ps' style='width: auto;'>
														<option>Product</option>
														<option".($type=="Service" ? " selected='selected'" : "").">Service</option>
													</select>
													<input class='inputbox occ-value' type='text' placeholder='Product/Service' size='50' value=\"".htmlspecialchars($product)."\" style='width: calc(40%);' />
					                        		<a href='javascript:;' class='btn btn-danger remove-occ'>-</a>
				                        		</div>";
											}
										echo "
			                        	</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			                        	<a href='javascript:;' class='btn btn-success btn-small add-occ'>+ Add Product/Service</a>
                						<hr>
                    				</div>	
	                        	";
							}
                    		?>
                    	</div>
                    	<a href="javascript:;" class="btn btn-success add-oc">+ Add Additional Category</a>	
                    	<input type="hidden" name="offerCatalog" id="offerCatalog" value="<?php echo htmlspecialchars($row->offerCatalog); ?>" />
                    	
                    	<script>
                    		jQuery(".oc-list").on("click", ".remove-oc", function() {
                    			if(jQuery(".oc-wrapper").length > 1) {
                    				jQuery(this).parent().remove();
                    			} else {
                    				jQuery(this).prev().val("");
                    			}
                    			buildOfferCatalog();
                    		});
                    		jQuery(".oc-list").on("click", ".remove-occ", function() {
                    			if(jQuery(this).parents(".oc-children").children().length > 1) {
                    				jQuery(this).parent().remove();
                    			} else {
                    				jQuery(this).prev().val("");
                    			}
                    			buildOfferCatalog();
                    		});
                    		jQuery(".oc-list").on("click", ".add-occ", function() {
                    			var newChild = jQuery(this).prev().find(".oc-child").last().clone();
                    			jQuery(this).prev().append(newChild);
                    			jQuery(this).prev().find(".occ-value").last().val("");
                    		});
                    		var newCategoryOriginal = jQuery(".oc-wrapper").last().clone();
                    		var newCategory = newCategoryOriginal.clone();
                    		jQuery(".add-oc").click(function() {
                    			jQuery(".oc-list").append(newCategory);
                    			jQuery(".oc-wrapper").last().find("input").val("");
                    			newCategory = newCategoryOriginal.clone();
                    		});
                    		jQuery(".oc-list").on("change", ".oc-value", function() {
                    			buildOfferCatalog();
                    		});
                    		jQuery(".oc-list").on("change", ".occ-value", function() {
                    			buildOfferCatalog();
                    		});
                    		jQuery(".oc-list").on("change", ".oc-ps", function() {
                    			buildOfferCatalog();
                    		});
                    		
                    		function buildOfferCatalog() {
                    			var ocData = new Array();
                    			jQuery(".oc-wrapper").each(function() {
                    				var cat = jQuery(this).find(".oc-value").val();
                    				var products = Array();
                    				if(cat.length) {
	                    				jQuery(this).find(".occ-value").each(function() {
	                    					if(jQuery(this).val().length) {
	                    						var occVal = jQuery(this).val();
	                    						var occType = jQuery(this).prev().val();
	                    						products.push(new Array(occType, occVal));
	                    					}
	                    				});
	                    				ocData.push(new Array(cat, products));
	                    			}
                    			});
                    			jQuery("#offerCatalog").val(JSON.stringify(ocData));
                    		}
                    		buildOfferCatalog();
                    	</script>
                    </td>
            	</tr>
				<tr>
					<th colspan="2">
						<h3>Social Media</h3>
					</th>
				</tr>
                <tr>
                    <td width="2%" class="key">
                        <label for="sameAs">
                            Social Media Links
                        </label>
                    </td>
                    <td width="80%">
                    	<div class="sa-list">
                    		<?php
                			$split = explode(",", $row->sameAs);
                    		if($row->sameAs && count($split)) {
								foreach($split as $sa) {
									echo "
                    					<div class='sa-wrapper'>
											<input class='inputbox sa-value' type='text' placeholder='https://www.facebook.com/YourBusinessName' size='50' value=\"".htmlspecialchars($sa)."\" style='width: calc(40%);' />
				                        	<a href='javascript:;' class='btn btn-danger remove-sa'>-</a>
                        				</div>	
		                        	";
								}
							} else {
								echo "
                					<div class='sa-wrapper'>
										<input class='inputbox sa-value' type='text' placeholder='https://www.facebook.com/YourBusinessName' size='50' value='' style='width: calc(40%);' />
			                        	<a href='javascript:;' class='btn btn-danger remove-sa'>-</a>
                    				</div>	
	                        	";
							}
                    		?>
                    	</div>
                    	<a href="javascript:;" class="btn btn-success add-sa">+ Add Additional</a>	
                    	<input type="hidden" id="sameAs" name="sameAs" value="<?php echo htmlspecialchars($row->sameAs); ?>" />
                    	
                    	<script>
                    		jQuery(".sa-list").on("click", ".remove-sa", function() {
                    			if(jQuery(".sa-wrapper").length > 1) {
                    				jQuery(this).parent().remove();
                    			} else {
                    				jQuery(this).prev().val("");
                    			}
                    			buildSameAs();
                    		});
                    		jQuery(".add-sa").click(function() {
                    			jQuery(".sa-list").append(jQuery(".sa-wrapper").last().clone());
                    			jQuery(".sa-wrapper").last().find("input").val("");
                    		});
                    		jQuery(".sa-list").on("change", ".sa-value", function() {
                    			buildSameAs();
                    		});
                    		
                    		function buildSameAs() {
                    			var sas = new Array();
                    			jQuery(".sa-value").each(function() {
                    				var val = jQuery(this).val();
                    				if(val.length) {
                    					sas.push(val);
                    				}
                    			});
                    			jQuery("#sameAs").val(sas.join(","));
                    		}
                    		buildSameAs();
                    	</script>
                    </td>
            	</tr>
			</table>
			
			<input type="hidden" name="fl_schema_id" value="1" />
			<input type="hidden" name="c" value="schema" />
			<input type="hidden" name="option" value="com_fl_schema" />
			<input type="hidden" name="task" value="" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

}
