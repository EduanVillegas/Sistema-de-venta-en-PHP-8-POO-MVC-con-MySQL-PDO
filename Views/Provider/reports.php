<div class="container">
    <h3><?php echo $model1[0][0]["Provider"];?></h3>
    <div class="row">
        <div class="col-sm ">
            <form action="Payment" method="post">
                <div class="card text-center border-secondary">
                    <div class="card-header text-white bg-secondary">
                        <h5>Reportes de pagos</h5>
                    </div>
                    <div class="card-body">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" data-toggle="tab" href="#nav-fee" role="tab"
                                    aria-selected="true" onclick="provider.SetSection(1)">Cuotas</a>

                                <a class="nav-item nav-link" data-toggle="tab" href="#nav-waytopay" role="tab"
                                    aria-selected="true" onclick="provider.SetSection(2)">Forma de pago</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-fee" role="tabpanel">
                                <div class="col-md-10">
                                    <div class="row">
                                        <p>Deuda: </p>
                                        &nbsp;
                                        <p> <?php echo $model1[1].DecimalFormat::number_format($model1[0][0]["CurrentDebt"]);?>
                                        </p>
                                        <input type="hidden" value="<?php echo $model1[0][0]["CurrentDebt"];?>"
                                            id="currentDebt" />
                                    </div>
                                    <div class="row">
                                        <p>Pago: </p>
                                        &nbsp;
                                        <p><?php echo $model1[1].DecimalFormat::number_format($model1[0][0]["LastPayment"]);?>
                                        </p>
                                    </div>
                                    <div class="row">
                                    <?php if ($model1[0][0]["Agreement"] == 'Q'){ ?>
                                        <p>Cuotas quincenales: </p>
                                        <?php }else{ ?>
                                            <p>Cuotas por mes: </p>
                                        <?php } ?>
                                        &nbsp;
                                        <p><?php echo $model1[1].DecimalFormat::number_format($model1[0][0]["Monthly"]);?>
                                        </p>
                                        <input type="hidden" value="<?php echo $model1[0][0]["Monthly"];?>"
                                            id="monthly">
                                    </div>
                                    <div class="row">
                                        <p>Fecha del pago: </p>
                                        &nbsp;
                                        <?php if ($model1[0][0]["DatePayment"] == NULL){ ?>
                                        <p class="text-danger">No disponible.</p>
                                        <?php }else{ ?>
                                        <p><?php echo date("d-m-Y", strtotime($model1[0][0]["DatePayment"]));?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <p>Ticket: </p>
                                        &nbsp;
                                        <p><?php echo $model1[0][0]["Ticket"];?></p>
                                    </div>
                                    <div class="row">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" class="form-check-input" id="inlineRadio1" value="1"
                                                name="radioOptions">
                                            <label class="form-check-label" for="inlineRadio1">Cuotas</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <input type="number" id="ProviderFees"
                                                onkeyup="return provider.Payments(event,this)" min="0"
                                                class="form-control" name="ProviderFees" style="width: 8rem;"
                                                value="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-waytopay" role="tabpanel">
                                <div class="col-md-10">
                                    <div class="row">
                                        <p>Deuda total: </p>
                                        &nbsp;
                                        <p><?php echo $model1[1].DecimalFormat::number_format($model1[0][0]["CurrentDebt"]);?>
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p>Forma de pago </p>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" id="inlineRadio2"
                                                        name="RadioOptions1" value="1">
                                                    <label class="form-check-label" for="inlineRadio2">Quincenal</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" id="inlineRadio3"
                                                        name="RadioOptions1" value="2">
                                                    <label class="form-check-label" for="inlineRadio3">Mensual</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <p id="fee">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Pagos" class="form-control" autofocus
                                                onkeyup="return provider.Payments(event,this)" id="PaymentProvider"
                                                autocomplete="off" name="payment" />

                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <p class="text-danger" id="paymentMessage">
                                                    <?php echo $model3->LastPayment ?? "" ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="submit" id="payment" value="Efectuar pago"
                                                class="btn btn-success btn-block">
                                            <input type="hidden" value="<?php echo $model1[0][0]["IdProviders"];?>"
                                                name="idProvider">
                                                <input type="hidden" value="1" name="SetSection" id="SetSection">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="card border-secondary">
            <div class="card-header text-white bg-secondary">
                <form action="GetPayments" method="post">
                    <div class="row">
                        <div class="col-sm">
                            <label class="form-check-label">Inicio</label>
                            <input type="text" name="date1" id="fecha" class="datepicker form-control"
                                autocomplete="off" style="height: 30px">
                        </div>
                        <div class="col-sm">
                            <label class="form-check-label">Final</label>
                            <input type="text" name="date2" id="fecha" class="datepicker form-control"
                                autocomplete="off" style="height: 30px">
                        </div>
                        <div class="col-sm">
                            <input type="submit" value="Filtrar" class="btn btn-info btn-sm" style="margin-top: 23px">
                        </div>
                    </div>
                </form>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Debt
                                </th>
                                <th>
                                    CurrentDebt
                                </th>
                                <th>
                                    Payment
                                </th>
                                <th>
                                    Date
                                </th>
                                <th>
                                    Opciones
                                </th>
                            </tr>
                        </thead>
                        <?php 
                            if (is_array($model2["results"])){

                              foreach ($model2["results"] as $key => $value) { 
                                    
                        ?>
                        <tr>
                            <td>
                                <p><?php echo $model1[1].DecimalFormat::number_format($value["Debt"]);?></p>
                            </td>
                            <td>
                                <p><?php echo $model1[1].DecimalFormat::number_format($value["CurrentDebt"]);?></p>
                            </td>
                            <td>
                                <p><?php echo $model1[1].DecimalFormat::number_format($value["Payment"]);?></p>
                            </td>
                            <td>
                                <p><?php echo date("d-m-Y", strtotime($value["Date"]));?></p>
                            </td>
                            <td>
                                <a href="<?php echo URL.'Provider/DetailsDebt?idDebt='.$value["IdPayments"]?>"
                                    class="btn btn-outline-info btn-sm">
                                    Reportes
                                </a>
                            </td>
                        <tr>
                            <?php
                                }
                            }else {
                        ?>
                            <p class="text-danger"><?php echo $model2[0]["results"];?></p>
                            <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>