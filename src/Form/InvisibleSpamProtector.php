<?php

namespace Sunnysideup\SimplestSpam\Form;

use SilverStripe\SpamProtection\SpamProtector;
use Sunnysideup\SimplestSpam\Form\InvisibleSpamField;

/**
 * Protecter class to handle spam protection interface
 *
 * @package simplest spam field
 * @author nicolaas[at] sunnysideup.co.nz
 */

class InvisibleSpamProtector implements SpamProtector
{
    protected $field;

    /**
     * Return the Field that we will use in this protector
     *
     * @return string
     */
    public function getFieldName()
    {
        return InvisibleSpamField::class;
    }

    /**
     * @return bool
     */
    public function updateForm($form, $before=null, $fieldsToSpamServiceMapping=null)
    {
        return $form->Fields();
    }

    public function setFieldMapping($fieldMapping)
    {
    }

    public function getFormField($name = null, $title = null, $value = null, $form = null, $rightTitle = null)
    {
        return new InvisibleSpamField($name, $title, $value, $form, $rightTitle);
    }

    public function sendFeedback($object = null, $feedback = "")
    {
        return true;
    }
}
