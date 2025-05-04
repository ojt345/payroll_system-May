<?php
// Include database connection
require_once 'db_connection.php';

// Start session to store search results
session_start();

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize variables
$searchCriteria = array();
$hasResults = false;
$redirectUrl = "../html/payroll_report.php"; // Change path as needed

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Method 1: Search by Payroll Number
    if (!empty($_POST['payrollNo'])) {
        $payrollNo = sanitize_input($_POST['payrollNo']);
        
        // Query the view using PayrollMstID
        $query = "SELECT * FROM payroll_comprehensive_view WHERE PayrollMstID = '$payrollNo' LIMIT 1";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            // Store search criteria in session
            $_SESSION['search_type'] = 'payroll_no';
            $_SESSION['payroll_no'] = $payrollNo;
            $hasResults = true;
            
            // Debug info
            $_SESSION['debug_query'] = $query;
            $_SESSION['debug_result_count'] = mysqli_num_rows($result);
        } else {
            $_SESSION['error_message'] = "No records found for Payroll No: $payrollNo";
            $_SESSION['debug_query'] = $query;
            $_SESSION['debug_error'] = mysqli_error($conn);
        }
    } 
    // Method 2: Search by date range, responsibility, and office
    else if (!empty($_POST['date_start']) && !empty($_POST['date_end']) && 
             !empty($_POST['responsibility_title']) && !empty($_POST['office_title'])) {
        
        $dateStart = sanitize_input($_POST['date_start']);
        $dateEnd = sanitize_input($_POST['date_end']);
        $responsibilityTitle = sanitize_input($_POST['responsibility_title']);
        $officeTitle = sanitize_input($_POST['office_title']);
        
        // Query the view using the combined criteria
        $query = "SELECT * FROM payroll_comprehensive_view 
                 WHERE date_start = '$dateStart' 
                 AND date_end = '$dateEnd'
                 AND responsibility_title = '$responsibilityTitle'
                 AND office_title = '$officeTitle'
                 LIMIT 1";
        
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            // Store search criteria in session
            $_SESSION['search_type'] = 'criteria';
            $_SESSION['date_start'] = $dateStart;
            $_SESSION['date_end'] = $dateEnd;
            $_SESSION['responsibility_title'] = $responsibilityTitle;
            $_SESSION['office_title'] = $officeTitle;
            $hasResults = true;
            
            // Debug info
            $_SESSION['debug_query'] = $query;
            $_SESSION['debug_result_count'] = mysqli_num_rows($result);
        } else {
            $_SESSION['error_message'] = "No records found matching your search criteria.";
            $_SESSION['debug_query'] = $query;
            $_SESSION['debug_error'] = mysqli_error($conn);
        }
    } else {
        $_SESSION['error_message'] = "Please provide either a Payroll No. or complete all required fields.";
    }
    
    // Redirect based on search results
    if ($hasResults) {
        // Redirect to the payroll report page
        header("Location: $redirectUrl");
        exit();
    } else {
        // No results found, redirect back to search page with error message
        header("Location: ../html/request_payroll.php");
        exit();
    }
} else {
    // If the form wasn't submitted properly, redirect back to search page
    $_SESSION['error_message'] = "Invalid form submission.";
    header("Location: ../html/request_payroll.php");
    exit();
}
?>