<?php
// This file is part of the customcert module for Moodle - http://moodle.org/
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
 * This file contains the customcert element expirationdate's core interaction API.
 *
 * @package    customcertelement_expirationdate
 * @copyright  2013 Original By: Mark Nelson <markn@moodle.com> Modified By: Les Shier <lesshier@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace customcertelement_expirationdate;

defined('MOODLE_INTERNAL') || die();

/**
 * Date - Course grade date
 */
define('CUSTOMCERT_EXPIRATIONDATE_COURSE_GRADE', '0');

/**
 * Date - Issue
 */
define('CUSTOMCERT_EXPIRATIONDATE_ISSUE', '-1');

/**
 * Date - Completion
 */
define('CUSTOMCERT_EXPIRATIONDATE_COMPLETION', '-2');

/**
 * Date - Course start
 */
define('CUSTOMCERT_EXPIRATIONDATE_COURSE_START', '-3');

/**
 * Date - Course end
 */
define('CUSTOMCERT_EXPIRATIONDATE_COURSE_END', '-4');

/**
 * Date - Current date
 */
define('CUSTOMCERT_EXPIRATIONDATE_CURRENT_DATE', '-5');

/**
 * Interval - Year
 */
define('CUSTOMCERT_EXPIRATIONDATE_YEAR', '0');

/**
 * Interval - Month
 */
define('CUSTOMCERT_EXPIRATIONDATE_MONTH', '-1');

/**
 * Interval - Day
 */
define('CUSTOMCERT_EXPIRATIONDATE_DAY', '-2');

/**
 * Interval - Hour
 */
define('CUSTOMCERT_EXPIRATIONDATE_HOUR', '-3');

/**
 * Interval - Minute
 */
define('CUSTOMCERT_EXPIRATIONDATE_MINUTE', '-4');

/**
 * Interval - Second
 */
define('CUSTOMCERT_EXPIRATIONDATE_SECOND', '-5');


require_once($CFG->dirroot . '/lib/grade/constants.php');

