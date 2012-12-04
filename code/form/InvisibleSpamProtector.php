<?php

/**
 * Protecter class to handle spam protection interface
 *
 * @package simplest spam field
 * @author nicolaas[at] sunnysideup.co.nz
 */

class InvisibleSpamProtector implements SpamProtector {

	protected $field;

	/**
	 * Return the Field that we will use in this protector
	 *
	 * @return string
	 */
	function getFieldName() {
		return 'InvisibleSpamField';
	}

	/**
	 * @return bool
	 */
	function updateForm($form, $before=null, $fieldsToSpamServiceMapping=null) {
		return $form->Fields();
	}

	/*
	function setFieldMapping($fieldToPostTitle, $fieldsToPostBody=null, $fieldToAuthorName=null, $fieldToAuthorUrl=null, $fieldToAuthorEmail=null, $fieldToAuthorOpenId=null) {

	}
	*/

	public function getFormField($name = null, $title = null, $value = null, $form = null, $rightTitle = null) {
 		return new InvisibleSpamField($name, $title, $value, $form, $rightTitle);
	}

	public function sendFeedback($object = null, $feedback = "") {
		return true;
	}

}
