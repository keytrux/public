class TodoList {
    constructor() {
        this.tasks = [];
        this.nextId = 1;
        this.load();
    }
    addTask(title) {
        const newTask = { id: this.nextId++, title, completed: false };
        this.tasks.push(newTask);
        this.save();
        this.list();
    }
    save() {
        localStorage.setItem('tasks', JSON.stringify(this.tasks));
    }
    load() {
        const storedTasks = localStorage.getItem("tasks");
        if (storedTasks) {
            this.tasks = JSON.parse(storedTasks);
            this.nextId = this.tasks.length > 0 ? Math.max(...this.tasks.map(task => task.id)) + 1 : 1;
        }
    }
    list() {
        let container = document.getElementById("div-list");
        if (!container) {
            console.error("Контейнер для списка задач не найден");
            return;
        }
        container.innerHTML = "";
        let localStorageTask = [];
        localStorageTask = JSON.parse(localStorage.getItem("tasks"));
        if (!localStorageTask) {
            return;
        }
        let elemUl = document.createElement("ul");
        elemUl.id = "list";
        localStorageTask.forEach((task) => {
            let elemLi = document.createElement("li");
            elemLi.textContent = `${task.title} [${task.completed ? '✓' : '✗'}]`;
            let buttonDone = document.createElement("button");
            buttonDone.textContent = 'Выполнить';
            buttonDone.id = `${task.id}`;
            buttonDone.className = "btn-done";
            buttonDone.onclick = () => {
                this.completeTask(task.id);
                this.save();
                this.list();
            };
            let buttonDelete = document.createElement("button");
            buttonDelete.textContent = 'Удалить';
            buttonDelete.id = `${task.id}`;
            buttonDelete.className = "btn-delete";
            buttonDelete.onclick = () => {
                this.deleteTask(task.id);
                this.save();
                this.list();
            };
            elemLi.appendChild(buttonDone);
            elemLi.appendChild(buttonDelete);
            elemUl.appendChild(elemLi);
            container.appendChild(elemUl);
        });
    }
    completeTask(id) {
        const task = this.tasks.find((task) => task.id === id);
        if (task) {
            task.completed = true;
        }
        else {
            console.log('Задача не найдена');
        }
    }
    deleteTask(id) {
        const taskIndex = this.tasks.findIndex((task) => task.id === id);
        if (taskIndex !== -1) {
            this.tasks.splice(taskIndex, 1);
        }
        else {
            console.log('Задача не найдена');
        }
    }
}
function resetTasks() {
    localStorage.removeItem("tasks");
    todoList.list();
    console.log('123');
}
const todoList = new TodoList();
document.addEventListener("DOMContentLoaded", () => {
    todoList.list();
    const add_task = document.querySelector('.add-task');
    add_task.addEventListener('submit', (event) => {
        event.preventDefault();
        console.log("Форма выполнена");
        const formData = new FormData(add_task);
        const title = formData.get('title');
        let inputTitle = document.getElementById("title");
        console.log("Заголовок задачи:", title);
        inputTitle.value = "";
        todoList.addTask(title);
    });
});
