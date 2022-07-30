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

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once 'forms/local_course_creator_course_create_form.php';

global $DB, $CFG, $USER, $COURSE, $OUTPUT, $PAGE, $EXTDB;
$categoryid = optional_param('category', '0', PARAM_INT); // Course category - can be changed in edit form.

global $SESSION;
$SESSION->catid = $categoryid;

require_login();

// Permissions --
$catcontext = context_coursecat::instance($categoryid);
require_capability('moodle/course:create', $catcontext);
require_capability('moodle/backup:backupcourse', context_course::instance($COURSE->id));
// --

$PAGE->set_context(context_coursecat::instance($categoryid));

$PAGE->requires->jquery();
$require = $PAGE->requires;

//Try to add js externally
$jsUrl = new moodle_url($CFG->wwwroot . '/local/course_creator/module.js');
$require = $PAGE->requires;
$require->js($jsUrl);
//try to load normal js
$PAGE->requires->js_init_call('M.course_create.toggle');
$PAGE->set_url('/local/course_creator/view.php', array('category' => $categoryid));
$PAGE->set_title(get_string('pluginname','local_course_creator'));
$PAGE->set_heading(get_string('choose','local_course_creator'));
$PAGE->set_pagelayout('admin');


$renderer = $PAGE->get_renderer('local_course_creator');
$editform = new local_course_creator_course_create_form($CFG->wwwroot . '/local/course_creator/view.php?category=' . $categoryid);
if ($editform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect(new moodle_url('/'));
} else if ($fromform = $editform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    $course_template_id = $fromform->course_template;
    $course_create_type = $fromform->createchoice;
    $course_prev_id = $fromform->course_prev;
    if ($course_create_type == "2") {
        echo 'redirecting...';
        redirect(new moodle_url('/course/edit.php', array('category' => $categoryid, 'returnto' => 'category')));
    } elseif ($course_create_type == "1") {
        redirect(new moodle_url('/local/course_creator/course_backup.php', array('course' => $course_prev_id, 'category' => $categoryid, 'returnto' => 'category')));
    } elseif ($course_create_type == "0") {
        redirect(new moodle_url('/local/course_creator/course_backup.php', array('course' => $course_template_id, 'category' => $categoryid, 'returnto' => 'category')));
    }
} else {
    echo $OUTPUT->header();

    // ---
    $addurl = new moodle_url('/local/course_creator/addheading.php?category=' . $categoryid);
    //$linkcontent = '<div style="text-align:right;margin-bottom: 20px;"><a href="'.$addurl.'">'.get_string('add_heading','local_course_creator').'</a></div>';
    //echo $linkcontent;
    // ---

    echo $renderer->display_breadcrumb(0);
    $editform->display();
    echo $OUTPUT->footer();
}
?>