-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 19, 2017 at 10:38 AM
-- Server version: 5.5.52-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

DROP TABLE IF EXISTS `nestpay_payments`;
--
-- Table structure for table `nestpay_payment`
--

CREATE TABLE IF NOT EXISTS `nestpay_payments` (
  `id` bigint(20) NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-processed; 0-not_processed',
  `oid` char(64) NOT NULL COMMENT 'Unique identifier of the order',
  `trantype` char(20) NOT NULL COMMENT 'Transaction type Set to "Auth" for authorization, “PreAuth” for preauthorization',
  `amount` decimal(12,2) NOT NULL COMMENT 'amount transaction amount Use "." or "," as decimal separator, do not use grouping character',
  `currency` char(3) NOT NULL COMMENT 'ISO code of transaction currency ISO 4217 numeric currency code, 3 digits',
  `Response` char(10) DEFAULT NULL COMMENT 'Payment status. Possible values: "Approved", "Error", "Declined"',
  `ProcReturnCode` char(2) DEFAULT NULL COMMENT 'Transaction status code. “00” for authorized transactions, “99” for gateway errors, others for ISO-8583 error codes',
  `mdStatus` char(3) DEFAULT NULL COMMENT 'Status code for the 3D transaction. 1=authenticated transaction 2, 3, 4 = Card not participating or attempt 5,6,7,8 = Authentication not available or system error 0 = Authentication failed',
  `ErrMsg` varchar(255) DEFAULT NULL COMMENT 'Error message',
  `AuthCode` char(32) DEFAULT NULL COMMENT 'Transaction Verification/Approval/Authoriza tion code',
  `TransId` varchar(64) DEFAULT NULL COMMENT ' Nestpay Transaction Id',
  `TRANID` varchar(64) DEFAULT NULL COMMENT ' Nestpay Transaction Id',
  `clientIp` varchar(15) DEFAULT NULL COMMENT 'IP address of the customer',
  `email` varchar(64) DEFAULT NULL COMMENT 'Customer''s email address',
  `tel` varchar(32) DEFAULT NULL COMMENT 'Customer phone',
  `description` varchar(255) DEFAULT NULL COMMENT 'Description sent to MPI',
  `BillToCompany` varchar(255) DEFAULT NULL COMMENT 'BillTo company name',
  `BillToName` varchar(255) DEFAULT NULL COMMENT 'BillTo name/surname',
  `BillToStreet1` varchar(255) DEFAULT NULL COMMENT 'BillTo address line 1',
  `BillToStreet2` varchar(255) DEFAULT NULL COMMENT 'BillTo address line 2',
  `BillToCity` varchar(64) DEFAULT NULL COMMENT 'BillTo city',
  `BillToStateProv` varchar(32) DEFAULT NULL COMMENT 'BillTo state/province',
  `BillToPostalCode` varchar(32) DEFAULT NULL COMMENT 'BillTo postal code',
  `BillToCountry` varchar(32) DEFAULT NULL COMMENT 'BillTo country code',
  `ShipToCompany` varchar(255) DEFAULT NULL COMMENT 'ShipTo company',
  `ShipToName` varchar(255) DEFAULT NULL COMMENT 'ShipTo name',
  `ShipToStreet1` varchar(255) DEFAULT NULL COMMENT 'ShipTo address line 1',
  `ShipToStreet2` varchar(255) DEFAULT NULL COMMENT 'ShipTo address line 2',
  `ShipToCity` varchar(64) DEFAULT NULL COMMENT 'ShipTo city',
  `ShipToStateProv` varchar(32) DEFAULT NULL COMMENT 'ShipTo state/province',
  `ShipToPostalCode` varchar(32) DEFAULT NULL COMMENT 'ShipTo postal code',
  `ShipToCountry` varchar(32) DEFAULT NULL COMMENT 'ShipTo country code',
  `DimCriteria1` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria2` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria3` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria4` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria5` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria6` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria7` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria8` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria9` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `DimCriteria10` varchar(64) DEFAULT NULL COMMENT 'Merchant specific parameter',
  `comments` varchar(255) DEFAULT NULL COMMENT 'Kept as description for the transaction',
  `instalment` varchar(3) NULL COMMENT 'Instalment count',
  `INVOICENUMBER` varchar(255) DEFAULT NULL COMMENT 'Invoice Number',
  `storetype` varchar(16) DEFAULT NULL COMMENT 'Merchant payment model Possible values: "pay_hosting", “3d_pay”, "3d", "3d_pay_hosting"',
  `lang` varchar(16) DEFAULT NULL COMMENT 'Language of the payment pages hosted by NestPay',
  `xid` varchar(255) DEFAULT NULL COMMENT 'Internet transaction identifier',
  `HostRefNum` varchar(255) DEFAULT NULL COMMENT 'Host reference number ',
  `ReturnOid` varchar(64) DEFAULT NULL COMMENT 'Returned order ID, must same as input orderId',
  `MaskedPan` char(20) DEFAULT NULL COMMENT 'Masked credit card number',
  `rnd` char(20) DEFAULT NULL COMMENT 'Random string, will be used for hash comparison',
  `merchantID` varchar(255) DEFAULT NULL COMMENT 'MPI merchant ID',
  `txstatus` varchar(255) DEFAULT NULL COMMENT '3D status for archival Possible values "A", "N", "Y"',
  `iReqCode` varchar(255) DEFAULT NULL COMMENT 'Code provided by ACS indicating data that is formatted correctly, but which invalidates the request. This element is included when business processing cannot be performed for some reason.',
  `iReqDetail` varchar(255) DEFAULT NULL COMMENT 'May identify the specific data elements that caused the Invalid Request Code (so never supplied if Invalid Request Code is omitted).',
  `vendorCode` varchar(255) DEFAULT NULL COMMENT 'Error message describing iReqDetail error.',
  `PAResSyntaxOK` varchar(255) DEFAULT NULL COMMENT 'If PARes validation is syntactically correct, the value is true. Otherwise value is false. "Y" or "N',
  `PAResVerified` varchar(255) DEFAULT NULL COMMENT 'If signature validation of the return message is successful, the value is true. If PARes message is not received or signature validation fails, the value is false. "Y" or "N',
  `eci` varchar(255) DEFAULT NULL COMMENT 'Electronic Commerce Indicator. empty for non-3D transactions',
  `cavv` varchar(255) DEFAULT NULL COMMENT 'Cardholder Authentication Verification Value, determined by ACS. 28 characters, contains a 20 byte value that has been Base64 encoded, giving a 28 byte result.',
  `cavvAlgorthm` varchar(255) DEFAULT NULL COMMENT 'CAVV algorithm Possible values "0", "1", "2", "3"',
  `md` varchar(255) DEFAULT NULL COMMENT 'MPI data replacing card number',
  `Version` varchar(255) DEFAULT NULL COMMENT 'MPI version information 3 characters l(ike "2.0")',
  `sID` varchar(255) DEFAULT NULL COMMENT 'Schema ID "1" for Visa, "2" for Mastercard',
  `mdErrorMsg` text DEFAULT NULL COMMENT 'Error Message from MPI (if any)',
  `clientid` varchar(255) DEFAULT NULL,
  `EXTRA_TRXDATE` varchar(255) DEFAULT NULL,

  `ACQBIN` varchar(255) DEFAULT NULL,
  `acqStan` varchar(255) DEFAULT NULL,
  `cavvAlgorithm` varchar(255) DEFAULT NULL,
  `digest` varchar(255) DEFAULT NULL,
  `dsId` varchar(255) DEFAULT NULL,
  `Ecom_Payment_Card_ExpDate_Month` varchar(255) DEFAULT NULL,
  `Ecom_Payment_Card_ExpDate_Year` varchar(255) DEFAULT NULL,
  `EXTRA_CARDBRAND` varchar(255) DEFAULT NULL,
  `EXTRA_CARDISSUER` varchar(255) DEFAULT NULL,
  `EXTRA_INVOICENUMBER` varchar(255) DEFAULT NULL,
  `failUrl` varchar(255) DEFAULT NULL,
  `HASH` varchar(255) DEFAULT NULL,
  `hashAlgorithm` varchar(255) DEFAULT NULL,
  `HASHPARAMS` varchar(255) DEFAULT NULL,
  `HASHPARAMSVAL` varchar(255) DEFAULT NULL,
  `okurl` varchar(255) DEFAULT NULL,
  `payResults.dsId` varchar(255) DEFAULT NULL,
  `refreshtime` varchar(255) DEFAULT NULL,
  `SettleId` varchar(255) DEFAULT NULL,

  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nestpay_payment`
--
ALTER TABLE `nestpay_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `oid` (`oid`),
  ADD INDEX(`processed`),
  ADD INDEX(`trantype`),
  ADD INDEX(`currency`),
  ADD INDEX(`Response`),
  ADD INDEX(`ProcReturnCode`),
  ADD INDEX(`mdStatus`),
  ADD INDEX(`AuthCode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nestpay_payment`
--
ALTER TABLE `nestpay_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;