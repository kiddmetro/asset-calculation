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
        $current_cost = $row["current_cost"];
        $depreciation_percentage = $row["depreciation_percentage"];
        $current_year = date("Y");

        // Calculate useful life of the asset based on depreciation percentage
        $useful_life = 100 / $depreciation_percentage;
        $end_of_life = $purchase_year + $useful_life;

        // Update the end_of_life in the database
        // $update_sql = "UPDATE assets SET end_of_life = $end_of_life WHERE asset_id = $asset_id";
        // $db->query($update_sql);

        echo "Car Name: " . $row["name"] . "<br>";
        echo "Original Purchase Year: " . $purchase_year . "<br>";
        echo "Original Purchase Cost: $" . number_format($purchase_cost, 2) . "<br>";
        echo "Current Cost: $" . number_format($current_cost, 2) . "<br>";
        echo "Years of Usage: " . min($end_of_life - $purchase_year , $useful_life) . "<br>";
        echo "Current Year: " . $current_year . "<br>";
        echo "End of Life: " . $end_of_life . "<br>";

        // Calculate annual depreciation expense
        $annual_depreciation_expense = $purchase_cost / $useful_life;

        // Calculate salvage value (assuming full depreciation)
        $salvage_value = $purchase_cost - ($annual_depreciation_expense * $useful_life);

        echo "Depreciation Rate: " . $depreciation_percentage . "% per year<br><br>";
        echo "Annual Depreciation Expense: $" . number_format($annual_depreciation_expense, 2) . "<br>";
        echo "Salvage Value: $" . number_format($salvage_value, 2) . "<br><br>";

        // Asset Calculation Logic
        $depreciated_value = $purchase_cost;
        $next_year = $purchase_year + 1; // Start from the purchase year

        while ($next_year <= $end_of_life && $depreciated_value > 0) {
            $depreciation = $annual_depreciation_expense;
            $depreciated_value -= $depreciation;

            if ($depreciated_value < 0) {
                $depreciated_value = 0;
            }

            echo "Depreciation at the End of " . $next_year . ": $" . number_format($depreciation, 2) . "<br>";
            echo "Net Book Value at the end of the year " . $next_year . ": $" . number_format($depreciated_value, 2) . "<br><br>";

            if ($next_year == $current_year) {
                $netbook_value = $depreciated_value;
            }

            $next_year++;
        }

        if (isset($netbook_value)) {
            echo "<br>Net Book Value in the current year (" . $current_year . "): $" . number_format($netbook_value, 2) . "<br><br>";
        }
    }
} else {
    echo "0 results";
}

?>

<html>
    
</html>
