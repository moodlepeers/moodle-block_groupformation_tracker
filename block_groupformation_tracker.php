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
 * Class block_pseudolearner
 *
 * @package block_groupformation_tracker
 * @author Rene Roepke, Sven Timpe
 * @copyright 2018 MoodlePeers
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/controller/content_controller.php');

class block_groupformation_tracker extends block_base {

    /**
     * Init function
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_groupformation_tracker');
    }

    /**
     * On deletion of instance
     *
     * @return bool
     * @throws dml_exception
     */
    public function instance_delete() {
        global $DB;
        $courseid = $this->page->course->id;

        $DB->delete_records('block_groupformation_tracker', array('courseid' => $courseid));

        return true;
    }

    /**
     * On creation of instance
     *
     * @return bool
     * @throws dml_exception
     */
    public function instance_create() {
        global $DB;
        $courseid = $this->page->course->id;
        $record = new stdClass();
        $record->courseid = $courseid;
        $DB->insert_record('block_groupformation_tracker', $record);
        return true;
    }

    /**
     * Returns content object
     *
     * @return stdClass|stdObject|string
     * @throws coding_exception
     */
    public function get_content() {

        global $USER, $COURSE;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        // User/index.php expect course context, so get one if page has module context.
        $currentcontext = $this->page->context->get_course_context(false);

        $this->content = new stdClass();
        if (has_capability('moodle/block:edit', $currentcontext)) $this->content->text = get_string('choosegf', 'block_groupformation_tracker');
        else $this->content->text = get_string('wait_for_teacher_choosegf', 'block_groupformation_tracker');
        // Use lang strings.

        if (empty($currentcontext)) {
            return $this->content;
        }

        if (isset($this->config->groupformationid) && groupformation_check_instance($this->config->groupformationid)) {
            $controller = new gfTracker_content_controller($currentcontext, $this->page->course->id, $this->config->groupformationid);
            $this->content = $controller->get_content($USER->id);
        }

        return $this->content;
    }

    public function applicable_formats() {
        return array('all' => false,
                     'site' => false,
                     'site-index' => false,
                     'course-view' => true,
                     'course-view-social' => false,
                     'mod' => false,
                     'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
          return true;
    }

    public function has_config() {
        return true;
    }
}
