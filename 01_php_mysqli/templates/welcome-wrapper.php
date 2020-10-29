<!-- welcome wrapper -->

<?php
setcookie('welcomeWrapper', 'nope', time() + 1800);
?>
<div class="bg-custom-dark vw-100 vh-100 d-flex flex-column justify-content-center align-items-center overflow-hidden position-absolute" id="welcome-main-wrapper">
    <div id="welcome-heading" class="bg-custom-light p-4 rounded-lg m-3">
        <h1 class="display text-center">Welcome to RealEstate Listings</h1>
    </div>
    <div id="explore-listings-wrapper" class="position-relative">
        <div id="explore-listings" class="position-absolute d-flex justify-content-center align-items-center">
            <button type="button" class="btn btn-lg btn-outline-custom-light mr-3" id="explore-btn">Explore<i class="fas fa-chevron-right ml-2"></i></button>
        </div>
    </div>
    <div id="door-shadow-wrapper">
        <div id="door-shadow"></div>
    </div>
</div>
<script src="js/welcome-wrapper.js"></script>