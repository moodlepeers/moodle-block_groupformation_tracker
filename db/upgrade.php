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
 * Upgrade function for database changes
 *
 * @package block_groupformation_tracker
 * @copyright 2018 MoodlePeers
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die ();

/**
 * Execute groupformation upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_groupformation_tracker_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2018070900) {

        // Define field groupformationid to be added to block_groupformation_tracker.
        $table = new xmldb_table('block_groupformation_tracker');
        $field = new xmldb_field('groupformationid', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'courseid');

        // Conditionally launch add field groupformationid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Groupformation_tracker savepoint reached.
        upgrade_block_savepoint(true, 2018070900, 'groupformation_tracker');
    }

    return true;
}
