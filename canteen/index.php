<?php
    /* Connect to the database */
    $connection = mysqli_connect('localhost','kuntzece','smartspoon57', 'kuntzece_canteen' );
    /* If connection error --> exit */
    if (mysqli_connect_errno()) {
        echo 'Failed to connect </3';
        exit();
    }

    /* query all nutritional info */
    $all_nutritional_query = "SELECT * FROM nutritional_info";
    $all_nutritional_result = mysqli_query($connection, $all_nutritional_query);

    /* select and query user filters */
    $menu_filters = isset($_POST["category"]) ? $_POST["category"] : [];
    /* takes filter array and converts to string */
    $filters_str = implode(",",$menu_filters);
    /* filter query changes based on if user has selected any filters */
    $filter_query = "SELECT products.*, images.* FROM products JOIN product_info ON products.product_id = product_info.product_id JOIN images ON products.img_id = images.img_id WHERE products.cost > 0";
    if ($filters_str != "") {
        $filter_query = $filter_query." AND product_info.nutritional_info_id in (".$filters_str.") GROUP BY product_id HAVING COUNT(product_info.nutritional_info_id) = ".count($menu_filters);
    }
    /* groups items so there are no repeated products */
    else {
        $filter_query = $filter_query." GROUP BY product_id";
    }

    /* Checks if user chose to sort items --> if yes, adds the sorting to the query */
    $sorting = isset($_POST['sort_by']) ? $_POST['sort_by'] : "";
    if ($sorting != "") {
        $filter_query = $filter_query.$sorting;
    }

    /* queries the database */
    $filter_result = mysqli_query($connection, $filter_query);
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <title>WGC Canteen</title>
        <link href = "style.css" rel = "stylesheet" type = "text/css"/>
        <link rel = "icon" rel = "shortcut icon" sizes = "32x32" href = "img/favicon-32x32.png"/>
        <script>
            // javascript code for checkboxes inside drop down menu taken from https://stackoverflow.com/a/27547021
            // answer by user vitfo (https://stackoverflow.com/users/3025330/vitfo) on stack overflow
            let expanded = false;
            // shows checkboxes (used when clicking on the dropdown menu)
            function showCheckboxes() {
                const checkboxes = document.getElementById("checkboxes");
                if (!expanded) {
                    checkboxes.style.display = "block";
                    expanded = true;
                } else {
                    checkboxes.style.display = "none";
                    expanded = false;
                }
            }
        </script>
    </head>
    <body>
        <div class = "header">
            <a href = "index.php"><img src = "img/wgc-logo.png"/></a>
            <h1>WGC Canteen</h1>
        </div>
        <div class = "navbar">
            <a href = 'index.php' class = "active">our products</a>
            <a href = 'weekly-specials.php'>weekly specials</a>
            <a href = 'random-item.php'>give me something random</a>
        </div>
        <div class = "main">
            <div class = "menu">
                <div class = "menu-top">
                    <div><h1 style="color: #354E54"> Menu Filters:</h1></div>
                </div>
                <div class = "menu-filters">
                    <!--category form-->
                    <form name = "categories" id = "categories" method = "post" action = "index.php">
                        <div class = "multiselect">
                            <div class = "selectBox" onclick="showCheckboxes()">
                                <select>
                                    <option>Categories</option>
                                </select>
                                <div class = "overSelect"></div>
                            </div>
                            <div id = "checkboxes">
                                <!--options-->
                                <?php
                                /*
                                    allows checked checkboxes to remain checked (even after refreshing page)
                                    learnt about the checked statement from here: https://stackoverflow.com/a/12541453
                                 */
                                    while ($all_nutritional_record = mysqli_fetch_assoc($all_nutritional_result)) {
                                        $is_checked = "";
                                        if (in_array($all_nutritional_record['nutritional_info_id'], $menu_filters)) {
                                            $is_checked = "checked = 'checked'";
                                        }
                                        /* this link (https://html5-tutorial.net/forms/checkboxes/) really helped me understand how to use checkboxes !!  */
                                        echo "<label for = '".$all_nutritional_record['nutritional_info_id']."'>";
                                        echo "<input type = 'checkbox' name = 'category[]' id = '".$all_nutritional_record['nutritional_info_id']."'"."value = '".$all_nutritional_record['nutritional_info_id']."' ".$is_checked."' />";
                                        echo $all_nutritional_record['name'];
                                        echo "</label>";
                                    }
                                ?>
                                <div><input type = "submit" value = "Filter"></div>
                            </div>
                        </div>
                    </form>
                    <div class = "sort-form">
                        <form name = "sorting" id = "sorting" method="post" action="index.php">
                            <label for="sort_by">Sort By:
                            <select name = "sort_by">
                                <option name = "sort_by" value = " ORDER BY products.name">Name A-Z</option>
                                <option name = "sort_by" value = " ORDER BY products.cost ASC">Price Low-High</option>
                                <option name = "sort_by" value = " ORDER BY products.cost DESC">Price High-Low</option>
                            </select>
                            </label>
                            <input type="submit" id="sort_button" value="Sort Items"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            <div class="products">
                <!--prints out products-->
                <?php
                    while($filter_record = mysqli_fetch_assoc($filter_result)) {
                        echo "<div class = 'product'>";
                            echo "<div class = 'product-image'><img src = 'img/".$filter_record['filename'].".png' alt = '".$filter_record['alt_text']."'></div>";
                            echo "<div><br><p>".$filter_record['name']."</p><br></div>";
                            echo "<div><p>Cost: $".$filter_record['cost']."</p></div>";
                            echo "<div><p>".$filter_record['availability']."</p><br></div>";
                            echo "<div><p> Ingredients: ".$filter_record['ingredients']."</p></div>";
                        echo "</div>";
                    }
                    if ($filter_record = 0) {
                        echo "no results";
                    }
                ?>
            </div>
        </div>
    </body>
</html>
<?php
    /* ends connection */
    mysqli_close($connection);
?>