<!-- Edit Listing -->

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
$errors = [];
$msg = '';
$author = $price = $address = $city = $province = $beds = $baths = $description = $image = '';
$userInput = false;
$listingImage = true;
// 
if (isset($_POST['submit-edit'])) {
    // 
    $userInput = true;
    $update_listing_id = mysqli_real_escape_string($conn, $_POST['update_listing_id']);
    // 
    if (empty($_POST['author'])) {
        // 
        $errors['author'] = true;
        $msg = 'Please fill out Author field';
        // 
    } else {
        // 
        $author = mysqli_real_escape_string($conn, $_POST['author']);
        // 
        if (!preg_match(
            "/^[a-zA-Z0-9\s]{6,20}$/",
            $author
        )) {
            // 
            $errors['author'] = true;
            $msg = 'Please provide valid Author';
            // 
        }
    }
    // 
    if (empty($_POST['price'])) {
        // 
        $errors['price'] = true;
        $msg = 'Please fill out Price field';
        // 
    } else {
        // 
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        // 
        if (!filter_var($price, FILTER_VALIDATE_FLOAT)) {
            // 
            $errors['price'] = true;
            $msg = 'Please provide valid Price';
            // 
        }
    }
    // 
    if (empty($_POST['address'])) {
        // 
        $errors['address'] = true;
        $msg = 'Please fill out Address field';
        // 
    } else {
        // 
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        // 
    }
    // 
    if (empty($_POST['city'])) {
        // 
        $errors['city'] = true;
        $msg = 'Please fill out City field';
        // 
    } else {
        // 
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        // 
    }
    // 
    if (!isset($_POST['province'])) {
        // 
        $errors['province'] = true;
        $msg = 'Please fill out Province field';
        // 
    } else {
        // 
        $province = mysqli_real_escape_string($conn, $_POST['province']);
        // 
    }
    //
    if (!isset($_POST['beds'])) {
        // 
        $errors['beds'] = true;
        $msg = 'Please specify # of Baths';
        // 
    } else {
        // 
        $beds = mysqli_real_escape_string($conn, $_POST['beds']);
        // 
    }
    //
    if (!isset($_POST['baths'])) {
        // 
        $errors['baths'] = true;
        $msg = 'Please specify # of Baths';
        // 
    } else {
        // 
        $baths = mysqli_real_escape_string($conn, $_POST['baths']);
        // 
    }
    //
    if (empty($_POST['description'])) {
        // 
        $errors['description'] = true;
        $msg = 'Please fill out Description field';
        // 
    } else {
        // 
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        // 
        if (!preg_match(
            "/.{1,300}/",
            $description
        )) {
            // 
            $errors['description'] = true;
            $msg = 'Please provide valid Description';
            // 
        }
    }
    // 
    if (isset($_FILES['image'])) {
        // 
        if ($_FILES['image']['error'] === 4) {
            // 
            $image = $_SESSION['currentFrontImg'];
            // 
        } else {
            // 
            $target = "uploads/" . basename($_FILES['image']['name']);
            $image = $_FILES['image']['name'];
            // 
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                // 
                $imgMsg = "Image uploaded successfully";
                // 
            } else {
                // 
                $imgMsg = "Image not uploaded";
                // 
            }
            // 
        }
        // 
    } else {
        // 
        $image = mysqli_real_escape_string($conn, "");
        // 
    }
    // 
    if (!empty($_POST['author']) && !empty($_POST['price']) && !empty($_POST['address']) && !empty($_POST['city']) && isset($_POST['province']) && isset($_POST['beds']) && isset($_POST['baths']) && !empty($_POST['description']) && empty($errors)) {
        // 
        $sql = "UPDATE real_estate_listings SET price = '$price', address = '$address', city = '$city', province = '$province', beds = '$beds', baths = '$baths', front_img = '$image', description = '$description', author = '$author' WHERE id = {$update_listing_id}";
        // 
        if (mysqli_query($conn, $sql)) {
            // 
            header('Location: ' . ROOT_URL);
            // 
        } else {
            // 
            echo 'ERROR: ' . mysqli_error($conn);
            // 
        }
        // 
    }
    // 
} else {
    // 
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM real_estate_listings WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $listing = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    // 
    $_SESSION['currentFrontImg'] = mysqli_real_escape_string($conn, $listing['front_img']);
    // 
    mysqli_close($conn);
    // 
}
// 
$_SESSION['errors'] = [];
$_SESSION['msg'] = '';
// 
?>

<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>

