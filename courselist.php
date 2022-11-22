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


require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/formslib.php');

require_once("classes/courselist_table.php");

$categoryid = optional_param('category', 0, PARAM_INT);

// Permissions --
$catcontext = context_coursecat::instance($categoryid);
require_capability('moodle/course:create', $catcontext);

require_capability('moodle/backup:backupcourse', context_course::instance($COURSE->id));
// --

$PAGE->set_url($CFG->wwwroot . '/local/course_creator/courselist.php?category=' . $categoryid);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_course_creator'));
$PAGE->set_heading(get_string('pluginname', 'local_course_creator'));

$previewnode = $PAGE->navigation->add(get_string('pluginname', 'local_course_creator'), new moodle_url('/local/course_creator/view.php?category=' . $categoryid), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add(get_string('course_list', 'local_course_creator'), new moodle_url('/local/course_creator/courselist.php?category=' . $categoryid));
$thingnode->make_active();

$PAGE->requires->jquery();
echo $OUTPUT->header();

//plan list table
$pera[] = 'id > 0';
$table = new courselist_table('uniqueid');
//$search = optional_param('search', '', PARAM_ALPHA);
$searchstr = "";

// Work out the sql for the table.
$perasstring = implode("&&", $pera);
$table->set_sql('*', "{local_course_creator_items}", "$perasstring");
$table->no_sorting('courseid');
$table->no_sorting('course');
$table->no_sorting('action');
$table->no_sorting('category');
// $table->sortable(true,'daytimestamp','DESC');
$table->define_baseurl("$CFG->wwwroot/local/course_creator/courselist.php?category=" . $categoryid);

$addcategoryurl = new moodle_url('/local/course_creator/addheading.php?category=' . $categoryid);
$addurl = new moodle_url('/local/course_creator/addcourse.php?category=' . $categoryid);
$linkcontent = '<div style="text-align:right;margin-bottom: 20px;"><a href="' . $addurl . '">' . get_string('add_course', 'local_course_creator') . '</a>&nbsp;&nbsp;&nbsp;<a href="' . $addcategoryurl . '">' . get_string('add_heading', 'local_course_creator') . '</a></div>';
echo $linkcontent;

$table->out(20, true);
echo $OUTPUT->footer();