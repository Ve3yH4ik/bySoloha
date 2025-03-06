document.getElementById('product_id').onchange = function () {
    const productId = this.value;

    fetch(`get_options.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            const optionsSelect = document.getElementById('options_select');
            optionsSelect.innerHTML = '';
            data.options.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option.id;
                optionElement.textContent = option.name;
                optionsSelect.appendChild(optionElement);
            });
        });
};