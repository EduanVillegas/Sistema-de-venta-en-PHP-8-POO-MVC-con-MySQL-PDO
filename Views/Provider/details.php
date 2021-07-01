<div class="container">
    <div class="row">
        <div class="col-sm ">
            <div class="card text-center border-secondary">
                <div class="card-header text-white bg-secondary">
                    <h3><?php echo $model1[0]["Provider"]; ?></h3>
                </div>

                <div class="card-body">
                    <?php echo "<img class='imageUserDetails' src='data:image/jpg;base64," . $model1[0]["Image"] . "' />"; ?>
                    <div class="col-md-10">
                        <div class="row">
                            <p> <?php echo $model1[0]["Provider"]; ?></p>
                        </div>
                        <div class="row">
                            <p>State: </p>
                            &nbsp;
                            <?php if ($model1[0]["State"]) { ?>
                                <p class="text-success">Disponible.</p>
                            <?php } else { ?>
                                <p class="text-danger">No disponible.</p>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <a class="btn btn-outline-dark" href="<?php echo URL . 'Provider/Reports?id=' . $model1[0]["IdProvider"] ?>">
                                Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-secondary">
                <div class="card-header text-white bg-secondary">
                    <h5>Informacion</h5>
                </div>
                <div class="card-body">
                    <table class="tableCursos">
                        <tbody>

                            <tr>
                                <th>
                                    Telefono
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p><?php echo $model1[0]["Phone"]; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Correo electronico
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p><?php echo $model1[0]["Email"]; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    Session::setSession('model1', serialize(array(
                                        "IdProvider" => $model1[0]["IdProvider"],
                                        "Provider" => $model1[0]["Provider"],
                                        "Email" => $model1[0]["Email"],
                                        "Phone" => $model1[0]["Phone"],
                                        "Direction" => $model1[0]["Direction"],
                                        "Date" => $model1[0]["Date"],
                                        "State" => $model1[0]["State"],
                                        "Image" => $model1[0]["Image"]
                                    )));
                                    Session::setSession('model2', serialize(array()));
                                    ?>
                                    <a href="<?php echo URL . 'Provider/Register' ?>" class="btn btn-outline-dark">
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