<!-- Edit Listing -->

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
$errors = $data = [];
$imgMsg = '';
$userInput = false;
$listingImage = true;
unset($_SESSION['after-edit-listing']);
// 
if (isset($_POST['submit-edit'])) {
    // 
    $userInput = true;
    $update_listing_id = htmlspecialchars($_POST['update_listing_id']);
    // 
    $data['author'] = htmlspecialchars($_POST['author']);
    if (empty($_POST['author']) || !preg_match("/^(\w+( \w+)*){6,20}$/", $data['author'])) {
        $errors['author'] = 'Please provide valid Author';
    }
    // 
    $data['price'] = htmlspecialchars($_POST['price']);
    if (empty($_POST['price']) || !filter_var($data['price'], FILTER_VALIDATE_FLOAT)) {
        $errors['price'] = 'Please provide valid Price';
    }
    // 
    $data['address'] = htmlspecialchars($_POST['address']);
    if (empty($_POST['address'])) {
        $errors['address'] = 'Please fill out Address field';
    }
    // 
    $data['city'] = htmlspecialchars($_POST['city']);
    if (empty($_POST['city'])) {
        $errors['city'] = 'Please fill out City field';
    }
    // 
    if (!isset($_POST['province'])) {
        $errors['province'] = 'Please fill out Province field';
    } else {
        $data['province'] = htmlspecialchars($_POST['province']);
    }
    // 
    if (!isset($_POST['beds'])) {
        $errors['beds'] = 'Please specify # of Baths';
    } else {
        $data['beds'] = htmlspecialchars($_POST['beds']);
    }
    // 
    if (!isset($_POST['baths'])) {
        $errors['baths'] = 'Please specify # of Baths';
    } else {
        $data['baths'] = htmlspecialchars($_POST['baths']);
    }
    // 
    $data['description'] = htmlspecialchars($_POST['description']);
    if (empty($_POST['description']) || !preg_match("/^.{1,300}$/", $data['description'])) {
        $errors['description'] = 'Please provide valid Description';
    }
    // 
    if (isset($_FILES['image'])) {
        // 
        if ($_FILES['image']['error'] === 4) {
            // 
            $data['front_img'] = $_SESSION['currentFrontImg'];
            // 
        } else {
            // 
            $target = "uploads/" . basename($_FILES['image']['name']);
            $data['front_img'] = $_FILES['image']['name'];
            // 
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imgMsg = "Image uploaded successfully";
            } else {
                $imgMsg = "Image not uploaded";
            }
            // 
        }
        // 
    } else {
        $data['front_img'] = "";
    }
    // 
    if (!empty($_POST['author']) && !empty($_POST['price']) && !empty($_POST['address']) && !empty($_POST['city']) && isset($_POST['province']) && isset($_POST['beds']) && isset($_POST['baths']) && !empty($_POST['description']) && empty($errors)) {
        // 
        if ($listingObj->updateData($data, $update_listing_id)) {
            $_SESSION['after-edit-listing'] = 'Your listing has been updated';
            // header('Location: ' . ROOT_URL . 'index.php');
            header("refresh:1.5; url=index.php");
        } else {
            $_SESSION['after-edit-listing'] = 'Something went wrong';
        }
        // 
    }
    // 
} else {
    // 
    $id = htmlspecialchars($_GET['id']);
    $listing = $listingObj->getListingById($id);
    // 
    $_SESSION['currentFrontImg'] = htmlspecialchars($listing['front_img']);
    // 
}
// 
$_SESSION['errors'] = [];
// 
?>

<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>

