// Music Promotion Modal and Form Handler
(function() {
    const modal = document.getElementById('musicPromotionModal');
    const promotionForm = document.getElementById('promotionForm');
    const waitlistForm = document.getElementById('waitlistForm');
    const waitlistFormElement = document.getElementById('waitlistFormElement');
    const promotionFormElement = document.getElementById('promotionForm');
    const loadingDiv = document.getElementById('promotionLoading');
    const closeBtn = document.querySelector('.close-promotion-modal');

    if (!modal) return;

    // Open modal and check availability
    function openPromotionModal() {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Check slot availability
        checkAvailability();
    }

    // Close modal
    function closePromotionModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        resetForms();
    }

    // Reset forms
    function resetForms() {
        promotionForm.style.display = 'none';
        waitlistForm.style.display = 'none';
        loadingDiv.style.display = 'none';
        if (promotionFormElement) promotionFormElement.reset();
        if (waitlistFormElement) waitlistFormElement.reset();
    }

    // Check slot availability
    async function checkAvailability() {
        try {
            loadingDiv.style.display = 'block';
            promotionForm.style.display = 'none';
            waitlistForm.style.display = 'none';

            const response = await fetch('/promotions/check-availability', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();
            loadingDiv.style.display = 'none';

            if (data.available) {
                promotionForm.style.display = 'block';
                loadPricing();
            } else {
                waitlistForm.style.display = 'block';
            }
        } catch (error) {
            console.error('Error checking availability:', error);
            loadingDiv.style.display = 'none';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to check availability. Please try again.',
                confirmButtonColor: '#c8102e',
            });
        }
    }

    // Load pricing information
    async function loadPricing() {
        try {
            const response = await fetch('/promotions/pricing', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            if (data.durations) {
                const price7 = data.durations.find(d => d.days === 7);
                const price14 = data.durations.find(d => d.days === 14);
                if (price7) {
                    const price7El = document.getElementById('price7Days');
                    if (price7El) price7El.textContent = price7.formatted_price;
                }
                if (price14) {
                    const price14El = document.getElementById('price14Days');
                    if (price14El) price14El.textContent = price14.formatted_price;
                }
            }
        } catch (error) {
            console.error('Error loading pricing:', error);
        }
    }

    // Handle promotion form submission
    if (promotionFormElement) {
        promotionFormElement.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitPromotionBtn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            try {
                const formData = new FormData(promotionFormElement);

                const response = await fetch('/promotions/submit', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success && data.authorization_url) {
                    // Redirect to Paystack checkout
                    window.location.href = data.authorization_url;
                } else {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    
                    if (data.slots_full) {
                        // Slots became full, show waitlist
                        promotionForm.style.display = 'none';
                        waitlistForm.style.display = 'block';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: data.message || 'Failed to submit promotion. Please try again.',
                            confirmButtonColor: '#c8102e',
                        });
                    }
                }
            } catch (error) {
                console.error('Error submitting promotion:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#c8102e',
                });
            }
        });
    }

    // Handle waitlist form submission
    if (waitlistFormElement) {
        waitlistFormElement.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = waitlistFormElement.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Joining...';

            try {
                const formData = new FormData(waitlistFormElement);

                const response = await fetch('/promotions/waitlist', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Waitlist',
                        text: data.message,
                        confirmButtonColor: '#c8102e',
                    }).then(() => {
                        closePromotionModal();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to join waitlist. Please try again.',
                        confirmButtonColor: '#c8102e',
                    });
                }

                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            } catch (error) {
                console.error('Error joining waitlist:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#c8102e',
                });
            }
        });
    }

    // Close modal handlers
    if (closeBtn) {
        closeBtn.addEventListener('click', closePromotionModal);
    }

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closePromotionModal();
            }
        });
    }

    // Escape key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closePromotionModal();
        }
    });

    // Expose open function globally
    window.openMusicPromotionModal = openPromotionModal;
})();
