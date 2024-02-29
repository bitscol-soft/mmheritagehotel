  <!-- Modal -->
  <div class="modal fade" id="webcam-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="padding: 2px !important">
          <button type="button" class="close btn-sm web-cam-close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="text-align: center;">
            <div id="my_camera" style="width: 600px !important; height: 135px !important;display: inline;"></div>
            <div>
                <a href="javascript:void(0)" onClick="take_snapshot()" class="btn btn-success btn-sm"><i class="fa fa-camera"></i></a>
            </div>
            {{-- <a href="javascript:void(0)" onClick="reset()" class="btn btn-danger btn-sm"><i class="fa fa-close"></i></a> --}}
        </div>
        <div class="modal-footer" style="padding: 0 !important; margin: 0 !important">
          <button type="button" class="btn btn-secondary btn-sm web-cam-close">Close</button>
        </div>
      </div>
    </div>
  </div>
