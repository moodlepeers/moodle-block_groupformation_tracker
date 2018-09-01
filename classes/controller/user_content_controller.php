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
 * Class gfTracker_user_content_controller
 *
 * @package block_groupformation_tracker
 * @author Rene Roepke, Sven Timpe
 * @copyright 2018 MoodlePeers
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');
require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/controller/badge_controller.php');
require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/utilities.php');

use groupformation_tracker\utilities;

class gfTracker_user_content_controller{

    /** @var int ID of groupformation */
    private $groupformationid = null;

    /** @var int ID of user */
    private $userid = null;

    /** @var mixed|null groupformationstate of user */
    private $userstate = null;

    /** @var mixed|null groupformationstate of activity */
    private $activitystate = null;

    /** @var gfTracker_badge_controller */
    private $badgecontroller = null;

    /** @var int cm of Groupformation */
    private $groupformationcm = null;

    /**
     * gfTracker_user_content_controller constructor.
     * @param $groupformationid
     * @param $userid
     * @throws dml_exception
     */
    public function __construct($groupformationid, $userid) {
        $this->groupformationid = $groupformationid;

        $this->userid = $userid;

        $this->userstate = groupformation_get_user_state($groupformationid, $userid);

        $this->activitystate = groupformation_get_activity_state($groupformationid);

        $this->badgecontroller = new gfTracker_badge_controller();

        $this->groupformationcm = groupformation_get_cm($groupformationid);
    }

    /**
     * Returns a part of block content
     *
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_content() {
        $text = "";

        if (groupformation_get_instance_by_id($this->groupformationid) === false) {
            $text .= "<div class='col'><p>";
            $text .= get_string('wait_for_teacher_choosegf', 'block_groupformation_tracker');
            $text .= "</p></div>";

            return $text;
        }

        switch ($this->activitystate){
            case "q_open":
                $text .= $this->content_open();
                break;

            case "q_closed":
                $text .= $this->content_closed();
                break;

            case "gf_started":

            case "gf_aborted":

            case "gf_done":

            case "ga_started":
                $text .= $this->content_gf_started();
                break;

            case "ga_done":
                $text .= $this->content_ga_done();
                break;

            case "q_reopened":
                $text .= $this->content_reopened();
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    /**
     * Returns content for activity state 'q_open'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_open() {

        $text = "";
        switch ($this->userstate){
            case "started":
                $text .= $this->content_started_open();
                break;

            case "consent_given":

            case "p_code_given":
                $text .= $this->content_p_code_given_open();
                break;

            case "answering":
                $text .= $this->content_answering_open();
                break;

            case "submitted":
                $text .= $this->content_submitted();
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    /**
     * Returns content for activity state 'q_closed'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_closed() {

        $text = "";
        switch ($this->userstate){
            case "started":

            case "consent_given":

            case "p_code_given":

            case "answering":
                $text .= $this->content_started_closed();
                break;

            case "submitted":
                $text .= $this->content_submitted();
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    /**
     * Returns content for activity state 'gf_started'/'gf_aborted'/'gf_done'/'ga_started'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_gf_started() {

        $text = "";
        switch ($this->userstate){
            case "started":

            case "consent_given":

            case "p_code_given":

            case "answering":
                $text .= $this->content_wait_gf_started();
                break;

            case "submitted":
                $text .= $this->content_submitted(true);
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    /**
     * Returns content for activity state 'q_reopened'
     *
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function content_reopened() {

        $text = "";
        switch ($this->userstate){
            case "started":
                $text .= $this->content_started_open("reopened");
                break;

            case "consent_given":

            case "p_code_given":
                $text .= $this->content_p_code_given_open("reopened");
                break;

            case "answering":
                $text .= $this->content_answering_open("reopened");
                break;

            case "submitted":
                $members = groupformation_get_group_members($this->groupformationid, $this->userid);
                if (count($members) > 0) {
                    $text .= $this->content_ga_done();
                } else {
                    $text .= $this->content_submitted();
                }
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    // Activity state: open.

    /**
     * Returns content for user state 'started'
     *
     * @param string $state
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function content_started_open($state = "open") {

        $dates = groupformation_get_dates($this->groupformationid);
        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge($state);
        $text .= "</div>";
        if ($dates['end_raw'] >= time()) {
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('q_closed_at', 'block_groupformation_tracker').$dates['end']."</p>";
            $text .= "</div>";
        }
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('start_answering', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_user_overview_button
        ($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for user state 'p_code_given'
     *
     * @param string $state
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function content_p_code_given_open($state = "open") {

        $dates = groupformation_get_dates($this->groupformationid);
        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge($state);
        $text .= "</div>";
        if ($dates['end_raw'] >= time()) {
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('q_closed_at', 'block_groupformation_tracker').$dates['end']."</p>";
            $text .= "</div>";
        }
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('start_answering', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_questionnaire_button
        ($this->groupformationcm, get_string('to_questionnaire', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for user state 'answering'
     *
     * @param string $state
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function content_answering_open($state = "open") {

        $dates = groupformation_get_dates($this->groupformationid);
        $numberofquestions = groupformation_get_number_of_questions($this->groupformationid);
        $questionsready = groupformation_get_number_of_answered_questions($this->groupformationid, $this->userid);
        $progress = ($questionsready / $numberofquestions) * 100;
        $progress = round($progress, 2);
        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge($state);
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_progressbar($progress);
        $text .= "</div>";
        if ($dates['end_raw'] >= time()) {
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('q_closed_at', 'block_groupformation_tracker').$dates['end']."</p>";
            $text .= "</div>";
        }
        if ($progress == 100) {
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('submit_questionnaire', 'block_groupformation_tracker')."</p>";
            $text .= "</div>";
            $text .= "<div class='col'>";
            $text .= $this->badgecontroller->get_go_to_user_overview_button
            ($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
            $text .= "</div>";
        } else {
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('continue_answering', 'block_groupformation_tracker')."</p>";
            $text .= "</div>";
            $text .= "<div class='col'>";
            $text .= $this->badgecontroller->get_go_to_questionnaire_button
            ($this->groupformationcm, get_string('to_questionnaire', 'block_groupformation_tracker'));
            $text .= "</div>";
        }

        return $text;
    }

    /**
     * Returns content for user state 'submitted'
     *
     * @param bool $gf_bool
     * @return string
     * @throws coding_exception
     */
    public function content_submitted($gfbool = false) {

        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("submitted");
        $text .= "</div>";
        if ($gfbool) {
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('gf_started_wait_for_teacher', 'block_groupformation_tracker')."</p>";
            $text .= "</div>";
        }
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_questionnaire_button
        ($this->groupformationcm, get_string('see_your_answers', 'block_groupformation_tracker'));
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_see_evaluation_button($this->groupformationcm);
        $text .= "</div>";

        return $text;
    }

