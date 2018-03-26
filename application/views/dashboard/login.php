<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/dashboard/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/dashboard/fonts/font-awesome/css/font-awesome.min.css'); ?>">
    <style type="text/css">
        @font-face {
            font-family: 'Yekan';
            src: url('../assets/dashboard/fonts/Yekan.eot?-wwn5ej');
            src: url('../assets/dashboard/fonts/Yekan.eot?#iefix-wwn5ej') format('embedded-opentype'),
            url('../assets/dashboard/fonts/Yekan.woff?-wwn5ej') format('woff'),
            url('../assets/dashboard/fonts/Yekan.ttf?-wwn5ej') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            background: #8e9eab; /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #eef2f3, #8e9eab); /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #eef2f3, #8e9eab); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }

        .message {
            font-family: 'Yekan';
            font-weight: bold;
            color: red;

        }

    </style>
    <title>Login</title>
</head>

<body>
<div class="container">
    <div class="row" style="margin-top: 300px;">

        <div class="col-sm-5 col-sm-offset-3">
            <?php echo form_open('login/admin'); ?>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-left" style="font-weight: bold;">Login</h3>
                    <h3 class="panel-title pull-right"><span class="glyphicon glyphicon-lock"></span></h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><span
                                        class="glyphicon glyphicon-user"></span></span>
                            <input type="text" class="form-control" placeholder="Username" name="identity"
                                   aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><span
                                        class="fa fa-key"></span></span>
                            <input type="password" class="form-control" placeholder="Password" name="password"
                                   aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <span class="message">
                            <?php
                            $error = Functions::read('error');
                            $text = Functions::read('text');
                            if ($error == 'yes') {
                                echo $text;
                            }
                            ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block">Login</button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</div>

</body>
</html>

