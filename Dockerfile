# Use a imagem base do Debian 12
FROM debian:12

# Adiciona metadados ao contêiner
LABEL maintainer="CaioSimioni"
LABEL version="1.0"
LABEL description="Imagem para o projeto integrador de saúde"
LABEL php_version="7.4"
LABEL name="projetosaude"

# Atualiza a lista de pacotes e instala as dependências necessárias
RUN apt-get update && apt-get install -y \
    bash \
    curl \
    wget \
    vim \
    git \
    php \
    php-mysql \
    net-tools \
    && rm -rf /var/lib/apt/lists/*

# Copia todos os arquivos do diretório atual para o diretório /data no contêiner
COPY . /data

WORKDIR /data

# Expõe a porta padrão do HTTP
EXPOSE 80

# Define o comando padrão para iniciar o servidor PHP embutido
CMD [ "php", "-S", "0.0.0.0:8080", "./router.php" ]
