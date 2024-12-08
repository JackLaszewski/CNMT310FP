"use strict";
document.addEventListener("DOMContentLoaded", () => {

    let modal = document.querySelector("#modal");
    let span = document.querySelector(".close");
    let viewCourseButtons = document.querySelectorAll(".view-course");

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.classList.remove("open");
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.classList.remove("open");
        }
    }

    //adds event listener to each view course button
    viewCourseButtons.forEach(button => {
        button.addEventListener("click", () => {
            let studentId = button.getAttribute("data-student-id");
            openModal(studentId);//pass in student id
        });
    });

    function openModal(studentId) {
        // Open the modal
        modal.classList.add("open");

        // Fetch the student's courses using AJAX
        fetch('ModalStudentCourses.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'text/html',
            },
            body: JSON.stringify({ student_id: studentId }) // Send student ID to PHP
        })
            .then(response => response.text()) // Expect HTML response instead of JSON
            .then(data => {
                if (data) {
                    // Insert the HTML content (table of courses) into the modal
                    document.getElementById('modal-course-list').innerHTML = data;
                } else {
                    document.getElementById('modal-course-list').innerHTML = "<p>No courses found.</p>";
                }
            })
            .catch(error => console.error('Error fetching courses:', error));
    }

    // Close the modal when the user clicks on the close button
    closeBtn.onclick = function () {
        modal.classList.remove("open");
    }
});

document.getElementById('submit-add-class').addEventListener('click', function() {
    const form = document.getElementById('add-class-form');
    const formData = new FormData(form);

    fetch('adminFunctionView.php?action=add_class', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Identifies the request as AJAX
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.result === 'Success') {
            alert('Course added successfully! Course ID: ' + data.data.course_id);
            form.reset(); // Clear the form
        } else {
            alert('Error adding course: ' + (data.message || 'Unknown error.'));
        }
    })
    .catch(error => console.error('Error:', error));
});