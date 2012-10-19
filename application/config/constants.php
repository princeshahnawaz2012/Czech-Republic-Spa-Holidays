<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('ATTEMPT_LOGIN', 1);

define('USER_ROLE_SUPER_ADMIN', 1);
define('USER_ROLE_ADMIN', 5);
define('USER_ROLE_MANAGER', 10);
define('USER_ROLE_TRANSLATOR', 15);

define('USER_STATUS_ALL', 0);
define('USER_STATUS_ACTIVE', 1);
define('USER_STATUS_BLOCKED', 2);


define('ARTICLE_STATUS_ALL', 0);
define('ARTICLE_STATUS_ACTIVE', 1);
define('ARTICLE_STATUS_INACTIVE', 2);

define('ARTICLE_MODIFY_FLAG_ON', 2);
define('ARTICLE_MODIFY_FLAG_OFF', 0);

define('LANGUAGE_ABBR_DEFAULT', 'en');

define('PER_PAGE_NUM_DEFAULT', 25);

define('COUNTRY_ALL', 0);
define('COUNTRY_ACTIVE', 1);
define('COUNTRY_INACTIVE', 2);

define('REGION_ALL', 0);
define('REGION_ACTIVE', 1);
define('REGION_INACTIVE', 2);

define('CITY_ALL', 0);
define('CITY_ACTIVE', 1);
define('CITY_INACTIVE', 2);

define('CURRENCY_ALL', 0);
define('CURRENCY_ACTIVE', 1);
define('CURRENCY_INACTIVE', 2);

define('CATEGORY_ALL', 0);
define('CATEGORY_ACTIVE', 1);
define('CATEGORY_INACTIVE', 2);

define('SPA_ALL', 0);
define('SPA_ACTIVE', 1);
define('SPA_INACTIVE', 2);

define('TRANSFER_CALCULATION_TYPE_COMISSION', 1);
define('TRANSFER_CALCULATION_TYPE_MARK_UP', 2);

define('OFFSEASON_CALCULATION_ALL', 0);
define('OFFSEASON_CALCULATION_BY_FIRST_SEASON', 1);
define('OFFSEASON_CALCULATION_BY_SECOND_SEASON', 2);
define('OFFSEASON_CALCULATION_BY_BOTH_SEASON', 3);

define('ILLNESE_ALL', 0);
define('ILLNESE_ACTIVE', 1);
define('ILLNESE_INACTIVE', 2);

define('ESSENTIAL_INFO_ALL', 0);
define('ESSENTIAL_INFO_ACTIVE', 1);
define('ESSENTIAL_INFO_INACTIVE', 2);

define('MEDICAL_TREATMENT_ALL', 0);
define('MEDICAL_TREATMENT_ACTIVE', 1);
define('MEDICAL_TREATMENT_INACTIVE', 2);

define('FACILITY_ALL', 0);
define('FACILITY_ACTIVE', 1);
define('FACILITY_INACTIVE', 2);

define('PROGRAMME_ALL', 0);
define('PROGRAMME_ACTIVE', 1);
define('PROGRAMME_INACTIVE', 2);

define('PROGRAMME_IMAGE_ALL', 0);
define('PROGRAMME_IMAGE_ACTIVE', 1);
define('PROGRAMME_IMAGE_INACTIVE', 2);

define('CATEGORY_SHOW_SHORT_DESCRIPTION', 'd');
define('CATEGORY_SHOW_ILLNESES', 'i');

define('COMPLEX_TREATMENT_MEDICAL', 2);
define('COMPLEX_TREATMENT_COSMETIC', 1);

define('STATION_ALL', 0);
define('STATION_ACTIVE', 1);
define('STATION_INACTIVE', 2);

define('TRANSFER_ALL', 0);
define('TRANSFER_ACTIVE', 1);
define('TRANSFER_INACTIVE', 2);


/* End of file constants.php */
/* Location: ./application/config/constants.php */