// payroll_report.js
document.addEventListener('DOMContentLoaded', function() {
    // Fetch payroll data from the server
    fetchPayrollData()
        .then(data => {
            if (data && data.success) {
                populatePayrollData(data);
            } else {
                showError(data.message || 'Failed to load payroll data');
                console.error('Server response:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while loading payroll data. Please check the console for details.');
        });
    
    // Set up PDF download button
document.getElementById('download-pdf').addEventListener('click', function() {
    const element = document.getElementById('pdf-content');
    const downloadButton = document.getElementById('download-pdf'); // Get reference to download button
    
    // Hide the download button before generating PDF
    // This ensures it won't appear in the printed PDF
    downloadButton.style.display = 'none';
    
    // Add a page-break style and margin to the second page
    const secondPage = document.querySelector('.second-page');
    if (secondPage) {
        secondPage.style.pageBreakBefore = 'always';
        secondPage.style.paddingTop = '120px';
    }
        
    // Get the payroll number from the HTML content
    const payrollNumber = document.getElementById('payrollNo').textContent.trim();
    const filename = `payroll_${payrollNumber || 'unknown'}.pdf`; // Fallback to 'unknown' if empty

    // Configure the PDF options with 8.5 x 13 inches (legal or folio size)
    // Set specific margins: top, right, bottom, left
    // Setting bottom margin to 0 to remove the bottom whitespace
    const options = {
        margin: [0.2, 0.2, 0, 0.2], // [top, right, bottom, left] in inches
        filename: filename,
        image: { type: 'jpeg', quality: 1.0 },
        html2canvas: { 
            scale: 5, // Higher scale for better quality
            // Ensure all content is captured without cutting off
            scrollY: 0,
            windowHeight: document.documentElement.offsetHeight
        },
        jsPDF: { 
            unit: 'in', 
            format: [8.5, 13], 
            orientation: 'landscape',
            compress: true // Compress the PDF to reduce size
        }
    };
    
    // Generate and save the PDF
    html2pdf()
        .from(element)
        .set(options)
        .save()
        .then(function() {
            // Show the download button again after PDF generation is complete
            // This makes it available if the user wants to download again
            downloadButton.style.display = 'block'; 
        });
});
});

/**
 * Fetch payroll data from the server
 */
async function fetchPayrollData() {
    try {
        const response = await fetch('../php/get_payroll_data.php');
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Fetch error:', error);
        return { 
            success: false, 
            message: 'Failed to fetch data from server. Error: ' + error.message 
        };
    }
}

/**
 * Display error message
 */
function showError(message) {
    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger';
    errorDiv.style.padding = '15px';
    errorDiv.style.margin = '20px';
    errorDiv.style.backgroundColor = '#f8d7da';
    errorDiv.style.color = '#721c24';
    errorDiv.style.borderRadius = '5px';
    errorDiv.style.border = '1px solid #f5c6cb';
    errorDiv.innerHTML = `<strong>Error:</strong> ${message}<br>
                          <p>Possible solutions:</p>
                          <ul>
                            <li>Check if the database connection is correct</li>
                            <li>Verify that all required PHP files are present</li>
                            <li>Make sure the payroll_comprehensive_view exists in your database</li>
                            <li><a href="../html/request_payroll.php" style="color: #721c24; text-decoration: underline;">Go back to search page</a></li>
                          </ul>`;
    
    // Insert at the top of the document
    document.body.insertBefore(errorDiv, document.body.firstChild);
}

/**
 * Populate payroll data into the HTML template
 */
function populatePayrollData(data) {
    // Set payroll period dates
    document.getElementById('date_start').textContent = formatDate(data.periodInfo.date_start);
    document.getElementById('date_end').textContent = formatDate(data.periodInfo.date_end);
    
    // Set payroll header information
    document.getElementById('responsibility').textContent = data.periodInfo.responsibility_title;
    document.getElementById('payrollNo').textContent = data.periodInfo.PayrollMstID;
    document.getElementById('office').textContent = data.periodInfo.office_title;
    
    // Set certification names
    document.getElementById('cert_a').textContent = data.certifications.cert_a;
    document.getElementById('cert_b').textContent = data.certifications.cert_b;
    document.getElementById('cert_c').textContent = data.certifications.cert_c;
    document.getElementById('cert_d').textContent = data.certifications.cert_d;
    document.getElementById('cert_correct').textContent = data.certifications.cert_correct;
    
    // Populate employee rows
    populateEmployeeRows(data.employees);
    
    // Calculate and populate totals
    calculateTotals(data.employees);
}

/**
 * Format date in MM/DD/YYYY format
 */
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric'
        });
    } catch (e) {
        console.error('Date formatting error:', e);
        return dateString;
    }
}

