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
require_once(dirname(dirname(__FILE__)) . '../../config.php');
global $PAGE, $CFG, $USER, $DB, $OUTPUT, $COURSE, $SESSION;

require_login();


$headingid = optional_param('id', 0, PARAM_INT);
$deleteid = optional_param('delete', 0, PARAM_INT);
$categoryid = optional_param('category', 0, PARAM_INT);

$catcontext = context_coursecat::instance($categoryid);
require_capability('moodle/course:create', $catcontext);

$returnurl = new moodle_url('/local/course_creator/headingcourselist.php?category=' . $categoryid . '&id=' . $headingid);
if ($deleteid > 0) {
    require_sesskey();
    $DB->delete_records('local_course_creator_items', array('id' => $deleteid));
    redirect(urldecode($returnurl));
}
$record = $DB->get_record_sql("select name from {local_course_creator_cat} where id=?", array($headingid));

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/formslib.php');

require("headingcourse_table.php");
$PAGE->set_url($CFG->wwwroot . '/local/course_creator/headingcourselist.php?category=' . $categoryid . '&id=' . $headingid);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_course_creator'));
$PAGE->set_heading(get_string('pluginname', 'local_course_creator'));

$previewnode = $PAGE->navigation->add(get_string('pluginname', 'local_course_creator'), new moodle_url('/local/course_creator/view.php?category=' . $categoryid), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add($record->name . " - " . get_string('course_list', 'local_course_creator'), new moodle_url('/local/course_creator/headingcourselist.php?category=' . $categoryid));
$thingnode->make_active();

$PAGE->requires->jquery();


echo $OUTPUT->header();

//plan list table
$table = new headingcourse_table('uniqueid');

// Work out the sql for the table.
$table->set_sql('*', "{local_course_creator_items}", "categoryid=:categoryid",array("categoryid"=>$headingid));
$table->no_sorting('courseid');
$table->no_sorting('action');
$table->define_baseurl("$CFG->wwwroot/local/course_creator/headingcourselist.php?category=" . $categoryid . "&id=" . $headingid);

echo "<h2>" . $record->name . " - " . get_string('course_list', 'local_course_creator') . "</h2>";

$addurl = new moodle_url('/local/course_creator/addheading.php?category=' . $categoryid);
$headinglisturl = new moodle_url('/local/course_creator/headinglist.php?category=' . $categoryid);

$linkcontent = '<div style="text-align:right;margin-bottom: 20px;"><a href="' . $addurl . '">' . get_string('add_heading', 'local_course_creator') . '</a>&nbsp;&nbsp;<a href="' . $headinglisturl . '">' . get_string('heading_list', 'local_course_creator') . '</a></div>';
echo $linkcontent;

$table->out(20, true);
echo $OUTPUT->footer();