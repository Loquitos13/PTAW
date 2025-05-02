let firstName = document.getElementById('firstName');
let lastName = document.getElementById('lastName');
let email = document.getElementById('email');
let password = document.getElementById('password');
let confPassword = document.getElementById('confirmPassword');
let infoMessage = document.getElementById('infoMessage');

function validatePassword() {
  let validate = false;

  if (password.value === confPassword.value) {

    validate = true;

  }

  return validate;

}

async function getEmailAvailability(email) {

  try {
    const response = await fetch('../client/checkEmailAvailability.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({email: email})
    })

    const data = await response.json();
    
    return JSON.stringify(data.data);
  
  } catch (error) {
    
    return null;
  
  }
}

document.getElementById('createAccForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  if (firstName && firstName.value && lastName && lastName.value && email && email.value && password && password.value && confPassword && confPassword.value) {

    infoMessage.textContent = "";

    if (validatePassword()) {

      infoMessage.textContent = "";

      const emailAvailability = await getEmailAvailability(email.value);

      if (emailAvailability === "null") {
        
        infoMessage.textContent = "";

        const formData = {
          nome_cliente: firstName.value + " " + lastName.value,
          email_cliente: email.value,
          pass_cliente: password.value
        };

        const response = await fetch('../client/createUser.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.status === 'success') {

          window.location.href = "/PTAW/index.php";

        } else {

          infoMessage.textContent = result.message || "Registration failed!";

        }

      } else {

        infoMessage.textContent = "Email already in use!";

      }
      
    } else {

      infoMessage.textContent = "Passwords don't match";

      }

  } else {
      
    infoMessage.textContent = "Please fill all the fields!";

  }

});
