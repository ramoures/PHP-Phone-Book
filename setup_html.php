<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="view/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="view/assets/css/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="my-4">
    <div class="container col-12 col-md-9 col-lg-6 col-xl-5">
    <header class="bg-secondary rounded-2 rounded-bottom-0 p-2 text-white">
        <h1 class="p-0 m-0 h2 px-1">PHP Phone Book Setup</h1>
    </header>
    <main class="border border-secondary border-end border-start p-4 pb-4 d-grid gap-3">
        
        <?php if($step === 1): ?>
            <div class="lh-lg d-grid gap-3">
                <h2 class="col h3 text-primary">&#9632; Step1</h2>
                <h3 class="h4">Setup MySQL required Tables</h3>
                <?php if($dbConnected): ?>
                    <ul class="fs-5 d-grid gap-2">
                        <div class="d-flex lh-1 align-items-center gap-2">
                        1. <i>admins</i> table created.<?php if($success1) print '<i class="border rounded pe-2 bi bi-check-lg fs-4 bg-success text-white">Success</i>'; else  print '<i class="border rounded pe-2 bi bi-x-lg fs-4 bg-danger text-white">Error!</i>' ?>
                        </div>
                        <?php if($success1):?>
                        <div class="d-flex lh-1 align-items-center gap-2">
                        2. <i>phone_numbers</i> table created.<?php if($success2) print '<i class="border rounded pe-2 bi bi-check-lg fs-4 bg-success text-white">Success</i>'; else  print '<i class="border rounded pe-2 bi bi-x-lg fs-4 bg-danger text-white">Error!</i>' ?>
                        </div>
                        <?php endif ?>
                        <?php if($success1 && $success2): ?>
                        <div class="d-flex lh-1 align-items-center gap-2">
                        3. <i>upload</i> table created.<?php if($success3) print '<i class="border rounded pe-2 bi bi-check-lg fs-4 bg-success text-white">Success</i>'; else  print '<i class="border rounded pe-2 bi bi-x-lg fs-4 bg-danger text-white">Error!</i>' ?>
                        </div>
                        <?php endif ?>
                    </ul>
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <div class="flex-fill d-flex align-items-center">
                        <hr class="flex-fill "><i class="bi text-secondary fs-5 bi-arrow-right"></i>
                        </div>
                        <button type="button" class="btn btn-lg btn-primary" <?php if(!$dbConnected || !$success1 || !$success2 || !$success3):?>disabled<?php endif ?> onclick="location.href='?step=2'">Next</button>
                    </div>
                <?php else: ?>
                    <h5 class="text-danger d-grid">
    
                        <div><?php print $msg ?></div>
                        <div class="d-flex justify-content-center">
                        <img class="img-fluid rounded mt-4" src="setup.png" width="500px">
                        </div>
                    </h5>
                <?php endif ?>
            </div>
        <?php endif ?>
        <?php if($step === 2 && $dbConnected && $success1 && $success2 && $success3): ?>
            <div class="step2 d-grid gap-3">
                <h2 class="col h3 text-primary">&#9632; Step2</h2>
                <div class="flex-fill d-flex justify-content-between">
                <h3 class="h4"> Admin Signup</h3>
                <small class="text-secondary">You can change later.</small>
                </div>
                <form method="post">
                <div class="d-flex justify-content-center mb-5">
                    
                    <div class="col-11 col-md-9 col-lg-7 col-xl-6 d-grid gap-2">
                    <?php if($alert): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-square-fill me-2" ></i><?php print $alert ?>
                        </div>
                    <?php endif ?>
                        <label for="username">Username</label>
                        <input name="username" id="username" value="<?php print $username ?>" class="form-control form-control-lg">
                        <label for="password">Password</label>
                        <div class="d-flex flex-wrap gap-1">
                            <input type="password" name="password" value="<?php print $password ?>" id="password" class="form-control form-control-lg col">
                            <button type="button" class="btn col-auto btn-lg lh-1 border rounded text-body showPass user-select-none">
                            <i class="bi bi-eye fs-4 opacity-75"></i>
                        </div>
                        <label for="confirm">Confirm Password</label>
                        <div class="d-flex flex-wrap gap-1">
                            <input type="password" name="confirm" id="confirm" class="form-control form-control-lg col">
                            <button type="button" class="btn col-auto btn-lg lh-1 border rounded text-body showPass user-select-none">
                            <i class="bi bi-eye fs-4 opacity-75"></i>
                        </div>
                    </button>
                    </div>
                </div>
                <h5 class="text-danger">
                        <?php print $msg ?>
                    </h5>
                <div class="d-flex justify-content-end align-items-center gap-3">
                    <button type="button" class="btn btn-lg btn-secondary" onclick="location.href='?step=1'">Back</button>
                    <div class="flex-fill d-flex align-items-center">
                    <hr class="flex-fill "><i class="bi text-secondary fs-5 bi-arrow-right"></i>
                    </div>
                    <button type="submit" value="1" name="btn_submit" class="btn btn-lg btn-success next">Finish</button>
                </div>
                </form>
     
            </div>
        <?php endif ?>

    </main>
    <footer class="bg-secondary rounded-2 rounded-top-0 p-2 d-flex text-white">
    <span class="align-items-center d-flex gap-1">PHP Phone Book 1.0 . Licensed MIT  . <a class="text-white" href="https://github.com/ramoures">{github}</a>
    </footer>
    </div>
    <script src="view/assets/js/jquery-3.7.0.min.js"></script>
    <script>
        $('.showPass').on('click',function(){
            const type = $(this).parent().find('input').attr('type')
            if(type==='password')
            {
                $(this).parent().find('input').attr('type','text');
                $(this).find('i').removeAttr('class').addClass('bi bi-eye-slash fs-4 opacity-75')
            }
            else
            {
                $(this).parent().find('input').attr('type','password');
                $(this).find('i').removeAttr('class').addClass('bi bi-eye fs-4 opacity-75')
            }
        });
    </script>
</body>
</html>