<div class="container">
    <h1><?php echo $model1[0]["Name"]." ".$model1[0]["LastName"];?></h1>
    <div class="row">
        <div class="col-sm ">
            <div class="card text-center" style="width: 21rem;">
                <div class="card-header ">
                <?php echo "<img class='imageUserDetails' src='data:image/jpg;base64,".$model1[0]["Image"]."' />";?>
                    
                </div>
                <div class="card-body">
                    <div class="col-md-10">
                        <div class="row">
                            <p> <?php echo $model1[0]["Name"]." ".$model1[0]["LastName"];?></p>
                        </div>
                        <div class="row">
                        <p>Role: </p>
                            &nbsp;
                            <p> <?php echo $model1[0]["Role"];?></p>
                        </div>
                        <div class="row">
                           <p>State: </p>
                            &nbsp;
                            <?php if ($model1[0]["State"]){ ?>                               
                                <p class="text-success">Disponible.</p>
                            <?php }else{ ?>
                                <p class="text-danger">No disponible.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <div class="card-body">
                    <table class="tableCursos">
                        <tbody>
                            <tr>
                                <th>
                                    Informacion
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    NID
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p><?php echo $model1[0]["NID"];?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Nombre
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p><?php echo $model1[0]["Name"]." ".$model1[0]["LastName"];?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Telefono
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p><?php echo $model1[0]["Phone"];?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Correo electronico
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p><?php echo $model1[0]["Email"];?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                 <?php 
                                 Session::setSession('model1',serialize(array(
                                    "IdUser"=>$model1[0]["IdUser"],
                                    "NID"=>$model1[0]["NID"],
                                   "Name"=>$model1[0]["Name"],
                                   "LastName"=>$model1[0]["LastName"],
                                   "Email"=>$model1[0]["Email"],
                                   "Phone"=>$model1[0]["Phone"],
                                   "Direction"=>$model1[0]["Direction"],
                                   "User"=>$model1[0]["User"],
                                   "Role"=>$model1[0]["Role"],
                                   "Image"=>$model1[0]["Image"]
                               )));
                               Session::setSession('model2',serialize(array()));
                                 ?>
                                    <a href="<?php echo URL.'User/Register'?>" class="btn btn-success ">
                                    Editar
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>