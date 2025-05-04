# payroll_system-May

# Payroll System Documentation

## Introduction

This payroll system provides a solution for managing payroll operations, including generating payroll reports and individual payslips. The system streamlines the payroll process, ensuring accurate calculations of employee compensation, deductions, and net pay.

## System Overview

The payroll system consists of two main modules:

1. **Payroll Module**: Allows users to search for and generate payroll reports for specific departments, offices, or periods.
2. **Payslip Module**: Enables users to search for and generate individual employee payslips.

Both modules connect to a central database that stores employee information, salary details, deductions, and other relevant payroll data.

## User Guide

### Payroll Request Form

1. **Access the Form**: Navigate to the payroll request form page.
2. **Search Options**: You can search by:
   - Payroll No., OR
   - Start Date, End Date, Responsibility Center, and Office (all required if Payroll No. is not provided)
3. **Apply Search**: Click the "Apply" button to search for payroll data.
4. **View Results**: The system will display the payroll report with detailed information.
   
![payroll_request](https://github.com/user-attachments/assets/6be17f66-4fd3-4f8f-bb40-8f48198b5ebc)


### Payslip Request Form

1. **Access the Form**: Navigate to the payslip request form page.
2. **Search Options**: You can search by:
   - Payroll No., OR
   - Employee #, Start Date, End Date, Responsibility Center, and Office (all required if Payroll No. is not provided)
3. **Apply Search**: Click the "Apply" button to search for payslip data.
4. **View Results**: The system will display the employee payslip(s) with detailed information.

![payslip_request](https://github.com/user-attachments/assets/54287f07-3870-419d-a525-4adda62924e9)

### Viewing Reports

1. **Payroll Report**: Displays detailed payroll information for multiple employees including:
   - Compensations (Salaries and Wages, PERA)
   - Deductions (Tardiness, Tax Withheld, Contributions, Loans)
   - Net Take Home Pay
   - First and Second Half Proceeds
   - Certifications
   - Accounting Entries

2. **Payslip Report**: Displays individual employee payslip including:
   - Employee Information
   - Income Details
   - Government Mandated Deductions
   - Personal Deductions
   - Net Take Home Pay
   - Payment Split (1st and 2nd half)

### Downloading Reports

1. **PDF Download Button**: Both the payroll report and payslip report pages include a "Download as PDF" button.
2. **Download Process**: 
   - Click the "Download as PDF" button
   - The system will generate a PDF file of the current report
   - Your browser will automatically download the PDF file
3. **Printing**: You can print the PDF file using your preferred PDF viewer application.

## Developer Guide

### System Architecture

The payroll system follows a simple MVC-like architecture:
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL

### Database Structure

Key database tables include:
- `responsibility` - Responsibility centers
- `office` - Office information
- `payroll` - Master payroll records
- `employee` - Employee information
- Plus relevant tables for deductions, compensations, etc.

### File Structure

```
/
├── css/
│   ├── request_payroll.css
│   ├── request_payslip.css
│   ├── payslip_report.css
│   └── styles.css
├── html/
│   ├── request_payroll.php
│   ├── request_payslip.php
│   └── ...
├── images/
│   ├── logo1.png
│   ├── logo2.png
│   ├── logo2.2.png
│   └── proll.png
├── php/
│   ├── db_connection.php
│   ├── process_search.php
│   ├── payslip_search.php
│   └── ...
├── script/
│   ├── payroll_report.js
│   ├── payslip_print.js
│   └── ...
└── index.php
```

### Main Components

#### 1. Database Connection
The file `db_connection.php` manages the database connection. Make sure this file is properly configured with database credentials.

#### 2. Request Forms
- `request_payroll.php`: Form for requesting payroll reports
- `request_payslip.php`: Form for requesting employee payslips

#### 3. Processing Scripts
- `process_search.php`: Processes payroll search requests
- `payslip_search.php`: Processes payslip search requests

#### 4. Report Generation
- `payroll_report.js`: Handles dynamic content for payroll reports
- `payslip_print.js`: Manages payslip generation and printing

#### 5. Styling
- CSS files in the `/css` directory control the appearance of forms and reports

### Known Issues

1. **Database Connection Fallback**: If database connection fails, the system provides fallback options but with limited functionality.
2. **PDF Generation**: The PDF generation relies on client-side libraries (html2pdf.js) which may have browser compatibility issues in older browsers.
3. **Form Validation**: Client-side validation is implemented, but server-side validation should be enhanced for better security.

## Installation Instructions

1. **Server Requirements**:
   - PHP 7.0 or higher
   - MySQL 5.6 or higher
   - Web server (Apache/Nginx)

2. **Database Setup**:
   - Create a new database
   - Import the provided SQL schema (not included in this documentation)
   - Update `db_connection.php` with your database credentials

3. **File Deployment**:
   - Upload all files to your web server
   - Ensure proper file permissions (typically 755 for directories, 644 for files)
   - Make sure the `php` directory has appropriate permissions for database connections

4. **Configuration**:
   - Check all file paths and URLs in the code
   - Update logo paths if necessary
   - Configure any institution-specific information

## Maintenance

### Regular Maintenance Tasks

1. **Database Backups**: Schedule regular database backups
2. **Log Monitoring**: Check for any PHP errors or warnings
3. **Updates**: Keep libraries and dependencies updated
4. **Security Checks**: Regularly review code for security vulnerabilities

### Troubleshooting

1. **Database Connection Issues**:
   - Verify database credentials in `db_connection.php`
   - Check database server status
   - Ensure PHP has appropriate database extensions enabled

2. **Report Generation Problems**:
   - Check browser console for JavaScript errors
   - Verify that all required libraries are loading correctly
   - Ensure data is being properly fetched from the database

3. **PDF Download Issues**:
   - Check if the browser blocks pop-ups
   - Verify that html2pdf.js library is loading correctly
   - Check browser compatibility

## Contact Information

For technical support or questions about the system, please contact:

- **System Administrator**: [admin@example.com](mailto:admin@example.com)
- **Development Team**: [dev@example.com](mailto:dev@example.com)
- **Help Desk**: +63 XXX XXX XXXX

---

*Last Updated: May 04, 2025*
