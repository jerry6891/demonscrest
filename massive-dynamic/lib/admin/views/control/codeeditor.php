<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

<textarea class="vp-input px-display-none" name="<?php echo esc_attr($name); ?>" ><?php echo esc_attr($value); ?></textarea>
<div class="vp-js-codeeditor" data-vp-opt="<?php echo esc_attr($opt); ?>"></div>

<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>