<footer class="footer orange darken-3">
    <p>&copy; <?php echo date("Y"); ?> Srirammanan 2024 | Prepared for internal use, strictly no public or violation</p>
</footer>

<!-- Custom CSS for footer animation -->
<style>
    .footer {
        text-align: center;
        padding: 20px 0;
        background-color: #ff9800; /* Orange color */
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 9999;
        opacity: 0;
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 1s;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
