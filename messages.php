<?php
/**
 * Define the localization text for JavaScript (:> JavaScript用の翻訳テキスト定義
 */
function __localize_messages() {
    return [
      // key: handle_name => value: localization text
      'loading' => __( 'Please Wait...', 'plotter' ), // custom.js: showLoading()
      'dialog_yes' => __( 'Ok', 'plotter' ), // custom.js: dialog()
      'dialog_no' => __( 'Cancel', 'plotter' ), // custom.js: dialog()
      'error' => __( 'Error!', 'plotter' ), // common title
      'confirmation' => __( 'Please check the following', 'plotter' ), // common title
      'are_you_sure' => __( 'Are you sure you want to execute?', 'plotter' ), // common message
      'switch_src_ttl' => __( 'Switch Manageable Story', 'plotter' ), // custom.js: Top Navigation
      'switch_src_msg' => __( 'Any unsaved data will be lost.', 'plotter' ), // custom.js: Top Navigation

      'show' => __( 'Show', 'plotter' ), // custom-profile.js
      'hide' => __( 'Hide', 'plotter' ), // custom-profile.js
      'show_passwd' => __( 'Show Password', 'plotter' ), // custom-account.js, custom-register.js
      'hide_passwd' => __( 'Hide Password', 'plotter' ), // custom-account.js, custom-register.js
      'shorter_username' => __( 'Your username must be at least 3 characters in length.', 'plotter' ), // custom-register.js
      'shorter_passwd' => __( 'Your password must be at least 6 characters in length.', 'plotter' ), // custom-register.js

      'act_num' => __( 'Act %d', 'plotter' ), // custom-create-new.js, custom-edit-storyline.js : 369,427,435
      'move_cross_dependency_ttl' => __( 'Move To Storyline', 'plotter' ), // custom-edit-storyline.js: 110
      'move_cross_dependency_msg' => __( 'If you move to a storyline with the different dependency, unsaved data will be lost. Your changes are saved by commit.', 'plotter' ), // custom-edit-storyline.js: 111,129
      'add_sub_storyline_ttl' => __( 'Add New Sub Storyline', 'plotter' ), // custom-edit-storyline.js: 128
      'remove_dependent_storylines_ttl' => __( 'Remove Dependent Storylines', 'plotter' ), // custom-edit-storyline.js: 146
      'remove_dependent_storylines_msg' => __( 'All acts and sub storylines subordinate to this dependency storylines are removed (cannot restore after done).', 'plotter' ), // custom-edit-storyline.js: 147
      
    ];
}
