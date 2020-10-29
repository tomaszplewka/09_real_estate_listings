<!-- Showcase Carousel -->

<div id="showcaseCarousel" class="carousel slide carousel-fade position-relative" data-ride="carousel">
    <div id="showcase-main-heading" class="position-absolute d-flex flex-column justify-content-around align-items-center">
        <h1 class="display-3 text-white">Find Your Dream Home</h1>
        <div id="search-listings" class="">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <button type="submit" name="search-submit" class="btn btn-outline-custom-white" id="search-btn">Browse Our Database<i class="fas fa-chevron-right ml-2"></i></button>
            </form>
        </div>
        <?php //include('showcase-search-form.php'); ?>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active" data-interval="3000">
            <img src="img/index-showcase-3-md.jpg" class="d-block img-carousel" alt="...">
        </div>
        <div class="carousel-item" data-interval="3000">
            <img src="img/index-showcase-1-md.jpg" class="d-block img-carousel" alt="...">
        </div>
        <div class="carousel-item" data-interval="3000">
            <img src="img/index-showcase-2-md.jpg" class="d-block img-carousel" alt="...">
        </div>
    </div>
</div>