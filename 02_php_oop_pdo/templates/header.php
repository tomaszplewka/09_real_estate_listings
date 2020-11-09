<!-- Header -->

<?php
$currFile = basename($_SERVER['PHP_SELF'], '.php');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Listings</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha512-xA6Hp6oezhjd6LiLZynuukm80f8BoZ3OpcEYaqKoCV3HKQDrYjDE1Gu8ocxgxoXmwmSzM4iqPvCsOkQNiu41GA==" crossorigin="anonymous" />
    <link rel="stylesheet" href="style/style.css">
</head>

<body class="position-relative bg-custom-helper <?php echo !isset($_COOKIE['welcomeWrapper']) ? 'vw-100 vh-100 overflow-hidden' : ''; ?>">

    <?php
    if (!isset($_COOKIE['welcomeWrapper'])) {
        include('templates/welcome-wrapper.php');
    }
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo ROOT_URL . 'index.php'; ?>">
                <i class="fas fa-home mr-2"></i>
                RealEstate
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currFile === 'index' ? 'active' : ''; ?>" href="<?php echo ROOT_URL . 'index.php'; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currFile === 'add-listing' ? 'active' : ''; ?>" href="<?php echo ROOT_URL . 'add-listing.php'; ?>">Add Listing</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>