<?php

namespace groupformation_tracker;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

class utilities {

    public static function get_activity_url($groupformationid) {
        $cmid = groupformation_get_cm($groupformationid);
        return "/mod/groupformation/view.php?id=".$cmid;
    }

    public static function get_user_profile_url($userid, $courseid) {
        global $CFG;
        $uri = $CFG->wwwroot . '/user/view.php?id=' . $userid . '&course=' . $courseid;
        return $uri;
    }

    public static function get_link_button($url, $string){
        $text = "<a href=\"".$url."\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">".$string."</a>";

        return $text;
    }
}