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

$menu_filters = isset($_POST["category"]) ? $_POST["category"] : [];
print_r($menu_filters);
$filters_str = implode(",",$menu_filters);
echo $filters_str;

$filter_query = "SELECT products.* FROM products JOIN product_info ON products.product_id = product_info.product_id";
if ($filters_str != "") {
    $filter_query = $filter_query." WHERE product_info.nutritional_info_id in (".$filters_str.")";
}
$filter_result = mysqli_query($connection, $filter_query);

?>
<?php


$all_nutritional_query = "SELECT * FROM nutritional_info";
$all_nutritional_result = mysqli_query($connection, $all_nutritional_query);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>test php</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <script>
            let expanded = false;

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
        <div class="menu-filters">
            <!--category form-->
            <form name="categories[]" id="categories" method="post" action="test-form.php">
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
                        while ($all_nutritional_record = mysqli_fetch_assoc($all_nutritional_result)) {
                            $is_checked = "";
                            if (in_array($all_nutritional_record['nutritional_info_id'], $menu_filters)) {
                                $is_checked = "checked='checked'";
                            }

                            echo "<label for='".$all_nutritional_record['nutritional_info_id']."'>";
                            echo "<input type='checkbox' name='category[]' id='".$all_nutritional_record['nutritional_info_id']."'"."value='".$all_nutritional_record['nutritional_info_id']."' ".$is_checked."' />";
                            echo $all_nutritional_record['name'];
                            echo '</label>';
                        }
                        while($filter_record = mysqli_fetch_assoc($filter_result)) {
                            echo "<br>".$filter_record['name']."<br>";
                            echo "<br>".$filter_record['cost']."<br>";
                            }
                        ?>

                    </div>
                </div>
                <div><input type="submit" value="Filter"></div>
            </form>
        </div>
    </body>
