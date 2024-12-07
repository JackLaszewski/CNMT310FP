"use strict";

document.addEventListener("DOMContentLoaded", () => {
    const loginCard = document.querySelector(".login-card");
    const classCard = document.querySelector(".class-card");
    const logoutButton = document.querySelector("#logout-button");

    // Handle login card visibility based on localStorage
    const showLogin = localStorage.getItem("showLogin");

    if (showLogin === "false") {
        loginCard.style.display = "none";
        classCard.classList.add("expanded");
    } else {
        loginCard.style.display = "block";
        classCard.classList.remove("expanded");
    }

    // Check if the user is logged in
    if (logoutButton === null) {
        loginCard.style.display = "block";
        classCard.classList.remove("expanded");
    } else {
        loginCard.style.display = "none";
        classCard.classList.add("expanded");
    }
});