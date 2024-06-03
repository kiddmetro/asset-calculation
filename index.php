<?php 

require_once ('./config/db.php');



// Insert an example car asset
// $insert_car_asset = "INSERT INTO assets (name, category, description, year_of_purchase, cost_of_asset, end_of_life, current_cost) VALUES
//     ('Audi RS35', 'Sports', 'Audi smooth and ready for use', 2013, 2500000, 2026, 1900000)";

// if (!$db->query($insert_car_asset)) {
//     die("Error inserting car asset: " . $db->error);
// }


// $insert_minor_fault = "INSERT INTO Faults (asset_id, fault_type, fault_rating, description, reported_date) VALUES (1, 'minor', 60, 'Minor scratch on surface', '2022-05-15')";

// if (!$db->query($insert_minor_fault)) {
//     die("Error inserting minor fault: " . $db->error);
// }

// $insert_extreme_critical_fault = "INSERT INTO Faults (asset_id, fault_type, fault_rating, description, reported_date) VALUES (1, 'Extreme_critical', 100, 'Engine failure', '2023-03-22')";

// if (!$db->query($insert_extreme_critical_fault)) {
//     die("Error inserting Extreme critical fault: " . $db->error);
// }

// $insert_insignificant_fault = "INSERT INTO Faults (asset_id, fault_type, fault_rating, description, reported_date) VALUES (1, 'insignificant',  5, 'Minor wear and tear', '2022-05-15')";
// $insert_minor_fault = "INSERT INTO Faults (asset_id, fault_type, fault_rating, description, reported_date) VALUES (1, 'minor',  30, 'Small scratch on surface', '2022-06-10')";
// $insert_moderate_fault = "INSERT INTO Faults (asset_id, fault_type, fault_rating, description, reported_date) VALUES (1, 'moderate',  50, 'Minor engine issue', '2023-03-22')";
// $insert_major_fault = "INSERT INTO Faults (asset_id, fault_type, fault_rating, description, reported_date) VALUES (1, 'major', 40, 'Engine failure', '2023-04-15')";

// if (!$db->query($insert_insignificant_fault)) {
//     die("Error inserting insignificant fault: " . $db->error);
// }

// if (!$db->query($insert_minor_fault)) {
//     die("Error inserting minor fault: " . $db->error);
// }

// if (!$db->query($insert_moderate_fault)) {
//     die("Error inserting moderate fault: " . $db->error);
// }

// if (!$db->query($insert_major_fault)) {
//     die("Error inserting major fault: " . $db->error);
// }


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


        // echo  $accumulated_appreciation . "<br>";
        // echo $accumulated_depreciation . "<br>";

        // Get all faults for the current asset
        $faults_sql = "SELECT * FROM faults WHERE asset_id = $asset_id";
        $faults_result = $db->query($faults_sql);

        $total_impact = 0;

        if ($faults_result->num_rows > 0) {
            while ($fault_row = $faults_result->fetch_assoc()) {
                $fault_type = $fault_row["fault_type"];
                $fault_rating = $fault_row["fault_rating"];
                $impact_percentage = 0;

                if ($fault_type == 'insignificant' && $fault_rating >= 0 && $fault_rating < 20) {
                    $impact_percentage = $fault_rating * 1.0 / 100;
                } elseif ($fault_type == 'minor' && $fault_rating >= 20 && $fault_rating < 40) {
                    $impact_percentage = $fault_rating * 1.0 / 100;
                } elseif ($fault_type == 'moderate' && $fault_rating >= 40 && $fault_rating < 60) {
                    $impact_percentage = $fault_rating * 1.0 / 100;
                } elseif ($fault_type == 'major' && $fault_rating >= 60 && $fault_rating < 80) {
                    $impact_percentage = $fault_rating * 1.0 / 100;
                } elseif ($fault_type == 'critical' && $fault_rating >= 80 && $fault_rating < 90) {
                    $impact_percentage = $fault_rating * 1.0 / 100;
                } elseif ($fault_type == 'extreme_critical' && $fault_rating >= 90 && $fault_rating <= 100) {
                    $impact_percentage = $fault_rating * 1.0 / 100;
                }
                else {
                    echo "Invalid Value";
                }

                $impact_value = $impact_percentage * $purchase_cost;
                $total_impact += $impact_value;

                echo "Fault Type: " . $fault_type . "<br>";
                echo "Fault Rating: " . $fault_rating . "<br>";
                echo "Impact Percentage: " . ($impact_percentage * 100) . "%<br>";
                echo "Impact Value: $" . number_format($impact_value, 2) . "<br><br>";
            }
        }

        $current_depreciation_worth = $current_cost - $accumulated_depreciation - $total_impact;
        $current_appreciation_worth = $purchase_cost + $accumulated_appreciation - $total_impact;


        echo "Car Name: " . $row["name"] . "<br>";
        echo "Original Purchase Year: " . $purchase_year . "<br>";
        echo "Original Purchase Cost: $" . number_format($purchase_cost, 2) . "<br>";
        echo "Current Cost: $" . number_format($current_cost, 2) . "<br>";
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