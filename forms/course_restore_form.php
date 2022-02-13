<?php
/****************************************************************
 *
 * File:     /local/course_creation_wizard/forms/course_create_form.php
 *
 * Purpose:  Form for course creation
 ****************************************************************/

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
 * Form to create a course.
 * @package local
 * @subpackage course_creation_wizard
 * @author      Shubhendra Doiphode (Github: doiphode)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// No direct script access.
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class course_restore_form extends moodleform {
    public function definition() {
        global $CFG;
        global $SESSION;

        $mform = &$this->_form;
        $attributes = array();
        $mform->addElement('header', 'course_create_header', get_string('course_restore_wizard_legend', 'local_course_creation_wizard'));
        $mform->addElement('text', 'fullname', get_string('course_full_name', 'local_course_creation_wizard'), $attributes);
        $mform->addRule('fullname', null, 'required', null, 'client');
        $mform->setType('fullname', PARAM_RAW);
        $mform->addElement('text', 'shortname', get_string('course_short_name', 'local_course_creation_wizard'), $attributes);
        $mform->addRule('shortname', null, 'required', null, 'client');
        $mform->setType('shortname', PARAM_RAW);
        // require_once($CFG->libdir . '/coursecatlib.php');
        $displaylist = core_course_category::make_categories_list('moodle/course:create');
        // $displaylist = coursecat::make_categories_list('moodle/course:create');
        // foreach ($course_cats as $id => &$course_cat){
        // $course_cat = $course_cat->shortname;
        // }

        $mform->addElement('select', 'course_categories', get_string('category_select', 'local_course_creation_wizard'), $displaylist, array('class' => 'cat_list'));

        $mform->setDefault('course_categories', array('id' => $SESSION->catid));

        $this->add_action_buttons(true, 'Create Course');
    }
}