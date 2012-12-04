<?php

/**
 * Protecter class to handle spam protection interface
 *
 * @package recaptcha
 */

class SimplestSpamProtector implements SpamProtector {

	protected $field;

	/**
	 * Return the Field that we will use in this protector
	 *
	 * @return string
	 */
	function getFieldName() {
		return 'SimplestSpamField';
	}

	/**
	 * @return bool
	 */
	function updateForm($form, $before=null, $fieldsToSpamServiceMapping=null) {

		$this->field = $this->getFormField("SimplestSpamField", "Please answer this question to prove you are a real human", null, $form);

		if ($before && $form->Fields()->fieldByName($before)) {
			$form->Fields()->insertBefore($this->field, $before);
		}
		else {
			$form->Fields()->push($this->field);
		}

		return $form->Fields();
	}

	function setFieldMapping($fieldToPostTitle, $fieldsToPostBody=null, $fieldToAuthorName=null, $fieldToAuthorUrl=null, $fieldToAuthorEmail=null, $fieldToAuthorOpenId=null) {

	}


	public function getFormField($name = null, $title = null, $value = null, $form = null, $rightTitle = null) {
		return new SimplestSpamField($name, $title, $value, $form, $rightTitle);
	}

	public function sendFeedback($object = null, $feedback = "") {
		return true;
	}

}
