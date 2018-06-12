<?php
/**
 * Template part for displaying add character content in page.php
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$_plotter = get_query_var( 'plotter', [] );
$page_name         = @$_plotter['page_name'] ?: '';
$current_user_id   = @$_plotter['current_user_id'] ?: null;
$current_source_id = @$_plotter['current_source_id'] ?: null;
$current_journal   = @$_plotter['current_journal'] ?: [];
$max_upload_size   = size_format( @$_plotter['max_upload_size'] ?: 1024 * 1024 * 2 );
$journal_item_ids  = @$_plotter['journal_item_ids'] ?: [];
$journal_default_items = @$_plotter['journal_default_items'] ?: [];

// Defines the variables for this page
$enable_toolbox = true;
$default_active_tab = isset( $_GET['tab'] ) && in_array( (int) $_GET['tab'], [ 1, 2, 3 ], true ) ? (int) $_GET['tab'] : 2;
$define_char_roles = [
  __( 'Protagonist', WPGENT_DOMAIN ),    // (:> 主人公
  __( 'Hero', WPGENT_DOMAIN ),           // (:> ヒーロー
  __( 'Heroine', WPGENT_DOMAIN ),        // (:> ヒロイン
  __( 'Rival', WPGENT_DOMAIN ),          // (:> ライバル
  __( 'Villain', WPGENT_DOMAIN ),        // (:> ヴィラン
  __( 'Enemy', WPGENT_DOMAIN ),          // (:> 敵
  __( 'Main Character', WPGENT_DOMAIN ), // (:> 主要キャラクター
  __( 'Sub Character', WPGENT_DOMAIN ),  // (:> サブキャラクター
  __( 'Regular', WPGENT_DOMAIN ),        // (:> レギュラー
  __( 'Ally', WPGENT_DOMAIN ),           // (:> 仲間
  __( 'Narrator', WPGENT_DOMAIN ),       // (:> ナレーター
  __( 'Storyteller', WPGENT_DOMAIN ),    // (:> ストーリーテラー
];
$max_tags = 5;
$items_order = [ 'created_at' => 'asc' ]; // default
if ( isset( $_COOKIE['char_order'] ) ) {
  $items_order = json_decode( stripslashes( $_COOKIE['char_order'] ), true );
}
reset( $items_order );

if ( empty( $current_journal ) ) {
  // Set default field settings
  $current_journal = [
    'name'             => [ 'order' => 1,  'label' => __( 'Name', WPGENT_DOMAIN ),                       'visible' => true  ],
    'first_name'       => [ 'order' => 1,  'label' => __( 'First Name', WPGENT_DOMAIN )                                     ],
    'middle_name'      => [ 'order' => 2,  'label' => __( 'Middle Name', WPGENT_DOMAIN )                                    ],
    'last_name'        => [ 'order' => 3,  'label' => __( 'Last Name', WPGENT_DOMAIN )                                      ],
    'nickname'         => [ 'order' => 2,  'label' => __( 'Nickname', WPGENT_DOMAIN ),                   'visible' => true  ],
    'aliases'          => [ 'order' => 3,  'label' => __( 'Alias(es)', WPGENT_DOMAIN ),                  'visible' => true  ],
    'display_name'     => [ 'order' => 4,  'label' => __( 'Display Name', WPGENT_DOMAIN ),               'visible' => true  ],
    'separator_line_1' => [ 'order' => 5,                                                                'visible' => true  ],
    'image'            => [ 'order' => 6,  'label' => __( 'Avatar Image', WPGENT_DOMAIN ),               'visible' => true  ],
    'role'             => [ 'order' => 7,  'label' => __( 'Role', WPGENT_DOMAIN ),                       'visible' => true  ],
    'gender'           => [ 'order' => 8,  'label' => __( 'Gender', WPGENT_DOMAIN ),                     'visible' => true  ],
    'nationality'      => [ 'order' => 9,  'label' => __( 'Nationality', WPGENT_DOMAIN ),                'visible' => true  ],
    'birth_and_death'  => [ 'order' => 10                                                                                   ],
    'birth_date'       => [                'label' => __( 'Date of birth', WPGENT_DOMAIN ),              'visible' => true  ],
    'died_date'        => [                'label' => __( 'Date of death', WPGENT_DOMAIN ),              'visible' => true  ],
    'separator_line_2' => [ 'order' => 11,                                                               'visible' => true  ],
    'standing'         => [ 'order' => 12, 'label' => __( 'Occupation / Standing', WPGENT_DOMAIN ),      'visible' => true  ],
    'biography'        => [ 'order' => 13, 'label' => __( 'Biographical Summary', WPGENT_DOMAIN ),       'visible' => true  ],
    'history'          => [ 'order' => 14, 'label' => __( 'More detailed life history', WPGENT_DOMAIN ), 'visible' => true  ],
    'secret_info'      => [ 'order' => 15, 'label' => __( 'Secret Information', WPGENT_DOMAIN ),         'visible' => false ],
    'note'             => [ 'order' => 16, 'label' => __( 'Creator&#039;s note', WPGENT_DOMAIN ),        'visible' => false ],
    'tags'             => [ 'order' => 17, 'label' => __( 'Tags', WPGENT_DOMAIN ),                       'visible' => false ],
    'publish'          => [ 'order' => 18, 'label' => __( 'Publish', WPGENT_DOMAIN )                                        ],
  ];
}

$sort_fields = [];
$field_length = count( $current_journal );
foreach( (array) $current_journal as $_field => $_opts ) {
  $sort_fields[$_field] = ! empty( $_opts['order'] ) ? $_opts['order'] : $field_length++;
}
array_multisort( $sort_fields, SORT_ASC, $current_journal );

/*
 * Retrieve attributes for specific field per tab (:> タブごとに指定フィールド用属性値を取得
 */
