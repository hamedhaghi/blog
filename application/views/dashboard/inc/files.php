<input type="hidden" id="path" value="<?= $path; ?>">
<div class="row">
    <div class="col-sm-12">
        <hr>
        <div class="text-left ltr address">
            <strong> Current Folder :</strong>

            <ol class="breadcrumb breadcrumb-arrow">
                <?php if (!empty($path)) : ?>
                    <?php
                    $fixed_path = $path;
                    $path = explode(DIRECTORY_SEPARATOR, $path);
                    $dirs = array();
                    $key = array_keys($path, 'uploads');
                    if (!empty($key)) {
                        for ($i = $key[0]; $i <= sizeof($path) - 1; $i++) {
                            $dirs[] = $path[$i];
                        }
                    }
                    $temp = null;
                    ?>
                    <?php foreach ($dirs as $dir) : ?>
                        <?php
                        if (!empty($temp)) {
                            $temp .= DIRECTORY_SEPARATOR . $dir;
                        } else {
                            $temp = FCPATH . 'uploads';
                        }
                        ?>

                        <li><a onclick="open_dir(this)" data-path="<?= $temp; ?>"
                               data-url="<?= base_url('dashboard/open_dir'); ?>"
                               href="javascript:void(0)"><?= $dir; ?></a></li>

                    <?php endforeach; ?>
                <?php else : ?>
                    <li><a href="javascript:void(0)">uploads</a></li>
                <?php endif; ?>
            </ol>
        </div>

        <div class="clearfix"></div>
        <hr>
        <br>
        <?php if (!empty($files)) : ?>
            <div class="main">
                <?php $i = 0; ?>
                <?php foreach ($files as $file) : ?>
                    <?php if ($i == 12) {
                        echo '<div class="clearfix"></div>';
                        $i = 0;
                    } ?>
                    <div class="col-sm-2 text-center">
                        <div class="well drag-item drop-item" data-path="<?= $file; ?>"
                             data-url="<?= base_url('dashboard/file_move'); ?>"
                             data-type="<?= is_dir($file) ? 'folder' : 'file'; ?>">
                            <?php if (is_dir($file)): ?>
                                <a title="<?= pathinfo($file)['basename']; ?>" onclick="open_dir(this)"
                                   data-path="<?= $file; ?>"
                                   data-url="<?= base_url('dashboard/open_dir'); ?>" href="javascript:void(0)"
                                   class="folder text-center"><i
                                            class="fa fa-folder fa-4x"></i>
                                    <p class="fileName overText"><?= pathinfo($file)['basename']; ?></p></a>
                                <br>
                                <a href="javascript:void(0)" title="Delete" onclick="file_delete(this)"
                                   data-url="<?= base_url('dashboard/file_delete'); ?>" data-type="folder"
                                   data-path="<?= $file; ?>" class="text-danger mr ml"><i
                                            class="fa fa-trash fa-lg"></i></a>
                                <?php
                                $url = substr($file, strpos($file, basename(base_url())), strlen($file));
                                $split = explode(DIRECTORY_SEPARATOR, $url);
                                $url_sep = array();
                                if (!empty($split)) {
                                    foreach ($split as $item) {
                                        if ($item != basename(base_url())) {
                                            $url_sep[] = $item;
                                        }
                                    }
                                    $url = implode("/", $url_sep);
                                    $url = base_url($url);
                                }

                                ?>
                                <a href="javascript:void(0)"
                                   title="Information" onclick="show_info_modal(this)" data-url="<?= $url; ?>"
                                   data-type="folder" data-path="<?= $file; ?>"
                                   data-filename="<?= pathinfo($file)['filename']; ?>"
                                   data-size="<?= Functions::directory_size($file . DIRECTORY_SEPARATOR); ?>"
                                   class="text-info mr ml"><i
                                            class="fa fa-info fa-lg"></i></a>
                                <a href="javascript:void(0)" title="Rename" data-type="folder" data-path="<?= $file; ?>"
                                   data-filename="<?= pathinfo($file)['filename']; ?>" onclick="show_rename_modal(this)"
                                   class="text-success mr ml"><i
                                            class="fa fa-pencil fa-lg"></i></a>

                            <?php else : ?>
                                <!--   <a title="<? /*= pathinfo($file)['basename']; */ ?>" href="javascript:void(0)"
                                   class="file text-center">-->
                                <?php
                                $url = substr($file, strpos($file, basename(base_url())), strlen($file));
                                $split = explode(DIRECTORY_SEPARATOR, $url);
                                $url_sep = array();
                                if (!empty($split)) {
                                    foreach ($split as $item) {
                                        if ($item != basename(base_url())) {
                                            $url_sep[] = $item;
                                        }
                                    }
                                    $url = implode("/", $url_sep);
                                    $url = base_url($url);
                                }

                                ?>

                                    <?php if (exif_imagetype($file)) : ?>

                                        <img class="img-responsive img-thumbnail file-img" src="<?= $url; ?>"
                                             alt="<?= pathinfo($file, PATHINFO_FILENAME); ?>">
                                    <?php else : ?>
                                        <i class="fa <?= Functions::file_mime_type($file); ?> fa-4x"></i>
                                        <p class="fileName overText"><?= pathinfo($file)['basename']; ?></p>
                                    <?php endif; ?>


                                <!--</a>-->
                                <br>
                                <a href="javascript:void(0)" title="Delete" onclick="file_delete(this)"
                                   data-url="<?= base_url('dashboard/file_delete'); ?>" data-type="file"
                                   data-path="<?= $file; ?>" class="text-danger mr ml"><i
                                            class="fa fa-trash fa-lg"></i></a>
                                <?php
                                $url = substr($file, strpos($file, basename(base_url())), strlen($file));
                                $split = explode(DIRECTORY_SEPARATOR, $url);
                                $url_sep = array();
                                if (!empty($split)) {
                                    foreach ($split as $item) {
                                        if ($item != basename(base_url())) {
                                            $url_sep[] = $item;
                                        }
                                    }
                                    $url = implode("/", $url_sep);
                                    $url = base_url($url);
                                }

                                ?>
                                <a href="javascript:void(0)" title="Information"
                                   data-size="<?= Functions::file_size($file); ?>" data-type="file"
                                   data-path="<?= $file; ?>" data-url="<?= $url; ?>"
                                   data-filename="<?= pathinfo($file)['basename']; ?>" onclick="show_info_modal(this)"
                                   class="text-info mr ml"><i
                                            class="fa fa-info fa-lg"></i></a>
                                <a href="javascript:void(0)" title="Rename" data-type="file" data-path="<?= $file; ?>"
                                   data-filename="<?= pathinfo($file)['filename']; ?>" onclick="show_rename_modal(this)"
                                   class="text-success mr ml"><i
                                            class="fa fa-pencil fa-lg"></i></a>
                                <a href="javascript:void(0)" title="Download" data-path="<?= $file; ?>"
                                   data-file-url="<?= $url; ?>" data-url="<?= base_url('dashboard/file_download'); ?>"
                                   onclick="file_download(this)" class="mr ml"><i class="fa fa-download fa-lg"></i></a>
                                    <!--       <?php /*if (!exif_imagetype($file)) : */ ?>
                                    <a href="javascript:void(0)" title="View / Edit" data-path="<? /*= $file; */ ?>"
                                       data-file-url="<? /*= $url; */ ?>" data-url="<? /*= base_url('dashboard/file_read'); */ ?>"
                                       onclick="show_view_modal(this)"
                                       class="mr ml"><i
                                                class="fa fa-eye fa-lg"></i></a>
                                --><?php /*endif; */ ?>
                            <?php endif; ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <?php $i = $i + 2; ?>
                <?php endforeach; ?>

                <div class="clearfix"></div>
            </div>
        <?php endif; ?>
    </div>
</div>