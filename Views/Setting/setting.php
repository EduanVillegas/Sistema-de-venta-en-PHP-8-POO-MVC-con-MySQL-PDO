<div class="container">
    <h3>Setting</h3>
    <div class="row">
        <div class="col-sm ">
            <div class="card" style="width: 21rem;">
                <div class="card-header ">
                    <h5>Setting</h5>
                </div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" data-toggle="tab" href="#nav-money" role="tab"
                                aria-selected="true">Type of money</a>

                            <a class="nav-item nav-link" data-toggle="tab" href="#nav-interests" role="tab"
                                aria-selected="false">Interests</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-money" role="tabpanel">
                            <form action="TypeMoney" method="post">
                                <div class="form-check ">
                                    <input type="radio" class="form-check-input" id="inlineRadio1" name="RadioOptions"
                                        value="1">
                                    <label class="form-check-label" for="inlineRadio1">L lempira</label>
                                </div>
                                <div class="form-check ">
                                    <input type="radio" class="form-check-input" id="inlineRadio2" name="RadioOptions"
                                        value="2">
                                    <label class="form-check-label" for="inlineRadio2">$ dolar</label>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Save" class="btn btn-outline-info btn-sm">
                                    &nbsp;
                                    <label class="text-success">Money:<span class="text-danger">
                                            <?php echo $model2['TypeMoney'];?></span> </label>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="nav-interests" role="tabpanel">
                            <form action="SetInterests" method="post">
                                <div class="row">
                                    <input name="Interests" placeholder="Interests" class="form-control" autofocus
                                        style="width: 9rem;" autocomplete="off"
                                        onkeypress="return filterFloat(event,this);" />
                                    &nbsp;
                                    <input type="submit" value="Save" class="btn btn-outline-info btn-sm">
                                </div>
                                <div class="form-group">
                                    <label class="text-success">Intereses:<span class="text-danger">
                                            <?php echo $model2['Interests'];?></span>% </label>
                                </div>
                            </form>
                        </div>
                        <div class="form-group">
                            <span class="text-danger"><?php echo $model1->TypeMoney ?? "" ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>