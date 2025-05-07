# Wallet Application

Este README explica como iniciar e rodar o projeto **Wallet** tanto com Docker (via Docker Compose) quanto sem Docker, além de como executar as migrações do banco de dados.

---

## 📦 Requisitos

- Git
- **Com Docker**

  - Docker Engine ≥ 20.10
  - Docker Compose v2

- **Sem Docker**

  - PHP ≥ 8.3
  - Composer
  - MySQL ≥ 8.0

---

## ⚙️ Iniciando com Docker

1. **Clone o repositório**

   ```bash
   git clone https://github.com/FelipeAugustoAMF/wallet.git wallet
   cd wallet
   ```

2. **Build e start dos containers**

   ```bash
   # Faz build das imagens e já sobe em background
   docker compose up -d --build
   ```

3. **Verifique se os containers estão rodando**

   ```bash
   docker compose ps
   ```

4. **Rodar as migrations**
   Dentro do container `web`:

   ```bash
   docker compose exec web php spark migrate
   ```

   > Para resetar tudo e rodar do zero:
   >
   > ```bash
   > docker compose exec web php spark migrate:reset
   > docker compose exec web php spark migrate
   > ```

5. **Acessar a aplicação**
   Abra no navegador:

   ```
   http://localhost:8080
   ```

---

## 🐳 Rodando sem Docker

1. **Clone o repositório**

   ```bash
   git clone git clone https://github.com/FelipeAugustoAMF/wallet.git wallet wallet
   cd wallet
   ```

2. **Instale dependências PHP**

   ```bash
   composer install
   ```

3. **Prepare o banco de dados**

   - Crie o banco `wallet` no MySQL
   - Execute as migrations:

     ```bash
     php spark migrate
     ```

4. **Inicie o servidor embutido do PHP**

   ```bash
   php spark serve --host=0.0.0.0 --port=8080
   ```

   Acesse em

   ```
   http://localhost:8080
   ```

---

## ℹ️ Observação sobre o .env

O arquivo `.env` está incluso no repositório **apenas por se tratar de um projeto de teste**. Em projetos reais, nunca versionar arquivos `.env` com credenciais ou segredos sensíveis.
