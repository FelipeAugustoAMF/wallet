<?php
?>

<style>
    .input-field {
        margin-bottom: 1rem;
        display: flex;
        flex-direction: column;
    }

    .input-field__label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--clr-text);
    }

    .input-field__control {
        padding: 0.6rem 1rem;
        border: 1px solid var(--bg-panel);
        border-radius: 0.3rem;
        background-color: var(--btn-backgound);
        color: var(--clr-text);
        font-size: 1rem;
        transition: border-color 0.3s ease-in-out;
        box-shadow: none;
        outline: none;
        width: 100%;
    }

    .input-field__control:focus,
    .input-field__control:active {
        border-color: rgb(255, 255, 255) !important;
    }
</style>

<div class="input-field">
    <label class="input-field__label" for="<?= esc($id) ?>"><?= esc($label) ?></label>
    <input
        type="<?= esc($type ?? 'text') ?>"
        id="<?= esc($id) ?>"
        name="<?= esc($name) ?>"
        class="input-field__control"
        placeholder="<?= esc($placeholder ?? '') ?>"
        <?= isset($minlength) ? 'minlength="' . esc($minlength) . '"' : '' ?>
        <?= isset($maxlength) ? 'maxlength="' . esc($maxlength) . '"' : '' ?>
        <?= isset($pattern) ? 'pattern="' . esc($pattern) . '"' : '' ?>
        <?= isset($autocomplete) ? 'autocomplete="' . esc($autocomplete) . '"' : '' ?>
        <?= isset($min) ? 'min="' . esc($min) . '"' : '' ?>
        <?= isset($max) ? 'max="' . esc($max) . '"' : '' ?>
        <?= isset($step) ? 'step="' . esc($step) . '"' : '' ?>
        required>
</div>