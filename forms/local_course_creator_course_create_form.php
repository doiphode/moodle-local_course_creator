<?php

/****************************************************************
 *
 * File:     /local/course_creator/forms/course_create_form.php
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
 * @subpackage course_creator
 * @author      Shubhendra Doiphode (Github: doiphode)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// No direct script access.
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class local_course_creator_course_create_form extends moodleform {
    const TEMPELATE_CAT_ID = 1;

    public function definition() {
        global $DB;
        $categoryid = optional_param('category', '0', PARAM_INT);
        $mform = &$this->_form;
        $mform->addElement('header', 'course_create_header', get_string('course_creator_legend', 'local_course_creator'));
        $radioarray = array();
        $attributes = array();
        $radioarray[] = $mform->createElement('radio', 'createchoice', '', get_string('create_option_c', 'local_course_creator'), 2, $attributes);
        $radioarray[] = $mform->createElement('radio', 'createchoice', '', get_string('create_option_a', 'local_course_creator'), 0, $attributes);
        $radioarray[] = $mform->createElement('radio', 'createchoice', '', get_string('create_option_b', 'local_course_creator'), 1, $attributes);

        $mform->addGroup($radioarray, 'radioar', get_string('create_option_label', 'local_course_creator'), array(' '), false);
        $mform->setDefault('createchoice', 2);
        
        $catcourses = $DB->get_records_select('course', "category=:categoryid", ['categoryid'=>$categoryid], 'fullname ASC', 'id, fullname');

        foreach ($catcourses as $id => &$catcourse) {
            $catcourse = $catcourse->fullname;
        }
        $mform->addElement('select', 'course_prev', get_string('prev_course_options', 'local_course_creator'), $catcourses, array('class' => 'create_prev'));

        // --
        $tempcourses = $DB->get_records_select('course', "category=:category", ['category'=> self::TEMPELATE_CAT_ID], 'fullname ASC', 'id, fullname');
       
        foreach ($tempcourses as $id => &$tempcourse) {
            $tempcourse = $tempcourse->fullname;
        }
        //        $mform->addElement('select', 'course_template', get_string('template_options', 'local_course_creator'), $tempcourses, array('class'=>'create_template'));

        $courseTemplate_arr = array(); 
        $records = $DB->get_records_sql("SELECT cti.*,ctc.name as categoryname,c.fullname as coursename FROM {local_course_creator_items} cti LEFT JOIN {local_course_creator_cat} ctc ON cti.categoryid=ctc.id LEFT JOIN {course} c ON c.id=cti.courseid order by cti.categoryid asc");
        foreach ($records as $record) {
            $courseTemplate_arr[$record->categoryname][$record->courseid] = $record->coursename;
        }

        $mform->addElement('selectgroups', 'course_template', get_string('template_options', 'local_course_creator'), $courseTemplate_arr, array('class' => 'create_template'));
        // --
        $this->add_action_buttons(true, 'Create Course');

    }
}