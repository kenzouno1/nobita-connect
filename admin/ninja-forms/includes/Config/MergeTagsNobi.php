<?php if ( ! defined( 'ABSPATH' ) ) exit;

return array(

    /*
    |--------------------------------------------------------------------------
    | Querystring
    |--------------------------------------------------------------------------
    */
    'fullName' => array(
        'tag' => 'FullName',
        'label' => esc_html__( 'Họ Và Tên', 'ninja_forms' ),
        'callback' => null,
    ),
    'email' => array(
        'tag' => 'Email',
        'label' => esc_html__( 'Email', 'ninja_forms' ),
        'callback' => null,
    ),
    'phone' => array(
        'tag' => 'Phone',
        'label' => esc_html__( 'Số điện thoại', 'ninja_forms' ),
        'callback' => null,
    ),
    'address' => array(
        'tag' => 'Address',
        'label' => esc_html__( 'Địa chỉ', 'ninja_forms' ),
        'callback' => null,
    ),
);
