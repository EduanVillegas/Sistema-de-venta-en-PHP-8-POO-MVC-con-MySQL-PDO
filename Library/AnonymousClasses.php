<?php
declare (strict_types = 1);
class AnonymousClasses
{
    public function TUser(array $array){
        return new class($array){
            public $IdUser = 0;
            public $NID;
            public $Name;
            public $LastName;
            public $Email;
            public $Password;
            public $Phone;
            public $Direction;
            public $User;
            public $Role;
            public $Image;
            public $Is_active;
            public $State;
            public $Date;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdUser"])) {$this->IdUser = $array["IdUser"];}
                    if (!empty($array["NID"])) {$this->NID = $array["NID"];}
                    if (!empty($array["Name"])){$this->Name = $array["Name"];}
                    if (!empty($array["LastName"])){$this->LastName = $array["LastName"];}
                    if (!empty($array["Email"])){$this->Email = $array["Email"];}
                    if (!empty($array["Password"])){$this->Password = $array["Password"];}
                    if (!empty($array["Phone"])){$this->Phone = $array["Phone"];}
                    if (!empty($array["Direction"])){$this->Direction = $array["Direction"];}
                    if (!empty($array["User"])){$this->User = $array["User"];}
                    if (!empty($array["Role"])){$this->Role = $array["Role"];}
                    if (!empty($array["Image"])){$this->Image = $array["Image"];}
                    if (!empty($array["Is_active"])){$this->Is_active = $array["Is_active"];}
                    if (!empty($array["State"])){$this->State = $array["State"];}
                    if (!empty($array["Date"])){$this->Image = $array["Date"];}
                }
            }
        };
    }
    public function TClient(array $array){
        return new class($array){
            public $IdClient = 0;
            public $NID;
            public $Name;
            public $LastName;
            public $Email;
            public $Phone;
            public $Direction;
            public $Image;
            public $Credit;
            public $Date;
            public $State;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdClient"])) {$this->IdClient = $array["IdClient"];}
                    if (!empty($array["NID"])) {$this->NID = $array["NID"];}
                    if (!empty($array["Name"])){$this->Name = $array["Name"];}
                    if (!empty($array["LastName"])){$this->LastName = $array["LastName"];}
                    if (!empty($array["Email"])){$this->Email = $array["Email"];}
                    if (!empty($array["Phone"])){$this->Phone = $array["Phone"];}
                    if (!empty($array["Direction"])){$this->Direction = $array["Direction"];}
                    if (!empty($array["Image"])){$this->Image = $array["Image"];}
                    if (!empty($array["Credit"])){$this->Credit = $array["Credit"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                    if (!empty($array["State"])){$this->State = $array["State"];}
                }
            }
        };
    }
    public function TReports_clients(array $array){
        return new class($array){
            public $IdReport = 0;
            public $Debt;
            public $Monthly;
            public $Changes;
            public $LastPayment;
            public $DatePayment;
            public $CurrentDebt;
            public $DateDebt;
            public $Ticket;
            public $Deadline;
            public $IdClients;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdReport"])) {$this->IdReport = $array["IdReport"];}
                    if (!empty($array["Debt"])) {$this->Debt = $array["Debt"];}
                    if (!empty($array["Monthly"])){$this->Monthly = $array["Monthly"];}
                    if (!empty($array["Change"])){$this->Changes = $array["Change"];}
                    if (!empty($array["LastPayment"])){$this->LastPayment = $array["LastPayment"];}
                    if (!empty($array["DatePayment"])){$this->DatePayment = $array["DatePayment"];}
                    if (!empty($array["CurrentDebt"])){$this->CurrentDebt = $array["CurrentDebt"];}
                    if (!empty($array["DateDebt"])){$this->DateDebt = $array["DateDebt"];}
                    if (!empty($array["Ticket"])){$this->Ticket = $array["Ticket"];}
                    if (!empty($array["Deadline"])){$this->Deadline = $array["Deadline"];}
                    if (!empty($array["IdClient"])){$this->IdClients = $array["IdClient"];}
                }
            }
        };
    }
    public function TPayments_clients(array $array){
        return new class($array){
            public $IdPayments = 0;
            public $Debt;
            public $Monthly;
            public $Changes;
            public $Payment;
            public $Date;
            public $PreviousDebt;
            public $CurrentDebt;
            public $DateDebt;
            public $Ticket;
            public $Deadline;
            public $IdUser;
            public $User;
            public $IdClients;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdPayments"])) {$this->IdPayments = $array["IdPayments"];}
                    if (!empty($array["Debt"])) {$this->Debt = $array["Debt"];}
                    if (!empty($array["Monthly"])){$this->Monthly = $array["Monthly"];}
                    if (!empty($array["Change"])){$this->Changes = $array["Change"];}
                    if (!empty($array["Payment"])){$this->Payment = $array["Payment"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                    if (!empty($array["PreviousDebt"])){$this->PreviousDebt = $array["PreviousDebt"];}
                    if (!empty($array["CurrentDebt"])){$this->CurrentDebt = $array["CurrentDebt"];}
                    if (!empty($array["DateDebt"])){$this->DateDebt = $array["DateDebt"];}
                    if (!empty($array["Ticket"])){$this->Ticket = $array["Ticket"];}
                    if (!empty($array["Deadline"])){$this->Deadline = $array["Deadline"];}
                    if (!empty($array["IdUser"])){$this->IdUser = $array["IdUser"];}
                    if (!empty($array["User"])){$this->User = $array["User"];}
                    if (!empty($array["IdClients"])){$this->IdClients = $array["IdClients"];}
                }
            }
        };
    }
    public function TCustomer_interests(array $array){
        return new class($array){
            public $IdInterests = 0;
            public $Debt;
            public $Monthly;
            public $Date;
            public $Interests;
            public $Canceled;
            public $Deadline;
            public $IdCustomer;
            public $InitialDate;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdInterests"])) {$this->IdInterests = $array["IdInterests"];}
                    if (!empty($array["Debt"])) {$this->Debt = $array["Debt"];}
                    if (!empty($array["Monthly"])){$this->Monthly = $array["Monthly"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                    if (!empty($array["Interests"])){$this->Interests = $array["Interests"];}
                    if (!empty($array["Canceled"])){$this->Canceled = $array["Canceled"];}
                    if (!empty($array["Deadline"])){$this->Deadline = $array["Deadline"];}
                    if (!empty($array["IdCustomer"])){$this->IdCustomer = $array["IdCustomer"];}
                    if (!empty($array["InitialDate"])){$this->InitialDate = $array["InitialDate"];}
                }
            }
        };
    }
    public function TCustomer_interests_reports(array $array){
        return new class($array){
            public $IdinterestReports = 0;
            public $Interests;
            public $Payment;
            public $Changes;
            public $Fee;
            public $TicketInterest;
            public $InterestDate;
            public $IdClient;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdinterestReports"])) {$this->IdinterestReports = $array["IdinterestReports"];}
                    if (!empty($array["Interests"])) {$this->Interests = $array["Interests"];}
                    if (!empty($array["Payment"])){$this->Payment = $array["Payment"];}
                    if (!empty($array["Changes"])){$this->Changes = $array["Changes"];}
                    if (!empty($array["Fee"])){$this->Fee = $array["Fee"];}
                    if (!empty($array["TicketInterest"])){$this->TicketInterest = $array["TicketInterest"];}
                    if (!empty($array["InterestDate"])){$this->InterestDate = $array["InterestDate"];}
                    if (!empty($array["IdClient"])){$this->IdClient = $array["IdClient"];}
                }
            }
        };
    }
    public function TPayments_Customer_Interest(array $array){
        return new class($array){
            public $IdPaymentsInterest = 0;
            public $Interests;
            public $Payment;
            public $Changes;
            public $Fee;
            public $Date;
            public $Ticket;
            public $IdUser;
            public $User;
            public $IdCustomer;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdPaymentsInterest"])) {$this->IdPaymentsInterest = $array["IdPaymentsInterest"];}
                    if (!empty($array["Interests"])) {$this->Interests = $array["Interests"];}
                    if (!empty($array["Payment"])){$this->Payment = $array["Payment"];}
                    if (!empty($array["Changes"])){$this->Changes = $array["Changes"];}
                    if (!empty($array["Fee"])){$this->Fee = $array["Fee"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                    if (!empty($array["Ticket"])){$this->Ticket = $array["Ticket"];}
                    if (!empty($array["IdUser"])){$this->IdUser = $array["IdUser"];}
                    if (!empty($array["User"])){$this->User = $array["User"];}
                    if (!empty($array["IdCustomer"])){$this->IdCustomer = $array["IdCustomer"];}
                }
            }
        };
    }
    public function TSetting(array $array){
        return new class($array){
            public $ID = 0;
            public $TypeMoney;
            public $Interests;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["ID"])) {$this->ID = $array["ID"];}
                    if (!empty($array["TypeMoney"])) {$this->TypeMoney = $array["TypeMoney"];}
                    if (!empty($array["Interests"])){$this->Interests = $array["Interests"];}
                
                }
            }
        };
    }
    public function TProvider(array $array){
        return new class($array){
            public $IdProvider = 0;
            public $Provider;
            public $Email;
            public $Phone;
            public $Direction;
            public $Image;
            public $Date;
            public $State;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdProvider"])) {$this->IdProvider = $array["IdProvider"];}
                    if (!empty($array["Provider"])){$this->Provider = $array["Provider"];}
                    if (!empty($array["Email"])){$this->Email = $array["Email"];}
                    if (!empty($array["Phone"])){$this->Phone = $array["Phone"];}
                    if (!empty($array["Direction"])){$this->Direction = $array["Direction"];}
                    if (!empty($array["Image"])){$this->Image = $array["Image"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                    if (!empty($array["State"])){$this->State = $array["State"];}
                }
            }
        };
    }
    public function TReports_provider(array $array){
        return new class($array){
            public $IdReport = 0;
            public $Debt;
            public $Monthly;
            public $Changes;
            public $LastPayment;
            public $DatePayment;
            public $CurrentDebt;
            public $DateDebt;
            public $Ticket;
            public $IdProviders;
            public $Agreement;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdReport"])) {$this->IdReport = $array["IdReport"];}
                    if (!empty($array["Debt"])) {$this->Debt = $array["Debt"];}
                    if (!empty($array["Monthly"])){$this->Monthly = $array["Monthly"];}
                    if (!empty($array["Change"])){$this->Changes = $array["Change"];}
                    if (!empty($array["LastPayment"])){$this->LastPayment = $array["LastPayment"];}
                    if (!empty($array["DatePayment"])){$this->DatePayment = $array["DatePayment"];}
                    if (!empty($array["CurrentDebt"])){$this->CurrentDebt = $array["CurrentDebt"];}
                    if (!empty($array["DateDebt"])){$this->DateDebt = $array["DateDebt"];}
                    if (!empty($array["Ticket"])){$this->Ticket = $array["Ticket"];}
                    if (!empty($array["IdProviders"])){$this->IdProviders = $array["IdProviders"];}
                    if (!empty($array["Agreement"])){$this->IdProviders = $array["Agreement"];}
                }
            }
        };
    }
    public function TPayments_providers(array $array){
        return new class($array){
            public $IdPayments = 0;
            public $Debt;
            public $Monthly;
            public $Changes;
            public $Payment;
            public $Date;
            public $PreviousDebt;
            public $CurrentDebt;
            public $DateDebt;
            public $Ticket;
            public $IdUser;
            public $Agreement;
            public $IdProviders;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdPayments"])) {$this->IdPayments = $array["IdPayments"];}
                    if (!empty($array["Debt"])) {$this->Debt = $array["Debt"];}
                    if (!empty($array["Monthly"])){$this->Monthly = $array["Monthly"];}
                    if (!empty($array["Change"])){$this->Changes = $array["Change"];}
                    if (!empty($array["Payment"])){$this->Payment = $array["Payment"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                    if (!empty($array["PreviousDebt"])){$this->PreviousDebt = $array["PreviousDebt"];}
                    if (!empty($array["CurrentDebt"])){$this->CurrentDebt = $array["CurrentDebt"];}
                    if (!empty($array["DateDebt"])){$this->DateDebt = $array["DateDebt"];}
                    if (!empty($array["Ticket"])){$this->Ticket = $array["Ticket"];}
                    if (!empty($array["IdUser"])){$this->IdUser = $array["IdUser"];}
                    if (!empty($array["Agreement"])){$this->Agreement = $array["Agreement"];}
                    if (!empty($array["IdProviders"])){$this->IdProviders = $array["IdProviders"];}
                }
            }
        };
    }
    public function TShopping(array $array){
        return new class($array){
            public $IdShopping = 0;
            public $Description;
            public $Quantity;
            public $Price;
            public $Amount;
           //public $Provider;
            public $IdProvider;
            public $IdUser;
            public $Image;
            public $Date;
            public $Ticket;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdShopping"])) {$this->IdShopping = $array["IdShopping"];}
                    if (!empty($array["Description"])){$this->Description = $array["Description"];}
                    if (!empty($array["Quantity"])){$this->Quantity = $array["Quantity"];}
                    if (!empty($array["Price"])){$this->Price = $array["Price"];}
                    if (!empty($array["Amount"])){$this->Amount = $array["Amount"];}
                   // if (!empty($array["Provider"])){$this->Provider = $array["Provider"];}
                    if (!empty($array["IdProvider"])){$this->IdProvider = $array["IdProvider"];}
                    if (!empty($array["IdUser"])){$this->IdUser = $array["IdUser"];}
                    if (!empty($array["Image"])){$this->Image = $array["Image"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                    if (!empty($array["Ticket"])){$this->Ticket = $array["Ticket"];}
                }
            }
        };
    }
    public function Temporary_shopping(array $array){
        return new class($array){
            public $IdTemporary = 0;
            public $Description;
            public $Quantity;
            public $Price;
            public $Amount;
            public $IdProvider;
            public $Provider;
            public $IdUser;
            public $Image;
            public $Date;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdTemporary"])) {$this->IdTemporary = $array["IdTemporary"];}
                    if (!empty($array["Description"])){$this->Description = $array["Description"];}
                    if (!empty($array["Quantity"])){$this->Quantity = $array["Quantity"];}
                    if (!empty($array["Price"])){$this->Price = $array["Price"];}
                    if (!empty($array["Amount"])){$this->Amount = $array["Amount"];}
                    if (!empty($array["Provider"])){$this->Provider = $array["Provider"];}
                    if (!empty($array["IdProvider"])){$this->IdProvider = $array["IdProvider"];}
                    if (!empty($array["IdUser"])){$this->IdUser = $array["IdUser"];}
                    if (!empty($array["Image"])){$this->Image = $array["Image"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                }
            }
        };
    }
    public function TReports_shopping(array $array){
        return new class($array){
            public $IdReport = 0;
            public $Ticket;
            public $Products;
            public $Credit;
            public $Payment;
            public $Debt;
            public $IdProvider;
            public $Changes;
            public $Date;
            function __construct($array){
                if(0 < count($array)){
                    if (!empty($array["IdReport"])) {$this->IdReport = $array["IdReport"];}
                    if (!empty($array["Ticket"])){$this->Ticket = $array["Ticket"];}
                    if (!empty($array["Products"])){$this->Products = $array["Products"];}
                    if (!empty($array["Credit"])){$this->Credit = $array["Credit"];}
                    if (!empty($array["Payment"])){$this->Payment = $array["Payment"];}
                    if (!empty($array["Debt"])){$this->Debt = $array["Debt"];}
                    if (!empty($array["IdProvider"])){$this->IdProvider = $array["IdProvider"];}
                    if (!empty($array["Changes"])){$this->Changes = $array["Changes"];}
                    if (!empty($array["Date"])){$this->Date = $array["Date"];}
                }
            }
        };
    }
}

?>