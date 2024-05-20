// File: set_reservation_selector.js

// Function to set the selected option of the select element
function setSelectedPackage(packageId) {
    const selectElement = document.getElementById('app_services');
    selectElement.value = packageId;
}

// Add event listener to the "Select Corporate Package" button
document.getElementById('select-corporate-package').addEventListener('click', function() {
    setSelectedPackage('corporate-package-selector');
});

// Add event listener to the "Select Basic Package" button
document.getElementById('select-basic-package').addEventListener('click', function() {
    setSelectedPackage('basic-option-selector');
});

// Add event listener to the "Select Premium Package" button
document.getElementById('select-premium-package').addEventListener('click', function() {
    setSelectedPackage('premium-package-selector');
});
