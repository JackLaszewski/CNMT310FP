"use strict";
document.addEventListener("DOMContentLoaded", () => {

    let modal = document.querySelector("#modal");
    let span = document.querySelector(".close");
    let viewCourseButtons = document.querySelectorAll("#view-course");

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal && modal.style.display == "block") {
            modal.style.display = "none";
        }
    }

    //adds event listener to each view course button
    viewCourseButtons.forEach(button => {
        button.addEventListener("click", () => {
            modal.style.display = "block";
        });
    });

});