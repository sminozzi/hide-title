<?php
/**
 * Plugin Name: Hide Title
 * Description: Remove Titles from Posts and Pages.
 * Author: Bill Minozzi
 * Author URI: http://billminozzi.com
 * Version: 1.03
 * Text Domain: hide-site-title
 * Domain Path: /languages
 * License: GPL-2.0-or-later
*/
if (!defined("ABSPATH")) {
    exit();
}
define("HIDE_SITE_TITLE", plugin_dir_path(__FILE__));
define("HIDE_SITE_TITLE_PATH", plugin_dir_path(__FILE__));
define("HIDE_SITE_TITLE_URL", plugin_dir_url(__FILE__));
define("HIDE_SITE_TITLE_IMAGES", plugin_dir_url(__FILE__) . "images");
$hide_site_title_plugin = plugin_basename(__FILE__);
$hide_site_title_id = sanitize_text_field(get_option("hide_site_title_id", ""));
$hide_site_title_class = sanitize_text_field(get_option("hide_site_title_class", ""));
$hide_site_title_hide_title = sanitize_text_field(get_option("hide_site_title_hide_title", ""));

//var_export($hide_site_title_hide_title);

function hide_site_title_load_textdomain()
{
    load_plugin_textdomain("hide-site-title", false, HIDE_SITE_TITLE . "/languages");
}
add_action("plugins_loaded", "hide_site_title_load_textdomain");


if ($hide_site_title_hide_title == "maybe") {
    function hide_site_title_meta_boxes_setup()
    {
        add_action("add_meta_boxes", "hide_site_title_add_post_meta_box");
        add_action("save_post", "hide_site_title_save_meta", 10, 2);
    }
    add_action("load-post.php", "hide_site_title_meta_boxes_setup");
    add_action("load-post-new.php", "hide_site_title_meta_boxes_setup");
    function hide_site_title_add_post_meta_box()
    {
        add_meta_box(
            "hide-site-title-hide-site-title",
            esc_html__("Hide the Title?", "hide-site-title"),
            "hide_site_title_render_metabox",
            null,
            "side",
            "core"
        );
    }
    function hide_site_title_exec($title, $post_id = 0)
    {
        if (!$post_id) {
            return $title;
        }
        $hide_title = get_post_meta($post_id, "hide_title", true);
        if (
            !is_admin() &&
            is_singular() &&
            intval($hide_title) &&
            in_the_loop()
        ) {
            return "";
        }
        return $title;
    }
    add_filter("the_title", "hide_site_title_exec", 10, 2);
    function hide_site_title_render_metabox($post)
    {
        $curr_value = get_post_meta($post->ID, "hide_title", true);
        wp_nonce_field(basename(__FILE__), "hide_site_title_meta_nonce");
        ?>
	<input type="hidden" name="hide-site-title-hide-site-title-checkbox" value="0"/>
	<input type="checkbox" name="hide-site-title-hide-site-title-checkbox" id="hide-site-title-hide-site-title-checkbox"
	       value="1" <?php checked($curr_value, "1"); ?> />
	<label for="hide-site-title-hide-site-title-checkbox"><?php esc_html_e(
     "Hide the title for this element",
     "hide-site-title"
 ); ?></label>
	<?php
    }
    function hide_site_title_save_meta($post_id, $post)
    {
        $nonce = isset($_POST["hide_site_title_meta_nonce"]) ? sanitize_text_field($_POST["hide_site_title_meta_nonce"]) : '';

        if (
            ! $nonce or
            !  wp_verify_nonce($nonce, "hide_site_title_meta_action") or
            ! current_user_can("manage_options")
        ) {
        
            return;
        }

        $form_data = isset($_POST["hide-site-title-hide-site-title-checkbox"])
        ? sanitize_text_field($_POST["hide-site-title-hide-site-title-checkbox"])
        : "0";

        update_post_meta($post_id, "hide_title", $form_data);
    

    }
} // end maybe... with matabox
elseif ($hide_site_title_hide_title == "yes" && (!isset($_GET['page']) || sanitize_text_field($_GET['page']) !== 'hide-site-title')) {
  // Nonce verification is not necessary here, 
  // as this block should always execute 
  // unless the user is on the plugin dashboard.
  add_action('wp_enqueue_scripts', 'hide_site_title_add_styles');
  add_action('wp_head', 'hide_site_title_add_styles');

}

