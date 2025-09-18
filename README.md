# To-Do List com Login + CRUD

## Descrição
Este é um projeto de lista de tarefas (To-Do List) com autenticação de usuários e funcionalidades completas de **CRUD** (Create, Read, Update, Delete).  
O objetivo é treinar **PHP, MySQL e front-end básico** enquanto se constrói uma aplicação útil para o dia a dia.

---

## Funcionalidades
- Cadastro de usuários com **senha criptografada**.  
- Login de usuários com verificação de credenciais.  
- Logout seguro.  
- Criação, edição, conclusão e exclusão de tarefas.  
- Visualização de tarefas filtradas por usuário.  
- Status das tarefas: Pendente, Em andamento, Concluída.  
- Possibilidade de adicionar **prazo e prioridade** (opcional).  

---

## Estrutura do Projeto
```
/todo-list
├── index.php          # Login
├── register.php       # Cadastro
├── tasks.php          # Lista de tarefas
├── add_task.php       # Criar tarefa
├── update_task.php    # Editar tarefa
├── delete_task.php    # Excluir tarefa
├── logout.php         # Encerrar sessão
├── /db
│   └── connect.php    # Conexão com o banco de dados
├── /assets
│   ├── css            # Arquivos CSS
│   └── js             # Arquivos JavaScript
└── README.md          # Este arquivo
```

---

## Banco de Dados
Banco utilizado: **MySQL** (via XAMPP).  
Tabelas:

### users
- `id` INT AUTO_INCREMENT PRIMARY KEY  
- `username` VARCHAR(50) UNIQUE NOT NULL  
- `email` VARCHAR(100) UNIQUE NOT NULL  
- `password` VARCHAR(255) NOT NULL  
- `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP  

### tasks
- `id` INT AUTO_INCREMENT PRIMARY KEY  
- `user_id` INT NOT NULL (foreign key → users.id, ON DELETE CASCADE)  
- `title` VARCHAR(100) NOT NULL  
- `status` INT DEFAULT 0 (0 = pendente, 1 = em andamento, 2 = concluída)  
- `due_date` DATE NULL  
- `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
- `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

---

## Tecnologias Utilizadas
- PHP 8+  
- MySQL  
- HTML5 / CSS3  
- JavaScript básico  
- XAMPP (ou Laragon/Wamp) para ambiente local  

---

## Como Rodar o Projeto
1. Copie a pasta `todo-list` para o diretório `htdocs` do XAMPP.  
2. Abra o **XAMPP Control Panel** e inicie **Apache** e **MySQL**.  
3. Crie o banco de dados `todo_list` no phpMyAdmin e configure as tabelas (`users` e `tasks`).  
4. Configure a conexão no arquivo `db/connect.php` com seu usuário e senha do MySQL.  
5. Abra o navegador e acesse:  
```
http://localhost/todo-list/index.php
```
6. Comece testando cadastro, login e CRUD de tarefas.  

---

## Observações
- As senhas são armazenadas de forma segura com `password_hash`.  
- Status e datas das tarefas podem ser expandidos conforme necessidade.  
- Futuras melhorias podem incluir categorias, filtros, AJAX para atualizar tarefas sem recarregar a página, e responsividade para dispositivos móveis.
