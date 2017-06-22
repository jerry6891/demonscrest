<div class="vp-field">
	<div class="label">
		<label>
			<?php esc_attr_e('Restore Default Options', 'massive-dynamic') ?>
		</label>
		<div class="description">
			<p><?php esc_attr_e('Restore options to initial default values.', 'massive-dynamic') ?></p>
		</div>
	</div>
	<div class="field">
		<div class="input">
			<div class="buttons">
				<input class="vp-js-restore vp-button button button-primary" type="button" value="<?php esc_attr_e('Restore Default', 'massive-dynamic') ?>" />
				<p><?php esc_attr_e('** Please make sure you have already make a backup data of your current settings. Once you click this button, your current settings will be gone.', 'massive-dynamic'); ?></p>
				<span class="px-margin-left">
					<span class="vp-field-loader vp-js-loader px-display-none"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" class="px-middle"></span>
					<span class="vp-js-status px-display-none" ></span>
				</span>
			</div>
		</div>
	</div>
</div>