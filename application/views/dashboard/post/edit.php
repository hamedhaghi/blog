<?php $this->load->view('dashboard/inc/header'); ?>
    <div id="filemanagerModal" aria-hidden="true" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 80%">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="margin-right: 20px;">افزودن فایل از سرور</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 text-center ">
                            <button id="create_folder_btn" type="button" class="btn btn-primary btn-block"
                                    onclick="show_folder_modal(this)"
                            ><i class="fa fa-plus mr ml"></i>Create New Folder
                            </button>
                        </div>
                        <div class="col-sm-6 text-center">
                            <button id="open_upload_modal" type="button" class="btn btn-primary btn-block"
                                    onclick="open_upload_modal(this)"
                            ><i class="fa fa-upload mr ml"></i>Upload File
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="content">
                                <?php
                                $data['path'] = $path;
                                $data['files'] = $files;
                                ?>
                                <?php $this->load->view('dashboard/inc/files', $data); ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-success pull-left" id="addToEditor">Add To Editor</button>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div id="createFolderModal" aria-hidden="true" class="modal fade" style="direction: ltr;" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create New Folder</h4>
                </div>
                <div class="modal-body">
                    <p id="create_message"></p>
                    <div class="form-group">
                        <label for="folder_name">Folder Name : </label>
                        <input type="text" id="folder_name" class="form-control" name="folder_name">
                    </div>
                    <div class="form-group">
                        <button type="button" id="save_folder_btn" onclick="create_folder(this)"
                                data-url="<?= base_url('dashboard/create_folder'); ?>"
                                class="btn btn-primary">Create
                        </button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div id="renameModal" aria-hidden="true" class="modal fade" style="direction: ltr;" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Rename</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Name : </label>
                        <input type="text" id="name" class="form-control" name="name">
                        <input type="hidden" id="rename_path" value="">
                        <input type="hidden" id="type" value="">
                    </div>
                    <div class="form-group">
                        <button type="button" onclick="rename(this)"
                                data-url="<?= base_url('dashboard/rename_folder'); ?>"
                                class="btn btn-primary">Rename
                        </button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div id="uploadModal" aria-hidden="true" class="modal fade" style="direction: ltr;" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload File</h4>
                </div>
                <div class="modal-body">
                    <p id="upload_message"></p>
                    <div class="form-group">
                        <label for="file1">Name : </label>
                        <input type="file" id="file1" class="form-control" name="file">
                    </div>
                    <div class="form-group">
                        <button type="button" onclick="do_upload(this)" id="btn_upload"
                                data-url="<?= base_url('dashboard/file_upload'); ?>" data-path=""
                                class="btn btn-primary">Upload
                        </button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div id="viewModal" aria-hidden="true" class="modal fade" style="direction: ltr;" role="dialog">
        <div class="modal-dialog" style="width: 50%; ">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">View or Edit</h4>
                </div>
                <div class="modal-body">
                    <p id="edit_message"></p>
                    <div class="form-group">
                        <label for="file_content">Folder Name : </label>
                        <textarea name="file_content" class="form-control" style="resize: vertical;" id="file_content" cols="30" rows="30"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" id="edit_btn" onclick="file_edit(this)" data-path=""
                                data-url="<?= base_url('dashboard/file_edit'); ?>"
                                class="btn btn-primary">Save
                        </button>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div id="infoModal" aria-hidden="true" class="modal fade" style="direction: ltr;" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Info</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="info_size">Size : </label>
                        <input type="text" id="info_size" value="" readonly="readonly" class="form-control ltr">
                    </div>
                    <div class="form-group">
                        <label for="info_name">Name : </label>
                        <input type="text" id="info_name" value="" readonly="readonly" class="form-control ltr">
                    </div>
                    <div class="form-group">
                        <label for="info_physical_path">Physical Path : </label>
                        <input type="text" id="info_physical_path" value="" readonly="readonly" class="form-control ltr">
                    </div>

                    <div class="form-group">
                        <label for="info_website_url">Url : </label>
                        <input type="text" id="info_website_url" value="" readonly="readonly" class="form-control ltr">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success pull-left" onclick="copy_to_clipboard(this)">Copy Url
                    </button>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div class="row mt">
        <div class="col-sm-12">
            <?php echo form_open_multipart('dashboard/post_update'); ?>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-right">ویرایش پست<i class="fa fa-newspaper-o pull-right"></i></h3>
                    <a class="pull-left" href="<?= base_url('dashboard/posts'); ?>">پست ها</a>
                </div>
                <div class="panel-body">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="name1">عنوان :</label>
                            <input type="text" class="form-control" id="name1" name="name" required="required"
                                   autocomplete="off"
                                   value="<?= $post->name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="slug">نشانی اینترنتی :</label>
                            <input type="text" class="form-control" id="slug" name="slug" required="required"
                                   autocomplete="off"
                                   value="<?= $post->slug; ?>">
                        </div>
                        <div class="form-group">
                            <label for="body">متن :</label>
                            <button type="button" id="add_file_btn" style="display: none;"
                                    class="btn btn-default pull-left mb" onclick="show_filemanager_modal(this)">افزودن
                                فایل از سرور
                            </button>
                            <div class="clearfix"></div>
                            <textarea name="description" id="body" cols="30" rows="10"
                                      class="form-control"><?= $post->description; ?></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                ویرایش<span class="glyphicon glyphicon-ok mr" aria-hidden="true"></span>
                            </button>
                            <input type="hidden" name="post_id" value="<?= $post->id; ?>">
                            <input type="hidden" name="old_picture" value="<?= $post->picture; ?>">
                        </div>

                    </div>
                    <div class="col-sm-3">

                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">
                                <h1 class="panel-title pull-right"><i
                                            class="glyphicon glyphicon-cog pull-right ml"></i>مشخصات</h1>
                                <div class="pull-left">
                                    <input type="checkbox" name="visible" id="visible" data-toggle="toggle"
                                           data-onstyle="success" data-offstyle="default" data-class="fast"
                                           class="form-control pull-left" <?= $post->visible == 1 ? 'checked="checked"' : null; ?>
                                           data-on="انتشار"
                                           data-off="پیش نویس" data-size="mini">
                                </div>

                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="category_id" class="mt">انتخاب دسته بندی :</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option <?= empty($post->category_id) ? 'selected="selected"' : null; ?>
                                                value="">دسته بندی نشده
                                        </option>
                                        <?php if ($categories->count() > 0) : ?>
                                            <?php foreach ($categories as $category) : ?>
                                                <option <?= $post->category_id == $category->id ? 'selected="selected"' : null; ?>
                                                        value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                                <?php if ($category->children()->count() > 0) : ?>
                                                    <?php foreach ($category->children as $child) : ?>
                                                        <option <?= $post->category_id == $child->id ? 'selected="selected"' : null; ?>
                                                                value="<?= $child->id ?>">
                                                            -- <?= $child->name; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="dateInput" class="mt">تاریخ انتشار :</label>
                                    <input type="text" autocomplete="off" id="dateInput" onkeydown="return false;"
                                           name="published_at" class="form-control ltr"
                                           value="<?= $this->jdf->jdate('Y/m/d', strtotime($post->published_at), '', '', 'en'); ?>">

                                </div>

                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title"><i
                                            class="fa fa-search pull-right ml"></i>تنظیمات سئو
                                </h1>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="meta_keyword" class="mt">کلمات کلیدی :</label>
                                    <input type="text"
                                           value="<?= @json_decode($post->seo_params)->keyword; ?>"
                                           autocomplete="off" name="meta_keyword" id="meta_keyword"
                                           class="form-control" placeholder="تگ متا ...">
                                </div>
                                <div class="form-group">
                                    <label for="meta_description" class="mt">توضیحات کلیدی :</label>
                                    <textarea name="meta_description" id="meta_description" cols="30" rows="10"
                                              autocomplete="off"
                                              class="form-control" style="resize: none"
                                              placeholder="تگ متا ..."><?= @json_decode($post->seo_params)->description; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title"><i
                                            class="fa fa-tags pull-right ml"></i>انتخاب تگ
                                </h1>
                            </div>
                            <div class="panel-body">
                                <select name="tags[]" id="tags" class="form-control tags" style="width: 100%"
                                        multiple="multiple">
                                    <?php if (!empty($tags)) : ?>
                                        <?php foreach ($tags as $tag) : ?>
                                            <?php
                                            $selected = null;
                                            if (!empty($old_tags)) {
                                                if (in_array($tag->id, $old_tags)) {
                                                    $selected = 'selected="selected"';
                                                }
                                            }
                                            ?>
                                            <option <?= $selected; ?>
                                                    value="<?= url_title($tag->slug); ?>"><?= $tag->name; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title"><i
                                            class="glyphicon glyphicon-picture pull-right ml"></i>انتخاب تصویر
                                </h1>
                            </div>
                            <div class="panel-body">
                                <input type="file" id="file" name="file" accept="image/*">
                                <?php if (!empty($post->picture) && file_exists(FCPATH . '/uploads/posts/' . $post->picture)) : ?>
                                    <hr>
                                    <img src="<?= base_url('uploads/posts/' . $post->picture); ?>"
                                         class="img-responsive img-thumbnail" alt="<?= $post->name; ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <script>
        var post_page=true;
    </script>
<?php $this->load->view('dashboard/inc/footer'); ?>