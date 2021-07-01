<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistema de ventas</title>

    <link rel="stylesheet" href="<?php echo URL.RQ ?>css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo URL.RQ ?>css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="<?php echo URL.RQ ?>css/style.css" />
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light bg-white border-bottom box-shadow mb-3">
            <div class="container">
                <a class="navbar-brand">
                    <img src="<?php echo URL.RQ ?>images/icons/logo-google.png" class="mx-auto w-25 imglogo">SalesSystem
                </a>
                <?php
                 $user = Session::getSession("User"); 
                 if(null != $user){
                 ?>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse d-sm-inline-flex flex-sm-row-reverse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-dark" title="Manage">Hello
                                <?php echo $user["Name"]." ".$user["LastName"]?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo URL?>Index/Logout" class="nav-link text-dark" title="Manage">Logout</a>
                        </li>

                    </ul>
                    <ul class="navbar-nav flex-grow-1">
                        <li class="nav-item">
                            <a href="<?php echo URL?>Main/Main" class="nav-link text-dark">Home</a>
                        </li>
                        <li class="nav-item  dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" href="#">Customers</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo URL?>Client/Client">Clients List</a>
                                <a class="dropdown-item" href="<?php echo URL?>Client/Register">Add client</a>
                            </div>
                        </li>
                        <li class="nav-item  dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" href="#">Provider</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo URL?>Provider/Provider">Provider List</a>
                                <a class="dropdown-item" href="<?php echo URL?>Provider/Register">Add provider</a>
                            </div>
                        </li>
                        <li class="nav-item  dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" href="#">Shopping</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo URL?>Shopping/AddShoppings">Add Shopping</a>
                                <a class="dropdown-item" href="<?php echo URL?>Shopping/Shoppings">Shopping List</a>
                            </div>
                        </li>
                        <li class="nav-item  dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" href="#">Users</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo URL?>User/User">User List</a>
                                <a class="dropdown-item" href="<?php echo URL?>User/Register">Add user</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="<?php echo URL?>Setting/Setting">Setting</a>
                        </li>
                    </ul>
                </div>
                <?php }?>
            </div>
        </nav>
    </header>