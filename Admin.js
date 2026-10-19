document.addEventListener("DOMContentLoaded", function () {

    const banButtons = document.querySelectorAll(
        'button[name="account_action"][value="Ban"], button[name="user_action"][value="Ban"]'
    );

    banButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {

            const confirmBan = confirm("Are you sure you want to ban this user?");

            if (!confirmBan) {
                event.preventDefault();
            }

        });
    });


    const unbanButtons = document.querySelectorAll(
        'button[name="account_action"][value="Unban"]'
    );

    unbanButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {

            const confirmUnban = confirm("Are you sure you want to unban this user?");

            if (!confirmUnban) {
                event.preventDefault();
            }

        });
    });


    const updateStatusButton = document.querySelector(
        'button[name="update_status"]'
    );

    if (updateStatusButton) {

        updateStatusButton.addEventListener("click", function (event) {

            const confirmUpdate = confirm(
                "Are you sure you want to update this penalty status?"
            );

            if (!confirmUpdate) {
                event.preventDefault();
            }

        });
    }


    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');

    if (startDate && endDate) {

        const reportForm = startDate.closest("form");

        reportForm.addEventListener("submit", function (event) {

            if (
                startDate.value !== "" &&
                endDate.value !== "" &&
                startDate.value > endDate.value
            ) {

                alert("Start date cannot be later than end date.");
                event.preventDefault();

            }

        });
    }

});