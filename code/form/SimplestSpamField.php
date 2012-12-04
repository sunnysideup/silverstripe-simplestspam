<?php
/**
 * Provides an {@link FormField} which allows form to validate for non-bot submissions
 * by giving them a challenge answer a question
 *
 * @module SimplestSpam
 */
class SimplestSpamField extends SpamProtectorField {

	protected static $questions_and_answers = array();

	protected static $has_been_initialised = false;

	protected $error = '';

	protected function initialise() {
		if(!self::$has_been_initialised) {
			if(!count(self::$questions_and_answers)) {
				self::$questions_and_answers = DataObject::get("SimplestSpamFieldQuestion");
			}
			$questionCount = 0;
			if(self::$questions_and_answers) {
				$questionCount = self::$questions_and_answers->count();
			}
			if(!isset($_REQUEST['SimplestSpam_challenge_field']) && $questionCount) {
				$randomNumber = rand(0, $questionCount - 1);
				Session::set("SimplestSpamQuestion", $randomNumber + 1); // adding one to make it easier to work out if anything has been entered, i.e. 0 could be nothing or first question
			}
		}
		self::$has_been_initialised = true;
	}

	public function Field() {
		$this->initialise();
		$html = '<input type="text" name="SimplestSpam_challenge_field" class="text requiredField" />';
		return $html;
	}

	function FieldHolder() {
		$this->initialise();
		$obj = $this->getQuestionAnswerObject();
		if(!$obj) {
			return "";
		}
		$question = $obj->Question;
		$siteConfig = DataObject::get_one("SiteConfig");
		$explanation = $siteConfig->SimplestSpamExplanation;
		if($explanation) {
			$explanation = $explanation;
		}
		$Title = $this->XML_val('Title');
		$Message = $this->XML_val('Message');
		$MessageType = $this->XML_val('MessageType');
		$Type = $this->XML_val('Type');
		$extraClass = $this->XML_val('extraClass');
		$Name = $this->XML_val('Name');
		$Field = $this->XML_val('Field');
		$messageBlock = (!empty($Message)) ? "<span class=\"message $MessageType\">$Message</span>" : "";
		return <<<HTML
<div id="$Name" class="field $Type $extraClass">
	<label class="left spamquestion">{$question} </label>
	<div class="middleColumn">
		{$Field}
		<label class="required">{$explanation} {$messageBlock}</label>
	</div>
</div>
HTML;
	}

	public function validate($validator) {
		$siteConfig = DataObject::get_one("SiteConfig");
		// don't bother querying the SimplestSpam-service if fields were empty
		if(
			!isset($_REQUEST['SimplestSpam_challenge_field'])
			|| empty($_REQUEST['SimplestSpam_challenge_field'])
		) {
			$validator->validationError(
				$this->name,
				$siteConfig->SimplestSpamWrongAnswerFieldMessage,
				"validation",
				false
			);
			Session::set("FormField.{$this->form->FormName()}.{$this->Name()}", $siteConfig->SimplestSpamWrongAnswerFieldMessage);
			$this->form->sessionMessage($siteConfig->SimplestSpamWrongAnswerFormMessage, "bad");
			return false;
		}
		$response = $_REQUEST['SimplestSpam_challenge_field'];
		$obj = $this->getQuestionAnswerObject();
		if(!$obj || !isset($obj->Answer)) {
			user_error("SimplestSpamField::validate(): could not find answer - sorry, please try again'", E_USER_ERROR);
			return false;
		}
		$answer = $obj->Answer;
		if($this->cleanupAnswer($answer) != $this->cleanupAnswer($response)) {
			$validator->validationError(
				$this->name,
				$siteConfig->SimplestSpamWrongAnswerFormMessage,
				"validation",
				false
			);
			Session::set("FormField.{$this->form->FormName()}.{$this->Name()}", $siteConfig->SimplestSpamWrongAnswerFieldMessage);
			$this->form->sessionMessage($siteConfig->SimplestSpamWrongAnswerFormMessage, "bad");
			return false;
		}
		return true;
	}

	protected function cleanupAnswer($v) {
		return trim(strtolower($v));
	}

	protected function getQuestionAnswerObject() {
		$this->initialise();
		$number = Session::get("SimplestSpamQuestion");
		if($number > 0) {
			$number = $number - 1;
			if($dos = DataObject::get("SimplestSpamFieldQuestion", $where = null, $sort = null, $join = null, $limit = "$number, 1")) {
				return $dos->first();
			}
			else {
				$this->error = _t("SimplestSpamField.QUESTIONNOTFOUND", "Selected question not found.");
			}
		}
		else {
			$this->error = _t("SimplestSpamField.QUESTIONSELECTIONNOTAVAILABLE", "No question selection made.");
		}
		return false;
	}


}
