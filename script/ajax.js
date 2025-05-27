document.getElementById('booking').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('../AJAXbooking.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const modal = document.getElementById('picture');
        const message = document.getElementById('message');

        if (data.success) {
            message.innerHTML = `Foglalás sikeres! Összeg: ${data.totalPrice} Ft CAR ID: ${data.carId} .${data.checkin} - ${data.checkout}`;
            message.style.color = 'green';
        } else {
            message.innerHTML = `Hiba: ${data.error}`;
            message.style.color = 'red';
        }

        modal.classList.remove('hidden');
    })
});

document.getElementById('close').addEventListener('click', function () {
    document.getElementById('picture').classList.add('hidden');
});