    // Activity state: closed.

    /**
     * Returns content for user state 'started'/'consent_given'/'p_code_given'/'answering'
     *
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function content_started_closed() {

        $dates = groupformation_get_dates($this->groupformationid);
        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("closed");
        $text .= "</div>";
        $text .= "<div class='col'>";
        if ($dates['start_raw'] < time()) {
            $text .= "<p>" . get_string('closed_wait_for_teacher', 'block_groupformation_tracker') . "</p>";
        } else {
            $text .= "<p>".get_string('q_open_at', 'block_groupformation_tracker').$dates['start']."</p>";
        }
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for user state 'started'/'consent_given'/'p_code_given'/'answering'
     *
     * @param string $state
     * @return string
     * @throws coding_exception
     */
    public function content_wait_gf_started($state = "closed") {

        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge($state);
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('gf_started_wait_for_teacher', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for activity state 'ga_done'
     *
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function content_ga_done() {
        $text = "";

        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("ga_done");
        $text .= "</div>";

        if (groupformation_has_group($this->groupformationid, $this->userid)) {

            $text .= "<div class='col'>";
            $text .= "<p>".get_string('in_group', 'block_groupformation_tracker');
            $text .= " <b>";
            $text .= groupformation_get_group_name($this->groupformationid, $this->userid);
            $text .= "</b>";
            $text .= "</p>";
            $text .= "</div>";

            $text .= "<div class='col'>";
            $members = groupformation_get_group_members($this->groupformationid, $this->userid);
            if (count($members) > 0) {
                $text .= "<p>".get_string('your_groupmembers', 'block_groupformation_tracker');
                $text .= $this->create_group_member_list($members);
                $text .= "</p>";
            } else {
                $text .= "<p>".get_string('alone_in_group', 'block_groupformation_tracker')."</p>";
            }
            $text .= "</div>";
            $text .= "<div class='col'>";
            $text .= "<a href=\"".utilities::get_activity_url($this->groupformationid)
                ."\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">"
                .get_string("go_to_activity", "block_groupformation_tracker")."</a>";
            $text .= "</div>";
        } else {
            $text .= "<div class='col'>";
            $text .= "<p>";
            $text .= get_string('did_not_answer', 'block_groupformation_tracker');
            $text .= "</p>";
            $text .= "</div>";
        }
        return $text;
    }

    /**
     * Returns a html list of groupmembers
     *
     * @param $members
     * @return string
     * @throws dml_exception
     */
    public function create_group_member_list($members) {
        $list = '<ul class="list-group">';
        $gfinstance = groupformation_get_instance_by_id($this->groupformationid);
        foreach ($members as $memberid => $membername) {
            $list .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
            $list .= '<a href="'.utilities::get_user_profile_url($memberid, $gfinstance->course).'">'.$membername.'</a>';
            $list .= '</li>';
        }
        $list .= '</ul>';
        return $list;
    }
}