/**
 * Create employee rows in both tables
 */
function populateEmployeeRows(employees) {
    if (!employees || employees.length === 0) {
        showError('No employee data available to display.');
        return;
    }
    
    // Get the employee row templates
    const firstTableRow = document.querySelector('.payroll-table:first-of-type tbody tr.expandable:first-of-type');
    const secondTableRow = document.querySelector('.second-page .payroll-table tbody tr.expandable:first-of-type');
    
    // Get the parent containers
    const firstTableBody = firstTableRow.parentElement;
    const secondTableBody = secondTableRow.parentElement;
    
    // Find the total rows in first table
    const firstTableTotalRows = firstTableBody.querySelectorAll('tr:nth-last-child(-n+4)');
    
    // Remove template rows
    firstTableRow.remove();
    secondTableRow.remove();
    
    // Create rows for each employee
    employees.forEach((employee, index) => {
        // Create first table row
        const newFirstRow = createEmployeeRowFirstTable(employee, index + 1);
        
        // Create second table row
        const newSecondRow = createEmployeeRowSecondTable(employee, index + 1);
        
        // Insert rows before the total rows
        firstTableBody.insertBefore(newFirstRow, firstTableTotalRows[0]);
        secondTableBody.insertBefore(newSecondRow, secondTableBody.querySelector('.total-row'));
    });
}

/**
 * Create an employee row for the first table
 */
function createEmployeeRowFirstTable(employee, rowNum) {
    const row = document.createElement('tr');
    row.className = 'expandable';
    
    row.innerHTML = `
        <td>${rowNum}</td>
        <td>${employee.emp_name || ''}</td>
        <td>${employee.emp_position || employee.PositionTitle || ''}</td>
        <td>${employee.emp_no || employee.EmpNo || ''}</td>
        <td>${formatCurrency(employee.Salary)}</td>
        <td>${formatCurrency(employee.PERA)}</td>
        <td>${formatCurrency(employee.GrossIncome)}</td>
        <td>${formatCurrency(employee.Tardiness)}</td>
        <td>${formatCurrency(employee.IncomeTaxWithHeld)}</td>
        <td>${formatCurrency(employee.PHIC_EmployeeShare)}</td>
        <td>${formatCurrency(employee.PHIC_EmployerShare)}</td>
        <td>${formatCurrency(employee.PAGIBIG_EmployeeShare)}</td>
        <td>${formatCurrency(employee.PAGIBIG_EmployerShare)}</td>
        <td>${formatCurrency(employee.PAGIBIG_II)}</td>
        <td>${formatCurrency(employee.GSIS_EmployeeShare)}</td>
        <td>${formatCurrency(employee.GSIS_EmployerShare)}</td>
        <td>${formatCurrency(employee.GSIS_ECC)}</td>
        <td>${formatCurrency(employee.PAGIBIG_MPL)}</td>
        <td>${formatCurrency(employee.PAGIBIG_Housing)}</td>
        <td>${formatCurrency(employee.PAGIBIG_LotPurchase)}</td>
        <td>${formatCurrency(employee.GSCGEA_Dues)}</td>
        <td>${formatCurrency(employee.GSCGEA_OtherDues)}</td>
        <td>${formatCurrency(employee.GSCCoop)}</td>
    `;
    
    return row;
}

