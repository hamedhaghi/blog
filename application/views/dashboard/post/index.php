<?php $this->load->view('dashboard/inc/header'); ?>

<div class="row mt">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">پست ها<i class="fa fa-newspaper-o pull-right"></i></h3>
                <a class="pull-left" href="<?= base_url('dashboard/post_create'); ?>">افزودن پست</a>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="well">
                            <div class="row">

                                <?= form_open('dashboard/posts/index'); ?>
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

                <?php if (count($posts) > 0) : ?>
                    <!-- Start Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">عنوان</th>
                                <th class="text-center">تاریخ انتشار</th>
                                <th class="text-center">وضعیت</th>
                                <th class="text-center">نظرات</th>
                                <th class="text-center">مشاهده نظر</th>
                                <th class="text-center">ویرایش</th>
                                <th class="text-center">حذف</th>

                            </tr>
                            </thead>

                            <tbody class="text-center">
                            <?php foreach ($posts as $post) : ?>

                                <tr>
                                    <td><?= $post->name; ?></td>
                                    <td>
                                        <?php if (date('Y/m/d', strtotime($post->published_at)) > date('Y/m/d', time())) : ?>
                                            <span class="text-danger"><?= $this->jdf->jdate('Y/m/d', strtotime($post->published_at), '', '', 'en'); ?></span>
                                        <?php else : ?>
                                            <span><?= $this->jdf->jdate('Y/m/d', strtotime($post->published_at), '', '', 'en'); ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php
                                        $status_text = 'پیش نویس';
                                        $text_color = 'text-danger';
                                        if ($post->visible == 1) {
                                            $status_text = 'انتشار';
                                            $text_color = 'text-success';
                                        }
                                        ?>
                                        <a onclick='_publish("<?= base_url('dashboard/post_visible/' . $post->id); ?>", this);'
                                           href="javascript:void(0)" title="<?= $status_text; ?>"
                                           class="<?= $text_color; ?>"><?= $status_text; ?></a>
                                    </td>
                                    <td>
                                        <span><?=$post->comments->count();?></span>
                                    </td>
                                    <td>
                                        <a href="<?=base_url('dashboard/comment/' . $post->id);?>">مشاهده</a>
                                    </td>
                                    <td><a href="<?= base_url('dashboard/post_edit/' . $post->id); ?>"
                                           title="ویرایش"><span class="glyphicon glyphicon-pencil"></span></a>
                                    </td>
                                    <td>
                                        <a onclick='_delete("<?= base_url('dashboard/post_delete/' . $post->id); ?>");'
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
