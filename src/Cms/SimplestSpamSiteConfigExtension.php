<?php

namespace Sunnysideup\SimplestSpam\Cms;

use TableField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use Sunnysideup\SimplestSpam\Model\SimplestSpamFieldQuestion;
use SilverStripe\ORM\DataExtension;

/**
 *@author nicolaas [at] sunnysideup.co.nz
 *
 *
 **/


/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: upgrade to SS4
  * OLD:  extends DataExtension (ignore case)
  * NEW:  extends DataExtension (COMPLEX)
  * EXP: Check for use of $this->anyVar and replace with $this->anyVar[$this->owner->ID] or consider turning the class into a trait
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
class SimplestSpamSiteConfigExtension extends DataExtension
{
    private static $db = array(
        "SimplestSpamExplanation" => "Varchar(255)",
        "SimplestSpamWrongAnswerFieldMessage" => "Varchar(255)",
        "SimplestSpamWrongAnswerFormMessage" => "Varchar(255)"
    );

    private static $defaults = array(
        "SimplestSpamExplanation" => "this question is here to prevent spam",
        "SimplestSpamWrongAnswerFieldMessage" => "please check anti-spam field to proceed",
        "SimplestSpamWrongAnswerFormMessage" => "Submission was NOT successful. Please check anti-spam field..."
    );


    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab("Root.Spam", $this->createTableListField());
        $fields->addFieldToTab("Root.Spam", new TextField("SimplestSpamExplanation", "Explanation of spam question"));
        $fields->addFieldToTab("Root.Spam", new TextField("SimplestSpamWrongAnswerFieldMessage", "Error message next to field for wrong spam question answer"));
        $fields->addFieldToTab("Root.Spam", new TextField("SimplestSpamWrongAnswerFormMessage", "Error message at top of form for wrong spam question answer"));
        return $fields;
    }

    protected function createTableListField()
    {
        $table = new TableField(
            $name = SimplestSpamFieldQuestion::class,
            $sourceClass = SimplestSpamFieldQuestion::class,
            $fieldList = array("Question" => "Question", "Answer" => "Answer"),
            $fieldTypes = array("Question" => TextField::class, "Answer" => TextField::class),
            $sourceFilter = null,
            $sourceSort = null,
            $sourceJoin = null
        );
        $table->setPermissions(array("show", "add", "delete", "edit"));
        return $table;
    }
}
