<?php $this->load->view('dashboard/inc/header'); ?>
<div class="row mt">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-right">مدیریت فایل ها<i class="fa fa-file-text pull-right"></i></h3>

            </div>
            <div class="panel-body">
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
                <div class="content">
                    <?php $this->load->view('dashboard/inc/files'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('dashboard/inc/footer'); ?>
