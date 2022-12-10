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
namespace local_course_creator;

global $CFG, $USER, $DB;

require "$CFG->libdir/tablelib.php";

class headingcourse_table extends \table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.


        $columns = array('courseid', 'action');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array(
            get_string('name'),
            get_string('action'));
        $this->define_headers($headers);
    }

    function col_courseid($value) {
        $courseid = $value->courseid;

        global $DB;

        $data = $DB->get_record_sql("SELECT * FROM {course} WHERE id=?", array($courseid));
        return $data->fullname;
    }

    function col_action($value) {
        $id = $value->id;
        $categoryid = $value->categoryid;

        $catid = optional_param('category', 0, PARAM_INT);

        $action = '<div >
           
            <a href="headingcourselist.php?category=' . $catid . '&id=' . $categoryid . '&delete=' . $id . '">' . get_string('delete') . '</a>
        </div>';

        return $action;
    }

}


/**
 * This function is called for each data row to allow processing of
 * columns which do not have a *_cols function.
 * @return string return processed value. Return NULL if no change has
 *     been made.
 */
