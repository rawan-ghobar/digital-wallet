// General Axios request function to handle POST requests
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
    const loginBtn = document.getElementById("login-button");
    const loginInfo = document.getElementById("login");
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("errorMessage");

    loginBtn.addEventListener("click", async (event) => {
        event.preventDefault(); 

        const loginURL = "http://localhost/digital-wallet/wallet-server/client/v1/login.php";
        const loginData = {
            login: loginInfo.value,
            password: passwordInput.value,
        };

        try {
            const response = await request(loginURL, loginData);

            console.log(response); 

            if (response.data.success) {
                window.location.href = '/home.html'; 
            } else {
                errorMessage.textContent = response.data.message; 
            }

        } catch (error) {
            errorMessage.textContent = "Login failed. Please try again.";
        }
    });
});
