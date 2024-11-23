<style>
 .notification {
    position: fixed;
    top: -1000px;
    right: 10px;
    width: 300px;
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    color: #fff;
    z-index: 20000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #4CAF50;
    opacity: 0;
    animation: slideInOut 20s forwards ;
}

.notification.success {
    background-color: #4CAF50; /* Green */
}

.notification.error {
    background-color: #f44336; /* Red */
}

.remove-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    margin-left: 10px;
}

@keyframes slideInOut {
    0% {
        top: -100px; /* Start above the viewport */
        opacity: 0;
    }
    10% {
        top: 20px; /* Final position */
        opacity: 1;
    }
    15% {
        top: 20px; /* Final position */
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        top: -1000px; /* Move above the viewport */
        opacity: 0;
    }
}

</style>

    @if (session('success'))
        <div class="notification success">
            {{ session('success') }}
            <button class="remove-btn">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="notification error">
            {{ session('error') }}
            <button class="remove-btn">&times;</button>
        </div>
    @endif

    <!-- Your content here -->

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                const removeBtn = notification.querySelector('.remove-btn');
                removeBtn.addEventListener('click', () => {
                    notification.remove();
                });

                // Remove notification after a delay
                setTimeout(() => {
                    notification.remove();
                }, 10000); // Time in milliseconds (e.g., 5000ms = 5s)
            });
        });
    </script>

