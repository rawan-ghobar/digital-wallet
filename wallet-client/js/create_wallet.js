const request = (url, data, token) => {
    return axios.post(url, data, {
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${token}`
        }
    })
    .then(response => response)
    .catch(error => {
        console.error('Request failed:', error);
        throw error;
    });
};

document.addEventListener("DOMContentLoaded", () => {
    // Select elements
    const walletName = document.getElementById("wallet_name");
    const walletPin = document.getElementById("wallet_pin");
    const errorMessage = document.getElementById("errorMessage");
    const successMessage = document.getElementById("successMessage");
    const createBtn = document.getElementById("wallet-create-btn");

    // Event listener for wallet creation
    createBtn.addEventListener("click", async (event) => {
        event.preventDefault();

        try {
            const token = localStorage.getItem("jwtToken");
            if (!token) {
                errorMessage.textContent = "No token found. Please log in first.";
                return;
            }

            console.log("Token from localStorage:", token);

            const response = await request(
                "http://localhost/digital-wallet/wallet-server/client/v1/add_wallet.php",
                { wallet_name: walletName.value, wallet_pin: walletPin.value },
                token
            );

            console.log("Axios Response:", response);

            if (response.data.success) {
                // Assuming API returns an object in response.data.message with wallet details
                const walletDetails = response.data.message;
                successMessage.textContent = `Wallet created successfully! Wallet ID: ${walletDetails.wallet_id}, Balance: ${walletDetails.wallet_balance}`;
                // Redirect after 3 seconds
                setTimeout(() => {
                    window.location.href = "wallets.html";
                }, 3000);
            } else {
                errorMessage.textContent = response.data.message;
            }

        } catch (error) {
            console.error('Wallet creation error:', error);
            errorMessage.textContent = "Wallet creation failed. Please try again.";
        }
    });
});
