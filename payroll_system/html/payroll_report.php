<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll View</title>
    <link rel="icon" type="image/png" href="../images/proll.png">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="../script/payroll_report.js" defer></script>
</head>
<body>


<style>


</style>

    <!-- Wrapper for All Pages -->
    <div id="pdf-content">
        <!-- ======================== FIRST PAGE ======================== -->
        <div class="pagefirst">
            <div class="payroll-header">
                <div class="download-button-placeholder">
                    <button id="download-pdf" class="no-print">Download as PDF</button>
                </div>
                <img src='../images/logo1.png' class="logo-left">
                <div class="logo-right-container">
                    <img src='../images/logo2.png' class="logo-right">
                    <img src='../images/logo2.2.png' class="logo-right-bg">
                </div>
                <h3>PAYROLL</h3>
                <p>For the period of <span id="date_start"></span> - <span id="date_end"></span></p>
            </div>

            <div class="details">
                <div class="details-group">
                    <p>LGU: General Santos City, 9, South Cotabato</p>
                    <p>Responsibility Center: <span id="responsibility" class="underline">&nbsp;</span></p>
                    <p>Payroll No.: <span id="payrollNo" class="underline">&nbsp;</span></p>
                </div>

                <div class="details-group align-end">
                    <p>Fund: <span class="underline">&nbsp;</span></p>
                    <p>Office: <span id="office" class="underline">&nbsp;</span></p>
                    <p>
                        Sheet <span id='sheet-no' class="underline">1</span> of 
                        <span id='total-sheets' class="underline">1</span> Sheets
                    </p>
                </div>
            </div>

            <p class="payroll-note">
                We acknowledge receipts of cash shown opposite our names as full compensation for services rendered for the period covered.
            </p>

            <!-- Payroll Table -->
            <table class="payroll-table expandable">
                <thead>
                    <tr>
                        <th rowspan="4">#</th>
                        <th rowspan="4">Name</th>
                        <th rowspan="4">Position</th>
                        <th rowspan="4">Emp#</th>
                        <th colspan="3">COMPENSATIONS</th>
                        <th colspan="16">DEDUCTIONS</th>
                    </tr>
                    <tr>
                        <th rowspan="3">Salaries and Wages</th>
                        <th rowspan="3">PERA</th>
                        <th rowspan="3">Gross Amount Earned</th>
                        <th rowspan="3">Tardiness / Under Time</th>
                        <th rowspan="3">Tax Withheld</th>
                        <th colspan="8">CONTRIBUTIONS</th>
                        <th colspan="3">PAGIBIG LOANS</th>
                        <th colspan="3">OTHERS</th>
                    </tr>
                    <tr>
                        <th colspan="2">Phil Health</th>
                        <th colspan="3">PAGIBIG Fund</th>
                        <th colspan="3">GSIS</th>
                        <th rowspan="2">MPL</th>
                        <th rowspan="2">Housing</th>
                        <th rowspan="2">Lot Purchase / HEAL</th>
                        <th rowspan="2">GSCGEA Dues</th>
                        <th rowspan="2">GSCGEA Other Dues</th>
                        <th rowspan="2">GSC Coop</th>
                    </tr>
                    <tr>
                        <th>Personal</th>
                        <th>Gov't</th>
                        <th>Personal</th>
                        <th>Gov't</th>
                        <th>PagIBIG II</th>
                        <th>Personal</th>
                        <th>Gov't</th>
                        <th>ECC</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Updated first table row template -->
                    <tr class="expandable">
                        <td id="id"></td> 
                        <td id="name"></td> 
                        <td id="PositionTitle"></td>
                        <td id="EmpNo"></td> 
                        <td id="Salary"></td> 
                        <td id="PERA"></td> 
                        <td id="GrossIncome"></td>
                        <td id="Tardiness"></td> 
                        <td id="IncomeTaxWithHeld"></td> 
                        <td id="PHIC_EmployeeShare"></td> 
                        <td id="PHIC_EmployerShare"></td>
                        <td id="PAGIBIG_EmployeeShare"></td> 
                        <td id="PAGIBIG_EmployerShare"></td> 
                        <td id="PAGIBIG_II"></td> 
                        <td id="GSIS_EmployeeShare"></td> 
                        <td id="GSIS_EmployerShare"></td> 
                        <td id="GSIS_ECC"></td>
                        <td id="PAGIBIG_MPL"></td> 
                        <td id="PAGIBIG_Housing"></td> 
                        <td id="PAGIBIG_LotPurchase"></td> 
                        <td id="GSCGEA_Dues"></td> 
                        <td id="GSCGEA_OtherDues"></td> 
                        <td id="GSCCoop"></td>
                    </tr>


                    <!-- Page total -->
                    <tr class="expandable total-row">
                        <td colspan="4" rowspan="2">Page Total:</td>
                        <td id="p_salaries"></td>
                        <td id="p_pera"></td>
                        <td id="p_gross" rowspan="2"></td>
                        <td id="p_tardiness" rowspan="2"></td>
                        <td id="p_tax" rowspan="2"></td>
                        <td id="p_philper"></td>
                        <td id="p_philgovt"></td>
                        <td id="p_pagper"></td>
                        <td id="p_paggovt"></td>
                        <td id="p_pag2"></td>
                        <td id="p_gsis_per"></td>
                        <td id="p_gsis_govt"></td>
                        <td id="p_ecc"></td>
                        <td id="p_mpl"></td>
                        <td id="p_housing"></td>
                        <td id="p_lotpurchase"></td>
                        <td id="p_dues"></td>
                        <td id="p_otherdues"></td>
                        <td id="p_coop"></td>
                    </tr>
                    <!-- Second Row -->
                    <tr class="expandable total-row">
                        <td id="p_total_salarypera" colspan="2"></td>
                        <td id="p_totalphil" colspan="2"></td>
                        <td id="p_totalpag" colspan="3"></td>
                        <td id="p_totalgsis" colspan="3"></td>
                        <td id="p_totalpagloans" colspan="3"></td>
                        <td id="p_totalothers" colspan="3"></td>
                    </tr>
                    <!-- Grand total -->
                    <tr class="expandable total-row">
                        <td colspan="4" rowspan="2">Grand Total:</td>
                        <td id="g_salaries"></td>
                        <td id="g_pera"></td>
                        <td id="g_gross" rowspan="2"></td>
                        <td id="g_tardiness" rowspan="2"></td>
                        <td id="g_tax" rowspan="2"></td>
                        <td id="g_philper"></td>
                        <td id="g_philgovt"></td>
                        <td id="g_pagper"></td>
                        <td id="g_paggovt"></td>
                        <td id="g_pag2"></td>
                        <td id="g_gsis_per"></td>
                        <td id="g_gsis_govt"></td>
                        <td id="g_ecc"></td>
                        <td id="g_mpl"></td>
                        <td id="g_housing"></td>
                        <td id="g_lotpurchase"></td>
                        <td id="g_dues"></td>
                        <td id="g_otherdues"></td>
                        <td id="g_coop"></td>
                    </tr>
                    <!-- Second Row -->
                    <tr class="expandable total-row">
                        <td id="g_total_salarypera" colspan="2"></td>
                        <td id="g_totalphil" colspan="2"></td>
                        <td id="g_totalpag" colspan="3"></td>
                        <td id="g_totalgsis" colspan="3"></td>
                        <td id="g_totalpagloans" colspan="3"></td>
                        <td id="g_totalothers" colspan="3"></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- ======================== Certified Card Grid ======================== -->
        <div class="pageBelow1st">
            <div class="card_container">
                <div class="section">
                    <div class="section-title">A</div>
                    <div class="section-content">
                        <strong style="display: inline-block; margin-bottom: 30px;">CERTIFIED:</strong> Services duly rendered as stated.
                        <div class="signature-row">
                            <div class="signature-details">
                                <strong id="cert_a"></strong>
                                <div class="signature-line"></div>
                                <div class="title">Signature over Printed Name<br>Authorized Official</div>
                            </div>
                            <div class="date-section">
                                <div class="date-line"></div>
                                <div class="title">Date</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">B</div>
                    <div class="section-content">
                        <strong style="display: inline-block; margin-bottom: 30px;">CERTIFIED:</strong> Supporting documents complete and proper.
                        <div class="signature-row">
                            <div class="signature-details">
                                <strong id="cert_b"></strong>
                                <div class="signature-line"></div>
                                <div class="title">Acting CGDH II<br>Head of Accounting Division / Unit</div>
                            </div>
                            <div class="date-section">
                                <div class="date-line"></div>
                                <div class="title">Date</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">C</div>
                    <div class="section-content">
                        <strong style="display: inline-block; margin-bottom: 30px;">CERTIFIED:</strong> Cash available for the purpose.
                        <div class="signature-row">
                            <div class="signature-details">
                                <strong id="cert_c"></strong>
                                <div class="signature-line"></div>
                                <div class="title">City Treasurer</div>
                            </div>
                            <div class="date-section">
                                <div class="date-line"></div>
                                <div class="title">Date</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">D</div>
                    <div class="section-content">
                    <strong style="display: inline-block; margin-bottom: 30px;">APPROVE FOR PAYMENT:</strong> <span class="amount">₱0.00</span>
                        <div class="signature-row">
                            <div class="signature-details">
                                <strong id="cert_d"></strong>
                                <div class="signature-line"></div>
                                <div class="title">City Mayor</div>
                            </div>
                            <div class="date-section">
                                <div class="date-line"></div>
                                <div class="title">Date</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">E</div>
                    <div class="section-content">
                        <strong style="margin-bottom: 30px;">CERTIFIED:</strong> Each employee whose name appears on the payroll has been paid the amount as indicated opposite his/her name.
                        <div class="signature-row">
                            <div class="signature-details">
                                <div style="display: inline-block; margin-top: 30px;" class="signature-line"></div>
                                <div class="title">Signature over Printed Name<br>Disbursing Officer</div>
                            </div>
                            <div class="date-section">
                                <div class="date-line"></div>
                                <div class="title">Date</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section" style="display: flex; justify-content: center; align-items: center;position: relative;">
                    <div class="section-title">F</div>
                    <div class="section-content">
                        <div class="signature-row">
                            <div class="signature-details" style="text-align: center;">
                                <strong style="display: inline-block; ">CAFOA No:______________</strong>  
                                <span style="margin-left: 30px;">Date:______________</span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>


        <!-- ======================== Certified Card Grid  G======================== -->
        <div class="pageletterG">
            <div class="section">
                <div class="section-title">G</div>
                <div class="section-content">
                    <strong>ACCOUNTING ENTRIES</strong>
                    <table class="accounting-table">
                        <tr>
                            <th>Particulars</th>
                            <th>Account Code</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Particulars</th>
                            <th>Account Code</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

     <!-- Prepared By & Certified Correct Section -->
            <div class="signature-container">
                <!-- Prepared by -->
                <div class="signature-box">
                    <strong>Prepared by:</strong>
                    <div class="signature-details">
                        <div class="nameG">&nbsp;</div> <!-- Placeholder for visual consistency -->
                        <div class="signature-lineG"></div>
                    </div>
                </div>

                <!-- Certified Correct -->
                <div class="signature-box">
                    <strong>Certified Correct:</strong>
                    <div class="signature-details">
                        <div class="nameG"><strong id="cert_correct"></strong></div>
                        <div class="signature-lineG"></div>
                        <div class="position">Acting CGDH II</div>
                    </div>
                </div>
            </div>



        <!-- ======================== Page Break 2nd page ======================== -->
        <div class="second-page">
            <table class="payroll-table">
                <thead>
                    <tr>
                        <th rowspan="3">#</th>
                        <th rowspan="3">Name</th>
                        <th rowspan="3">Position</th>
                        <th rowspan="3">Emp#</th>
                        <th colspan="8">DEDUCTIONS</th> 
                        <th rowspan="3">Gross Gov't Deduction</th>
                        <th rowspan="3">Gross Personal Deduction</th>
                        <th rowspan="3">Net TakeHome Pay</th>
                        <th rowspan="3">1st Half Proceeds</th>
                        <th rowspan="3">2nd Half Proceeds</th> 
                    </tr>
                    <tr>
                        <th colspan="7">GSIS DEDUCTIONS</th>  
                        <th>Bank Loan</th> 
                    </tr>
                    <tr>
                        <th>GSIS MPL</th>
                        <th>CEAP</th>
                        <th>Computer Loan</th>
                        <th>MPL Lite</th>
                        <th>PL (Regular)</th>
                        <th>GSIS-EL</th>
                        <th>GFAL</th>
                        <th>LBP</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Updated second table row template -->
                    <tr class="expandable">
                        <td id="id"></td> 
                        <td id="name"></td> 
                        <td id="PositionTitle"></td>
                        <td id="EmpNo"></td> 
                        <td id="GSIS_MPL"></td> 
                        <td id="ceap"></td> 
                        <td id="GSIS_ComputerLoan"></td>
                        <td id="GSIS_MPLLite"></td> 
                        <td id="GSIS_PolicyLoanRegular"></td> 
                        <td id="GSIS_EmergencyLoan"></td>
                        <td id="GSIS_GFAL"></td> 
                        <td id="BankLoan_LBP"></td> 
                        <td id="GrossDeductionGovernment"></td> 
                        <td id="GrossDeductionPersonal"></td>
                        <td id="NetPay"></td> 
                        <td id="NetPay1stHalf"></td> 
                        <td id="NetPay2ndHalf"></td>
                    </tr>

                    <!-- Page total -->
                    <tr class="expandable total-row">
                        <td colspan="4" rowspan="2">Page Total:</td>
                        <td id="p_gsis_mpl"></td>
                        <td id="p_ceap"></td>
                        <td id="p_comp"></td>
                        <td id="p_mpl_lite"></td>
                        <td id="p_pl_reg"></td>
                        <td id="p_gsis_el"></td>
                        <td id="p_gfal"></td>
                        <td id="p_lbp_tot"></td>
                        <td id="p_gross_gov_ded" rowspan="2"></td>
                        <td id="p_gross_per_ded" rowspan="2"></td>
                        <td id="p_net_pay" rowspan="2"></td>
                        <td id="p_first_half"></td>
                        <td id="p_second_half"></td>
                    </tr>
                    <tr class="expandable total-row">
                        <td id="p_total_gsis_comp" colspan="3"></td>
                        <td id="p_total_mpl_gfal" colspan="4"></td>
                        <td id="p_total_lbp"></td>
                        <td id="p_total_proceeds" colspan="2"></td>
                    </tr>

                    <!-- Grand total -->
                    <tr class="expandable total-row">
                        <td colspan="4" rowspan="2">Grand Total:</td>
                        <td id="g_gsis_mpl"></td>
                        <td id="g_ceap"></td>
                        <td id="g_comp"></td>
                        <td id="g_mpl_lite"></td>
                        <td id="g_pl_reg"></td>
                        <td id="g_gsis_el"></td>
                        <td id="g_gfal"></td>
                        <td id="g_lbp_tot"></td>
                        <td id="g_gross_gov_ded" rowspan="2"></td>
                        <td id="g_gross_per_ded" rowspan="2"></td>
                        <td id="g_net_pay" rowspan="2"></td>
                        <td id="g_first_half"></td>
                        <td id="g_second_half"></td>
                    </tr>
                    <tr class="expandable total-row">
                        <td id="g_total_gsis_comp" colspan="3"></td>
                        <td id="g_total_mpl_gfal" colspan="4"></td>
                        <td id="g_total_lbp"></td>
                        <td id="g_total_proceeds" colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    
</body>
</html>

