--
-- Table structure for table `data_employment_status`
--

DROP TABLE IF EXISTS `data_employment_status`;
CREATE TABLE IF NOT EXISTS `data_employment_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_job_category`
--

DROP TABLE IF EXISTS `data_job_category`;
CREATE TABLE IF NOT EXISTS `data_job_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_job_interview`
--

DROP TABLE IF EXISTS `data_job_interview`;
CREATE TABLE IF NOT EXISTS `data_job_interview` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_job_specification_attachment`
--

DROP TABLE IF EXISTS `data_job_specification_attachment`;
CREATE TABLE IF NOT EXISTS `data_job_specification_attachment` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) NOT NULL,
  `job_title_id` int(13) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `orig_file_name` varchar(250) NOT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  `file_content` mediumblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `data_job_title`
--

DROP TABLE IF EXISTS `data_job_title`;
CREATE TABLE IF NOT EXISTS `data_job_title` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `note` text NOT NULL,
  `date_added` date NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_location`
--

DROP TABLE IF EXISTS `data_location`;
CREATE TABLE IF NOT EXISTS `data_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(110) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `province` varchar(60) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip_code` varchar(35) DEFAULT NULL,
  `phone` varchar(35) DEFAULT NULL,
  `fax` varchar(35) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `data_nationality`
--

DROP TABLE IF EXISTS `data_nationality`;
CREATE TABLE IF NOT EXISTS `data_nationality` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_pay_grade`
--

DROP TABLE IF EXISTS `data_pay_grade`;
CREATE TABLE IF NOT EXISTS `data_pay_grade` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `is_deleted` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `data_pay_grade_currency`
--

DROP TABLE IF EXISTS `data_pay_grade_currency`;
CREATE TABLE IF NOT EXISTS `data_pay_grade_currency` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pay_grade_id` bigint(20) NOT NULL,
  `currency_code` varchar(6) NOT NULL DEFAULT '',
  `currency_id` bigint(20) NOT NULL,
  `min_salary` double DEFAULT NULL,
  `max_salary` double DEFAULT NULL,
  `is_deleted` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `data_qualification_education`
--

DROP TABLE IF EXISTS `data_qualification_education`;
CREATE TABLE IF NOT EXISTS `data_qualification_education` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_qualification_language`
--

DROP TABLE IF EXISTS `data_qualification_language`;
CREATE TABLE IF NOT EXISTS `data_qualification_language` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_qualification_license`
--

DROP TABLE IF EXISTS `data_qualification_license`;
CREATE TABLE IF NOT EXISTS `data_qualification_license` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_qualification_membership`
--

DROP TABLE IF EXISTS `data_qualification_membership`;
CREATE TABLE IF NOT EXISTS `data_qualification_membership` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_qualification_skill`
--

DROP TABLE IF EXISTS `data_qualification_skill`;
CREATE TABLE IF NOT EXISTS `data_qualification_skill` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_salary_component`
--

DROP TABLE IF EXISTS `data_salary_component`;
CREATE TABLE IF NOT EXISTS `data_salary_component` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `component_name` varchar(100) NOT NULL,
  `component_type` varchar(100) NOT NULL,
  `add_to_total_payable` tinyint(1) NOT NULL,
  `add_to_ctc` tinyint(1) NOT NULL,
  `value_type` enum('amount','percentage') NOT NULL,
  `date_added` date NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_work_shift`
--

DROP TABLE IF EXISTS `data_work_shift`;
CREATE TABLE IF NOT EXISTS `data_work_shift` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `hours_per_day` decimal(4,2) NOT NULL,
  `start_time` varchar(15) NOT NULL,
  `end_time` varchar(15) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `is_deleted` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) NOT NULL,
  `emp_number` varchar(50) DEFAULT NULL,
  `emp_lastname` varchar(100) NOT NULL DEFAULT '',
  `emp_firstname` varchar(100) NOT NULL DEFAULT '',
  `emp_middle_name` varchar(100) NOT NULL DEFAULT '',
  `emp_nick_name` varchar(100) DEFAULT '',
  `is_deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee_location`
--

DROP TABLE IF EXISTS `employee_location`;
CREATE TABLE IF NOT EXISTS `employee_location` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `location_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee_work_shift`
--

