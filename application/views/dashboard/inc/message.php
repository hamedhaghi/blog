<?php if(isset($_SESSION['message'])) : ?>
    <div class="alert alert-<?php echo $_SESSION['status']; ?>">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <ul>
            <?php echo $_SESSION['message']; ?>
        </ul>
    </div>
<?php endif; ?>