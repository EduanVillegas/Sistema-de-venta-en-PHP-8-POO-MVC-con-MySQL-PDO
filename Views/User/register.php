<div class="container p-4">
    <form method="post" action="AddUser" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card text-center">
                    <div class="card-header ">
                        <output id="imageUser">
                            <img src="<?php 

                            if($model1 != null){
                                if($model1->Image != null){
                                    echo 'data:image/jpeg;base64,'.$model1->Image; 
                                }else{
                                    echo URL.RQ.'images/default.png'; 
                                }
                            }else{
                                echo URL.RQ.'images/default.png'; 
                            }

                            ?>" class="responsive-img" />
                        </output>                        
                    </div>
                    <div class="card-body">
                        <div class="caption text-center">
                            <label class="btn btn-primary" for="files">Cargar foto</label>
                            <input accept="image/*" type="file" name="file" id="files">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-5">
                <div class="panel  panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Registrar usuarios</h3>
                    </div>
                    <div class="panel-body">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <div id="header" class="bg-info">
                                        <h2 class="mb-0 t">
                                            <button class="btn btn-link text-light " type="button" data-toggle="collapse"
                                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Ingresar informacion
                                            </button>
                                        </h2>
                                    </div>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                     data-parent="#accordionExample">
                                     <div class="card-body">
                                        <div class="form-group">
                                            <input type="text" name="nid" placeholder="NID" class="form-control" value="<?php echo $model1->NID ?? "" ?>" onkeypress="new User().ClearMessages(this);" autofocus />
                                            <span id="nid" class="text-danger"><?php echo $model2->NID ?? "" ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="name" placeholder="Name"
                                            class="form-control" value="<?php echo $model1->Name ?? "" ?>" onkeypress="new User().ClearMessages(this);"/>
                                            <span id="name" class="text-danger"><?php echo $model2->Name ?? "" ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="lastname" placeholder="Last Name" class="form-control" value="<?php echo $model1->LastName ?? "" ?>" onkeypress="new User().ClearMessages(this);"/>
                                            <span id="lastname" class="text-danger"><?php echo $model2->LastName ?? "" ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="phone" placeholder="Phone Number" class="form-control" value="<?php echo $model1->Phone ?? "" ?>"  onkeypress="new User().ClearMessages(this);"/>
                                            <span  id="phone" class="text-danger"><?php echo $model2->Phone ?? "" ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="direction" placeholder="Direction" class="form-control" value="<?php echo $model1->Direction ?? "" ?>"  onkeypress="new User().ClearMessages(this);"/>
                                            <span  id="direction" class="text-danger"><?php echo $model2->Direction ?? "" ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="user" placeholder="User" class="form-control" value="<?php echo $model1->User ?? "" ?>" onkeypress="new User().ClearMessages(this);"/>
                                            <span id="user" class="text-danger"><?php echo $model2->User ?? "" ?></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" placeholder="Email" class="form-control" value="<?php echo $model1->Email ?? "" ?>" onkeypress="new User().ClearMessages(this);"/>
                                            <span id="email" class="text-danger"><?php echo $model2->Email ?? "" ?></span>
                                        </div>
                                        <?php if($model1 == null || 0 == $model1->IdUser){ ?>
                                        <div class="form-group">
                                            <input type="password" name="password" placeholder="Password" class="form-control" value="<?php echo $model1->Password ?? "" ?>" onkeypress="new User().ClearMessages(this);"/>
                                            <span id="password" class="text-danger"><?php echo $model2->Password ?? "" ?></span>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <select onchange="new User().ClearMessages(this);" class="form-control" name="role">
                                            <?php 
                                                if($model2 == null){
                                                   echo "<option>Seleccione un role</option>";
                                                }
                                                foreach ($model3 as $key => $value) {
                                                    echo "<option>".$value["Role"]."</option>";
                                                }
                                            ?>
                                            </select>
                                            <span id="role" class="text-danger"><?php echo $model2->Role ?? "" ?></span>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                               <div class="col-md-12">
                                                <button type="submit" class="btn btn-success">Register</button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                   <a href="<?php echo URL?>User/Cancel" class="btn btn-warning text-white">Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                         <input type="hidden" name="iduser" value="<?php echo $model1->IdUser ?? 0 ?>"/>
                                        </div>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>