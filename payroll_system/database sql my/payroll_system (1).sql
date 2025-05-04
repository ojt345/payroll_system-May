-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 04:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payroll_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `certified_by`
--

CREATE TABLE `certified_by` (
  `cert_id` int(11) NOT NULL,
  `cert_a` varchar(100) NOT NULL,
  `cert_b` varchar(100) NOT NULL,
  `cert_c` varchar(100) NOT NULL,
  `cert_d` varchar(100) NOT NULL,
  `cert_e` varchar(100) NOT NULL,
  `cert_correct` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certified_by`
--

INSERT INTO `certified_by` (`cert_id`, `cert_a`, `cert_b`, `cert_c`, `cert_d`, `cert_e`, `cert_correct`) VALUES
(1, 'Jane Wilson', 'Mark Thompson', 'Sarah Johnson', 'Robert Davis', 'Michael Brown', 'Jennifer Lee');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(100) NOT NULL,
  `emp_position` varchar(100) NOT NULL,
  `emp_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_name`, `emp_position`, `emp_no`) VALUES
(1, 'John Doe', 'Accountant', 'EMP001'),
(2, 'Jane Smith', 'Financial Analyst', 'EMP002'),
(3, 'John Lloyd', 'Accountant', 'EMP003'),
(4, 'Jane Layla', 'HR Officer', 'EMP004'),
(5, 'Carlos Reyes', 'Engineer', 'EMP005'),
(6, 'Emily Tan', 'Analyst', 'EMP006'),
(7, 'Mark Lim', 'Supervisor', 'EMP007'),
(8, 'Grace Lee', 'Developer', 'EMP008'),
(9, 'Aaron Cruz', 'Technician', 'EMP009');

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE `office` (
  `office_id` int(11) NOT NULL,
  `office_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`office_id`, `office_title`) VALUES
(1, 'Head Office');

-- --------------------------------------------------------

