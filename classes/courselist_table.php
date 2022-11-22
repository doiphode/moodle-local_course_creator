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
 * @subpackage course_creator
 * @author      Shubhendra Doiphode (Github: doiphode)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// require_once(dirname(dirname(__FILE__)) . '../../config.php');
global $CFG, $USER, $DB;

require "$CFG->libdir/tablelib.php";

class courselist_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.


        $columns = array('courseid', 'course', 'category', 'action');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.

        $headers = array(
            get_string('courseid', 'local_course_creator'),
            get_string('course'),
            get_string('category'),
            get_string('action'));
        $this->define_headers($headers);
    }


    function col_course($value) {
        global $DB;
        $id = $value->courseid;

        $data = $DB->get_record_sql("SELECT * FROM {course} WHERE id=?", array($id));
        return $data->fullname;
    }

    function col_category($value) {
        global $DB;
        $id = $value->categoryid;
        
        $data = $DB->get_record_sql("SELECT * FROM {local_course_creator_cat} WHERE id=?", array($id));
        return $data->name;
    }

    function col_action($value) {
        $categoryid = optional_param('category', 0, PARAM_INT);
        $id = $value->id;

        $action = "";
        if(is_numeric($categoryid) && is_numeric($id)){
            $action = '<div >
                <a href="addcourse.php?category=' . $categoryid . '&id=' . $id . '">' . get_string('edit') . '</a>&nbsp;&nbsp;
                <a href="addcourse.php?category=' . $categoryid . '&delete=' . $id . '&sesskey='.sesskey().'">' . get_string('delete') . '</a>
            </div>';
        }
        
        return $action;
    }

}


/**
 * This function is called for each data row to allow processing of
 * columns which do not have a *_cols function.
 * @return string return processed value. Return NULL if no change has
 *     been made.
 */
