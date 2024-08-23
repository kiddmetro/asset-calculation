<?php 

require_once ('./config/db.php');

// SQL query to fetch the car asset data
$sql = "SELECT * FROM assets WHERE asset_id = 11";
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
        $end_of_life = round($purchase_year + $useful_life);

        // Update the end_of_life in the database
        $update_sql = "UPDATE assets SET end_of_life = $end_of_life WHERE asset_id = $asset_id";
        $db->query($update_sql);

        // Calculate years of usage
        $years_of_usage = round($end_of_life - $purchase_year);

        echo "Car Name: " . $row["name"] . "<br>";
        echo "Original Purchase Year: " . $purchase_year . "<br>";
        echo "Original Purchase Cost: $" . number_format($purchase_cost, 2) . "<br>";
        echo "Current Cost: $" . number_format($current_cost, 2) . "<br>";
        echo "Years of Usage: " . $years_of_usage . "<br>";
        echo "Current Year: " . $current_year . "<br>";
        echo "End of Life: " . $end_of_life . "<br>";

        // Calculate annual depreciation expense
        $annual_depreciation_expense = $purchase_cost / $useful_life;

        echo "Depreciation Rate: " . $depreciation_percentage . "% per year<br><br>";
        echo "Annual Depreciation Expense: $" . number_format($annual_depreciation_expense, 2) . "<br><br>";

        // Asset Calculation Logic
        $depreciated_value = $purchase_cost;
        $next_year = $purchase_year + 1; // Start from the purchase year

        while ($next_year <= $end_of_life && $depreciated_value > 0) {
            $depreciation = $annual_depreciation_expense;
            $depreciated_value -= $depreciation;

             // Set a minimum depreciated value of $10
             if ($depreciated_value < 10) {
                $depreciated_value = 10;
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


// <CALCULATION OF INITIAL PURCAHSE COST OF ASSETS>

// Initialize variables to hold total costs and counts
$totalCarsCost = $totalFurnitureCost = $totalElectronicsCost = 0;
$countCars = $countFurniture = $countElectronics = 0;

// SQL query to fetch all asset data
$sql = "SELECT category, cost_of_asset FROM assets";
$result = $db->query($sql);
echo "<h3>CALCULATION OF INITIAL PURCAHSE COST OF ASSETS</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $category = $row["category"];
        $purchase_cost = $row["cost_of_asset"];

        // Add the purchase cost to the corresponding category total and increment the count
        switch ($category) {
            case 'Cars':
                $totalCarsCost += $purchase_cost;
                $countCars++;
                break;
            case 'Furniture':
                $totalFurnitureCost += $purchase_cost;
                $countFurniture++;
                break;
            case 'Electronics':
                $totalElectronicsCost += $purchase_cost;
                $countElectronics++;
                break;
        }
    }

    // Calculate the average purchase cost for each category
    $averageCarsCost = ($countCars > 0) ? $totalCarsCost / $countCars : 0;
    $averageFurnitureCost = ($countFurniture > 0) ? $totalFurnitureCost / $countFurniture : 0;
    $averageElectronicsCost = ($countElectronics > 0) ? $totalElectronicsCost / $countElectronics : 0;

    // Display the results
    echo "<br>Total Purchase Cost of Cars: $" . number_format($totalCarsCost, 2) . "<br>";
    echo "Average Purchase Cost of Cars: $" . number_format($averageCarsCost, 2) . "<br><br>";

    echo "Total Purchase Cost of Furniture: $" . number_format($totalFurnitureCost, 2) . "<br>";
    echo "Average Purchase Cost of Furniture: $" . number_format($averageFurnitureCost, 2) . "<br><br>";

    echo "Total Purchase Cost of Electronics: $" . number_format($totalElectronicsCost, 2) . "<br>";
    echo "Average Purchase Cost of Electronics: $" . number_format($averageElectronicsCost, 2) . "<br><br>";
} else {
    echo "0 results";
}

// <CALCULATION OF THE TOTAL NUMBER OF ASSETS IN EACH CATEGORY>

// SQL query to count the total number of assets for each category
$sql = "SELECT category, COUNT(*) AS total_assets FROM assets GROUP BY category";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>CALCULATION OF THE TOTAL NUMBER OF ASSETS IN EACH CATEGORY</h3>";
    
    while ($row = $result->fetch_assoc()) {
        $category = $row["category"];
        $total_assets = $row["total_assets"];

        // Display the total number of assets for each category with the correct format
        echo " Total number of " . $category . ": " . $total_assets . "<br>";
        echo "<br>";
    }
} else {
    echo "0 results";
}


// <TOTAL NETBOOK VALUE FOR EACH CATEGORY IN CURRENT YEAR>

// Initialize arrays to store total net book value for each category
$totalNetBookValue = [];
$current_year = date("Y"); // Get the current year

