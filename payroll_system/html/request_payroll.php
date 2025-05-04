<?php
// First, check if we can connect to the database
$connection_error = false;
$responsibility_options = "";
$office_options = "";

try {
    // Include database connection file
    require_once '../php/db_connection.php';
    
    // Test connection
    if ($conn) {
        // Get responsibility centers
        $resp_query = "SELECT responsibility_id, responsibility_title FROM responsibility";
        $resp_result = mysqli_query($conn, $resp_query);
        
        if ($resp_result) {
            while($row = mysqli_fetch_assoc($resp_result)) {
                $responsibility_options .= "<option value='".$row['responsibility_title']."'>".$row['responsibility_title']."</option>";
            }
        }
        
        // Get offices
        $office_query = "SELECT office_id, office_title FROM office";
        $office_result = mysqli_query($conn, $office_query);
        
        if ($office_result) {
            while($row = mysqli_fetch_assoc($office_result)) {
                $office_options .= "<option value='".$row['office_title']."'>".$row['office_title']."</option>";
            }
        }
    } else {
        $connection_error = true;
    }
} catch (Exception $e) {
    $connection_error = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Request Form</title>
    <link rel="icon" type="image/png" href="../images/proll.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/request_payroll.css">
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
                <h2 class="header-title">Payroll Request Form</h2>
                <img src="../images/logo2.png" class="logo-right" alt="Right Logo">
            </div>

            <!-- Note for Users -->
            <div class="alert alert-info" role="alert">
                <strong>Note:</strong> You can either enter a <strong>Payroll No.</strong> or provide all other details (Start Date, End Date, Responsibility Center, and Office) for searching.
            </div>
            
            <?php if ($connection_error): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Database Connection Error:</strong> Could not connect to the database. Please contact the administrator.
            </div>
            <?php endif; ?>
            
            <form id="payrollRequestForm" action="../php/process_search.php" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">LGU:</label>
                        <select class="form-select" id="lgu" name="lgu">
                            <option value="General Santos City">General Santos City, South Cotabato</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Payroll No.:</label>
                        <input type="text" class="form-control" id="payrollNo" name="payrollNo" placeholder="Enter Payroll No.">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">For the Period of:</label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Date:</label>
                        <input type="date" class="form-control" id="date_start" name="date_start">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="date_end" name="date_end">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Responsibility Center:</label>
                        <select class="form-select" id="responsibility_title" name="responsibility_title">
                            <option value="" disabled selected>None</option>
                            <?php echo $responsibility_options; ?>
                            <?php if (empty($responsibility_options)): ?>
                                <option value="Accounting Department">Accounting Department</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Office:</label>
                        <select class="form-select" id="office_title" name="office_title">
                            <option value="" disabled selected>None</option>
                            <?php echo $office_options; ?>
                            <?php if (empty($office_options)): ?>
                                <option value="Head Office">Head Office</option>
                            <?php endif; ?>
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
        document.getElementById('payrollRequestForm').addEventListener('submit', function(e) {
            const payrollNo = document.getElementById('payrollNo').value;
            const startDate = document.getElementById('date_start').value;
            const endDate = document.getElementById('date_end').value;
            const responsibility = document.getElementById('responsibility_title').value;
            const office = document.getElementById('office_title').value;
            
            // Check if either payroll number is provided OR all other fields are provided
            if (!payrollNo && (!startDate || !endDate || !responsibility || !office)) {
                e.preventDefault();
                alert('Please either enter a Payroll No. OR complete all other search fields (Start Date, End Date, Responsibility Center, and Office).');
            }
        });
    </script>
</body>
</html>