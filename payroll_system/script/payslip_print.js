document.addEventListener('DOMContentLoaded', function() {
    // Generate barcodes for all payslips
    generateBarcodes();
    
    // Calculate values for payslips
    calculatePayslipValues();
    
    // Add event listener for download button
    const downloadBtn = document.getElementById('downloadPdfBtn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            generatePDF();
        });
    } else {
        console.error('Download button not found in the DOM');
    }
    
});

// Generate barcodes using JsBarcode library
function generateBarcodes() {
    const barcodeElements = document.querySelectorAll('.barcode-svg');
    
    barcodeElements.forEach(function(element) {
        const payrollId = element.getAttribute('data-payroll');
        const empNo = element.getAttribute('data-emp');
        const barcodeValue = `${payrollId}-${empNo}`;
        
        JsBarcode(element, barcodeValue, {
            format: "CODE128",
            width: 1.5,
            height: 30,
            displayValue: false,
            margin: 0
        });
    });
}

// Calculate various financial values for the payslips
function calculatePayslipValues() {
    const payslips = document.querySelectorAll('.payslip-container');
    
    payslips.forEach(function(payslip) {
        // Verify that the computed values match what we expect
        try {
            // Parse gross income
            const grossIncomeElement = payslip.querySelector('.gross-income');
            if (!grossIncomeElement) return;
            
            const grossIncomeText = grossIncomeElement.textContent.trim().replace(/,/g, '');
            const grossIncome = parseFloat(grossIncomeText);
            
            // Parse total deductions
            const totalDeductionsElement = payslip.querySelector('.total-deductions');
            if (!totalDeductionsElement) return;
            
            const totalDeductionsText = totalDeductionsElement.textContent.trim().replace(/,/g, '');
            const totalDeductions = parseFloat(totalDeductionsText);
            
            // Parse net pay
            const netPayElement = payslip.querySelector('.net-pay');
            if (!netPayElement) return;
            
            const netPayText = netPayElement.textContent.trim().replace(/,/g, '');
            const netPay = parseFloat(netPayText);
            
            // Validation - if these calculations don't match, there's an issue
            const calculatedNetPay = (grossIncome - totalDeductions).toFixed(2);
            
            // If there's a significant difference, log an error (for debugging)
            if (Math.abs(calculatedNetPay - netPay) > 0.1) {
                console.error('Net pay calculation mismatch:', {
                    grossIncome,
                    totalDeductions,
                    calculatedNetPay,
                    reportedNetPay: netPay
                });
            }
        } catch (error) {
            console.error('Error calculating payslip values:', error);
        }
    });
}

// Generate PDF using html2pdf library
function generatePDF() {
    console.log('Starting PDF generation...');
    
    // Make sure html2pdf is loaded
    if (typeof html2pdf === 'undefined') {
        console.error('html2pdf library not loaded!');
        alert('PDF generation library not loaded. Please check your internet connection and try again.');
        return;
    }
    
    // Create and show a loading indicator
    const loadingIndicator = document.createElement('div');
    loadingIndicator.style.position = 'fixed';
    loadingIndicator.style.top = '50%';
    loadingIndicator.style.left = '50%';
    loadingIndicator.style.transform = 'translate(-50%, -50%)';
    loadingIndicator.style.padding = '20px';
    loadingIndicator.style.background = 'rgba(0, 0, 0, 0.7)';
    loadingIndicator.style.color = 'white';
    loadingIndicator.style.borderRadius = '5px';
    loadingIndicator.style.zIndex = '1000';
    loadingIndicator.textContent = 'Generating PDF...';
    document.body.appendChild(loadingIndicator);
    
    // Hide the control buttons for PDF generation
    const controls = document.querySelector('.controls');
    if (controls) {
        controls.style.display = 'none';
    }
    
    // Define folio paper size (8.5 x 13 inches) in points (72 points per inch)
    const folioWidthPt = 8.5 * 72;  // 612 points
    const folioHeightPt = 13 * 72;  // 936 points
    
    try {
        // Get the payslips container to convert to PDF
        const element = document.querySelector('.payslips-wrapper');
        if (!element) {
            console.error('Could not find payslips-wrapper element');
            alert('Error: Could not find the content to convert to PDF.');
            document.body.removeChild(loadingIndicator);
            if (controls) controls.style.display = 'flex';
            return;
        }
        
        // Configure PDF options with adjusted page size to ensure fit
        const options = {
            margin: [20, 10, 20, 22], // top, right, bottom, left
            filename: 'payslip_report.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: {
                scale: 2,
                useCORS: true,
                logging: true
            },
            jsPDF: {
                unit: 'pt',
                format: [folioWidthPt, folioHeightPt],
                orientation: 'portrait',
                compress: true
            },
            pagebreak: {
                mode: ['avoid-all', 'css', 'legacy']
            }
        };
        
        console.log('PDF options configured:', options);
        
        // Generate PDF
        html2pdf()
            .from(element)
            .set(options)
            .save()
            .then(function() {
                console.log('PDF generated successfully');
                // Clean up
                document.body.removeChild(loadingIndicator);
                if (controls) controls.style.display = 'flex';
            })
            .catch(function(error) {
                console.error('Error generating PDF:', error);
                alert('There was an error generating the PDF. Please try again.');
                // Clean up
                document.body.removeChild(loadingIndicator);
                if (controls) controls.style.display = 'flex';
            });
    } catch (error) {
        console.error('Exception caught during PDF generation:', error);
        alert('An unexpected error occurred while generating the PDF.');
        // Clean up
        document.body.removeChild(loadingIndicator);
        if (controls) controls.style.display = 'flex';
    }
}
