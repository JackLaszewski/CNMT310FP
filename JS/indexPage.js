"use strict";
document.addEventListener("DOMContentLoaded", () => {
  // get login button and class card
  const loginCard = document.querySelector(".login-card");
  const logoutButton = document.querySelector("#logout-button");
  const loginForm = document.querySelector("#login-form");

  let showLogin = localStorage.getItem("showLogin");
  if (showLogin === "false") {
    loginCard.style.display = "none";
  } else {
    loginCard.style.display = "block";
  }

  // add event listener to login form
  loginForm.addEventListener("submit", (event) => {
    //event.preventDefault();
    localStorage.setItem("showLogin", "false"); //cookie for keeping the login card open/closed
    // toggle visibility of login card
    loginCard.style.display = "none";
  });

  // add event listener to logout button
  logoutButton.addEventListener("click", () => {
    localStorage.setItem("showLogin", "true");
    // toggle visibility of login card
    loginCard.style.display = "block";
  });
});
