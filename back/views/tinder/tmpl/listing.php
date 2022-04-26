<form id="acym_form" action="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl')); ?>" method="post" name="acyForm">
    <?php acym_formOptions(); ?>
	<input type="hidden" name="father_id">
	<input type="hidden" name="mother_id">

	<div class="acym__content grid-x cell">
		<h2 class="acym__title"><?php echo acym_translation('ACYM_NEXT_REPRODUCTIONS'); ?></h2>

		<div class="grid-x cell grid-margin-x margin-y">
            <?php if (empty($data['possibleMates'])) { ?>
				<h3 class="margin-auto"><?php echo acym_translation('ACYM_NO_MATE_FOUND'); ?></h3>
            <?php } else { ?>
                <?php foreach ($data['possibleMates'] as $oneMate) {
                    $color = $data['purity_levels'][$oneMate['mount']->purity_level]['color'];
                    ?>
					<div class="cell large-3 acym__content text-center grid-x grid-margin-x margin-y" style="border: 1px solid <?php echo $color; ?>">
						<div class="cell">
							<img alt="" src="<?php echo ACYM_IMAGES.'colors/'.$oneMate['color']->species_id.'/'.$oneMate['color']->id.'.png'; ?>" />
						</div>
						<div class="cell"><?php echo $oneMate['color']->name; ?></div>
						<div class="cell grid-x">
							<a class="cell small-6" href="<?php echo acym_completeLink('mounts&task=settings&id='.intval($oneMate['mount']->id)); ?>">
                                <?php echo $oneMate['mount']->name; ?>
							</a>
							<a class="cell small-6" href="<?php echo acym_completeLink('mounts&task=settings&id='.intval($oneMate['suitor']->id)); ?>">
                                <?php echo $oneMate['suitor']->name; ?>
							</a>
							<span class="cell small-6">
								<?php
                                $reproductions = $oneMate['mount']->reproductions_counter.'/'.$oneMate['mount']->max_reproductions;
                                if ($oneMate['mount']->reproductions_counter == $oneMate['mount']->max_reproductions - 1) {
                                    echo '<span style="color: red;">'.$reproductions.'</span>';
                                } else {
                                    echo $reproductions;
                                }
                                ?>
							</span>
							<span class="cell small-6">
								<?php
                                $reproductions = $oneMate['suitor']->reproductions_counter.'/'.$oneMate['suitor']->max_reproductions;
                                if ($oneMate['suitor']->reproductions_counter == $oneMate['suitor']->max_reproductions - 1) {
                                    echo '<span style="color: red;">'.$reproductions.'</span>';
                                } else {
                                    echo $reproductions;
                                }
                                ?>
							</span>
						</div>
						<div class="cell margin-bottom-0">
                            <?php echo acym_translation('ACYM_COMMON_ANCESTORS'); ?>: <?php echo $oneMate['commonAncestors']; ?>
						</div>
						<div class="cell">
                            <?php echo acym_translation('ACYM_HAS_MATED'); ?>: <?php
                            if ($oneMate['hasMated']) {
                                echo '<span style="color: red;">'.acym_translation('ACYM_YES').'</span>';
                            } else {
                                echo '<span style="color: green;">'.acym_translation('ACYM_NO').'</span>';
                            }
                            ?>
						</div>
						<div class="cell">
							<button
									class="button acy_button_submit"
									data-task="mate"
									data-confirmation-message="ACYM_ARE_YOU_SURE"
									data-father="<?php echo $oneMate['suitor']->id; ?>"
									data-mother="<?php echo $oneMate['mount']->id; ?>">
                                <?php echo acym_translation('ACYM_CONFIRM'); ?>
							</button>
						</div>
					</div>
                <?php } ?>
            <?php } ?>
		</div>
	</div>
</form>
