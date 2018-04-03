<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2017                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2017
 */

/**
 * This class provides the functionality to delete a group of participants.
 *
 * This class provides functionality for the actual deletion.
 */
class CRM_LCD_MoveParticipant_Form_Task extends CRM_Event_Form_Task {

  /**
   * Build all the data structures needed to build the form.
   */
  public function preProcess() {
    //check for permission
    if (!CRM_Core_Permission::checkActionPermission('CiviEvent', CRM_Core_Action::UPDATE)) {
      CRM_Core_Error::fatal(ts('You do not have permission to access this page.'));
    }
    parent::preProcess();
  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    $this->addEntityRef('change_contact_id', ts('Select Contact'));
    $count = count($this->_participantIds);
    $this->assign('count', $count);

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

  /**
   * Process the form after the input has been submitted and validated.
   */
  public function postProcess() {
    $moved = $failed = 0;
    $values = $this->exportValues();
    //Civi::log()->debug('postProcess', array('values' => $values));

    foreach ($this->_participantIds as $participantId) {
      try {
        $currentContactId = civicrm_api3('participant', 'getvalue', array(
          'id' => $participantId,
          'return' => 'contact_id',
        ));
      }
      catch (CiviCRM_API3_Exception $e) {}

      $params = array(
        'change_contact_id' => $values['change_contact_id'],
        'contact_id' => $values['change_contact_id'],
        'participant_id' => $participantId,
        'current_contact_id' => $currentContactId,
      );

      if (CRM_LCD_MoveParticipant_BAO_MoveParticipant::moveParticipant($params)) {
        $moved++;
      }
      else {
        $failed++;
      }
    }

    if ($moved) {
      CRM_Core_Session::setStatus(ts('%count participant moved.', array(
        'plural' => '%count participants moved.',
        'count' => $moved
      )), ts('Moved'), 'success');
    }

    if ($failed) {
      CRM_Core_Session::setStatus(ts('1 could not be moved.', array(
        'plural' => '%count could not be moved.',
        'count' => $failed
      )), ts('Error'), 'error');
    }

    parent::postProcess();

    $session = CRM_Core_Session::singleton();
    CRM_Utils_System::redirect($session->readUserContext());
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
