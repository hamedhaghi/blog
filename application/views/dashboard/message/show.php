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
                                <div class="col-sm-8 col-sm-offset-2">
                                    <div style="background: #fff;overflow: auto; padding: 7px;border: solid 1px #ccc;">
                                        <h4 class="pull-right">نام و نام خانوادگی :&nbsp;<?=$message->fullname;?></h4>
                                        <h4 class="pull-left">تاریخ ارسال :&nbsp;<?=$this->jdf->jdate('H:i Y/m/d', strtotime($message->created_at), '', '', 'en');?></h4>
                                        <div class="clearfix"></div>
                                        <h4 class="pull-right"><a onclick='_delete("<?= base_url('dashboard/message_delete/' . $message->id); ?>");'
                                                                  href="javascript:void(0)" title="حذف" class="text-danger"><span
                                                    class="fa fa-trash ml"></span><span>حذف</span></a></h4>
                                        <h4 class="pull-left"><?=$message->email;?></h4>
                                    </div>
                                    <div style="background: #fff;overflow: auto; padding: 7px;border: solid 1px #ccc;margin-top: 5px;">
                                        <h4>موضوع :&nbsp;<?=$message->subject;?></h4>
                                        <p><?=$message->description;?></p>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>

</div>


<?php $this->load->view('dashboard/inc/footer'); ?>
