document.addEventListener("DOMContentLoaded", async () => {
    const walletsContainer = document.getElementById("walletsContainer");
    const token = localStorage.getItem("jwtToken");
    if (!token) {
        walletsContainer.innerHTML = "<p>Please log in to view your wallets.</p>";
        return;
    }

    try {
        const response = await axios.get("http://localhost/digital-wallet/wallet-server/client/v1/view_wallet.php", {
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        if (response.data.success) {
            const wallets = response.data.message.wallets;
            if (wallets.length === 0) {
                walletsContainer.innerHTML = "<p>No wallets found for this user</p>";
            } else {
                walletsContainer.innerHTML = ""; 
        
                wallets.forEach(wallet => {
                    const walletBox = document.createElement("div");
                    walletBox.classList.add("grid-item-wallet");
                    
                    walletBox.innerHTML = `
                        <p><strong>ID:</strong> ${wallet.wallet_id}</p>
                        <p><strong>Name:</strong> ${wallet.wallet_name}</p>
                        <p><strong>Balance:</strong> ${wallet.wallet_balance}</p>
                    `;
                    
                    walletsContainer.appendChild(walletBox);
                });
            }
        } else {
            walletsContainer.innerHTML = `<p>Error: ${response.data.message}</p>`;
        }
    } catch (error) {
        console.error("Error retrieving wallets:", error);
        walletsContainer.innerHTML = "<p>Failed to load wallets. Please try again later.</p>";
    }
});
