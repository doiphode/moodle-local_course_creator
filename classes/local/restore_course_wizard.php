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
namespace local_course_creator\local;
// No direct script access.
require_once(dirname(dirname(dirname(__FILE__))) . '/../../config.php');
defined('MOODLE_INTERNAL') || die();
/* *
 * Defaults for the local course create
 */
global $DB, $CFG, $EXTDB;

require_once $CFG->libdir . '/adodb/adodb.inc.php';
require_once $CFG->dirroot . '/course/lib.php';

/**
 *
 * restore_course is a class to restore the backup course from course creation wizard
 * @package local
 * @subpackage course_creator
 * @author      Shubhendra Doiphode (Github: doiphode)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_course_wizard {
    protected $fullname;
    protected $shortname;
    protected $category;
    protected $directory;
    protected $filepath;
    protected $courseid;

    function __construct($category, $fullname, $shortname) {
        $this->category = $category;
        $this->fullname = $fullname;
        $this->shortname = $shortname;
    }

    public function extract_backup_file($filename) {
        GLOBAL $CFG;
        $this->directory = $directory = $CFG->tempdir . '/backup/';
        $this->filepath = $filepath = $filename . '_folder';
        $archive = $directory . $filename;
        $folder = $directory . $filepath;
        mkdir($folder);
        $fb = get_file_packer('application/vnd.moodle.backup');
        $fb->extract_to_pathname($archive, $folder);
        self::delete($archive);
        return true;
    }

    protected function delete($dir) {

        if (!file_exists($dir)) {
            return true;
        }
        unlink($dir);
        return true;
    }

    protected function update_course() {
        $course_data = new \stdClass();
        $course_data->id = $this->courseid;
        $course_data->fullname = $this->fullname;
        $course_data->shortname = $this->shortname;
        $course_data->visible = 1;
        update_course($course_data);
    }

    public function execute() {
        GLOBAL $CFG, $USER, $DB, $OUTPUT, $PAGE;

        require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
        require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
        // Transaction.
        $transaction = $DB->start_delegated_transaction();
        // Prepare a progress bar which can display optionally during long-running
        // Create new course.
        $folder = $this->filepath; // as found in: $CFG->dataroot . '/temp/backup/'
        $categoryid = $this->category; // e.g. 1 == Miscellaneous
        $userdoingrestore = $USER->id; // e.g. 2 == admin
        $courseid = \restore_dbops::create_new_course('Restored Course', 'RES', $categoryid);
        $this->courseid = $courseid;

        // Restore backup into course.
        $controller = new \restore_controller($folder, $courseid,
            \backup::INTERACTIVE_NO, \backup::MODE_GENERAL, $userdoingrestore,
            \backup::TARGET_NEW_COURSE);
        $controller->execute_precheck();
        echo $OUTPUT->header();
        $renderer = $PAGE->get_renderer('local_course_creator');
        echo $renderer->display_breadcrumb(2);
        echo get_string('course_restoring', 'local_course_creator');

        // Commit.
        $transaction->allow_commit();

        // Div used to hide the 'progress' step once the page gets onto 'finished'.
        echo \html_writer::start_div('', array('id' => 'executionprogress'));
        // Start displaying the actual progress bar percentage.
        $controller->set_progress(new \core\progress\display());
        $controller->execute_plan();
        echo \html_writer::end_div();
        //echo html_writer::script('document.getElementById("executionprogress").style.display = "none";');
        $controller->destroy();
        self::delete($this->directory . $this->filepath);
        self::update_course();
    }

    public function get_course_id() {
        return $this->courseid;
    }
}