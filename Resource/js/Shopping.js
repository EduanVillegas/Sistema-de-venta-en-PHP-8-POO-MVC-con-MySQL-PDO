class Shopping extends Uploadpicture {
    constructor(){
        super();
        this.URL = window.location.origin + "/PDHN/SalesSystem/";
    }
    purchaseAmount(){
        var Quantity = document.getElementById("Quantity").value;
        var Price = document.getElementById("Price").value;
        let Amount = Price * Quantity;
        document.getElementById("labelCompra_Importe").innerHTML = numberDecimales(Amount);
    }
    GetProvider(){
        var provider=document.getElementById("Provider").value;
        $.post(
            this.URL+"Shopping/GetProvider",
            {provider},
            (response) =>{
                try {
                    var item = JSON.parse(response);
                    $("#listProvider").html(item.dataFilter);
                    console.log(item);
                } catch (error) {
                    
                }
            }
        );
    }
    SetProvider() {
        let providers = document.getElementById("listProvider");
        let provider = providers.options[providers.selectedIndex].text;
        document.getElementById("Provider").value = provider;
    }
    Payments(event, input,amount){
        if (input != null) {
            if (filterFloat(event, input)) {
                this.Payment(event, input,amount);
            }else{
                return false;
            }
        }else{
            this.Payment(event, input,amount);
        }
    }
    Payment(event, input,amount){
        var payments;
        var key = window.Event ? event.which : event.keyCode;
        var chark = String.fromCharCode(key);
        if (input == null){
            payments = document.getElementById("Payments").value;
        }else{
            payments = input.value + chark;
        }
        var amount = amount == null ? 0.0 : parseFloat(amount);
        if (payments != "000") {
            let debt = amount - payments;
            if (amount < payments){
                $('#payment').attr("disabled", false);
                $('#check').attr("disabled", true);
                document.getElementById("labelCompra_Debt").innerHTML = "Cambio para el sistema";
            }else  if (amount == payments) {
                $('#check').attr("disabled", true);
                $('#payment').attr("disabled", false);
            }
            let debts = numberDecimales(debt);
            document.getElementById("labelCompra_Debts").innerHTML = debts.replace('-', '');
        }else{
            if (document.getElementById("check").checked) {
                $('#Payments').attr("disabled", true);
                $('#payment').attr("disabled", false);                
            }
        }
    }
    Check(){
        var payments = document.getElementById("Payments").value;
        if (document.getElementById("check").checked) {
            $('#payment').attr("disabled", false);
            if (payments == "000") {
                $('#Payments').attr("disabled", true);
            }
        }else{
            $('#payment').attr("disabled", true);
            $('#Payments').attr("disabled", false);
        }
    }
    Restore(){
        this.purchaseAmount();
        this.GetProvider();
    }
}