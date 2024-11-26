let projectCounter = 1;

const addUserNewProjectListeners = () => {

    document.getElementById('add_project').addEventListener('click', function () {
        const name = document.getElementById('new_project_name').value.trim();
        const url = document.getElementById('new_project_url').value.trim();

        if (name && url) {
            addProject(name, url);
            clearInputFields();
        } else {
            alert('Please provide both project name and URL.');
        }
    });

};

const addProject = (name, url) => {
    console.log('add project'); 
    const newProjectId = projectCounter++;

    const container = document.createElement('div');
    //
    container.classList.add('grid', 'grid-cols-12', 'mb-4');
    container.dataset.projectId = newProjectId;

    const nameInput = createInput('text', `new_projects[${newProjectId}][name]`, name, 'Project Name', newProjectId);
    const urlInput = createInput('url', `new_projects[${newProjectId}][url]`, url, 'Project URL', newProjectId);
    const removeButton = createRemoveButton(container);

    container.appendChild(nameInput);
    container.appendChild(urlInput);
    container.appendChild(removeButton);

    const projectsSection = document.getElementById('new_projects');
    projectsSection.appendChild(container);
};

const createInput = (type, name, value, placeholder, projectId) => {
    const input = document.createElement('input');
    input.type = type;
    input.name = name;
    input.value = value;
    input.placeholder = placeholder;
    input.dataset.projectId = projectId;
    input.readOnly= true;
    if (type === 'url') {
        input.classList.add('lg:col-span-6','col-span-5','w-full', 'card', 'mb-2', 'mr-2');
    }else {
        input.classList.add('col-span-5','w-full', 'card', 'mb-2', 'mr-2');
    }

    
    return input;
};

const createRemoveButton = (container) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'text-white');
    button.innerText = 'Remove';
    button.onclick = function () {
        container.remove();
    };
    return button;
};

const clearInputFields = () => {
    document.getElementById('new_project_name').value = '';
    document.getElementById('new_project_url').value = '';
};

const removeProject = (button) => {
    button.parentElement.remove();
};

const addRemoveButtonEventListeners = () => {
    const removeButtons = document.querySelectorAll('#projects button');
    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            console.log('remove button clicked');
            removeProject(button);
        });
    });
};

addRemoveButtonEventListeners();
addUserNewProjectListeners();