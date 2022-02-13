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
 * The primary renderer for the backup.
 *
 * Can be retrieved with the following code:
 * <?php
 *  $renderer = $PAGE->get_renderer('local_course_creation_wizard');
 * ?>
 *
 * @package local
 * @subpackage course_creation_wizard
 * @author      Shubhendra Doiphode (Github: doiphode)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

class local_course_creation_wizard_renderer extends plugin_renderer_base {
    const COURSE_CREATION_WIZARD = 0;
    const COURSE_SETUP = 1;
    const COURSE_INFORMATION = 2;

    public function display_breadcrumb($stage) {
        $items = [get_string('stage_one', 'local_course_creation_wizard'), get_string('stage_two', 'local_course_creation_wizard'), get_string('stage_three', 'local_course_creation_wizard')];
        foreach ($items as $key => &$item) {
            $attr = array();
            if ($key == $stage) {
                $attr = array('class' => 'active');
                $item = html_writer::tag('span', '<b>' . $item . '</b>', $attr);
            } else {
                $item = html_writer::tag('span', $item, $attr);
            }
        }
        return html_writer::tag('div', join(get_separator(), $items), array('class' => 'course_create_stage clearfix'));
    }
}