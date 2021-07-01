<div class="container">
    <h3><?php $model1[0][0]["Name"]." ".$model1[0][0]["LastName"];?></h3>
    <div class="row">
        <div class="col-sm ">
            <form action="Payment" method="post">
                <div class="card text-center border-secondary" >
                    <div class="card-header text-white bg-secondary">
                        <h5>Reportes de pagos</h5>
                    </div>
                    <div class="card-body">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" data-toggle="tab" href="#nav-fee" role="tab"
                                    aria-selected="true" onclick="client.SetSection(1)">Cuotas</a>

                                <a class="nav-item nav-link" data-toggle="tab" href="#nav-interests" role="tab"
                                    aria-selected="false" onclick="client.SetSection(2)">Intereses</a>

                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-fee" role="tabpanel">
                                <div class="col-md-10">
                                    <div class="row">
                                        <p>Deuda: </p>
                                        &nbsp;
                                        <p> <?php echo $model2[1].DecimalFormat::number_format($model1[0][0]["CurrentDebt"]);?> </p>
                                    </div>
                                    <div class="row">
                                        <p>Pago: </p>
                                        &nbsp;
                                        <p><?php echo $model2[1].DecimalFormat::number_format($model1[0][0]["LastPayment"]);?></p>
                                    </div>
                                    <div class="row">
                                        <p>Cuotas por mes: </p>
                                        &nbsp;
                                        <p><?php echo $model2[1].DecimalFormat::number_format($model1[0][0]["Monthly"]);?></p>
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
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-interests" role="tabpanel">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <p>Intereses</p>
                                            </th>
                                            <th>
                                                <p>Pago</p>
                                            </th>
                                            <th>
                                                <p>Fecha pago</p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p> <?php echo $model2[1].DecimalFormat::number_format($model1[1]["Interests"]);?> </p>
                                            </td>
                                            <td>
                                                <p> <?php echo $model2[1].DecimalFormat::number_format($model1[1]["Payment"]);?> </p>
                                            </td>
                                            <td>
                                                <?php if ($model1[1]["InterestDate"] == NULL){ ?>
                                                <p class="text-danger">No disponible.</p>
                                                <?php }else{ ?>
                                                <p><?php echo date("d-m-Y", strtotime($model1[1]["InterestDate"]));?>
                                                </p>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th>
                                                <p>Cambio</p>
                                            </th>
                                            <th>
                                                <p>Cuotas</p>
                                            </th>
                                            <th>
                                                <p>Total intreses </p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p> <?php echo $model2[1].DecimalFormat::number_format($model1[1]["Change"]);?> </p>
                                            </td>
                                            <td>
                                                <p><?php echo $model1[1]["Fee"];?></p>
                                            </td>
                                            <td>
                                                <input type="number" id="AmountFees"
                                                    onkeypress="return client.GetInterests(event,this, <?php echo $model1[1]['IdClient']; ?>)"
                                                    min="0" class="form-control" name="AmountFees"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-10">
                                    <div class="row">
                                        <p>Total a pagar <?php echo $model2[1];?> </p>
                                        &nbsp;
                                        <p class="text-danger" id="amountFees"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="inlineRadio1" name="radioOptions"
                                        value="1">
                                    <label class="form-check-label" for="inlineRadio1">Cuotas</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="inlineRadio2" name="radioOptions"
                                        value="2">
                                    <label class="form-check-label" for="inlineRadio2">Intereses</label>
                                </div>
                                <br />
                                <br />
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" id="Input_Payment" name="payment" placeholder="Pagos"
                                                class="form-control" autofocus autocomplete="off"
                                                onkeypress="return client.Payments(event,this)" />
                                            <span class="text-danger"></span>
                                            <input type="hidden" value="<?php echo $model1[1]["IdClient"];?>"
                                                name="idClient">
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
            <div class="card border-secondary ">
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
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" data-toggle="tab" href="#nav-fee2" role="tab"
                                aria-selected="true">Cuotas</a>

                            <a class="nav-item nav-link" data-toggle="tab" href="#nav-interests2" role="tab"
                                aria-selected="false">Intereses</a>

                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-fee2" role="tabpanel">
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
                                <tbody>
                                    <?php 
                                    if (is_array($model2[0]["results"])){
                                        foreach ($model2[0]["results"] as $key => $value) { 
                                    
                                    ?>
                                    <tr>
                                        <td>
                                            <p><?php echo $model2[1].DecimalFormat::number_format($value["Debt"]);?></p>
                                        </td>
                                        <td>
                                            <p><?php echo $model2[1].DecimalFormat::number_format($value["CurrentDebt"]);?></p>
                                        </td>
                                        <td>
                                            <p><?php echo $model2[1].DecimalFormat::number_format($value["Payment"]);?></p>
                                        </td>
                                        <td>
                                            <p><?php echo date("d-m-Y", strtotime($value["Date"]));?></p>
                                        </td>
                                        <td>
                                            <a href="<?php echo URL.'Client/DetailsDebt?idDebt='.$value["IdPayments"]?>"
                                                class="btn btn-outline-info btn-sm">
                                                Reportes
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }else {
                                        ?>
                                    <p class="text-danger"><?php echo $model2[0]["results"];?></p>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-interests2" role="tabpanel">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                           Interests
                                        </th>
                                        <th>
                                            Payment
                                        </th>
                                        <th>
                                            Fee
                                        </th>
                                        <th>
                                            Date
                                        </th>
                                        <th>
                                            Opciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    if (is_array($model2[2]["results"])){
                                        foreach ($model2[2]["results"] as $key => $value) { 
                                    
                                    ?>
                                    <tr>
                                        <td>
                                            <p><?php echo $model2[1].DecimalFormat::number_format($value["Interests"]);?></p>
                                        </td>
                                        <td>
                                            <p><?php echo $model2[1].DecimalFormat::number_format($value["Payment"]);?></p>
                                        </td>
                                        <td>
                                            <p><?php echo $value["Fee"];?></p>
                                        </td>
                                        <td>
                                            <p><?php echo date("d-m-Y", strtotime($value["Date"]));?></p>
                                        </td>
                                        <td>
                                            <a href="<?php echo URL.'Client/DetailsInterest?idInterest='.$value["IdPaymentsInterest"]?>"
                                                class="btn btn-outline-info btn-sm">
                                                Reportes
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }else {
                                        ?>
                                    <p class="text-danger"><?php echo $model2[0]["results"];?></p>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>