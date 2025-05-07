<?= $this->extend('layouts/common') ?>

<?= $this->section('head') ?>
<style>
    .login-container {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
    }

    .login-title {
        text-align: center;
        margin-bottom: 1.5rem;
        color: var(--clr-text);
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .login-message {
        text-align: center;
        color: #ff6b6b;
        font-size: 0.875rem;
    }

    .login-footer {
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
    <div class="login-container">
        <h2 class="login-title">Login</h2>

        <form id="loginForm" class="login-form">
            <?= view('components/InputField', [
                'id' => 'email',
                'name' => 'email',
                'label' => 'E-mail',
                'type' => 'email',
                'placeholder' => 'email@exemplo.com'
            ]) ?>

            <?= view('components/InputField', [
                'id' => 'password',
                'name' => 'password',
                'label' => 'Senha',
                'type' => 'password',
                'placeholder' => 'Digite a senha'
            ]) ?>

            <?= view('components/ButtonField', [
                'text' => 'Entrar',
                'variant' => 'primary',
                'type' => 'submit'
            ]) ?>

            <div id="msg" class="login-message"></div>

            <p class="login-footer">Ainda não possui conta?</p>

            <?= view('components/ButtonField', [
                'text' => 'Cadastre-se',
                'variant' => 'secondary',
                'href' => '/register'
            ]) ?>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('loginForm').addEventListener('submit', async e => {
        e.preventDefault();
        showLoader();
        try {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            console.log('Input values:', {
                email: emailInput.value,
                password: passwordInput.value
            });

            const formData = {
                email: emailInput.value,
                password: passwordInput.value
            };

            const res = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify(formData)
            });

            if (res.ok) {
                location = '/dashboard';
            } else {
                const data = await res.json();
                console.log('Error response:', data);
                document.getElementById('msg').innerText = data?.message ?? data?.messages?.error ?? 'Algo deu errado';
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