function get_attr( $field_name, $attribute, $default, $var_type = 1 ) {
  $_plotter = get_query_var( 'plotter', [] );
  $journal_options = @$_plotter['current_journal'] ?: [];
  if ( isset( $journal_options[$field_name][$attribute] ) && ! empty( $journal_options[$field_name][$attribute] ) ) {
    $_attr = $journal_options[$field_name][$attribute];
  } else {
    $_attr = $default;
  }
  switch ( $attribute ) {
    case 'order':
      if ( $var_type == 1 ) {
        $ret_attr = 'style="order: '. (int) $_attr .'"';
      } else {
        // for tabindex
        $ret_attr = (int) $_attr;
      }
      break;
    case 'visible':
      if ( $var_type == 1 ) {
        $ret_attr = $_attr ? '' : 'pseudo-hide';
      } else
      if ( $var_type == 2 ) {
        $ret_attr = $_attr ? '' : 'pseudo-hide';
      } else
      if ( $var_type == 3 ) {
        if ( $_attr ) {
          $ret_attr  = '<button type="button" class="btn btn-default btn-visibility"><i class="plt-eye"></i></button>';
          $ret_attr .= '<input type="hidden" name="publish_'. $field_name .'_visible" value="true" />';
        } else {
          $ret_attr  = '<button type="button" class="btn btn-default btn-visibility"><i class="plt-eye-blocked"></i></button>';
          $ret_attr .= '<input type="hidden" name="publish_'. $field_name .'_visible" value="false" />';
        }
      }
      break;
    case 'label':
    default:
      $ret_attr = $_attr;
      break;
  }
  return $ret_attr;
}
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class( 'flex-container column-2' ); ?>>
            <div class="x_panel panel-primary">
              <div class="x_title<?php if ( $enable_toolbox ) : ?> with-toolbox<?php endif; ?>">
                <h3><i class="plt-user-plus blue"></i> <?= __( 'Create Character', WPGENT_DOMAIN ) ?></h3>
<?php if ( $enable_toolbox ) : ?>
                <ul class="panel_toolbox">
                  <li<?php if ( $default_active_tab == 1 ) : ?> class="active"<?php endif; ?>><a href="#view-char" class="readonly-mode" data-toggle="tab" title="<?= __( 'Switch to Readonly', WPGENT_DOMAIN ) ?>"><i class="plt-file-eye"></i></a></li>
                  <li<?php if ( $default_active_tab == 2 ) : ?> class="active"<?php endif; ?>><a href="#edit-char" class="edit-mode" data-toggle="tab" title="<?= __( 'Switch to Editor', WPGENT_DOMAIN ) ?>"><i class="plt-pencil7"></i></a></li>
                  <li<?php if ( $default_active_tab == 3 ) : ?> class="active"<?php endif; ?>><a href="#settings" class="setting-panel" data-toggle="tab" title="<?= __( 'Setting Character Fields', WPGENT_DOMAIN ) ?>"><i class="plt-cog2"></i></a></li>
                </ul>
<?php endif; ?>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form id="character-creation" class="form-horizontal form-label-left withValidator" method="post" autocomplete="off" novalidate>
                  <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                  <input type="hidden" name="source_id" value="<?= esc_attr( $current_source_id ) ?>">
                  <input type="hidden" name="character_id" value="">
                  <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="">
                  <?php wp_nonce_field( $page_name . '-creation_' . $current_user_id, '_token', true, true ); ?>

                  <div class="tab-content">
<?php /* Tab-1 #view-char : start */ ?>
                    <div class="tab-pane fade<?php if ( $default_active_tab == 1 ) : ?> in active<?php endif; ?>" id="view-char">
                      <div class="flex-cols-container">
                        <div class="item-name form-group <?= get_attr( 'name', 'visible', '', 1 ) ?>" <?= get_attr( 'name', 'order', 1 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'name', 'label', __( 'Name', WPGENT_DOMAIN ) ) ?> <span class="required"></span></label>
                          <div class="col-md-10 col-sm-10 col-xs-12 _flex-rows-container">
