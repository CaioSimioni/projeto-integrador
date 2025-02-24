CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Opcional: Criar um usuário admin inicial
-- Senha: admin@1234
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@example.com', '$2y$12$O4HlpXtwzpKLqFo5hGDAseuxb.chDa850Y8RbKQnE/wkuX1mamxLe');
