<div class="container">

    <div class="row">
        <div class="col-sm ">
            <div class="card text-center border-secondary">
                <div class="card-header text-white bg-secondary">
                    <h4><?php echo $model1[0]["Name"] . " " . $model1[0]["LastName"]; ?></h4>
                </div>
                <div class="card-body">
                    <?php echo "<img class='imageUserDetails' src='data:image/jpg;base64," . $model1[0]["Image"] . "' />"; ?>
                    <div class="col-md-10">
                        <div class="row">
                            <p> <?php echo $model1[0]["Name"] . " " . $model1[0]["LastName"]; ?></p>
                        </div>
                        <div class="row">
                            <p>Credit: </p>
                            &nbsp;
                            <?php if ($model1[0]["Credit"]) { ?>
                                <p class="text-success">Disponible.</p>
                            <?php } else { ?>
                                <p class="text-danger">No disponible.</p>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <a class="btn btn-outline-dark " href="<?php echo URL . 'Client/Reports?id=' . $model1[0]["IdClient"] ?>">
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
                                    NID
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <p><?php echo $model1[0]["NID"]; ?></p>
                                </td>
                            </tr>
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
                                        "IdClient" => $model1[0]["IdClient"],
                                        "NID" => $model1[0]["NID"],
                                        "Name" => $model1[0]["Name"],
                                        "LastName" => $model1[0]["LastName"],
                                        "Email" => $model1[0]["Email"],
                                        "Phone" => $model1[0]["Phone"],
                                        "Direction" => $model1[0]["Direction"],
                                        "Credit" => $model1[0]["Credit"],
                                        "Date" => $model1[0]["Date"],
                                        "State" => $model1[0]["State"],
                                        "Image" => $model1[0]["Image"]
                                    )));
                                    Session::setSession('model2', serialize(array()));
                                    ?>
                                    <a href="<?php echo URL . 'Client/Register' ?>" class="btn btn-outline-dark">
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