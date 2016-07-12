<div id="edit_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal" role="form" action="my_urls.php" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit URL</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="control-label col-sm-3" for="long_url">Long URL:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="long_url" name="long_url" placeholder="Long URL" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3" for="short_url">String:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="short_url_disabled" name="short_url_disabled" placeholder="String" required disabled="true">
              <input type="hidden" id="short_url" name="short_url">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <input type="submit" class="btn btn-primary" value="OK">
        </div>
      </div>
    </form>
  </div>
</div>
