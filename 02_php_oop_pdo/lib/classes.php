<?php 

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;

    private $dbhandler;
    private $error;
    private $sqlQuery;

    public function __construct() {
        // set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        // instantiate PDO object
        try {
            $this->dbhandler = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function queryDB($dbQuery) {
        $this->sqlQuery = $this->dbhandler->prepare($dbQuery);
    }

    public function bind($parameter, $value, $type = null) {
        if (is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->sqlQuery->bindValue($parameter, $value, $type);
    }

    public function execute() {
        return $this->sqlQuery->execute();
    }
    
    public function resultAll() {
        $this->execute();
        return $this->sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resultSingle() {
        $this->execute();
        return $this->sqlQuery->fetch(PDO::FETCH_ASSOC);
    }

    public function countRows() {
        $this->execute();
        return $this->sqlQuery->rowCount();
    }

}

class Listing {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get Listings
    public function getListings() {
        $this->db->queryDB("SELECT * FROM listings_pdo_oop ORDER BY created_at DESC");
        return $this->db->resultAll();
    }

    public function getListingsWithLimit($startNum, $endNum) {
        $this->db->queryDB("SELECT * FROM listings_pdo_oop ORDER BY created_at DESC LIMIT :startNum, :endNum");
        $this->db->bind(':startNum', $startNum);
        $this->db->bind(':endNum', $endNum);
        return $this->db->resultAll();
    }

    public function getListingById($id) {
        $this->db->queryDB("SELECT * FROM listings_pdo_oop WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->resultSingle();
    }

    public function getPopularCities() {
        $this->db->queryDB("SELECT city, COUNT(city) AS countCity FROM listings_pdo_oop GROUP BY city ORDER BY countCity DESC");
        return $this->db->resultAll();
    }

    public function getDistinctCities() {
        $this->db->queryDB("SELECT DISTINCT city FROM listings_pdo_oop ORDER BY city");
        return $this->db->resultAll();
    }

    public function customQuery($query) {
        $this->db->queryDB($query);
        return $this->db->resultAll();
    }
    
    public function deleteListingById($delete_id) {
        $this->db->queryDB("DELETE FROM listings_pdo_oop WHERE id = :delete_id");
        $this->db->bind(':delete_id', $delete_id);
        $this->db->execute();
    }

    public function setListingLimit() {
        $this->db->queryDB("SELECT count(*) as allRows FROM listings_pdo_oop");
        return $this->db->resultSingle();
    }

    public function insertData($data) {
        $this->db->queryDB("INSERT INTO listings_pdo_oop(price, address, city, province, beds, baths, front_img, description, author) VALUES(:price, :address, :city, :province, :beds, :baths, :front_img, :description, :author)");
        // 
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':beds', $data['beds']);
        $this->db->bind(':baths', $data['baths']);
        $this->db->bind(':front_img', $data['front_img']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':author', $data['author']);
        // 
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateData($data, $update_listing_id) {
        $this->db->queryDB("UPDATE listings_pdo_oop SET price = :price, address = :address, city = :city, province = :province, beds = :beds, baths = :baths, front_img = :front_img, description = :description, author = :author WHERE id = :update_listing_id");
        // 
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':beds', $data['beds']);
        $this->db->bind(':baths', $data['baths']);
        $this->db->bind(':front_img', $data['front_img']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':author', $data['author']);
        $this->db->bind(':update_listing_id', $update_listing_id);
        // 
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function manageVisitedListings($id) {
        // 
        if (isset($_SESSION['visitedPages'])) {
            // 
            if (in_array($id, $_SESSION['visitedPages'])) {
                // 
                $key = array_search($id, $_SESSION['visitedPages']);
                unset($_SESSION['visitedPages'][$key]);
                array_unshift($_SESSION['visitedPages'], intval($id));
                // 
            } else {
                // 
                array_unshift($_SESSION['visitedPages'], intval($id));
                // 
            }
            // 
        } else {
            // 
            $_SESSION['visitedPages'] = [];
            // 
        }
        // 
    }
}

?>