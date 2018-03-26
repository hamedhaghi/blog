<?php $this->load->view('dashboard/inc/header'); ?>
<div class="row mt">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">مشاهده نظر<i class="fa fa-comments pull-right"></i></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="well">
                            <h4 class="text-center">عنوان پست :&nbsp;&nbsp;<?= $post->name; ?></h4>
                        </div>
                        <div class="well">
                            <?php if (count($comments) > 0) : ?>
                                <?php foreach ($comments as $comment) : ?>
                                    <div style="border: solid 1px #ccc;background: #ffffff;border-radius: 5px;position: relative;margin-top: 5px">
                                        <h4 class="pull-right mr">کاربر :&nbsp;<a
                                                    href="<?= base_url('dashboard/user_edit/' . $comment->user->id); ?>"><?= $comment->user->username; ?></a>
                                        </h4>
                                        <h4 class="pull-left ml"><?= $this->jdf->jdate('H:i Y/m/d', strtotime($comment->created_at), '', '', 'en'); ?></h4>
                                        <div class="clearfix"></div>
                                        <br>
                                        <p class="text-justify mr ml">
                                            <?=$comment->description;?>
                                        </p>
                                        <div style="padding-bottom: 5px;">
                                            <a href="javascript:void(0)" onclick='_delete("<?= base_url('dashboard/comment_delete/' . $comment->id . '/' . $post->id); ?>");'
                                              class="text-danger  pull-left ml">حذف</a>
                                            <?php if ($comment->status == 'confirm') : ?>
                                                <a href="javascript:void(0)"  onclick='_confirm("<?= base_url('dashboard/comment_confirm/' . $comment->id . '/' . $post->id); ?>", this);' title="نمایش نده" class="text-warning mr">نمایش نده</a>
                                            <?php else : ?>
                                                <a href="javascript:void(0)" onclick='_confirm("<?= base_url('dashboard/comment_confirm/' . $comment->id . '/' . $post->id); ?>", this);' title="نمایش بده" class="text-success mr">نمایش بده</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('dashboard/inc/footer'); ?>
