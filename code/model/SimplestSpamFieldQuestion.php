<?php

class SimplestSpamFieldQuestion extends DataObject
{
    //database
    private static $db = array(
        "Question" => "Varchar(100)",
        "Answer" => "Varchar(50)"
    );
    //formatting
    private static $searchable_fields = array("Question" => "PartialMatchFilter");

    private static $summary_fields = array("Question" => "Question", "Answer" => "Answer");

    private static $singular_name = "Spam filter question";

    private static $plural_name = "Spam filter questions";

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        $simplestSpamFieldQuestion = SimplestSpamFieldQuestion::get()->First();
        if (!$simplestSpamFieldQuestion) {
            $simplestSpamFieldQuestion = new SimplestSpamFieldQuestion();
            $simplestSpamFieldQuestion->Question = 'What is the original name for New Zealand?';
            $simplestSpamFieldQuestion->Answer = 'Aotearoa';
            $simplestSpamFieldQuestion->write();
            DB::alteration_message($simplestSpamFieldQuestion->ClassName."Created default entry for SimplestSpamFieldQuestion", 'created');
        }
    }
}
