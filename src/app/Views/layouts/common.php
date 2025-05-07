<?= $this->extend('layouts/variables') ?>

<?= $this->section('head') ?>
<style>
    body {
        background: var(--bg-dark);
        color: var(--clr-text);
        min-height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 2rem;
    }

    .card-panel {
        background: var(--bg-panel);
        width: 100%;
        max-width: 420px;
        border-radius: .4rem;
        padding: 2.5rem 2rem 3rem;
        box-shadow: 0 0 20px rgba(0, 0, 0, .4);
    }

    @media (max-width: 576px) {
        .card-panel {
            padding: 2rem 1.25rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('body') ?>
<?= $this->renderSection('content') ?>
<?= $this->renderSection('scripts') ?>
<?= $this->endSection() ?>