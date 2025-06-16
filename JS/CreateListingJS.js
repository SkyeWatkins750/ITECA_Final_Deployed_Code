function ValidateInputs() {
    isValid = true;

    const itemName = document.getElementById("itemName");
    const price = document.getElementById("price");
    const category = document.getElementById("category");
    const condition = document.getElementById("condition");
    const image = document.getElementById("image");
    const description = document.getElementById("description");

    const itemNameError = document.getElementById("itemNameError");
    const priceError = document.getElementById("priceError");
    const categoryError = document.getElementById("categoryError");
    const conditionError = document.getElementById("conditionError");
    const imageError = document.getElementById("imageError");
    const descriptionError = document.getElementById("descriptionError");

    itemNameError.textContent = "";
    priceError.textContent = "";
    categoryError.textContent = "";
    conditionError.textContent = "";
    imageError.textContent = "";
    descriptionError.textContent = "";

    if (itemName.value.trim() === "") {
        itemNameError.textContent = "Item name is required.";
        isValid = false;
    }
    if (price.value.trim() === "") {
        priceError.textContent = "Price is required.";
        isValid = false;
    }
    if (parseFloat(price.value.trim()) < 0) {
        priceError.textContent = "Price cannot be negative";
        isValid = false;
    }
    if (category.value === "") {
        categoryError.textContent = "Select a category.";
        isValid = false;
    }
    if (condition.value === "") {
        conditionError.textContent = "Select a condition.";
        isValid = false;
    }
    if (image.files.length === 0) {
        imageError.textContent = "Upload an image.";
        isValid = false;
    }
    if (description.value.trim() === "") {
        descriptionError.textContent = "Provide an item description.";
        isValid = false;
    }
    return isValid;
}