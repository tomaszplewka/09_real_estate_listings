<?php 

function processPostData() {
    // 
    $sqlArray = [];
    $sqlArray['location'] = $_POST['location'] === 'Any' ? 'Any' : $_POST['location'];
    $sqlArray['minPrice'] = $_POST['minPrice'] === 0 ? 0. : filter_var($_POST['minPrice'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
    $sqlArray['maxPrice'] = $_POST['maxPrice'] === 'Unlimited' ? 'Unlimited' : filter_var($_POST['maxPrice'], FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
    $sqlArray['beds'] = $_POST['beds'] === 'Any' ? 'Any' : $_POST['beds'];
    $sqlArray['baths'] = $_POST['baths'] === 'Any' ? 'Any' : $_POST['baths'];
    return $sqlArray;
    // 
}

function markSelected($selectName, $selectValue)
{
    return (isset($_POST[$selectName]) && $_POST[$selectName] === $selectValue) ? 'selected' : '';
}

?>