<?php
if( ! defined("ABSPATH") ) {
    exit;
}
class wishListSettings {
    public function __construct() {
        add_action("admin_menu",array($this,"wish_list_create_menu"));
    }
    function wish_list_create_menu() {
        add_menu_page('Wish List Settings', 'Wish List','edit_posts' , __FILE__, array($this,"wish_list_settings_page"));
        add_action( 'admin_init', array($this,'wish_list_register_settings') );
    }
    function wish_list_settings_page () {
        ?>
            <style>
                .admin-wish-list-container {
                    position: relative;
                    width: 98%;
                    height: 300px;
                    margin: 0 auto;
                    margin-top: 1%;
                }
                .wLine {
                    position: relative;
                    width: 20%;
                    margin: 1%;
                }
                .wAlignRight {
                    right: 0;
                    position: absolute;
                }
                .wListPosition {
                    width: 181px;
                }
                label {
                    font-weight: 500;
                }
                .wHTitle:after {
                    font-family: FontAwesome;
                    content: '\f08a';
                    margin-left: 10px;
                }
            </style>
            <div class="admin-wish-list-container">
                <form method="post" action="options.php">
                    <?php settings_fields('wish-list-settings-group'); ?>
                    <?php do_settings_sections('wish-list-settings-group'); ?>
                <h2 class="wHTitle">Wish List Settings</h2>
                <div class="wLine">
                    <label for="wListPosition"> Position:
                        <select class="wAlignRight wListPosition" name="wListPosition" id="wListPosition">
                            <?php $wLP=get_option('wListPosition'); ?>
                            <option value="wList-top-left"     <?php echo $select = $wLP == 'wList-top-left' ? 'selected' : '';    ?> >Top Left</option>
                            <option value="wList-top-right"    <?php echo $select = $wLP == 'wList-top-right' ? 'selected' : '';   ?> >Top Right</option>
                            <option value="wList-bottom-left"  <?php echo $select = $wLP == 'wList-bottom-left' ? 'selected' : ''; ?> >Bottom Left</option>
                            <option value="wList-bottom-right" <?php echo $select = $wLP == 'wList-bottom-right' ? 'selected' : '';?> >Bottom Right</option>
                        </select>
                    </label>
                </div>
                <div class="wLine">
                    <label for="wListTitle"> Title :
                        <input class="wAlignRight" type="text" name="wListTitle" id="wListTitle" value="<?php echo get_option('wListTitle') ; ?>">
                    </label>
                </div>
                <div class="wLine">
                    <label for="colorPickerBackground">Background Color :
                        <input class="wAlignRight" type="color" name="colorPickerBackground" id="colorPickerBackground" value="<?php echo get_option('colorPickerBackground') ; ?>">
                    </label>
                </div>
                <div class="wLine">
                    <label for="colorPickerText">Text Color :
                        <input class="wAlignRight" type="color" name="colorPickerText" id="colorPickerText" value="<?php echo get_option('colorPickerText') ; ?>">
                    </label>
                </div>
                <div class="wLine">
                   <p class="wAlignRight">
                       <?php submit_button(); ?>
                   </p>
                </div>
                </form>
            </div>
        <?php
    }
    function wish_list_register_settings() {
        register_setting( 'wish-list-settings-group', 'wListPosition' );
        register_setting( 'wish-list-settings-group', 'wListTitle' );
        register_setting( 'wish-list-settings-group', 'colorPickerBackground' );
        register_setting( 'wish-list-settings-group', 'colorPickerText' );
    }
}
new wishListSettings();