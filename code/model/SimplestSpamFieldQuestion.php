<?php

class SimplestSpamFieldQuestion extends DataObject {
	//database
	public static $db = array(
		"Question" => "Varchar(100)",
		"Answer" => "Varchar(50)"
	);
	//formatting
	public static $searchable_fields = array("Question" => "PartialMatchFilter");

	public static $summary_fields = array("Question" => "Question", "Answer" => "Answer");

	public static $singular_name = "Spam filter question";

	public static $plural_name = "Spam filter questions";

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		$simplestSpamFieldQuestion = DataObject::get_one("SimplestSpamFieldQuestion");
		if(!$simplestSpamFieldQuestion) {
			$simplestSpamFieldQuestion = new SimplestSpamFieldQuestion();
			$simplestSpamFieldQuestion->Question = 'What is the original name for New Zealand?';
			$simplestSpamFieldQuestion->Answer = 'Aotearoa';
			$simplestSpamFieldQuestion->write();
			DB::alteration_message($simplestSpamFieldQuestion->ClassName."Created default entry for SimplestSpamFieldQuestion", 'created');
		}
	}

}
