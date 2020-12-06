<?php

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
 * External Web Service Template
 *
 * @package   plugintype_local
 * @copyright 2020, Vlad <force55595@gmai.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_local_external extends external_api
{

    // test service
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function hello_world_parameters()
    {
        return new external_function_parameters(
            array('welcomemessage' => new external_value(PARAM_TEXT, 'The welcome message. By default it is "Hello world,"', VALUE_DEFAULT, 'Hello world, '))
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function hello_world($welcomemessage = 'Hello world, ')
    {
        global $USER;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::hello_world_parameters(),
            array('welcomemessage' => $welcomemessage));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }

        return $params['welcomemessage'] . $USER->firstname;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function hello_world_returns()
    {
        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }

    // end test service

    /**
     * Return users
     * @param string $users
     * @return false|string return users
     * @throws invalid_parameter_exception
     */
    public static function get_users($users = 'all')
    {
        $params = self::validate_parameters(self::get_users_parameters(),
            array('users' => $users));

        if ($params['users'] == 'all') {
            $allUsers = get_users(true, '', '', null, '', '', '', '', '', 'id,firstname,lastname,email');
        } else {
            $allUsers = false;
        }

        return json_encode($allUsers);
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_users_parameters()
    {
        return new external_function_parameters(
            array('users' => new external_value(PARAM_TEXT, 'Get users', VALUE_DEFAULT, 'all'))
        );
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_users_returns()
    {
        return new external_value(PARAM_TEXT, 'Return users');
    }

    /**
     * Return courses
     * @param string $courses
     * @return false|string return courses
     * @throws dml_exception
     * @throws invalid_parameter_exception
     */
    public static function get_courses($courses = 'all')
    {
        $params = self::validate_parameters(self::get_courses_parameters(),
            array('courses' => $courses));

        if ($params['courses'] == 'all') {
            $courses = get_courses('all', '', 'c.id,c.fullname');
        } else {
            $courses = false;
        }

        return json_encode($courses);
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_courses_parameters()
    {
        return new external_function_parameters(
            array('courses' => new external_value(PARAM_TEXT, 'Get courses', VALUE_DEFAULT, 'all'))
        );
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_courses_returns()
    {
        return new external_value(PARAM_TEXT, 'Return courses');
    }


    public static function get_enrolled_users($users = '')
    {

        global $DB;

        $params = self::validate_parameters(self::get_enrolled_users_parameters(),
            array('users' => $users));

        if ($params['users'] == 'all') {
            $sql = "SELECT  
                    u.id AS 'User_ID',
                    CONCAT(u.firstname , ' ' , u.lastname) AS 'Name', 
                    c.fullname AS 'Course', 
                    ROUND(gg.finalgrade,2) AS Grade
                
                    FROM mdl_course AS c
                    JOIN mdl_context AS ctx ON c.id = ctx.instanceid
                    JOIN mdl_role_assignments AS ra ON ra.contextid = ctx.id
                    JOIN mdl_user AS u ON u.id = ra.userid
                    JOIN mdl_grade_grades AS gg ON gg.userid = u.id
                    JOIN mdl_grade_items AS gi ON gi.id = gg.itemid
                    JOIN mdl_course_categories AS cc ON cc.id = c.category
                     
                    WHERE  gi.courseid = c.id 
                    ORDER BY lastname";
            $users = $DB->get_records_sql($sql);
        } else {
            $users = false;
        }

        return json_encode($users);
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_enrolled_users_parameters()
    {
        return new external_function_parameters(
            array('users' => new external_value(PARAM_TEXT, 'Get courses', VALUE_DEFAULT, 'all'))
        );
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_enrolled_users_returns()
    {
        return new external_value(PARAM_TEXT, 'Return courses');
    }


}