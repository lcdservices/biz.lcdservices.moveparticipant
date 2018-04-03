<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_LCD_MoveParticipant_Form_MoveParticipant extends CRM_Core_Form {

  /**
   * check permissions
   */
  public function preProcess() {
    //check for edit permission
    if (!CRM_Core_Permission::checkActionPermission('CiviEvent', CRM_Core_Action::UPDATE)) {
      CRM_Core_Error::fatal(ts('You do not have permission to access this page.'));
    }
    parent::preProcess();
  }

  public function buildQuickForm() {
    $this->_participantId = CRM_Utils_Request::retrieve('id', 'Positive', $this);

    $this->_contactId = civicrm_api3('participant', 'getvalue', array(
      'id' => $this->_participantId,
      'return' => 'contact_id',
    ));

    //get current contact name.
    $this->assign('currentContactName', CRM_Contact_BAO_Contact::displayName($this->_contactId));

    $this->addEntityRef('change_contact_id', ts('Select Contact'));
    $this->add('hidden', 'contact_id', '', array('id' => 'contact_id'));
    $this->add('hidden', 'participant_id', $this->_participantId, array('id' => 'participant_id'));
    $this->add('hidden', 'current_contact_id', $this->_contactId, array('id' => 'current_contact_id'));
    $this->assign('contactId', $this->_contactId);

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    //Civi::log()->debug('postProcess', array('values' => $values));

    $params = array(
      'change_contact_id' => $values['change_contact_id'],
      'contact_id' => $values['change_contact_id'],
      'participant_id' => $values['participant_id'],
      'current_contact_id' => $values['current_contact_id'],
    );

    $result = CRM_LCD_MoveParticipant_BAO_MoveParticipant::moveParticipant($params);

    if ($result) {
      CRM_Core_Session::setStatus(ts('Participant moved successfully.'), ts('Moved'), 'success');
    }
    else {
      CRM_Core_Session::setStatus(ts('Unable to move participant.'), ts('Error'), 'error');
    }

    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
