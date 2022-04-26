<?php if (empty($data['mounts'])) { ?>
	<h1 class="cell acym__listing__empty__search__title text-center"><?php echo acym_translation('ACYM_NO_RESULTS_FOUND'); ?></h1>
<?php } else { ?>
	<input type="hidden" name="mother_id">
	<div class="cell grid-x margin-top-1">
		<div class="grid-x acym__listing__actions cell margin-bottom-1">
            <?php
            $actions = [
                'mate' => acym_translation('ACYM_MATE'),
                'retire' => acym_translation('ACYM_RETIRE'),
                'delete' => acym_translation('ACYM_DELETE'),
            ];
            echo acym_listingActions($actions);
            ?>
		</div>
		<div class="cell grid-x">
			<div class="auto cell acym_vcenter">
                <?php
                $options = [
                    '' => ['ACYM_ALL', $data['mountNumberPerStatus']['all']],
                    'dragodinde' => ['ACYM_DRAGODINDE', $data['mountNumberPerStatus']['dragodinde']],
                    'muldo' => ['ACYM_MULDO', $data['mountNumberPerStatus']['muldo']],
                    'volkorne' => ['ACYM_VOLKORNE', $data['mountNumberPerStatus']['volkorne']],
                ];
                echo acym_filterStatus($options, $data['status'], 'mounts_status');
                ?>
			</div>
			<div class="cell acym_listing_sort-by auto">
                <?php echo acym_sortBy(
                    [
                        'id' => strtolower(acym_translation('ACYM_ID')),
                        'name' => acym_translation('ACYM_NAME'),
                        'purity_level,gender' => acym_translation('ACYM_PURITY_LEVEL'),
                    ],
                    'mounts',
                    $data['ordering']
                ); ?>
			</div>
		</div>
	</div>
	<div class="grid-x acym__listing acym__listing__view__list">
		<div class="grid-x cell acym__listing__header">
			<div class="medium-shrink small-1 cell">
				<input id="checkbox_all" type="checkbox" name="checkbox_all">
			</div>
			<div class="grid-x medium-auto small-11 cell acym__listing__header__title__container">
				<div class="acym__listing__header__title small-1 text-center">
                    <?php echo acym_translation('ACYM_COLOR'); ?>
				</div>
				<div class="acym__listing__header__title small-1 text-center">
                    <?php echo acym_translation('ACYM_GENDER'); ?>
				</div>
				<div class="acym__listing__header__title small-1 text-center">
                    <?php echo acym_translation('ACYM_NAME'); ?>
				</div>
				<div class="acym__listing__header__title small-1 text-center">
                    <?php echo acym_translation('ACYM_PURITY_LEVEL'); ?>
				</div>
				<div class="acym__listing__header__title small-1 text-center">
                    <?php echo acym_translation('ACYM_REPRODUCTIONS'); ?>
				</div>
				<div class="medium-6 text-center cell">
				</div>
				<div class="acym__listing__header__title cell hide-for-small-only medium-1 text-center acym__listing__id">
                    <?php echo acym_translation('ACYM_ID'); ?>
				</div>
			</div>
		</div>
        <?php foreach ($data['mounts'] as $mount) { ?>
			<div data-acy-elementid="<?php echo acym_escape($mount->id); ?>" class="grid-x cell acym__listing__row">
				<div class="medium-shrink small-1 cell">
					<input id="checkbox_<?php echo acym_escape($mount->id); ?>" type="checkbox" name="elements_checked[]" value="<?php echo acym_escape($mount->id); ?>">
				</div>
				<div class="grid-x medium-auto small-11 cell acym__listing__title__container">
					<div class="small-1 text-center small-up-1 cell">
						<img src="<?php echo ACYM_IMAGES.'colors/'.$mount->species_id.'/'.$mount->color_id.'.png'; ?>" alt="" />
					</div>
					<div class="small-1 text-center small-up-1 cell">
						<img alt="" src="<?php echo ACYM_IMAGES.(empty($mount->gender) ? 'male.png' : 'female.png'); ?>" style="width: 30px;height: 30px;" />
					</div>
					<div class="small-1 text-center small-up-1 cell">
						<a class="cell auto" href="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl').'&task=settings&id='.intval($mount->id)); ?>">
                            <?php echo acym_escape($mount->name); ?>
						</a>
					</div>
					<div class="small-1 text-center small-up-1 cell">
						<span style="color: <?php echo $data['purity_levels'][$mount->purity_level]['color']; ?>">
							<?php echo $data['purity_levels'][$mount->purity_level]['label']; ?>
						</span>
					</div>
					<div class="small-1 text-center small-up-1 cell">
						<span style="color: <?php echo $mount->reproductions_counter === $mount->max_reproductions ? 'red' : 'green'; ?>">
							<?php echo $mount->reproductions_counter.'/'.$mount->max_reproductions; ?>
						</span>
					</div>
					<div class="medium-6 text-center cell">
                        <?php
                        if (!empty($mount->gender)) {
                            echo '<button 
									class="acym_vcenter align-center acy_button_submit cell medium-6 large-shrink button " 
									data-task="settings"
									data-mother-id="'.intval($mount->id).'">';
                            echo '<i class="acymicon-playmount_add"></i>Add child</button>';
                        }
                        ?>
					</div>
					<div class="medium-1 hide-for-small-only text-center acym__listing__id">
                        <?php echo acym_escape($mount->id); ?>
					</div>
				</div>
			</div>
        <?php } ?>
	</div>
    <?php echo $data['pagination']->display('mounts'); ?>
<?php } ?>
