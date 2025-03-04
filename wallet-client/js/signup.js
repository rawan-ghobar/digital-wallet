document.addEventListener("DOMContentLoaded", () => {
    const signupBtn = document.getElementById("signup-btn");
    const firstName = document.getElementById("fname");
    const lastName = document.getElementById("lname");
    const email = document.getElementById("email");
    const phoneNb = document.getElementById("phonenb");
    const passwordInput = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");
    const errorMessage = document.getElementById("errorMessage");

    signupBtn.addEventListener("click", async (event) => {
        event.preventDefault(); 

        try {
            const response = await axios.post(
                "http://localhost/digital-wallet/wallet-server/client/v1/signup.php",
                {
                    fname: firstName.value,
                    lname: lastName.value,
                    email: email.value,
                    phonenb: phoneNb.value,
                    user_password: passwordInput.value,
                    confirm_password: confirmPassword.value,
                },
                {
                    headers: {
                        "Content-Type": "application/json",
                    }
                }
            );

            console.log(response);

            if (response.data.success) {
                window.location.href = 'login.html';
            } else {
                errorMessage.textContent = response.data.message; 
            }

        } catch (error) {
            console.error('Login error:', error);
            errorMessage.textContent = "Login failed. Please try again.";
        }
    });
});
