/**
 * For Global Settings
 */
$(document).ready(function() {
  
  var gf              = $("#globalSettings"),
      wls             = window.localStorage,
      currentSrcId    = Number( $('#change_source option:selected').val() );
  //storedSrcCache( currentSrcId );
  
  $('#change_source').on('change', function(){
    logger.debug( currentSrcId, $(this).val() );
    if ( $(this).val() === '' ) {
      // Add new story
      storedSrcCache( currentSrcId );
      gf.find('[type="text"]').val('');
      gf.find('#global-btn-add').removeClass('hide');
      gf.find('#global-btn-update').addClass('hide');
      gf.find('#global-btn-remove').addClass('hide');
    } else {
      // Switch another story
      var newSrcId = Number( $(this).val() );
      if ( currentSrcId != newSrcId ) {
        currentSrcId = newSrcId;
        // ajax
        storedSrcCache( currentSrcId );
      } else {
        restoreSrcCache( currentSrcId );
      }
      gf.find('#global-btn-remove').removeClass('hide');
      gf.find('#global-btn-update').removeClass('hide');
      gf.find('#global-btn-add').addClass('hide');
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