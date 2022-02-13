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
 * @package local
 * @subpackage course_creation_wizard
 * @author      Shubhendra Doiphode (Github: doiphode)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

/**
 * Hook to insert a link in settings navigation menu block
 *
 * @param settings_navigation $navigation
 * @param course_context $context
 * @return void
 */
function local_course_creation_wizard_extend_settings_navigation(settings_navigation $navigation, $context) {
    global $CFG, $DB, $PAGE;

    $categorynode = $navigation->get('categorysettings');


    // If not in a course category context, then leave
    if ($context == null) {
        return;
    }

    if (null == ($categorynode = $navigation->get('categorysettings'))) {

        return;
    }


    if (has_capability('moodle/course:create', $context)) {


        // $url = new moodle_url('/local/course_creation_wizard/view.php', array('category' => $context->instanceid));
        // $node = navigation_node::create(get_string('pluginname', 'local_course_creation_wizard'), $url, navigation_node::TYPE_SETTING, null, 'course_create', new pix_icon('i/return', ''));
        // $PAGE->navigation->add_node($node);
        // $node->showinflatnavigation = true;

        $url = new moodle_url('/local/course_creation_wizard/view.php', array('category' => $context->instanceid));
//        $url = new moodle_url('/local/course_creation_wizard/addheading.php', array('category' => $context->instanceid));
        $categorynode->add(get_string('pluginname', 'local_course_creation_wizard'), $url, navigation_node::TYPE_SETTING, null, 'course_create', new pix_icon('i/return', ''));


    }

}