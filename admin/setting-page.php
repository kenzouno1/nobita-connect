<?php

if (!defined("WPINC")) {
    die();
}

function nobi_connect_settings_init()
{
    register_setting("nobi_connect", "nobi_connect_options");

    add_settings_section(
        "nobi_connect_section_developers",
        "Nobi Connect Settings",
        "nobi_connect_section_developers_callback",
        "nobi_connect"
    );

    add_settings_field(
        "nobi_connect_field_domain",
        "Domain",
        "nobi_connect_field_cb",
        "nobi_connect",
        "nobi_connect_section_developers",
        [
            "name" => "domain",
            "pattern" => '^[a-z0-9][a-z0-9-]+[a-z0-9](\.ecrm.vn|\.nobi.pro)$',
            "type" => "text",
            "description" => __(
                "Tên miền đầy đủ hệ thống Nobi của bạn (vd: shopabc.ecrm.vn, shopxyz.nobi.pro)",
                "nobi_connect"
            ),
        ]
    );

    add_settings_field(
        "nobi_connect_field_apikey",
        "ApiKey",
        "nobi_connect_field_cb",
        "nobi_connect",
        "nobi_connect_section_developers",
        [
            "name" => "apikey",
            "type" => "text",
            "description" => __(
                'Nhận ApiKey theo hướng dẫn <a href="https://helpv2.nobi.pro/api/key-xac-thuc" target="_blank">tại đây</a> (vd: 15950b1f-864a-4b48-857f-332ba0e021db)',
                "nobi_connect"
            ),
            "pattern" =>
                '^[a-fA-F0-9]{8}[-]([a-fA-F0-9]{4}[-]){3}[a-fA-F0-9]{12}$',
        ]
    );
    add_settings_field(
        "nobi_connect_field_use_access_link",
        "Link truy cập",
        "nobi_connect_field_cb",
        "nobi_connect",
        "nobi_connect_section_developers",
        [
            "name" => "use_access_link",
            "type" => "checkbox",
            "description" => __(
                "Lưu giữ url mà khách truy cập đầu tiên truy cập vào website và gửi về hệ thống Nobi",
                "nobi_connect"
            ),
        ],
    );
}

add_action("admin_init", "nobi_connect_settings_init");

function nobi_connect_section_developers_callback($args)
{
    ?>
    <p id="<?php echo esc_attr(
        $args["id"]
    ); ?>"><?php esc_html_e("Nhập thông tin để kết nối website của bạn với Nobi Pro", "nobi_connect"); ?></p>
    <?php
}

function nobi_connect_field_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $options = get_option("nobi_connect_options");

    $type = isset($args["type"]) ? $args["type"] : "text";
    ?>
    <div>
        <?php if ($type == "checkbox") { ?>
            <input 
                type="checkbox" 
                name="nobi_connect_options[<?php echo esc_attr(
                    $args["name"]
                ); ?>]"
                id="<?php echo esc_attr($args["name"]); ?>"
                value="1" <?php checked(
                    isset($options[$args["name"]])
                        ? esc_attr($options[$args["name"]])
                        : "",
                    1
                ); ?> 
            />
        <?php } elseif ($type == "text") { ?>
    <input 
        type="text" 
        required
        name="nobi_connect_options[<?php echo esc_attr($args["name"]); ?>]"
        id="<?php echo esc_attr($args["name"]); ?>"
        value="<?php echo isset($options[$args["name"]])
            ? esc_attr($options[$args["name"]])
            : ""; ?>"
        <?php echo isset($args["pattern"])
            ? 'pattern="' . esc_attr($args["pattern"]) . '"'
            : ""; ?>
        style="width: 400px"
    />
<?php } ?>
</div>
<?php if (isset($args["description"])) { ?>
    <p class="description">
        <?php echo $args["description"] ?>
    </p>
    <?php }
}

function nobi_connect_options_page()
{
    add_menu_page(
        "Nobi",
        "Nobi Settings",
        "manage_options",
        "nobi_connect",
        "nobi_connect_options_page_html",
        plugins_url("/images/icon.png", dirname(__FILE__))
    );
}

add_action("admin_menu", "nobi_connect_options_page");

function nobi_connect_options_page_html()
{
    if (!current_user_can("manage_options")) {
        return;
    }

    if (isset($_GET["settings-updated"])) {
        add_settings_error(
            "nobi_connect_messages",
            "nobi_connect_message",
            __("Settings Saved", "nobi_connect"),
            "updated"
        );
    }

    settings_errors("nobi_connect_messages");
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields("nobi_connect");
            do_settings_sections("nobi_connect");
            submit_button("Save Settings");?>
        </form>
    </div>
    <?php
}
