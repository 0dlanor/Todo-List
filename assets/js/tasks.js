// ----- Gerenciar modal para adicionar tarefas -----

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


// ----- Gerenciar logout -----

const logoutBtn = document.getElementById('logout-btn')

logoutBtn.addEventListener('click', logout)

async function logout() {

    try {

        const response = await fetch('endpoints/logout.php', {
            method: 'POST',
        })

        const result = await response.json()

        result.success ? console.log("Saindo da conta") : console.error(result.message)

    } catch (error) {
        console.error("Erro na requisição: ", error)
    }

}


// ----- Gerenciar tarefas via AJAX -----

// Função para carregar tarefas via AJAX

async function loadTasks() {
    const tasksContainer = document.querySelector('.tasks-container')
    tasksContainer.innerHTML = '' // Limpa o container antes de carregar

    try {
        const response = await fetch('endpoints/tasks_api.php', {
            method: 'POST'
        })

        const result = await response.json()

        // Iterar sobre cada task retornada
        result.tasks.forEach(task => {

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
        const response = await fetch('endpoints/add_task.php', {
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
        formData.append('task_id', task_id)

        const response = await fetch('endpoints/delete_task.php', {
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

        const response = await fetch('endpoints/update_task.php', {
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