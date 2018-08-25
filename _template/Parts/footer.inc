    <?php if (UserInfo::IsAuthUser()): ?>

        <script>

            function session_renew()
            {

                $.ajax({
                    url: "<?=_SPM_?>index.php?cmd=session-renew",
                });

            }

            document.addEventListener('DOMContentLoaded', function() {

                session_renew();
                setInterval(session_renew, 1000 * 60 * 5);

            });

        </script>

    <?php endif; ?>

    <script src="<?=_SPM_assets_?>js/bootstrap.min.js"></script>

    </body>

</html>