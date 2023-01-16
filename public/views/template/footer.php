    </main>
    <footer>
        <div class="container text-secondary">
            <p class="float-end mb-1">
                <a href="#" class="link-light">Back to top</a>
            </p>
            <p class="mb-0">Website created by <a href="mailto:sebastian@hitaitaro.com" class="link-secondary">Sebastián
                    Cámara</a> using <a href="https://getbootstrap.com/" class="link-secondary">Bootstrap</a>.</p>
        </div>
    </footer>

    <script src="<?php echo $this->getLibPath(); ?>bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
    <script src="<?php echo $this->getLibPath(); ?>jquery/jquery.min.js"></script>
    <script src="<?php echo $this->getJsPath(); ?>main.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-3655566-5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-3655566-5');
    </script>
</body>

</html>