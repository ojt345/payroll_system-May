<?php
session_start();

// Check if payslip data exists in session
if (!isset($_SESSION['payslip_data']) || empty($_SESSION['payslip_data'])) {
    header("Location: ../html/request_payslip.php");
    exit();
}

$payslip_data = $_SESSION['payslip_data'];
$cert_data = $_SESSION['cert_data'];

// Format the date range for display
$date_format = '';
if (!empty($payslip_data[0]['date_start']) && !empty($payslip_data[0]['date_end'])) {
    $start_date = date('F j', strtotime($payslip_data[0]['date_start']));
    $end_date = date('j, Y', strtotime($payslip_data[0]['date_end']));
    $date_format = "For the period of {$start_date} - {$end_date}";
}

// Get current date and time for the footer
$print_datetime = date('n/j/Y, g:i:sA');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Advice Slip</title>
    <link rel="stylesheet" href="../css/payslip_report.css">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
</head>
<body>
    <div class="controls">
        <button id="downloadPdfBtn" class="btn">Download as PDF</button>
    </div>
    
    <div class="payslips-wrapper">
        <?php 
        // Loop through all payslip data
        $counter = 0;
        foreach ($payslip_data as $index => $employee): 
            // Start a new row for every 2 payslips
            if ($counter % 2 == 0): ?>
                <div class="payslip-row">
            <?php endif; ?>
            
            <div class="payslip-container" data-emp-no="<?php echo htmlspecialchars($employee['emp_no']); ?>" data-payroll-id="<?php echo htmlspecialchars($employee['PayrollMstID']); ?>">
                <div class="header">
                    <img src="../images/logo1.png" alt="General Santos City Logo" class="logo1">
                    <div class="header-text">
                        <h3>Republic of the Philippines</h3>
                        <h3>General Santos City</h3>
                        <h3 class="nowrap">Human Resource Management and Development Office</h3>
                    </div>
                    <img src="../images/logo2.png" alt="Magandang Gensan Logo" class="logo2">
                </div>
                <img src="../images/logo2.2.png" alt="Divider" class="divider">
                
                <div class="title">Payroll Advice Slip</div>
                <div class="period"><?php echo $date_format; ?></div>
                
                <div class="employee-info">
                    <div class="employee-info-row">
                        <div class="info-label">Employee ID#:</div>
                        <div class="info-value emp-no"><?php echo htmlspecialchars($employee['emp_no']); ?></div>
                    </div>
                    <div class="employee-info-row">
                        <div class="info-label">Employee Name:</div>
                        <div class="info-value emp-name"><?php echo htmlspecialchars($employee['emp_name']); ?></div>
                    </div>
                    <div class="employee-info-row">
                        <div class="info-label">Official Designation:</div>
                        <div class="info-value"><?php echo htmlspecialchars($employee['emp_position']); ?></div>
                    </div>
                    <div class="employee-info-row">
                        <div class="info-label">Home Office:</div>
                        <div class="info-value"><?php echo htmlspecialchars($employee['office_title']); ?></div>
                    </div>
                    <div class="employee-info-row">
                        <div class="info-label">Tax Exemption Code:</div>
                        <div class="info-value"><?php echo htmlspecialchars($employee['tax_code']); ?></div>
                    </div>
                </div>
                
                <div class="income-section">
                    <div class="section-title">INCOME:</div>
                    <div class="item">
                        <span class="item-label">Monthly Salary</span>
                        <span class="item-value"><?php echo number_format($employee['Salary'], 2); ?></span>
                    </div>
                    <div class="item">
                        <span class="item-label">PERA</span>
                        <span class="item-value"><?php echo number_format($employee['PERA'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <div class="item">
                            <span class="item-label">Gross Income</span>
                            <span class="item-value gross-income"><?php echo number_format($employee['GrossIncome'], 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="deductions-section">
                    <div class="section-title">DEDUCTIONS:</div>
                    <div class="subsection-title">Government Mandated Deductions</div>
                    <div class="item">
                        <span class="item-label">Leave w/o Pay / UT (LWOP/UT)</span>
                        <span class="item-value"><?php echo number_format($employee['Tardiness'], 2); ?></span>
                    </div>
                    <div class="item">
                        <span class="item-label">Income Tax Withheld (ITW)</span>
                        <span class="item-value"><?php echo number_format($employee['IncomeTaxWithHeld'], 2); ?></span>
                    </div>
                    <div class="item">
                        <span class="item-label">Phil Health Contribution</span>
                        <span class="item-value"><?php echo number_format($employee['PHIC_EmployeeShare'], 2); ?></span>
                    </div>
                    <div class="item">
                        <span class="item-label">PagIBIG Fund Contribution</span>
                        <span class="item-value"><?php echo number_format($employee['PAGIBIG_EmployeeShare'], 2); ?></span>
                    </div>
                    <div class="item">
                        <span class="item-label">GSIS Insurance Premium</span>
                        <span class="item-value"><?php echo number_format($employee['GSIS_EmployeeShare'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <div class="item">
                            <span class="item-label">Total Mandated Deductions</span>
                            <span class="item-value"><?php echo number_format($employee['GrossDeductionGovernment'], 2); ?></span>
                        </div>
                    </div>
                    
                    <div class="subsection-title">Personal Deductions</div>
                    <?php if ($employee['GSIS_MPL'] > 0): ?>
                    <div class="item">
                        <span class="item-label">GSIS MPL</span>
                        <span class="item-value"><?php echo number_format($employee['GSIS_MPL'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($employee['GSIS_ComputerLoan'] > 0): ?>
                    <div class="item">
                        <span class="item-label">Computer Loan</span>
                        <span class="item-value"><?php echo number_format($employee['GSIS_ComputerLoan'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($employee['GSIS_PolicyLoanRegular'] > 0): ?>
                    <div class="item">
                        <span class="item-label">PL (Regular)</span>
                        <span class="item-value"><?php echo number_format($employee['GSIS_PolicyLoanRegular'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($employee['GSIS_EmergencyLoan'] > 0): ?>
                    <div class="item">
                        <span class="item-label">GSIS-EL</span>
                        <span class="item-value"><?php echo number_format($employee['GSIS_EmergencyLoan'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($employee['GSIS_GFAL'] > 0): ?>
                    <div class="item">
                        <span class="item-label">GFAL</span>
                        <span class="item-value"><?php echo number_format($employee['GSIS_GFAL'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($employee['PAGIBIG_MPL'] > 0): ?>
                    <div class="item">
                        <span class="item-label">PAGIBIG MPL</span>
                        <span class="item-value"><?php echo number_format($employee['PAGIBIG_MPL'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($employee['GSCGEA_Dues'] > 0): ?>
                    <div class="item">
                        <span class="item-label">GSCGEA Dues</span>
                        <span class="item-value"><?php echo number_format($employee['GSCGEA_Dues'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($employee['GSCGEA_OtherDues'] > 0): ?>
                    <div class="item">
                        <span class="item-label">GSCGEA Other Dues</span>
                        <span class="item-value"><?php echo number_format($employee['GSCGEA_OtherDues'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="total-row">
                        <div class="item">
                            <span class="item-label">Total Personal Deductions</span>
                            <span class="item-value"><?php echo number_format($employee['GrossDeductionPersonal'], 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="summary-row border-single">
                    <span class="item-label">Total Deductions:</span>
                    <span class="item-value total-deductions"><?php 
                        $total_deductions = $employee['GrossDeductionGovernment'] + $employee['GrossDeductionPersonal'];
                        echo number_format($total_deductions, 2); 
                    ?></span>
                </div>

                <div class="summary-row border-double">
                    <span class="item-label">Net Take Home Pay :</span>
                    <span class="item-value net-pay"><?php echo number_format($employee['NetPay'], 2); ?></span>
                </div>

                <div class="summary-row border-single">
                    <span class="item-label">1st half proceeds:</span>
                    <span class="item-value"><?php echo number_format($employee['NetPay1stHalf'], 2); ?></span>
                </div>

                <div class="summary-row border-single">
                    <span class="item-label">2nd half proceeds:</span>
                    <span class="item-value"><?php echo number_format($employee['NetPay2ndHalf'], 2); ?></span>
                </div>

                <div class="signatures">
                    <div class="signature-block">
                        <div class="signature-label">Noted by:</div>
                        <div class="signature-line"></div>
                        <div class="signature-name"><?php echo htmlspecialchars($cert_data['cert_d']); ?></div>
                    </div>
                    <div class="signature-block">
                        <div class="signature-label">Received by:</div>
                        <div class="signature-line"></div>
                        <div class="signature-name"><?php echo htmlspecialchars($employee['emp_name']); ?></div>
                    </div>
                </div>
                
                <div class="footer-container">
                    <div class="barcode">
                        <svg class="barcode-svg" 
                             data-payroll="<?php echo htmlspecialchars($employee['PayrollMstID']); ?>" 
                             data-emp="<?php echo htmlspecialchars($employee['emp_no']); ?>"></svg>
                    </div>
                    <div class="payslip-footer">
                        Payslip #<?php echo htmlspecialchars($employee['payroll_id']); ?>; Printed: <?php echo $print_datetime; ?>
                    </div>
                </div>
            </div>
            
            <?php
            $counter++;
            // Close the row after every 2 payslips or at the end
            if ($counter % 2 == 0 || $index == count($payslip_data) - 1): ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="../script/payslip_print.js"></script>
</body>
</html>

