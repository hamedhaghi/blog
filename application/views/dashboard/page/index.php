<?php $this->load->view('dashboard/inc/header'); ?>

    <div class="row mt">
        <div class="col-sm-12">
            <?php echo form_open_multipart('dashboard/pages'); ?>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-right"><?=$title;?><i class="fa fa-file pull-right"></i></h3>

                </div>
                <div class="panel-body">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <label for="name">عنوان :</label>
                            <input type="text" class="form-control" id="name" name="name" required="required"
                                   autocomplete="off"
                                   value="<?=@$page->name;?>">
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label for="slug">نشانی اینترنتی :</label>-->
<!--                            <input type="text" class="form-control" id="slug" name="slug" required="required"-->
<!--                                   autocomplete="off"-->
<!--                                   value="">-->
<!--                        </div>-->
                        <div class="form-group">
                            <label for="body">متن :</label>
                            <textarea name="description" id="body" cols="30" rows="10" style="resize: vertical;"
                                      class="form-control"><?=@$page->description;?></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                ذخیره<span class="glyphicon glyphicon-ok mr" aria-hidden="true"></span>
                            </button>
                            <input type="hidden" value="<?=$type;?>" name="type">
                        </div>

                    </div>

                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>


<?php $this->load->view('dashboard/inc/footer'); ?>