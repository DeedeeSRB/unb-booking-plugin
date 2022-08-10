<div class="d-flex justify-content-end">
    <div class=" w-25">
        <div class="col-auto">
            <div id="unb_book_room_alert" class="alert alert-danger alert-dismissible collapse" role="alert"></div>
            <div id="unb_book_room_suc" class="alert alert-success alert-dismissible collapse"></div>
            <div class="card">
                <div class="card-body">
                    <label for="in" class="form-label">Check In</label>
                    <input class="form-control mb-3" id='in' /> 
                    <label for="out" class="form-label">Check Out</label>
                    <input class="form-control" id='out' />
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body text-center">
                    <?php
                        $nonce = wp_create_nonce("unb_book_room_nonce");
                    ?>
                    <button type="button" class="btn btn-warning" data-nonce="<?= $nonce ?>" data-room-id="<?= $post_id ?>" onclick="unb_book_room_submit(this)">Add Order to Cart</button>
                </div>
            </div>
        </div>
    </div>
</div>