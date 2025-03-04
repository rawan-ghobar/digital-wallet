const request = (url, data) => {
    return axios.post(url, data, {
        headers: {
            "Content-Type": "application/json",
        }
    })
    .then(response => response)
    .catch(error => {
        console.error('Request failed:', error);
        throw error;
    });
};

document.addEventListener("DOMContentLoaded", () => {

    const walletId = document.getElementById("wallet_id");
    const walletPin = document.getElementById("wallet_pin");
    const amount   = document.getElementById("amount");
    const errorMessage = document.getElementById("errorMessage");
    const depositBtn = document.querySelector("deposit-btn");

    depositBtn.addEventListener("click", async (event) => {
        event.preventDefault()

        try {
            const response = await axios.post(
                "http://localhost/digital-wallet/wallet-server/client/v1/deposit.php",
                {
                    wallet_id: walletId.value,
                    wallet_pin: walletPin.value,
                    amount: amount.value
                },
                {
                    headers: {
                        "Content-Type": "application/json",
                    }
                }
            );

        console.log(response);

        if (response.data.success) {
            window.location.href = 'home.html';
        } else {
            errorMessage.textContent = response.data.message; 
        }

    } catch (error) {
        console.error('Deposit error:', error);
        errorMessage.textContent = "Deposit failed. Please try again.";
    }
    });
});
