function ValidateInputs() {
    isValid = true;

    const cardName = document.getElementById('cardName');
    const cardNumber = document.getElementById('cardNumber');
    const expiryDate = document.getElementById('expiryDate');
    const cvv = document.getElementById('cvv');
    const country = document.getElementById('country');
    const province = document.getElementById('province');
    const city = document.getElementById('city');
    const address = document.getElementById('address');
    const postalCode = document.getElementById('postalCode');

    const nameError = document.getElementById('CardNameError');
    const numberError = document.getElementById('CardNumberError');
    const dateError = document.getElementById('ExpiryDateError');
    const cvvError = document.getElementById('CVVError');
    const countryError = document.getElementById('CountryError');
    const provinceError = document.getElementById('ProvinceError');
    const cityError = document.getElementById('CityError');
    const addressError = document.getElementById('AddressError');
    const postalCodeError = document.getElementById('PostalCodeError');

    nameError.textContent = "";
    numberError.textContent = "";
    dateError.textContent = "";
    cvvError.textContent = "";
    countryError.textContent = "";
    provinceError.textContent = "";
    cityError.textContent = "";
    addressError.textContent = "";
    postalCodeError.textContent = "";

    if (cardName.value.trim() === "") {
        nameError.textContent = "Card holder name is required.";
        isValid = false;
    }
    if (cardNumber.value.trim() === "") {
        numberError.textContent = "Card number is required.";
        isValid = false;
    } else if (cardNumber.value.trim().length < 16) {
        numberError.textContent = "Card number is not complete.";
        isValid = false;
    } else if (cardNumber.value.trim().length > 16) {
        numberError.textContent = "Card number is too long.";
        isValid = false;
    }

    const expiryValue = expiryDate.value.trim();
    const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;

    if (expiryValue === "") {
        dateError.textContent = "Expiry date is required.";
        isValid = false;
    } else if (!expiryPattern.test(expiryValue)) {
        dateError.textContent = "Expiry date must be in this format MM/YY."
        isValid = false;
    } else {
        const [monthStr, yearStr] = expiryValue.split('/');
        const inputMonth = parseInt(monthStr, 10);
        const inputYear = parseInt("20" + yearStr, 10);

        const today = new Date();
        const currentMonth = today.getMonth() + 1;
        const currentYear = today.getFullYear();

        if (inputYear < currentYear || (inputYear === currentYear && inputMonth < currentMonth)) {
            dateError.textContent = "Card has expired."
            isValid = false;
        }
    }
    if (cvv.value.trim() === "") {
        cvvError.textContent = "CVV is required.";
        isValid = false;
    } else if (cvv.value.trim().length < 3) {
        cvvError.textContent = "CVV is not complete.";
        isValid = false;
    } else if (cvv.value.trim().length > 3) {
        cvvError.textContent = "CVV is too long.";
        isValid = false;
    }

    if (country.value.trim() === "") {
        countryError.textContent = "Country is required.";
        isValid = false;
    }

    if (province.value.trim() === "") {
        provinceError.textContent = "Select a Province.";
        isValid = false;
    }

    if (city.value.trim() === "") {
        cityError.textContent = "City is required.";
        isValid = false;
    }

    if (address.value.trim() === "") {
        addressError.textContent = "Address is required.";
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

