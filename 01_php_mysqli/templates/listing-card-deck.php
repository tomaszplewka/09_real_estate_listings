<!-- Listing Card Deck -->

<div class="row row-cols-1 row-cols-md-3 m-0 p-0">
    <?php foreach ($array as $realEstateListing) : ?>
        <div class="card-deck col m-0 p-0">
            <div class="card position-relative border-dark bg-custom-dark text-white text-center m-3 shadow-lg">
                <?php if ($realEstateListing['front_img']) : ?>
                    <div class="front-photo-wrapper">
                        <img src="uploads/<?php echo $realEstateListing['front_img']; ?>" class="front-photo rounded" alt="front photo">
                    </div>
                <?php else : ?>
                    <div class="photo-default d-flex justify-content-center align-items-center bg-custom-white">
                        <i class="fas fa-home text-dark"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo '$' . number_format($realEstateListing['price'], $decimals = 0, $dec_point = ".", $thousands_sep = ","); ?></h5>
                    <p class="card-text"><?php echo $realEstateListing['address'] . ', ' . $realEstateListing['city'] . ', ' . $realEstateListing['province']; ?></p>
                    <div class="card-text d-flex justify-content-around my-2">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <i class="fas fa-bed fa-2x my-1"></i>
                            <p>Beds: <?php echo $realEstateListing['beds']; ?></p>
                        </div>
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <i class="fas fa-bath fa-2x my-1"></i>
                            <p>Baths: <?php echo $realEstateListing['baths']; ?></p>
                        </div>
                    </div>
                    <a href="<?php $_SESSION['previous_location'] = $_SERVER["REQUEST_URI"]; ?><?php echo ROOT_URL . 'listing.php'; ?>?id=<?php echo $realEstateListing['id'] ?>" class="stretched-link btn btn-outline-custom-light">View Listing</a>
                </div>
                <div class="card-footer bg-custom-light">
                    <small class="text-muted">Added By: <?php echo $realEstateListing['author']; ?></small>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>