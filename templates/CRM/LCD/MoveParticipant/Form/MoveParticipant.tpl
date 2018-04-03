<div id="moveParticipant" class="crm-block crm-form-block crm-participant-move-form-block">
  <div class="messages status">
    <div class="icon inform-icon"></div> {ts 1=$currentContactName}This participant is currently assigned to %1.{/ts}
  </div>

  {foreach from=$elementNames item=elementName}
    <div class="crm-section">
      <div class="label">{$form.$elementName.label}</div>
      <div class="content">{$form.$elementName.html}</div>
      <div class="clear"></div>
    </div>
  {/foreach}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>
