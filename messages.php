<?php
/**
 * Define the localization text for JavaScript (:> JavaScript用の翻訳テキスト定義
 *
 * @param string $page_name (optional)
 */
function __localize_messages( $page_name=null ) {
    $localize_messages = [
        // Common Messages
        // key: handle_name => value: localization text
        'loading' => __( 'Please Wait...', 'plotter' ), // custom.js: showLoading()
        'dialog_yes' => __( 'Ok', 'plotter' ), // custom.js: dialog()
        'dialog_no' => __( 'Cancel', 'plotter' ), // custom.js: dialog()
        'error' => __( 'Error!', 'plotter' ), // common title
        'confirmation' => __( 'Please check the following', 'plotter' ), // common title
        'are_you_sure' => __( 'Are you sure you want to execute?', 'plotter' ), // common message
        'notify_empty_title' => __( 'Please be sure to fill here', 'plotter' ), // "wp_print_footer_scripts" hook on functions.php
        'switch_src_ttl' => __( 'Switch Manageable Story', 'plotter' ), // custom.js: Top Navigation
        'switch_src_msg' => __( 'Any unsaved data will be lost.', 'plotter' ), // custom.js: Top Navigation
        
    ];
    $partial_messages = [
        // Key: regex
        '/^profile$/' => [
            'show' => __( 'Show', 'plotter' ), // custom-profile.js
            'hide' => __( 'Hide', 'plotter' ), // custom-profile.js
            'delete_account_ttl' => __( 'Your Account Deletion', 'plotter' ), // custom-profile.js : 185
            'delete_account_msg' => __( "If you delete the account, withdrawal from the Plotter's membership. And it will be removed all data you saved, then you can not restore them.", 'plotter' ), // custom-profile.js : 186
            
        ],
        '/^(account|register)$/' => [
            'show_passwd' => __( 'Show Password', 'plotter' ), // custom-account.js, custom-register.js
            'hide_passwd' => __( 'Hide Password', 'plotter' ), // custom-account.js, custom-register.js
            'shorter_username' => __( 'Your username must be at least 3 characters in length.', 'plotter' ), // custom-register.js
            'shorter_passwd' => __( 'Your password must be at least 6 characters in length.', 'plotter' ), // custom-register.js
            
        ],
        '/^dashboard$/' => [
            
        ],
        '/^whole\-story$/' => [
            
        ],
        '/^(create\-new|edit-storyline)$/' => [
            'act_num' => __( 'Act %d', 'plotter' ), // custom-create-new.js, custom-edit-storyline.js : 369,427,435
            'move_cross_dependency_ttl' => __( 'Move To Storyline', 'plotter' ), // custom-edit-storyline.js: 110
            'move_cross_dependency_msg' => __( 'If you move to a storyline with the different dependency, unsaved data will be lost. Your changes are saved by commit.', 'plotter' ), // custom-edit-storyline.js: 111,129
            'add_sub_storyline_ttl' => __( 'Add New Sub Storyline', 'plotter' ), // custom-edit-storyline.js: 128
            'remove_dependent_storylines_ttl' => __( 'Remove Dependent Storylines', 'plotter' ), // custom-edit-storyline.js: 146
            'remove_dependent_storylines_msg' => __( 'All acts and sub storylines subordinate to this dependency storylines are removed (cannot restore after done).', 'plotter' ), // custom-edit-storyline.js: 147
            
        ],
        // etc...
    ];
    //$_keys = array_keys( $partial_messages );
    foreach ( $partial_messages as $_k => $_v ) {
        if ( empty( $page_name ) || preg_match( $_k, $page_name ) == 1 ) {
            $localize_messages = array_merge( $localize_messages, $partial_messages[$_k] );
        }
    }
    return $localize_messages;
}
