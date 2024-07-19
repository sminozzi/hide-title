<?php namespace cardealer_BillCatchErrors;
// created 06/23
// upd: 2023-10-16
if (!defined("ABSPATH")) {
    die("Invalid request.");
}


// Linha 41 é custom do wptools




if (function_exists('is_multisite') AND is_multisite()) {
    return;
}

/*
// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.

// catch js errors...


/*
// >>>>>>>>>>>>>>>> call
function cardealer_bill_hooking_catch_errors()
{
    if (function_exists('is_admin') && function_exists('current_user_can')) {
        if(is_admin() and current_user_can("manage_options")){
            $declared_classes = get_declared_classes();
            foreach ($declared_classes as $class_name) {
                if (strpos($class_name, "bill_catch_errors") !== false) {
                    return;
                }
            }
            require_once dirname(__FILE__) . "/includes/checkup/class_bill_catch_errors.php";

        }
    } 
}
add_action("shutdown", "cardealer_bill_hooking_catch_errors");
// end >>>>>>>>>>>>>>>>>>>>>>>>>
*/





add_action("wp_ajax_bill_js_error_catched", "bill_js_error_catched");
add_action(
    "wp_ajax_nopriv_cardealer_bill_js_error_catched",
    "cardealer_bill_js_error_catched"
);
function cardealer_bill_js_error_catched()
{
    if (isset($_REQUEST)) {
        if (!isset($_REQUEST["bill_js_error_catched"])) {
            die("empty error");
        }
        if (
            !wp_verify_nonce(
                sanitize_text_field($_POST["_wpnonce"]),
                "bill-catch-js-errors"
            )
        ) {
            status_header(406, "Invalid nonce");
            die();
        }
        $bill_js_error_catched = sanitize_text_field(
            $_REQUEST["bill_js_error_catched"]
        );
        $bill_js_error_catched = trim($bill_js_error_catched);
        // 2024
        $errstr = substr($bill_js_error_catched, 9);
        $parts = explode(" | ", $message);
        if (isset($parts[1])) {
            $errfile = $parts[1];
        } else {
            die("NOT OK 1!");
        }
        if (isset($parts[2])) {
            $errline = $parts[2];
        } else {
            die("NOT OK 2!");
        }
        wptoolsErrorHandler("Javascript", $errstr, $errfile, $errline);
        if (!empty($bill_js_error_catched)) {
            $parts = explode(" | ", $bill_js_error_catched);
            for ($i = 0; $i < count($parts); $i++) {
                $txt = "Javascript " . $parts[$i];
                error_log($txt);
                add_option("bill_javascript_error", time());
            }
            die("OK!!!");
        }
    }
    die("NOT OK!");
}
class cardealer_bill_catch_errors
{
    public function __construct()
    {
        add_action("wp_head", [$this, "add_bill_javascript_to_header"]);
        add_action("admin_head", [$this, "add_bill_javascript_to_header"]);
    }
    public function add_bill_javascript_to_header()
    {
        $nonce = wp_create_nonce("bill-catch-js-errors");
        $ajax_url =
            esc_attr($this->get_ajax_url()) .
            "?action=log_js_error&_wpnonce=" .
            $nonce;
        ?>
        <script>
        var errorQueue = []; 
        var timeout;
        function isBot() {
            const bots = ['bot', 'googlebot', 'bingbot', 'facebook', 'slurp', 'twitter','yahoo']; // Add other bots if necessary
            const userAgent = navigator.userAgent.toLowerCase();
            return bots.some(bot => userAgent.includes(bot));
        }
        window.onerror = function(msg, url, line) {
            var errorMessage = [
                'Message: ' + msg,
                'URL: ' + url,
                'Line: ' + line
            ].join(' - ');
            // Filter bots errors...
            if (isBot()) {
                return;
            }
            errorQueue.push(errorMessage); 
            if (errorQueue.length >= 5) { 
                sendErrorsToServer();
            } else {
                clearTimeout(timeout);
                timeout = setTimeout(sendErrorsToServer, 5000); 
            }
        }
        function sendErrorsToServer() {
            if (errorQueue.length > 0) {
                var message = errorQueue.join(' | ');
                var xhr = new XMLHttpRequest();
                var nonce = '<?php echo esc_js($nonce); ?>';
                var ajaxurl = '<?php echo esc_js($ajax_url); ?>';
                xhr.open('POST', encodeURI(ajaxurl)); 
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (200 === xhr.status) {
                        try {
                            //console.log(xhr.response);
                        } catch (e) {
                            console.log('error xhr not 200!');
                        }
                    } else {
                        console.log('error 2');
                    }
                };
                xhr.send(encodeURI('action=bill_js_error_catched&_wpnonce=' + nonce + '&bill_js_error_catched=' + message));
                errorQueue = []; // Clear the error queue after sending
            }
        }
        window.addEventListener('beforeunload', sendErrorsToServer);
        </script>
        <?php
    }
    private function get_ajax_url()
    {
        return esc_attr(admin_url("admin-ajax.php"));
    }
}
new cardealer_bill_catch_errors();
