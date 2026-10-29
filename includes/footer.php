        </main>
    </div>
</div>
<script src="<?= $base ?>/assets/js/validation.js"></script>
<script>
    var btn = document.getElementById("menuBtn");
    if (btn) {
        btn.addEventListener("click", function () {
            document.getElementById("sidebar").classList.toggle("open");
        });
    }
</script>
</body>
</html>