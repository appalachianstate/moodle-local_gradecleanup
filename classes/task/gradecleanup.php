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
    public static $choices = array('0' => 'Never delete history',
                                   '1000' => '1000 days',
                                   '365' => '365 days',
                                   '180' => '180 days',
                                   '150' => '150 days',
                                   '120' => '120 days',
                                   '90' => '90 days',
                                   '60' => '60 days',
                                   '30' => '30 days');

    public function get_name() {
        // Shown in admin screens.
        return get_string('pluginname', 'local_gradecleanup');
    }

    public function execute() {
        global $DB;

        $daystokeep = get_config('local_gradecleanup', 'gradecleanup_daystokeep');

        // Check for config value of 0 or not set.
        if (!$daystokeep) {
            mtrace("    Days to keep setting is not set or set to 0");
            return;
        }

        // Check for invalid configuration.
        if (!is_numeric($daystokeep) || $daystokeep < 0 || !array_key_exists($daystokeep, self::$choices)) {
            mtrace("    Days to keep setting is invalid");
            return;
        }

        // Calculate time to stop deleting grade histories.
        $deletestopdate = time() - ($daystokeep * DAYSECS);
        mtrace("    Deleting grade history prior to " . date('c', $deletestopdate));

        $tables = array('grade_outcomes_history',
                        'grade_categories_history',
                        'grade_items_history',
                        'grade_grades_history',
                        'scale_history');

        foreach ($tables as $table) {
            if ($DB->delete_records_select($table, "timemodified < ?", array($deletestopdate))) {
                mtrace("    Deleted old grade history records from '$table'");
            }
        }
    }
}
