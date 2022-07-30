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
require_once("$CFG->dirroot/local/course_creator/forms/local_course_creator_addheading_form.php");
require_login();

$eid = optional_param('id', 0, PARAM_INT);
$deleteid = optional_param('delete', 0, PARAM_INT);
$categoryid = optional_param('category', 1, PARAM_INT);

// Permissions --
$catcontext = context_coursecat::instance($COURSE->id);
require_capability('moodle/course:create', $catcontext);

require_capability('moodle/backup:backupcourse', context_course::instance($COURSE->id));
// --

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/course_creator/addheading.php?category=' . $categoryid);
$PAGE->requires->jquery();
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_course_creator'));
$PAGE->set_heading(get_string('pluginname', 'local_course_creator'));


$previewnode = $PAGE->navigation->add(get_string('pluginname', 'local_course_creator'), new moodle_url('/local/course_creator/view.php?category=' . $categoryid), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add(get_string('add_heading', 'local_course_creator'), new moodle_url('/local/course_creator/addheading.php?category=' . $categoryid));
$thingnode->make_active();

$returnurl = new moodle_url('/local/course_creator/headinglist.php?category=' . $categoryid);

## Delete record
if ($deleteid > 0) {
    require_sesskey();
    $DB->delete_records('local_course_creator_cat', array('id' => $deleteid));
    $DB->delete_records('local_course_creator_items', array('categoryid' => $deleteid));
    redirect(urldecode($returnurl));
}

$data = new stdClass();
if ($eid > 0) {
    $data = $DB->get_record_sql("SELECT * FROM {local_course_creator_cat} WHERE id=?", array($eid));
}

$mform = new local_course_creator_addheading_form(null, array('data' => $data, "category" => $categoryid));

if ($mform->is_cancelled()) {

    $returnurl = new moodle_url('/local/course_creator/headinglist.php?category=' . $SESSION->catid);

    redirect($returnurl);
} else if ($formdata = $mform->get_data()) {

    $eid = $formdata->eid;
    $catid = $formdata->catid;
    $categoryname = trim($formdata->name);
    // $category = $formdata->category;

    $returnurl = new moodle_url('/local/course_creator/headinglist.php?category=' . $catid);

    if ($categoryname != '') {

        $record = $DB->get_record_sql("select count(*) as allcount from {local_course_creator_cat} where name=?", array($categoryname));

        if ($record->allcount == 0) {

            if ($eid > 0) {
                $updatedatarecord = new stdClass();
                $updatedatarecord->id = $eid;
                $updatedatarecord->name = $categoryname;

                $result = $DB->update_record('local_course_creator_cat', $updatedatarecord);
            } else {
                $insert = new stdClass();
                $insert->name = $categoryname;
                $insert->timecreated = time();

                $result = $DB->insert_record('local_course_creator_cat', $insert, true);
            }
        }

        redirect(urldecode($returnurl));

    }

    redirect(urldecode($returnurl));
}

echo $OUTPUT->header();

echo $OUTPUT->box_start('generalbox');
$mform->display();
echo $OUTPUT->box_end();
echo $OUTPUT->footer();