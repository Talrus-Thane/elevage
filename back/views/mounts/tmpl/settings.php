<div id="acym__list__settings" class="acym__content">
	<form id="acym_form" action="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl')); ?>" method="post" name="acyForm" data-abide novalidate>
		<div class="cell grid-x text-right grid-margin-x margin-left-0 margin-right-0 margin-bottom-0 margin-y">
            <?php include acym_getView('mounts', 'settings_actions'); ?>
		</div>
		<div class="grid-x margin-bottom-1 grid-margin-x">
			<div class="cell grid-x margin-bottom-1 acym__content margin-y">
                <?php include acym_getView('mounts', 'settings_information'); ?>
			</div>
		</div>

		<input type="hidden" name="id" value="<?php echo acym_escape($data['mountInformation']->id); ?>">
        <?php acym_formOptions(true, 'settings'); ?>
	</form>
</div>
