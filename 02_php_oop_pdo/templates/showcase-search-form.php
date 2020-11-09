<!-- Search Form -->

<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="showcase-search-form" class="position-relative <?php echo $listingView ? 'visible listing-view p-3' : 'hide'; ?> mb-0">
    <div id="search-form">
        <div class="d-flex justify-content-center m-auto p-0 <?php echo count($_SESSION['errors']) ? 'visible' : 'not-visible hide'; ?>">
            <div class="alert alert-danger py-1 px-2 m-0 mb-3 mx-2 d-inline-block <?php echo count($_SESSION['errors']) === 1 && isset($_SESSION['errors']['minMaxPrice']) ? 'not-visible hide' : 'visible'; ?>" role="alert">
                <?php
                    if (count($_SESSION['errors']) >= 2 && !isset($_SESSION['errors']['minMaxPrice'])) {
                        echo "Please specify all fields below";
                    } elseif (count($_SESSION['errors']) === 2 && isset($_SESSION['errors']['minMaxPrice'])) {
                        echo $_SESSION['errors'][array_key_last($_SESSION['errors'])];
                    } else {
                        echo $_SESSION['errors'][array_key_first($_SESSION['errors'])];
                    }
                ?>
            </div>
            <div class="alert alert-danger py-1 px-2 m-0 mb-3 mx-2 d-inline-block <?php echo isset($_SESSION['errors']['minMaxPrice']) ? 'visible' : 'not-visible hide'; ?>" role="alert">
                <?php echo isset($_SESSION['errors']['minMaxPrice']) ? $_SESSION['errors']['minMaxPrice'] : ""; ?>
            </div>
        </div>
        <div class="row p-0 m-0">
            <div class="form-group col-12 col-lg-3 <?php  ?> p-0 m-0 position-relative select-input">
                <select class="form-control" id="" name="location">
                    <option disabled selected value="Choose Location">Location</option>
                    <option value="Any" <?php echo isset($_SESSION['custom-search']['location']) && $_SESSION['custom-search']['location'] === 'Any' ? 'selected' : ''; ?>>Any</option>
                    <?php foreach ($cities as $city) : ?>
                        <option value="<?php echo $city['city'] ?>" <?php echo isset($_SESSION['custom-search']['location']) && $city['city'] === $_SESSION['custom-search']['location'] ? 'selected' : ''; ?>><?php echo $city['city'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-2 p-0 m-0 position-relative select-input">
                <select class="form-control" id="minPriceSelect" name="minPrice">
                    <option disabled selected>Min Price</option>
                    <?php foreach ([0., 100000., 200000., 300000., 400000., 500000., 600000., 700000., 800000., 900000., 1000000.] as $minPrice) : ?>
                        <option value="<?php echo number_format($minPrice, $decimals = 0, $dec_point = ".", $thousands_sep = ",") ?>" <?php echo isset($_SESSION['custom-search']['minPrice']) && $_SESSION['custom-search']['minPrice'] === $minPrice ? 'selected' : ''; ?>><?php echo number_format($minPrice, $decimals = 0, $dec_point = ".", $thousands_sep = ",") ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-2 p-0 m-0 position-relative select-input">
                <select class="form-control" id="maxPriceSelect" name="maxPrice">
                    <option disabled selected>Max Price</option>
                    <option value="Unlimited" <?php echo isset($_SESSION['custom-search']['maxPrice']) && $_SESSION['custom-search']['maxPrice'] === 'Unlimited' ? 'selected' : ''; ?>>Unlimited</option>
                    <?php foreach ([0., 100000., 200000., 300000., 400000., 500000., 600000., 700000., 800000., 900000., 1000000., 1500000., 2000000., 2500000., 5000000., 10000000.] as $maxPrice) : ?>
                        <option value="<?php echo number_format($maxPrice, $decimals = 0, $dec_point = ".", $thousands_sep = ",") ?>" <?php echo isset($_SESSION['custom-search']['maxPrice']) && $_SESSION['custom-search']['maxPrice'] === $maxPrice ? 'selected' : ''; ?>><?php echo number_format($maxPrice, $decimals = 0, $dec_point = ".", $thousands_sep = ",") ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-2 p-0 m-0 position-relative select-input">
                <select class="form-control" id="" name="beds">
                    <option disabled selected>Beds</option>
                    <?php foreach (['Any', '1', '1+', '2', '2+', '3', '3+', '4', '4+', '5', '5+'] as $beds) : ?>
                        <option value="<?php echo $beds; ?>" <?php echo isset($_SESSION['custom-search']['beds']) && $_SESSION['custom-search']['beds'] === $beds ? 'selected' : ''; ?>><?php echo $beds; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-2 p-0 m-0 position-relative select-input">
                <select class="form-control" id="" name="baths">
                    <option disabled selected>Baths</option>
                    <?php foreach (['Any', '1', '1+', '2', '2+', '3', '3+', '4', '4+', '5', '5+'] as $baths) : ?>
                        <option value="<?php echo $baths; ?>" <?php echo isset($_SESSION['custom-search']['baths']) && $_SESSION['custom-search']['baths'] === $baths ? 'selected' : ''; ?>><?php echo $baths; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-none d-lg-flex col-12 col-lg-1 p-0 m-0 justify-content-center btn-group" role="group">
                <button class="btn btn-custom-feature-primary rounded-0" type="submit" name="submit" id="showcase-form-submit-btn-1">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="d-flex d-lg-none col-12 col-lg-2 p-0 m-0 justify-content-center btn-group" role="group">
                <button class="btn btn-custom-feature-primary rounded-0" type="submit" name="submit" id="showcase-form-submit-btn-2">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
            </div>
        </div>
    </div>
</form>