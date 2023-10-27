<?php

require_once 'moveparticipant.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function moveparticipant_civicrm_config(&$config) {
  _moveparticipant_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function moveparticipant_civicrm_install() {
  _moveparticipant_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function moveparticipant_civicrm_enable() {
  _moveparticipant_civix_civicrm_enable();
}

function moveparticipant_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  /*Civi::log()->debug('moveparticipant_civicrm_links', array(
    '$op' => $op,
    '$objectName' => $objectName,
    '$objectId' => $objectId,
    '$links' => $links,
    '$mask' => $mask,
    '$values' => $values,
  ));*/

  if ($op == 'participant.selector.row' && $objectName == 'Participant') {
    $links[] = [
      'name' => 'Move',
      'url' => 'civicrm/moveparticipant',
      'qs' => 'id=%%id%%',
      'title' => 'Move',
      //'bit' => NULL,
    ];
  }
}

function moveparticipant_civicrm_searchTasks($objectType, &$tasks) {
  /*Civi::log()->debug('moveparticipant_civicrm_searchTasks', array(
    '$objectType' => $objectType,
    '$tasks' => $tasks,
  ));*/

  if ($objectType == 'event') {
    $tasks[] = [
      'title' => 'Move participants',
      'class' => 'CRM_LCD_MoveParticipant_Form_Task',
      'result' => TRUE,
    ];
  }
}
