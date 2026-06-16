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
 * @package local
 * @subpackage course_creator
 * @author      Shubhendra Doiphode (Github: doiphode)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$plugin->version = 2026061600;        // The current plugin version (Date: YYYYMMDDXX)

$plugin->requires = 2022112800;        // Moodle 4.1 onwards
$plugin->component = 'local_course_creator';       // Full name of the plugin (used for diagnostics)
$plugin->maturity = MATURITY_STABLE; // required for registering to Moodle's database of plugins
$plugin->release = '5.1 (Build 20260616)';  // required for registering to Moodle's database of plugins
$plugin->dependencies = array();
$plugin->supports = [401, 502]; // This plugin supports Moodle 4.1 to 5.2.
