<?php $this->load->view('dashboard/inc/header'); ?>
<div class="row mt">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">گزارش کلی<i class="fa fa fa-database pull-right"></i></h3>
            </div>
            <div class="panel-body">
                <div class="col-sm-4 text-center">
                    <div class="total-report">
                        <span class="total-report-span">کاربر</span><br><i
                                class="fa fa-user fa-4x total-report-i"></i><br><span
                                class="total-report-span"><?= $users; ?></span>
                    </div>
                </div>
                <div class="col-sm-4 text-center">

                </div>
                <div class="col-sm-4 text-center">

                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">جدیدترین کاربران<i class="fa fa fa-users pull-right"></i></h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($latest_users)) : ?>
                    <!-- Start Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-baseline">
                            <tbody class="text-center">
                            <?php foreach ($latest_users as $latest_user) : ?>
                                <?php
                                $picture = base_url('assets/img/user1.png');
                                if (!empty($latest_user->picture) && file_exists(FCPATH . "uploads/profiles/{$latest_user->picture}")) {
                                    $picture = base_url("uploads/profiles/{$latest_user->picture}");
                                }
                                ?>
                                <tr>
                                    <td><img src="<?= $picture; ?>" class="img-circle table-img"
                                             alt=""></td>
                                    <td><?= $latest_user->username; ?></td>
                                    <td><?= $this->jdf->jdate('H:i Y/m/d', strtotime($latest_user->created_at), '', '', 'en'); ?></td>
                                    <td><a href="<?= base_url('dashboard/user_edit/' . $latest_user->id); ?>"
                                           title="مشاهده">مشاهده</a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->
                <?php endif; ?>

            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">جدیدترین سفارشات<i class="fa fa fa-th-list pull-right"></i></h3>
            </div>
            <div class="panel-body">
                <!-- Start Table -->
                <?php if (!empty($latest_orders)) : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-baseline">
                            <tbody class="text-center">
                            <?php foreach ($latest_orders as $latest_order) : ?>
                                <tr>
                                    <td><?= $latest_order->user->username; ?></td>
                                    <td><?= $this->jdf->jdate('H:i Y/m/d', strtotime($latest_order->created_at), '', '', 'en'); ?></td>
                                    <td><a href="<?= base_url('dashboard/order/' . $latest_order->id); ?>"
                                           title="مشاهده سبد خرید"><i class="fa fa-shopping-basket"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <!-- End Table -->
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">آمار فروش سال جاری<i class="fa fa-bar-chart pull-right"></i></h3>
            </div>
            <div class="panel-body">
                <!-- Start Chart -->

                <textarea style="display: none; visibility: hidden;" id="stats"><?= $stats; ?></textarea>
                <canvas id="myChart" width="400" height="300"></canvas>
                <!--End Chart -->
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">پیام های دریافتی &nbsp;(تماس با ما)<i class="fa fa fa-envelope pull-right"></i>
                </h3>
            </div>
            <div class="panel-body">
                <!-- Start Table -->
                <?php if (!empty($messages)) : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-baseline">
                            <tbody class="text-center">
                            <?php foreach ($messages as $message) : ?>
                                <tr>
                                    <td><?= $message->fullname; ?></td>
                                    <td><?= $message->email; ?></td>
                                    <td><?= $this->jdf->jdate('H:i Y/m/d', strtotime($message->created_at), '', '', 'en'); ?></td>
                                    <td><a href="<?= base_url('dashboard/message/' . $message->id); ?>">مشاهده</a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <!-- End Table -->
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('dashboard/inc/footer'); ?>
