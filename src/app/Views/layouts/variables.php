<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title><?= esc($title ?? 'Wallet') ?></title>

    <style>
        :root {
            --bg-dark: #00060c;
            --bg-panel: #02223f;
            --clr-text: rgb(255, 255, 255);
            --clr-accent: #7b1e12;
            --clr-accent-h: #992819;
            --btn-backgound: rgb(150, 150, 150);

            --btn-py: .6rem;
            --btn-px: 1.2rem;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }
    </style>

    <?= $this->renderSection('head') ?>
</head>

<body>
    <?= $this->renderSection('body') ?>
</body>

</html>