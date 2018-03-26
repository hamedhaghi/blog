<?php $this->load->view('dashboard/inc/header'); ?>

<div class="row mt">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">کاربران<i class="fa fa-users pull-right"></i></h3>
                <a class="pull-left" href="<?= base_url('dashboard/user_create'); ?>">افزودن کاربر</a>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="well">
                            <div class="row">

                                <?= form_open('dashboard/users/index'); ?>
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

                <?php if (count($users) > 0) : ?>
                    <!-- Start Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">تصویر پروفایل</th>
                                <th class="text-center">نام کاربری</th>
                                <th class="text-center">تاریخ ثبت</th>
                                <th class="text-center">وضعیت</th>

                                <th class="text-center">ویرایش</th>
                                <th class="text-center">حذف</th>

                            </tr>
                            </thead>

                            <tbody class="text-center">
                            <?php foreach ($users as $user) : ?>
                                <?php
                                $picture = base_url('assets/dashboard/img/user1.png');
                                if (!empty($user->picture) && file_exists(FCPATH . "uploads/profiles/{$user->picture}")) {
                                    $picture = base_url("uploads/profiles/{$user->picture}");
                                }
                                ?>
                                <tr>
                                    <td><img src="<?=$picture;?>" class="img-circle table-img"
                                             alt=""></td>
                                    <td><?= $user->username; ?></td>
                                    <td><?= $this->jdf->jdate('H:i Y/m/d', strtotime($user->created_at), '', '', 'en'); ?></td>
                                    <td>
                                        <?php
                                        $active_text = 'غیرفعال';
                                        $active_color = 'text-danger';
                                        if ($user->active == 1) {
                                            $active_text = 'فعال';
                                            $active_color = 'text-success';
                                        }
                                        ?>
                                        <a onclick='_status("<?= base_url('dashboard/user_active/' . $user->id); ?>", this);'
                                           href="javascript:void(0)" title="<?= $active_text; ?>"
                                           class="<?= $active_color; ?>"><?= $active_text; ?></a>
                                    </td>

                                    <td><a href="<?= base_url('dashboard/user_edit/' . $user->id); ?>"
                                           title="ویرایش"><span class="glyphicon glyphicon-pencil"></span></a>
                                    </td>
                                    <td>
                                        <a onclick='_delete("<?= base_url('dashboard/user_delete/' . $user->id); ?>");'
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
