const registerForm = document.getElementById("registerForm");
const errorMessage = document.querySelector(".message-error");

registerForm.addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    let xhr = new XMLHttpRequest();

    xhr.open('POST', '/register-process', true);

    xhr.onload = function() {
        let result = JSON.parse(xhr.responseText);

        if (xhr.status === 400) {
            errorMessage.classList.add("active");
            errorMessage.textContent = result;
        }

        if (xhr.status === 200) {
            document.location.href = "/login";
        }
    };

    // Отправить запрос
    xhr.send(formData);
})

