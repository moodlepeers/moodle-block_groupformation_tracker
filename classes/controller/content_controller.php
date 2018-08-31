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
/** @var int ID of groupformation*/
private $groupformationid = null;

/**
 * gfTracker_content_controller constructor.
 * @param $context
 * @param $courseid
 * @param $groupformationid
 */
public function __construct($context, $courseid, $groupformationid)
{
    $this->context = $context;

    $this->courseid = $courseid;

    $this->groupformationid = $groupformationid;
}

    /**
     * Returns block content
     *
     * @param $userid
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     */
public function get_content($userid){

    global $PAGE;

    $content = new stdClass();
    $content->text = "<div class='container' style='width: auto;'>";

    $users = groupformation_get_users($this->groupformationid);

    $users = array_merge($users[0], $users[1]);

    $foruser = in_array($userid, $users);


    if (!(groupformation_get_instance_by_id($this->groupformationid) === false)){
        // shows an icon and the name of the groupformation at the top of the page
        $gfinstance = groupformation_get_instance_by_id($this->groupformationid);
        $content->text .= "<div class='col'>";
        if ($foruser)
            $content->text .= "<a href=\"/mod/groupformation/analysis_view.php?id=".groupformation_get_cm($this->groupformationid)."&do_show=analysis\" style='color: black'>";
        $content->text .= "<div style='background:url(/blocks/groupformation_tracker/images/icon_20px.png) left no-repeat; padding-left: 22px; height: 20px;'>";
        $content->text .= "<h5>";
        $content->text .= $gfinstance->name;
        $content->text .= "</h5>";
        $content->text .= "</div>";
        if ($foruser)
            $content->text .= "</a>";
        $content->text .= "</div>";
    }


    if (has_capability('moodle/block:edit', $this->context)){
        // it´s a teacher
        $controller = new gfTracker_teacher_content_controller($this->groupformationid);
        $content->text .= $controller->get_content();

    } else {
        // it´s a student

        $users = groupformation_get_users($this->groupformationid);

        $users = array_merge($users[0], $users[1]);

        $foruser = in_array($userid, $users);



        if (!$foruser) {
            $content->text .= "<div class='col'><p>" . get_string('notforuser', 'block_groupformation_tracker') . "</p></div>";
        } else {
            $controller = new gfTracker_user_content_controller($this->groupformationid,$userid);
            $content->text .= $controller->get_content();
        }

    }

    $content->text .= "</div>";

    return $content;
}

}