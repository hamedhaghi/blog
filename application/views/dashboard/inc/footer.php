</div>
</div>
</div>
<script src="<?= base_url('assets/dashboard/js/jquery-3.2.1.min.js'); ?>?v=<?= FILE_VERSION; ?>"></script>
<script src="<?= base_url('assets/dashboard/js/bootstrap.min.js'); ?>?v=<?= FILE_VERSION; ?>"></script>
<script src="<?= base_url('assets/dashboard/js/sweetalert.min.js'); ?>?v=<?= FILE_VERSION; ?>"></script>
<script src="<?= base_url('assets/dashboard/js/dashboard.js'); ?>?v=<?= FILE_VERSION; ?>"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php if (isset($script)): ?>
    <?php foreach ($script as $item) : ?>
        <script src="<?= base_url('assets/dashboard/js/' . $item); ?>?v=<?= FILE_VERSION; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>