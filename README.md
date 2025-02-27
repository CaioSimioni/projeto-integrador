# Projeto Saude

<img src="./public/images/login_page.png">

## Descrição

O Projeto Saude é uma iniciativa do curso de Bacharelado em Tecnologia da Informação que busca melhorar a eficiência no atendimento de pacientes em postinhos de saúde. O objetivo é desenvolver uma solução tecnológica que otimize o processo de agendamento, triagem e atendimento, proporcionando uma melhor experiência tanto para os pacientes quanto para os profissionais de saúde.

![GitHub release (latest by date)](https://img.shields.io/github/v/release/CaioSimioni/projeto-integrador)
![GitHub](https://img.shields.io/github/license/CaioSimioni/projeto-integrador)
![GitHub language count](https://img.shields.io/github/languages/top/CaioSimioni/projeto-integrador?color=blue&label=PHP)

## Deploy

Para configurar o ambiente e rodar o projeto, siga os passos abaixo:

1. Clone o repositório para o seu ambiente local:

    ```sh
    git clone https://github.com/CaioSimioni/projeto-integrador.git
    cd projeto-integrador
    ```

2. Configure o arquivo `.env` com as variáveis de ambiente necessárias:

    ```.env
    # App
    APP_NAME=ProjetoSaude
    APP_PORT=8080
    APP_URL=http://localhost:8080

    # Database
    DB_ROOT_PASSWORD=root
    DB_HOST=db
    DB_DRIVER=mysql
    DB_DATABASE=projetointegrador
    DB_USER=appuser
    DB_PASSWORD=asdasd
    DB_PORT=3306

    # PHPMyAdmin
    PMA_PORT=8081
    ```

3. Execute o comando para iniciar os serviços com Docker:

    ```sh
    docker-compose up --build
    ```

4. Acesse a aplicação no navegador utilizando a URL configurada no arquivo `.env`:

    ```
    http://localhost:3000
    ```

5. Para acessar o PHPMyAdmin, utilize a porta configurada na variável `PMA_PORT` no arquivo `.env`. Por exemplo:

    ```
    http://localhost:8081
    ```

6. Para parar os serviços Docker, utilize um dos seguintes comandos:

    ```sh
    docker-compose stop
    ```

    ou

    ```sh
    docker-compose down
    ```

7. Para adicionar um usuário admin inicial, altere o final do arquivo `init.sql` adicionando a seguinte linha:

    ```sql
    -- Opcional: Criar um usuário admin inicial
    -- Senha: admin@1234
    INSERT INTO users (username, email, password) VALUES
    ('admin', 'admin@example.com', '$2y$12$O4HlpXtwzpKLqFo5hGDAseuxb.chDa850Y8RbKQnE/wkuX1mamxLe');
    ```

## Contribuir

Para mais detalhes sobre como contribuir, consulte o [guia de contribuição](./CONTRIBUTING.md).

Vídeo ensinando a contribuir para o projeto: [Como contribuir para o projeto integrador](https://youtu.be/qOvohOSjMp4?si=kSjac_U-bX2DvoPZ).

## Licença

Este projeto está licenciado sob a [MIT LICENSE](./LICENSE).

## Contribuidores

| [<img src="https://avatars.githubusercontent.com/u/83130766?v=4" width=115><br><sub>Caio Ribeiro Simioni</sub>](https://github.com/CaioSimioni) |  [<img src="https://avatars.githubusercontent.com/u/170760593?v=4" width=115><br><sub>Paulo Henrique Justino da Silva</sub>](https://github.com/JustinoSilva15) | [<img src="https://avatars.githubusercontent.com/u/146387290?v=4" width=115><br><sub>Lucas Henrique Dias Castro</sub>](https://github.com/lucashdc) | [<img src="https://avatars.githubusercontent.com/u/200537143?v=4" width=115><br><sub>Pedro Iago Victorio Das Dores</sub>](https://github.com/PEDROIAGOP5) | [<img src="https://avatars.githubusercontent.com/u/200687095?v=4" width=115><br><sub>Benjamin Rogério Sanches</sub>](https://github.com/benjamin-sanches) |
| :---: | :---: | :---: | :--: | :---: |
