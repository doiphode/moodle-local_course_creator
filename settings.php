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
 * @package      local
 * @subpackage   course_creation_wizard
 * @author       Shubhendra Doiphode (Github: doiphode)
 * @license      http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// No direct script access.
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // needs this condition or there is error on login page
    // Create the new settings page in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as $settings will be NULL.
    $settings = new admin_settingpage('local_course_creation_wizard', get_string('setting_name', 'local_course_creation_wizard'));

    $settings->add(new admin_setting_description('local_course_creation_wizard/heading',
        '<a href="../local/course_creation_wizard/addheading.php?category=1">' . get_string('add_heading', 'local_course_creation_wizard') . '</a>', '', ''));

    $ADMIN->add('localplugins', $settings);


}






