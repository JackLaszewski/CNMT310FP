"use strict";
document.addEventListener("DOMContentLoaded", () => {

    // get login card and class card
    const loginCard = document.querySelector(".login-card");
    const classCard = document.querySelector(".class-card");

    //add event listener to login card submit button
    loginCard.addEventListener("submit", (event) => {
        console.log("Form submitted");
        //event.preventDefault();
        //toggle visability of login card and class card
        loginCard.classList.add("hidden");
        classCard.classList.remove("hidden");

    });
});