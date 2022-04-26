<form id="acym_form" action="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl')); ?>" method="post" name="acyForm">
    <?php acym_formOptions(); ?>
	<div class="acym__content grid-x cell">
		<div class="grid-x cell grid-margin-x margin-y">
            <?php if (empty($data['mounts'])) { ?>
				<h3 class="margin-auto"><?php echo acym_translation('ACYM_NO_MOUNT_FOUND'); ?></h3>
            <?php } else { ?>
                <?php foreach ($data['mounts'] as $colorId => $color) { ?>
					<div class="cell large-3 acym__content text-center grid-x grid-margin-x margin-y" style="border: 1px solid">
						<div class="cell">
							<img alt="" src="<?php echo ACYM_IMAGES.'colors/2/'.$colorId.'.png'; ?>" />
						</div>
						<div class="cell"><?php echo $data['colors'][$colorId]->name; ?></div>
						<div class="cell grid-x">
                            <?php foreach ([0 => 'male', 1 => 'female'] as $gKey => $gName) { ?>
								<div class="cell large-6 grid-x">
									<div class="cell">
										<img class="gender_image"
											 alt="<?php echo $gName; ?>"
											 src="<?php echo ACYM_IMAGES.$gName.'.png'; ?>" />
									</div>
                                    <?php foreach ($data['purity_levels'] as $pKey => $pName) { ?>
										<div class="cell grid-x">
											<div class="cell large-6">
                                                <?php echo $pName.':'; ?>
											</div>
											<div class="cell large-6">
												<a href="<?php echo $data['mountsLink'].'&mounts_color='.$colorId.'&mounts_gender='.$gName.'&mounts_purity='.$pKey; ?>">
                                                    <?php echo $color[$gKey][$pKey]; ?>
												</a>
											</div>
										</div>
                                    <?php } ?>
								</div>
                            <?php } ?>
						</div>
					</div>
                <?php } ?>
            <?php } ?>
		</div>
	</div>
</form>
