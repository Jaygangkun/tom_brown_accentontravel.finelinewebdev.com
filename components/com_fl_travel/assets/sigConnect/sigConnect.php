<?php

require "sigConfig.php";

class sigConnect
{
	private $curl;
	private $config;
	private $exclusions;
	private $pricePointDisplaySettings;
	
	public function __construct() {
		// Build CURL
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		
		// Get Config
		$this->config = new sigConfig();
		
		// Get Agency Exclusions
		$thisAgency = $this->getThisAgency();
		$this->exclusions['cruise'] = array();
		$this->exclusions['tour'] = array();
		foreach($thisAgency->supplier_exclusions as $supplierExclusion) {
			if($supplierExclusion->cruise_flag) {
				$this->exclusions['cruise'][] = $supplierExclusion->supplier_id;
			}
			if($supplierExclusion->tour_flag) {
				$this->exclusions['tour'][] = $supplierExclusion->supplier_id;
			}
		}
		
		foreach($thisAgency->price_point_type_display_settings as $pricePoint) {
			$this->pricePointDisplaySettings[$pricePoint->supplier_id][$pricePoint->price_point_type_id] = $pricePoint->show_to_consumers;
		}
	}
	
	public function getPage($type = "", $filter = "", $include = "", $startAt = 1, $maxReturn = 24, $sort = "", $sortOrder = "") {
		$endPoint = $this->config->endPoints[$type];
		$resultVar = $this->config->resultVarNames[$type];
		if(empty($endPoint)) {
			echo "Missing End Point.";
			exit;
		}
		if(empty($maxReturn)) {
			echo "Missing Max Return.";
			exit;
		}
		if(empty($resultVar)) {
			echo "Missing Return Var Name.";
			exit;
		}
		
		$allResults = array();

		$url = $this->config->baseUrl . $endPoint . "?api_key=".$this->config->apiKey;
		if($startAt) {
			$url .= "&start_at=" . $startAt;
		}
		if($maxReturn) {
			$url .= "&max_return=" . $maxReturn;
		}
		if($filter) {
			$url .= "&filter=" . $filter;
		}
		if($include) {
			$url .= "&include=" . $include;
		}
		if($fields) {
			$url .= "&fields=" . $fields;
		}
		if($sort) {
			if(!$sortOrder) {
				$sortOrder = "ASC";
			}
			$url .= "&sort=$sort";
			$url .= "&sort_order=$sortOrder";
		}
		
		if($this->config->debug) {
			echo "<div class='alert alert-info small'>URL: $url</div>";
		}
		
		$result = $this->runCurlRequest($url);
		
		$metaData = $result->meta;
		$totalRecords = $metaData->total;
		
		if(count($result->$resultVar)) {
			foreach($result->$resultVar as $thisResult) {
				$allResults[] = $thisResult;
			}
		}
		
		if(count($allResults)) {
			return array("data" => $allResults, "totalCount" => $metaData->total);
		} else {
			// echo "Warning: Get all '$type' returned no results \n";
			return false;
		}
	}
	
	private function getAll($type = "", $filter = "", $include = "", $fields = "") {
		$endPoint = $this->config->endPoints[$type];
		$maxReturn = $this->config->endPointsMaxReturn[$type];
		$resultVar = $this->config->resultVarNames[$type];
		if(empty($endPoint)) {
			echo "Missing End Point.";
			exit;
		}
		if(empty($maxReturn)) {
			echo "Missing Max Return.";
			exit;
		}
		if(empty($resultVar)) {
			echo "Missing Return Var Name.";
			exit;
		}
		
		$startAt = 1;
		$totalRecords = 9999999999;
		$allResults = array();
		
		while($startAt <= $totalRecords) {
			$url = $this->config->baseUrl . $endPoint . "?api_key=".$this->config->apiKey;
			if($startAt) {
				$url .= "&start_at=" . $startAt;
			}
			if($maxReturn) {
				$url .= "&max_return=" . $maxReturn;
			}
			if($filter) {
				$url .= "&filter=" . $filter;
			}
			if($include) {
				$url .= "&include=" . $include;
			}
			if($fields) {
				$url .= "&fields=" . $fields;
			}
			
			$result = $this->runCurlRequest($url);
			
			$metaData = $result->meta;
			$totalRecords = $metaData->total;
			
			$currentCount = $startAt+$maxReturn-1;
			if($currentCount > $totalRecords) {
				$currentCount = $totalRecords;
			}
			$percentage = floor($currentCount / $totalRecords * 10000) / 100;
			
			$this->out("Pulled ".$currentCount." of $totalRecords ($percentage%)");
			
			$startAt += $maxReturn;
			
			
			if(count($result->$resultVar)) {
				if($this->config->debug) {
					print_r($result->$resultVar[0]);
					return false;
				}
				foreach($result->$resultVar as $thisResult) {
					$allResults[] = $thisResult;
				}
			}
		}
		
		if(count($allResults)) {
			return $allResults;
		} else {
			echo "Warning: Get all '$type' returned no results \n";
			return false;
		}
	}
	
