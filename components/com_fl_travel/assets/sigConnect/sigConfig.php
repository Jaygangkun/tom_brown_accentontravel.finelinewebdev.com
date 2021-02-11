<?php

class sigConfig {
	public $baseUrl = "https://api.signaturetravelnetwork.com/sws/v1/";
	public $apiKey = "37f565b33aa3471abc7b44e92d3c3ff5";
	public $agencyId = 1956;
	public $debug = TRUE;
	
	public $endPoints = array(
		"getAdvisors" => "advisors",
		"getAdvisor" => "advisors/{id}",
		"getAgencies" => "agencies",
		"getAgency" => "agencies/{id}",
		"getDeals" => "deals",
		"getDeal" => "deals/{id}",
		"getDestinations" => "destinations",
		"getDestination" => "destinations/{id}",
		"getSearchableDestinations" => "searchable_destinations",
		"getSearchableDestination" => "searchable_destinations/{id}",
		"getOffers" => "offers",
		"getOffer" => "offers/{id}",
		"getCruiseOffers" => "offers/cruise",
		"getCruiseOffer" => "offers/cruise/{id}",
		"getCruisePricing" => "offers/cruise/{id}/pricing",
		"getHotelOffers" => "offers/hotel",
		"getHotelOffer" => "offers/hotel/{id}",
		"getTourOffers" => "offers/tour",
		"getTourOffer" => "offers/tour/{id}",
		"getEWizardTemplates" => "ewizard_templates",
		"getEWizardTemplate" => "ewizard_templates/{id}",
		"getPromos" => "offers",
		"getPromo" => "offers/{id}",
		"getShips" => "ships",
		"getShip" => "ships/{id}",
		"getSuppliers" => "suppliers",
		"getSupplier" => "suppliers/{id}",
		"getTagCategories" => "tags/categories",
		"getTags" => "tags",
		"getTaggedItems" => "tags/items",
	);
	
	public $endPointsMaxReturn = array(
		"getAdvisors" => 1000,
		"getShips" => 1000,
		"getAgencies" => 100,
		"getDeals" => 1000,
		"getDestinations" => 1000,
		"getSearchableDestinations" => 1000,
		"getOffers" => 100,
		"getCruiseOffers" => 100,
		"getHotelOffers" => 100,
		"getTourOffers" => 100,
		"getEWizardTemplates" => 1000,
		"getPromos" => 1000,
		"getShips" => 1000,
		"getSuppliers" => 1000,
		"getTagCategories" => 1000,
		"getTags" => 1000,
		"getTaggedItems" => 1000
	);
	
	public $resultVarNames = array(
		"getAdvisors" => "advisors",
		"getAdvisor" => "advisors",
		"getAgencies" => "agencies",
		"getAgency" => "agencies",
		"getDeals" => "deals",
		"getDeal" => "deals",
		"getDestinations" => "destinations",
		"getDestination" => "destinations",
		"getSearchableDestinations" => "searchable_destinations",
		"getSearchableDestination" => "searchable_destinations",
		"getOffers" => "offers",
		"getOffer" => "offers",
		"getCruiseOffers" => "offers",
		"getCruiseOffer" => "offers",
		"getCruisePricing" => "cruise_offer_pricing",
		"getHotelOffers" => "offers",
		"getHotelOffer" => "offers",
		"getTourOffers" => "offers",
		"getTourOffer" => "offers",
		"getEWizardTemplates" => "ewizard_templates",
		"getEWizardTemplate" => "ewizard_templates",
		"getPromos" => "offers",
		"getPromo" => "offers",
		"getShips" => "ships",
		"getShip" => "ships",
		"getSuppliers" => "suppliers",
		"getSupplier" => "suppliers",
		"getTagCategories" => "tag_categories",
		"getTags" => "tags",
		"getTaggedItems" => "tagged_items"
	);
	
}
