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
 * Class gfTracker_content_controller
 *
 * @package block_groupformation_tracker
 * @author Rene Roepke, Sven Timpe
 * @copyright 2018 MoodlePeers
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/controller/teacher_content_controller.php');
require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/controller/user_content_controller.php');

class gfTracker_content_controller{

    /** @var null context of current page */
    private $context = null;
    /** @var int ID of course */
    private $courseid = null;
    /** @var array of groupformations*/
    private $groupformationids = null;

    /**
     * gfTracker_content_controller constructor.
     * @param $context
     * @param $courseid
     * @param $groupformationids
     */
    public function __construct($context, $courseid, $groupformationids) {
        $this->context = $context;

        $this->courseid = $courseid;

        $this->groupformationids = $groupformationids;
    }

    public function get_content($userid){

        $content = new stdClass();
        $content->text = '';
        if (has_capability('moodle/block:edit', $this->context)){
            $role = 'teacher';
        } else {
            $role = 'student';
        }

        for ($i = 0; $i < count($this->groupformationids); $i++){
            $content->text .= $this->get_content_for_gfid($userid, $this->groupformationids[$i], $role);
            if ($i < count($this->groupformationids)-1){
                $content->text .= '<hr/>';
            }
        }

        return $content;
    }

    /**
     * Returns block content for groupformation with id $gfid
     *
     * @param $userid
     * @param $gfid
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_content_for_gfid($userid, $gfid, $role) {

        if ($role == 'teacher') {
            return $this->get_teacher_content_for_gfid($userid, $gfid);
        } else if ($role == 'student') {
            return $this->get_user_content_for_gfid($userid, $gfid);
        } else {
            return;
        }

        /*
        $text = "<div class='container' style='width: auto;'>";

        $users = groupformation_get_users($gfid);

        $users = array_merge($users[0], $users[1]);

        $foruser = in_array($userid, $users);

        if (!(groupformation_get_instance_by_id($gfid) === false)) {
            // Shows an icon and the name of the groupformation at the top of the page.
            $gfinstance = groupformation_get_instance_by_id($gfid);
            $text .= "<div class='col'>";
            if ($foruser) {
                $text .= "<a href=\"/mod/groupformation/analysis_view.php?id="
                    .groupformation_get_cm($gfid)."&do_show=analysis\" class=\"block-groupformation-tracker-name-link\">";
            }
            $text .= "<div class='block-groupformation-tracker-gfname'>";
            $text .= "<h5>";
            $text .= $gfinstance->name;
            $text .= "</h5>";
            $text .= "</div>";
            if ($foruser) {
                $text .= "</a>";
            }
            $text .= "</div>";
        }

        if (has_capability('moodle/block:edit', $this->context)) {
            // It´s a teacher.
            $controller = new gfTracker_teacher_content_controller($gfid);
            $text .= $controller->get_content();

        } else {
            // It´s a student.

            $users = groupformation_get_users($gfid);

            $users = array_merge($users[0], $users[1]);

            $foruser = in_array($userid, $users);

            if (!$foruser) {
                $text .= "<div class='col'><p>" . get_string('notforuser', 'block_groupformation_tracker') . "</p></div>";
            } else {
                $controller = new gfTracker_user_content_controller($gfid, $userid);
                $text .= $controller->get_content();
            }

        }

        $text .= "</div>";

        return $text;
        */
    }

    private function get_user_content_for_gfid($userid, $gfid) {
        $text = "<div class='container' style='width: auto;'>";

        $users = groupformation_get_users($gfid);

        $users = array_merge($users[0], $users[1]);

        $foruser = in_array($userid, $users);

        if (!(groupformation_get_instance_by_id($gfid) === false)) {
            // Shows an icon and the name of the groupformation at the top of the page.
            $gfinstance = groupformation_get_instance_by_id($gfid);
            $text .= "<div class='col'>";
            if ($foruser) {
                $text .= "<a href=\"/mod/groupformation/analysis_view.php?id="
                    .groupformation_get_cm($gfid)."&do_show=analysis\" class=\"block-groupformation-tracker-name-link\">";
            }
            $text .= "<div class='block-groupformation-tracker-gfname'>";
            $text .= "<h5>";
            $text .= $gfinstance->name;
            $text .= "</h5>";
            $text .= "</div>";
            if ($foruser) {
                $text .= "</a>";
            }
            $text .= "</div>";

            if (!$foruser) {
                $text .= "<div class='col'><p>" . get_string('notforuser', 'block_groupformation_tracker') . "</p></div>";
            } else {
                $controller = new gfTracker_user_content_controller($gfid, $userid);
                $text .= $controller->get_content();
            }
        }

        return $text;
    }

    private function get_teacher_content_for_gfid($userid, $gfid) {

        $text = "<div class='container' style='width: auto;'>";

        if (!(groupformation_get_instance_by_id($gfid) === false)) {
            // Shows an icon and the name of the groupformation at the top of the page.
            $gfinstance = groupformation_get_instance_by_id($gfid);
            $text .= "<div class='col'>";
            $text .= "<a href=\"/mod/groupformation/analysis_view.php?id="
                .groupformation_get_cm($gfid)."&do_show=analysis\" class=\"block-groupformation-tracker-name-link\">";

            $text .= "<div class='block-groupformation-tracker-gfname'>";
            $text .= "<h5>";
            $text .= $gfinstance->name;
            $text .= "</h5>";
            $text .= "</div>";
            $text .= "</a>";

            $text .= "</div>";

            $controller = new gfTracker_teacher_content_controller($gfid);
            $text .= $controller->get_content();
        }

        return $text;
    }

}