<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>پنل مدیریت | <?= @$title; ?></title>

    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/dashboard/css/bootstrap.min.css'); ?>?v=<?= FILE_VERSION; ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/dashboard/css/bootstrap-rtl.min.css'); ?>?v=<?= FILE_VERSION; ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/dashboard/fonts/font-awesome/css/font-awesome.css'); ?>?v=<?= FILE_VERSION; ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/dashboard/css/font-awesome-animation.css'); ?>?v=<?= FILE_VERSION; ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/dashboard/css/sweetalert.css'); ?>?v=<?= FILE_VERSION; ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/dashboard/css/animate.min.css'); ?>?v=<?= FILE_VERSION; ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/dashboard/css/dashboard.css'); ?>?v=<?= FILE_VERSION; ?>">
    <?php if (isset($style)): ?>
        <?php foreach ($style as $item) : ?>
            <link rel="stylesheet" type="text/css"
                  href="<?= base_url('assets/dashboard/css/' . $item); ?>?v=<?= FILE_VERSION; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2 sidebar">
            <div class="text-center myHover">
                <?php
                $auth = new Auth('admins');
                $admin = $auth->info();
                $path = base_url('assets/dashboard/img/user1.png');
                if (!empty(trim($admin->picture)) && file_exists(FCPATH . 'uploads/profiles/' . $admin->picture)) {
                    $path = base_url("uploads/profiles/{$admin->picture}");
                }
                ?>
                <a href="<?= base_url('dashboard/profile'); ?>" class="target">
                    <img src="<?= $path; ?>"
                         class="user-image img-circle img-responsive center-block"
                         alt="<?= $admin->username; ?>">
                    <i class="fa fa-pencil myPen fa-lg"></i>
                </a>
                <br>

                <a href="<?= base_url('dashboard/profile'); ?>"
                   class="username"><?= !empty($admin->name) && !empty($admin->family) ? $admin->name . ' ' . $admin->family : $admin->username; ?>
                </a>
                <br>
                <a href="<?= base_url('dashboard/logout'); ?>" class="exit">خروج</a>
                <br>
                <br>
                <br>
            </div>

            <?php
            $controller_name = $this->router->fetch_class();
            $dashboard = null;
            $comments = null;
            $posts = null;
            $tags = null;
            $users = null;
            $pages = null;
            $messages = null;
            $categories = null;
            $files = null;
            switch ($controller_name) {
                case 'dashboard':
                    switch ($this->router->fetch_method()) {
                        case 'index' :
                            $dashboard = "class=\"focus\"";
                            break;
                        case 'comments' :
                        case 'comment' :
                            $comments = "class=\"focus\"";
                            break;
                        case 'posts' :
                        case 'post_create' :
                        case 'post_edit' :
                            $posts = "class=\"focus\"";
                            break;

                        case 'tags':
                        case 'tag_create':
                        case 'tag_edit':
                            $tags = "class=\"focus\"";
                            break;
                        case 'categories':
                        case 'category_create':
                        case 'category_edit':
                            $categories = "class=\"focus\"";
                            break;
                        case 'users' :
                        case 'user_create':
                        case 'user_edit':
                            $users = "class=\"focus\"";
                            break;
                        case 'pages' :
                            $pages = "class=\"focus\"";
                            break;

                        case 'messages' :
                        case 'message' :
                            $messages = "class=\"focus\"";
                            break;
                        case 'files' :
                            $files = "class=\"focus\"";
                            break;

                    }
                    break;
            }


            ?>

            <ul>
                <li>
                    <a <?= $dashboard; ?> href="<?= base_url('dashboard'); ?>"><i
                                class="fa fa-dashboard sidebar-icon fa-lg"></i><span>داشبورد</span></a>
                </li>
                <li>
                    <?php
                    $this->load->model('Comment_model');
                    $count_comments = Comment_model::where('status', 'unread')->count();
                    ?>
                    <?php if ($count_comments > 0) : ?>
                        <span class="badge count-badge animated flash  pull-left"><?= $count_comments; ?></span>
                    <?php endif; ?>

                    <a <?= $comments; ?> href="<?= base_url('dashboard/comments'); ?>"><i
                                class="fa fa-comments  sidebar-icon fa-lg"></i><span>نظرات</span></a>
                </li>

                <li class="menu">
                    <a <?= $posts; ?> href="<?= base_url('dashboard/posts'); ?>"><i
                                class="fa fa-caret-left caret-left-red pull-left"></i><i
                                class="fa fa-newspaper-o sidebar-icon fa-lg"></i><span>پست ها</span></a>
                    <ul>
                        <li><a href="<?= base_url('dashboard/post_create'); ?>">افزودن پست</a></li>
                    </ul>
                </li>
                <li class="menu">
                    <a <?= $tags; ?> href="<?= base_url('dashboard/tags'); ?>"><i
                                class="fa fa-caret-left caret-left-red pull-left"></i><i
                                class="fa fa-tags sidebar-icon fa-lg"></i><span>تگ ها</span></a>
                    <ul>
                        <li><a href="<?= base_url('dashboard/tag_create'); ?>">افزودن تگ</a></li>

                    </ul>
                </li>
                <li class="menu">
                    <a <?= $categories; ?> href="<?= base_url('dashboard/categories'); ?>"><i
                                class="fa fa-caret-left caret-left-red pull-left"></i><i
                                class="fa fa-bars sidebar-icon fa-lg"></i><span>دسته بندی ها</span></a>
                    <ul>
                        <li><a href="<?= base_url('dashboard/category_create'); ?>">افزودن دسته بندی</a></li>

                    </ul>
                </li>


                <li class="menu"><a <?= $users; ?> href="<?= base_url('dashboard/users'); ?>"><i
                                class="fa fa-caret-left caret-left-red pull-left"></i><i
                                class="fa fa-users sidebar-icon fa-lg"></i><span>کاربران</span></a>
                    <ul>
                        <li><a href="<?= base_url('dashboard/user_create'); ?>">افزودن کاربر</a></li>
                    </ul>
                </li>
                <li class="menu"><a <?= $pages; ?> href="javascript:void(0)"><i
                                class="fa fa-caret-left caret-left-red pull-left"></i><i
                                class="fa fa-file sidebar-icon fa-lg"></i><span>صفحات سایت</span></a>
                    <ul>
                        <li><a href="<?= base_url('dashboard/pages/contact') ?>">تماس با ما</a></li>
                        <li><a href="<?= base_url('dashboard/pages/about') ?>">درباره ما</a></li>
                    </ul>
                </li>
                <li>
                    <?php
                    $this->load->model('Message_model');
                    $count_messages = Message_model::where('status', 'unread')->count();
                    ?>
                    <?php if ($count_messages > 0) : ?>
                        <span class="badge count-badge animated flash  pull-left"><?= $count_messages; ?></span>
                    <?php endif; ?>
                    <a <?= $messages; ?> href="<?= base_url('dashboard/messages'); ?>"><i
                                class="fa fa-envelope sidebar-icon fa-lg"></i><span>پیام های دریافتی</span></a>

                </li>

                <li>


                    <a <?= $files; ?> href="<?= base_url('dashboard/files'); ?>"><i
                                class="fa fa-file-text sidebar-icon fa-lg"></i><span>مدیریت فایل ها</span></a>

                </li>

            </ul>
        </div>
        <div class="col-sm-10 col-sm-offset-2">
            <?php $this->load->view('dashboard/inc/message'); ?>


