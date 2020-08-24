<!-- Index -->

<?php
// 
session_start();
// 
include('config/config.php');
include('config/db_connect.php');
// 
function markSelected($selectName, $selectValue)
{
    return (isset($_POST[$selectName]) && $_POST[$selectName] === $selectValue) ? 'selected' : '';
}
// 
function queryDatabase($sqlQuery, $conn)
{
    // 
    $sql = $sqlQuery;
    $result = mysqli_query($conn, $sql);
    $arrayListings = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    // 
    return $arrayListings;
}
// 
$dataInterval = [1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000];
$photoTags = ['house', 'family', 'skyscraper', 'love', 'neighbourhood', 'interior', 'kitchen'];
$location = $minPrice = $maxPrice = $beds = $baths = '';
$formVisible = false;
$listingView = false;
$seeAllView = false;
// 
if (isset($_GET['all-search'])) {
    // 
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        // 
        $page = $_GET['page'];
        // 
    } else {
        // 
        $page = 1;
        // 
    }
    // 
    if (isset($_POST['records-limit'])) {
        // 
        $_SESSION['records-limit'] = $_POST['records-limit'];
        header('Location: ' . ROOT_URL . 'index.php' . '?page=' . $page . $_SESSION['search']);
        // 
    }
    // 
    $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 3;
    // 
    $sqlQuery = "SELECT count(id) AS id FROM real_estate_listings";
    $seeAllListings = queryDatabase($sqlQuery, $conn);
    // 
    $totalPages = ceil($seeAllListings[0]['id'] / $limit);
    // 
    $paginationStart = ($page - 1) * $limit;
    // 
    $sqlQuery = "SELECT * FROM real_estate_listings LIMIT {$paginationStart}, {$limit}";
    $realEstateListings = queryDatabase($sqlQuery, $conn);
    // 
    $prev = $page - 1;
    $next = $page + 1;
    // 
} elseif (isset($_GET['custom-search'])) {
    // 
    if (empty($_SESSION['errors'])) {
        // 
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            // 
            $page = $_GET['page'];
            // 
        } else {
            // 
            $page = 1;
            // 
        }
        // 
        if (isset($_POST['records-limit'])) {
            // 
            $_SESSION['records-limit'] = $_POST['records-limit'];
            header('Location: ' . ROOT_URL . 'index.php' . '?page=' . $page . $_SESSION['search']);
            // 
        }
        // 
        $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 3;
        // 
        $paginationStart = ($page - 1) * $limit;
        // 
        $sqlQuery = "SELECT * FROM real_estate_listings WHERE 1 = 1";
        // 
        foreach ($_SESSION['custom-search'] as $key => $value) {
            // 
            if (isset($_SESSION['custom-search'][$key])) {
                // 
                if ($key === 'location') {
                    // 
                    $value === 'Any' ? '' : ($sqlQuery .= " AND city = '{$value}' ");
                    // 
                } elseif ($key === 'minPrice') {
                    // 
                    $value === 0. ? '' : ($sqlQuery .= " AND CAST(price AS DECIMAL) >= CAST({$value} AS DECIMAL) ");
                    // 
                } elseif ($key === 'maxPrice') {
                    // 
                    $value === 'Unlimited' ? '' : ($sqlQuery .= " AND CAST(price AS DECIMAL) <= CAST({$value} AS DECIMAL) ");
                    // 
                } else {
                    // 
                    $value === 'Any' ? '' : ($sqlQuery .= " AND {$key} = '{$value}' ");
                    // 
                }
                // 
            }
            // 
        }
        // 
        $realEstateListings = queryDatabase($sqlQuery, $conn);
        // 
        $totalPages = ceil(count($realEstateListings) / $limit);
        // 
        $sqlQuery .= " LIMIT {$paginationStart}, {$limit}";
        // 
        $realEstateListings = queryDatabase($sqlQuery, $conn);
        // 
        $prev = $page - 1;
        $next = $page + 1;
        // 
    } else {
        // 
        $realEstateListings = [];
        $totalPages = 0;
        // 
    }
    // 
}
// 
if (!empty(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY))) {
    // 
    $formVisible = true;
    $listingView = true;
    // 
}
// 
if (basename($_SERVER['PHP_SELF'], '.php') === 'index' && empty(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY))) {
    // 
    $_SESSION['errors'] = [];
    $_SESSION['msg'] = '';
    // 
}
// 
if (isset($_POST['search-submit'])) {
    // 
    $sqlQuery = "SELECT * FROM real_estate_listings ORDER BY created_at DESC";
    $seeAllListings = queryDatabase($sqlQuery, $conn);
    // 
    $formVisible = true;
    $listingView = true;
    $seeAllView = true;
    // 
    $_SESSION['search'] = '&all-search=true';
    // 
    $_SESSION['custom-search'] = [];
    // 
    header('Location: ' . ROOT_URL . 'index.php' . '?page=' . '1' . $_SESSION['search']);
    // 
}
// 
if (isset($_POST['submit'])) {
    // 
    $formVisible = true;
    $listingView = true;
    // 
    $_SESSION['search'] = '?custom-search=true';
    // 
    if (isset($_POST['maxPrice']) && isset($_POST['minPrice'])) {
        // 
        if ($_POST['maxPrice'] !== 'Unlimited') {
            // 
            filter_var($_POST['minPrice'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND) > filter_var($_POST['maxPrice'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND) ? $_SESSION['errors']['minMaxPrice'] = 'minPrice cannot be greater than maxPrice' : '';
            // 
        }
        // 
    }
    // 
    if (empty($_SESSION['errors']['minMaxPrice']) && isset($_POST['location']) && isset($_POST['minPrice']) && isset($_POST['maxPrice']) && isset($_POST['beds']) && isset($_POST['baths'])) {
        // 
        if (isset($_SESSION['errors']['minMaxPrice'])) {
            // 
            header('Location: ' . ROOT_URL . 'index.php' . '?page=' . '1' . $_SESSION['search']);
            // 
        }
        // 
        $_SESSION['errors'] = [];
        $_SESSION['msg'] = '';
        // 
        $sqlArray = [];
        // 
        $sqlArray['location'] = $_POST['location'] === 'Any' ? 'Any' : $_POST['location'];
        $sqlArray['minPrice'] = $_POST['minPrice'] === 0 ? 0. : filter_var($_POST['minPrice'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
        $sqlArray['maxPrice'] = $_POST['maxPrice'] === 'Unlimited' ? 'Unlimited' : filter_var($_POST['maxPrice'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
        $sqlArray['beds'] = $_POST['beds'] === 'Any' ? 'Any' : $_POST['beds'];
        $sqlArray['baths'] = $_POST['baths'] === 'Any' ? 'Any' : $_POST['baths'];
        // 
        $_SESSION['custom-search'] = $sqlArray;
        // 
        $_SESSION['search'] = '&custom-search=true';
        // 
        header('Location: ' . ROOT_URL . 'index.php' . '?page=' . '1' . $_SESSION['search']);
        // 
    } else {
        // 
        $sqlArray = [];
        // 
        $sqlArray['location'] = $_POST['location'] === 'Any' ? 'Any' : $_POST['location'];
        $sqlArray['minPrice'] = $_POST['minPrice'] === 0 ? 0. : filter_var($_POST['minPrice'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
        $sqlArray['maxPrice'] = $_POST['maxPrice'] === 'Unlimited' ? 'Unlimited' : filter_var($_POST['maxPrice'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
        $sqlArray['beds'] = $_POST['beds'] === 'Any' ? 'Any' : $_POST['beds'];
        $sqlArray['baths'] = $_POST['baths'] === 'Any' ? 'Any' : $_POST['baths'];
        // 
        $_SESSION['custom-search'] = $sqlArray;
        // 
        if (!isset($_POST['location'])) {
            // 
            $_SESSION['errors'][] = 'location';
            $_SESSION['msg'] = 'Please specify Location';
            // 
        }
        // 
        if (!isset($_POST['minPrice'])) {
            // 
            $_SESSION['errors'][] = 'minPrice';
            $_SESSION['msg'] = 'Please select Min Price';
            // 
        }
        // 
        if (!isset($_POST['maxPrice'])) {
            // 
            $_SESSION['errors'][] = 'maxPrice';
            $_SESSION['msg'] = 'Please select Max Price';
            // 
        }
        // 
        if (!isset($_POST['beds'])) {
            // 
            $_SESSION['errors'][] = 'beds';
            $_SESSION['msg'] = 'Please specify # of Beds';
            // 
        }
        // 
        if (!isset($_POST['baths'])) {
            // 
            $_SESSION['errors'][] = 'baths';
            $_SESSION['msg'] = 'Please specify # of Baths';
            // 
        }
        //
        header('Location: ' . ROOT_URL . 'index.php' . $_SESSION['search']);
        // 
    }
    // 
}
// 
$sqlQuery = "SELECT * FROM real_estate_listings ORDER BY created_at DESC LIMIT 3";
$listings = queryDatabase($sqlQuery, $conn);
// 
if (isset($_SESSION['visitedPages']) && count($_SESSION['visitedPages'])) {
    // 
    $array = $_SESSION['visitedPages'];
    // 
    $recentlyViewedListings = [];
    // 
    foreach ($array as $id) {
        // 
        $sql = "SELECT * FROM real_estate_listings WHERE id = {$id}";
        $result = mysqli_query($conn, $sql);
        $listing = mysqli_fetch_assoc($result);
        array_push($recentlyViewedListings, $listing);
        mysqli_free_result($result);
        // 
    }
    // 
}
// 
$sqlQuery = "SELECT DISTINCT city FROM real_estate_listings ORDER BY city";
$cities = queryDatabase($sqlQuery, $conn);
// 
$sqlQuery = "SELECT city, COUNT(city) AS countCity FROM `real_estate_listings` GROUP BY city ORDER BY countCity DESC LIMIT 3";
$popularCities = queryDatabase($sqlQuery, $conn);
// 
mysqli_close($conn);
//
?>

<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>

<?php if ($listingView) : ?>
    <div class="container p-0 border border-dark shadow">

        <?php include('templates/showcase-search-form.php') ?>

    </div>
    <div class="container my-5 pt-3 pb-5 bg-custom-secondary border border-dark shadow-lg" id="search-results">
        <h2 class="h2 m-3">Search Results</h2>
        <div class="d-flex justify-content-between bd-highlight mx-3" id="search-results-control">
            <!-- Select dropdown -->
            <form id="records-limit-form" action="<?php echo 'index.php' . '?page=' . '1' . $_SESSION['search'] ?>" method="post" class="p-0 m-0 mr-2">
                <select name="records-limit" id="records-limit" class="custom-select">
                    <option disabled selected>Records Limit</option>
                    <?php foreach ([3, 6, 9, 12] as $limit) : ?>
                        <option <?php echo empty($realEstateListings) ? 'disabled' : ''; ?> <?php if (isset($_SESSION['records-limit']) && $_SESSION['records-limit'] == $limit) echo 'selected'; ?> value="<?= $limit; ?>">
                            <?= $limit; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <!-- Pagination -->
            <nav class="">
                <ul class="pagination justify-content-end m-0">
                    <li class="page-item text-dark <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link text-dark" href="<?php echo $page <= 1 ? '#' : "?page=" . $prev . $_SESSION['search']; ?>">
                            Previous
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <?php if ($i === 1) : ?>
                            <li class="page-item text-dark first-page <?php echo $page == $i ? 'pagination-active' : ''; ?>">
                                <a class="page-link text-dark" href="index.php?page=<?= $i . $_SESSION['search']; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php elseif ($i === intval($totalPages)) : ?>
                            <li class="page-item text-dark last-page <?php echo $page == $i ? 'pagination-active' : ''; ?>">
                                <a class="page-link text-dark" href="index.php?page=<?= $i . $_SESSION['search']; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php else : ?>
                            <li class="page-item text-dark <?php echo $page == $i ? 'pagination-active' : ''; ?>">
                                <a class="page-link text-dark" href="index.php?page=<?= $i . $_SESSION['search']; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <li class="page-item text-dark <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link text-dark" href="<?php echo $page >= $totalPages ? '#' : "?page=" . $next . $_SESSION['search']; ?>">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <?php if (empty($realEstateListings)) : ?>
            <p class="h4 text-dark text-center my-5">No Results to Show</p>
        <?php else : ?>

            <?php
            $array = $realEstateListings;
            include('templates/listing-card-deck.php');
            ?>

        <?php endif; ?>

    </div>
<?php else : ?>
    <div class="container p-0 border border-dark shadow">
        <?php include('templates/showcase-carousel.php'); ?>
    </div>
    <div class="container my-5 pt-3 pb-5 bg-custom-light border border-dark shadow-lg">
        <h2 class="h2 m-3">Recently Viewed</h2>

        <?php if (isset($_SESSION['visitedPages']) && count($_SESSION['visitedPages'])) : ?>

            <?php
            $array = $recentlyViewedListings;
            include('templates/listing-card-deck.php');
            ?>

        <?php else : ?>
            <p class="h4 text-dark text-center my-5">No Recently Viewed Listings</p>
        <?php endif; ?>

    </div>
    <div class="container my-5 pt-3 pb-5 bg-custom-white border border-dark shadow-lg">
        <h2 class="h2 m-3">Popular Cities</h2>
        <div class="row row-cols-1 row-cols-md-3 m-0 p-0">
            <?php foreach ($recentlyViewedListings as $key => $recentlyViewedListing) : ?>
                <div class="card-deck col m-0 p-0">
                    <div class="card position-relative border-dark bg-custom-white text-white text-center m-0 mx-3 shadow-lg">
                        <div id="popularCitiesCarousel" class="carousel slide carousel-fade position-relative" data-ride="carousel">
                            <div id="showcase-main-heading" class="position-absolute d-flex flex-column justify-content-around align-items-center">
                                <h4 class="display-4 text-white pt-4"><?php echo $popularCities[$key]['city']; ?></h4>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-interval="<?php echo $dataInterval[array_rand($dataInterval, 1)]; ?>">
                                    <img src="<?php echo 'https://source.unsplash.com/400x400/?city,' . $photoTags[array_rand($photoTags, 1)]; ?>" class="d-block img-carousel" alt="...">
                                </div>
                                <div class="carousel-item" data-interval="<?php echo $dataInterval[array_rand($dataInterval, 1)]; ?>">
                                    <img src="<?php echo 'https://source.unsplash.com/400x400/?city,' . $photoTags[array_rand($photoTags, 1)]; ?>" class="d-block img-carousel" alt="...">
                                </div>
                                <div class="carousel-item" data-interval="<?php echo $dataInterval[array_rand($dataInterval, 1)]; ?>">
                                    <img src="<?php echo 'https://source.unsplash.com/400x400/?city,' . $photoTags[array_rand($photoTags, 1)]; ?>" class="d-block img-carousel" alt="...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container my-5 pt-3 pb-5 bg-custom-secondary border border-dark shadow-lg">
        <h2 class="h2 m-3">Recently Added</h2>

        <?php
        $array = $listings;
        include('templates/listing-card-deck.php');
        ?>

    </div>
<?php endif; ?>
<div class="container my-5 pt-3 pb-3 bg-custom-white border border-dark shadow-lg" id="footer-showcase">
    <div class="row row-cols-1 row-cols-md-2 m-0 p-0">
        <div class="col-12 col-md-6 m-0 p-0">
            <h3 class="h3 text-center mx-5">We Were Born To Be Real Estate Agents</h3>
            <p class="lead text-center m-0 mx-5 mb-2">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestiae dolor totam aliquid tenetur minus eos sit, dolorum corporis praesentium at.</p>
        </div>
        <div class="col-12 col-md-6 row m-0 p-0">
            <div class="col-12 col-md-6 m-0 p-0">
                <img src="https://source.unsplash.com/300x300/?person,phone" class="d-block img-carousel" alt="...">
            </div>
            <div class="col-12 col-md-6 m-0 p-0">
                <img src="https://source.unsplash.com/300x301/?person,talk" class="d-block img-carousel" alt="...">
            </div>
        </div>
    </div>
</div>

<?php include('templates/footer.php'); ?>

</html>