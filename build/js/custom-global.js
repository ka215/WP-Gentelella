/**
 * For Whole Story (Global Settings)
 */
$(document).ready(function() {
  
  var gf              = $("#globalSettings"),
      wls             = window.localStorage,
      currentSrcId    = Number( $('#change_source option:selected').val() );
  //storedSrcCache( currentSrcId );
  
  // Event handlers
  $('#change_source').on('change', function(){
    logger.debug( currentSrcId, $(this).val() );
    if ( $(this).val() === '' ) {
      // Add new story
      storedSrcCache( currentSrcId ); // 現ソースIDをローカルに保存
      gf.find('input[name="source_id"]').val('');
      gf.find('#source_name').val('');
      gf.find('input[id^="wh"]').val('');
      gf.find('#global-btn-add').removeClass('hide');
      gf.find('#global-btn-update').addClass('hide');
      gf.find('#global-btn-remove').addClass('hide');
    } else {
      // Switch another story
      var newSrcId = Number( $(this).val() );
      if ( currentSrcId != newSrcId ) {
        currentSrcId = newSrcId;
        // ajaxで新ソースIDのデータを取得する
        
        gf.find('#source_name').val('Retrive Title');
        gf.find('input[id^="wh"]').val('Retrive Data');
        
        // Cookie値の lastSource を新ソースIDに更新する
        
        storedSrcCache( currentSrcId ); // 新ソースIDをローカルに保存
      } else {
        restoreSrcCache( currentSrcId ); // 現ソースIDをロード
      }
      gf.find('input[name="source_id"]').val(currentSrcId);
      gf.find('#global-btn-remove').removeClass('hide');
      gf.find('#global-btn-update').removeClass('hide');
      gf.find('#global-btn-add').addClass('hide');
    }
  });
  
  $(document).on('click', 'button.btn[id^="global-btn"]', function(e){
    e.preventDefault();
    var action = $(this).attr('id').replace('global-btn-', '');
    if ( 'cancel' == action ) {
      
      return false;
    } else {
      $('#global-post-action').val( action );
      gf.submit();
    }
  });
  
  
  function storedSrcCache( sid ) {
    if ( sid == Number( gf.find('[name="source_id"]').val() ) ) {
      var src_data = {
        'source_id': currentSrcId,
        'source_name': gf.find('#source_name').val(),
        'who': gf.find('#who').val(),
        'what': gf.find('#what').val(),
        'where': gf.find('#where').val(),
        'when': gf.find('#when').val(),
        'why': gf.find('#why').val(),
        'team_writing': gf.find('#team_writing').val(),
        'permission': gf.find('#permission').val()
      };
      wls.setItem( 'plt_cursrc', JSON.stringify( src_data ) );
    }
  }
  
  function restoreSrcCache( sid ) {
    var src_data = JSON.parse( wls.getItem( 'plt_cursrc' ) );
    if ( sid == Number( src_data.source_id ) ) {
      gf.find('[name="source_id"]').val( sid );
      gf.find('#source_name').val( src_data.source_name );
      gf.find('#who').val( src_data.who );
      gf.find('#what').val( src_data.what );
      gf.find('#where').val( src_data.where );
      gf.find('#when').val( src_data.when );
      gf.find('#why').val( src_data.why );
      gf.find('#team_writing').val( src_data.team_writing );
      gf.find('#permission').val( src_data.permission );
    }
  }
  
  
  
});