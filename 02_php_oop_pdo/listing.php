<!-- Listing -->

<?php
// 
session_start();
// 
include('config/config.php');
include('lib/classes.php');
// 
$listingObj = new Listing;
// 
unset($_SESSION['after-delete-listing']);
// 
// Check if Delete button was clicked
if (isset($_POST['submit-delete'])) {
    // 
    $delete = htmlspecialchars($_POST['delete-listing-id']);
    $key = array_search($delete, $_SESSION['visitedPages']);
    array_splice($_SESSION['visitedPages'], $key, 1);
    $listingObj->deleteListingById($delete);
    $_SESSION['after-delete-listing'] = 'Your listing has been deleted';
    // header('Location: ' . ROOT_URL . 'index.php');
    header("refresh:1.5; url=index.php");
    // 
} else {
    // 
    $id = htmlspecialchars($_GET['id']);
    $listing = $listingObj->getListingById($id);
    Listing::manageVisitedListings($id);
    // 
}
// 
// Reset SESSION variable
$_SESSION['errors'] = [];
// 
?>

<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>

<div class="container my-5">
    <div class="card-deck col m-0 p-0">
        <div id="after-delete-listing" class="card border border-dark bg-custom-primary m-0 mx-3 shadow-lg py-5 d-flex flex-column justify-content-center align-items-center text-center <?php echo isset($_SESSION['after-delete-listing']) ? '' : 'hide'; ?>">
            <h1 class="display-5 text-white py-3 px-4 text-center"><?php echo $_SESSION['after-delete-listing']; ?></h1>
        </div>
        <div class="card border border-dark bg-custom-dark text-white m-0 mx-3 shadow-lg <?php echo isset($_SESSION['after-delete-listing']) ? 'hide' : ''; ?>">
            <div class="row m-0 p-0 card-header bg-custom-light">
                <h1 id="listing-heading" class="display-4 m-3 col m-0 p-0 text-dark text-center">Listing #<?php echo $id; ?></h1>
            </div>
            <div class="row p-0 m-0">
                <div class="m-0 p-0 col-12 col-md-4">
                    <?php if ($listing['front_img']) : ?>
                        <div class="front-photo-wrapper">
                            <img src="uploads/<?php echo $listing['front_img']; ?>" class="front-photo rounded" alt="front photo">
                        </div>
                    <?php else : ?>
                        <div class="photo-default d-flex justify-content-center align-items-center m-3 bg-custom-white">
                            <i class="fas fa-home text-dark"></i>
                        </div>
                    <?php endif; ?>
                    <div class="row p-0 px-3 m-0 my-3">
                        <div class="btn-group col-12 row p-0 m-0" role="group" id="listing-buttons">
                            <a href="<?php echo $_SESSION['previous_location']; ?>" class="btn btn-outline-custom-light col-12 col-lg-4">Back</a>
                            <a href="<?php echo ROOT_URL; ?>edit-listing.php?id=<?php echo $listing['id'] ?>" class="btn btn-outline-custom-feature-primary col-12 col-lg-4">Edit</a>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="m-0 col-12 col-lg-4 p-0">
                                <input type="hidden" name="delete-listing-id" value="<?php echo $id ?>">
                                <button type="submit" name="submit-delete" id="delete-listing-id" href="" class="btn btn-outline-custom-primary w-100">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="m-0 p-0 col-12 col-md-8">
                    <div class="card-body p-3">
                        <ul class="nav nav-tabs" id="" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-feature" id="" data-toggle="tab" href="#summary" role="tab">Summary</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-feature" id="" data-toggle="tab" href="#description" role="tab">Description</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="">
                            <div class="tab-pane fade p-3 show active" id="summary" role="tabpanel">
                                <div class="d-flex justify-content-between m-0" id="listing-summary">
                                    <div class="p-0 mr-3">
                                        <h4 class="card-text">Price: <?php echo '$' . number_format($listing['price'], $decimals = 0, $dec_point = ".", $thousands_sep = ","); ?></h4>
                                        <h4 class="card-text">Address: <?php echo $listing['address']; ?></h4>
                                        <h4 class="card-text">City: <?php echo $listing['city']; ?></h4>
                                        <h4 class="card-text">Province: <?php echo $listing['province']; ?></h4>
                                    </div>
                                    <div class="card-text my-2">
                                        <div class="d-flex flex-column justify-content-between align-items-center">
                                            <i class="fas fa-bed fa-2x my-1"></i>
                                            <h5>Beds: <?php echo $listing['beds']; ?></h5>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <i class="fas fa-bath fa-2x my-1"></i>
                                            <h5>Baths: <?php echo $listing['baths']; ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade p-3" id="description" role="tabpanel">
                                <?php echo $listing['description']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-custom-light text-center">
                <small class="text-muted">Added By: <?php echo $listing['author']; ?> on <?php echo $listing['created_at']; ?></small>
            </div>
        </div>
    </div>
</div>

<?php include('templates/showcase-footer.php'); ?>
<?php include('templates/footer.php'); ?>

</html>