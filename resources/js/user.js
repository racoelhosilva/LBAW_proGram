import { sendDelete, sendPost, sendToastMessage } from "./utils";

let projectCounter = 1;

const addUserNewProjectListeners = () => {
    const addProjectButton = document.getElementById('add-project');
    const projectsSection = document.getElementById('projects');
    const newProjectName = document.getElementById('new-project-name');
    const newProjectUrl = document.getElementById('new-project-url');

    if (!addProjectButton || !projectsSection || !newProjectName || !newProjectUrl) {
        return;
    }
    
    addProjectButton.addEventListener('click', function () {
        const name = newProjectName.value.trim();
        const url = newProjectUrl.value.trim();

        if (name && url) {
            addProject(name, url, projectsSection);
            clearInputFields(newProjectName, newProjectUrl);
        } else {
            alert('Please provide both project name and URL.');
        }
    });

};

const addProject = (name, url, projectsSection) => {
    const newProjectId = projectCounter++;

    const container = document.createElement('div');
    
    container.classList.add('grid', 'grid-cols-12', 'gap-2');
    container.dataset.projectId = newProjectId;

    const nameInput = createInput('text', `top_projects[${newProjectId}][name]`, name, 'Project Name', newProjectId);
    const urlInput = createInput('url', `top_projects[${newProjectId}][url]`, url, 'Project URL', newProjectId);
    const removeButton = createRemoveButton(container);

    container.appendChild(nameInput);
    container.appendChild(urlInput);
    container.appendChild(removeButton);

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
        input.classList.add('lg:col-span-6','col-span-5','w-full', 'card', 'my-2');
    }else {
        input.classList.add('col-span-5','w-full', 'card', 'my-2');
    }

    return input;
};

const createRemoveButton = (container) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger');
    button.innerText = 'Remove';
    button.addEventListener('click', () => container.remove());

    return button;
};

const clearInputFields = (newProjectName, newProjectUrl) => {
    newProjectName.value = '';
    newProjectUrl.value = '';
};

const removeProject = (button) => {
    button.parentElement.remove();
};

const addRemoveButtonEventListeners = () => {
    const removeButtons = document.querySelectorAll('#projects button');

    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            removeProject(button);
        });
    });
};

addRemoveButtonEventListeners();
addUserNewProjectListeners();

/** User Profile Follow Button **/

const handleFollowProfile = (button, userId) => {
    button.disabled = true;
    if (button.classList.contains('following')) {
        sendDelete(`/api/user/${userId}/follow`)
            .then((_) => {
                button.classList.remove('following');
                button.classList.add("unfollowing");
                button.disabled = false;
                sendToastMessage('User unfollowed successfully.', 'success');
            })
            .catch((error) => {
                button.disabled = false;
                sendToastMessage('An error occurred while unfollowing.', 'error');
            });
    }
    else if (button.classList.contains('pending')) {
        sendDelete(`/api/user/${userId}/follow`)
            .then((_) => {
                button.classList.remove('pending', 'secondary-btn');
                button.classList.add('unfollowing', 'primary-btn');
                button.disabled = false;
                sendToastMessage('Request canceled successfully.', 'success');
            })
            .catch((error) => {
                button.disabled = false;
                sendToastMessage('An error occurred while removing request.', 'error');
            });
    }
    else if (button.classList.contains('unfollowing')) {
        sendPost(`/api/user/${userId}/follow`)
            .then((data) => {
                button.disabled = false;
                if (data.action === 'follow') {
                    button.classList.remove('unfollowing');
                    button.classList.add("following");
                    button.disabled = false;
                    sendToastMessage(data.message, 'success');
                } 
                else if (data.action === 'request') {
                    button.classList.remove('unfollowing', 'primary-btn');
                    button.classList.add('pending', 'secondary-btn');
                    button.disabled = false;
                    sendToastMessage(data.message, 'success');
                }
                else if (data.action === 'none') {
                    button.disabled = false;
                    sendToastMessage(data.message, 'error');
                }
            })
            .catch((error) => {
                button.disabled = false;
                sendToastMessage('An error occurred while following.', 'error');
            });
    }
};

const addUserProfileFollowListeners = () => {
	const followProfileButton = document.querySelectorAll(".follow-profile-button");

    followProfileButton.forEach((button) => {
        const userId = button.dataset.userId;

        button.addEventListener("click", () => handleFollowProfile(button, userId));
    });
};

addUserProfileFollowListeners();
