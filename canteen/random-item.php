<?php
    /* Connect to the database */
    $connection = mysqli_connect('localhost', 'kuntzece', 'smartspoon57', 'kuntzece_canteen');
    /* If connection error --> exit */
    if (mysqli_connect_errno()) {
        echo 'Failed to connect </3';
        exit();
    }

    /* selects a random product */
    $random_query = "SELECT products.*, images.* FROM products JOIN images ON products.img_id = images.img_id WHERE products.cost > 0 ORDER BY RAND() LIMIT 1";
    $random_result = mysqli_query($connection, $random_query);
    /* random product query result */
    $random_record = mysqli_fetch_assoc($random_result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>WGC Canteen</title>
        <link href="style.css" rel="stylesheet" type="text/css"/>
        <link rel = "icon" rel = "shortcut icon" sizes = "32x32" href = "img/favicon-32x32.png"/>
    </head>
    <body>
        <div class = "header">
            <a href = "index.php"><img src = "img/wgc-logo.png"/></a>
            <h1>WGC Canteen</h1>
        </div>
        <div class = "navbar">
            <a href = 'index.php'>our products</a>
            <a href = 'weekly-specials.php'>weekly specials</a>
            <a href = 'random-item.php' class = "active">give me something random</a>
        </div>
        <div class = "main">
            <div class = "center-header"><h1 style="color: #354E54"> Don't know what to get? Here is a Random Item!!</h1></div>
            <!--display random product-->
            <div class="single-product-center">
                <?php
                    echo "<div class = 'single-display'>";
                    echo "<div class = 'big-image'><img src = 'img/".$random_record['filename'].".png' alt = '".$random_record['alt_text']."'style = 'width = '300px' height = '300px'></div>";
                    echo "<div class='product-info'>";
                    echo "<div><h1>".$random_record['name']."</h1></div>";
                    echo "<div><p>Special Cost: $".$random_record['cost']."</p></div>";
                    echo "<div><p>".$random_record['availability']."</p><br></div>";
                    echo "<div><p> Ingredients: ".$random_record['ingredients']."</p></div>";
                    echo "</div>";
                    echo "</div>";
                ?>
            </div>
        </div>
    </body>
</html>
<?php
/* ends connection */
mysqli_close($connection);
?>
