<?php

/**
 *@author nicolaas [at] sunnysideup.co.nz
 *
 *
 **/

class SimplestSpamSiteConfigExtension extends DataObjectDecorator {

	function extraStatics(){
		return array(
			"db" => array(
				"SimplestSpamExplanation" => "Varchar(255)",
				"SimplestSpamWrongAnswerFieldMessage" => "Varchar(255)",
				"SimplestSpamWrongAnswerFormMessage" => "Varchar(255)"
			),
			"defaults" => array(
				"SimplestSpamExplanation" => "this question is here to prevent spam",
				"SimplestSpamWrongAnswerFieldMessage" => "please check anti-spam field to proceed",
				"SimplestSpamWrongAnswerFormMessage" => "Submission was NOT successful. Please check anti-spam field..."
			)
		);
	}


	function updateCMSFields(FieldSet &$fields) {
		$fields->addFieldToTab("Root.Spam",$this->createTableListField());
		$fields->addFieldToTab("Root.Spam", new TextField("SimplestSpamExplanation", "Explanation of spam question"));
		$fields->addFieldToTab("Root.Spam", new TextField("SimplestSpamWrongAnswerFieldMessage", "Error message next to field for wrong spam question answer"));
		$fields->addFieldToTab("Root.Spam", new TextField("SimplestSpamWrongAnswerFormMessage", "Error message at top of form for wrong spam question answer"));
		return $fields;
	}

	protected function createTableListField() {
		$table = new TableField(
			$name = "SimplestSpamFieldQuestion",
			$sourceClass = "SimplestSpamFieldQuestion",
			$fieldList = array("Question" => "Question", "Answer" => "Answer"),
			$fieldTypes = array("Question" => "TextField", "Answer" => "TextField"),
			$sourceFilter = null,
			$sourceSort = null,
			$sourceJoin = null
		);
		$table->setPermissions(array("show", "add", "delete", "edit"));
		return $table;
	}

}
