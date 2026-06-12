<?php
/**
 * InfinityFree Database Connection Example
 * 
 * IMPORTANT: InfinityFree blocks external connections. 
 * This script will likely ONLY work when uploaded to your InfinityFree hosting account.
 * If you run this locally (e.g., from XAMPP in c:\xampp\...), you may get a connection timeout or access denied error.
 */

$host = 'sql100.infinityfree.com';
$dbname = 'if0_41630300_smartprep';
$username = 'if0_41630300';
$password = 'HR4b4RIyyhnj4';

// ==========================================
// 1. Connection (using MySQLi) & Error Handling
// ==========================================
// We enable exception throwing to catch any connection errors nicely
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    // Set charset to ensure proper handling of special characters
    $conn->set_charset("utf8mb4");
    
    echo "<h3>✅ Connected successfully to InfinityFree database!</h3>";
    
} catch (mysqli_sql_exception $e) {
    // 3. Error Handling for Connection Issues
    die("<h3>❌ Database Connection Failed</h3><p>Ensure you are running this script ON InfinityFree's servers, not locally on XAMPP.</p><p>Error Error Details: " . $e->getMessage() . "</p>");
}

// ==========================================
// 2. Example SQL queries
// ==========================================

echo "<hr>";

// --- A. CREATE TABLE ---
$sql_create = "CREATE TABLE IF NOT EXISTS demo_students (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(30) NOT NULL,
    study_subject VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    if ($conn->query($sql_create) === TRUE) {
        echo "Table 'demo_students' is ready.<br>";
    }
} catch (mysqli_sql_exception $e) {
    echo "Error creating table: " . $e->getMessage() . "<br>";
}

// --- B. INSERT DATA (Using Prepared Statements for security) ---
// Note: We use prepared statements to prevent SQL Injection
try {
    $stmt = $conn->prepare("INSERT INTO demo_students (first_name, study_subject) VALUES (?, ?)");
    
    // Bind parameters ("ss" means two strings)
    $stmt->bind_param("ss", $studentName, $subject);

    // Set values and execute
    $studentName = "Ali";
    $subject = "Mathematics";
    $stmt->execute();
    
    // Insert another record
    $studentName = "Sara";
    $subject = "Physics";
    $stmt->execute();

    echo "New records inserted successfully.<br>";
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    echo "Error inserting records: " . $e->getMessage() . "<br>";
}

// --- C. SELECT DATA ---
try {
    $sql_select = "SELECT id, first_name, study_subject, created_at FROM demo_students";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        echo "<h4>Student List:</h4><ul>";
        // Fetch and display each row
        while($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row["id"]. " - Name: <strong>" . htmlspecialchars($row["first_name"]). "</strong> - Subject: " . htmlspecialchars($row["study_subject"]) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "0 results found.";
    }
} catch (mysqli_sql_exception $e) {
    echo "Error selecting data: " . $e->getMessage() . "<br>";
}

// ==========================================
// Close connection when finished
// ==========================================
$conn->close();
?>
