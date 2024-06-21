<?php 

require_once ('./config/db.php');

// Inserting main categories
$insert_main_categories = "INSERT INTO category (category_name, parent_id) VALUES
    ('Cars', NULL),
    ('Car Brands', NULL)";

if (!$db->query($insert_main_categories)) {
    die("Error inserting main categories: " . $db->error);
}

// Get the ID of the main categories
$cars_category_id = $db->insert_id; // Cars category ID
$car_brands_category_id = $cars_category_id + 1; // Car Brands category ID

// Insert subcategories
$insert_subcategories = "INSERT INTO category (category_name, parent_id) VALUES
    ('Car Types', $cars_category_id),
    ('Car Parts', $cars_category_id),
    ('Car Accessories', $cars_category_id)";

if (!$db->query($insert_subcategories)) {
    die("Error inserting subcategories: " . $db->error);
}

// Insert brand categories
$insert_brand_categories = "INSERT INTO category (category_name, parent_id) VALUES
    ('Toyota', $car_brands_category_id),
    ('Honda', $car_brands_category_id),
    ('Ford', $car_brands_category_id),
    ('Chevrolet', $car_brands_category_id)";

if (!$db->query($insert_brand_categories)) {
    die("Error inserting brand categories: " . $db->error);
}

// Get the category IDs for cars, car types, and a specific brand (e.g., Toyota)
$get_category_ids = "SELECT category_id FROM category WHERE category_name IN ('Cars', 'Car Types', 'Toyota')";
$result = $db->query($get_category_ids);
$category_ids = array();
while ($row = $result->fetch_assoc()) {
    $category_ids[] = $row['category_id'];
}

if (count($category_ids) < 3) {
    die("Error: Could not find necessary categories in the database.");
}

// SQL query to fetch the car asset data
$sql = "SELECT * FROM assets WHERE asset_id = 2 ";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $asset_id = $row["asset_id"];
        $purchase_year = $row["year_of_purchase"];
        $purchase_cost = $row["cost_of_asset"];
        $end_of_life = $row["end_of_life"];
        $current_cost = $row["current_cost"];
        $depreciation_percentage = $row["depreciation_percentage"];
        $current_year = date("Y");

        // Calculate useful life of the asset
        $useful_life = $end_of_life - $purchase_year;

        echo "Car Name: " . $row["name"] . "<br>";
        echo "Original Purchase Year: " . $purchase_year . "<br>";
        echo "Original Purchase Cost: $" . number_format($purchase_cost, 2) . "<br>";
        echo "Current Cost: $" . number_format($current_cost, 2) . "<br>";
        echo "Years of Usage: " . ($current_year - $purchase_year) . "<br>";
        echo "Current Year: " . $current_year . "<br>";

        if ($current_cost < $purchase_cost) {
            // Depreciation logic using reducing balance method
            $depreciated_value = $purchase_cost;
            $next_year = $purchase_year;

            echo "Depreciation Rate: " . $depreciation_percentage . "% per year<br><br>";

            while ($next_year <= $end_of_life) {
                $depreciation = ($depreciation_percentage / 100) * $depreciated_value;
                $depreciated_value -= $depreciation;

                if ($depreciated_value < 0) {
                    $depreciated_value = 0;
                }

                echo "Depreciation for the year " . $next_year . ": $" . number_format($depreciation, 2) . "<br>";
                echo "Net Book Value at the end of the year " . $next_year . ": $" . number_format($depreciated_value, 2) . "<br><br>";

                if ($next_year == $current_year) {
                    $netbook_value = $depreciated_value;
                }

                $next_year++;
            }

            if (isset($netbook_value)) {
                echo "<br>Net Book Value in the current year (" . $current_year . "): $" . number_format($netbook_value, 2) . "<br><br>";

                $inc_dec_netbook = $current_cost - $netbook_value;
                echo "Increase/(Decrease) Net Book Value: $" . number_format($inc_dec_netbook, 2) . "<br><br>";
            }
        } elseif ($current_cost > $purchase_cost) {
            // Appreciation logic
            $appreciated_value = $purchase_cost;
            $next_year = $purchase_year;

            echo "Appreciation Rate: " . $depreciation_percentage . "% per year<br><br>";

            while ($next_year <= $current_year) {
                $appreciation = ($depreciation_percentage / 100) * $appreciated_value;
                $appreciated_value += $appreciation;

                echo "Appreciation for the year " . $next_year . ": $" . number_format($appreciation, 2) . "<br>";
                echo "Net Book Value in the year " . $next_year . ": $" . number_format($appreciated_value, 2) . "<br>";

                if ($next_year == $current_year) {
                    $netbook_value = $appreciated_value;
                }

                $next_year++;
            }

            if (isset($netbook_value)) {
                echo "<br>Net Book Value in the current year (" . $current_year . "): $" . number_format($netbook_value, 2) . "<br><br>";

                $inc_dec_netbook = $current_cost - $netbook_value;
                echo "Increase/(Decrease) Net Book Value: $" . number_format($inc_dec_netbook, 2) . "<br><br>";
            }
        } else {
            echo "No change in value.<br>";
        }
    }
} else {
    echo "0 results";
}


?>
