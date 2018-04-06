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
      'switch_src_ttl' => __( 'Switch Manageable Story', 'plotter' ), // custom.js: Top Navigation
      'switch_src_msg' => __( 'Any unsaved data will be lost. Are you sure?', 'plotter' ), // custom.js: Top Navigation
      'act_num' => __( 'Act %d', 'plotter' ), // custom-create-new.js, custom-edit-storyline.js : 
      'move_cross_dependency_ttl' => __( 'Move To Storyline', 'plotter' ), // custom-edit-storyline.js:
      'move_cross_dependency_msg' => __( 'If you move to a storyline with the different dependency, unsaved data will be lost. Your changes are saved by commit. Are you sure you want to move?', 'plotter' ), // custom-edit-storyline.js:
      'add_sub_storyline_ttl' => __( 'Add New Sub Storyline', 'plotter' ), // custom-edit-storyline.js:
      'remove_dependent_storylines_ttl' => __( 'Remove Dependent Storylines', 'plotter' ), // custom-edit-storyline.js:
      'remove_dependent_storylines_msg' => __( 'All storylines subordinate to this dependency are removed. Are you sure you want to remove?', 'plotter' ), // custom-edit-storyline.js:
      
    ];
}
