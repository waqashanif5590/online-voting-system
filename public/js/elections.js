function setupDropdown(buttonId, dropdownId, selectedTextId, arrowId, checkboxName, defaultText, itemName, itemNamePlural) {
    const dropdownButton = document.getElementById(buttonId);
    const dropdown = document.getElementById(dropdownId);
    const selectedText = document.getElementById(selectedTextId);
    const dropdownArrow = document.getElementById(arrowId);
    const checkboxes = dropdown.querySelectorAll(
        'input[name="' + checkboxName + '[]"]'
    );

    dropdownButton.addEventListener('click', () => {
        const isHidden = dropdown.classList.toggle('hidden');

        dropdownButton.setAttribute('aria-expanded', String(!isHidden));
        dropdownArrow.classList.toggle('rotate-180', !isHidden);
    });

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', updateSelectedItems);
    });

    function updateSelectedItems() {
        const selected = [...checkboxes]
            .filter((checkbox) => checkbox.checked);

        if (selected.length === 0) {
            selectedText.textContent = defaultText;
        } else {
            const itemNameToShow = selected.length === 1 ? itemName : itemNamePlural;
            selectedText.textContent = `${selected.length} ${itemNameToShow} selected`;
        }
    }

    document.addEventListener('click', (event) => {
        if (
            !dropdownButton.contains(event.target) &&
            !dropdown.contains(event.target)
        ) {
            dropdown.classList.add('hidden');
            dropdownButton.setAttribute('aria-expanded', 'false');
            dropdownArrow.classList.remove('rotate-180');
        }
    });
}

setupDropdown(
    'partyDropdownButton',
    'partyDropdown',
    'selectedPartyText',
    'partyDropdownArrow',
    'parties',
    'Select parties',
    'party',
    'parties'
);

setupDropdown(
    'positionDropdownButton',
    'positionDropdown',
    'selectedPositionText',
    'positionDropdownArrow',
    'positions',
    'Select positions',
    'position',
    'positions'
);