/**
 * Create an employee row for the second table
 */
function createEmployeeRowSecondTable(employee, rowNum) {
    const row = document.createElement('tr');
    row.className = 'expandable';
    
    row.innerHTML = `
        <td>${rowNum}</td>
        <td>${employee.emp_name || ''}</td>
        <td>${employee.emp_position || employee.PositionTitle || ''}</td>
        <td>${employee.emp_no || employee.EmpNo || ''}</td>
        <td>${formatCurrency(employee.GSIS_MPL)}</td>
        <td>${formatCurrency(employee.ceap)}</td>
        <td>${formatCurrency(employee.GSIS_ComputerLoan)}</td>
        <td>${formatCurrency(employee.GSIS_MPLLite)}</td>
        <td>${formatCurrency(employee.GSIS_PolicyLoanRegular)}</td>
        <td>${formatCurrency(employee.GSIS_EmergencyLoan)}</td>
        <td>${formatCurrency(employee.GSIS_GFAL)}</td>
        <td>${formatCurrency(employee.BankLoan_LBP)}</td>
        <td>${formatCurrency(employee.GrossDeductionGovernment)}</td>
        <td>${formatCurrency(employee.GrossDeductionPersonal)}</td>
        <td>${formatCurrency(employee.NetPay)}</td>
        <td>${formatCurrency(employee.NetPay1stHalf)}</td>
        <td>${formatCurrency(employee.NetPay2ndHalf)}</td>
    `;
    
    return row;
}

/**
 * Format currency values
 */
