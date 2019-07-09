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
    /** @var string shows whether it is a student or a teacher */
    private $role = null;
    /** @var gfTracker_badge_controller */
    private $badgecontroller = null;

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

        $this->badgecontroller = new gfTracker_badge_controller();

        if (has_capability('moodle/block:edit', $this->context)){
            // It is a teacher.
            $this->role = 'teacher';
        } else {
            // It is a student.
            $this->role = 'student';
        }
    }

    /**
     * Returns block content for all groupformation that should be tracked
     *
     * @param $userid
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_content($userid){

        if ($this->role == 'student') {
            $this->groupformationids = array_intersect($this->groupformationids, get_groupformationids_for_user($userid));
            sort($this->groupformationids);
        }

        $content = new stdClass();
        $content->text = '';

        for ($i = 0; $i < count($this->groupformationids); $i++){
            $content->text .= $this->get_content_for_gfid($userid, $this->groupformationids[$i], $this->role);
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
    }

    /**
     * Returns block user block content for a special groupformation
     *
     * @param $userid
     * @param $gfid
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
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

            $buttonname = 'tracker_button_gfid_'.$gfid.'_userid_'.$userid;
            $tracker_button = optional_param($buttonname, null, PARAM_INT);
            $tracked = get_gf_tracked_for_user($userid, $gfid);
            if (isset($tracker_button)) {
                if ($tracked) {
                    $tracked = 0;
                } else if (!$tracked) {
                    $tracked = 1;
                }
                set_gf_tracked_for_user($userid, $gfid, $tracked);
            }

            $text .= $this->badgecontroller->get_tracker_button($buttonname, $this->courseid);
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
            } else if ($tracked) {
                $controller = new gfTracker_user_content_controller($gfid, $userid);
                $text .= $controller->get_content();
            }
        }

        return $text;
    }

    /**
     * Returns block teacher block content for a speoial groupformation
     *
     * @param $userid
     * @param $gfid
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
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