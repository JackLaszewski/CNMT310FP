"use strict";
document.addEventListener("DOMContentLoaded", () => {
  // get login button and class card
  const loginCard = document.querySelector(".login-card");
  const logoutButton = document.querySelector("#logout-button");
  

  //creates cookie for keeping the login card open/closed
  let showLogin = localStorage.getItem("showLogin");
  if (showLogin === "false") {
    loginCard.style.display = "none";
  } else {
    loginCard.style.display = "block";
  }

  //check if logout button exists
  if (logoutButton === null) {
    loginCard.style.display = "block";
  }else{
    loginCard.style.display = "none";
  }

  //make smooth transition for class card size


});
