<?php if ( ! defined( 'ABSPATH' ) ) exit;

return array(
        'nb-args' => array(
            'name' => 'nb-args',
            'type' => 'option-repeater',
            'label' => __( 'Args', 'nobi_connect' ) . ' <a href="#" class="nf-add-new">' . __( 'Thêm trường', 'nobi_connect' )  . '</a>',
            'width' => 'full',
            'group' => 'primary',
            'tmpl_row' => 'tmpl-nf-nobi-args-repeater-row',
            'value' => array(),
            'columns'   =>array(
                'key' => array(
                    'header' => __( 'Key', 'nobi_connect' ),
                    'default' => '',
                    ),
                'value' => array(
                    'header' => __( 'Value', 'nobi_connect' ),
                    'default' => '',
                ),
            ),
        ),
);