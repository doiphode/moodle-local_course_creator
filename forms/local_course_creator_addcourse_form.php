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
 * minimalistic edit form
 * @package local
 * @subpackage course_creator
 * @author      Shubhendra Doiphode (Github: doiphode)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

/**
 * Class user_files_form
 * @copyright 2010 Petr Skoda (http://skodak.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_course_creator_addcourse_form extends moodleform {

    /**
     * Add elements to this form.
     */
    public function definition() {

        global $DB;

        $catid = optional_param('category', 0, PARAM_INT);

        ## heading list
        $records = $DB->get_records_sql("SELECT * FROM {local_course_creator_cat} order by name asc");
        $category_arr = array();
        $categoryid = 0;
        foreach ($records as $record) {
            if ($categoryid == 0) $categoryid = $record->id;
            $category_arr[$record->id] = $record->name;
        }

        ## course names
        $course_arr = array();
        $courseid = 0;

        $mform = $this->_form;

        $data = $this->_customdata['data'];
        $catid = $this->_customdata['category'];

        $name = "";
        $eid = 0;
        if (isset($data->courseid)) {
            $courseid = $data->courseid;
            $eid = $data->id;
            $categoryid = $data->categoryid;
        }
        if (empty($path)) {
            $path = '/';
        }

        $returnurl = new moodle_url('/local/course_creator/courselist.php?category=' . $catid);
        $addurl = new moodle_url('/local/course_creator/addheading.php?category=' . $catid);

        $linkcontent = '<div style="text-align:right;margin-bottom: 20px;"><a href="' . $addurl . '">' . get_string('add_heading', 'local_course_creator') . '</a>&nbsp;&nbsp;&nbsp;<a href="' . $returnurl . '">' . get_string('course_list', 'local_course_creator') . '</a></div>';
        $mform->addElement('html', $linkcontent);

        $mform->addElement('hidden', 'eid', "", array("size" => 50, "maxlength" => 50, "width" => "100%"));
        $mform->setType('eid', PARAM_INT);
        $mform->setDefault('eid', $eid);

        $mform->addElement('hidden', 'catid', "", array("size" => 50, "maxlength" => 50, "width" => "100%"));
        $mform->setType('catid', PARAM_INT);
        $mform->setDefault('catid', $catid);

        $mform->addElement('select', 'categoryid', get_string('heading', 'local_course_creator'), $category_arr);
        $mform->setDefault('categoryid', $categoryid);
        $mform->addRule('categoryid', get_string('missingcategory', 'local_course_creator'), 'required', null, 'client');

        // $mform->addElement('select', 'courseid', get_string('course'), $course_arr);
        // $mform->setDefault('courseid', $courseid);
        // $mform->addRule('courseid', get_string('missingcategory','local_course_creator'), 'required', null, 'client');
        $mform->addElement('text', 'courseid', get_string('courseid', 'local_course_creator'), 'maxlength="254" size="50"');
        $mform->setType('courseid', PARAM_INT);
        $mform->addRule('courseid', get_string('missingcourseid', 'local_course_creator'), 'required', null, 'client');
        $mform->addRule('courseid', get_string('enternumberonly', 'local_course_creator'), 'numeric', null, 'client');
        $mform->setDefault('courseid', $courseid);

        $mform->setType('returnurl', PARAM_LOCALURL);

        $this->add_action_buttons(true, get_string('submit'));
        // $mform->addElement('submit', 'submin_but', get_string('create'));
    }

    /**
     * Validate incoming data.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = array();


        return $errors;
    }


}
