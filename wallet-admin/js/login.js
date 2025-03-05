document.addEventListener("DOMContentLoaded", () => {
    console.log("DOMContentLoaded fired - top of script.");
  
    const loginBtn = document.getElementById("login-button");
    const email = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("errorMessage");
  
    console.log("Found elements, about to add event listener.");
  
    loginBtn.addEventListener("click", async (event) => {
      event.preventDefault();
      console.log("Login button clicked!");
  
      try {
        console.log("Inside try block, about to send request...");
  
        const response = await axios.post(
          "http://localhost/digital-wallet/wallet-server/admin/v1/admin_login.php",
          {
            email: email.value,
            admin_password: passwordInput.value,
          },
          {
            headers: {
              "Content-Type": "application/json",
            }
          }
        );
  
        console.log("Response received:", response); 
        if (response.data.success) {
          console.log("Success is true, storing token.");
          const token = response.data?.data?.token;
          console.log("Extracted token:", token);
          localStorage.setItem("jwtToken", token);
          console.log("Redirecting to home.html now...");
          window.location.href = 'home.html';
        } else {
          console.log("Success is false, showing error message.");
          errorMessage.textContent = response.data.message; 
        }
      } catch (error) {
        console.log("We are in the catch block!");
        console.error("Login error:", error);
        errorMessage.textContent = "Login failed. Please try again.";
      }
    });
  });
  