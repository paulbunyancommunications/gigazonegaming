<?php
function add_theme_menu_item()
{
    add_menu_page("Theme Panel", "Theme Panel", "manage_options", "theme-panel", "theme_settings_page", null, 99);
}

add_action("admin_menu", "add_theme_menu_item");

function theme_settings_page()
{
    ?>
    <div class="wrap">
        <h1><?php echo wp_get_theme(); ?> Theme Panel</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields("section");
            do_settings_sections("theme-options");
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function displayEventDateElement()
{
    ?>
    <input type="text" name="event_date" id="event_date" value="<?php echo get_option('event_date'); ?>" />
    <?php
}

function displayEventLocationElement()
{
    ?>
    <input type="text" name="event_location" id="event_location" value="<?php echo get_option('event_location'); ?>" />
    <?php
}

function display_theme_panel_fields()
{
    add_settings_section("section", "Event Settings", null, "theme-options");

    add_settings_field("event_date", "Event Date", "displayEventDateElement", "theme-options", "section");
    add_settings_field("event_location", "Event Location", "displayEventLocationElement", "theme-options", "section");

    register_setting("section", "event_date");
    register_setting("section", "event_location");
}

add_action("admin_init", "display_theme_panel_fields");