--
-- Stand-in structure for view `payroll_comprehensive_view`
-- (See below for the actual view)
--
CREATE TABLE `payroll_comprehensive_view` (
`date_start` date
,`date_end` date
,`PayrollMstID` varchar(50)
,`responsibility_title` varchar(100)
,`office_title` varchar(100)
,`payroll_id` int(11)
,`emp_name` varchar(100)
,`emp_position` varchar(100)
,`emp_no` varchar(50)
,`tax_code` varchar(50)
,`Salary` decimal(12,2)
,`PERA` decimal(12,2)
,`GrossIncome` decimal(12,2)
,`Tardiness` decimal(12,2)
,`IncomeTaxWithHeld` decimal(12,2)
,`PHIC_EmployeeShare` decimal(12,2)
,`PHIC_EmployerShare` decimal(12,2)
,`PAGIBIG_EmployeeShare` decimal(12,2)
,`PAGIBIG_EmployerShare` decimal(12,2)
,`PAGIBIG_II` decimal(12,2)
,`GSIS_EmployeeShare` decimal(12,2)
,`GSIS_EmployerShare` decimal(12,2)
,`GSIS_ECC` decimal(12,2)
,`PAGIBIG_MPL` decimal(12,2)
,`PAGIBIG_Housing` decimal(12,2)
,`PAGIBIG_LotPurchase` decimal(12,2)
,`GSCGEA_Dues` decimal(12,2)
,`GSCGEA_OtherDues` decimal(12,2)
,`GSCCoop` decimal(12,2)
,`GSIS_MPL` decimal(12,2)
,`ceap` decimal(12,2)
,`GSIS_ComputerLoan` decimal(12,2)
,`GSIS_MPLLite` decimal(12,2)
,`GSIS_PolicyLoanRegular` decimal(12,2)
,`GSIS_EmergencyLoan` decimal(12,2)
,`GSIS_GFAL` decimal(12,2)
,`BankLoan_LBP` decimal(12,2)
,`GrossDeductionGovernment` decimal(12,2)
,`GrossDeductionPersonal` decimal(12,2)
,`NetPay` decimal(12,2)
,`NetPay1stHalf` decimal(12,2)
,`NetPay2ndHalf` decimal(12,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_period`
--

CREATE TABLE `payroll_period` (
  `period_id` int(11) NOT NULL,
  `PayrollMstID` varchar(50) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `responsibility_id` int(11) NOT NULL,
  `office_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_period`
--

INSERT INTO `payroll_period` (`period_id`, `PayrollMstID`, `date_start`, `date_end`, `responsibility_id`, `office_id`, `status`) VALUES
(1, '10001', '2025-04-01', '2025-04-15', 1, 1, 'Active'),
(2, '10002', '2025-05-01', '2025-05-31', 1, 1, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_report_table`
--

CREATE TABLE `payroll_report_table` (
  `payroll_id` int(11) NOT NULL,
  `period_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `tax_code` varchar(50) DEFAULT NULL,
  `Salary` decimal(12,2) DEFAULT 0.00,
  `PERA` decimal(12,2) DEFAULT 0.00,
  `GrossIncome` decimal(12,2) DEFAULT 0.00,
  `Tardiness` decimal(12,2) DEFAULT 0.00,
  `IncomeTaxWithHeld` decimal(12,2) DEFAULT 0.00,
  `PHIC_EmployeeShare` decimal(12,2) DEFAULT 0.00,
  `PHIC_EmployerShare` decimal(12,2) DEFAULT 0.00,
  `PAGIBIG_EmployeeShare` decimal(12,2) DEFAULT 0.00,
  `PAGIBIG_EmployerShare` decimal(12,2) DEFAULT 0.00,
  `PAGIBIG_II` decimal(12,2) DEFAULT 0.00,
  `GSIS_EmployeeShare` decimal(12,2) DEFAULT 0.00,
  `GSIS_EmployerShare` decimal(12,2) DEFAULT 0.00,
  `GSIS_ECC` decimal(12,2) DEFAULT 0.00,
  `PAGIBIG_MPL` decimal(12,2) DEFAULT 0.00,
  `PAGIBIG_Housing` decimal(12,2) DEFAULT 0.00,
  `PAGIBIG_LotPurchase` decimal(12,2) DEFAULT 0.00,
  `GSCGEA_Dues` decimal(12,2) DEFAULT 0.00,
  `GSCGEA_OtherDues` decimal(12,2) DEFAULT 0.00,
  `GSCCoop` decimal(12,2) DEFAULT 0.00,
  `GSIS_MPL` decimal(12,2) DEFAULT 0.00,
  `ceap` decimal(12,2) DEFAULT 0.00,
  `GSIS_ComputerLoan` decimal(12,2) DEFAULT 0.00,
  `GSIS_MPLLite` decimal(12,2) DEFAULT 0.00,
  `GSIS_PolicyLoanRegular` decimal(12,2) DEFAULT 0.00,
  `GSIS_EmergencyLoan` decimal(12,2) DEFAULT 0.00,
  `GSIS_GFAL` decimal(12,2) DEFAULT 0.00,
  `BankLoan_LBP` decimal(12,2) DEFAULT 0.00,
  `GrossDeductionGovernment` decimal(12,2) DEFAULT 0.00,
  `GrossDeductionPersonal` decimal(12,2) DEFAULT 0.00,
  `NetPay` decimal(12,2) DEFAULT 0.00,
  `NetPay1stHalf` decimal(12,2) DEFAULT 0.00,
  `NetPay2ndHalf` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_report_table`
--

INSERT INTO `payroll_report_table` (`payroll_id`, `period_id`, `emp_id`, `tax_code`, `Salary`, `PERA`, `GrossIncome`, `Tardiness`, `IncomeTaxWithHeld`, `PHIC_EmployeeShare`, `PHIC_EmployerShare`, `PAGIBIG_EmployeeShare`, `PAGIBIG_EmployerShare`, `PAGIBIG_II`, `GSIS_EmployeeShare`, `GSIS_EmployerShare`, `GSIS_ECC`, `PAGIBIG_MPL`, `PAGIBIG_Housing`, `PAGIBIG_LotPurchase`, `GSCGEA_Dues`, `GSCGEA_OtherDues`, `GSCCoop`, `GSIS_MPL`, `ceap`, `GSIS_ComputerLoan`, `GSIS_MPLLite`, `GSIS_PolicyLoanRegular`, `GSIS_EmergencyLoan`, `GSIS_GFAL`, `BankLoan_LBP`, `GrossDeductionGovernment`, `GrossDeductionPersonal`, `NetPay`, `NetPay1stHalf`, `NetPay2ndHalf`) VALUES
(1, 1, 1, NULL, 35000.00, 2000.00, 37000.00, 0.00, 3500.00, 550.00, 550.00, 100.00, 100.00, 0.00, 2450.00, 4900.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8250.00, 650.00, 28100.00, 14050.00, 14050.00),
(2, 1, 2, NULL, 40000.00, 2000.00, 42000.00, 0.00, 4200.00, 600.00, 600.00, 100.00, 100.00, 0.00, 2800.00, 5600.00, 100.00, 1000.00, 0.00, 0.00, 150.00, 0.00, 800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 9650.00, 1950.00, 30400.00, 15200.00, 15200.00),
(3, 2, 5, NULL, 37500.00, 2000.00, 39500.00, 0.00, 3700.00, 570.00, 570.00, 100.00, 100.00, 0.00, 2600.00, 5200.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 680.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8700.00, 1000.00, 30800.00, 15400.00, 15400.00),
(4, 2, 2, NULL, 38500.00, 2000.00, 40500.00, 0.00, 3900.00, 580.00, 580.00, 100.00, 100.00, 0.00, 2670.00, 5340.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 720.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8820.00, 1100.00, 31680.00, 15840.00, 15840.00),
(5, 2, 3, NULL, 37500.00, 2000.00, 39500.00, 0.00, 3700.00, 570.00, 570.00, 100.00, 100.00, 0.00, 2600.00, 5200.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 680.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8700.00, 1000.00, 30800.00, 15400.00, 15400.00),
(6, 2, 9, NULL, 38500.00, 2000.00, 40500.00, 0.00, 3900.00, 580.00, 580.00, 100.00, 100.00, 0.00, 2670.00, 5340.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 720.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8820.00, 1100.00, 31680.00, 15840.00, 15840.00),
(17, 1, 3, NULL, 38000.00, 2000.00, 40000.00, 0.00, 3800.00, 570.00, 570.00, 100.00, 100.00, 0.00, 2650.00, 5300.00, 100.00, 500.00, 0.00, 0.00, 150.00, 0.00, 700.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8720.00, 1450.00, 29830.00, 14915.00, 14915.00),
(18, 1, 4, NULL, 36000.00, 2000.00, 38000.00, 0.00, 3400.00, 560.00, 560.00, 100.00, 100.00, 0.00, 2500.00, 5000.00, 100.00, 0.00, 500.00, 0.00, 150.00, 0.00, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8470.00, 1000.00, 28530.00, 14265.00, 14265.00),
(19, 1, 5, NULL, 39000.00, 2000.00, 41000.00, 0.00, 4000.00, 580.00, 580.00, 100.00, 100.00, 0.00, 2700.00, 5400.00, 100.00, 0.00, 0.00, 600.00, 150.00, 0.00, 750.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 9060.00, 1100.00, 31940.00, 15970.00, 15970.00),
(20, 1, 6, NULL, 37000.00, 2000.00, 39000.00, 0.00, 3600.00, 550.00, 550.00, 100.00, 100.00, 0.00, 2550.00, 5100.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 650.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8600.00, 1200.00, 30400.00, 15200.00, 15200.00),
(21, 1, 7, NULL, 41000.00, 2000.00, 43000.00, 0.00, 4300.00, 600.00, 600.00, 100.00, 100.00, 0.00, 2850.00, 5700.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 900.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 9900.00, 1300.00, 33100.00, 16550.00, 16550.00),
(22, 1, 8, NULL, 39500.00, 2000.00, 41500.00, 0.00, 4100.00, 590.00, 590.00, 100.00, 100.00, 0.00, 2750.00, 5500.00, 100.00, 200.00, 0.00, 0.00, 150.00, 0.00, 820.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 9110.00, 800.00, 32390.00, 16195.00, 16195.00),
(23, 1, 9, NULL, 40500.00, 2000.00, 42500.00, 0.00, 4250.00, 610.00, 610.00, 100.00, 100.00, 0.00, 2900.00, 5800.00, 100.00, 1000.00, 0.00, 0.00, 150.00, 0.00, 950.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 10120.00, 1250.00, 32380.00, 16190.00, 16190.00),
(24, 1, 3, NULL, 38500.00, 2000.00, 40500.00, 0.00, 3900.00, 580.00, 580.00, 100.00, 100.00, 0.00, 2670.00, 5340.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 720.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8820.00, 1100.00, 31680.00, 15840.00, 15840.00),
(25, 1, 3, NULL, 37500.00, 2000.00, 39500.00, 0.00, 3700.00, 570.00, 570.00, 100.00, 100.00, 0.00, 2600.00, 5200.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 680.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8700.00, 1000.00, 30800.00, 15400.00, 15400.00),
(26, 1, 2, NULL, 38500.00, 2000.00, 40500.00, 0.00, 3900.00, 580.00, 580.00, 100.00, 100.00, 0.00, 2670.00, 5340.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 720.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8820.00, 1100.00, 31680.00, 15840.00, 15840.00),
(27, 1, 1, NULL, 37500.00, 2000.00, 39500.00, 0.00, 3700.00, 570.00, 570.00, 100.00, 100.00, 0.00, 2600.00, 5200.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 680.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8700.00, 1000.00, 30800.00, 15400.00, 15400.00),
(28, 1, 9, NULL, 38500.00, 2000.00, 40500.00, 0.00, 3900.00, 580.00, 580.00, 100.00, 100.00, 0.00, 2670.00, 5340.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 720.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8820.00, 1100.00, 31680.00, 15840.00, 15840.00),
(29, 1, 5, NULL, 37500.00, 2000.00, 39500.00, 0.00, 3700.00, 570.00, 570.00, 100.00, 100.00, 0.00, 2600.00, 5200.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 680.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8700.00, 1000.00, 30800.00, 15400.00, 15400.00),
(30, 1, 2, NULL, 38500.00, 2000.00, 40500.00, 0.00, 3900.00, 580.00, 580.00, 100.00, 100.00, 0.00, 2670.00, 5340.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 720.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8820.00, 1100.00, 31680.00, 15840.00, 15840.00),
(31, 1, 9, NULL, 37500.00, 2000.00, 39500.00, 0.00, 3700.00, 570.00, 570.00, 100.00, 100.00, 0.00, 2600.00, 5200.00, 100.00, 0.00, 0.00, 0.00, 150.00, 0.00, 680.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 8700.00, 1000.00, 30800.00, 15400.00, 15400.00);

-- --------------------------------------------------------

--
-- Table structure for table `responsibility`
--

CREATE TABLE `responsibility` (
  `responsibility_id` int(11) NOT NULL,
  `responsibility_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `responsibility`
--

INSERT INTO `responsibility` (`responsibility_id`, `responsibility_title`) VALUES
(1, 'Accounting Department'),
(2, 'ICTD');

-- --------------------------------------------------------

--
-- Structure for view `payroll_comprehensive_view`
--
DROP TABLE IF EXISTS `payroll_comprehensive_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `payroll_comprehensive_view`  AS SELECT `pp`.`date_start` AS `date_start`, `pp`.`date_end` AS `date_end`, `pp`.`PayrollMstID` AS `PayrollMstID`, `r`.`responsibility_title` AS `responsibility_title`, `o`.`office_title` AS `office_title`, `prt`.`payroll_id` AS `payroll_id`, `e`.`emp_name` AS `emp_name`, `e`.`emp_position` AS `emp_position`, `e`.`emp_no` AS `emp_no`, `prt`.`tax_code` AS `tax_code`, `prt`.`Salary` AS `Salary`, `prt`.`PERA` AS `PERA`, `prt`.`GrossIncome` AS `GrossIncome`, `prt`.`Tardiness` AS `Tardiness`, `prt`.`IncomeTaxWithHeld` AS `IncomeTaxWithHeld`, `prt`.`PHIC_EmployeeShare` AS `PHIC_EmployeeShare`, `prt`.`PHIC_EmployerShare` AS `PHIC_EmployerShare`, `prt`.`PAGIBIG_EmployeeShare` AS `PAGIBIG_EmployeeShare`, `prt`.`PAGIBIG_EmployerShare` AS `PAGIBIG_EmployerShare`, `prt`.`PAGIBIG_II` AS `PAGIBIG_II`, `prt`.`GSIS_EmployeeShare` AS `GSIS_EmployeeShare`, `prt`.`GSIS_EmployerShare` AS `GSIS_EmployerShare`, `prt`.`GSIS_ECC` AS `GSIS_ECC`, `prt`.`PAGIBIG_MPL` AS `PAGIBIG_MPL`, `prt`.`PAGIBIG_Housing` AS `PAGIBIG_Housing`, `prt`.`PAGIBIG_LotPurchase` AS `PAGIBIG_LotPurchase`, `prt`.`GSCGEA_Dues` AS `GSCGEA_Dues`, `prt`.`GSCGEA_OtherDues` AS `GSCGEA_OtherDues`, `prt`.`GSCCoop` AS `GSCCoop`, `prt`.`GSIS_MPL` AS `GSIS_MPL`, `prt`.`ceap` AS `ceap`, `prt`.`GSIS_ComputerLoan` AS `GSIS_ComputerLoan`, `prt`.`GSIS_MPLLite` AS `GSIS_MPLLite`, `prt`.`GSIS_PolicyLoanRegular` AS `GSIS_PolicyLoanRegular`, `prt`.`GSIS_EmergencyLoan` AS `GSIS_EmergencyLoan`, `prt`.`GSIS_GFAL` AS `GSIS_GFAL`, `prt`.`BankLoan_LBP` AS `BankLoan_LBP`, `prt`.`GrossDeductionGovernment` AS `GrossDeductionGovernment`, `prt`.`GrossDeductionPersonal` AS `GrossDeductionPersonal`, `prt`.`NetPay` AS `NetPay`, `prt`.`NetPay1stHalf` AS `NetPay1stHalf`, `prt`.`NetPay2ndHalf` AS `NetPay2ndHalf` FROM ((((`payroll_period` `pp` join `responsibility` `r` on(`pp`.`responsibility_id` = `r`.`responsibility_id`)) join `office` `o` on(`pp`.`office_id` = `o`.`office_id`)) join `payroll_report_table` `prt` on(`pp`.`period_id` = `prt`.`period_id`)) join `employee` `e` on(`prt`.`emp_id` = `e`.`emp_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certified_by`
--
ALTER TABLE `certified_by`
  ADD PRIMARY KEY (`cert_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `office`
--
ALTER TABLE `office`
  ADD PRIMARY KEY (`office_id`);

--
-- Indexes for table `payroll_period`
--
ALTER TABLE `payroll_period`
  ADD PRIMARY KEY (`period_id`),
  ADD KEY `responsibility_id` (`responsibility_id`),
  ADD KEY `office_id` (`office_id`);

--
-- Indexes for table `payroll_report_table`
--
ALTER TABLE `payroll_report_table`
  ADD PRIMARY KEY (`payroll_id`),
  ADD KEY `period_id` (`period_id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `responsibility`
--
ALTER TABLE `responsibility`
  ADD PRIMARY KEY (`responsibility_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certified_by`
--
ALTER TABLE `certified_by`
  MODIFY `cert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `office`
--
ALTER TABLE `office`
  MODIFY `office_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payroll_period`
--
ALTER TABLE `payroll_period`
  MODIFY `period_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payroll_report_table`
--
ALTER TABLE `payroll_report_table`
  MODIFY `payroll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `responsibility`
--
ALTER TABLE `responsibility`
  MODIFY `responsibility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payroll_period`
--
ALTER TABLE `payroll_period`
  ADD CONSTRAINT `payroll_period_ibfk_1` FOREIGN KEY (`responsibility_id`) REFERENCES `responsibility` (`responsibility_id`),
  ADD CONSTRAINT `payroll_period_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `office` (`office_id`);

--
-- Constraints for table `payroll_report_table`
--
ALTER TABLE `payroll_report_table`
  ADD CONSTRAINT `payroll_report_table_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `payroll_period` (`period_id`),
  ADD CONSTRAINT `payroll_report_table_ibfk_2` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
