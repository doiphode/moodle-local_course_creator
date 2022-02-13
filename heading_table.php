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

require_once(dirname(dirname(__FILE__)) . '../../config.php');
global $CFG, $USER, $DB;

require "$CFG->libdir/tablelib.php";

class heading_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.


        $columns = array('name', 'totalcourse', 'action');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.

        $headers = array(
            get_string('name'),
            get_string('totalcourse', 'local_course_creation_wizard'),
            get_string('action'));
        $this->define_headers($headers);
    }

    function col_totalcourse($value) {
        $id = $value->id;

        global $DB;

        $data = $DB->get_record_sql("SELECT count(*) as allcount FROM {course_template_items} WHERE categoryid=" . $id);

        return $data->allcount;
    }

    function col_action($value) {
        $categoryid = optional_param('category', 0, PARAM_INT);
        $id = $value->id;

        $action = '<div >
            <a href="addheading.php?category=' . $categoryid . '&id=' . $id . '">' . get_string('edit') . '</a>&nbsp;&nbsp;
            <a href="addheading.php?category=' . $categoryid . '&delete=' . $id . '">' . get_string('delete') . '</a>&nbsp;&nbsp;
            <a href="headingcourselist.php?category=' . $categoryid . '&id=' . $id . '">' . get_string('viewcourse', 'local_course_creation_wizard') . '</a>
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
