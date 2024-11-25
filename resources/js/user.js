let projectCounter = 1; 

document.getElementById('add_project').addEventListener('click', function () {
    const name = document.getElementById('new_project_name').value.trim();
    const url = document.getElementById('new_project_url').value.trim();

    if (name && url) {
        const newProjectId = projectCounter++;

        const container = document.createElement('div');
        container.classList.add('flex', 'items-center', 'mb-4');
        container.dataset.projectId = newProjectId;

        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.name = `new_projects[${newProjectId}][name]`;
        nameInput.value = name;
        nameInput.placeholder = 'Project Name';
        nameInput.dataset.projectId = newProjectId; 
        nameInput.classList.add('w-full', 'card', 'mb-2', 'mr-2');

        const urlInput = document.createElement('input');
        urlInput.type = 'url';
        urlInput.name = `new_projects[${newProjectId}][url]`;
        urlInput.value = url;
        urlInput.placeholder = 'Project URL';
        urlInput.dataset.projectId = newProjectId;
        urlInput.classList.add('w-full', 'card', 'mb-2', 'mr-2');

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'text-white');
        removeButton.innerText = 'Remove';
        removeButton.onclick = function () {
            container.remove();
        };

        container.appendChild(nameInput);
        container.appendChild(urlInput);
        container.appendChild(removeButton);

        const projectsSection = document.getElementById('new_projects');
        projectsSection.appendChild(container);

        document.getElementById('new_project_name').value = '';
        document.getElementById('new_project_url').value = '';
    } else {
        alert('Please provide both project name and URL.');
    }
});
const removeProject =(button)  =>{
    button.parentElement.remove();
}

const addRemoveButtonEventListeners = () => {
    const removeButtons = document.querySelectorAll('#projects button');
    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            console.log('remove button clicked');
            removeProject(button);
        });
    });
}
addRemoveButtonEventListeners();



