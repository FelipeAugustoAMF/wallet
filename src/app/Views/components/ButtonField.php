<?php // app/Views/components/ButtonField.php ?>

<style>
    /* Component-scoped ButtonField styles */
    .button-group {
        margin-bottom: 1rem;
    }

    .button-control {
        display: block;
        width: 100%;
        padding: var(--btn-py) var(--btn-px);
        font-weight: 500;
        border: none;
        border-radius: .25rem;
        cursor: pointer;
        background: var(--clr-accent);
        color: #fff;
        font-size: 1rem;
        transition: background 0.2s ease-in-out;
        text-align: center;
        text-decoration: none;
    }

    .button-control.primary:hover,
    .button-control.primary:focus {
        background: var(--clr-accent-h);
    }

    .button-control.secondary {
        background: #6c757d;
    }

    .button-control.secondary:hover,
    .button-control.secondary:focus {
        background: #818a91;
    }
</style>

<div class="button-group">
    <?php if (isset($href)): ?>
        <a href="<?= esc($href) ?>"
           class="button-control <?= esc($variant ?? 'primary') ?>"
           <?= isset($id) ? 'id="'.esc($id).'"' : '' ?>>
            <?= esc($text) ?>
        </a>
    <?php else: ?>
        <button type="<?= esc($type ?? 'button') ?>"
                class="button-control <?= esc($variant ?? 'primary') ?>"
                <?= isset($id) ? 'id="'.esc($id).'"' : '' ?>>
            <?= esc($text) ?>
        </button>
    <?php endif; ?>
</div>
