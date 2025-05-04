<?php
// Start session to access search criteria
session_start();

// Include database connection
require_once 'db_connection.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'periodInfo' => [],
    'employees' => [],
    'certifications' => [
        'cert_a' => 'Department Head',
        'cert_b' => 'Accountant Name',
        'cert_c' => 'City Treasurer',
        'cert_d' => 'City Mayor',
        'cert_correct' => 'Accountant Name'
    ]
];

// Check if search criteria is available in session
if (!isset($_SESSION['search_type'])) {
    $response['message'] = 'No search criteria found. Please go back and search again.';
    echo json_encode($response);
    exit();
}

// Get search criteria from session
$searchType = $_SESSION['search_type'];

try {
    if ($searchType === 'payroll_no') {
        // Search by payroll number
        $payrollNo = $_SESSION['payroll_no'];
        
        // Query for period information
        $periodQuery = "SELECT * FROM payroll_comprehensive_view 
                        WHERE PayrollMstID = '$payrollNo' 
                        LIMIT 1";
        
        $periodResult = mysqli_query($conn, $periodQuery);
        
        if ($periodResult && mysqli_num_rows($periodResult) > 0) {
            $response['periodInfo'] = mysqli_fetch_assoc($periodResult);
            
            // Now fetch all employees for this payroll
            $employeesQuery = "SELECT * FROM payroll_comprehensive_view 
                              WHERE PayrollMstID = '$payrollNo'";
            
            $employeesResult = mysqli_query($conn, $employeesQuery);
            
            if ($employeesResult && mysqli_num_rows($employeesResult) > 0) {
                while ($row = mysqli_fetch_assoc($employeesResult)) {
                    $response['employees'][] = $row;
                }
                $response['success'] = true;
            } else {
                $response['message'] = 'Found payroll header but no employee data.';
            }
        } else {
            $response['message'] = 'Payroll not found.';
        }
    } else if ($searchType === 'criteria') {
        // Search by multiple criteria
        $dateStart = $_SESSION['date_start'];
        $dateEnd = $_SESSION['date_end'];
        $responsibilityTitle = $_SESSION['responsibility_title'];
        $officeTitle = $_SESSION['office_title'];
        
        // Query for period information
        $periodQuery = "SELECT * FROM payroll_comprehensive_view 
                        WHERE date_start = '$dateStart' 
                        AND date_end = '$dateEnd'
                        AND responsibility_title = '$responsibilityTitle'
                        AND office_title = '$officeTitle'
                        LIMIT 1";
        
        $periodResult = mysqli_query($conn, $periodQuery);
        
        if ($periodResult && mysqli_num_rows($periodResult) > 0) {
            $response['periodInfo'] = mysqli_fetch_assoc($periodResult);
            
            // Now fetch all employees for this payroll
            $employeesQuery = "SELECT * FROM payroll_comprehensive_view 
                              WHERE date_start = '$dateStart' 
                              AND date_end = '$dateEnd'
                              AND responsibility_title = '$responsibilityTitle'
                              AND office_title = '$officeTitle'";
            
            $employeesResult = mysqli_query($conn, $employeesQuery);
            
            if ($employeesResult && mysqli_num_rows($employeesResult) > 0) {
                while ($row = mysqli_fetch_assoc($employeesResult)) {
                    $response['employees'][] = $row;
                }
                $response['success'] = true;
            } else {
                $response['message'] = 'Found payroll header but no employee data.';
            }
        } else {
            $response['message'] = 'Payroll not found.';
        }
    } else {
        $response['message'] = 'Invalid search type.';
    }
} catch (Exception $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

// Add debug info in development mode
if (true) { // Change to false in production
    $response['debug'] = [
        'session' => $_SESSION,
        'queries' => [
            'period' => isset($periodQuery) ? $periodQuery : 'No query executed',
            'employees' => isset($employeesQuery) ? $employeesQuery : 'No query executed'
        ]
    ];
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>