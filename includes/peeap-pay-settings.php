<?php

// Display the plugin settings page
function peeap_pay_settings_page() {
    ?>
    <div class="wrap">
        <h1>Peeap Pay Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'peeap_pay_settings_group' );
            do_settings_sections( 'peeap-pay-settings' );
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Client ID</th>
                    <td><input type="text" name="peeap_pay_client_id" value="<?php echo esc_attr( get_option( 'peeap_pay_client_id' ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Secret ID</th>
                    <td><input type="text" name="peeap_pay_secret_id" value="<?php echo esc_attr( get_option( 'peeap_pay_secret_id' ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Payment Mode</th>
                    <td>
                        <label for="peeap_pay_mode" class="switch">
                            <input type="checkbox" name="peeap_pay_mode" id="peeap_pay_mode" value="live" <?php checked( 'live', get_option( 'peeap_pay_mode' ) ); ?>>
                            <span class="slider"></span> 
                            <span id="mode-label"><?php echo ( get_option( 'peeap_pay_mode' ) === 'live' ) ? 'Live' : 'Sandbox'; ?></span>
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
function peeap_pay_register_settings() {
    register_setting( 'peeap_pay_settings_group', 'peeap_pay_client_id' );
    register_setting( 'peeap_pay_settings_group', 'peeap_pay_secret_id' );
    register_setting( 'peeap_pay_settings_group', 'peeap_pay_mode' );
}
add_action( 'admin_init', 'peeap_pay_register_settings' );

// Add the toggle CSS
function peeap_pay_admin_styles() {
    ?>
    <style>
        /* Toggle switch styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
           
            width: 58px;
            height: 34px;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }   
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
        .slider.round {
            border-radius: 34px;
        }
            .slider.round:before {
                border-radius: 50%;
            }       
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }
        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'peeap_pay_admin_styles' );   
        