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
    <form id="add-task-form">
      <input type="text" name="title" placeholder="Título da tarefa" required>
      <input type="date" name="due_date" placeholder="Data de conclusão (opcional)">
      <button type="submit">Adicionar</button>
      <button type="button" id="cancel-btn">Cancelar</button>
    </form>
  </div>
</div>

<script>

    // ----- Gerenciar modal -----

    const addTaskForm = document.getElementById('add-task-form');
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

    // Adicionar tarefa

    addTaskForm.addEventListener('submit', addTask)


    // ----- Gerenciar tarefas via AJAX -----

    // Função para carregar tarefas via AJAX

    async function loadTasks() {
        const tasksContainer = document.querySelector('.tasks-container')
        tasksContainer.innerHTML = '' // Limpa o container antes de carregar

        try {
            const response = await fetch('tasks_api.php')
            const tasks = await response.json()

            tasks.forEach(task => {

                // Definir o texto baseado no status da tarefa
                let statusText = ''

                switch (task.status) {

                  case "0":
                      statusText = "Pendente"
                      break;

                  case "1":
                      statusText = "Em andamento"
                      break;

                  case "2":
                      statusText = "Concluída"
                      break;
                }

                //Criação da div da task
                const div = document.createElement('div');
                div.className = 'task';
                div.dataset.task_id = task.id

                // Criação das tags da task
                div.innerHTML = `
                    <h3>${task.title}</h3>
                    <p>Status: ${statusText}</p>
                    <p>Criada em: ${task.created_at}</p>
                    ${task.due_date ? `<p>Prazo: ${task.due_date}</p>` : ''}

                    <button onclick="updateTaskStatus(${div.dataset.task_id}, 2)">Concluída</button>
                    <button onclick="updateTaskStatus(${div.dataset.task_id}, 1)">Em andamento</button>
                    <button onclick="updateTaskStatus(${div.dataset.task_id}, 0)">Pendente</button>
                    <button onclick="deleteTask(${div.dataset.task_id})">Excluir</button>
                `;
                tasksContainer.appendChild(div);
            });

        } catch (error) {
            console.error('Erro ao carregar tarefas:', error);

        }
    }

    loadTasks()


    // Função para adicionar tarefas via AJAX

    async function addTask(event) {

      event.preventDefault()

      const formData = new FormData(addTaskForm)

      try {
          const response = await fetch('add_task.php', {
            method: 'POST',
            body: formData
          })

          const result = await response.json()

          if (result.success) {
            // Fechar modal
            modal.style.display = 'none'
            addTaskForm.reset()

            loadTasks()

          } else {
            console.error("Erro: ", result.message)

          }

      } catch (error) {
        console.error("Erro na requisição: ", error)
      }

    }


    // Função para deletar tarefas via AJAX

    async function deleteTask(task_id) {

      try {

        const formData = new FormData()
        formData.append('task_id',task_id)

        const response = await fetch('delete_task.php', {
          method: 'POST',
          body: formData
        })

        const result = await response.json()

        if (result.success) {
          loadTasks()
          alert("Tarefa excluída.")

        } else {
          console.error("Erro: ", result.message)
        }

      } catch (error) {
        console.error("Erro na requisição: ", error)
      }

    }


    // Função para modificar tarefas via AJAX

    async function updateTaskStatus(task_id, state) {
      
      try {

        const formData = new FormData()
        formData.append('state', state)
        formData.append('task_id', task_id)

        const response = await fetch('update_task.php', {
          method: 'POST',
          body: formData
        })

        const result = await response.json()

        if (result.success) {
          loadTasks()

        } else {
          console.error("Erro: ", result.message)
        }

      } catch (error) {
        console.error("Erro na requisição: ", error)
      }

    }

</script>

</body>
</html>
