<?php $this->load->view('dashboard/inc/header'); ?>

<div class="row mt">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">پیام های دریافتی (تماس با ما)<i
                            class="fa fa-envelope pull-right"></i></h3>

            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="well">
                            <div class="row">

                                <?= form_open('dashboard/messages/index'); ?>
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

                <?php if (count($messages) > 0) : ?>
                    <!-- Start Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">نام و نام خانوادگی</th>
                                <th class="text-center">موضوع</th>
                                <th class="text-center">تاریخ ثبت</th>
                                <th class="text-center">بخش</th>
                                <th class="text-center">وضعیت</th>
                                <th class="text-center">مشاهده</th>
                                <th class="text-center">حذف</th>

                            </tr>
                            </thead>

                            <tbody class="text-center">
                            <?php foreach ($messages as $message) : ?>

                                <tr>
                                    <td><?= $message->fullname; ?></td>
                                    <td><?= $message->subject; ?></td>
                                    <td><?= $this->jdf->jdate('H:i Y/m/d', strtotime($message->created_at), '', '', 'en'); ?></td>
                                    <td><?= $message->section; ?></td>
                                    <td>
                                        <?php if ($message->status == 'read') : ?>
                                            <span class="text-success">بررسی شده</span>
                                        <?php else : ?>
                                            <span class="text-danger">بررسی نشده</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?=base_url('dashboard/message/' . $message->id);?>">مشاهده</a>
                                    </td>
                                    <td>
                                        <a onclick='_delete("<?= base_url('dashboard/message_delete/' . $message->id); ?>");'
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
