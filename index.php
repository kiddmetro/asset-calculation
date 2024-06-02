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

// // Insert an example car asset
// $insert_car_asset = "INSERT INTO assets (name, category, description, year_of_purchase, cost_of_asset, end_of_life, current_cost, year_of_usage, category_id) VALUES
//     ('Toyota Camry', 'Sedan', '2021 Model of Toyota camry with butterfly doors and tinted glass', 2022, 35000, 2032, 75000, 2, $category_ids[2])";

// if (!$db->query($insert_car_asset)) {
//     die("Error inserting car asset: " . $db->error);
// }


// $insert_minor_fault = "INSERT INTO Faults (asset_id, fault_type, fault_expense, fault_rating, description, reported_date) VALUES (1, 'minor', 15000, 60, 'Minor scratch on surface', '2022-05-15')";

// if (!$db->query($insert_minor_fault)) {
//     die("Error inserting minor fault: " . $db->error);
// }

// $insert_extreme_critical_fault = "INSERT INTO Faults (asset_id, fault_type, fault_expense, fault_rating, description, reported_date) VALUES (1, 'Extreme_critical', 27000, 100, 'Engine failure', '2023-03-22')";

// if (!$db->query($insert_extreme_critical_fault)) {
//     die("Error inserting Extreme critical fault: " . $db->error);
// }

// SQL query to fetch the car asset data
$sql = "SELECT * FROM assets WHERE asset_id = 1 ";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $asset_id = $row["asset_id"];
        $purchase_year = $row["year_of_purchase"];
        $purchase_cost = $row["cost_of_asset"];
        $end_of_life = $row["end_of_life"];
        $current_cost = $row["current_cost"];
        $current_year = date("Y");
        
        // Calculate useful life of the asset
        $useful_life = $end_of_life - $purchase_year;

        // Calculate depreciation and appreciation rate per year
        $depreciation_rate = ($purchase_cost - $current_cost) / $useful_life;
        $appreciation_rate = ($current_cost - $purchase_cost ) / $useful_life;

        // Calculate accumulated depreciation up to the current year
        $depreciation_years = $current_year - $purchase_year;
        $accumulated_depreciation = $depreciation_rate * $depreciation_years;


        // Calculate accumulated appreciation up to the current year
        $appreciation_years = $current_year - $purchase_year;
        $accumulated_appreciation = $appreciation_rate * $appreciation_years;


        // Get all faults for the current asset
        $faults_sql = "SELECT * FROM faults WHERE asset_id = $asset_id";
        $faults_result = $db->query($faults_sql);

        $total_impact = 0;

        if ($faults_result->num_rows > 0) {
            while ($fault_row = $faults_result->fetch_assoc()) {
                $fault_type = $fault_row["fault_type"];
                $fault_rating = $fault_row["fault_rating"];
                $impact_percentage = 0;

                switch ($fault_type) {
                    case 'minor':
                        $impact_percentage = 0.1;
                        break;
                    case 'moderate':
                        $impact_percentage = 0.2;
                        break;
                    case 'major':
                        $impact_percentage = 0.3;
                        break;
                    case 'critical':
                        $impact_percentage = 0.4;
                        break;
                    case 'extreme_critical':
                        $impact_percentage = 0.5;
                        break;
                }

                $total_impact += $fault_rating * $impact_percentage / 100 * $purchase_cost;

                echo "Fault Type: " . $fault_type . "<br>";
                echo "Fault Rating: " . $fault_rating . "<br>";
                echo "Impact Percentage: " . ($impact_percentage * 100) . "%<br>";
            }
        }

        $current_depreciation_worth = $current_cost - $accumulated_depreciation - $total_impact;
        $current_appreciation_worth = $purchase_cost + $accumulated_appreciation - $total_impact;


        
        echo "Car Name: " . $row["name"] . "<br>";
        echo "Original Purchase Year: " . $purchase_year . "<br>";
        echo "Original Purchase Cost: $" . number_format($purchase_cost, 2) . "<br>";
        echo "Years of Usage: " . ($current_year - $purchase_year) . "<br>";
        echo "Current Year: " . $current_year . "<br>";
        if ($depreciation_rate > 0) {
            echo "Depreciation Per Year: $" . number_format($depreciation_rate, 2) . "<br>";
            echo "Current Worth: $" . number_format($current_depreciation_worth, 2) . "<br>";
        } elseif ($appreciation_rate > 0) {
            $current_worth = $row["current_cost"] + ($appreciation_rate * $appreciation_years);
            echo "Appreciation Per Year: $" . number_format($appreciation_rate, 2) . "<br>";
            echo "Current Worth: $" . number_format($current_appreciation_worth, 2) . "<br>";
        } else {
            echo "No change in value.<br>";
        }
        echo "Total Fault Impact: $" . number_format($total_impact, 2) . "<br>";
        // echo "Total Critical Fault Impact: $" . number_format($critical_impact, 2) . "<br>";

        $revaluation = $current_cost - $purchase_cost;
        if ($revaluation > 0) {
            echo "Revaluation: Asset has appreciated by $" . number_format($revaluation, 2) . "<br>";
        } elseif ($revaluation < 0) {
            echo "Revaluation: Asset has depreciated by $" . number_format(abs($revaluation), 2) . "<br>";
        } else {
            echo "Revaluation: No change in value.<br>";
        }
    }
} else {
    echo "0 results";
}



?>