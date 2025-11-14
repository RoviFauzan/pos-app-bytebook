<?php
// For static hosting (Vercel/GitHub Pages) this file is not executed.
// Use index.html at the same path to redirect to view/login.html.
// For PHP-capable hosting, keep this redirect:
header("Location: controller/Controller.php?u=login");
exit;