<!-- Search Form -->

<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="showcase-search-form" class="position-relative <?php echo $formVisible ? 'visible' : ''; ?> <?php echo $listingView ? 'listing-view p-3' : 'house-shape'; ?> mb-0">
    <div class="<?php echo $formVisible ? '' : 'hide'; ?>" id="search-form">
        <div class="d-flex justify-content-center m-auto p-0 <?php echo count($_SESSION['errors']) ? 'visible' : 'not-visible hide'; ?>">
            <div class="alert alert-danger py-1 px-2 m-0 mb-3 mx-2 d-inline-block <?php echo count($_SESSION['errors']) === 1 && isset($_SESSION['errors']['minMaxPrice']) ? 'not-visible hide' : 'visible'; ?>" role="alert">
                <?php echo count($_SESSION['errors']) >= 2 ? "Please specify all fields below" : "{$_SESSION['msg']}"; ?>
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
                    <option value="0" <?php echo isset($_SESSION['custom-search']['minPrice']) && $_SESSION['custom-search']['minPrice'] === 0. ? 'selected' : ''; ?>>0</option>
                    <option value="100,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 100000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>100,000</option>
                    <option value="200,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 200000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>200,000</option>
                    <option value="300,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 300000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>300,000</option>
                    <option value="400,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 400000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>400,000</option>
                    <option value="500,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 500000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>500,000</option>
                    <option value="600,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 600000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>600,000</option>
                    <option value="700,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 700000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>700,000</option>
                    <option value="800,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 800000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>800,000</option>
                    <option value="900,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 900000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>900,000</option>
                    <option value="1,000,000" <?php echo isset($_SESSION['custom-search']['minPrice']) && 1000000. === $_SESSION['custom-search']['minPrice'] ? 'selected' : ''; ?>>1,000,000</option>
                </select>
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-2 p-0 m-0 position-relative select-input">
                <select class="form-control" id="maxPriceSelect" name="maxPrice">
                    <option disabled selected>Max Price</option>
                    <option value="Unlimited" <?php echo isset($_SESSION['custom-search']['maxPrice']) && $_SESSION['custom-search']['maxPrice'] === 'Unlimited' ? 'selected' : ''; ?>>Unlimited</option>
                    <option value="100,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 100000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>100,000</option>
                    <option value="200,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 200000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>200,000</option>
                    <option value="300,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 300000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>300,000</option>
                    <option value="400,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 400000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>400,000</option>
                    <option value="500,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 500000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>500,000</option>
                    <option value="600,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 600000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>600,000</option>
                    <option value="700,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 700000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>700,000</option>
                    <option value="800,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 800000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>800,000</option>
                    <option value="900,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 900000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>900,000</option>
                    <option value="1,000,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 1000000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>1,000,000</option>
                    <option value="1,500,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 1500000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>1,500,000</option>
                    <option value="2,000,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 2000000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>2,000,000</option>
                    <option value="2,500,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 2500000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>2,500,000</option>
                    <option value="5,000,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 5000000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>5,000,000</option>
                    <option value="10,000,000" <?php echo isset($_SESSION['custom-search']['maxPrice']) && 10000000. === $_SESSION['custom-search']['maxPrice'] ? 'selected' : ''; ?>>10,000,000</option>
                </select>
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-2 p-0 m-0 position-relative select-input">
                <select class="form-control" id="" name="beds">
                    <option disabled selected>Beds</option>
                    <option value="Any" <?php echo isset($_SESSION['custom-search']['beds']) && $_SESSION['custom-search']['beds'] === 'Any' ? 'selected' : ''; ?>>Any</option>
                    <option value="1" <?php echo isset($_SESSION['custom-search']['beds']) && '1' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>1</option>
                    <option value="1+" <?php echo isset($_SESSION['custom-search']['beds']) && '1+' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>1+</option>
                    <option value="2" <?php echo isset($_SESSION['custom-search']['beds']) && '2' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>2</option>
                    <option value="2+" <?php echo isset($_SESSION['custom-search']['beds']) && '2+' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>2+</option>
                    <option value="3" <?php echo isset($_SESSION['custom-search']['beds']) && '3' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>3</option>
                    <option value="3+" <?php echo isset($_SESSION['custom-search']['beds']) && '3+' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>3+</option>
                    <option value="4" <?php echo isset($_SESSION['custom-search']['beds']) && '4' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>4</option>
                    <option value="4+" <?php echo isset($_SESSION['custom-search']['beds']) && '4+' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>4+</option>
                    <option value="5" <?php echo isset($_SESSION['custom-search']['beds']) && '5' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>5</option>
                    <option value="5+" <?php echo isset($_SESSION['custom-search']['beds']) && '5+' === $_SESSION['custom-search']['beds'] ? 'selected' : ''; ?>>5+</option>
                </select>
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-2 p-0 m-0 position-relative select-input">
                <select class="form-control" id="" name="baths">
                    <option disabled selected>Baths</option>
                    <option value="Any" <?php echo isset($_SESSION['custom-search']['baths']) && $_SESSION['custom-search']['baths'] === 'Any' ? 'selected' : ''; ?>>Any</option>
                    <option value="1" <?php echo isset($_SESSION['custom-search']['baths']) && '1' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>1</option>
                    <option value="1+" <?php echo isset($_SESSION['custom-search']['baths']) && '1+' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>1+</option>
                    <option value="2" <?php echo isset($_SESSION['custom-search']['baths']) && '2' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>2</option>
                    <option value="2+" <?php echo isset($_SESSION['custom-search']['baths']) && '2+' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>2+</option>
                    <option value="3" <?php echo isset($_SESSION['custom-search']['baths']) && '3' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>3</option>
                    <option value="3+" <?php echo isset($_SESSION['custom-search']['baths']) && '3+' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>3+</option>
                    <option value="4" <?php echo isset($_SESSION['custom-search']['baths']) && '4' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>4</option>
                    <option value="4+" <?php echo isset($_SESSION['custom-search']['baths']) && '4+' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>4+</option>
                    <option value="5" <?php echo isset($_SESSION['custom-search']['baths']) && '5' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>5</option>
                    <option value="5+" <?php echo isset($_SESSION['custom-search']['baths']) && '5+' === $_SESSION['custom-search']['baths'] ? 'selected' : ''; ?>>5+</option>
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