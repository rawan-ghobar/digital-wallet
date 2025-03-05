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

    const swalletId = document.getElementById("swallet_id");
    const rwalletId = document.getElementById("rwallet_id");
    const walletPin = document.getElementById("wallet_pin");
    const amount   = document.getElementById("amount");
    const errorMessage = document.getElementById("errorMessage");
    const successMessage = document.getElementById("successMessage");
    const sendBtn = document.getElementById("send-btn");

    sendBtn.addEventListener("click", async (event) => {
        event.preventDefault()
        const swalletIdInt = parseInt(swalletId.value, 10);
        const rwalletIdInt = parseInt(rwalletId.value, 10);
        const amountFloat = parseFloat(amount.value);

        try {
            const response = await axios.post(
                "http://localhost/digital-wallet/wallet-server/client/v1/p2p.php",
                {
                    swallet_id: swalletIdInt,
                    wallet_pin: walletPin.value,
                    amount: amountFloat,
                    rwallet_id : rwalletIdInt

                },
                {
                    headers: {
                        "Content-Type": "application/json",
                    }
                }
            );

        console.log(response);

        if (response.data.success) {
            successMessage.textContent = response.data.message; 
        } else {
            errorMessage.textContent = response.data.message; 
        }

    } catch (error) {
        console.error('Transfer error:', error);
        errorMessage.textContent = "Transfer failed. Please try again.";
    }
    });
});