DROP TABLE IF EXISTS `employee_work_shift`;
CREATE TABLE IF NOT EXISTS `employee_work_shift` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `work_shift_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `organization_gen_info`
--

DROP TABLE IF EXISTS `organization_gen_info`;
CREATE TABLE IF NOT EXISTS `organization_gen_info` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tax_id` varchar(30) DEFAULT NULL,
  `registration_number` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `country_code` varchar(30) DEFAULT NULL,
  `province` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `zip_code` varchar(30) DEFAULT NULL,
  `street1` varchar(100) DEFAULT NULL,
  `street2` varchar(100) DEFAULT NULL,
  `note` text,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `password_reminders`
--

DROP TABLE IF EXISTS `password_reminders`;
CREATE TABLE IF NOT EXISTS `password_reminders` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='to store code for forgot password';

-- --------------------------------------------------------

--
-- Table structure for table `site_country`
--

DROP TABLE IF EXISTS `site_country`;
CREATE TABLE IF NOT EXISTS `site_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(80) NOT NULL DEFAULT '',
  `country_code` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `site_currency_type`
--

DROP TABLE IF EXISTS `site_currency_type`;
CREATE TABLE IF NOT EXISTS `site_currency_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_code` char(3) NOT NULL DEFAULT '',
  `currency_name` varchar(70) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `site_province`
--

DROP TABLE IF EXISTS `site_province`;
CREATE TABLE IF NOT EXISTS `site_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_name` varchar(40) NOT NULL DEFAULT '',
  `province_code` char(2) NOT NULL DEFAULT '',
  `country_code` char(3) NOT NULL DEFAULT 'us',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

DROP TABLE IF EXISTS `throttle`;
CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bba_token` varchar(40) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  `permissions` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL,
  `timezone` varchar(200) NOT NULL,
  `timeformat` enum('12','24') NOT NULL DEFAULT '24',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_logged` datetime NOT NULL,
  `signup_ip` varchar(40) NOT NULL,
  `user_access` enum('User','Admin') NOT NULL,
  `ess_role_id` int(11) NOT NULL,
  `supervisor_role_id` bigint(20) NOT NULL,
  `admin_role_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `blocked_by` bigint(20) NOT NULL,
  `user_status` enum('Ok','Locked','Deleted') NOT NULL,
  `new_email` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_location`
--

DROP TABLE IF EXISTS `user_location`;
CREATE TABLE IF NOT EXISTS `user_location` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `location_id` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) NOT NULL,
  `parent_role_id` int(11) NOT NULL,
  `role_key` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `is_assignable` tinyint(1) DEFAULT '0',
  `is_predefined` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- Added on Nov 26

CREATE TABLE IF NOT EXISTS `announcement_news` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `topic` varchar(200) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `date_published` date NOT NULL,
  `published_to_supervisor` tinyint(4) NOT NULL,
  `published_to_admin` tinyint(4) NOT NULL,
  `published_to_all_employees` tinyint(4) NOT NULL,
  `date_added` date NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  `status` enum('draft','published','archived') CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `announcement_news_attachment`;
CREATE TABLE IF NOT EXISTS `announcement_news_attachment`(
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `announcement_news_id` int(13) NOT NULL,
  `saved_file_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `orig_file_name` varchar(250) NOT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
ALTER TABLE  `announcement_news_attachment` ADD  `date_added` DATE NOT NULL AFTER  `added_by`;

DROP TABLE IF EXISTS `announcement_document`;
CREATE TABLE IF NOT EXISTS `announcement_document` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `topic` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `date_published` date NOT NULL,
  `published_to_supervisor` tinyint(1) NOT NULL,
  `published_to_admin` tinyint(1) NOT NULL,
  `published_to_all_employees` tinyint(1) NOT NULL,
  `date_added` date NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  `status` enum('draft','published','archived') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `announcement_document_attachment`;
