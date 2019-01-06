<?php

namespace Sunnysideup\SimplestSpam\Form;

use SpamProtectorField;


use SilverStripe\View\Requirements;
use SilverStripe\Control\Controller;


/**
 * Provides an {@link FormField} which allows form to validate for non-bot submissions
 * by giving them a honey pot
 *
 * @module SimplestSpam
 */
class InvisibleSpamField extends SpamProtectorField
{


    /**
     * list of fields that can be placed as honey pots
     * you can add your own one here...
     * @param Array $a
     */
    private static $definitions = array(
        "Email" => array("Class" => "mustenterbecausitisrequired", "Name" => "must_not_enter_email_field", "Label" => "must not enter email here"),
        "URL" => array("Class" => "urlthatisrequired", "Name" => "must_not_enter_url_field", "Label" => "extra url"),
        "BLANK" => array("Class" => "leavethisblank", "Name" => "BLANK", "Label" => "Please leave this field blank to stop spam")
    );

    /**
     * minimum number of seconds for a user to complete a form
     * set to zero to ignore
     * @param Integer $i
     **/
    private static $min_seconds_completing_form = 10;

    /**
     * maximum number of seconds for a user to complete a form
     * set to zero to ignore
     * @param Integer $i
     **/
    private static $max_seconds_completing_form = 600;


    /**
     * also consider: height: 0px; overflow: hidden; etc...
     **/
    private static $css_rules = array(
        "text-indent" => "-2000px"
    );


    /**
     * returns the label being used
     * @return String
     */
    protected function labelUsed()
    {
        return self::$definitions[$this->usedField()]["Label"];
    }

    /**
     * returns the field name being used
     * @return String
     */
    protected function fieldNameUsed()
    {
        return self::$definitions[$this->usedField()]["Name"];
    }

    /**
     * class name used
     * @return String
     */
    protected function classNameUsed()
    {
        return self::$definitions[$this->usedField()]["Class"];
    }

    /**
     *
     * @return String
     */
    public function FieldHolder($options = array())
    {
        if (is_array(self::$css_rules) && count(self::$css_rules)) {
            $css = '';
            foreach (self::$css_rules as $key => $value) {
                $css .= '.css("'.$key.'", "'.$value.'")';
            }
            Requirements::customScript('jQuery(".'.$this->classNameUsed().'")'.$css.';', $this->classNameUsed());
        }
        $Title = $this->labelUsed();
        $Message = $this->XML_val('Message');
        $MessageType = $this->XML_val('MessageType');
        $Type = $this->XML_val('Type');
        $extraClass = $this->XML_val('extraClass');
        $Name = $this->fieldNameUsed();
        $Class = $this->classNameUsed();
        $Field = $this->XML_val('Field');
        $messageBlock = (!empty($Message)) ? "<span class=\"message $MessageType\">$Message</span>" : "";
        $name = $this->labelUsed();
        $time = time();
        return <<<HTML
<div id="$Name" class="$Class">
	<label>$Title</label>
	<div class="middleColumn">
		{$Field}
	</div>
</div>
<input type="hidden" value="$time" name="remembermeasstarttime" />
HTML;
    }

    /**
     *
     * @return String
     */
    public function Field($options = array())
    {
        $this->initialise();
        $html = '<input type="text" name="'.$this->fieldNameUsed().'" class="text" />';
        return $html;
    }

    /**
     *
     *
     */
    public function validate($validator)
    {
        // don't bother querying the SimplestSpam-service if fields were empty
        if (!isset($_REQUEST[$this->fieldNameUsed()]) || $_REQUEST[$this->fieldNameUsed()]) {
            $validator->validationError(
                $this->name,
                $this->createValidationMessage(),
                "validation",
                false
            );
            return false;
        }
        if (!isset($_REQUEST["remembermeasstarttime"]) || !intval($_REQUEST["remembermeasstarttime"])) {
            $validator->validationError(
                $this->name,
                _t("InvisibleSpamField.NOTIME", "Could not process submission. It seemed to be a spam attack, if you are not a spammer then please reload form and try again."),
                "validation",
                false
            );
        } else {
            $time = time();
            $oldTime = $_REQUEST["remembermeasstarttime"];
            $difference = $time - $oldTime;
            $min = $this->Config()->get("min_seconds_completing_form");
            $max = $this->Config()->get("max_seconds_completing_form");
            if ($min && $min > $difference) {
                $validator->validationError(
                    $this->name,
                    _t("InvisibleSpamField.TOOFAST", "Could not process submission. It seems that your submission took shorter than expected, which indicates a possible spam attack. Please reload the page and try again."),
                    "validation",
                    false
                );
            }
            if ($max && $max < $difference) {
                $validator->validationError(
                    $this->name,
                    _t("InvisibleSpamField.TOOSLOW", "Could not process submission. It seems that your submission took longer than expected, which indicates a possible spam attack. Please reload the page and try again."),
                    "validation",
                    false
                );
            }
        }
        return true;
    }

    protected function createValidationMessage()
    {
        return
            _t("InvisibleSpamField.SPAMMESSAGE_NOTSUBMITTED", "Form could not be submitted. ").
            _t("InvisibleSpamField.SPAMMESSAGE_DONOTCOMPLETE1", " Please do NOT complete the [<i>").
            $this->labelUsed().
            _t("InvisibleSpamField.SPAMMESSAGE_DONOTCOMPLETE2", "</i>] field. This field is added to prevent spam (Spammers will complete it - you should not complete it).");
    }


    protected function initialise()
    {
        return true;
    }

    /**
     * returns the key of the field to be used...
     * @return String
     */
    protected function usedField()
    {

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: upgrade to SS4
  * OLD: Session:: (case sensitive)
  * NEW: Controller::curr()->getRequest()->getSession()-> (COMPLEX)
  * EXP: If THIS is a controller than you can write: $this->getRequest(). You can also try to access the HTTPRequest directly. 
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        $key = Controller::curr()->getRequest()->getSession()->get("InvisibleSpamFieldKey");
        if (!$key) {
            $key = array_rand($this->Config()->get("definitions"));

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: upgrade to SS4
  * OLD: Session:: (case sensitive)
  * NEW: Controller::curr()->getRequest()->getSession()-> (COMPLEX)
  * EXP: If THIS is a controller than you can write: $this->getRequest(). You can also try to access the HTTPRequest directly. 
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
            Controller::curr()->getRequest()->getSession()->set("InvisibleSpamFieldKey", $key);
        }
        return $key;
    }
}
