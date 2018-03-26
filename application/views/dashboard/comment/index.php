<?php $this->load->view('dashboard/inc/header'); ?>

<div class="row mt">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">نظرات<i class="fa fa-comments pull-right"></i></h3>

            </div>
            <div class="panel-body">

                <?php if (count($comments) > 0) : ?>
                    <!-- Start Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">کاربر</th>
                                <th class="text-center">تاریخ ثبت</th>
                                <th class="text-center">وضعیت</th>
                                <th class="text-center">مشاهده نظر</th>
                                <th class="text-center">حذف</th>

                            </tr>
                            </thead>

                            <tbody class="text-center">
                            <?php foreach ($comments as $comment) : ?>
                                <tr>
                                    <td><?= $comment->user->username; ?></td>
                                    <td>
                                        <span><?= $this->jdf->jdate('Y/m/d', strtotime($comment->created_at), '', '', 'en'); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $status_text = 'مشاهده نشده';
                                        $text_color = 'text-danger';
                                        if ($comment->status == 'read') {
                                            $status_text = 'مشاهده شد';
                                            $text_color = 'text-success';
                                        }elseif ($comment->status == 'confirm'){
                                            $status_text = 'تایید شد';
                                            $text_color = 'text-info';
                                        }
                                        ?>
                                        <span class="<?=$text_color;?>"><?=$status_text;?></span>

                                    </td>
                                    <td>
                                        <a href="<?=base_url('dashboard/comment/' . $comment->post->id);?>">مشاهده</a>
                                    </td>

                                    <td>
                                        <a onclick='_delete("<?= base_url('dashboard/comment_delete/' . $comment->id); ?>");'
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
