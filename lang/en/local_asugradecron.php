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
 * @package   local_asugradecron
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright 2015, Appalachian State University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'ASU Grade Cron';
$string['asugradecron_gradehistorylifetime_label'] = 'Grade history lifetime';
$string['asugradecron_gradehistorylifetime_desc'] = 'Enter the number of days you want to keep history of changes in grade related tables. It is recommended to keep it as long as possible. If you experience performance problems or have limited database space, try to set lower value. Enter 0 to never delete the history.';
