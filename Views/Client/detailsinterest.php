<div class="container">
    <h3><?php echo $model1["Name"]." ".$model1["LastName"];?></h3>
    <div class="row">
        <div class="col-sm ">
            <div class="card text-center border-dark" style="width: 21rem;">
                <div class="card-header text-white bg-secondary">
                    <h5>Reporte de pago</h5>
                </div>
                <div class="card-body">
                    <div class="col-md-10">
                        <div class="row">
                            <p>Interes: </p>
                            &nbsp;
                            <p><?php echo $model2.DecimalFormat::number_format($model1["Interests"]);?></p>
                        </div>
                        <div class="row">
                            <p>Cuotas: </p>
                            &nbsp;
                            <p><?php echo $model1["Fee"];?></p>
                        </div>
                        <div class="row">
                            <p>Pago: </p>
                            &nbsp;
                            <p><?php echo $model2.DecimalFormat::number_format($model1["Payment"]);?></p>
                        </div>
                        <div class="row">
                            <p>Cambio: </p>
                            &nbsp;
                            <p><?php echo $model2.DecimalFormat::number_format($model1["Changes"]);?></p>
                        </div>
                        <div class="row">
                            <p>Fecha del pago: </p>
                            &nbsp;
                            <?php if ($model1["Date"] == NULL){ ?>
                            <p class="text-danger">No disponible.</p>
                            <?php }else{ ?>
                            <p><?php echo date("d-m-Y", strtotime($model1["Date"]));?></p>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <p>Ticket: </p>
                            &nbsp;
                            <p><?php echo $model1["Ticket"];?></p>
                        </div>
                        <div class="row">
                            <p>Usuario: </p>
                            &nbsp;
                            <p><?php echo $model1["User"];?></p>
                        </div>
                        <div class="row">
                            <form action="TicketInterest" method="get">
                                <input type="submit" value="Ticket" class="btn btn-outline-info btn-sm">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>