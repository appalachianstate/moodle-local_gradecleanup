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
 * @package   local_gradecleanup
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright 2015, Appalachian State University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gradecleanup\task;

defined('MOODLE_INTERNAL') || die();

class gradecleanup extends \core\task\scheduled_task {
    public function get_name() {
        // Shown in admin screens.
        return get_string('pluginname', 'local_gradecleanup');
    }

    public function execute() {
        global $CFG, $DB;

        $daystokeep = get_config('local_gradecleanup', 'gradecleanup_daystokeep');

        // Check for invalid configuration.
        if (!is_numeric($daystokeep) || $daystokeep < 0) {
            mtrace("    Days to keep setting is invalid");
            return;
        }

        // Check for config value of 0 or not set.
        if (!$daystokeep) {
            mtrace("    Days to keep setting is not set or set to 0");
            return;
        }

        // Calculate time to stop deleting grade histories.
        $deletestopdate = time() - ($daystokeep * DAYSECS);
        $humanreadabledate = date('c', $deletestopdate);
        mtrace("    Deleting grade history prior to '$humanreadabledate'");

        $tables = array('grade_outcomes_history', 'grade_categories_history',
                'grade_items_history', 'grade_grades_history', 'scale_history');

        foreach ($tables as $table) {
            if ($DB->delete_records_select($table, "timemodified < ?", array($deletestopdate))) {
                mtrace("    Deleted old grade history records from '$table'");
            }
        }
    }
}