/**
 * The customcert element expiration date's core interaction API.
 *
 * @package    customcertelement_expirationdate
 * @copyright  2013 Original By: Mark Nelson <markn@moodle.com> Modified By: Les Shier <lesshier@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class element extends \mod_customcert\element {

    /**
     * This function renders the form elements when adding a customcert element.
     *
     * @param \mod_customcert\edit_element_form $mform the edit_form instance
     */
    public function render_form_elements($mform) {
        global $COURSE;

        // Get the possible date options.
        $dateoptions = array();
        $dateoptions[CUSTOMCERT_EXPIRATIONDATE_ISSUE] = get_string('issueddate', 'customcertelement_expirationdate');
        $dateoptions[CUSTOMCERT_EXPIRATIONDATE_CURRENT_DATE] = get_string('currentdate', 'customcertelement_expirationdate');
        $dateoptions[CUSTOMCERT_EXPIRATIONDATE_COMPLETION] = get_string('completiondate', 'customcertelement_expirationdate');
        $dateoptions[CUSTOMCERT_EXPIRATIONDATE_COURSE_START] = get_string('coursestartdate', 'customcertelement_expirationdate');
        $dateoptions[CUSTOMCERT_EXPIRATIONDATE_COURSE_END] = get_string('courseenddate', 'customcertelement_expirationdate');
        $dateoptions[CUSTOMCERT_EXPIRATIONDATE_COURSE_GRADE] = get_string('coursegradedate', 'customcertelement_expirationdate');
        $dateoptions = $dateoptions + \mod_customcert\element_helper::get_grade_items($COURSE);

        $intervalunitoptions = array();
        $intervalunitoptions[CUSTOMCERT_EXPIRATIONDATE_YEAR] = get_string('intervalyear', 'customcertelement_expirationdate');
        $intervalunitoptions[CUSTOMCERT_EXPIRATIONDATE_MONTH] = get_string('intervalmonth', 'customcertelement_expirationdate');
        $intervalunitoptions[CUSTOMCERT_EXPIRATIONDATE_DAY] = get_string('intervalday', 'customcertelement_expirationdate');
        $intervalunitoptions[CUSTOMCERT_EXPIRATIONDATE_HOUR] = get_string('intervalhour', 'customcertelement_expirationdate');
        $intervalunitoptions[CUSTOMCERT_EXPIRATIONDATE_MINUTE] = get_string('intervalminute', 'customcertelement_expirationdate');
        $intervalunitoptions[CUSTOMCERT_EXPIRATIONDATE_SECOND] = get_string('intervalsecond', 'customcertelement_expirationdate');

        $mform->addElement('select', 'dateitem', get_string('dateitem', 'customcertelement_expirationdate'), $dateoptions);
        $mform->addHelpButton('dateitem', 'dateitem', 'customcertelement_expirationdate');

        $mform->addElement('select', 'dateformat', get_string('dateformat', 'customcertelement_expirationdate'), self::get_expirationdate_formats());
        $mform->addHelpButton('dateformat', 'dateformat', 'customcertelement_expirationdate');

        $mform->addElement('text', 'interval', get_string('interval', 'customcertelement_expirationdate'));        
        $mform->addHelpButton('interval', 'interval', 'customcertelement_expirationdate');
        $mform->setType('interval', PARAM_INT);
        $mform->setDefault('interval', 0);

        $mform->addElement('select', 'intervalunit', get_string('intervalunit', 'customcertelement_expirationdate'), $intervalunitoptions);
        $mform->addHelpButton('intervalunit', 'intervalunit', 'customcertelement_expirationdate');

        parent::render_form_elements($mform);
    }

    /**
     * This will handle how form data will be saved into the data column in the
     * customcert_elements table.
     *
     * @param \stdClass $data the form data
     * @return string the json encoded array
     */
    public function save_unique_data($data) {
        // Array of data we will be storing in the database.
        $arrtostore = array(
            'dateitem' => $data->dateitem,
            'dateformat' => $data->dateformat,
            'interval' => $data->interval,
            'intervalunit' => $data->intervalunit
        );

        // Encode these variables before saving into the DB.
        return json_encode($arrtostore);
    }

    /**
     * Handles rendering the element on the pdf.
     *
     * @param \pdf $pdf the pdf object
     * @param bool $preview true if it is a preview, false otherwise
     * @param \stdClass $user the user we are rendering this for
     */
    public function render($pdf, $preview, $user) {
        global $DB;

        // If there is no element data, we have nothing to display.
        $data = $this->get_data();
        if (empty($data)) {
            return;
        }

        $courseid = \mod_customcert\element_helper::get_courseid($this->id);

        // Decode the information stored in the database.
        $dateinfo = json_decode($data);
        $dateitem = $dateinfo->dateitem;
        $dateformat = $dateinfo->dateformat;
        $interval = $dateinfo->interval;
        $intervalunit = $dateinfo->intervalunit;

        // If we are previewing this certificate then just show a demonstration date.
        if ($preview) {
            $date = time();
        } else {
            // Get the page.
            $page = $DB->get_record('customcert_pages', array('id' => $this->get_pageid()), '*', MUST_EXIST);
            // Get the customcert this page belongs to.
            $customcert = $DB->get_record('customcert', array('templateid' => $page->templateid), '*', MUST_EXIST);
            // Now we can get the issue for this user.
            $issue = $DB->get_record('customcert_issues', array('userid' => $user->id, 'customcertid' => $customcert->id),
                '*', MUST_EXIST);

            if ($dateitem == CUSTOMCERT_EXPIRATIONDATE_ISSUE) {
                $date = $issue->timecreated;
            } else if ($dateitem == CUSTOMCERT_EXPIRATIONDATE_CURRENT_DATE) {
                $date = time();
            } else if ($dateitem == CUSTOMCERT_EXPIRATIONDATE_COMPLETION) {
                // Get the last completion date.
                $sql = "SELECT MAX(c.timecompleted) as timecompleted
                          FROM {course_completions} c
                         WHERE c.userid = :userid
                           AND c.course = :courseid";
                if ($timecompleted = $DB->get_record_sql($sql, array('userid' => $issue->userid, 'courseid' => $courseid))) {
                    if (!empty($timecompleted->timecompleted)) {
                        $date = $timecompleted->timecompleted;
                    }
                }
            } else if ($dateitem == CUSTOMCERT_EXPIRATIONDATE_COURSE_START) {
                $date = $DB->get_field('course', 'startdate', array('id' => $courseid));
            } else if ($dateitem == CUSTOMCERT_EXPIRATIONDATE_COURSE_END) {
                $date = $DB->get_field('course', 'enddate', array('id' => $courseid));
            } else {
                if ($dateitem == CUSTOMCERT_EXPIRATIONDATE_COURSE_GRADE) {
                    $grade = \mod_customcert\element_helper::get_course_grade_info(
                        $courseid,
                        GRADE_DISPLAY_TYPE_DEFAULT,
                        $user->id
                    );
                } else if (strpos($dateitem, 'gradeitem:') === 0) {
                    $gradeitemid = substr($dateitem, 10);
                    $grade = \mod_customcert\element_helper::get_grade_item_info(
                        $gradeitemid,
                        $dateitem,
                        $user->id
                    );
                } else {
                    $grade = \mod_customcert\element_helper::get_mod_grade_info(
                        $dateitem,
                        GRADE_DISPLAY_TYPE_DEFAULT,
                        $user->id
                    );
                }

                $dategraded = $grade->get_dategraded();
                if ($grade && !empty($dategraded)) {
                    $date = $dategraded;
                }
            }
        }

        // Ensure that a date has been set.
        if (!empty($date)) {
            \mod_customcert\element_helper::render_content($pdf, $this, $this->get_expirationdate_format_string($this->get_expirationdate_future_int($date, $interval, $intervalunit), $dateformat));
        }
    }

    /**
     * Render the element in html.
     *
     * This function is used to render the element when we are using the
     * drag and drop interface to position it.
     *
     * @return string the html
     */
    public function render_html() {
        // If there is no element data, we have nothing to display.
        $data = $this->get_data();
        if (empty($data)) {
            return '';
        }

        // Decode the information stored in the database.
        $dateinfo = json_decode($data);
        $dateformat = $dateinfo->dateformat;
        $interval = $dateinfo->interval;
        $intervalunit = $dateinfo->intervalunit;

        return \mod_customcert\element_helper::render_html_content($this, $this->get_expirationdate_format_string($this->get_expirationdate_future_int(time(), $interval, $intervalunit), $dateformat));
    }

    /**
     * Sets the data on the form when editing an element.
     *
     * @param \mod_customcert\edit_element_form $mform the edit_form instance
     */
    public function definition_after_data($mform) {
        // Set the item and format for this element.
        $data = $this->get_data();
        if (!empty($data)) {
            $dateinfo = json_decode($data);

            $element = $mform->getElement('dateitem');
            $element->setValue($dateinfo->dateitem);

            $element = $mform->getElement('dateformat');
            $element->setValue($dateinfo->dateformat);

            $element = $mform->getElement('interval');
            $element->setValue($dateinfo->interval);

            $element = $mform->getElement('intervalunit');
            $element->setValue($dateinfo->intervalunit);
        }

        parent::definition_after_data($mform);
    }

    /**
     * This function is responsible for handling the restoration process of the element.
     *
     * We will want to update the course module the date element is pointing to as it will
     * have changed in the course restore.
     *
     * @param \restore_customcert_activity_task $restore
     */
    public function after_restore($restore) {
        global $DB;

        $dateinfo = json_decode($this->get_data());
        if ($newitem = \restore_dbops::get_backup_ids_record($restore->get_restoreid(), 'course_module', $dateinfo->dateitem)) {
            $dateinfo->dateitem = $newitem->newitemid;
            $DB->set_field('customcert_elements', 'data', $this->save_unique_data($dateinfo), array('id' => $this->get_id()));
        }
    }

    /**
     * Helper function to return all the date formats.
     *
     * @return array the list of date formats
     */
    public static function get_expirationdate_formats() {
        $date = time();

        $suffix = self::get_expirationordinal_number_suffix(userdate($date, '%d'));

        $dateformats = array(
            1 => userdate($date, '%B %d, %Y'),
            2 => userdate($date, '%B %d' . $suffix . ', %Y'),
            'strftimedate' => userdate($date, get_string('strftimedate', 'langconfig')),
            'strftimedatefullshort' => userdate($date, get_string('strftimedatefullshort', 'langconfig')),
            'strftimedateshort' => userdate($date, get_string('strftimedateshort', 'langconfig')),
            'strftimedatetime' => userdate($date, get_string('strftimedatetime', 'langconfig')),
            'strftimedatetimeshort' => userdate($date, get_string('strftimedatetimeshort', 'langconfig')),
            'strftimedaydate' => userdate($date, get_string('strftimedaydate', 'langconfig')),
            'strftimedaydatetime' => userdate($date, get_string('strftimedaydatetime', 'langconfig')),
            'strftimedayshort' => userdate($date, get_string('strftimedayshort', 'langconfig')),
            'strftimedaytime' => userdate($date, get_string('strftimedaytime', 'langconfig')),
            'strftimemonthyear' => userdate($date, get_string('strftimemonthyear', 'langconfig')),
            'strftimerecent' => userdate($date, get_string('strftimerecent', 'langconfig')),
            'strftimerecentfull' => userdate($date, get_string('strftimerecentfull', 'langconfig')),
            'strftimetime' => userdate($date, get_string('strftimetime', 'langconfig'))
        );

        return $dateformats;
    }

    /**
     * Returns the date in a readable format.
     *
     * @param int $date
     * @param string $dateformat
     * @return string
     */
    protected function get_expirationdate_format_string($date, $dateformat) {
        // Keeping for backwards compatibility.
        if (is_number($dateformat)) {
            switch ($dateformat) {
                case 1:
                    $certificatedate = userdate($date, '%B %d, %Y');
                    break;
                case 2:
                    $suffix = self::get_expirationordinal_number_suffix(userdate($date, '%d'));
                    $certificatedate = userdate($date, '%B %d' . $suffix . ', %Y');
                    break;
                case 3:
                    $certificatedate = userdate($date, '%d %B %Y');
                    break;
                case 4:
                    $certificatedate = userdate($date, '%B %Y');
                    break;
                default:
                    $certificatedate = userdate($date, get_string('strftimedate', 'langconfig'));
            }
        }

        // Ok, so we must have been passed the actual format in the lang file.
        if (!isset($certificatedate)) {
            $certificatedate = userdate($date, get_string($dateformat, 'langconfig'));
        }

        return $certificatedate;
    }

    /**
     * Returns the future date.
     *
     * @param int $date
     * @param int $interval
     * @param int $intervalunit
     * @return int
     */
    protected function get_expirationdate_future_int($date, $interval, $intervalunit) {
        if (is_number($intervalunit)) {
            $date = date_create('@' . $date);
            switch ($intervalunit) {
                case CUSTOMCERT_EXPIRATIONDATE_YEAR:
                    date_add($date,date_interval_create_from_date_string($interval . " years"));
                    break;
                case CUSTOMCERT_EXPIRATIONDATE_MONTH:
                    date_add($date,date_interval_create_from_date_string($interval . " months"));
                    break;
                case CUSTOMCERT_EXPIRATIONDATE_DAY:
                    date_add($date,date_interval_create_from_date_string($interval . " days"));
                    break;
                case CUSTOMCERT_EXPIRATIONDATE_HOUR:
                    date_add($date,date_interval_create_from_date_string($interval . " hours"));
                    break;
                case CUSTOMCERT_EXPIRATIONDATE_MINUTE:
                    date_add($date,date_interval_create_from_date_string($interval . " minutes"));
                    break;
                case CUSTOMCERT_EXPIRATIONDATE_SECOND:
                    date_add($date,date_interval_create_from_date_string($interval . " seconds"));
            }
        }

        return date_timestamp_get($date);
    }

    /**
     * Helper function to return the suffix of the day of
     * the month, eg 'st' if it is the 1st of the month.
     *
     * @param int $day the day of the month
     * @return string the suffix.
     */
    protected static function get_expirationordinal_number_suffix($day) {
        if (!in_array(($day % 100), array(11, 12, 13))) {
            switch ($day % 10) {
                // Handle 1st, 2nd, 3rd.
                case 1:
                    return get_string('numbersuffix_st_as_in_first', 'customcertelement_expirationdate');
                case 2:
                    return get_string('numbersuffix_nd_as_in_second', 'customcertelement_expirationdate');
                case 3:
                    return get_string('numbersuffix_rd_as_in_third', 'customcertelement_expirationdate');
            }
        }
        return 'th';
    }
}