// add_action('wp_head', 'hide_site_title_add_styles');



function hide_site_title_add_styles() {
    global $hide_site_title_class, $hide_site_title_id;
    
    $custom_styles = "<style>
        .post .entry-title,
        .page .entry-title {
            display: none !important;
        }
    ";

    if (!empty($hide_site_title_class)) {
        $hide_site_title_class_sanitized = esc_attr($hide_site_title_class);
        $custom_styles .= "
            .post .{$hide_site_title_class_sanitized} {
                display: none;
            }
        ";
    }

    if (!empty($hide_site_title_id)) {
        $hide_site_title_id_sanitized = esc_attr($hide_site_title_id);
        $custom_styles .= "
            .post #{$hide_site_title_id_sanitized} {
                display: none;
            }
        ";
    }

    // Sanitize and echo the custom styles
    echo wp_kses($custom_styles, array(
        'style' => array(),
        'br' => array(),
        'div' => array(),
        'span' => array(),
        'p' => array(),
        'a' => array(
            'href' => array(),
            'title' => array()
        )
    ));

    echo  '</style>';
}




// Hook to add the administration menu
add_action("admin_menu", "hide_site_title_admin_menu");
// Function to create the administration menu
function hide_site_title_admin_menu()
{
    // Add a submenu page under "Tools"
    add_management_page(
        "Hide Title",
        "Hide Title",
        "manage_options",
        "hide-site-title", // slug
        "hide_site_title_page_content"
    );
}
// Function to display the main page content
function hide_site_title_page_content()
{
     global $active_tab;
    ?>
    <div class="wrap">
        <h1>Hide Title</h1>
        <?php
        
        // Check if the database update flag is set
        $database_updated = get_option(
            "hide_site_title_database_updated",
            false
        );
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] === "POST" && $database_updated) {
            // Display a success message
            echo '<div class="updated notice is-dismissible"><p>Database updated.</p></div>';
            // Remove the update flag to avoid displaying the message again
            delete_option("hide_site_title_database_updated");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["tab"])) {
                // Sanitize nonce
                $nonce = isset($_POST["_wpnonce"]) ? sanitize_key($_POST["_wpnonce"]) : '';
            
                // Verify nonce
                if ($nonce && !wp_verify_nonce($nonce, "hide-site-title-nonce")) {
                    echo "<p>Nonce verification failed!!!</p>";
                }
        }

        $active_tab = isset($_POST["tab"])
        ? sanitize_text_field($_POST["tab"])
        : "main";

    ?>
            <h2 class="nav-tab-wrapper">
                <form method="post" action="">
                    <?php wp_nonce_field("hide-site-title-nonce"); ?>
                    <input type="hidden" name="page" value="hide-site-title">
                    <input type="hidden" name="tab" value="main">
                    <input type="hidden" name="active_tab" value="<?php echo esc_attr($active_tab); ?>">
                    <button type="submit" class="nav-tab <?php echo $active_tab === "main" ? "nav-tab-active" : ""; ?>">Dashboard</button>
                </form>
                <form method="post" action="">
                    <?php wp_nonce_field("hide-site-title-nonce"); ?>
                    <input type="hidden" name="page" value="hide-site-title">
                    <input type="hidden" name="tab" value="1">
                    <input type="hidden" name="active_tab" value="<?php echo esc_attr(
                        $active_tab
                    ); ?>">
                    <button type="submit" class="nav-tab <?php echo $active_tab ===
                    "1"
                        ? "nav-tab-active"
                        : ""; ?>">Settings</button>
                </form>
                <form method="post" action="">
                    <?php wp_nonce_field("hide-site-title-nonce"); ?>
                    <input type="hidden" name="page" value="hide-site-title">
                    <input type="hidden" name="tab" value="2">
                    <input type="hidden" name="active_tab" value="<?php echo esc_attr(
                        $active_tab
                    ); ?>">
                    <button type="submit" class="nav-tab <?php echo $active_tab ===
                    "2"
                        ? "nav-tab-active"
                        : ""; ?>">Troubleshooting</button>
                </form>
                <!-- Add more tabs as needed -->
            </h2>
            <div class="content">
                <?php if ($active_tab === "1") {
                    hide_site_title_tab1();
                } elseif ($active_tab === "2") {
                    hide_site_title_tab2();
                } else {
                     ?>
                    <h2>Dashboard</h2>
                    Comprehensive Title Removal Strategy: Leveraging 3 Approaches for Success!
                    <br>
                    This plugin will attempt 3 approaches to ensure success in removing the title. 1) It will try to do so using WordPress get_post_meta. 2) It will attempt to hide it with CSS by changing the properties of the entry-title element. If these two approaches do not work, it will proceed to step 3 and allow you to locate the ID or class of the title element and inform the plugin. 
                    That's all we can do to ensure that the goal is achieved.
                    <br>
                    <br>
                    This plugin will attempt to automatically hide the title of your pages and posts as selected in the Settings Tab.
                    <br>
                    However, if your theme does not adhere to WordPress standards and the title continues to appear, you will need to identify the class or ID of your element (title) and add the details in the Settings Tab. There, in the Settings Tab, you will receive more information on how to do it.
                    <br>
                    <br>
                    If you choose to hide only selected pages and posts, the plugin will add a metabox* to all pages and posts for you to mark and decide whether the title will be displayed or not.
                    <br>
                    (*) click Help at top right corner if necessary.
                    <br>
                    <br>
                    Using Selected Pages Method, if possible, the plugin will completely removes the title instead of just hiding with CSS or JavaScript.
                    <br>
                    <br>
                    If you encounter any issues, please request free support before leaving a negative review, as various factors such as low WordPress memory and other considerations may be at play. Check out our Troubleshooting tab.
                <?php
                } ?>
            </div>
    </div>
    <?php

 }
