<div class="container p-4">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <div class="card text-center">
                <div class="card-header">
                    <h3>Login</h3>
                </div>
                <img src="<?php echo URL.RQ ?>images/icons/logo-google.png" class="mx-auto w-25">
                <div class="card-body">
                    <form action="Index/Login" method="POST">
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" class="form-control" value="<?php echo $model1->Email ?? "" ?>" onkeypress="new User().ClearMessages(this);"/>
                            <span id="email" class="text-danger"><?php echo $model2->Email ?? "" ?></span>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" class="form-control" value="<?php echo $model1->Password ?? "" ?>" onkeypress="new User().ClearMessages(this);"/>
                            <span id="password" class="text-danger"><?php echo $model2->Password ?? "" ?></span>
                        </div>
                        <div class="form-group">
                            <span class="text-danger">
                            <?php echo $model2->Role ?? "" ?>
                            </span>
                        </div>
                        <button class="btn btn-primary btn-block">
                            SingIn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>