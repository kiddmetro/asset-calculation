<?php
session_start();
$hostname = "localhost";
$username = "root";
$password = "";
$database = "asset_database";

mysqli_report(MYSQLI_REPORT_STRICT|MYSQLI_REPORT_ERROR);

$db = new mysqli($hostname, $username, $password, $database);
if ($db->connect_error) {
    die("Unable to connect to database: " . $db->connect_error);
}

// CREATE CATEGORIES TABLE
$create_category_table = $db->query('CREATE TABLE IF NOT EXISTS category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL,
    CONSTRAINT fk_parent_category FOREIGN KEY (parent_id) REFERENCES category(category_id)
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ENGINE=InnoDB');



// CREATE ASSET TABLE IN DATABASE
$create_asset_table = $db->query('CREATE TABLE IF NOT EXISTS assets (
   asset_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(50) NOT NULL, 
   category VARCHAR(50) NOT NULL, 
   description VARCHAR(255) NOT NULL,
   year_of_purchase INT(4) NOT NULL,
   cost_of_asset INT(100) NOT NULL,
   end_of_life INT(4) NOT NULL,
   current_cost INT(100) NOT NULL,
   year_of_usage INT(4) NOT NULL,
   category_id INT,
   CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES category(category_id)
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ENGINE=InnoDB');


// CREATE FAULT TABLE
$create_fault_table = $db->query('CREATE TABLE IF NOT EXISTS faults (
    fault_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asset_id INT(10) UNSIGNED NOT NULL,
    fault_type ENUM("minor", "moderate", "major", "critical", "extreme_critical") NOT NULL,(
    fault_expense DECIMAL(10,2) NOT NULL,
    fault_rating INT NOT NULL,
    description TEXT,
    reported_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES Assets(asset_id) ON DELETE CASCADE
) CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ENGINE=InnoDB');

?>
