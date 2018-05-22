<?php
/**
 * Template part for displaying idea note content in page.php
 *
 * @package WordPress
 * @subpackage Plotter
 * @since 1.0
 * @version 1.0
 */
$_plotter = get_query_var( 'plotter', [] );
$page_name       = @$_plotter['page_name'] ?: '';
$current_user_id = @$_plotter['current_user_id'] ?: null;
$having_ideas    = @$_plotter['having_ideas'] ?: 0;
$default_ideas   = @$_plotter['default_ideas'] ?: [];

$enable_extension_field = false;
$default_open_tab = $having_ideas == 0 ? 1 : 2;
$use_pagination = false;

//var_dump( $default_ideas );
/*
$search_key = "abc AＢC　あいう　 　\r\n　𠮷 \n";
$_parse_keys = preg_split( '/[\s\p{Z}]++/u', $search_key );
foreach ( $_parse_keys as $_kw ) {
  if ( ! empty( $_kw ) ) {
var_dump( [ $_kw, strlen( $_kw ), mb_strlen( $_kw ), mb_strwidth( $_kw ) ] );
  }
}
*/
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div <?php post_class( 'flex-container' ); ?>>
<?php /*
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"> */ ?>
            <div class="x_panel panel-primary">
              <div class="x_title">
                <h3><i class="plt-lamp8 blue"></i> <?= __( 'Idea Note', WPGENT_DOMAIN ) ?></h3>
                <?php get_template_part( 'partials/toolbox' ); ?>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form id="ideasForm" class="form-horizontal form-label-left withValidator" method="post" novalidate>
                  <input type="hidden" name="from_page" value="<?= esc_attr( $page_name ) ?>">
                  <input type="hidden" name="post_action" id="<?= esc_attr( $page_name ) ?>-post-action" value="">
                  <?php wp_nonce_field( $page_name . '-form_' . $current_user_id, '_token', true, true ); ?>

                  <div class="idea-note-tabs" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="ideasTab" class="nav nav-tabs bar_tabs" role="tablist">
                      <li role="presentation"<?php if ( $default_open_tab == 1 ) : ?> class="active"<?php endif; ?>>
                        <a href="#tab-content1" role="tab" id="register-idea-tab" data-toggle="tab" aria-expanded="<?php if ( $default_open_tab == 1 ) : ?>true<?php else : ?>false<?php endif; ?>"><?= __( 'Stack an Idea', WPGENT_DOMAIN ) ?></a>
                      </li>
                      <li role="presentation"<?php if ( $default_open_tab == 2 ) : ?> class="active"<?php endif; ?>>
                        <a href="#tab-content2" role="tab" id="find-ideas-tab" data-toggle="tab" aria-expanded="<?php if ( $default_open_tab == 2 ) : ?>true<?php else : ?>false<?php endif; ?>"><?= __( 'Find the Ideas', WPGENT_DOMAIN ) ?></a>
                      </li>
                    </ul>
                    <div id="ideasTabContent" class="tab-content">
                      <div role="tabpanel" class="tab-pane fade<?php if ( $default_open_tab == 1 ) : ?> active in<?php endif; ?>" id="tab-content1" aria-labelledby="stack-idea-tab">

                        <p><?= __( 'Remember, by accumulating the best ideas, you can put it into practical use.', WPGENT_DOMAIN ) ?></p>
                        <div class="item form-group">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="item_name"><?= __( 'Title', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" id="idea_title" name="idea_title" class="form-control" placeholder="<?= __( 'You do not have to worry about no title', WPGENT_DOMAIN ) ?>" value="<?= esc_attr( '' ) ?>">
                          </div>
                        </div>
                        <div class="item form-group">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="item_name"><?= __( 'Content', WPGENT_DOMAIN ) ?> <span class="required"></span></label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea id="idea_content" name="idea_content" class="form-control" rows="3" placeholder="<?= __( 'Please fill in with freely what you came up', WPGENT_DOMAIN ) ?>" required="required"><?= nl2br( esc_textarea( '' ) ) ?></textarea>
                            <i id="idea_content_feedback" class="plt-warning form-control-feedback hide"></i>
                          </div>
                        </div>
<?php if ( $enable_extension_field ) : ?>
                        <div class="item form-group">
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" for="item_name"><?= __( 'Extension', WPGENT_DOMAIN ) ?></label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="idea_extension" name="idea_extension" class="form-control" placeholder="<?= __( 'You do not have to worry about no extension', WPGENT_DOMAIN ) ?>" value="<?= esc_attr( '' ) ?>">
                          </div>
                        </div>
<?php else : ?>
                        <input type="hidden" id="idea_extension" name="idea_extension" value="<?= esc_attr( '' ) ?>" />
<?php endif; ?>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                            <button type="button" id="cancel" class="btn btn-default hide"><?= __( 'Cancel', WPGENT_DOMAIN ) ?></button>
                            <button type="button" id="update-idea" class="btn btn-primary hide"><?= __( 'Update Idea', WPGENT_DOMAIN ) ?></button>
                            <button type="button" id="save-idea" class="btn btn-primary"><?= __( 'Save Idea', WPGENT_DOMAIN ) ?></button>
                          </div>
                        </div>

                      </div><!-- /#tab-content1 -->
                      <div role="tabpanel" class="tab-pane fade<?php if ( $default_open_tab == 2 ) : ?> active in<?php endif; ?>" id="tab-content2" aria-labelledby="find-ideas-tab">

                        <div class="form-group">
                          <div class="col-md-8 col-sm-10 col-xs-12 col-md-offset-2 col-sm-offset-1">
                            <div class="input-group">
                              <input type="text" id="search_keywords" name="search_keywords" class="form-control" placeholder="<?= __( 'Please enter any keywords you want finding', WPGENT_DOMAIN ) ?>" value="">
                              <span class="input-group-btn">
                                <button type="button" id="find-idea" class="btn btn-primary" title="<?= __( 'Find Idea', WPGENT_DOMAIN ) ?>"><i class="plt-search3"></i></button>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="text-center describe-finding<?php if ( $having_ideas == 0 ) : ?> hide<?php endif; ?>"><?php if ( $having_ideas > 0 ) : ?>
                          <p class="help-block"><b><?= __( 'Total number of ideas currently being stocked', WPGENT_DOMAIN ) ?></b><span><?= $having_ideas ?></span></p>
                        <?php endif; ?></div>
<?php if ( $use_pagination ) : ?>
                        <nav class="text-center">
                          <ul class="pagination pagination-sm">
                            <li><a href="#" aria-label="Move to first"><i class="plt-arrow-left4" aria-hidden="true"></i></a></li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#" aria-label="Move to last"><i class="plt-arrow-right4" aria-hidden="true"></i></a></li>
                          </ul>
                        </nav>
<?php endif; ?>
                        <div class="table-responsive<?php if ( $having_ideas == 0 ) : ?> hide<?php endif; ?>">
                          <table id="idea-list" class="table table-striped table-hover">
                            <thead>
                              <tr>
                                <th class="list-idea-cell" colspan="2"><?= __( 'Idea', WPGENT_DOMAIN ) ?></th>
                                <th class="list-date-cell"><?= __( 'Last Modified', WPGENT_DOMAIN ) ?></th>
                                <th class="list-ctrl-cell"></th>
                              </tr>
                            </thead>
                            <tbody data-total-ideas="<?= $having_ideas ?>">
<?php if ( $having_ideas > 0 ) {
        foreach ( $default_ideas as $_offset => $_idea ) : ?>
                              <tr data-idea-id="<?= esc_attr( $_idea['id'] ) ?>" data-offset="<?= $_offset ?>">
                                <td class="list-check-cell">
                                  <a href="#"><i class="plt-lamp8"></i></a>
                                  <!-- a href="#"><i class="plt-lamp7"></i></a -->
                                  <input type="hidden" name="selected_ideas[]" value="<?= esc_attr( $_idea['id'] ) ?>" />
                                </td>
                                <td class="list-idea-cell">
                                  <?php if ( ! empty( $_idea['title'] ) ) : ?><label><?= esc_html( $_idea['title'] ) ?></label><?php endif; ?>
                                  <?php if ( ! empty( $_idea['content'] ) ) : ?><p><?= nl2br( esc_html( $_idea['content'] ) ) ?></p><?php endif; ?>
                                </td>
                                <td class="list-date-cell"><span title="<?= esc_attr( $_idea['updated_at'] ) ?>" aria-label="<?= esc_attr( $_idea['updated_at'] ) ?>"><?php 
          echo human_time_diff( strtotime( $_idea['updated_at'] ), current_time( 'timestamp' ) ) .' '. __( 'ago', 'plotter' );
                                ?></span></td>
                                <td class="list-ctrl-cell">
                                  <button type="button" class="btn btn-default btn-sm edit-idea" title="<?= __( 'Edit Idea', WPGENT_DOMAIN ) ?>"><i class="plt-pencil7"></i></button>
                                  <button type="button" class="btn btn-default btn-sm hide-idea" title="<?= __( 'Invisible Idea', WPGENT_DOMAIN ) ?>"><i class="plt-eye-blocked"></i></button>
                                  <button type="button" class="btn btn-default btn-sm remove-idea" title="<?= __( 'Remove Idea', WPGENT_DOMAIN ) ?>"><i class="plt-cross2"></i></button>
                                </td>
                              </tr>
<?php   endforeach;
      } ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td class="list-more-cell" colspan="4"><i class="plt-more2"></i></td>
                              </tr>
                              <tr id="list-template" style="display: none" data-idea-id="%s" data-offset="%d">
                                <td class="list-check-cell">
                                  <a href="#"><i class="plt-lamp8"></i></a>
                                  <input type="hidden" name="selected_ideas[]" value="%s" />
                                </td>
                                <td class="list-idea-cell">%s</td>
                                <td class="list-date-cell"><span title="%s" aria-label="%s">%s</span></td>
                                <td class="list-ctrl-cell">
                                  <button type="button" class="btn btn-default btn-sm edit-idea" title="<?= __( 'Edit Idea', WPGENT_DOMAIN ) ?>"><i class="plt-pencil7"></i></button>
                                  <button type="button" class="btn btn-default btn-sm hide-idea" title="<?= __( 'Invisible Idea', WPGENT_DOMAIN ) ?>"><i class="plt-eye-blocked"></i></button>
                                  <button type="button" class="btn btn-default btn-sm remove-idea" title="<?= __( 'Remove Idea', WPGENT_DOMAIN ) ?>"><i class="plt-cross2"></i></button>
                                </td>
                              </tr>
                            </tfoot>
                          </table>
                        </div><!-- /.table-responsive -->
<?php if ( $having_ideas == 0 ) : ?>
                        <div id="no-idea">
                          <p class="help-block"><b><?= __( 'There are no ideas currently stocked.', WPGENT_DOMAIN ) ?></b></p>
                        </div>
<?php endif; ?>
                      </div><!-- /#tab-content2 -->
                    </div><!-- /.tab-content -->
                  </div><!-- /role:tabpanel -->

                </form>
              </div><!-- /.x_content -->
            </div><!-- /.x_panel -->
<?php /*
            </div><!-- /.col -->
          </div><!-- /.row --> */ ?>
          </div><!-- /.flex-container -->
        </div>
        <!-- /.right_col -->

