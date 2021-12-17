<div id="listCommentModal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"id="commentCountModalLabel"><i class="fas fa-thumbs-up"></i> 532</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body">
                <nav class="navbar justify-content-between">
                    <div class="float-right">
                        <button id="btnAllComments" class="btn btn-link">(All)</button>
                        <button id="btnYourComments" class="btn btn-link">(You)</button>
                        <button id="btnPositiveComments" class="btn btn-link"><i class="fas fa-smile-beam fa-2x"></i></i></button>
                        <button id="btnNeutralComments" class="btn btn-link ml-1"><i class="fas fa-meh fa-2x"></i></button>
                        <button id="btnNegativeComments" class="btn btn-link ml-1"><i class="fas fa-angry fa-2x"></i></button>
                    </div>
                </nav>

                <div id="commentListContainer">
                    {{-- Container for comment list --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>