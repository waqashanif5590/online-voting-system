const identityType = document.getElementById('identityType');
const identityNumber = document.getElementById('identityNumber');
const identityHelp = document.getElementById('identityHelp');

function updateIdentityFields() {
    if (identityType.value === 'cnic') {
        identityNumber.maxLength = 13;
        identityNumber.placeholder = 'Enter 13-digit CNIC';
        identityHelp.textContent = 'Enter your 13-digit CNIC number, without hyphens or dashes.'
    } else if (identityType.value === 'passport') {
        identityNumber.maxLength = 9;
        identityNumber.placeholder = 'Enter 9-digit CNIC';
        identityHelp.textContent = 'Enter your 9-digit Passport number, without hyphens or dashes.'
    }
}
identityType.addEventListener('change', updateIdentityFields);
updateIdentityFields();
