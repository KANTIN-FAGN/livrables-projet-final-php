<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$connected = isset($_SESSION['connected']) && $_SESSION['connected'] === true;

$imagePath = BASE_PATH . 'src/public/img/logo.png';
$imageData = base64_encode(file_get_contents($imagePath));
$mimeType = mime_content_type($imagePath);
?>
<header>
    <div class="header-container">
        <div class="header-logo">
            <img src="data:<?= $mimeType ?>;base64,<?= $imageData ?>" alt="logo">
        </div>
        <div class="header-profile">
            <a href="<?= ($connected === true) ? '/profile' : '/login'; ?>">
                <i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-user">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </i>
            </a>
            <?php if ($connected === true) : ?>
                <a href="/logout">
                    <i>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-log-out">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                    </i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>