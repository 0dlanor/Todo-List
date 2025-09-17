<?php 

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'db/connect.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Minhas Tarefas</title>
<style>
  body { font-family: Arial, sans-serif; margin: 0; padding: 0; }

  /* Nav */
  nav { background-color: #333; color: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
  nav .user { font-weight: bold; }
  nav button { margin-left: 10px; padding: 5px 10px; cursor: pointer; }

  main { padding: 20px; }
  .tasks-container { display: flex; flex-direction: column; gap: 10px; }

  /* Tarefa */
  .task { border: 1px solid #ccc; padding: 10px; border-radius: 5px; }

  /* Modal */
  .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
           background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
  .modal-content { background-color: #fff; padding: 20px; border-radius: 5px; width: 300px; }
  .modal-content input, .modal-content button { width: 100%; margin: 5px 0; padding: 8px; }
</style>
</head>
<body>

<nav>
  <div class="user">Olá, <?= htmlspecialchars($_SESSION['username']) ?></div>
  <div class="actions">
    <button id="add-task-btn">Adicionar Tarefa</button>
    <button id="logout-btn"><a href="logout.php">Sair</a></button>
  </div>
</nav>

<main>
  <div class="tasks-container">
    <!-- Aqui as tasks vão ser inseridas via PHP/JS -->
    
    

  </div>
</main>

<!-- Modal -->
<div class="modal" id="task-modal">
  <div class="modal-content">
    <h3>Adicionar Tarefa</h3>
    <form method="POST" action="">
      <input type="text" name="title" placeholder="Título da tarefa" required>
      <input type="date" name="due_date" placeholder="Data de conclusão (opcional)">
      <button type="submit">Adicionar</button>
      <button type="button" id="cancel-btn">Cancelar</button>
    </form>
  </div>
</div>

<script>

    // ----- Gerenciar modal -----

    const modal = document.getElementById('task-modal');
    const addBtn = document.getElementById('add-task-btn');
    const cancelBtn = document.getElementById('cancel-btn');

    // Abrir modal
    addBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
    });

    // Fechar modal
    cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    });

    // Fechar modal ao clicar fora do conteúdo
    window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
    });


    // ----- Gerenciar tarefas via AJAX -----

    // Função para carregar tarefas via AJAX

    async function loadTasks() {
        const tasksContainer = document.querySelector('.tasks-container')
        tasksContainer.innerHTML = '' // Limpa o container antes de carregar

        try {
            const response = await fetch('tasks_api.php')
            const tasks = await response.json()

            tasks.forEach(task => {
                const div = document.createElement('div');
                div.className = 'task';
                div.innerHTML = `
                    <h3>${task.title}</h3>
                    <p>Status: ${task.status}</p>
                    <p>Criada em: ${task.created_at}</p>
                    ${task.due_date ? `<p>Prazo: ${task.due_date}</p>` : ''}
                    <button onclick="updateStatus(${task.id}, 1)">Concluída</button>
                    <button onclick="updateStatus(${task.id}, 2)">Em andamento</button>
                    <button onclick="deleteTask(${task.id})">Excluir</button>
                `;
                container.appendChild(div);
            });

        } catch (error) {
            console.error('Erro ao carregar tarefas:', error);

        }
    }

    loadTasks()

</script>

</body>
</html>
