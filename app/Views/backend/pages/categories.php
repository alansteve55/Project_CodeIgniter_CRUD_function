<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Categories</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home');?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Categories and Products
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        Categories
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-default btn-sm p-0" role="button" id="add_category_btn">
                            <i class="fa fa-plus-circle"></i>Add Category
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless table-hover table-stripe" id="categories-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category Label</th>
                            <th scope="col">Action</th>
                            <th scope="col">Ordering</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        Products
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-default btn-sm p-0" role="button" id="add_products_btn">
                            <i class="fa fa-plus-circle"></i>Add Product
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless table-hover table-stripe" id="products-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Unit price</th>
                            <th scope="col">Unit type</th>
                            <th scope="col">stock level</th>
                            <th scope="col">Action</th>
                            <th scope="col">ordering</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('modals/category-modal-form.php')?>
<?php include('modals/edit-category-modal-form.php')?>
<?php include('modals/product-modal-form.php')?>

<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
    <link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<?= $this->endSection() ?>

<?= $this->section('scripts')?>
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>

    <script>
        $(document).on('click','#add_category_btn',function(e){
            e.preventDefault();
            var modal = $('body').find('div#category-modal');
            var modal_title = 'Add category';
            var modal_btn_text = "Add";
            modal.find('.modal-title').html(modal_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('input-error-text').html('');
            modal.find('input[type="text"]').val('');
            modal.modal('show');
        }); 

        $('#add_category_form').on('submit',function(e){
            e.preventDefault();
            //CSRF Hash
            var csrfName = $('.ci_csrf_data').attr('label'); //CSRF token
            var csrfHash  = $('.ci_csrf_data').val(); //CSRF Hash
            var form = this;
            var modal = $('body').find('div#category-modal');
            var formdata = new FormData(form);
                formdata.append(csrfName,csrfHash);
            
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                dataType: 'json',
                contentType: false,
                cache: false,
                beforeSend:function(){
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success:function(response){
                    //update csrf Hash
                    $('.ci_csrf_data').val(response.token);

                    if($.isEmptyObject(response.error)){
                        if(response.status == 1){
                            $(form)[0].reset();
                            modal.modal('hide');
                            toastr.success(response.msg);
                            categories_DT.ajax.reload(null,false);
                        }else{
                            toastr.error(response.msg);
                        }
                    }else{
                        $.each(response.error, function(prefix,val){
                            $(form).find('span.'+prefix+'_error').text(val);
                        });
                    }
                }
            });
        });

        // Retriew the categories
        var categories_DT = $('#categories-table').DataTable({
            processing:true,
            serverSide:true,
            ajax:"<?= route_to('get-categories') ?>",
            dom:"Brtip",
            info:true,
            fnCreatedRow:function(row, data, index){
                $('td', row).eq(0).html(index+1);
            },
            columnDefs:[
                { orderable:false, targets:[0,1,2] },
                { visible:false, targets:3 }
            ],
            order:[[3,'asc']]
        });

        //Edit categories
        $(document).on('click','.editCategoryBtn', function(e){
            e.preventDefault();
            var category_id = $(this).data('id');
            var url = "<?= route_to('get-category') ?>";
            $.get(url,{ category_id:category_id}, function(response){
                var modal_title = 'Edit category';
                var modal_btn_text = 'Save changes';
                var modal = $('body').find('div#edit-category-modal');
                modal.find('form').find('input[type="hidden"][name="category_id"]').val(category_id);
                modal.find('.modal-title').html(modal_title);
                modal.find('.modal-footer > button.action').html(modal_btn_text);
                modal.find('input[type="text"]').val(response.data.label);
                modal.find('span.error-text').html('');
                modal.modal('show');
            },'json'); 
        });

        $('#update_category_form').on('submit', function(e){
            e.preventDefault();
            var csrfName = $('.ci_csrf_data').attr('label'); //CSRF token
            var csrfHash  = $('.ci_csrf_data').val(); //CSRF Hash
            var form = this;
            var modal = $('body').find('div#edit-category-modal');
            var formdata = new FormData(form);
                formdata.append(csrfName,csrfHash);  
            
                $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                dataType: 'json',
                contentType: false,
                cache: false,
                beforeSend:function(){
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success:function(response){
                    //update csrf Hash
                    $('.ci_csrf_data').val(response.token);

                    if($.isEmptyObject(response.error)){
                        if(response.status == 1){
                            $(form)[0].reset();
                            modal.modal('hide');
                            toastr.success(response.msg);
                            categories_DT.ajax.reload(null,false);
                        }else{
                            toastr.error(response.msg);
                        }
                    }else{
                        $.each(response.error, function(prefix,val){
                            $(form).find('span.'+prefix+'_error').text(val);
                        });
                    }
                }
            });
        });

        //delete categories
        $(document).on('click','.deleteCategoryBtn',function(e){
            e.preventDefault();
            var category_id =$(this).data('id');
            var url = "<?= route_to('delete-category')?>";
            swal.fire({
                title:'Are you sure?',
                html:'You want to delete this category',
                showCloseButton:true,
                showCancelButton:true,
                cancelButtonText:'Cancel',
                confirmButtonText:'Delete',
                cancelButtonColor:'#d33',
                confirmButtonColor:'#3085d6',
                width:400,
                allowdOutsideClick:false
            }).then(function(result){
                if(result.value){
                    $.get(url,{category_id:category_id},function(response){
                        if(response.status == 1){
                            categories_DT.ajax.reload(null,false);
                            toastr.success(response.msg);
                        }else{
                            toastr.error(response.msg);
                        }
                    },'json');  
                }
            });
        });

        //Add product
        $(document).on('click','#add_products_btn',function(e){
            e.preventDefault();
            var modal_title = 'Add product';
            var modal_btn_text = 'ADD';
            var modal = $('body').find('div#product-modal');
            var select = modal.find('select[name="category_id"]');
            var url = "<?= route_to('get-parent-categories')?>";
            $.getJSON(url,{ parent_category_id:null} , function(response){
                select.find('option').remove();
                select.html(response.data);
            });
            modal.find('.modal-title').html(modal_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('.input[type="text"]').val('');
            modal.find('textarea').html('');
            modal.find('span.error-text').html(''); 
            modal.modal('show');
        });

        $(document).on('submit','#add_product_form', function(e){
            e.preventDefault();

            var csrfName = $('.ci_csrf_data').attr('name'); //CSRF token
            var csrfHash  = $('.ci_csrf_data').val(); //CSRF Hash
            var form = this;
            var modal = $('body').find('div#product-modal');
            var formdata = new FormData(form);
                formdata.append(csrfName,csrfHash); 
            
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                dataType: 'json',
                contentType: false,
                cache: false,
                beforeSend:function(){
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success:function(response){
                    //update csrf Hash
                    $('.ci_csrf_data').val(response.token);

                    if($.isEmptyObject(response.error)){
                        if(response.status == 1){
                            $(form)[0].reset();
                            modal.modal('hide');
                            toastr.success(response.msg);
                        }
                    }else{
                        $.each(response.error, function(prefix,val){
                            $(form).find('span.'+prefix+'_error').text(val);
                        });
                    }
                }
            });
            
        });

        


    </script>
<?= $this->endSection() ?>