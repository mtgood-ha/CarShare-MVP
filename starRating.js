document.addEventListener("DOMContentLoaded", () => {
    const ratingContainers = document.querySelectorAll('.star-rating');

    ratingContainers.forEach(container => {
        const stars = container.querySelectorAll('.star');
        const hiddenInputId = container.getAttribute('data-input-id');
        const hiddenInput = document.getElementById(hiddenInputId);

        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const value = parseInt(this.getAttribute('data-value'));
                
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('hover');
                }
                
                for (let i = value; i < stars.length; i++) {
                    if (stars[i].classList.contains('hover')) {
                        stars[i].classList.remove('hover');
                    }
                }
            });

            star.addEventListener('mouseout', () => {
                stars.forEach(s => s.classList.remove('hover'));
            });

            star.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'));
                hiddenInput.value = value; 
                
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('selected');
                }
                
                for (let i = value; i < stars.length; i++) {
                    if (stars[i].classList.contains('selected')) {
                        stars[i].classList.remove('selected');
                    }
                }
            });
        });
    });
});