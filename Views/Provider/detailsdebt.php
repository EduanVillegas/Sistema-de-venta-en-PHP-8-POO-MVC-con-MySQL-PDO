<?php
if (is_array($model1)) {
?>
    <div class='container'>
        <h3><?php echo $model3[0]["Provider"]; ?></h3>
        <div class='row'>
            <div class='col-sm '>
                <div class='card text-center border-secondary' style='width: 21rem;'>
                    <div class='card-header text-white bg-secondary'>
                        <h5>Reporte de pago</h5>
                    </div>
                    <div class='card-body'>
                        <div class='col-md-10'>
                            <div class='row'>
                                <p>Deuda: </p>
                                &nbsp;
                                <p><?php echo $model2 . DecimalFormat::number_format($model1["Debt"]); ?></p>
                            </div>
                            <div class="row">
                                <?php if ($model1["Agreement"] == 'Q') { ?>
                                    <p>Cuotas quincenales: </p>
                                <?php } else { ?>
                                    <p>Cuotas por mes: </p>
                                <?php } ?>

                                &nbsp;
                                <p><?php echo $model2 . DecimalFormat::number_format($model1["Monthly"]); ?>
                                </p>

                            </div>
                            <div class='row'>
                                <p>Fecha deuda: </p>
                                <?php if ($model1["DateDebt"] == NULL) { ?>
                                    <p class="text-danger">No disponible.</p>
                                <?php } else { ?>
                                    <p><?php echo date("d-m-Y", strtotime($model1["DateDebt"])); ?></p>
                                <?php } ?>
                            </div>
                            <div class='row'>
                                <p>Pago: </p>
                                &nbsp;
                                <p><?php echo $model2 . DecimalFormat::number_format($model1["Payment"]); ?></p>
                            </div>
                            <div class='row'>
                                <p>Fecha del pago: </p>
                                <?php if ($model1["Date"] == NULL) { ?>
                                    <p class="text-danger">No disponible.</p>
                                <?php } else { ?>
                                    <p><?php echo date("d-m-Y", strtotime($model1["Date"])); ?></p>
                                <?php } ?>
                            </div>
                            <div class='row'>
                                <p>Deuda actual: </p>
                                &nbsp;
                                <p><?php echo $model2 . DecimalFormat::number_format($model1["CurrentDebt"]); ?></p>
                            </div>
                            <div class='row'>
                                <p>Ticket: </p>
                                &nbsp;
                                <p><?php echo $model1["Ticket"]; ?></p>
                            </div>
                            <div class="row">
                                <p>Usuario: </p>
                                &nbsp;
                                <p><?php echo $model1["Name"] . " " . $model1["LastName"]; ?></p>
                            </div>
                            <div class='row'>
                                <form action='TicketDebt' method='get'>
                                    <input type='submit' value='Ticket' class='btn btn-outline-info btn-sm'>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {

?>
    <p><?php echo $model1;
        ?></p>
<?php
}
?>