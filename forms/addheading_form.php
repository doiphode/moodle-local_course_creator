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
 * @subpackage course_creation_wizard
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
class addheading_form extends moodleform {

    /**
     * Add elements to this form.
     */
    public function definition() {
        $categoryid = optional_param('category', 0, PARAM_INT);

        $mform = $this->_form;

        $data = $this->_customdata['data'];
        $catid = $this->_customdata['category'];
        $name = "";
        $eid = 0;
        if (isset($data->name)) {
            $name = $data->name;
            $eid = $data->id;
        }
        if (empty($path)) {
            $path = '/';
        }

        $returnurl = new moodle_url('/local/course_creation_wizard/headinglist.php?category=' . $catid);
        $addurl = new moodle_url('/local/course_creation_wizard/addcourse.php?category=' . $catid);

        $linkcontent = '<div style="text-align:right;margin-bottom: 20px;"><a href="' . $addurl . '">' . get_string('add_course', 'local_course_creation_wizard') . '</a>&nbsp;&nbsp;&nbsp;<a href="' . $returnurl . '">' . get_string('heading_list', 'local_course_creation_wizard') . '</a></div>';
        $mform->addElement('html', $linkcontent);

        $mform->addElement('hidden', 'eid', "", array("size" => 50, "maxlength" => 50, "width" => "100%"));
        $mform->setType('eid', PARAM_RAW);
        $mform->setDefault('eid', $eid);

        $mform->addElement('hidden', 'catid', "", array("size" => 50, "maxlength" => 50, "width" => "100%"));
        $mform->setType('catid', PARAM_RAW);
        $mform->setDefault('catid', $catid);

        $mform->addElement('text', 'name', get_string('name'), 'maxlength="254" size="50"');
        $mform->setType('name', PARAM_RAW);
        $mform->addRule('name', get_string('missingfullname'), 'required', null, 'client');
        $mform->setDefault('name', $name);


        // $category_arr = $this->categoryTree();
        // $mform->addElement('select', 'category', get_string('category'), $category_arr);


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
