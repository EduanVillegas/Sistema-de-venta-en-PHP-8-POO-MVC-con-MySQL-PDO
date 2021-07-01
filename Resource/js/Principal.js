class Principal {
    linkPrincipal(link) {
        let url = "";
        let cadena = link.split("/");
        for (let i = 0; i < cadena.length; i++) {
            if (i >= 3 && i <= 4) {
                url += cadena[i];
            }
        }
        switch (url) {
            case "UserRegister":
                document.getElementById('files').addEventListener('change', imageUser, false);
                break;
            case "ClientRegister":
                document.getElementById('files').addEventListener('change', imageClient, false);
                break;
            case "ClientReports":
                new Client().SetSection(1);
                break;
            case "ProviderRegister":
                document.getElementById('files').addEventListener('change', imageProvider, false);
                break;
            case "ProviderReports":
                new Provider().SetSection(1);
                $('#ProviderFees').keyup((e) => {
                    provider.Payments(e, null);
                });
                $("#ProviderFees").change((e) => {
                    provider.Payments(e, null);
                });
                $('#PaymentProvider').keyup((e) => {
                    provider.Payments(e, null);
                });
                break;
                case "ShoppingAddShoppings":
                    document.getElementById('files').addEventListener('change', imageShopping, false);
                    $('#Price').keyup((e) => {
                        shopping.purchaseAmount();
                    });
                    $("#Quantity").change((e) => {
                        shopping.purchaseAmount();
                    });
                    shopping.Restore();
                break;
        }
    }
}