	private function getOne($type = "", $id = "", $include = "all", $fields = "") {
		$endPoint = $this->config->endPoints[$type];
		$resultVar = $this->config->resultVarNames[$type];
		if(empty($endPoint)) {
			echo "Missing End Point.";
			exit;
		}
		if(empty($id)) {
			echo "Missing ID.";
			exit;
		}
		
		// Build URL and Insert ID
		$url = $this->config->baseUrl . $endPoint . "?api_key=".$this->config->apiKey;
		$url = str_replace("{id}", $id, $url);
		
		if($include) {
			$url .= "&include=" . $include;
		}
		if($fields) {
			$url .= "&fields=" . $fields;
		}
		
		$result = $this->runCurlRequest($url);
		
		$metaData = $result->meta;
		
		$results = $result->$resultVar;
		
		if(count($results)) {
			return $results[0];
		} else {
			echo "Warning: Get One '$type' returned no results for ID: $id \n";
			return false;
		}
	}
	
	private function runCurlRequest($url) {
		curl_setopt($this->curl, CURLOPT_URL, $url);
		$jsonReturn = curl_exec($this->curl);
		return json_decode($jsonReturn);
	}
	
	public function getAdvisors($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getAdvisors", $filter, $include, $fields );
	}
	
	public function getAdvisor($id) {
		return $this->getOne("getAdvisor", $id );
	}
	
	public function getAgencies($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getAgencies", $filter, $include, $fields );
	}
	
	public function getAgency($id) {
		return $this->getOne("getAgency", $id );
	}
	
	public function getThisAgency() {
		return $this->getOne("getAgency", $this->config->agencyId );
	}
	
	public function getDeals($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getDeals", $filter, $include, $fields );
	}
	
	public function getDeal($id) {
		return $this->getOne("getDeal", $id );
	}
	
	public function getDestinations($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getDestinations", $filter, $include, $fields );
	}
	
	public function getDestination($id) {
		return $this->getOne("getDestination", $id );
	}
	
	public function getSearchableDestinations($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getSearchableDestinations", $filter, $include, $fields );
	}
	
	public function getSearchableDestination($id) {
		return $this->getOne("getSearchableDestination", $id );
	}
	
	public function getOffers($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getOffers", $filter, $include, $fields );
	}
	
	public function getOffer($id) {
		return $this->getOne("getOffer", $id );
	}
	
	public function getCruiseOffers($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getCruiseOffers", $filter, $include, $fields );
	}
	
	public function getCruiseOfferPage($startAt = 1, $maxReturn = 24, $filter = "", $include = "all", $sort = "", $sortOrder = "") {
		return $this->getPage("getCruiseOffers", $filter, $include, $startAt, $maxReturn, $sort, $sortOrder );
	}
	
	public function getCruiseOffer($id) {
		return $this->getOne("getCruiseOffer", $id );
	}
	
	public function getCruisePricing($id) {
		return $this->getOne("getCruisePricing", $id );
	}
	
	public function getHotelOffers($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getHotelOffers", $filter, $include, $fields );
	}
	
	public function getHotelOffer($id) {
		return $this->getOne("getHotelOffer", $id );
	}
	
	public function getTourOffers($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getTourOffers", $filter, $include, $fields );
	}
	
	public function getTourOffer($id) {
		return $this->getOne("getTourOffer", $id );
	}
	
	public function getEWizardTemplates($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getEWizardTemplates", $filter, $include, $fields );
	}
	
	public function getEWizardTemplate($id) {
		return $this->getOne("getEWizardTemplate", $id );
	}
	
	public function getPromos($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getPromos", $filter, $include, $fields );
	}
	
	public function getPromo($id) {
		return $this->getOne("getPromo", $id );
	}
	
	public function getShips($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getShips", $filter, $include, $fields );
	}
	
	public function getShip($id) {
		return $this->getOne("getShip", $id );
	}
	
	public function getSuppliers($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getSuppliers", $filter, $include, $fields );
	}
	
	public function getSupplier($id) {
		return $this->getOne("getSupplier", $id );
	}

	public function getTagCategories($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getTagCategories", $filter, $include, $fields );
	}

	public function getTags($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getTags", $filter, $include, $fields );
	}

	public function getTag($id) {
		return $this->getOne("getTag", $id );
	}

	public function getTaggedItems($filter = "", $include = "all", $fields = "all") {
		return $this->getAll("getTaggedItems", $filter, $include, $fields );
	}
	
	private function out($log) {
		echo date("h:i:s").": $log \n";
	}
}
