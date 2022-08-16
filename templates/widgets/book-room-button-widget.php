<div class="d-flex justify-content-end">
    <div class=" w-25">
        <div class="col-auto">
            <form id="unb_book_room_form" onsubmit="return false">
                <div id="unb_book_room_alert" class="alert alert-danger alert-dismissible collapse" role="alert"></div>
                <div id="unb_book_room_suc" class="alert alert-success alert-dismissible collapse"></div>
                <div class="card">
                    <div class="card-body">
                        <label for="unb_room_check_in_form" class="form-label">Check In</label>
                        <input class="form-control mb-3" id='unb_room_check_in_form' required/> 
                        <label for="unb_room_check_out_form" class="form-label">Check Out</label>
                        <input class="form-control mb-3" id='unb_room_check_out_form' required/>
                        <label for="unb_room_num_visitors_form" class="form-label">Number of visitors</label>
                        <input type="number" class="form-control" id='unb_room_num_visitors_form' required/>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <?php
                            $nonce = wp_create_nonce("unb_book_room_nonce");
                        ?>
                        <input type="submit" class="btn btn-warning" data-nonce="<?= $nonce ?>" data-room-id="<?= $post_id ?>" onclick="unb_book_room_submit(this)" value="Add Order to Cart">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>