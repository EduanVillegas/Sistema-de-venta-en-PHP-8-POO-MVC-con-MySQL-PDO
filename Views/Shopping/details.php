<div class="container">
    <h3><?php echo $model1[0]["Description"]; ?></h3>
    <div class="row">
        <div class="col-sm">
            <div class="card text-center" style="width: 21rem;">
                <div class="card-header ">
                    <?php echo "<img class='imageUserDetails' src='data:image/jpg;base64," . $model1[0]["Image"] . "' />"; ?>
                </div>
                <div class="card-body">
                    <div class="col-md-10">
                        <div class="row">
                            <p><?php echo $model1[0]["Description"]; ?></p>
                        </div>
                        <div class="row">
                            <p class="text-success">
                                <?php echo $model2 . DecimalFormat::number_format($model1[0]["Price"]); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <table>
                                <tbody>
                                    <tr>
                                        <th>
                                            Informacion
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            Ticket
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><?php echo $model1[0]["Ticket"]; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Description
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><?php echo $model1[0]["Description"]; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Precio
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class=" text-success">
                                                <?php echo $model2 . DecimalFormat::number_format($model1[0]["Price"]); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Cantidad
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><?php echo $model1[0]["Quantity"]; ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm">
                            <table>
                                <tbody>
                                    <tr>
                                        <th>
                                            Importe
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="text-success">
                                                <?php echo $model2 . DecimalFormat::number_format($model1[0]["Amount"]); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Fecha
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                <?php if ($model1[0]["Date"] == NULL) { ?>
                                            <p class="text-danger">No disponible.</p>
                                        <?php } else { ?>
                                            <p><?php echo date("d-m-Y", strtotime($model1[0]["Date"])); ?></p>
                                        <?php } ?>
                                        </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Proveedor
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><?php echo $model1[0]["Provider"]; ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>