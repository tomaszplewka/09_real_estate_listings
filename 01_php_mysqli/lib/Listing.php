<?php 

class Listing {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get All Listings
    public function queryListings() {
        $this->db->queryDB("SELECT * FROM real_estate_listings");
        return $this->db->resultAll();
    }

    public function setListingLimit() {
        return $this->db->queryDB("SELECT count(id) AS id FROM real_estate_listings");
    }
}

?>