// SQL query to fetch all assets
$sql = "SELECT category, cost_of_asset, year_of_purchase, depreciation_percentage FROM assets";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>CALCULATION OF TOTAL NETBOOK VALUE FOR EACH CATEGORY IN CURRENT YEAR</h3>";

    while ($row = $result->fetch_assoc()) {
        $category = $row["category"];
        $cost_of_asset = $row["cost_of_asset"];
        $year_of_purchase = $row["year_of_purchase"];
        $depreciation_percentage = $row["depreciation_percentage"];

        // Calculate useful life of the asset based on depreciation percentage
        $useful_life = 100 / $depreciation_percentage; // Useful life in years
        $annual_depreciation = $cost_of_asset / $useful_life; // Annual depreciation expense

        // Calculate total depreciation up to the current year
        $years_since_purchase = $current_year - $year_of_purchase;
        $total_depreciation = min($years_since_purchase, $useful_life) * $annual_depreciation;

        // Calculate net book value
        $net_book_value = max($cost_of_asset - $total_depreciation, 10);

        // Add the net book value to the total for the category
        if (!isset($totalNetBookValue[$category])) {
            $totalNetBookValue[$category] = 0;
        }

        $totalNetBookValue[$category] += $net_book_value;
    }

    // Calculate and display the net book value for each category
    foreach ($totalNetBookValue as $category => $totalNBV) {
        echo "Total Net Book Value of " . $category . " assets in " . $current_year . ": $" . number_format($totalNBV, 2) . "<br>";
    }
} else {
    echo "0 results";
}

// <TOTAL ANNUAL DEPRECIATION EXPENSE PER CATEGORY>

// Initialize an array to store total annual depreciation per category
$annualDepreciationByCategory = [];

// SQL query to fetch all assets
$sql = "SELECT category, cost_of_asset, depreciation_percentage FROM assets";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>CALCULATION OF TOTAL ANNUAL DEPRECIATION EXPENSE PER CATEGORY</h3>";

    while ($row = $result->fetch_assoc()) {
        $category = $row["category"];
        $cost_of_asset = $row["cost_of_asset"];
        $depreciation_percentage = $row["depreciation_percentage"];

        // Calculate useful life of the asset based on depreciation percentage
        $useful_life = 100 / $depreciation_percentage; // Useful life in years

        // Calculate annual depreciation expense for the asset
        $annual_depreciation = $cost_of_asset / $useful_life;

        // Add the annual depreciation expense to the total for the category
        if (!isset($annualDepreciationByCategory[$category])) {
            $annualDepreciationByCategory[$category] = 0;
        }

        $annualDepreciationByCategory[$category] += $annual_depreciation;
    }

    // Display the annual depreciation expense for each category
    foreach ($annualDepreciationByCategory as $category => $totalAnnualDepreciation) {
        echo "Total Annual Depreciation Expense for " . $category . " assets: $" . number_format($totalAnnualDepreciation, 2) . "<br>";
    }
} else {
    echo "0 results";
}

// <TOTAL NUMBER OF ASSETS AT $10>

// Initialize an array to store the count of assets with $10 NBV per category
$assetsAtTenDollarsByCategory = [];

// SQL query to fetch all assets
$sql = "SELECT category, cost_of_asset, year_of_purchase, depreciation_percentage FROM assets";
$result = $db->query($sql);

$current_year = date("Y"); // Get the current year

if ($result->num_rows > 0) {
    echo "<h3>CALCULATION OF TOTAL NUMBER OF ASSETS AT $10</h3>";

    while ($row = $result->fetch_assoc()) {
        $category = $row["category"];
        $cost_of_asset = $row["cost_of_asset"];
        $year_of_purchase = $row["year_of_purchase"];
        $depreciation_percentage = $row["depreciation_percentage"];

        // Calculate useful life of the asset based on depreciation percentage
        $useful_life = 100 / $depreciation_percentage; // Useful life in years
        $annual_depreciation = $cost_of_asset / $useful_life; // Annual depreciation expense

        // Calculate total depreciation up to the current year
        $years_since_purchase = $current_year - $year_of_purchase;
        $total_depreciation = min($years_since_purchase, $useful_life) * $annual_depreciation;

        // Calculate net book value
        $net_book_value = max($cost_of_asset - $total_depreciation, 10);

        // Check if the net book value is $10
        if ($net_book_value == 10) {
            if (!isset($assetsAtTenDollarsByCategory[$category])) {
                $assetsAtTenDollarsByCategory[$category] = 0;
            }
            $assetsAtTenDollarsByCategory[$category]++;
        }
    }

    // Display the total number of assets at $10 per category
    foreach ($assetsAtTenDollarsByCategory as $category => $count) {
        echo "Total number of assets with a Net Book Value of $10 in " . $category . ": " . $count . "<br>";
    }
} else {
    echo "0 results";
}

// TOTAL PROCUREMENT COST PER YEAR

// Initialize an array to store procurement costs per year
$procurementByYear = [];

// SQL query to fetch all assets
$sql = "SELECT year_of_purchase, cost_of_asset FROM assets";
$result = $db->query($sql);

echo "<h3>CALCULATION OF TOTAL PROCUREMENT COST PER YEAR</h3>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $year_of_purchase = $row["year_of_purchase"];
        $cost_of_asset = $row["cost_of_asset"];

        // Add the procurement cost to the total for the year
        if (!isset($procurementByYear[$year_of_purchase])) {
            $procurementByYear[$year_of_purchase] = 0;
        }

        $procurementByYear[$year_of_purchase] += $cost_of_asset;
    }

    // Sort the array by year (ascending order)
    ksort($procurementByYear);

    // Display procurement costs per year
    foreach ($procurementByYear as $year => $totalProcurement) {
        echo "Year: " . $year . " - Total Procurement Cost: $" . number_format($totalProcurement, 2) . "<br>";
    }
} else {
    echo "0 results";
}

?>

<html>
    
</html>