<div class="container">
    <div class="jumbotron bg-custom-light my-5 border border-dark shadow-lg">
        <div id="after-edit-listing" class="d-flex flex-column justify-content-center align-items-center <?php echo isset($_SESSION['after-edit-listing']) ? '' : 'hide'; ?>">
            <h1 class="display-5 text-dark py-3 text-center"><?php echo $_SESSION['after-edit-listing']; ?></h1>
        </div>
        <h2 class="display-4 <?php echo isset($_SESSION['after-edit-listing']) ? 'hide' : ''; ?>">Edit Listing</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" class="<?php echo isset($_SESSION['after-edit-listing']) ? 'hide' : ''; ?>">
            <div class="d-flex justify-content-center w-50 m-auto p-0 <?php echo count($errors) ? 'visible' : 'not-visible'; ?>">
                <div class="alert alert-danger p-3 m-0 mb-2" role="alert">
                    <?php
                        if (isset($_POST['submit-edit'])) {
                            echo count($errors) >= 2 ? "Multiple fields not specified" : (!count($errors) ? '' : $errors[array_key_first($errors)]);
                        }
                    ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6">
                    <label for="author">Author</label>
                    <i class="far fa-frown <?php echo $errors['author'] ? 'invalid' : 'valid'; ?>"></i>
                    <input type="text" class="form-control" id="author" name="author" value="<?php echo $userInput ? htmlspecialchars($data['author']) : $listing['author']; ?>">
                    <small id="" class="form-text text-red <?php echo $errors['author'] ? '' : 'hide'; ?>">
                        Author field must be 6-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                    </small>
                </div>
                <div class="form-group col-12 col-sm-6">
                    <label for="price">Price (in CAD)</label>
                    <i class="far fa-frown <?php echo $errors['price'] ? 'invalid' : 'valid'; ?>"></i>
                    <input type="text" class="form-control" id="price" name="price" value="<?php echo $userInput ? htmlspecialchars($data['price']) : $listing['price']; ?>">
                    <small id="" class="form-text text-red <?php echo $errors['price'] ? '' : 'hide'; ?>">
                        Price field must be a number.
                    </small>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <i class="far fa-frown <?php echo $errors['address'] ? 'invalid' : 'valid'; ?>"></i>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $userInput ? htmlspecialchars($data['address']) : $listing['address']; ?>">
                <small id="" class="form-text text-red <?php echo $errors['address'] ? '' : 'hide'; ?>">
                    Address field must a valid Canadian address.
                </small>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-md-6">
                    <label for="city">City</label>
                    <i class="far fa-frown <?php echo $errors['city'] ? 'invalid' : 'valid'; ?>"></i>
                    <input type="text" class="form-control" id="city" name="city" value="<?php echo $userInput ? htmlspecialchars($data['city']) : $listing['city']; ?>">
                    <small id="" class="form-text text-red <?php echo $errors['city'] ? '' : 'hide'; ?>">
                        City field must be a valid Canadian city.
                    </small>
                </div>
                <div class="form-group col-12 col-md-6 position-relative select-input-add-listing">
                    <label for="province">Province</label>
                    <i class="far fa-frown <?php echo $errors['province'] ? 'invalid' : 'valid'; ?>"></i>
                    <select class="form-control select-add-listing" id="province" name="province">
                        <option disabled selected>Choose</option>
                        <?php foreach (["Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland and Labrador", "Northwest Territories", "Nova Scotia", "Nunavut", "Ontario", "Prince Edward Island", "Quebec", "Saskatchewan", "Yukon"] as $province) : ?>
                            <option value="<?php echo $province ?>" <?php echo $userInput ? markSelected('province', $province) : ($listing['province'] === $province ? 'selected' : ''); ?>><?php echo $province ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 position-relative select-input-add-listing">
                    <label for="beds">Beds</label>
                    <i class="far fa-frown <?php echo $errors['beds'] ? 'invalid' : 'valid'; ?>"></i>
                    <select class="form-control select-add-listing" id="beds" name="beds">
                        <option disabled selected>Choose</option>
                        <?php foreach (['1', '1+', '2', '2+', '3', '3+', '4', '4+', '5', '5+'] as $beds) : ?>
                            <option value="<?php echo $beds; ?>" <?php echo $userInput ? markSelected('beds', $beds) : ($listing['beds'] === $beds ? 'selected' : ''); ?>><?php echo $beds; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-12 col-sm-6 position-relative select-input-add-listing">
                    <label for="baths">Baths</label>
                    <i class="far fa-frown <?php echo $errors['baths'] ? 'invalid' : 'valid'; ?>"></i>
                    <select class="form-control select-add-listing" id="baths" name="baths">
                        <option disabled selected>Choose</option>
                        <?php foreach (['1', '1+', '2', '2+', '3', '3+', '4', '4+', '5', '5+'] as $baths) : ?>
                            <option value="<?php echo $baths; ?>" <?php echo $userInput ? markSelected('baths', $baths) : ($listing['baths'] === $baths ? 'selected' : ''); ?>><?php echo $baths; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Listing Description</label>
                <i class="far fa-frown <?php echo $errors['description'] ? 'invalid' : 'valid'; ?>"></i>
                <textarea class="form-control" id="description" rows="5" name="description"><?php echo $userInput ? htmlspecialchars($data['description']) : $listing['description']; ?></textarea>
                <small id="" class="form-text text-red <?php echo $errors['description'] ? '' : 'hide'; ?>">
                    Please provide a brief description (up to 300 characters) of your listing.
                </small>
            </div>
            <div class="input-file-wrapper">
                <input type="file" class="input-file" id="image" name="image">
                <label class="input-file-label" for="image"><?php echo isset($listing['front_img']) ? $listing['front_img'] : $data['front_img']; ?></label>
            </div>
            <input type="hidden" name="update_listing_id" id="" value="<?php echo $userInput ? $update_listing_id : $listing['id']; ?>">
            <button type="submit" class="btn btn-lg btn-outline-custom-dark" name="submit-edit">Submit</button>
        </form>
    </div>
</div>

<?php include('templates/showcase-footer.php'); ?>
<?php include('templates/footer.php'); ?>

</html>