CREATE TABLE IF NOT EXISTS `announcement_document_attachment` (
  `id` int(13) NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `date_added` date NOT NULL,
  `announcement_document_id` int(13) NOT NULL,
  `saved_file_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `orig_file_name` varchar(250) NOT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `announcement_document_category`;
CREATE TABLE IF NOT EXISTS `announcement_document_category`;(
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hr_config_data`;
CREATE TABLE IF NOT EXISTS `hr_config_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) NOT NULL DEFAULT '',
  `value` varchar(512) NOT NULL DEFAULT '',
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hr_config_custom_field`;
CREATE TABLE IF NOT EXISTS `hr_config_custom_field`(
 `id` bigint(20) NOT NULL AUTO_INCREMENT,
 `field_name` varchar(50) NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` int(11) NOT NULL,
  `screen` varchar(100) NOT NULL,
  `extra_data` text NOT NULL,
    `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE  `hr_config_custom_field` CHANGE  `type`  `type` ENUM(  'text',  'dropdown' ) NOT NULL DEFAULT  'text';
ALTER TABLE  `hr_config_custom_field` CHANGE  `screen`  `screen` ENUM(  'personal',  'contact',  'emergency',  'job',  'salary',  'immigration' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'personal',

DROP TABLE IF EXISTS `data_hr_reporting_method`;
CREATE TABLE IF NOT EXISTS `data_hr_reporting_method`(
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `data_hr_termination_reason`;
CREATE TABLE IF NOT EXISTS `data_hr_termination_reason`(
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_deleted` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `admin_email_configuration`;
CREATE TABLE IF NOT EXISTS `admin_email_configuration` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mail_type` varchar(50) DEFAULT NULL,
  `sent_as` varchar(250) NOT NULL,
  `sendmail_path` varchar(250) DEFAULT NULL,
  `smtp_host` varchar(250) DEFAULT NULL,
  `smtp_port` int(10) DEFAULT NULL,
  `smtp_username` varchar(250) DEFAULT NULL,
  `smtp_password` varchar(250) DEFAULT NULL,
  `smtp_auth_type` varchar(50) DEFAULT NULL,
  `smtp_security_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `admin_email_configuration` ADD  `subscription_id` BIGINT NOT NULL AFTER  `id`;

DROP TABLE IF EXISTS `hr_email_notification`;
CREATE TABLE IF NOT EXISTS `hr_email_notification` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
   `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `hr_email_notification_subscriber`;
CREATE TABLE IF NOT EXISTS `hr_email_notification_subscriber` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `notification_id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_id` (`notification_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `id` bigint(20) NOT NULL  AUTO_INCREMENT,
   `subscription_id` bigint(20) NOT NULL,
  `employee_number` varchar(50) DEFAULT NULL,
  `emp_lastname` varchar(100) NOT NULL DEFAULT '',
  `emp_firstname` varchar(100) NOT NULL DEFAULT '',
  `emp_middle_name` varchar(100) NOT NULL DEFAULT '',
  `emp_nick_name` varchar(100) DEFAULT '',
  `smoker` smallint(6) DEFAULT '0',
  `ethnic_race_code` varchar(13) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `nationality_id` bigint(20) DEFAULT NULL,
  `gender` smallint(6) DEFAULT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `ssn_num` varchar(100) CHARACTER SET latin1 DEFAULT '',
  `sin_num` varchar(100) DEFAULT '',
  `other_id` varchar(100) DEFAULT '',
  `driving_licence_num` varchar(100) DEFAULT '',
  `driving_licence_exp_date` date DEFAULT NULL,
  `military_service` varchar(100) DEFAULT '',
  `employment_status_id` int(13) DEFAULT NULL,
  `job_title_id` bigint(20) DEFAULT NULL,
  `job_category_id` bigint(20) DEFAULT NULL,
  `work_station` int(6) DEFAULT NULL,
  `address_street1` varchar(100) DEFAULT '',
  `address_street2` varchar(100) DEFAULT '',
  `city_code` varchar(100) DEFAULT '',
  `country_code` varchar(100) DEFAULT '',
  `province_code` varchar(100) DEFAULT '',
  `zipcode` varchar(20) DEFAULT NULL,
  `home_telephone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `work_telephone` varchar(50) DEFAULT NULL,
  `work_email` varchar(50) DEFAULT NULL,  
  `joined_date` date DEFAULT NULL,
  `other_email` varchar(50) DEFAULT NULL,
  `termination_reason_id` bigint(20) DEFAULT NULL,
  `custom1` varchar(250) DEFAULT NULL,
  `custom2` varchar(250) DEFAULT NULL,
  `custom3` varchar(250) DEFAULT NULL,
  `custom4` varchar(250) DEFAULT NULL,
  `custom5` varchar(250) DEFAULT NULL,
  `custom6` varchar(250) DEFAULT NULL,
  `custom7` varchar(250) DEFAULT NULL,
  `custom8` varchar(250) DEFAULT NULL,
  `custom9` varchar(250) DEFAULT NULL,
  `custom10` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `employee_reportto`;
CREATE TABLE IF NOT EXISTS `employee_reportto`(
   `id` bigint(20) NOT NULL  AUTO_INCREMENT,
  `subscription_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL ,
  `reports_to_employee_id` bigint(20) NOT NULL ,
  `reporting_method_id` bigint(20) NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `employee` ADD  `is_deleted` TINYINT NOT NULL;

--
-- Added on Dec 03
--
DROP TABLE IF EXISTS `employee_avatar`;
CREATE TABLE IF NOT EXISTS `employee_avatar` (
   `id` bigint(20) NOT NULL AUTO_INCREMENT,		
  `employee_id` bigint(20) NOT NULL ,
  `image` varchar(100) DEFAULT NULL,
  `image_type` varchar(50) DEFAULT NULL,
  `file_size` varchar(20) DEFAULT NULL,
  `image_width` varchar(20) DEFAULT NULL,
  `image_height` varchar(20) DEFAULT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `leave_period_history`;
CREATE TABLE IF NOT EXISTS `leave_period_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `leave_period_start_month` int(11) NOT NULL,
  `leave_period_start_day` int(11) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `leave_type`;
CREATE TABLE IF NOT EXISTS `leave_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `exclude_in_reports_if_no_entitlement` tinyint(1) NOT NULL DEFAULT '0',
  `operational_country_id` int(10) unsigned DEFAULT NULL,
  `subscription_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `operational_country_id` (`operational_country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `leave_work_week`;
CREATE TABLE IF NOT EXISTS `leave_work_week` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
   `subscription_id` bigint(20) NOT NULL,
  `operational_country_id` int(10) unsigned DEFAULT NULL,
  `monday`  ENUM(  'full',  'half',  'none' ) NOT NULL DEFAULT  'full',
  `tuesday`  ENUM(  'full',  'half',  'none' ) NOT NULL DEFAULT  'full',
  `wednesday`  ENUM(  'full',  'half',  'none' ) NOT NULL DEFAULT  'full',
  `thursday`  ENUM(  'full',  'half',  'none' ) NOT NULL DEFAULT  'full',
  `friday`  ENUM(  'full',  'half',  'none' ) NOT NULL DEFAULT  'full',
  `saturday`  ENUM(  'full',  'half',  'none' ) NOT NULL DEFAULT  'half',
  `sunday`  ENUM(  'full',  'half',  'none' ) NOT NULL DEFAULT  'none',
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `leave_holiday`;
CREATE TABLE IF NOT EXISTS `leave_holiday` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `holiday_date` date DEFAULT NULL,
  `recurring` tinyint(1) unsigned DEFAULT '0',
  `length` enum('full', 'half')  NOT NULL DEFAULT  'full',
  `operational_country_id` int(10) unsigned DEFAULT NULL,
 `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `leave`;
CREATE TABLE IF NOT EXISTS `leave` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `length_hours` decimal(6,2) unsigned DEFAULT NULL,
  `length_days` decimal(6,4) unsigned DEFAULT NULL,
  `status` enum('rejected', 'cancelled', 'pending_approval', 'scheduled', 'taken', 'weekend', 'holiday') DEFAULT 'scheduled',
  `comments` varchar(256) DEFAULT NULL,
  `leave_request_id` int(10) unsigned NOT NULL,
  `leave_type_id` int(10) unsigned NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `duration_type` enum('', 'morning', 'afternoon', 'specified_time') NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `employee_attachment`;
CREATE TABLE IF NOT EXISTS `employee_attachment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) NOT NULL,
  `added_by` bigint(20) NOT NULL,
  `date_added` date NOT NULL,
  `employee_id` bigint(20) NOT NULL,

  `saved_file_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `orig_file_name` varchar(250) NOT NULL,
  `file_type` varchar(200) DEFAULT NULL,
  `file_size` int(11) NOT NULL,
  `file_content` mediumblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;