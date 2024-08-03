<?php 

require_once ('./config/db.php');



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
            // Asset Calculation NLogic
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
        } else {
            echo "No change in value.<br>";
        }
    }
} else {
    echo "0 results";
}

?>

<html>
    
</html>