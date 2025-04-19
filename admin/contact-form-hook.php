<?php
add_action('wpcf7_before_send_mail', 'nobi_cf7_before_send_mail', 10, 3);
add_filter('wpcf7_editor_panels', 'nobi_add_cf7_panel', 10, 1);
add_filter('wpcf7_contact_form_properties', 'nobi_add_cf7_properties', 10, 2);
add_action('wpcf7_save_contact_form', 'nobi_save_config_contact_form', 10, 3);
add_action('wpcf7_form_hidden_fields', 'nobi_add_utm_fields', 10, 1);


function load_utm_value($name)
{
    if (isset($_GET[$name])) {
        return sanitize_text_field($_GET[$name]);
    } else if (isset($_COOKIE[$name])) {
        return sanitize_text_field($_COOKIE[$name]);
    }
}

function nobi_add_utm_fields($fields)
{

    $utm_source = load_utm_value("utm_source");
    $utm_campaign = load_utm_value("utm_campaign");
    $utm_content = load_utm_value("utm_content");
    $utm_medium = load_utm_value("utm_medium");
    $utm_term = load_utm_value("utm_term");

    $params = array_filter(array(
        'utm_source' => $utm_source,
        'utm_campaign' => $utm_campaign,
        'utm_content' => $utm_content,
        'utm_medium' => $utm_medium,
        'utm_term' => $utm_term
    ), function ($value, $key) {
        return isset($value) && $value != "";
    }, ARRAY_FILTER_USE_BOTH);

    global $wp;
    $link = add_query_arg($params, home_url($wp->request));

    return array(
        "link" => esc_url_raw($link)
    );

}

function nobi_cf7_before_send_mail($cf7)
{
    $options = get_option('nobi_connect_options');

    if (!isset($options) || !isset($options['domain']) || !isset($options['apikey']))
        return;

    $wpcf = WPCF7_ContactForm::get_current();
    $nobita = get_post_meta($wpcf->id, "_nobita", true);
    ;
    $formData = nobi_santize_cf7_form($nobita);
    $lead = new NobitaLead($formData);
    $callback = 'https://' . $options['domain'] . '/public-api/leads/createLead';
    wp_remote_post($callback, array(
        'headers' => array(
            'Content-Type' => 'application/json; charset=utf-8',
            'ApiKey' => $options['apikey']
        ),
        'body' => $lead->to_json(),
        'method' => 'POST',
        'data_format' => 'body'
    ));
    return $wpcf;

}

function nobi_save_config_contact_form($contact_form, $args)
{
    $properties = $contact_form->get_properties();
    if (null !== $args['wpcf7-nobita']) {
        $properties['nobita'] = $args['wpcf7-nobita'];
    }
    $contact_form->set_properties($properties);
}

function nobi_santize_cf7_form($fields)
{
    $output = array();

    foreach ($fields as $k => $v) {
        if (isset($_POST[$v])) {
            if ($k == "email") {
                $output[$k] = sanitize_email($_POST[$v]);
            } else {
                $output[$k] = sanitize_text_field($_POST[$v]);
            }
        }
    }

    $extra = array_filter($_POST, function ($value, $key) use ($fields) {
        return strpos($key, "_wpcf7") === false && isset($value) && $value != ""
            && !in_array($key, array_values($fields));
    }, ARRAY_FILTER_USE_BOTH);

    foreach ($extra as $k => $v) {
        $output[$k] = sanitize_text_field($v);
    }

    return $output;
}

function nobi_add_cf7_panel($panels)
{
    $panels['nobita-panel'] = array(
        'title' => __('Nobi', 'nobi_connect'),
        'callback' =>
            'nobi_cf7_config'
        ,
    );

    return $panels;
}

function nobi_add_cf7_properties($properties)
{
    if (!isset($properties["nobita"])) {
        $properties["nobita"] = array();
    }
    return $properties;
}


function nobi_cf7_fields()
{
    return array(
        'fullName' => array(
            'description' => __("Họ Và tên", 'nobi_connect')
        ),
        'firstName' => array(
            'description' => __("Tên", 'nobi_connect')
        ),
        'lastName' => array(
            'description' => __("Họ", 'nobi_connect')
        ),
        'email' => array(
            'description' => __("Email", 'nobi_connect')
        ),
        'phone' => array(
            'description' => __("Số điện thoại", 'nobi_connect')
        ),
        'address' => array(
            'description' => __("Địa chỉ", 'nobi_connect')
        ),
    );
}

function nobi_cf7_config($post)
{

    $description = __("Thiết lập đổ lead về Nobi", 'nobi_connect');

    $formTags = $post->collect_mail_tags();

    $fields = array(
        'fields' => nobi_cf7_fields()
    );
    $config = get_post_meta($post->id, "_nobita", true);

    ?>
    <datalist id="suggest">
        <?php foreach ($formTags as $v) {
            echo "<option value=" . esc_attr($v) . ">";
        }
        ?>
    </datalist>

    <fieldset>
        <h3>Thiết lập lưu dữ liệu</h3>
        <p>Thiết lập liên kết các trường trong form với Nobi.<br />
            Các trường được thiết lập trong bảng này sẽ được lưu vào thông tin lead, các trường khác sẽ tự động lưu vào các
            trường mở rộng trên Nobi.
            <br /> Để trống trường <strong>Họ và Tên</strong> và điền thông tin vào trường <strong>Họ</strong>,
            <strong>Tên</strong> nếu form có trường <strong>Họ</strong> và trường Tên</strong>
            <br /> Để trống 2 trường <strong>Họ</strong>,<strong>Tên</strong> nếu form chỉ có 1 ô Họ Tên
            <br />(Lưu ý : Cần một trường <strong>Email</strong> hoặc <strong>Điện thoại</strong> để liên hệ)
        </p>
        <?php
        foreach ($fields["fields"] as $key => $arr) {
            $field_id = sprintf('wpcf7-nobita-%s', strtr($key, '_', '-'));
            $field_name = sprintf('wpcf7-nobita[%s]', $key);
            $value = "";
            if (isset($config[$key]) && $config[$key] !== "") {
                $value = $config[$key];
            } else {
                if ($key == "phone" && in_array("your-phone", $formTags)) {
                    $value = "your-phone";
                } else if ($key == "fullName" && in_array("your-name", $formTags)) {
                    $value = "your-name";
                } else if ($key == "email" && in_array("your-email", $formTags)) {
                    $value = "your-email";
                }
            }

            ?>
            <p class="description">
                <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($arr['description']); ?><br />
                    <input list="suggest" type="text" id="<?php echo esc_attr($field_id); ?>"
                        name="<?php echo esc_attr($field_name); ?>" class="large-text" size="70"
                        value="<?php echo esc_attr($value); ?>"
                        data-config-field="<?php echo sprintf('nobita.%s', esc_attr($key)); ?>" />
                </label>
            </p>
            <?php
        }
        ?>
    </fieldset>


    <?php
}

