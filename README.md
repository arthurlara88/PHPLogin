CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('professor','aluno') NOT NULL,
    turma VARCHAR(50),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `turma`, `criado_em`) VALUES (NULL, 'Ednaldo Pereira', 'ep@gmail.com', '$2y$10$VbrFwovfplZmjYqYIg599.cNpw1IXXHnq/CPTP5mDtayj9VmGSg7.', 'professor', NULL, CURRENT_TIMESTAMP);
