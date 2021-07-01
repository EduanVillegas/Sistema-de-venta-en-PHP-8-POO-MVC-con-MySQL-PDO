<div class="container">
    <h2>Shopping</h2>
    <div class="row">
        <div class="col-sm ">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#nav-shopping" role="tab"
                        aria-selected="true">Shopping</a>

                    <a class="nav-item nav-link" data-toggle="tab" href="#nav-payments" role="tab"
                        aria-selected="false">Payments</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-shopping" role="tabpanel">
                    <form method="post" action="<?php echo URL.'Shopping/AddShopping' ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="card text-center">
                                    <div class="card-header ">
                                        <output id="imageShopping">
                                            <img src="<?php 
                                             if ($model1 != null) {
                                                if ($model1->Image != null){
                                                    echo 'data:image/jpeg;base64,' . $model1->Image;
                                                }else{
                                                    echo URL . RQ . 'images/icons/add_shopping.png';
                                                }
                                             }else{
                                                echo URL . RQ . 'images/icons/add_shopping.png';
                                             }
                                            ?>" class="responsive-img" />

                                        </output>
                                    </div>
                                    <div class="card-body">
                                        <div class="caption text-center">
                                            <label class="btn btn-primary" for="files">Imagen</label>
                                            <input type="file" accept="image/*" id="files" name="file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-7 col-md-5">
                                <div class="panel  panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Registrar compra</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input type="text" name="Description" placeholder="Descripción"
                                                        class="form-control" autofocus autocomplete="off"
                                                        value="<?php echo $model1->Description ?? "" ?>" />
                                                    <span id="Descripción"
                                                        class="text-danger"><?php echo $model2->Description ?? "" ?></span>
                                                </div>
                                                <div class="form-group">
                                                    <input type="number" name="Quantity" placeholder="Cantidad"
                                                        class="form-control" min="1" autocomplete="off"
                                                        onkeyup="shopping.purchaseAmount()" id="Quantity"
                                                        value="<?php echo $model1->Quantity ?? "" ?>" />
                                                    <span id="Quantitys"
                                                        class="text-danger"><?php echo $model2->Quantity ?? "" ?></span>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="Price" placeholder="Precio de compra"
                                                        class="form-control" autocomplete="off"
                                                        onkeypress="return filterFloat(event,this)" id="Price"
                                                        value="<?php echo $model1->Price ?? "" ?>" />
                                                    <span id="Prices"
                                                        class="text-danger"><?php echo $model2->Price ?? "" ?></span>
                                                </div>
                                                <div class="form-group">
                                                    <span
                                                        class="text-danger labelCompra_Importe"><?php echo $model3[0] ?>
                                                        <label class="text-success labelCompra_Importe"
                                                            id="labelCompra_Importe">0.00 </label>
                                                    </span>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="Provider" placeholder="Proveedor"
                                                        class="form-control" autocomplete="off"
                                                        onkeyup="shopping.GetProvider()" id="Provider"
                                                        value="<?php echo $model1->Provider ?? "" ?>" />
                                                    <span id="Proveedor"
                                                        class="text-danger"><?php echo $model2->Provider ?? "" ?></span>
                                                </div>
                                                <div class="form-group">
                                                    <select id="listProvider" class='form-control'
                                                        onclick="shopping.SetProvider()" name="listProvider">

                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-primary btn-block">Register</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <a href="<?php echo URL?>Shopping/Cancel"
                                                                class="btn btn-warning text-white">Cancel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="IdTemporary"
                                                        value="<?php echo $model1->IdTemporary ?? 0 ?>" />
                                                    <label
                                                        class="text-danger"><?php echo $model2->Ticket ?? "" ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="nav-payments" role="tabpanel">
                    <form method="post" action="<?php echo URL.'Shopping/Payments' ?>">
                        <div class="col-xl-7 col-md-5">
                            <div class="card">
                                <div class="card-header ">
                                    <h4>Realizar compra</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>
                                            Importe total
                                        </label>
                                        <br />
                                        <span class="text-danger labelCompra_Importe">
                                            <?php echo $model3[0] ?>
                                            <label
                                                class="text-success "><?php echo DecimalFormat::number_format($model3[2]);?></label>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="Payments" placeholder="Pago" id="Payments"
                                            class="form-control" autocomplete="off"
                                            onkeypress="return shopping.Payments(event,this,'<?php echo $model3[2] ?>')"
                                            required />

                                    </div>
                                    <div class="form-group">
                                        <label id="labelCompra_Debt">
                                            Deuda total
                                        </label>
                                        <br />
                                        <span class="text-danger labelCompra_Importe">
                                            <?php echo $model3[0] ?>
                                            <label class="text-success labelCompra_Importe" id="labelCompra_Debts">0.00
                                            </label>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="check" name="check"
                                                onclick="shopping.Check()">
                                            <label class="form-check-label" for="check">Credito</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-block"
                                                    id="payment">Aceptar</button>
                                            </div>
                                            &nbsp;&nbsp;
                                            <div class="form-group">
                                                <a href="<?php echo URL?>Shopping/Cancel"
                                                    class="btn btn-warning text-white">Cancel</a>
                                            </div>
                                            <div class="form-group">
                                                <input name="value" type="hidden" value="1" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-7">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?php echo URL.'Shopping/AddShoppings' ?>">
                        <div class="row">
                            <div class="form-group">
                                <input type="text" name="search" placeholder="Busacar" class="form-control" autofocus
                                    autocomplete="off" />
                            </div>
                            &nbsp;&nbsp;
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-info btn-sm">Buscar</button>
                            </div>
                        </div>
                    </form>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Quantity
                                </th>
                                <th>
                                    Price
                                </th>
                                <th>
                                    Amount
                                </th>
                                <th>
                                    Edit
                                </th>
                                <th>
                                    Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (is_array($model3[1]["results"])){
                                foreach ($model3[1]["results"] as $key => $value) { 
                            
                            ?>
                            <tr>
                                <td>
                                    <?php echo $value["Description"];?>
                                </td>
                                <td>
                                    <?php echo $value["Quantity"];?>
                                </td>
                                <td>
                                    <?php echo $model3[0].DecimalFormat::number_format($value["Price"]);?>
                                </td>
                                <td>
                                    <?php echo $model3[0].DecimalFormat::number_format($value["Amount"]);?>
                                </td>
                                <td>
                                    <a href="<?php echo URL.'Shopping/AddShoppings/'.$model3[3].'?IdTemporary='.$value["IdTemporary"]?>"
                                        class="btn btn-outline-info btn-sm">
                                        Editar
                                    </a>
                                </td>
                                <td>
                                    <form method="post" action="<?php echo URL.'Shopping/DeleteShopping'?>">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm">Eliminar</button>
                                        </div>
                                        <div class="form-group">
                                            <input name="delete" type="hidden"
                                                value="<?php echo $value["IdTemporary"] ?>" />
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <?php
                                }
                            }else {
                            ?>
                            <p class="text-danger"><?php echo $model3[1]["results"];?></p>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label>
                            Importe total:&nbsp;
                            <span class="text-danger">
                                <label
                                    class="text-success "><?php echo $model3[0].DecimalFormat::number_format($model3[2]);?></label>

                            </span>
                        </label>
                        <div class="text-center">
                            <?php
                        echo $model3[1]["pagi_info"]; 
                        echo "<br>";
                        echo $model3[1]["pagi_navegacion"];     
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>