<?php /*
                            <div class="inline-cols"><p id="pv-first_name" class="form-control">First Name</p></div>
                            <div class="inline-cols"><p id="pv-middle_name" class="form-control">Middle Name</p></div>
                            <div class="inline-cols"><p id="pv-last_name" class="form-control">Last Name</p></div>
*/ ?>
                            <p id="pv-full_name" class="form-control">Firstname Middlename Lastname</p>
                          </div>
                        </div><!-- /.item-name -->
                        <div class="item-nickname form-group <?= get_attr( 'nickname', 'visible', '', 1 ) ?>" <?= get_attr( 'nickname', 'order', 2 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'nickname', 'label', __( 'Nickname', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-nickname" class="form-control">Nickname</p>
                          </div>
                        </div><!-- /.item-nickname -->
                        <div class="item-aliases form-group <?= get_attr( 'aliases', 'visible', '', 1 ) ?>" <?= get_attr( 'aliases', 'order', 3 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'aliases', 'label', __( 'Alias(es)', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-aliases" class="form-control">Alias(es)</p>
                          </div>
                        </div><!-- /.item-aliases -->
                        <div class="item-display_name form-group <?= get_attr( 'display_name', 'visible', '', 1 ) ?>" <?= get_attr( 'display_name', 'order', 4 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'display_name', 'label', __( 'Display Name', WPGENT_DOMAIN ) ) ?> <span class="required"></span></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-display_name" class="form-control">Full Name</p>
                          </div>
                        </div><!-- /.item-display_name -->
                        <div class="item-role form-group <?= get_attr( 'role', 'visible', '', 1 ) ?>" <?= get_attr( 'role', 'order', 5 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'role', 'label', __( 'Role', WPGENT_DOMAIN ) ) ?> <span class="required"></span></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-role" class="form-control">Protagonist</p>
                          </div>
                        </div><!-- /.item-role -->
                        <div class="form-group <?= get_attr( 'separator_line_1', 'visible', '', 1 ) ?>" <?= get_attr( 'separator_line_1', 'order', 6 ) ?>>
                          <div class="separator-line"></div>
                        </div><!-- /separator_line_1 -->
                        <div class="item-avatar_image form-group <?= get_attr( 'image', 'visible', '', 1 ) ?>" <?= get_attr( 'image', 'order', 7 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-6"><?= get_attr( 'image', 'label', __( 'Avatar Image', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-4 col-sm-4 col-xs-6">
                            <span class="thumbnail current-view-image">
                              <img src="/assets/uploads/no-avatar.png" class="img-responsive img-rounded" />
                            </span>
                          </div>
                        </div><!-- /.item-avatar -->
                        <div class="item-gender form-group <?= get_attr( 'gender', 'visible', '', 1 ) ?>" <?= get_attr( 'gender', 'order', 8 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'gender', 'label', __( 'Gender', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-gender" class="form-control">male</p>
                          </div>
                        </div><!-- /.item-gender -->
                        <div class="item-nationality form-group <?= get_attr( 'nationality', 'visible', '', 1 ) ?>" <?= get_attr( 'nationality', 'order', 9 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'nationality', 'label', __( 'Nationality', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-nationality" class="form-control">Nationality, Affiliation or belonging faction etc.</p>
                          </div>
                        </div><!-- /.item-nationality -->
                        <div class="item-birth_and_death form-group <?= get_attr( 'birth_and_death', 'visible', '', 1 ) ?>" <?= get_attr( 'birth_and_death', 'order', 10 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-6 <?= get_attr( 'birth_date', 'visible', '', 1 ) ?>"><?= get_attr( 'birth_date', 'label', __( 'Date of birth', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-4 col-sm-4 col-xs-6 <?= get_attr( 'birth_date', 'visible', '', 1 ) ?>">
                            <p id="pv-birth_date" class="form-control">Month/Day/Year</p>
                          </div>
                          <label class="control-label col-md-2 col-sm-2 col-xs-6 <?= get_attr( 'died_date', 'visible', '', 1 ) ?>"><?= get_attr( 'died_date', 'label', __( 'Date of death', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-4 col-sm-4 col-xs-6 <?= get_attr( 'died_date', 'visible', '', 1 ) ?>">
                            <p id="pv-died_date" class="form-control">Month/Day/Year</p>
                          </div>
                        </div><!-- /.item-birth_and_death -->
                        <div class="form-group <?= get_attr( 'separator_line_2', 'visible', '', 1 ) ?>" <?= get_attr( 'separator_line_2', 'order', 11 ) ?>>
                          <div class="separator-line"></div>
                        </div><!-- /separator_line_2 -->
                        <div class="item-standing form-group <?= get_attr( 'standing', 'visible', '', 1 ) ?>" <?= get_attr( 'standing', 'order', 12 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'standing', 'label', __( 'Occupation / Standing', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-standing" class="form-control">Occupation, (social) standing or position in this story etc.</p>
                          </div>
                        </div><!-- /.item-standing -->
                        <div class="item-biography form-group <?= get_attr( 'biography', 'visible', '', 1 ) ?>" <?= get_attr( 'biography', 'order', 13 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'biography', 'label', __( 'Biographical Summary', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-biography" class="form-control">This biography or personality should be published as official information</p>
                          </div>
                        </div><!-- /.item-biography -->
                        <div class="item-history form-group <?= get_attr( 'history', 'visible', '', 1 ) ?>" <?= get_attr( 'history', 'order', 14 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'history', 'label', __( 'More detailed life history', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-history" class="form-control">This history that may be published as official information</p>
                          </div>
                        </div><!-- /.item-history -->
                        <div class="form-group" style="order: 90">
                          <div class="separator-line"></div>
                        </div>
                        <div class="item-secret_info form-group" style="order: 91">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'secret_info', 'label', __( 'Secret Information', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-secret_info" class="form-control">Confidential information not disclosed</p>
                          </div>
                        </div><!-- /.item-secret_info -->
                        <div class="item-note form-group" style="order: 92">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'note', 'label', __( 'Creator&#039;s note', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-note" class="form-control">Creator&#039;s notes will be confidential</p>
                          </div>
                        </div><!-- /.item-note -->
                        <div class="item-tags form-group <?= get_attr( 'tags', 'visible', '', 1 ) ?>" style="order: 93">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12"><?= get_attr( 'tags', 'label', __( "Tags", WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <p id="pv-tags" class="form-control">Tag keywords for distinguishable the character</p>
                          </div>
                        </div><!-- /.item-tags -->
                      </div><!-- /.flex-cols-container -->
                    </div><!-- /#view-char.tab-pane -->
<?php /* Tab-1 #view-char : end */ ?>
<?php /* Tab-2 #edit-char : start */ ?>
                    <div class="tab-pane fade<?php if ( $default_active_tab == 2 ) : ?> in active<?php endif; ?>" id="edit-char">
                      <div class="flex-cols-container">
                        <div class="item-name form-group" <?= get_attr( 'name', 'order', 1 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first_name"><?= get_attr( 'name', 'label', __( 'Name', WPGENT_DOMAIN ) ) ?> <span class="required"></span></label>
                          <div class="col-md-10 col-sm-10 col-xs-12 flex-rows-container">
                            <div class="inline-cols" <?= get_attr( 'first_name', 'order', 1 ) ?>>
                              <input type="text" id="first_name" name="first_name" class="form-control" placeholder="<?= get_attr( 'first_name', 'label', __( 'First Name', WPGENT_DOMAIN ) ) ?>" value="" tabindex="<?= get_attr( 'first_name', 'order', 1, 2 ) ?>" autocomplete="nope">
                            </div>
                            <div class="inline-cols" <?= get_attr( 'middle_name', 'order', 2 ) ?>>
                              <input type="text" id="middle_name" name="middle_name" class="form-control" placeholder="<?= get_attr( 'middle_name', 'label', __( 'Middle Name', WPGENT_DOMAIN ) ) ?>" value="" tabindex="<?= get_attr( 'middle_name', 'order', 2, 2 ) ?>" autocomplete="nope">
                            </div>
                            <div class="inline-cols" <?= get_attr( 'last_name', 'order', 3 ) ?>>
                              <input type="text" id="last_name" name="last_name" class="form-control" placeholder="<?= get_attr( 'last_name', 'label', __( 'Last Name', WPGENT_DOMAIN ) ) ?>" value="" tabindex="<?= get_attr( 'last_name', 'order', 3, 2 ) ?>" autocomplete="nope">
                            </div>
                          </div><!-- /.flex-rows-container -->
                          <input type="hidden" id="full_name" name="full_name" placeholder="<?= get_attr( 'name', 'label', __( 'Name', WPGENT_DOMAIN ) ) ?>" value="">
                        </div><!-- /.item-name -->
                        <div class="item-nickname form-group <?= get_attr( 'nickname', 'visible', '', 2 ) ?>" <?= get_attr( 'nickname', 'order', 2 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nickname"><?= get_attr( 'nickname', 'label', __( 'Nickname', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <input type="text" id="nickname" name="nickname" class="form-control" placeholder="<?= get_attr( 'nickname', 'label', __( 'Nickname', WPGENT_DOMAIN ) ) ?>" value="" tabindex="<?= get_attr( 'nickname', 'order', 2, 2 ) + 2 ?>" autocomplete="nope">
                          </div>
                        </div><!-- /.item-nickname -->
                        <div class="item-aliases form-group <?= get_attr( 'aliases', 'visible', '', 2 ) ?>" <?= get_attr( 'aliases', 'order', 3 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="aliases"><?= get_attr( 'aliases', 'label', __( 'Alias(es)', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <input type="text" id="aliases" name="aliases" class="form-control" placeholder="<?= get_attr( 'aliases', 'label', __( 'Alias(es)', WPGENT_DOMAIN ) ) ?>" value="" tabindex="<?= get_attr( 'aliases', 'order', 3, 2 ) + 2 ?>">
                          </div>
                        </div><!-- /.item-aliases -->
                        <div class="item-display_name form-group <?= get_attr( 'display_name', 'visible', '', 2 ) ?>" <?= get_attr( 'display_name', 'order', 4 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="display_name"><?= get_attr( 'display_name', 'label', __( 'Display Name', WPGENT_DOMAIN ) ) ?> <span class="required"></span></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <div class="input-group input-group-no-margin">
                              <select id="display_name" name="display_name" class="form-control editable-select" placeholder="<?= get_attr( 'display_name', 'label', __( 'Display Name', WPGENT_DOMAIN ) ) ?>" tabindex="<?= get_attr( 'display_name', 'order', 4, 2 ) + 2 ?>" autocomplete="nope">
<?php /*
                                <option>Full Name</option>
                                <option>Nickname</option>
                                <option>Alias</option>
*/ ?>
                              </select>
                              <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-erase" disabled><i class="plt-cross2"></i></button>
                              </span>
                            </div><!-- /.input-group -->
                          </div>
                        </div><!-- /.item-display_name -->
                        <div class="item-role form-group <?= get_attr( 'role', 'visible', '', 2 ) ?>" <?= get_attr( 'role', 'order', 5 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="role"><?= get_attr( 'role', 'label', __( 'Role', WPGENT_DOMAIN ) ) ?> <span class="required"></span></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <div class="input-group input-group-no-margin">
                              <select id="role" name="role" class="form-control editable-select" placeholder="<?= __( 'The role in this story', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'role', 'order', 5, 2 ) + 2 ?>">
<?php foreach ( $define_char_roles as $_char_role ) : ?>
                                <option><?= $_char_role ?></option>
<?php endforeach; ?>
                              </select>
                              <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-erase" disabled><i class="plt-cross2"></i></button>
                              </span>
                            </div><!-- /.input-group -->
                          </div>
                        </div><!-- /.item-role -->
                        <div class="form-group <?= get_attr( 'separator_line_1', 'visible', '', 2 ) ?>" <?= get_attr( 'separator_line_1', 'order', 6 ) ?>>
                          <div class="separator-line"></div>
                        </div><!-- /separator_line_1 -->
                        <div class="item-avatar_image form-group" <?= get_attr( 'image', 'visible', '', 2 ) ?>" <?= get_attr( 'image', 'order', 7 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="upload-image"><?= get_attr( 'image', 'label', __( 'Avatar Image', WPGENT_DOMAIN ) ) ?></label>
                          <div id="image-uploader" class="col-md-10 col-sm-10 col-xs-12">
                            <div class="col-sm-6 image-preview-container">
                              <div class="thumbnail current-image-preview">
                                <img src="/assets/uploads/no-avatar.png" class="img-responsive img-rounded" />
                              </div>
                              <div class="arrow-right hide"></div>
                              <div class="thumbnail new-image-preview hide">
                              </div>
                            </div><!-- /.image-preview-container -->
                            <div class="col-sm-6 image-ctrl-container">
                              <div class="input-group">
                                <label class="input-group-btn">
                                  <span id="upload-image" class="btn btn-default" title="<?= __( 'Choose File', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'image', 'order', 7, 2 ) ?>">
                                    <i class="plt-cloud-upload"></i><input type="file" name="assign_image" id="assign-image" class="hide" />
                                  </span>
                                </label>
                                <input type="text" id="preview-upfile" class="form-control" readonly="readonly" placeholder="<?= __( 'Choose your local image', WPGENT_DOMAIN ); ?>" value="" disabled />
                              </div>
                              <p class="help-block"><?php printf( __( 'Uploadable max file size: %s', WPGENT_DOMAIN ), $max_upload_size ); ?></p>
                              <div class="image-ctrl-buttons">
                                <button type="button" id="choose-image" name="choose_image" class="btn btn-default hide"><i class="plt-images3"></i> <?= __( 'Choose from Gallery', WPGENT_DOMAIN ) ?></button>
                                <button type="button" id="crop-image" name="crop_image" class="btn btn-default hide"><i class="plt-crop2"></i> <?= __( 'Crop Image', WPGENT_DOMAIN ) ?></button>
                                <button type="button" id="remove-image" name="remove_image" class="btn btn-default hide"><i class="plt-checkbox-unchecked2 gray"></i> <?= __( 'Remove', WPGENT_DOMAIN ) ?></button>
                              </div>
                              <input type="hidden" name="remove_image_action" id="remove-image-action" value="1" disabled />
                            </div>
                          </div>
                        </div><!-- /.item-avatar -->
                        <div class="item-gender form-group <?= get_attr( 'gender', 'visible', '', 2 ) ?>" <?= get_attr( 'gender', 'order', 8 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="gender"><?= get_attr( 'gender', 'label', __( 'Gender', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <div class="input-group input-group-no-margin">
                              <select id="gender" name="gender" class="form-control editable-select width-fit" placeholder="<?= __( 'Choose gender or undefined', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'gender', 'order', 8, 2 ) ?>" autocomplete="nope">
                                <option value="male">&lt;i class="plt-male"&gt;&lt;/i&gt; <?= __( 'Male', WPGENT_DOMAIN ) ?></option>
                                <option value="female">&lt;i class="plt-female"&gt;&lt;/i&gt; <?= __( 'Female', WPGENT_DOMAIN ) ?></option>
                                <option value="other">&lt;i class="plt-question6"&gt;&lt;/i&gt; <?= __( 'Other', WPGENT_DOMAIN ) ?></option>
                              </select>
                              <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-erase" disabled><i class="plt-cross2"></i></button>
                              </span>
                            </div><!-- /.input-group -->
                          </div>
                        </div><!-- /.item-gender -->
                        <div class="item-nationality form-group <?= get_attr( 'nationality', 'visible', '', 2 ) ?>" <?= get_attr( 'nationality', 'order', 9 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="nationality"><?= get_attr( 'nationality', 'label', __( 'Nationality', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <input type="text" id="nationality" name="nationality" class="form-control" placeholder="<?= __( 'Nationality, Affiliation or belonging faction etc.', WPGENT_DOMAIN ) ?>" value="" tabindex="<?= get_attr( 'nationality', 'order', 9, 2 ) ?>">
                          </div>
                        </div><!-- /.item-nationality -->
                        <div class="item-birth_and_death form-group <?= get_attr( 'birth_and_death', 'visible', '', 2 ) ?>" <?= get_attr( 'birth_and_death', 'order', 10 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-6 <?= get_attr( 'birth_date', 'visible', '', 2 ) ?>" for="birth_date"><?= get_attr( 'birth_date', 'label', __( 'Date of birth', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-4 col-sm-4 col-xs-6 <?= get_attr( 'birth_date', 'visible', '', 2 ) ?>">
                            <input type="text" id="birth_date" name="birth_date" class="form-control" placeholder="<?= __( 'Month/Day/Year', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'birth_and_death', 'order', 10, 2 ) ?>">
                          </div>
                          <label class="control-label col-md-2 col-sm-2 col-xs-6 <?= get_attr( 'died_date', 'visible', '', 2 ) ?>" for="died_date"><?= get_attr( 'died_date', 'label', __( 'Date of death', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-4 col-sm-4 col-xs-6 <?= get_attr( 'died_date', 'visible', '', 2 ) ?>">
                            <input type="text" id="died_date" name="died_date" class="form-control" placeholder="<?= __( 'Month/Day/Year', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'birth_and_death', 'order', 10, 2 ) + 1 ?>">
                          </div>
                        </div><!-- /.item-birth_and_death -->
                        <div class="form-group <?= get_attr( 'separator_line_2', 'visible', '', 2 ) ?>" <?= get_attr( 'separator_line_2', 'order', 11 ) ?>>
                          <div class="separator-line"></div>
                        </div><!-- /separator_line_2 -->
                        <div class="item-standing form-group <?= get_attr( 'standing', 'visible', '', 2 ) ?>" <?= get_attr( 'standing', 'order', 12 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="standing"><?= get_attr( 'standing', 'label', __( 'Occupation / Standing', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <textarea id="standing" name="standing" class="form-control" rows="2" placeholder="<?= __( 'Occupation, (social) standing or position in this story etc.', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'standing', 'order', 12, 2 ) ?>"></textarea>
                          </div>
                        </div><!-- /.item-standing -->
                        <div class="item-biography form-group <?= get_attr( 'biography', 'visible', '', 2 ) ?>" <?= get_attr( 'biography', 'order', 13 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="biography"><?= get_attr( 'biography', 'label', __( 'Biographical Summary', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <textarea id="biography" name="biography" class="form-control" rows="2" placeholder="<?= __( 'This biography or personality should be published as official information', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'biography', 'order', 13, 2 ) ?>"></textarea>
                          </div>
                        </div><!-- /.item-biography -->
                        <div class="item-history form-group <?= get_attr( 'history', 'visible', '', 2 ) ?>" <?= get_attr( 'history', 'order', 14 ) ?>>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="history"><?= get_attr( 'history', 'label', __( 'More detailed life history', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <textarea id="history" name="history" class="form-control" rows="2" placeholder="<?= __( 'This history that may be published as official information', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'history', 'order', 14, 2 ) ?>"></textarea>
                          </div>
                        </div><!-- /.item-history -->
                        <div class="form-group" style="order: 90">
                          <div class="separator-line"></div>
                        </div>
                        <div class="item-secret_info form-group" style="order: 91">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="secret_info"><?= get_attr( 'secret_info', 'label', __( 'Secret Information', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <textarea id="secret_info" name="secret_info" class="form-control" rows="2" placeholder="<?= __( 'Confidential information not disclosed', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'secret_info', 'order', 91, 2 ) ?>"></textarea>
                          </div>
                        </div><!-- /.item-secret_info -->
                        <div class="item-note form-group" style="order: 92">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="note"><?= get_attr( 'note', 'label', __( 'Creator&#039;s note', WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <textarea id="note" name="note" class="form-control" rows="2" placeholder="<?= __( 'Creator&#039;s notes will be confidential', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'note', 'order', 92, 2 ) ?>"></textarea>
                          </div>
                        </div><!-- /.item-note -->
                        <div class="item-tags form-group <?= get_attr( 'tags', 'visible', '', 2 ) ?>" style="order: 93">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="tags"><?= get_attr( 'tags', 'label', __( "Tags", WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <!-- textarea id="tags" name="tags" class="form-control" rows="2" placeholder="<?= __( 'Tag keywords for distinguishable the character', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'tags', 'order', 93, 2 ) ?>"></textarea -->
                            <input type="text" id="tags" name="tags" class="form-control" placeholder="<?= __( 'Tag keywords for distinguishable the character', WPGENT_DOMAIN ) ?>" tabindex="<?= get_attr( 'tags', 'order', 93, 2 ) ?>" />
                            <p class="help-block"><?php printf( __( 'Up to %d tags can added per character.', WPGENT_DOMAIN ), $max_tags ) ?></p>
                          </div>
                        </div><!-- /.item-tags -->
                        <div class="item-publish form-group" style="order: 94">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="publish"><?= get_attr( 'publish', 'label', __( "Publish", WPGENT_DOMAIN ) ) ?></label>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                            <label class="toggle-switch">
                              <input type="checkbox" id="publish" name="publish" class="js-switch" tabindex="<?= get_attr( 'publish', 'order', 94, 2 ) ?>" />
                            </label>
                            <p class="help-block"><?= __( 'The contents of confidential items are not displayed even when it is published.', WPGENT_DOMAIN ) ?></p>
                          </div>
                        </div><!-- /.item-publish -->
                      </div><!-- /.flex-cols-container -->
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                          <button type="button" id="btn-cancel" class="btn btn-default"><?= __( 'Cancel Creation', WPGENT_DOMAIN ) ?></button>
                          <button type="button" id="btn-cancel-edit" class="btn btn-default hide"><?= __( 'Cancel Edit', WPGENT_DOMAIN ) ?></button>
                          <button type="button" id="btn-remove" class="btn btn-dark hide"><?= __( 'Remove Character', WPGENT_DOMAIN ) ?></button>
                          <button type="button" id="btn-create" class="btn btn-primary"><?= __( 'Create Character', WPGENT_DOMAIN ) ?></button>
                          <button type="button" id="btn-update" class="btn btn-primary hide"><?= __( 'Save Changes', WPGENT_DOMAIN ) ?></button>
                        </div>
                      </div>
                    </div><!-- /#edit-char.tab-pane -->
<?php /* Tab-2 #edit-char : end */ ?>
<?php /* Tab-3 #settings : start */ ?>
                    <div class="tab-pane fade<?php if ( $default_active_tab == 3 ) : ?> in active<?php endif; ?>" id="settings">
                      <p class="help-block"><?= __( 'In this settings, You can override the label names displayed as field name, modify the order of several fields, change hide or show of fields, and add new custom fields. You should be to commit after changes.', WPGENT_DOMAIN ) ?></p>
                      <div class="flex-cols-container sortable-container"><!-- sortable contents -->
<?php foreach ( $current_journal as $_field => $_options ) {
        if ( in_array( $_field, [ 'first_name', 'middle_name', 'last_name' ], true ) ) {
          continue;
        }
        switch ( $_field ) {
          case 'name': ?>
                        <div class="item-name form-group disabled-sort" data-sort-id="name">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="set-first_name"><?= __( 'Name', WPGENT_DOMAIN ) ?> <span class="required"></span></label>
                          <div class="col-md-9 col-sm-9 col-xs-11">
                            <input type="text" id="set-name" name="label_name" class="form-control" value="<?= get_attr( 'name', 'label', __( 'Name', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Name', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'name', 'visible', true, 3 ) ?>
                          </div>
                          <div class="col-md-9 col-sm-9 col-xs-11 col-md-offset-2 flex-rows-container sortable-horizontal-container">
<?php       foreach ( $current_journal as $_in_field => $_in_options ) {
              switch ( $_in_field ) {
                case 'first_name': ?>
                            <div class="inline-cols input-group" data-sort-id="first_name">
                              <span class="input-group-addon"><i class="plt-transmission2 gray"></i></span>
                              <input type="text" id="set-first_name" name="label_first_name" class="form-control" value="<?= get_attr( 'first_name', 'label', __( 'First Name', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'First Name', WPGENT_DOMAIN ) ?>" />
                            </div>
<?php             break;
                case 'middle_name': ?>
                            <div class="inline-cols input-group" data-sort-id="middle_name">
                              <span class="input-group-addon"><i class="plt-transmission2 gray"></i></span>
                              <input type="text" id="set-middle_name" name="label_middle_name" class="form-control" value="<?= get_attr( 'middle_name', 'label', __( 'Middle Name', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Middle Name', WPGENT_DOMAIN ) ?>" />
                            </div>
<?php             break;
                case 'last_name': ?>
                            <div class="inline-cols input-group" data-sort-id="last_name">
                              <span class="input-group-addon"><i class="plt-transmission2 gray"></i></span>
                              <input type="text" id="set-last_name" name="label_last_name" class="form-control" value="<?= get_attr( 'last_name', 'label', __( 'Last Name', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Last Name', WPGENT_DOMAIN ) ?>" />
                            </div>
<?php             break;
              }
            } ?>
                          </div>
                        </div><!-- /.item-name -->
<?php       break;
          case 'nickname': ?>
                        <div class="item-nickname form-group" data-sort-id="nickname">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="set-nickname"><?= __( 'Nickname', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-9 col-sm-9 col-xs-11">
                            <input type="text" id="set-nickname" name="label_nickname" class="form-control" value="<?= get_attr( 'nickname', 'label', __( 'Nickname', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Nickname', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'nickname', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-nickname -->
<?php       break;
          case 'aliases': ?>
                        <div class="item-aliases form-group" data-sort-id="aliases">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="set-aliases"><?= __( 'Alias(es)', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-9 col-sm-9 col-xs-11">
                            <input type="text" id="set-aliases" name="label_aliases" class="form-control" value="<?= get_attr( 'aliases', 'label', __( 'Alias(es)', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Alias(es)', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'aliases', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-aliases -->
<?php       break;
          case 'display_name': ?>
                        <div class="item-display_name form-group" data-sort-id="display_name">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="set-display_name"><?= __( 'Display Name', WPGENT_DOMAIN ) ?> <span class="required"></span></label>
                          <div class="col-md-9 col-sm-9 col-xs-11">
                            <input type="text" id="set-display_name" name="label_display_name" class="form-control" value="<?= get_attr( 'display_name', 'label', __( 'Display Name', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Display Name', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <button type="button" class="btn btn-default btn-visibility" disabled><i class="plt-eye"></i></button>
                            <input type="hidden" name="publish_display_name_visible" value="true" />
                          </div>
                        </div><!-- /.item-display_name -->
<?php       break;
          case 'role': ?>
                        <div class="item-role form-group" data-sort-id="role">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="set-role"><?= __( 'Role', WPGENT_DOMAIN ) ?> <span class="required"></span></label>
                          <div class="col-md-9 col-sm-9 col-xs-11">
                            <input type="text" id="set-role" name="label_role" class="form-control" value="<?= get_attr( 'role', 'label', __( 'Role', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Role', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'role', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-role -->
<?php       break;
          case 'separator_line_1': ?>
                        <div class="form-group" data-sort-id="separator_line_1">
                          <div class="col-md-11 col-sm-11 col-xs-11">
                            <div class="separator-line"></div>
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'separator_line_1', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /#separator_line_1 -->
<?php       break;
          case 'image': ?>
                        <div class="item-avatar_image form-group" data-sort-id="image">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="set-image"><?= __( 'Avatar Image', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-9 col-sm-9 col-xs-11">
                            <input type="text" id="set-image" name="label_image" class="form-control" value="<?= get_attr( 'image', 'label', __( 'Avatar Image', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Avatar Image', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <button type="button" class="btn btn-default btn-visibility" disabled><i class="plt-eye"></i></button>
                            <input type="hidden" name="publish_image_visible" value="true" />
                          </div>
                        </div><!-- /.item-image -->
<?php       break;
          case 'gender': ?>
                        <div class="item-gender form-group" data-sort-id="gender">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="set-gender"><?= __( 'Gender', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-9 col-sm-9 col-xs-11">
                            <input type="text" id="set-gender" name="label_gender" class="form-control" value="<?= get_attr( 'gender', 'label', __( 'Gender', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Gender', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'gender', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-gender -->
<?php       break;
          case 'nationality': ?>
                        <div class="item-nationality form-group" data-sort-id="nationality">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="set-nationality"><?= __( 'Nationality', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-9 col-sm-9 col-xs-11">
                            <input type="text" id="set-nationality" name="label_nationality" class="form-control" value="<?= get_attr( 'nationality', 'label', __( 'Nationality', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Nationality', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'nationality', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-nationality -->
<?php       break;
          case 'birth_and_death': ?>
                        <div class="item-birth_and_death form-group" data-sort-id="birth_and_death">
                          <label class="control-label col-md-2 col-sm-2 col-xs-6" for="set-birth_date"><?= __( 'Date of birth', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-3 col-sm-3 col-xs-5">
                            <input type="text" id="set-birth_date" name="label_birth_date" class="form-control" value="<?= get_attr( 'birth_date', 'label', __( 'Date of birth', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Date of birth', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'birth_date', 'visible', true, 3 ) ?>
                          </div>
                          <label class="control-label col-md-2 col-sm-2 col-xs-6" for="set-died_date"><?= __( 'Date of death', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-3 col-sm-3 col-xs-5">
                            <input type="text" id="set-died_date" name="label_died_date" class="form-control" value="<?= get_attr( 'died_date', 'label', __( 'Date of death', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Date of death', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'died_date', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-birth_and_death -->
<?php       break;
          case 'separator_line_2': ?>
                        <div class="form-group" data-sort-id="separator_line_2">
                          <div class="col-md-11 col-sm-11 col-xs-11">
                            <div class="separator-line"></div>
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'separator_line_2', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /#separator_line_2 -->
<?php       break;
          case 'standing': ?>
                        <div class="item-standing form-group" data-sort-id="standing">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="set-standing"><?= __( 'Occupation / Standing', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-8 col-sm-8 col-xs-11">
                            <input type="text" id="set-standing" name="label_standing" class="form-control" value="<?= get_attr( 'standing', 'label', __( 'Occupation / Standing', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Occupation / Standing', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'standing', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-standing -->
<?php       break;
          case 'biography': ?>
                        <div class="item-biography form-group" data-sort-id="biography">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="set-biography"><?= __( 'Biographical Summary', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-8 col-sm-8 col-xs-11">
                            <input type="text" id="set-biography" name="label_biography" class="form-control" value="<?= get_attr( 'biography', 'label', __( 'Biographical Summary', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Biographical Summary', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'biography', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-biography -->
<?php       break;
          case 'history': ?>
                        <div class="item-history form-group" data-sort-id="history">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="set-history"><?= __( 'More detailed life history', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-8 col-sm-8 col-xs-11">
                            <input type="text" id="set-history" name="label_history" class="form-control" value="<?= get_attr( 'history', 'label', __( 'More detailed life history', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'More detailed life history', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'history', 'visible', true, 3 ) ?>
                          </div>
                        </div><!-- /.item-history -->
<?php       break;
          case 'extended_field': ?>
                        <div class="form-group disabled-sort">
                          <div class="col-md-3 col-sm-3 col-xs-12">
                            <input type="text" id="set-tmpl-label" class="form-control extended_field_label" value="<?= __( 'Field Label', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-8 col-sm-8 col-xs-11">
                            <input type="text" id="set-tmpl-type" class="form-control extended_field_type" value="<?= __( 'Field Type', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <button type="button" class="btn btn-default btn-add-field"><i class="plt-plus3"></i></button>
                          </div>
                        </div>
<?php       break;
          case 'secret_info': ?>
                        <div class="form-group disabled-sort">
                          <div class="separator-line"></div>
                        </div>
                        <div class="item-secret_info form-group disabled-sort" data-sort-id="secret_info">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="set-secret_info"><?= __( 'Secret Information', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-8 col-sm-8 col-xs-11">
                            <input type="text" id="set-secret_info" name="label_secret_info" class="form-control" value="<?= get_attr( 'secret_info', 'label', __( 'Secret Information', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Secret Information', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <button type="button" class="btn btn-default btn-visibility" disabled><i class="plt-eye-blocked"></i></button>
                            <input type="hidden" name="publish_secret_info_visible" value="false" />
                          </div>
                        </div><!-- /.item-secret_info -->
<?php       break;
          case 'note': ?>
                        <div class="item-note form-group disabled-sort" data-sort-id="note">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="set-note"><?= __( 'Creator&#039;s note', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-8 col-sm-8 col-xs-11">
                            <input type="text" id="set-note" name="label_note" class="form-control" value="<?= get_attr( 'note', 'label', __( 'Creator&#039;s note', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Creator&#039;s note', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <button type="button" class="btn btn-default btn-visibility" disabled><i class="plt-eye-blocked"></i></button>
                            <input type="hidden" name="publish_note_visible" value="false" />
                          </div>
                        </div><!-- /.item-note -->
<?php       break;
          case 'tags': ?>
                        <div class="item-tags form-group disabled-sort" data-sort-id="tags">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="set-tags"><?= __( 'Tags', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-8 col-sm-8 col-xs-11">
                            <input type="text" id="set-tags" name="label_tags" class="form-control" value="<?= get_attr( 'tags', 'label', __( 'Tags', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Tags', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <?= get_attr( 'tags', 'visible', false, 3 ) ?>
                          </div>
                        </div><!-- /.item-tags -->
<?php       break;
          case 'publish': ?>
                        <div class="item-publish form-group disabled-sort" data-sort-id="publish">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="set-publish"><?= __( 'Publish', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-8 col-sm-8 col-xs-11">
                            <input type="text" id="set-publish" name="label_publish" class="form-control" value="<?= get_attr( 'publish', 'label', __( 'Publish', WPGENT_DOMAIN ) ) ?>" data-default="<?= __( 'Publish', WPGENT_DOMAIN ) ?>" />
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-1">
                            <button type="button" class="btn btn-default btn-visibility" disabled><i class="plt-eye-blocked"></i></button>
                            <input type="hidden" name="publish_tags_visible" value="false" />
                          </div>
                        </div><!-- /.item-publish -->
<?php       break;
        }
      } /* endforeach */ ?>
                        <div class="ln_solid"></div>
                        <div class="form-group disabled-sort">
                          <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                            <button type="button" id="btn-reset" class="btn btn-default"><?= __( 'Reset', WPGENT_DOMAIN ) ?></button>
                            <button type="button" id="btn-add-field" class="btn btn-default" disabled><?= __( 'Add Field', WPGENT_DOMAIN ) ?></button>
                            <button type="button" id="btn-commit" class="btn btn-primary"><?= __( 'Commit', WPGENT_DOMAIN ) ?></button>
                          </div>
                        </div>
                      </div><!-- /.flex-cols-container -->
                    </div><!-- /#settings.tab-pane -->
<?php /* Tab-3 #settings : end */ ?>
                  </div><!-- /.tab-content -->
                </form>
              </div><!-- /.x_content -->
            </div><!-- /.x_panel.panel-primary -->

            <div class="x_panel panel-secondary">
              <div class="x_title">
                <h3><i class="plt-users4 blue"></i> <?= __( 'Character List', WPGENT_DOMAIN ) ?></h3>
                <?php get_template_part( 'partials/toolbox' ); ?>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div class="char-list">
<?php if ( empty( $journal_item_ids ) ) : ?>
                  <div class="no-list text-center">
                    <p class="help-block"><?= __( 'None', WPGENT_DOMAIN ) ?></p>
                  </div>
<?php else : 
        foreach ( $journal_default_items as $_item ) : ?>
                  <div class="list-item<?php if ( $_item['publish'] ) : ?> item-published<?php else : ?> item-private<?php endif; ?>" data-item-id="<?= esc_attr( $_item['id'] ) ?>">
                    <div class="thumbnail item-thumbnail">
<?php     if ( isset( $_item['images'][0]['url'] ) && ! empty( $_item['images'][0]['url'] ) ) {
            $thumb_src = $_item['images'][0]['url'];
          } else {
            $thumb_src = '/assets/uploads/no-avatar.png';
          } ?>
                      <img src="<?= esc_attr( $thumb_src ) ?>" class="img-responsive img-rounded" />
                    </div>
                    <div class="item-details">
                      <label class="item-name"><?= esc_html( $_item['display_name'] ) ?></label>
                      <div class="item-meta">
                        <span class="label label-default hide"><?= esc_html( $_item['role'] ) ?></span>
                        <span class="text-right"><?= __( 'Last Modified', WPGENT_DOMAIN ) ?>: <time class="last-updated" datetime="<?= esc_attr( $_item['updated_at'] ) ?>" title="<?= esc_attr( $_item['updated_at'] ) ?>"><?= esc_html( $_item['updated_at_htd'] ) ?></time></span>
                      </div>
                    </div>
                  </div>
<?php   endforeach;
      endif; ?>
                </div><!-- /.char-list -->
                <div class="list-ctrl">
<?php if ( count( $journal_item_ids ) > 10 ) : ?>
                  <button type="button" class="btn btn-default btn-sm" id="btn-load-more" tabindex="-1"><i class="plt-more2"></i> <?= __( 'Load More', WPGENT_DOMAIN ) ?></button>
<?php endif; ?>
<?php if ( count( $journal_item_ids ) > 1 ) : ?>
                  <span class="input-group input-group-sm input-group-no-margin">
                    <select id="sort-item-list" name="sort_item_list" class="form-control" tabindex="-1">
                      <option value="created"<?php if ( key( $items_order ) === 'created_at' ) : ?> selected="selected"<?php endif; ?>><?= __( 'Created', WPGENT_DOMAIN ) ?></option>
                      <option value="last_modified"<?php if ( key( $items_order ) === 'updated_at' ) : ?> selected="selected"<?php endif; ?>><?= __( 'Last Modified', WPGENT_DOMAIN ) ?></option>
                      <option value="name"<?php if ( key( $items_order ) === 'display_name' ) : ?> selected="selected"<?php endif; ?>><?= __( 'Name', WPGENT_DOMAIN ) ?></option>
                    </select>
                    <span class="input-group-btn">
<?php   $to_order = current( $items_order ) === 'asc' ? 'desc' : 'asc';
        $class_prefix = key( $items_order ) === 'display_name' ? 'plt-sort-alpha-' : ( $to_order === 'asc' ? 'plt-sort-numeric-' : 'plt-sort-numberic-' ); ?>
                      <button type="button" class="btn btn-default btn-sm" id="btn-sort-item" data-sort-by="<?= $to_order ?>" title="<?= __( 'Sort by', WPGENT_DOMAIN ) ?>"><i class="<?= $class_prefix . $to_order ?>"></i></button>
                    </span>
                  </span>
<?php endif; ?>
                </div>
              </div><!-- /.x_content -->
            </div><!-- /.x_panel.panel-secondary -->

          </div><!-- /.flex-container -->
        </div>
        <!-- /.right_col -->
