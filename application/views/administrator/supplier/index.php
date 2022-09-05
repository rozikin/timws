<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?= $title; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php if (validation_errors()) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= validation_errors(); ?></div>
                    <?php endif; ?>
                    <?= $this->session->flashdata('message'); ?>


                    <div class="card">
                        <div class="card-header">

                            <a href="<?= base_url(); ?>controller_supplier/add_supplier"
                                class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</a>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-hover table-bordered table-sm" style="width:100%">
                                <thead>
                                    <tr>

                                        <th scope="col">#</th>
                                        <th scope="col">Supplier Name</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Faximile</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">VAT</th>
                                        <th scope="col">Action</th>

                                    </tr>
                                </thead>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script type="text/javascript">
var save_method; //for save method string
var table;
var base_url = '<?php echo base_url(); ?>';

$(document).ready(function() {
    //call function show all product

    table = $('#example1').DataTable({

        "responsive": true,

        "ajax": {
            url: '<?php echo site_url('controller_supplier/get_data') ?>',
            type: 'POST'
        }

    });
});



function add_data() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Data'); // Set Title to Bootstrap modal title
    kd_otomatis();
}


function edit_data(id) {
    var a = "<?php echo site_url('controller_supplier/edit_supplier') ?>/" + id;
    window.location.assign(a);
}


function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax 

}



function deleted(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "<?php echo site_url('controller_supplier/remove') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    //if success reload ajax table
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                    reload_table();
                }
            });

        }
    })
}
</script>