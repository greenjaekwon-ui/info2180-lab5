window.addEventListener('DOMContentLoaded', () => {
    const countryInput = document.getElementById('country');
    const result = document.getElementById('result');
    const lookupBtn = document.getElementById('lookup');
    const citiesBtn = document.getElementById('lookup-cities');

    function fetchData(type) {
        const country = encodeURIComponent(countryInput.value.trim());
        let url = `world.php?country=${country}`;
        if (type === 'cities') {
            url += '&lookup=cities';
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed: ' + response.status);
                }
                return response.text();
            })
            .then(html => {
                result.innerHTML = html;
            })
            .catch(error => {
                result.innerHTML = '<p>Something went wrong. Please try again.</p>';
                console.error(error);
            });
    }

    lookupBtn.addEventListener('click', () => fetchData('country'));
    citiesBtn.addEventListener('click', () => fetchData('cities'));
});
