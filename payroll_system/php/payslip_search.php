<?php
session_start();
require_once '../php/db_connection.php';

// Initialize variables to track search results
$search_results = array();
$search_error = "";
$found_records = false;

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $payroll_mst_id = isset($_POST['PayrollMstID']) ? trim($_POST['PayrollMstID']) : '';
    $emp_no = isset($_POST['emp_no']) ? trim($_POST['emp_no']) : '';
    $date_start = isset($_POST['date_start']) ? $_POST['date_start'] : '';
    $date_end = isset($_POST['date_end']) ? $_POST['date_end'] : '';
    $responsibility_title = isset($_POST['responsibility_title']) ? $_POST['responsibility_title'] : '';
    $office_title = isset($_POST['office_title']) ? $_POST['office_title'] : '';
    
    // Build query based on search parameters
    $query = "SELECT * FROM payroll_comprehensive_view WHERE 1=1";
    
    // If payroll number is provided, use that for search
    if (!empty($payroll_mst_id)) {
        $query .= " AND PayrollMstID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $payroll_mst_id);
    } 
    // Otherwise use combination of other fields
    else if (!empty($emp_no) && !empty($date_start) && !empty($date_end) && !empty($responsibility_title) && !empty($office_title)) {
        $query .= " AND emp_no = ? AND date_start >= ? AND date_end <= ? AND responsibility_title = ? AND office_title = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $emp_no, $date_start, $date_end, $responsibility_title, $office_title);
    } 
    // If neither complete set is provided
    else {
        $search_error = "Please either enter a Payroll No. OR complete all other search fields (Employee #, Start Date, End Date, Responsibility Center, and Office).";
        // Redirect back to search form with error
        $_SESSION['search_error'] = $search_error;
        header("Location: ../html/request_payslip.php");
        exit();
    }
    
    // Execute query
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Check if any results were found
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
        $found_records = true;
        
        // Get certified_by information
        $cert_query = "SELECT * FROM certified_by WHERE cert_id = 1";
        $cert_result = mysqli_query($conn, $cert_query);
        $cert_data = mysqli_fetch_assoc($cert_result);
        
        // Store results in session for use in the report
        $_SESSION['payslip_data'] = $search_results;
        $_SESSION['cert_data'] = $cert_data;
        
        // Redirect to report page
        header("Location: ../html/payslip_report.php");
        exit();
    } else {
        $search_error = "No matching records found. Please try different search criteria.";
        $_SESSION['search_error'] = $search_error;
        header("Location: ../html/request_payslip.php");
        exit();
    }
    
    mysqli_stmt_close($stmt);
}

// If somehow reached here without proper form submission, redirect back to form
if (!$found_records) {
    header("Location: ../html/request_payslip.php");
    exit();
}
?>