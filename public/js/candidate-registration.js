document.querySelectorAll('.application-toggle').forEach((button) => {
    button.addEventListener('click', () => {
        const target = document.getElementById(button.getAttribute('aria-controls'));
        const isOpen = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        button.querySelector('svg').classList.toggle('rotate-180', !isOpen);
        target.style.maxHeight = isOpen ? '0' : `${target.scrollHeight}px`;
        target.style.opacity = isOpen ? '0' : '1';
        button.querySelector('.sr-only').textContent = isOpen
            ? 'Show application details'
            : 'Hide application details';
    });
});

const election = document.getElementById('election');
if (election) {
const position = document.getElementById('position');
const party = document.getElementById('party');

// Fill a <select> safely. textContent is used, so a title can never run as HTML.
function fillSelect(select, firstLabel, items, labelField) {
    select.innerHTML = '';
    const first = document.createElement('option');
    first.value = '';
    first.textContent = firstLabel;
    select.appendChild(first);
    items.forEach((item) => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item[labelField];
        select.appendChild(option);
    });
}

election.addEventListener('change', async () => {
    fillSelect(position, 'Loading positions...', [], 'title');
    fillSelect(party, 'Loading parties...', [], 'name');

    if (!election.value) {
        fillSelect(position, 'Select Position', [], 'title');
        fillSelect(party, 'No party / Independent', [], 'name');
        return;
    }

    try {
        const response = await fetch(
            `get-election-options.php?election_id=${encodeURIComponent(election.value)}`
        );

        if (!response.ok) {
            throw new Error('Could not load election options');
        }

        const data = await response.json();

        fillSelect(position, 'Select Position', data.positions, 'title');
        // An election may have no parties. Then only "No party / Independent" is shown.
        fillSelect(party, 'No party / Independent', data.parties, 'name');
    } catch (error) {
        fillSelect(position, 'Could not load positions', [], 'title');
        fillSelect(party, 'Could not load parties', [], 'name');
        console.error(error);
    }
});
}
