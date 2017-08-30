<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p>Delete Confirmation</p>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    {{ csrf_field() }}

                    <p>Are you sure you want to delete this location?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-red deleteConfirmation" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>