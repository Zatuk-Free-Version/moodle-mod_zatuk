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

namespace mod_zatuk;

use advanced_testcase;

/**
 * Genarator tests class for mod_zatuk.
 *
 * @package    mod_zatuk
 * @category   test
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class generator_test extends advanced_testcase {

    /**
     * Test on zatuk activity creation.
     */
    public function test_create_instance(): void {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();

        $this->assertFalse($DB->record_exists('zatuk', ['course' => $course->id]));
        $zatuk = $this->getDataGenerator()->create_module('zatuk', ['course' => $course]);
        $records = $DB->get_records('zatuk', ['course' => $course->id], 'id');
        $this->assertEquals(1, count($records));
        $this->assertTrue(array_key_exists($zatuk->id, $records));

        $params = ['course' => $course->id, 'name' => 'Another zatuk'];
        $zatuk = $this->getDataGenerator()->create_module('zatuk', $params);
        $records = $DB->get_records('zatuk', ['course' => $course->id], 'id');
        $this->assertEquals(2, count($records));
        $this->assertEquals('Another zatuk', $records[$zatuk->id]->name);
    }
}
