<?php

    /* Connect to the database */
    $connection = mysqli_connect('localhost', 'kuntzece', 'smartspoon57', 'kuntzece_canteen');
    /* If connection error --> exit */
    if (mysqli_connect_errno()) {
        echo 'Failed to connect </3';
        exit();
    }

    /* query all weekly special information */
    $weekly_special_query = "SELECT products.*, images.*, weekly_specials.* FROM products JOIN images ON products.img_id = images.img_id JOIN weekly_specials ON products.product_id = weekly_specials.product_id ORDER BY weekly_specials.day_order ASC";
    $weekly_special_result = mysqli_query($connection, $weekly_special_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>WGC Canteen</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class = "header">
        <img src = "img/wgc-logo.png" src = "index.php" style = "height: 150px; width: 150px;"/>
        <h1>WGC Canteen</h1>
    </div>
    </div>
    <div class="navbar">
        <a href='index.php' class="active">our products</a>
        <a href='weekly-specials.php'>weekly specials</a>
        <a href='random-item.php'>give me something random</a>
    </div>
    <div class="main">
        <div class="center-header"><h1 style="color: #354E54"> Our Weekly Specials !!</h1></div>
        <!--display all weekly specials  -->
        <?php
            while ($weekly_special_record = mysqli_fetch_assoc($weekly_special_result)) {
                echo "<div class = 'single-display'>";
                echo "<div class = 'big-image'><img src = 'img/".$weekly_special_record['filename'].".png' alt = '".$weekly_special_record['alt_text']."'style = 'width = '300px' height = '300px'></div>";
                echo "<div class='product-info'>";
                echo "<div><h1>".$weekly_special_record['day']."</h1></div>";
                echo "<div><p><br>".$weekly_special_record['name']."</p><br></div>";
                echo "<div><p>Special Cost: $".$weekly_special_record['special_cost']."</p></div>";
                echo "<div><p>".$weekly_special_record['availability']."</p><br></div>";
                echo "<div><p> Ingredients: ".$weekly_special_record['ingredients']."</p></div>";
                echo "</div>";
                echo "</div>";
            }
        ?>
    </div>

</body>

