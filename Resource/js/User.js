class User extends Uploadpicture {

    ClearMessages(input) {
        switch (input.name) {
            case "nid":
                document.getElementById(input.name).innerHTML = "";
                break;
            case "name":
                document.getElementById(input.name).innerHTML = "";
                break;
            case "lastname":
                document.getElementById(input.name).innerHTML = "";
                break;
            case "phone":
                document.getElementById(input.name).innerHTML = "";
                break;
            case "direction":
                document.getElementById(input.name).innerHTML = "";
                break;
            case "user":
                document.getElementById(input.name).innerHTML = "";
                break;
            case "email":
                document.getElementById(input.name).innerHTML = "";
                break;
            case "password":
                document.getElementById(input.name).innerHTML = "";
                break;
            case "role":
                document.getElementById(input.name).innerHTML = "";
                break;
        }
    }
}