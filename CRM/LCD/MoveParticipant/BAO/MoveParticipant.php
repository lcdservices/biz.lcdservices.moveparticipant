<?php

/**
 * Class CRM_LCD_MoveParticipant_Form_MoveParticipant
 */
class CRM_LCD_MoveParticipant_BAO_MoveParticipant {

  static function moveParticipant($params) {
    try {
      $participant = civicrm_api3('participant', 'create', [
        'id' => $params['participant_id'],
        'contact_id' => $params['contact_id'],
      ]);
    }
    catch (CiviCRM_API3_Exception $e) {}

    // record activity for moving participant
    if (empty($participant['is_error'])) {
      $subject = "Participant #{$params['participant_id']} Moved";
      $details = "Participant #{$params['participant_id']} was moved from contact #{$params['current_contact_id']} to contact #{$params['change_contact_id']}.";

      $activityTypeID = CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'participant_reassignment');

      $activityParams = [
        'source_contact_id' => $params['current_contact_id'],
        'activity_type_id' => $activityTypeID,
        'activity_date_time' => date('YmdHis'),
        'subject' => $subject,
        'details' => $details,
        'status_id' => 2,
      ];

      $session = CRM_Core_Session::singleton();
      $id = $session->get('userID');

      if ($id) {
        $activityParams['source_contact_id'] = $id;
        $activityParams['target_contact_id'][] = $params['current_contact_id'];
        $activityParams['target_contact_id'][] = $params['change_contact_id'];
      }

      try {
        CRM_Activity_BAO_Activity::create($activityParams);
      }
      catch (CiviCRM_API3_Exception $e) {}

      return TRUE;
    }

    return FALSE;
  }
}
