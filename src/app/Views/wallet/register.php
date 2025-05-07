<?= $this->extend('layouts/common') ?>

<?= $this->section('head') ?>
<style>
    .register-container {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
    }

    .register-title {
        text-align: center;
        margin-bottom: 1.5rem;
        color: var(--clr-text);
    }

    .register-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .register-message {
        text-align: center;
        color: #ff6b6b;
        font-size: 0.875rem;
    }

    .register-footer {
        text-align: center;
        margin: 1rem 0;
        color: var(--clr-text);
        font-size: 0.875rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= view('components/Loader') ?>

<div class="card-panel">
    <div class="register-container">
        <h2 class="register-title">Criar conta</h2>

        <form id="regForm" class="register-form">
            <?= view('components/InputField', [
                'id' => 'name',
                'name' => 'name',
                'label' => 'Nome',
                'type' => 'text',
                'minlength' => '3'
            ]) ?>

            <?= view('components/InputField', [
                'id' => 'email',
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
                'placeholder' => 'voce@exemplo.com'
            ]) ?>

            <?= view('components/InputField', [
                'id' => 'password',
                'name' => 'password',
                'label' => 'Senha',
                'type' => 'password',
                'minlength' => '6'
            ]) ?>

            <?= view('components/ButtonField', [
                'text' => 'Registrar',
                'variant' => 'primary',
                'type' => 'submit'
            ]) ?>

            <div id="msg" class="register-message"></div>

            <p class="register-footer">Já possui conta?</p>

            <?= view('components/ButtonField', [
                'text' => 'Login',
                'variant' => 'secondary',
                'href' => '/'
            ]) ?>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('regForm').addEventListener('submit', async e => {
        e.preventDefault();
        showLoader();
        try {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            console.log('Input values:', {
                name: nameInput.value,
                email: emailInput.value,
                password: passwordInput.value
            });

            const formData = {
                name: nameInput.value,
                email: emailInput.value,
                password: passwordInput.value
            };
            
            const res = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify(formData)
            });

            if (res.ok) {
                location = '/';
            } else {
                const data = await res.json();
                console.log('Error response:', data);
                document.getElementById('msg').innerText = 
                    (data.messages && Object.values(data.messages).join(', ')) ?? 
                    'Erro ao registrar';
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('msg').innerText = 'Erro ao processar a requisição';
        } finally {
            hideLoader();
        }
    });
</script>
<?= $this->endSection() ?>