<?php $this->load->view('dashboard/inc/header'); ?>

<div class="row mt">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">پروفایل من<i class="fa fa-user pull-right"></i></h3>

            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="well">
                            <?=form_open_multipart('dashboard/profile');?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3 class="text-center">اطلاعات کاربری</h3>
                                    <hr>
                                    <div class="form-group">
                                        <label for="username">نام کاربری :</label>
                                        <input type="text" required="required" autocomplete="off" name="username" value="<?= $admin->username; ?>"
                                               id="username" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="current_password">کلمه عبور فعلی :</label>

                                        <span class="text-warning">برای ویرایش اطلاعات ، وارد نمودن کلمه عبور فعلی اجباری می باشد</span>
                                        <input type="password" required="required" autocomplete="off" name="current_password" value="" id="current_password"
                                               class="form-control">


                                    </div>
                                    <div class="form-group">
                                        <label for="password">کلمه عبور جدید :</label>

                                        <span class="text-warning">درصورت وارد نکردن کلمه عبور جدید ، کلمه عبور فعلی شما استفاده خواهد شد</span>
                                        <input type="password" autocomplete="off" name="password" value="" id="password"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">ایمیل :</label>
                                        <input type="email" required="required" autocomplete="off" name="email" value="<?= $admin->email; ?>" id="email"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            ویرایش<span class="glyphicon glyphicon-ok mr" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h3 class="text-center">اطلاعات شخصی</h3>
                                    <hr>
                                    <div class="form-group">
                                        <label for="name">نام :</label>
                                        <input type="text" autocomplete="off" name="name" value="<?= $admin->name; ?>" id="name"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="family">نام خانوادگی :</label>
                                        <input type="text" autocomplete="off" name="family" value="<?= $admin->family; ?>" id="family"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="file">انتخاب تصویر پروفایل :</label>
                                        <input type="file" name="file" id="file" class="form-control" accept="image/*">
                                        <input type="hidden" name="old_picture" value="<?=$admin->picture;?>">
                                        <input type="hidden" name="admin_id" value="<?=$admin->id;?>">
                                    </div>
                                </div>

                            </div>
                            <?=form_close();?>
                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>

</div>


<?php $this->load->view('dashboard/inc/footer'); ?>
