document.addEventListener("DOMContentLoaded", () => {
    const loginBtn = document.getElementById("login-button");
    const loginInfo = document.getElementById("login");
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("errorMessage");

    loginBtn.addEventListener("click", async (event) => {
        event.preventDefault(); 

        try {
            const response = await axios.post(
                "http://localhost/digital-wallet/wallet-server/client/v1/login.php",
                {
                    login: loginInfo.value,
                    user_password: passwordInput.value,
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
            console.error('Login error:', error);
            errorMessage.textContent = "Login failed. Please try again.";
        }
    });
});
