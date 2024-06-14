<?php 

require_once ('./config/db.php');



// Insert an example car asset
// $insert_car_asset = "INSERT INTO assets (name, category, description, year_of_purchase, cost_of_asset, end_of_life, current_cost, depreciation_rate, inflation_rate) VALUES
//     ('Audi RS35', 'Sports', 'Audi smooth and ready for use', 2020, 350000, 2025, 250000, 20 , 1)";

// if (!$db->query($insert_car_asset)) {
//     die("Error inserting car asset: " . $db->error);
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
        $depreciation_percentage = $row["depreciation_percentage"];
        $current_year = date("Y");
        
        // Calculate useful life of the asset
        $useful_life = $end_of_life - $purchase_year;

        // Calculate depreciation and appreciation rate per year
        // $depreciation_rate = ($purchase_cost - $current_cost) / $useful_life;

        // Calculate accumulated depreciation up to the current year
        // $depreciation_years = $current_year - $purchase_year;
        // $accumulated_depreciation = $depreciation_rate * $depreciation_years;


        // Calculate accumulated appreciation up to the current year
        // $appreciation_years = $current_year - $purchase_year;
        // $accumulated_appreciation = $appreciation_rate * $appreciation_years;

        // $current_depreciation_worth = $current_cost - $accumulated_depreciation;
        // $current_appreciation_worth = $purchase_cost + $accumulated_appreciation;


        echo "Car Name: " . $row["name"] . "<br>";
        echo "Original Purchase Year: " . $purchase_year . "<br>";
        echo "Original Purchase Cost: $" . number_format($purchase_cost, 2) . "<br>";
        echo "Current Cost: $" . number_format($current_cost, 2) . "<br>";
        echo "Years of Usage: " . ($current_year - $purchase_year) . "<br>";
        echo "Current Year: " . $current_year . "<br>";
        if ($current_cost < $purchase_cost) {
            $depreciation_rate = ($depreciation_percentage/100) * $purchase_cost;
            echo "Depreciation Per Year: $" . number_format($depreciation_rate, 2) . "<br><br>";

            $depreciated_value = $purchase_cost;
            $next_year = $purchase_year;
            while($next_year <= $end_of_life){
                $depreciated_value -= $depreciation_rate;

                if ($depreciated_value < 0){
                    $depreciated_value = 0;
                }

                echo "Net Book Value in the year " .  $next_year . ": $" . number_format($depreciated_value, 2) . "<br>";
                

                $next_year++;
            }
             if($next_year == $current_year){
                    $netbook_value = $depreciated_value;
                    echo "DV" . number_format($netbook_value, 2). "<br><br>";

                    $inc_dec_netbook = $current_cost - $netbook_value;
                    echo "Incrase/Decrease Net Book Value: $" . number_format($inc_dec_netbook, 2). "<br><br>";
                }
            

        } elseif ($current_cost > $purchase_cost) {
            $current_worth = $row["current_cost"] + ($appreciation_rate * $appreciation_years);
            echo "Appreciation Per Year: $" . number_format($appreciation_rate, 2) . "<br>";
            echo "Current Worth: $" . number_format($current_appreciation_worth, 2) . "<br>";
        } else {
            echo "No change in value.<br>";
        }


        //Increase/Decrease  in Net Book value
        
        
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