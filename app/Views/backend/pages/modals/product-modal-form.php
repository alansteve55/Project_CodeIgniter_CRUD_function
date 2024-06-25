<div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true" data-backfrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="<?= route_to('add-product')?>" method="post" id="add_product_form">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Large modal
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash()?>" class="ci_csfr_data">
                <div class="form-group">
                    <label for=""><b>Parent category</b></label>
                    <select name="category_id" id="" class="form-control">
                        <option value="">Uncategorized</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for=""><b>Product Name</b></label>
                    <input type="text" class="form-control" name="product_name" placeholder="Enter product name">
                    <span class="text-danger error-text product_name_error"></span>
                </div>
                <div class="form-group">
                    <label for=""><b>Unit Price</b></label>
                    <input type="text" class="form-control" name="unit_price" placeholder="Enter unit price">
                    <span class="text-danger error-text unit_price_error"></span>
                </div>
                <div class="form-group">
                    <label for=""><b>Unit Type</b></label>
                    <input type="text" class="form-control" name="unit_type" placeholder="Enter unit type">
                    <span class="text-danger error-text unit_type_error"></span>
                </div>
                <div class="form-group">
                    <label for=""><b>Stock Level</b></label>
                    <input type="text" class="form-control" name="stock_level" placeholder="Enter stock level">
                    <span class="text-danger error-text stock_level_error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-primary action">
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>