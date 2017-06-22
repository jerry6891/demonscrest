<?php

class Pixflow_Customize_Description_Control extends WP_Customize_Control {

    public $type = 'description';

    public $subtitle = '';

    public $separator = false;

    public $required;

    public $class;

    public function render_content() {?>

        <div class="customizer-description <?php echo ' '.esc_attr($this->class); ?>">
            <?php $value =  (is_array($this->value()))? implode(" ",$this->value()):$this->value();  ?>
            <div class="content"><?php echo ( $value ); ?></div>
        </div>
        <?php if ( $this->separator ) echo '<hr class="customizer-separator">';
    }
}
