<!-- Footer Section using Bootstrap -->
<footer class="bg-dark text-white text-center text-lg-start py-3 mt-auto">
    <div class="container">
        <!-- Footer Content -->
        <div class="row">
            <!-- Left Section: Copyright -->
            <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                <p>&copy; 2024 Police System. All rights reserved.</p>
            </div>

            <!-- Center Section: Links (Vertical) -->
            <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                <p class="d-flex flex-column">
                    <a href="#" class="text-white text-decoration-none mb-2">About Us</a>
                    <a href="#" class="text-white text-decoration-none mb-2">Contact</a>
                    <a href="#" class="text-white text-decoration-none mb-2">Privacy Policy</a>
                </p>
            </div>

            <!-- Right Section: Social Media Icons -->
            <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
              <p class="d-flex flex-column">
                <a href="https://www.facebook.com" target="_blank" class="text-white text-decoration-none mb-2"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="https://twitter.com" target="_blank" class="text-white text-decoration-none mb-2"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="https://www.linkedin.com" target="_blank" class="text-white text-decoration-none mb-2"><i class="fab fa-linkedin"></i> LinkedIn</a>
              </p>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn" title="Back to Top">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Include FontAwesome for Social Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- Add jQuery for Back to Top Button -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Back to Top Button Visibility and Functionality
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {  // Show the button when scrolling down 100px
            $('#backToTop').fadeIn();
        } else {
            $('#backToTop').fadeOut();
        }
    });

    // Scroll to the top of the page when the button is clicked
    $('#backToTop').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 600); // Scroll to top over 600ms
        return false;
    });
</script>

<!-- Custom CSS to style the Back to Top Button -->
<style>
    /* Style for the Back to Top Button */
    #backToTop {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: none;
        background-color: red;  /* Red background */
        color: white;           /* White icon color */
        border: none;
        border-radius: 50%;     /* Circular button */
        width: 50px;
        height: 50px;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Shadow for better visibility */
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #backToTop:hover {
        background-color: #cc0000;  /* Darker red on hover */
    }

    #backToTop i {
        margin: 0;
    }
</style>