// Function to display the content of Tab 1 (Settings)
function hide_site_title_tab1()
{
    global $active_tab;
    // Add the Help button using the admin_head hook
    ?>
    <div class="wrap">
        <h2>Settings</h2>
        <form method="post" action="">
            <?php
            // Output nonce field
            wp_nonce_field(
                "hide_site_title_settings_nonce",
                "hide_site_title_settings_nonce"
            );
            // Get saved values
            $hide_title = sanitize_text_field(get_option("hide_site_title_hide_title", ""));
            $id = sanitize_text_field(get_option("hide_site_title_id", ""));
            $class = sanitize_text_field(get_option("hide_site_title_class", ""));

            ?>
            <br>
            <label>
                <input type="radio" name="hide_site_title_hide_title" value="yes" <?php checked(
                    "yes",
                    $hide_title
                ); ?> checked />
                Hide the Title on all Pages and Posts
            </label>
            <br>
            <label>
                <input type="radio" name="hide_site_title_hide_title" value="maybe" <?php checked(
                    "maybe",
                    $hide_title
                ); ?> />
                Hide the title only on selected Pages and Posts. In this step, you need to look for the metabox on each page or post and mark it.    </label>
            <br>
            <label>
                <input type="radio" name="hide_site_title_hide_title" value="no" <?php checked(
                    "no",
                    $hide_title
                ); ?> />
                Show the Title on all Pages and Posts
            </label>
            <br>
            <br>
            One of the fields below should be filled out only if the title remains visible after activating this plugin. 
            For instance, if your theme does not adhere to WordPress standards, it will be necessary to fill out one of the fields below. 
            The two fields below will be considered only if you chose the first option above.
            <br>
            No point or #, only the name. 
            <br>
            Click the Help button in the top right corner for more details if needed.
            <br>
            <br>
            <label>
                ID: &nbsp;# 
                <input type="text" name="hide_site_title_id" value="<?php echo esc_attr(
                    $id
                ); ?>" />
            </label>
            <br>
            <br>
            <label>
                Class:   &nbsp;. &nbsp; 
                <input type="text" name="hide_site_title_class" value="<?php echo esc_attr(
                    $class
                ); ?>" />
            </label>
            <br>
            <br>
            <input type="hidden" name="active_tab" value="<?php echo esc_attr(
                $active_tab
            ); ?>">
            <input type="submit" name="hide_site_title_save_settings" class="button-primary" value="Save Settings">
        </form>
    </div>
    <?php
}
// Function to display the content of Tab 2
function hide_site_title_tab2()
{
    ?>
    <div class="wrap">
        <h2>Troubleshooting</h2>
        <!-- Add your tab 2 content here -->
        Often, things may not work as expected, but it's not always a plugin's fault. For instance, low WordPress or server memory, JavaScript errors caused by other plugins or themes, etc. If you encounter any issues, don't hesitate to check out our troubleshooting page, where you'll discover various solutions to common problems.
    </div>
    <a class="button button-primary" href="https://siterightaway.net/troubleshooting">Visit Troubleshooting Page</a>
    <?php
}
// Function to save settings
function hide_site_title_save_settings()
{
    if (
        isset($_POST["hide_site_title_save_settings"]) &&
        isset($_POST["hide_site_title_settings_nonce"]) &&
        wp_verify_nonce(
            sanitize_key($_POST["hide_site_title_settings_nonce"]),
            "hide_site_title_settings_nonce"
        )
    )
    {
        // Sanitize and save the settings
        $hide_title = isset($_POST["hide_site_title_hide_title"])
            ? sanitize_text_field($_POST["hide_site_title_hide_title"])
            : "";
        $id = isset($_POST["hide_site_title_id"])
            ? sanitize_text_field($_POST["hide_site_title_id"])
            : "";
        $class = isset($_POST["hide_site_title_class"])
            ? sanitize_text_field($_POST["hide_site_title_class"])
            : "";
        update_option("hide_site_title_hide_title", $hide_title);
        update_option("hide_site_title_id", $id);
        update_option("hide_site_title_class", $class);
        update_option("hide_site_title_database_updated", true);
    }
}

