<div class="cell grid-x margin-y">
	<input type="hidden" id="colors" value="<?php echo acym_escape(json_encode($data['colors'])); ?>">
	<input type="hidden" id="mounts" value="<?php echo acym_escape(json_encode($data['mounts'])); ?>">
	<input type="hidden" name="mother_id" value="<?php echo acym_escape(json_encode($data['mother_id'])); ?>">

	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_NAME'); ?></label>
		<input style="max-width: 400px;"
			   name="mount[name]"
			   type="text"
			   class="acy_required_field cell"
			   value="<?php echo acym_escape($data['mountInformation']->name); ?>"
			   required>
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_GENDER'); ?></label>
		<img data-gender="0"
			 class="gender_image<?php echo empty($data['mountInformation']->gender) ? ' selected_gender' : ''; ?>"
			 alt="male"
			 src="<?php echo ACYM_IMAGES.'male.png'; ?>" />
		<img data-gender="1"
			 class="gender_image<?php echo empty($data['mountInformation']->gender) ? '' : ' selected_gender'; ?>"
			 alt="female"
			 src="<?php echo ACYM_IMAGES.'female.png'; ?>" />
		<input id="genderInput" type="hidden" name="mount[gender]" value="<?php echo $data['mountInformation']->gender; ?>" />
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_SPECIES'); ?></label>
        <?php echo acym_select($data['species'], 'species', $data['mountInformation']->species_id, ['onchange' => 'jQuery.updateColors(this.value);'], 'id', 'name'); ?>
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_COLOR'); ?></label>
        <?php echo acym_select($data['colors'][$data['mountInformation']->species_id], 'mount[color_id]', $data['mountInformation']->color_id, [], 'id', 'name', 'colorInput'); ?>
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_INTERCOURSE_COUNT'); ?></label>
		<input style="max-width: 400px;"
			   name="mount[reproductions_counter]"
			   type="number"
			   class="cell"
			   value="<?php echo acym_escape($data['mountInformation']->reproductions_counter); ?>">
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_MAX_REPRODUCTIONS'); ?></label>
		<input style="max-width: 400px;"
			   name="mount[max_reproductions]"
			   type="number"
			   class="cell"
			   value="<?php echo acym_escape($data['mountInformation']->max_reproductions); ?>">
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_PURITY_LEVEL'); ?></label>
        <?php echo acym_select($data['purity_levels'], 'mount[purity_level]', $data['mountInformation']->purity_level); ?>
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_REPRODUCTIVE'); ?></label>
        <?php
        echo acym_select(
            [
                0 => acym_translation('ACYM_NO'),
                1 => acym_translation('ACYM_YES'),
            ],
            'mount[reproductive]',
            $data['mountInformation']->reproductive
        );
        ?>
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_MOTHER'); ?></label>
        <?php echo acym_select($data['mounts'][$data['mountInformation']->species_id], 'mount[mother_id]', $data['mountInformation']->mother_id, [], 'id', 'name'); ?>
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_FATHER'); ?></label>
        <?php echo acym_select($data['mounts'][$data['mountInformation']->species_id], 'mount[father_id]', $data['mountInformation']->father_id, [], 'id', 'name'); ?>
	</div>
	<div class="cell large-6 grid-x">
		<label class="cell"><?php echo acym_translation('ACYM_LAST_PARTNER'); ?></label>
        <?php echo acym_select($data['mounts'][$data['mountInformation']->species_id], 'mount[last_partner_id]', $data['mountInformation']->last_partner_id, [], 'id', 'name'); ?>
	</div>
</div>
