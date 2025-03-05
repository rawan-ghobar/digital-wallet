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
    // 1) Select elements
    const walletName = document.getElementById("wallet_name");
    const walletPin = document.getElementById("wallet_pin");
    const errorMessage = document.getElementById("errorMessage");
    const createBtn = document.getElementById("wallet-create-btn");

    // Popup Elements
    const popupBox = document.getElementById("popupBox");
    const popupMessage = document.getElementById("popupMessage");
    const popupClose = document.getElementById("popupClose");

    // 2) Function to show popup
    function showPopup(message) {
        popupMessage.textContent = message;
        popupBox.style.display = "flex"; // Make popup visible
    }

    // 3) Close button event listener: Hide popup and redirect
    popupClose.addEventListener("click", () => {
        popupBox.style.display = "none";
        window.location.href = "wallets.html"; // Redirect after closing
    });

    // 4) Event listener for wallet creation
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
                showPopup("Wallet created successfully!");
            } else {
                errorMessage.textContent = response.data.message;
            }

        } catch (error) {
            console.error('Wallet creation error:', error);
            errorMessage.textContent = "Wallet creation failed. Please try again.";
        }
    });
});
