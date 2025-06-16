function ValidateInputsUser() {
    isValid = true;

    const fullName = document.getElementById("fullName");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const province = document.getElementById("province");
    const city = document.getElementById("city");
    const streetAddress = document.getElementById("streetAddress");
    const postalCode = document.getElementById("postalCode");

    const fullNameError = document.getElementById("fullNameError");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");
    const provinceError = document.getElementById("provinceError");
    const cityError = document.getElementById("cityError");
    const streetAddressError = document.getElementById("streetAddressError");
    const postalCodeError = document.getElementById("postalCodeError");

    fullNameError.textContent = "";
    emailError.textContent = "";
    passwordError.textContent = "";
    provinceError.textContent = "";
    cityError.textContent = "";
    streetAddressError.textContent = "";
    postalCodeError.textContent = "";

    if (fullName.value.trim() === "") {
        fullNameError.textContent = "Name is required."
        isValid = false;
    }

    if (email.value.trim() === "") {
        emailError.textContent = "Email is required."
        isValid = false;
    }

    if (province.value === "") {
        provinceError.textContent = "Province is required."
        isValid = false;
    }

    if (city.value.trim() === "") {
        cityError.textContent = "City is required."
        isValid = false;
    }

    if (streetAddress.value.trim() === "") {
        streetAddressError.textContent = "Address is required."
        isValid = false;
    }

    if (postalCode.value.trim() === "") {
        postalCodeError.textContent = "Postal code is required.";
        isValid = false;
    } else if (postalCode.value.trim().length < 4) {
        postalCodeError.textContent = "Postal code is not complete."
        isValid = false;
    }else if (postalCode.value.trim().length > 4) {
        postalCodeError.textContent = "Postal code is too long."
        isValid = false;
    }

    return isValid;
}