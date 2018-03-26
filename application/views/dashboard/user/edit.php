<?php $this->load->view('dashboard/inc/header'); ?>

<div class="row mt">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">ویرایش کاربر<i class="fa fa-user pull-right"></i></h3>
                <a class="pull-left" href="<?= base_url('dashboard/users'); ?>">کاربران</a>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="well">
                            <?= form_open_multipart('dashboard/user_update'); ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3 class="text-center">اطلاعات کاربری</h3>
                                    <hr>
                                    <div class="form-group">
                                        <label for="username">نام کاربری :</label>
                                        <input type="text" required="required" autocomplete="off" name="username"
                                               value="<?=$user->username; ?>"
                                               id="username" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="password">کلمه عبور :</label>
                                        <input type="password" autocomplete="off" name="password" value="" id="password"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">ایمیل :</label>
                                        <input type="email" required="required" autocomplete="off" name="email"
                                               value="<?=$user->email; ?>"
                                               id="email"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="active">وضعیت :</label>
                                                <select name="active" id="active" class="form-control">
                                                    <option <?=$user->active == 1 ? 'selected="selected"' : null;?> value="1" >فعال</option>
                                                    <option <?=$user->active == 0 ? 'selected="selected"' : null;?> value="0">غیرفعال</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="status">تعلیق حساب :</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option <?=$user->status == 'normal' ? 'selected="selected"' : null;?> value="0">خیر</option>
                                                    <option <?=$user->status == 'disabled' ? 'selected="selected"' : null;?> value="1">بله</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="file">انتخاب تصویر پروفایل :</label>
                                        <input type="file" name="file" id="file" class="form-control" accept="image/*">

                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            ویرایش<span class="glyphicon glyphicon-ok mr" aria-hidden="true"></span>
                                        </button>
                                        <input type="hidden" name="old_picture" value="<?=$user->picture;?>">
                                        <input type="hidden" name="user_id" value="<?=$user->id;?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h3 class="text-center">اطلاعات شخصی</h3>
                                    <hr>
                                    <div class="form-group">
                                        <label for="name">نام :</label>
                                        <input type="text" autocomplete="off" name="name" value="<?=$user->name;?>" id="name"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="family">نام خانوادگی :</label>
                                        <input type="text" autocomplete="off" name="family" value="<?=$user->family;?>" id="family"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">شماره موبایل :</label>
                                        <input type="text" autocomplete="off" pattern="\d*" name="mobile" value="<?=$user->mobile;?>"
                                               id="mobile" maxlength="11"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="address">نشانی :</label>
                                        <textarea style="resize: none" autocomplete="off" name="address" id="address"
                                                  cols="30" rows="10"
                                                  class="form-control"><?=$user->address;?></textarea>
                                    </div>

                                </div>

                            </div>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>

</div>


<?php $this->load->view('dashboard/inc/footer'); ?>
