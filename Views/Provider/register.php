<div class="container p-4">
    <form method="post" action="AddProvider" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card text-center border-secondary">
                    <div class="card-header ">
                        <output id="imageProvider">
                            <img src="<?php

                                        if ($model1 != null) {
                                            if ($model1->Image != null) {
                                                echo 'data:image/jpeg;base64,' . $model1->Image;
                                            } else {
                                                echo URL . RQ . 'images/default.png';
                                            }
                                        } else {
                                            echo URL . RQ . 'images/default.png';
                                        }

                                        ?>" class="responsive-img" />
                        </output>
                    </div>
                    <div class="card-body">
                        <div class="caption text-center">
                            <label class="btn btn-outline-secondary" for="files">Cargar foto</label>
                            <input accept="image/*" type="file" name="file" id="files">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-5">
                <div class="card text-center border-secondary">
                    <div class="card-header text-white bg-secondary">
                        <h3>Registrar Provider</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text" name="provider" placeholder="Provider" class="form-control" value="<?php echo $model1->Provider ?? "" ?>" onkeypress="new Provider().ClearMessages(this);" />
                            <span id="provider" class="text-danger"><?php echo $model2->Provider ?? "" ?></span>
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone" placeholder="Phone Number" class="form-control" value="<?php echo $model1->Phone ?? "" ?>" onkeypress="new Provider().ClearMessages(this);" />
                            <span id="phone" class="text-danger"><?php echo $model2->Phone ?? "" ?></span>
                        </div>
                        <div class="form-group">
                            <input type="text" name="direction" placeholder="Direction" class="form-control" value="<?php echo $model1->Direction ?? "" ?>" onkeypress="new Provider().ClearMessages(this);" />
                            <span id="direction" class="text-danger"><?php echo $model2->Direction ?? "" ?></span>
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" class="form-control" value="<?php echo $model1->Email ?? "" ?>" onkeypress="new Provider().ClearMessages(this);" />
                            <span id="email" class="text-danger"><?php echo $model2->Email ?? "" ?></span>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-outline-success">Register</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <a href="<?php echo URL ?>Provider/Cancel" class="btn btn-outline-warning">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="idProvider" value="<?php echo $model1->IdProvider ?? 0 ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>