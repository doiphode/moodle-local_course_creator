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
define('NO_OUTPUT_BUFFERING', true);
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once('classlib.php');
global $DB, $CFG, $USER, $COURSE, $PAGE, $EXTDB, $OUTPUT;
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
require_once 'forms/course_restore_form.php';
require_login();

// Permissions --
require_capability('moodle/backup:backupcourse', context_course::instance($COURSE->id));
// --

$PAGE->set_context(context_system::instance());
$filename = required_param('filename', PARAM_ALPHANUM);
$url = new moodle_url('/local/course_creation_wizard/course_restore.php', array('filename' => $filename));
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');
$PAGE->set_title('Course Creation Wizard');
$PAGE->set_heading('Choose');
$restoreform = new course_restore_form($CFG->wwwroot . '/local/course_creation_wizard/course_restore.php?filename=' . $filename);
$renderer = $PAGE->get_renderer('local_course_creation_wizard');
if ($restoreform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    $directory = $CFG->tempdir . '/backup/';
    $archive = $directory . $filename;
    unlink($archive);
    redirect(new moodle_url('/'));
} else if ($fromform = $restoreform->get_data()) {
    $categoryid = $fromform->course_categories;
    $course_full_name = $fromform->fullname;
    $course_shortname = $fromform->shortname;
    $restore_course = new restore_course_wizard($categoryid, $course_full_name, $course_shortname);
    $restore_course->extract_backup_file($filename);
    $restore_course->execute();

    echo "<script type='text/javascript'>window.location='" . new moodle_url('/course/view.php', array('id' => $restore_course->get_course_id())) . "'</script>";
    // redirect(new moodle_url('/course/view.php', array('id' => $restore_course->get_course_id())));
} else {
    echo $OUTPUT->header();
    echo $renderer->display_breadcrumb(2);
    $restoreform->display();
    echo $OUTPUT->footer();
}
?>