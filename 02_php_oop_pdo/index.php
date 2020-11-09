<!-- Index -->

<?php
// 
session_start();
// 
include('config/config.php');
include('lib/classes.php');
include('lib/utilities.php');
// 
$listingObj = new Listing;
// 
// Variables & SESSION reset
$dataInterval = [2000, 2500, 3000, 3500, 4000, 4500, 5000];
$listingView = false;
unset($_SESSION['after-add-listing']);
// 
// when Browse Our Database button is clicked
if (!empty(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY))) {
    $listingView = true;
}
// 
// reset errors
if (basename($_SERVER['PHP_SELF'], '.php') === 'index' && empty(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY))) {
    $_SESSION['errors'] = [];
}
//
// Get All Listings when Browse Our Database button is clicked
if (isset($_POST['search-submit'])) {
    // 
    $listingView = true;
    $seeAllListings = $listingObj->getListings();
    // 
    $_SESSION['search'] = '&all-search=true';
    $_SESSION['custom-search'] = [];
    header('Location: ' . ROOT_URL . 'index.php' . '?page=' . '1' . $_SESSION['search']);
    // 
}
// 
// when Browse Our Database button is clicked
if (isset($_GET['all-search'])) {
    // 
    // Get Current Page #
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
    // 
    // Check if records-limit was changed
    if (isset($_POST['records-limit'])) {
        // 
        $_SESSION['records-limit'] = $_POST['records-limit'];
        header('Location: ' . ROOT_URL . 'index.php' . '?page=' . $page . $_SESSION['search']);
        // 
    }
    // 
    $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 3;
    $paginationStart = ($page - 1) * $limit;
    // 
    $allRows = $listingObj->setListingLimit();
    $totalPages = ceil($allRows['allRows'] / $limit);
    // 
    $realEstateListings = $listingObj->getListingsWithLimit($paginationStart, intval($limit));
    // 
    $prev = $page - 1;
    $next = $page + 1;
    // 
} elseif (isset($_GET['custom-search'])) {
    // 
    if (empty($_SESSION['errors'])) {
        // 
        // Get Current Page #
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        // 
        // Check if records-limit was changed
        if (isset($_POST['records-limit'])) {
            // 
            $_SESSION['records-limit'] = $_POST['records-limit'];
            header('Location: ' . ROOT_URL . 'index.php' . '?page=' . $page . $_SESSION['search']);
            // 
        }
        // 
        $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 3;
        $paginationStart = ($page - 1) * $limit;
        // 
        $sqlQuery = "SELECT * FROM listings_pdo_oop WHERE 1 = 1";
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
        $realEstateListings = $listingObj->customQuery($sqlQuery);
        $totalPages = ceil(count($realEstateListings) / $limit);
        $sqlQuery .= " ORDER BY created_at DESC LIMIT {$paginationStart}, {$limit}";
        $realEstateListings = $listingObj->customQuery($sqlQuery);
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
// Custom Search Form is submitted
if (isset($_POST['submit'])) {
    // 
    $listingView = true;
    // 
    $_SESSION['search'] = '?custom-search=true';
    $_SESSION['custom-search'] = processPostData();
    // 
    // Check if minPrice > maxPrice
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
    if (isset($_POST['location']) && isset($_POST['minPrice']) && isset($_POST['maxPrice']) && isset($_POST['beds']) && isset($_POST['baths'])) {
        // 
        if (!isset($_SESSION['errors']['minMaxPrice'])) {
            // 
            $_SESSION['errors'] = [];
            $_SESSION['search'] = '&custom-search=true';
            header('Location: ' . ROOT_URL . 'index.php' . '?page=' . '1' . $_SESSION['search']);
            // 
        } else {
            // 
            header('Location: ' . ROOT_URL . 'index.php' . $_SESSION['search']);
            // 
        }
        // 
    } else {
        // 
        if (!isset($_POST['location'])) {
            $_SESSION['errors']['location'] = 'Please specify Location';
        }
        // 
        if (!isset($_POST['minPrice'])) {
            $_SESSION['errors']['minPrice'] = 'Please select Min Price';
        }
        // 
        if (!isset($_POST['maxPrice'])) {
            $_SESSION['errors']['maxPrice'] = 'Please select Max Price';
        }
        // 
        if (!isset($_POST['beds'])) {
            $_SESSION['errors']['beds'] = 'Please specify # of Beds';
        }
        // 
        if (!isset($_POST['baths'])) {
            $_SESSION['errors']['baths'] = 'Please specify # of Baths';
        }
        //
        header('Location: ' . ROOT_URL . 'index.php' . $_SESSION['search']);
        // 
    }
    // 
}

// 
// Recently Added Section
$listings = $listingObj->getListings();
// 
// Recently Viewed Section
if (isset($_SESSION['visitedPages']) && count($_SESSION['visitedPages'])) {
    // 
    $recentlyViewedListings = [];
    // 
    foreach ($_SESSION['visitedPages'] as $id) {
        // 
        $listing = $listingObj->getListingById($id);
        array_push($recentlyViewedListings, $listing);
        // 
    }
    // 
}
// 
// Popular Cities Section
$popularCities = $listingObj->getPopularCities();
// 
// Cities Search Form
$cities = $listingObj->getDistinctCities();
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
            <form id="records-limit-form" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . '1' . $_SESSION['search'] ?>" method="post" class="p-0 m-0 mr-2">
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
            $limit = false;
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
        $limit = true;
        include('templates/listing-card-deck.php');
        ?>

    <?php else : ?>
        <p class="h4 text-dark text-center my-5">No Recently Viewed Listings</p>
    <?php endif; ?>

</div>
<div class="container my-5 pt-3 pb-5 bg-custom-white border border-dark shadow-lg">
    <h2 class="h2 m-3">Popular Cities</h2>
    <?php if ($popularCities) : ?>
    <div class="row row-cols-1 row-cols-md-3 m-0 p-0">
            <?php foreach ($popularCities as $index => $popularCity) : ?>
                <div class="card-deck col m-0 p-0">
                    <div class="card position-relative border-dark bg-custom-white text-white text-center m-0 mx-3 shadow-lg">
                        <div id="popularCitiesCarousel" class="carousel slide carousel-fade position-relative" data-ride="carousel">
                            <div id="showcase-main-heading" class="position-absolute d-flex flex-column justify-content-around align-items-center">
                                <h4 class="display-4 text-white"><?php echo $popularCity['city']; ?></h4>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active" data-interval="<?php echo $dataInterval[array_rand($dataInterval, 1)]; ?>">
                                    <img src="<?php echo 'https://source.unsplash.com/400x400/?city, park'; ?>" class="d-block img-carousel" alt="...">
                                </div>
                                <div class="carousel-item" data-interval="<?php echo $dataInterval[array_rand($dataInterval, 1)]; ?>">
                                    <img src="<?php echo 'https://source.unsplash.com/400x401/?city, kitchen'; ?>" class="d-block img-carousel" alt="...">
                                </div>
                                <div class="carousel-item" data-interval="<?php echo $dataInterval[array_rand($dataInterval, 1)]; ?>">
                                    <img src="<?php echo 'https://source.unsplash.com/401x401/?city, skyscrapper'; ?>" class="d-block img-carousel" alt="...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($index === 2) { break; } ?>
            <?php endforeach; ?>

        </div>
    <?php else : ?>
        <p class="h4 text-dark text-center my-5">No Popular Cities to Display</p>
    <?php endif; ?>
</div>
<div class="container my-5 pt-3 pb-5 bg-custom-secondary border border-dark shadow-lg">
    <h2 class="h2 m-3">Recently Added</h2>

    <?php if ($listings) : ?>
        <?php
        $array = $listings;
        $limit = true;
        include('templates/listing-card-deck.php');
        ?>
    <?php else : ?>
        <p class="h4 text-dark text-center my-5">No Recently Added Listings</p>
    <?php endif; ?>

</div>

<?php endif; ?>

<?php include('templates/showcase-footer.php'); ?>
<?php include('templates/footer.php'); ?>

</html>