// Hook to save settings
add_action("admin_init", "hide_site_title_save_settings");
// Help
function hide_site_title_add_help_button()
{
    
    // global $active_tab;
    require_once ABSPATH . "wp-admin/includes/screen.php";
    $screen = get_current_screen();

    if ($screen && $screen->id !== "tools_page_hide-site-title") {
        return;
    }

    if (isset($_POST["tab"])) {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["_wpnonce"])) {
            $nonce = sanitize_text_field($_POST["_wpnonce"]);
            $tab = isset($_POST["tab"]) ? sanitize_text_field($_POST["tab"]) : "main";
    
            if (wp_verify_nonce($nonce, "hide-site-title-nonce")) {
                // Se o nonce for válido, continue com o processamento do formulário
                $active_tab = $tab;
            } else {
                // Se o nonce não for válido, exiba uma mensagem de erro ou tome outras ações necessárias
                echo "<p>Nonce verification failed!!!</p>";
            }
        }
    }
    else
       $active_tab = 'main';




    if ($active_tab == "2") {
        return;
    }
    if ($active_tab == "main") {
        $help =
            "Metabox is an added box in the page or post editor that enables users to mark and decide whether the title should be displayed or hidden for that specific page or post.";
    }
    if ($active_tab == "1") {
        $help = '
        <div><strong>Metabox</strong></div>
        <div>Metabox is an added box in the page or post editor that enables users to mark and decide whether the title should be displayed or hidden for that specific page or post.</div>
        <div>&nbsp;</div>
        <div><strong>How to recover the ID or class from your title on your home page.</strong></div>
        <div>Hover your mouse over the page title on your WordPress site.</div>
        <div>Right-click on the title.</div>
        <div>From the menu that appears, select "Inspect" or "Inspect Element."</div>
        <div>A new panel or window will open, highlighting the HTML code of the page.</div>
        <div>Look for the section that corresponds to the title, usually inside tags like &lt;h1&gt; or &lt;h2&gt;.</div>
        <div>Check for any <code>class</code> or <code>id</code> attributes associated with the title.</div>
         ';
    }
    $screen->add_help_tab([
        "id" => "hide-site-title-help",
        "title" => __("Help", "hide-site-title"),
        "content" => "<p>" . $help . "</p>",
    ]);

    
}
add_action("admin_head", "hide_site_title_add_help_button");