function formatCurrency(value) {
    if (value === null || value === undefined || value === '') return '0.00';
    return parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Calculate and populate totals
 */
function calculateTotals(employees) {
    // First table totals
    const firstTableTotals = {
        p_salaries: 0,
        p_pera: 0,
        p_gross: 0,
        p_tardiness: 0,
        p_tax: 0,
        p_philper: 0,
        p_philgovt: 0,
        p_pagper: 0,
        p_paggovt: 0,
        p_pag2: 0,
        p_gsis_per: 0,
        p_gsis_govt: 0,
        p_ecc: 0,
        p_mpl: 0,
        p_housing: 0,
        p_lotpurchase: 0,
        p_dues: 0,
        p_otherdues: 0,
        p_coop: 0
    };
    
    // Second table totals
    const secondTableTotals = {
        p_gsis_mpl: 0,
        p_ceap: 0,
        p_comp: 0,
        p_mpl_lite: 0,
        p_pl_reg: 0,
        p_gsis_el: 0,
        p_gfal: 0,
        p_lbp_tot: 0,
        p_gross_gov_ded: 0,
        p_gross_per_ded: 0,
        p_net_pay: 0,
        p_first_half: 0,
        p_second_half: 0
    };
    
    // Calculate totals
    employees.forEach(emp => {
        // First table
        firstTableTotals.p_salaries += parseFloat(emp.Salary || 0);
        firstTableTotals.p_pera += parseFloat(emp.PERA || 0);
        firstTableTotals.p_gross += parseFloat(emp.GrossIncome || 0);
        firstTableTotals.p_tardiness += parseFloat(emp.Tardiness || 0);
        firstTableTotals.p_tax += parseFloat(emp.IncomeTaxWithHeld || 0);
        firstTableTotals.p_philper += parseFloat(emp.PHIC_EmployeeShare || 0);
        firstTableTotals.p_philgovt += parseFloat(emp.PHIC_EmployerShare || 0);
        firstTableTotals.p_pagper += parseFloat(emp.PAGIBIG_EmployeeShare || 0);
        firstTableTotals.p_paggovt += parseFloat(emp.PAGIBIG_EmployerShare || 0);
        firstTableTotals.p_pag2 += parseFloat(emp.PAGIBIG_II || 0);
        firstTableTotals.p_gsis_per += parseFloat(emp.GSIS_EmployeeShare || 0);
        firstTableTotals.p_gsis_govt += parseFloat(emp.GSIS_EmployerShare || 0);
        firstTableTotals.p_ecc += parseFloat(emp.GSIS_ECC || 0);
        firstTableTotals.p_mpl += parseFloat(emp.PAGIBIG_MPL || 0);
        firstTableTotals.p_housing += parseFloat(emp.PAGIBIG_Housing || 0);
        firstTableTotals.p_lotpurchase += parseFloat(emp.PAGIBIG_LotPurchase || 0);
        firstTableTotals.p_dues += parseFloat(emp.GSCGEA_Dues || 0);
        firstTableTotals.p_otherdues += parseFloat(emp.GSCGEA_OtherDues || 0);
        firstTableTotals.p_coop += parseFloat(emp.GSCCoop || 0);
        
        // Second table
        secondTableTotals.p_gsis_mpl += parseFloat(emp.GSIS_MPL || 0);
        secondTableTotals.p_ceap += parseFloat(emp.ceap || 0);
        secondTableTotals.p_comp += parseFloat(emp.GSIS_ComputerLoan || 0);
        secondTableTotals.p_mpl_lite += parseFloat(emp.GSIS_MPLLite || 0);
        secondTableTotals.p_pl_reg += parseFloat(emp.GSIS_PolicyLoanRegular || 0);
        secondTableTotals.p_gsis_el += parseFloat(emp.GSIS_EmergencyLoan || 0);
        secondTableTotals.p_gfal += parseFloat(emp.GSIS_GFAL || 0);
        secondTableTotals.p_lbp_tot += parseFloat(emp.BankLoan_LBP || 0);
        secondTableTotals.p_gross_gov_ded += parseFloat(emp.GrossDeductionGovernment || 0);
        secondTableTotals.p_gross_per_ded += parseFloat(emp.GrossDeductionPersonal || 0);
        secondTableTotals.p_net_pay += parseFloat(emp.NetPay || 0);
        secondTableTotals.p_first_half += parseFloat(emp.NetPay1stHalf || 0);
        secondTableTotals.p_second_half += parseFloat(emp.NetPay2ndHalf || 0);
    });
    
    // Populate first table totals
    for (const [key, value] of Object.entries(firstTableTotals)) {
        const element = document.getElementById(key);
        if (element) {
            element.textContent = formatCurrency(value);
        }
        
        // Also set the grand total (same as page total for now)
        const grandElement = document.getElementById(key.replace('p_', 'g_'));
        if (grandElement) {
            grandElement.textContent = formatCurrency(value);
        }
    }
    
    // Calculate and populate subtotals for first table
    document.getElementById('p_total_salarypera').textContent = formatCurrency(
        firstTableTotals.p_salaries + firstTableTotals.p_pera
    );
    document.getElementById('g_total_salarypera').textContent = formatCurrency(
        firstTableTotals.p_salaries + firstTableTotals.p_pera
    );
    
    document.getElementById('p_totalphil').textContent = formatCurrency(
        firstTableTotals.p_philper + firstTableTotals.p_philgovt
    );
    document.getElementById('g_totalphil').textContent = formatCurrency(
        firstTableTotals.p_philper + firstTableTotals.p_philgovt
    );
    
    document.getElementById('p_totalpag').textContent = formatCurrency(
        firstTableTotals.p_pagper + firstTableTotals.p_paggovt + firstTableTotals.p_pag2
    );
    document.getElementById('g_totalpag').textContent = formatCurrency(
        firstTableTotals.p_pagper + firstTableTotals.p_paggovt + firstTableTotals.p_pag2
    );
    
    document.getElementById('p_totalgsis').textContent = formatCurrency(
        firstTableTotals.p_gsis_per + firstTableTotals.p_gsis_govt + firstTableTotals.p_ecc
    );
    document.getElementById('g_totalgsis').textContent = formatCurrency(
        firstTableTotals.p_gsis_per + firstTableTotals.p_gsis_govt + firstTableTotals.p_ecc
    );
    
    document.getElementById('p_totalpagloans').textContent = formatCurrency(
        firstTableTotals.p_mpl + firstTableTotals.p_housing + firstTableTotals.p_lotpurchase
    );
    document.getElementById('g_totalpagloans').textContent = formatCurrency(
        firstTableTotals.p_mpl + firstTableTotals.p_housing + firstTableTotals.p_lotpurchase
    );
    
    document.getElementById('p_totalothers').textContent = formatCurrency(
        firstTableTotals.p_dues + firstTableTotals.p_otherdues + firstTableTotals.p_coop
    );
    document.getElementById('g_totalothers').textContent = formatCurrency(
        firstTableTotals.p_dues + firstTableTotals.p_otherdues + firstTableTotals.p_coop
    );
    
    // Populate second table totals
    for (const [key, value] of Object.entries(secondTableTotals)) {
        const element = document.getElementById(key);
        if (element) {
            element.textContent = formatCurrency(value);
        }
        
        // Also set the grand total (same as page total for now)
        const grandElement = document.getElementById(key.replace('p_', 'g_'));
        if (grandElement) {
            grandElement.textContent = formatCurrency(value);
        }
    }
    
    // Calculate and populate subtotals for second table
    document.getElementById('p_total_gsis_comp').textContent = formatCurrency(
        secondTableTotals.p_gsis_mpl + secondTableTotals.p_ceap + secondTableTotals.p_comp
    );
    document.getElementById('g_total_gsis_comp').textContent = formatCurrency(
        secondTableTotals.p_gsis_mpl + secondTableTotals.p_ceap + secondTableTotals.p_comp
    );
    
    document.getElementById('p_total_mpl_gfal').textContent = formatCurrency(
        secondTableTotals.p_mpl_lite + secondTableTotals.p_pl_reg + 
        secondTableTotals.p_gsis_el + secondTableTotals.p_gfal
    );
    document.getElementById('g_total_mpl_gfal').textContent = formatCurrency(
        secondTableTotals.p_mpl_lite + secondTableTotals.p_pl_reg + 
        secondTableTotals.p_gsis_el + secondTableTotals.p_gfal
    );
    
    document.getElementById('p_total_lbp').textContent = formatCurrency(secondTableTotals.p_lbp_tot);
    document.getElementById('g_total_lbp').textContent = formatCurrency(secondTableTotals.p_lbp_tot);
    
    document.getElementById('p_total_proceeds').textContent = formatCurrency(
        secondTableTotals.p_first_half + secondTableTotals.p_second_half
    );
    document.getElementById('g_total_proceeds').textContent = formatCurrency(
        secondTableTotals.p_first_half + secondTableTotals.p_second_half
    );
    
    // Update the "APPROVE FOR PAYMENT" amount based on g_total_proceeds
    updateApproveForPaymentAmount();
}

/**
 * Update the "APPROVE FOR PAYMENT" amount based on g_total_proceeds
 */
function updateApproveForPaymentAmount() {
    // Get the g_total_proceeds value
    const totalProceedsElement = document.getElementById('g_total_proceeds');
    
    if (totalProceedsElement) {
        // Get the value (remove commas and convert to number)
        const totalProceeds = parseFloat(totalProceedsElement.textContent.replace(/,/g, ''));
        
        // Find the "APPROVE FOR PAYMENT" span element
        const approvePaymentSpan = document.querySelector('.amount');
        
        if (approvePaymentSpan) {
            // Format the value with peso sign and update the span
            approvePaymentSpan.textContent = `₱${formatCurrency(totalProceeds)}`;
        }
    }
}