<div class="container">
    <div class="jumbotron bg-custom-light my-5">
        <h2 class="display-4 mb-n3">Edit Listing</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <div class="d-flex justify-content-center w-50 m-auto p-0 <?php echo count($errors) ? 'visible' : 'not-visible'; ?>">
                <div class="alert alert-danger p-3 m-0 mb-2" role="alert">
                    <?php echo count($errors) >= 2 ? "Multiple fields not specified" : "{$msg}"; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6">
                    <label for="author">Author</label>
                    <i class="far fa-frown <?php echo $errors['author'] === true ? 'invalid' : 'valid'; ?>"></i>
                    <input type="text" class="form-control" id="author" name="author" value="<?php echo $userInput ? htmlspecialchars($author) : $listing['author']; ?>">
                    <small id="" class="form-text text-red <?php echo $errors['author'] === true ? '' : 'hide'; ?>">
                        Author field must be 6-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                    </small>
                </div>
                <div class="form-group col-12 col-sm-6">
                    <label for="price">Price (in CAD)</label>
                    <i class="far fa-frown <?php echo $errors['price'] === true ? 'invalid' : 'valid'; ?>"></i>
                    <input type="text" class="form-control" id="price" name="price" value="<?php echo $userInput ? htmlspecialchars($price) : $listing['price']; ?>">
                    <small id="" class="form-text text-red <?php echo $errors['price'] === true ? '' : 'hide'; ?>">
                        Price field must be a number.
                    </small>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <i class="far fa-frown <?php echo $errors['address'] === true ? 'invalid' : 'valid'; ?>"></i>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $userInput ? htmlspecialchars($address) : $listing['address']; ?>">
                <small id="" class="form-text text-red <?php echo $errors['address'] === true ? '' : 'hide'; ?>">
                    Address field must a valid Canadian address.
                </small>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-md-6">
                    <label for="city">City</label>
                    <i class="far fa-frown <?php echo $errors['city'] === true ? 'invalid' : 'valid'; ?>"></i>
                    <input type="text" class="form-control" id="city" name="city" value="<?php echo $userInput ? htmlspecialchars($city) : $listing['city']; ?>">
                    <small id="" class="form-text text-red <?php echo $errors['city'] === true ? '' : 'hide'; ?>">
                        City field must be a valid Canadian city.
                    </small>
                </div>
                <div class="form-group col-12 col-md-6 position-relative select-input-add-listing">
                    <label for="province">Province</label>
                    <i class="far fa-frown <?php echo $errors['province'] === true ? 'invalid' : 'valid'; ?>"></i>
                    <select class="form-control select-add-listing" id="province" name="province">
                        <option disabled selected>Choose</option>
                        <option value="Alberta" <?php echo $userInput ? markSelected('province', 'Alberta') : ($listing['province'] === 'Alberta' ? 'selected' : ''); ?>>Alberta</option>
                        <option value="British Columbia" <?php echo $userInput ? markSelected('province', 'British Columbia') : ($listing['province'] === 'British Columbia' ? 'selected' : ''); ?>>British Columbia</option>
                        <option value="Manitoba" <?php echo $userInput ? markSelected('province', 'Manitoba') : ($listing['province'] === 'Manitoba' ? 'selected' : ''); ?>>Manitoba</option>
                        <option value="New Brunswick" <?php echo $userInput ? markSelected('province', 'New Brunswick') : ($listing['province'] === 'New Brunswick' ? 'selected' : ''); ?>>New Brunswick</option>
                        <option value="Newfoundland and Labrador" <?php echo $userInput ? markSelected('province', 'Newfoundland and Labrador') : ($listing['province'] === 'Newfoundland and Labrador' ? 'selected' : ''); ?>>Newfoundland and Labrador</option>
                        <option value="Northwest Territories" <?php echo $userInput ? markSelected('province', 'Northwest Territories') : ($listing['province'] === 'Northwest Territories' ? 'selected' : ''); ?>>Northwest Territories</option>
                        <option value="Nova Scotia" <?php echo $userInput ? markSelected('province', 'Nova Scotia') : ($listing['province'] === 'Nova Scotia' ? 'selected' : ''); ?>>Nova Scotia</option>
                        <option value="Nunavut" <?php echo $userInput ? markSelected('province', 'Nunavut') : ($listing['province'] === 'Nunavut' ? 'selected' : ''); ?>>Nunavut</option>
                        <option value="Ontario" <?php echo $userInput ? markSelected('province', 'Ontario') : ($listing['province'] === 'Ontario' ? 'selected' : ''); ?>>Ontario</option>
                        <option value="Prince Edward Island" <?php echo $userInput ? markSelected('province', 'Prince Edward Island') : ($listing['province'] === 'Prince Edward Island' ? 'selected' : ''); ?>>Prince Edward Island</option>
                        <option value="Quebec" <?php echo $userInput ? markSelected('province', 'Quebec') : ($listing['province'] === 'Quebec' ? 'selected' : ''); ?>>Quebec</option>
                        <option value="Saskatchewan" <?php echo $userInput ? markSelected('province', 'Saskatchewan') : ($listing['province'] === 'Saskatchewan' ? 'selected' : ''); ?>>Saskatchewan</option>
                        <option value="Yukon" <?php echo $userInput ? markSelected('province', 'Yukon') : ($listing['province'] === 'Yukon' ? 'selected' : ''); ?>>Yukon</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-sm-6 position-relative select-input-add-listing">
                    <label for="beds">Beds</label>
                    <i class="far fa-frown <?php echo $errors['beds'] === true ? 'invalid' : 'valid'; ?>"></i>
                    <select class="form-control select-add-listing" id="beds" name="beds">
                        <option disabled selected>Choose</option>
                        <option value="1" <?php echo $userInput ? markSelected('beds', '1') : ($listing['beds'] === '1' ? 'selected' : ''); ?>>1</option>
                        <option value="1+" <?php echo $userInput ? markSelected('beds', '1+') : ($listing['beds'] === '1+' ? 'selected' : ''); ?>>1+</option>
                        <option value="2" <?php echo $userInput ? markSelected('beds', '2') : ($listing['beds'] === '2' ? 'selected' : ''); ?>>2</option>
                        <option value="2+" <?php echo $userInput ? markSelected('beds', '2+') : ($listing['beds'] === '2+' ? 'selected' : ''); ?>>2+</option>
                        <option value="3" <?php echo $userInput ? markSelected('beds', '3') : ($listing['beds'] === '3' ? 'selected' : ''); ?>>3</option>
                        <option value="3+" <?php echo $userInput ? markSelected('beds', '3+') : ($listing['beds'] === '3+' ? 'selected' : ''); ?>>3+</option>
                        <option value="4" <?php echo $userInput ? markSelected('beds', '4') : ($listing['beds'] === '4' ? 'selected' : ''); ?>>4</option>
                        <option value="4+" <?php echo $userInput ? markSelected('beds', '4+') : ($listing['beds'] === '4+' ? 'selected' : ''); ?>>4+</option>
                        <option value="5" <?php echo $userInput ? markSelected('beds', '5') : ($listing['beds'] === '5' ? 'selected' : ''); ?>>5</option>
                        <option value="5+" <?php echo $userInput ? markSelected('beds', '5+') : ($listing['beds'] === '5+' ? 'selected' : ''); ?>>5+</option>
                    </select>
                </div>
                <div class="form-group col-12 col-sm-6 position-relative select-input-add-listing">
                    <label for="baths">Baths</label>
                    <i class="far fa-frown <?php echo $errors['baths'] === true ? 'invalid' : 'valid'; ?>"></i>
                    <select class="form-control select-add-listing" id="baths" name="baths">
                        <option disabled selected>Choose</option>
                        <option value="1" <?php echo $userInput ? markSelected('baths', '1') : ($listing['baths'] === '1' ? 'selected' : ''); ?>>1</option>
                        <option value="1+" <?php echo $userInput ? markSelected('baths', '1+') : ($listing['baths'] === '1+' ? 'selected' : ''); ?>>1+</option>
                        <option value="2" <?php echo $userInput ? markSelected('baths', '2') : ($listing['baths'] === '2' ? 'selected' : ''); ?>>2</option>
                        <option value="2+" <?php echo $userInput ? markSelected('baths', '2+') : ($listing['baths'] === '2+' ? 'selected' : ''); ?>>2+</option>
                        <option value="3" <?php echo $userInput ? markSelected('baths', '3') : ($listing['baths'] === '3' ? 'selected' : ''); ?>>3</option>
                        <option value="3+" <?php echo $userInput ? markSelected('baths', '3+') : ($listing['baths'] === '3+' ? 'selected' : ''); ?>>3+</option>
                        <option value="4" <?php echo $userInput ? markSelected('baths', '4') : ($listing['baths'] === '4' ? 'selected' : ''); ?>>4</option>
                        <option value="4+" <?php echo $userInput ? markSelected('baths', '4+') : ($listing['baths'] === '4+' ? 'selected' : ''); ?>>4+</option>
                        <option value="5" <?php echo $userInput ? markSelected('baths', '5') : ($listing['baths'] === '5' ? 'selected' : ''); ?>>5</option>
                        <option value="5+" <?php echo $userInput ? markSelected('baths', '5+') : ($listing['baths'] === '5+' ? 'selected' : ''); ?>>5+</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Listing Description</label>
                <i class="far fa-frown <?php echo $errors['description'] === true ? 'invalid' : 'valid'; ?>"></i>
                <textarea class="form-control" id="description" rows="5" name="description"><?php echo $userInput ? htmlspecialchars($description) : $listing['description']; ?></textarea>
                <small id="" class="form-text text-red <?php echo $errors['description'] === true ? '' : 'hide'; ?>">
                    Please provide a brief description (up to 300 characters) of your listing.
                </small>
            </div>
            <div class="input-file-wrapper">
                <input type="file" class="input-file" id="image" name="image">
                <label class="input-file-label" for="image"><?php echo $listing['front_img'] ? $listing['front_img'] : "Choose front image"; ?></label>
            </div>
            <input type="hidden" name="update_listing_id" id="" value="<?php echo $userInput ? $update_listing_id : $listing['id']; ?>">
            <button type="submit" class="btn btn-lg btn-outline-custom-dark" name="submit-edit">Submit</button>
        </form>
    </div>
</div>

<?php include('templates/footer.php'); ?>

</html>