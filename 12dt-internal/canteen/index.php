<?php
    /* Connect to the database */
    $connection = mysqli_connect('localhost','kuntzece','smartspoon57', 'kuntzece_canteen' );
    /* If connection error --> exit */
    if (mysqli_connect_errno()) {
        echo 'Failed to connect </3';
        exit();
    } else {
        echo 'connected';
    }
    /* query all nutritional info */
    $all_nutritional_query = "SELECT * FROM nutritional_info";
    $all_nutritional_result = mysqli_query($connection, $all_nutritional_query);
    /* select and query user filters */
    $menu_filters = isset($_POST["category"]) ? $_POST["category"] : [];
    /* takes filter array and converts to string */
    $filters_str = implode(",",$menu_filters);
    /* filter query changes based on if user has selected any filters */
    $filter_query = "SELECT DISTINCT products.* FROM products JOIN product_info ON products.product_id = product_info.product_id";
    if ($filters_str != "") {
        $filter_query = $filter_query." WHERE product_info.nutritional_info_id in (".$filters_str.")";
    }
    $filter_result = mysqli_query($connection, $filter_query);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>WGC Canteen</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <!--javascript code for checkboxes inside drop down menu taken from https://stackoverflow.com/a/27547021-->
        <!--answer by user vitfo (https://stackoverflow.com/users/3025330/vitfo) on stack overflow-->
        <script>
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
        <div class="header">
            <img src="img/wgc_logo.png" src="index.php" style="height: 150px; width: 150px;"/>
            <h1>HEADER</h1>
        </div>
        <div class="navbar">
            <a href='index.php' class="active">our products</a>
            <a href='weekly-specials.php'>weekly specials</a>
            <a href='random-item.php'>give me something random</a>
        </div>
        <div class="main">
            <div class="menu">
                <div class="menu-top">
                    <div>Menu - Hide filters</div>
                    <div>
                        Sort by:
                        <select>
                            <option>Name -Z</option>
                            <option>Price Low-High</option>
                        </select>
                    </div>
                </div>
                <div class="menu-filters">
                    <!--category form-->
                    <form name="categories" id="categories" method="post" action="index.php">
                        <div class="multiselect">
                            <div class="selectBox" onclick="showCheckboxes()">
                                <select>
                                    <option>Categories</option>
                                </select>
                                <div class="overSelect"></div>
                            </div>
                            <div id="checkboxes">
                                <!--options-->
                                <?php
                                /*
                                    allows checked checkboxes to remain checked (even after refreshing page)
                                    learnt about the checked statement from here: https://stackoverflow.com/a/12541453
                                 */
                                while ($all_nutritional_record = mysqli_fetch_assoc($all_nutritional_result)) {
                                    $is_checked = "";
                                    if (in_array($all_nutritional_record['nutritional_info_id'], $menu_filters)) {
                                        $is_checked = "checked='checked'";
                                    }
                                    /* this link (https://html5-tutorial.net/forms/checkboxes/) really helped me understand how to use checkboxes !!  */
                                    echo "<label for='".$all_nutritional_record['nutritional_info_id']."'>";
                                    echo "<input type='checkbox' name='category[]' id='".$all_nutritional_record['nutritional_info_id']."'"."value='".$all_nutritional_record['nutritional_info_id']."' ".$is_checked."' />";
                                    echo $all_nutritional_record['name'];
                                    echo '</label>';
                                }
                                ?>

                            </div>
                        </div>
                        <input type="submit" value="Filter">
                    </form>
                </div>
            </div>
        </div>
            <div class="products">
                <!--prints out products-->
                <?php
                    while($filter_record = mysqli_fetch_assoc($filter_result)) {
                        echo "<div class='product'>";
                            echo "<div class='product-image'>"."</div>";
                            echo "<div>".$filter_record['name']."</div>";
                            echo "<div>"."<p> Cost:</p>".$filter_record['cost']."<br>";
                            echo $filter_record['availability']."<br>";
                            echo "<p> Ingredients:</p>".$filter_record['ingredients']."<br>"."</div>";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>
<?php
    mysqli_close($connection);
?>