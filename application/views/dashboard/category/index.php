<?php $this->load->view('dashboard/inc/header'); ?>

<div class="row mt">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">دسته بندی ها<i class="fa fa-bars pull-right"></i></h3>
                <a class="pull-left" href="<?= base_url('dashboard/category_create'); ?>">افزودن دسته بندی</a>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="well">
                            <div class="row">
                                <?= form_open('dashboard/categories/index'); ?>
                                <div class="col-sm-6 col-sm-offset-3">
                                    <div class="form-group">
                                        <input type="text" name="keyword" placeholder="عبارت جستجو ..."
                                               autocomplete="off"
                                               class="form-control" id="keyword">
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <button type="submit" class="btn btn-primary">جستجو</button>
                                </div>
                                <?= form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>

                <?php if (count($categories) > 0) : ?>
                    <!-- Start Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">والد</th>
                                <th class="text-center">عنوان</th>
                                <th class="text-center">تاریخ ثبت</th>
                                <th class="text-center">ویرایش</th>
                                <th class="text-center">حذف</th>

                            </tr>
                            </thead>

                            <tbody class="text-center">
                            <?php foreach ($categories as $category) : ?>
                                <tr>
                                    <td><?=$category->parent_id == 0 ? 'بله' : 'خیر'?></td>
                                    <td><?= $category->name; ?></td>
                                    <td><?= $this->jdf->jdate('H:i Y/m/d', strtotime($category->created_at), '', '', 'en'); ?></td>
                                    <td><a href="<?= base_url('dashboard/category_edit/' . $category->id); ?>"
                                           title="ویرایش"><span class="glyphicon glyphicon-pencil"></span></a>
                                    </td>
                                    <td>
                                        <a onclick='_delete("<?= base_url('dashboard/category_delete/' . $category->id); ?>");'
                                           href="javascript:void(0)" title="حذف"><span
                                                    class="glyphicon glyphicon-trash trash"></span></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>


                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->
                <?php endif; ?>
                <!-- Start Pagination -->
                <div class="row">
                    <div class="col-sm-7 col-sm-offset-5">
                        <nav aria-label="Page navigation">
                            <?php echo $pagination; ?>
                        </nav>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
        </div>


    </div>

</div>


<?php $this->load->view('dashboard/inc/footer'); ?>
