<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file is used to get encryption token.
 *
 * @since     Moodle 2.0
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
use phpzatuk;
global $CFG;
require_login();
require_capability('mod/zatuk:encryption', context_system::instance());
require_once($CFG->dirroot.'/repository/zatuk/zatuklib.php');
$apikey = trim(get_config('repository_zatuk', 'zatuk_key'));
$secret  = trim(get_config('repository_zatuk', 'zatuk_secret'));
$apiurl = trim(get_config('repository_zatuk', 'zatuk_api_url'));
$emailaddress  = trim(get_config('repository_zatuk', 'email'));
$username  = trim(get_config('repository_zatuk', 'name'));
$zatuk = new phpzatuk($apiurl, $apikey, $secret);
$url = required_param('uri',  PARAM_RAW);
echo $zatuk->get_encryption_token($url);
