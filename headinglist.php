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
global $PAGE, $CFG, $USER, $DB, $OUTPUT, $COURSE, $SESSION;

require_login();


require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/formslib.php');

require("heading_table.php");

$categoryid = optional_param('category', 0, PARAM_INT);

$catcontext = context_coursecat::instance($categoryid);
require_capability('moodle/course:create', $catcontext);

$PAGE->set_url($CFG->wwwroot . '/local/course_creation_wizard/headinglist.php?category=' . $categoryid);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_course_creation_wizard'));
$PAGE->set_heading(get_string('pluginname', 'local_course_creation_wizard'));

$previewnode = $PAGE->navigation->add(get_string('pluginname', 'local_course_creation_wizard'), new moodle_url('/local/course_creation_wizard/view.php?category=' . $categoryid), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add(get_string('heading_list', 'local_course_creation_wizard'), new moodle_url('/local/course_creation_wizard/headinglist.php?category=' . $categoryid));
$thingnode->make_active();

$PAGE->requires->jquery();

echo $OUTPUT->header();

//plan list table
$pera[] = 'id > 0';
$table = new heading_table('uniqueid');
$search = optional_param('search', '', PARAM_ALPHA);
$searchstr = "";
if (!empty($search)) {
    $searchstr = 'name like "%' . $search . '%"';
    $pera[] = '( ' . $searchstr . ' )';
}
// Work out the sql for the table.
$perasstring = implode("&&", $pera);
$table->set_sql('*', "{course_template_category}", "$perasstring");
$table->no_sorting('name');
$table->no_sorting('totalcourse');
$table->no_sorting('action');
// $table->sortable(true,'daytimestamp','DESC');
$table->define_baseurl("$CFG->wwwroot/local/course_creation_wizard/headinglist.php?category=" . $categoryid);

$addurl = new moodle_url('/local/course_creation_wizard/addheading.php?category=' . $categoryid);
$addtemplateurl = new moodle_url('/local/course_creation_wizard/addcourse.php?category=' . $categoryid);
$linkcontent = '<div style="text-align:right;margin-bottom: 20px;"><a href="' . $addurl . '">' . get_string('add_heading', 'local_course_creation_wizard') . '</a>&nbsp;&nbsp;&nbsp;<a href="' . $addtemplateurl . '">' . get_string('add_course', 'local_course_creation_wizard') . '</a></div>';
echo $linkcontent;

$table->out(20, true);
echo $OUTPUT->footer();