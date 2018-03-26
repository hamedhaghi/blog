<?php $this->load->view('dashboard/inc/header'); ?>

<div class="row mt">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">ویرایش دسته بندی<i class="fa fa-bars pull-right"></i></h3>
                <a class="pull-left" href="<?= base_url('dashboard/categories'); ?>">دسته بندی ها</a>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="well">
                            <div class="row">
                                <?= form_open('dashboard/category_update'); ?>
                                <div class="col-sm-6 col-sm-offset-3">
                                    <div class="form-group">
                                        <label for="parent_id">انتخاب والد :</label>
                                        <select name="parent_id" id="parent_id" class="form-control">
                                            <option value="0">بدون والد</option>
                                            <?php if ($categories->count() > 0) : ?>
                                                <?php foreach ($categories as $my_category) : ?>
                                                    <option <?= $my_category->id == $category->parent_id ? 'selected="selected"' : null; ?>
                                                            value="<?= $my_category->id; ?>"><?= $my_category->name; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">عنوان :</label>
                                        <input type="text" name="name" placeholder="" required="required"
                                               autocomplete="off" value="<?= $category->name; ?>"
                                               class="form-control" id="name">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary mt">
                                            ویرایش<span class="glyphicon glyphicon-ok mr" aria-hidden="true"></span>
                                        </button>
                                        <input type="hidden" name="category_id" value="<?=$category->id;?>">
                                    </div>
                                </div>

                                <?= form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>

</div>


<?php $this->load->view('dashboard/inc/footer'); ?>
