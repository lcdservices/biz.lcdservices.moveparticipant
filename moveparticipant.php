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
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function moveparticipant_civicrm_xmlMenu(&$files) {
  _moveparticipant_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function moveparticipant_civicrm_uninstall() {
  _moveparticipant_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function moveparticipant_civicrm_enable() {
  _moveparticipant_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function moveparticipant_civicrm_disable() {
  _moveparticipant_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function moveparticipant_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _moveparticipant_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function moveparticipant_civicrm_managed(&$entities) {
  _moveparticipant_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function moveparticipant_civicrm_caseTypes(&$caseTypes) {
  _moveparticipant_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function moveparticipant_civicrm_angularModules(&$angularModules) {
_moveparticipant_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function moveparticipant_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _moveparticipant_civix_civicrm_alterSettingsFolders($metaDataFolders);
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
    $links[] = array(
      'name' => 'Move',
      'url' => 'civicrm/moveparticipant',
      'qs' => 'id=%%id%%',
      'title' => 'Move',
      //'bit' => NULL,
    );
  }
}

function moveparticipant_civicrm_searchTasks($objectType, &$tasks) {
  /*Civi::log()->debug('moveparticipant_civicrm_searchTasks', array(
    '$objectType' => $objectType,
    '$tasks' => $tasks,
  ));*/

  if ($objectType == 'event') {
    $tasks[] = array(
      'title' => 'Move participants',
      'class' => 'CRM_LCD_MoveParticipant_Form_Task',
      'result' => TRUE,
    );
  }
}
