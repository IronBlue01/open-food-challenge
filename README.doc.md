# 📦 OpenFood Challenge Codesh - Danilo

Uma aplicação desenvolvida como desafio técnico para a Coodesh.

---

## 🚀 Tecnologias utilizadas

- **Linguagem:** PHP  
- **Framework:** Laravel  
- **Banco de Dados:** Postgres  
- **Containerização:** Docker  
- **Gerenciamento do Cron:** Supervisord 
- **Servidor Web:** Nginx + PHP-FPM  
- **Gerenciamento de dependências:** Composer 
- **Documentação:** Swagger 

---

## 📦 Instalação e uso

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/seu-usuario/nome-do-repositorio.git
   cd nome-do-repositorio
   ```

2. **Copie o arquivo `.env.example` para `.env`:**

   ```bash
   cp .env.example .env
   ```

3. **Dê permissão para a pasta `storage/app/public`, que será usada para salvar os arquivos temporariamente:**

   ```bash
   sudo chmod -R 777 ./storage/app/public
   ```

4. **Configure o webhook de notificação do Slack:**

   Caso deseje ativar a notificação via Slack basta alterar a variavel `SLACK_NOTIFICATION` para `true` no `.env`.

   Depois que ativar a notificação sempre que a importação for executada, a aplicação pode notificar em um canal do Slack se a importação foi bem-sucedida ou não.  
   Para isso, basta configurar a variável `SLACK_WEBHOOK_URL` no arquivo `.env` com o link webhook do canal desejado.
   

5. **Suba os containers da aplicação:**

   ```bash
   sudo docker-compose up -d --build
   ```

6. **Acesse o container da aplicação para executar os comandos necessários para o devido funcionamento:**

   ```bash
   sudo docker exec -it app bash
   ```

7. **Execute a primeira importação manualmente dentro do container:**

   ```bash
   php artisan produtos:baixar-arquivos
   ```

8. **Gerando a documentação da aplicação (dentro do container):**

   ```bash
   php artisan l5-swagger:generate  
   ```
   A documentação poderá ser acessada em:
   ```bash
   http://localhost:8000/api/documentation  
   ```
---

Esta aplicação utiliza **Laravel Sanctum** como sistema de autenticação.

Para começar a utilizar a API, siga os passos abaixo:

1. **Registre um novo usuário** acessando a rota:

   ```
   POST /api/auth/register
   ```

   Payload de exemplo:

   ```json
   {
     "name": "Danilo Henrique Lima de Oliveira",
     "email": "danilo22programador@gmail.com",
     "password": "12345678",
     "password_confirmation": "12345678"
   }
   ```

2. **Realize o login** através da rota:

   ```
   POST /api/auth/login
   ```

   Payload de exemplo:

   ```json
   {
     "email": "danilo22programador@gmail.com",
     "password": "12345678"
   }
   ```

3. A resposta da rota de login irá conter um **token de autenticação**, que deve ser enviado como **Bearer Token** no cabeçalho de todas as requisições subsequentes para acessar as rotas protegidas da aplicação.

---


## 🔄 Observações sobre o projeto

- A aplicação usa um sistema de **cronjob** que roda dentro do container.  
- O **supervisord** é o responsável por iniciar o sistema cron automaticamente.
- Após subir os containers, **não é necessário nenhuma outra configuração** adicional.
- A importação ocorre **todos os dias à meia-noite**.
- Esse horário pode ser alterado facilmente no arquivo `crontab` localizado na raiz da aplicação.

---

👨‍💻 *Desenvolvido por Danilo Jemrique para o desafio técnico da Coodesh.*
