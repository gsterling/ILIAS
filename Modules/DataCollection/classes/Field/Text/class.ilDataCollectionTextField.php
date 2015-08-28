<?php

/**
 * Class ilDataCollectionTextField
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilDataCollectionTextField extends ilDataCollectionRecordField
{

    /**
     * @param $form ilPropertyFormGUI
     */
    public function fillFormInput(&$form)
    {
        $value = $this->getValue();
        if ($this->hasProperty([ilDataCollectionField::PROPERTYID_TEXTAREA])) {
            $breaks = array( "<br />" );
            $input = str_ireplace($breaks, "", $value);
        } elseif ($this->hasProperty([ilDataCollectionField::PROPERTYID_URL]) && $json = json_decode($value)) {
            $input = $json->link;
            $input_title = $json->title;
            $form->getItemByPostVar('field_' . $this->field->getId() . '_title')->setValue($input_title);
        } else {
            $input = $value;
        }
        $form->getItemByPostVar('field_' . $this->field->getId())->setValue($input);
    }

    /**
     * @param $form ilPropertyFormGUI
     */
    public function setValueFromForm(&$form) {
        if ($this->hasProperty([ilDataCollectionField::PROPERTYID_URL])) {
            $value = json_encode(array(
                "link" => $form->getInput("field_" . $this->field->getId()),
                "title" => $form->getInput("field_" . $this->field->getId() . '_title')));
        } else {
            $value = $form->getInput("field_" . $this->field->getId());
        }
        $this->setValue($value);
    }

    /**
     * @param $prop_id
     * @return mixed
     */
    protected function hasProperty($prop_id) {
        $properties = $this->getField()->getProperties();
        return $properties[$prop_id];
    }
}