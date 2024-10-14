import { showMessage } from './messageHandler.js';

document.addEventListener('DOMContentLoaded', function () {
    const sectionButtons = document.querySelectorAll('.section-btn');
    const sectionContent = document.getElementById('sectionContent');
    const sectionTitle = document.getElementById('sectionTitle');
    const rulesContainer = document.getElementById('rulesContainer');
    const returnBtn = document.getElementById('returnBtn');
    const addSectionBtn = document.getElementById('addSectionBtn');
    const addSectionModal = new bootstrap.Modal(document.getElementById('addSectionModal'));
    const saveSectionBtn = document.getElementById('saveSectionBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeBtn = document.getElementById('closeBtn');
    const newSectionTitle = document.getElementById('newSectionTitle');

    sectionButtons.forEach(button => {
        button.addEventListener('click', function () {
            const sectionId = this.getAttribute('data-section-id');
            fetch(`/sentence-rules/${sectionId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    sectionTitle.textContent = data.section.title;
                    rulesContainer.innerHTML = '';
                    data.rules.forEach(rule => {
                        const ruleDiv = document.createElement('div');
                        ruleDiv.classList.add('mb-3', 'rule');
                        ruleDiv.innerHTML = `<p>${rule.rule_text}</p>`;
                        rulesContainer.appendChild(ruleDiv);
                    });

                    sectionContent.style.display = 'block';
                    document.getElementById('sectionButtons').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error fetching section rules:', error);
                });
        });
    });

    returnBtn.addEventListener('click', function () {
        sectionContent.style.display = 'none';
        document.getElementById('sectionButtons').style.display = 'block';
    });

    addSectionBtn.addEventListener('click', function () {
        addSectionModal.show();
    });

    saveSectionBtn.addEventListener('click', function () {
        const sectionTitle = newSectionTitle.value.trim();

        if (sectionTitle) {
            fetch('/sentence-rules/store-section', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ title: sectionTitle })
            })
                .then(response => response.json())
                .then(section => {
                    const newButton = document.createElement('button');
                    newButton.classList.add('btn', 'btn-primary', 'section-btn');
                    newButton.setAttribute('data-section-id', section.id);
                    newButton.textContent = section.title;
                    document.getElementById('sectionButtons').insertBefore(newButton, addSectionBtn);

                    addSectionModal.hide();
                    newSectionTitle.value = '';
                });
        } else {
            alert("Please enter a section title.");
        }
    });

    cancelBtn.addEventListener('click', function () {
        addSectionModal.hide();
        newSectionTitle.value = '';
    });
    closeBtn.addEventListener('click', function () {
        addSectionModal.hide();
        newSectionTitle.value = '';
    });

});
