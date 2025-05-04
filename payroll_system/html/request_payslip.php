<?php
session_start();
$connection_error = false;
$responsibility_options = '';
$office_options = '';

if (file_exists('../php/db_connection.php')) {
    require_once '../php/db_connection.php';
    
    // Check if connection is successful (assuming $conn is your database connection variable from db_connection.php)
    if (isset($conn)) {
        // Fetch responsibility options from database
        $responsibility_query = "SELECT responsibility_id, responsibility_title FROM responsibility ORDER BY responsibility_title";
        $responsibility_result = mysqli_query($conn, $responsibility_query);
        
        if ($responsibility_result) {
            while ($row = mysqli_fetch_assoc($responsibility_result)) {
                $responsibility_options .= "<option value='" . htmlspecialchars($row['responsibility_title']) . "'>" . htmlspecialchars($row['responsibility_title']) . "</option>";
            }
        }
        
        // Fetch office options from database
        $office_query = "SELECT office_id, office_title FROM office ORDER BY office_title";
        $office_result = mysqli_query($conn, $office_query);
        
        if ($office_result) {
            while ($row = mysqli_fetch_assoc($office_result)) {
                $office_options .= "<option value='" . htmlspecialchars($row['office_title']) . "'>" . htmlspecialchars($row['office_title']) . "</option>";
            }
        }
    } else {
        $connection_error = true;
    }
} else {
    $connection_error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip Request Form</title>
    <link rel="icon" type="image/png" href="../images/proll.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/request_payslip.css">
    <style>
        /* Additional styles to ensure visibility */
        .form-container {
            background-color: #fff;
        }
        .btn-primary {
            display: block !important;
            visibility: visible !important;
        }
        label, select, input, button {
            display: block !important;
            visibility: visible !important;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="form-container p-4 shadow-lg">
            <div class="payroll-header">
                <img src="../images/logo1.png" class="logo-left" alt="Left Logo">
                <h2 class="header-title">Payslip Request Form</h2>
                <img src="../images/logo2.png" class="logo-right" alt="Right Logo">
            </div>

            <!-- Note for Users -->
            <div class="alert alert-info" role="alert">
                <strong>Note:</strong> You can either enter a <strong>Payroll No.</strong> or provide all other details (Employee #, Start Date, End Date, Responsibility Center, and Office) for searching.
            </div>
            
            <?php if ($connection_error): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Database Connection Error:</strong> Could not connect to the database. Please contact the administrator.
            </div>
            <?php endif; ?>
            
            <form id="payslipRequestForm" action="../php/payslip_search.php" method="POST">
                <div class="row mb-3">
                    
                    <div class="col-md-4">
                        <label class="form-label">Payroll No.:</label>
                        <input type="text" class="form-control" id="PayrollMstID" name="PayrollMstID" placeholder="Enter Payroll No.">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Employee #:</label>
                        <input type="text" class="form-control" id="emp_no" name="emp_no">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Start Date:</label>
                        <input type="date" class="form-control" id="date_start" name="date_start">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="date_end" name="date_end">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Responsibility Center:</label>
                        <select class="form-select" id="responsibility_title" name="responsibility_title">
                            <option value="" disabled selected>None</option>
                            <?php echo $responsibility_options; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Office:</label>
                        <select class="form-select" id="office_title" name="office_title">
                            <option value="" disabled selected>None</option>
                            <?php echo $office_options; ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple client-side validation
        document.getElementById('payslipRequestForm').addEventListener('submit', function(e) {
            const payrollNo = document.getElementById('PayrollMstID').value;
            const empNo = document.getElementById('emp_no').value;
            const startDate = document.getElementById('date_start').value;
            const endDate = document.getElementById('date_end').value;
            const responsibility = document.getElementById('responsibility_title').value;
            const office = document.getElementById('office_title').value;
            
            // Check if either payroll number is provided OR all other fields are provided
            if (!payrollNo && (!empNo || !startDate || !endDate || !responsibility || !office)) {
                e.preventDefault();
                alert('Please either enter a Payroll No. OR complete all other search fields (Employee #, Start Date, End Date, Responsibility Center, and Office).');
            }
        });
    </script>
</body>
</html>