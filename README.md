CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- identificador único
    nome VARCHAR(100) NOT NULL,          -- nome do usuário
    email VARCHAR(100) NOT NULL UNIQUE,  -- email, não pode repetir
    senha VARCHAR(255) NOT NULL,         -- senha criptografada (bcrypt precisa de espaço)
    tipo ENUM('professor','aluno') NOT NULL,  -- define se é professor ou aluno
    turma VARCHAR(50),                   -- turma (se aluno)
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- data de criação
);
