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
 * @package local_course_creator
 * @author  2026 Shubhendra Doiphode (Github: doiphode)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_course_creator\output;

use moodle_url;
use moodle_page;
use core\output\help_icon;
use core\output\select_menu;
use core\output\single_button;
use stdClass;

/**
 * Generates the action bar for course creator pages, which includes a selection menu to navigate between different course creator pages.
 */
class manager_actionbar implements \templatable {
    /**
     * Category id.
     * @var int
     */
    protected int $categoryid;

    /**
     * The page we are rendering on, used to determine the active url for the action bar.
     * @var moodle_page
     */
    protected moodle_page $page;

    /**
     * The current page we are on, used to determine the active url for the action bar.
     * @var string
     */
    protected string $currentpage;

    /**
     * Action bar constructor.
     *
     * @param moodle_page $page
     * @param int $category
     * @param string $currentpage
     */
    public function __construct(moodle_page $page, int $category, string $currentpage = '') {
        $this->categoryid = $category;
        $this->page = $page;
        $this->currentpage = $currentpage;
    }

    /**
     * Get the name of the template to render the action bar.
     *
     * @return string
     */
    public function get_template_name(): string {
        return 'local_course_creator/manager_actionbar';
    }

    /**
     * Export the data for the template.
     *
     * Generate the selection menu for course creator pages.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(\renderer_base $output) {
        global $PAGE;

        $headinglist = (new moodle_url('/local/course_creator/headinglist.php', ['category' => $this->categoryid]))->out(false);
        $courselist = (new moodle_url('/local/course_creator/courselist.php', ['category' => $this->categoryid]))->out(false);
        $addcourseurl = (new \moodle_url('/local/course_creator/addcourse.php', ['category' => $this->categoryid]))->out(false);
        $addheadingurl = (new \moodle_url('/local/course_creator/addheading.php', ['category' => $this->categoryid]))->out(false);

        $headinglistlabel = get_string('heading_list', 'local_course_creator');
        $courselistlabel = get_string('course_list', 'local_course_creator');
        $addcourselabel = get_string('add_course', 'local_course_creator');
        $addheadinglabel = get_string('add_heading', 'local_course_creator');

        $nodes = [
            $headinglist => $headinglistlabel,
            $courselist => $courselistlabel,
            $addcourseurl => $addcourselabel,
            $addheadingurl => $addheadinglabel
        ];

        $pages = [
            'addcourse' => [
                [$addheadingurl, $addheadinglabel], [$courselist, $courselistlabel],
            ],
            'addheading' => [
                [$addcourseurl, $addcourselabel], [$headinglist, $headinglistlabel],
            ],
            'courselist' => [
                [$addcourseurl, $addcourselabel], [$addheadingurl, $addheadinglabel],
            ],
            'headinglist' => [
                [$addheadingurl, $addheadinglabel], [$addcourseurl, $addcourselabel],
            ],
        ];

        // Action buttons.
        $actionbuttons = [];
        if (isset($pages[$this->currentpage])) {
            foreach ($pages[$this->currentpage] as $page) {
                $actionbuttons[] = ['url' => $page[0], 'label' => $page[1]];
            }
        }

        $activeurl = $this->page->url->out(false);

        $selectmenu = new select_menu('coursecreatornavigation', $nodes, $activeurl, true);
        $selectmenu->set_label(get_string('participantsnavigation', 'course'), ['class' => 'visually-hidden']);

        return ['navigation' => $selectmenu->export_for_template($output), 'actionbuttons' => $actionbuttons];
    }

    /**
     * Helper to render the action bar from outside a template context
     *
     * @param moodle_page $page
     * @param int $categoryid
     * @return string
     */
    public static function instance(moodle_page $page, int $categoryid, string $currentpage = '') : string {
        global $OUTPUT;

        $manager = new self($page, $categoryid, $currentpage);
        return $OUTPUT->render_from_template(
            $manager->get_template_name(),
            $manager->export_for_template($OUTPUT)
        );
    }
}
