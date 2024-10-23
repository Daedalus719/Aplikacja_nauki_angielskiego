<x-app-layout>
    @section('title', 'Tasks for ' . $section->title)

    <div class="container">

        <a href="{{ route('sections.index') }}" class="btn btn-secondary mt-3">Wróć do sekcji z czasami</a>

        @if (Auth::check() && (Auth::user()->usertype === 'Admin' || Auth::user()->usertype === 'Moderator'))
            <a href="{{ route('tasks.create', $section->id) }}" class="btn btn-primary">Dodaj zadanie</a>
            <a href="{{ route('tasks.show_all', $section->id) }}" class="btn btn-secondary">Wyświetl wszystkie zadania</a>
        @endif

        <div id="task-choice" style="margin-top: 20px;">
            <h3 class="main-text">Wybierz typ zadania dla czasu: {{ $section->title }}</h3>
            <button class="btn btn-primary" onclick="loadTask(1)">Zadanie 1: Wybierz poprawną formę</button>
            <button class="btn btn-primary" onclick="loadTask(2)">Zadanie 2: Wpisz w lukę brakującą cześć
                zdania</button>
        </div>

        <div id="task-display" style="margin-top: 20px; display: none;">
            <h3 class="main-text">Zadanie:</h3>
            <div id="task-content" class="label-color "></div>

            <div id="result-message" class="mb-3 mt-2"></div>

            <button class="btn btn-success" id="check-task" onclick="checkTask()">Sprawdź odpowiedzi</button>
            <button class="btn btn-primary" onclick="loadTask(currentTaskType)">Wygeneruj nowe zadanie</button>
            <button class="btn btn-secondary" onclick="goBack()">Wróć do wyboru zadań</button>
        </div>

        <script>
            let currentTaskType = null;
            let currentTaskData = null;

            function shuffleArray(array) {
                for (let i = array.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [array[i], array[j]] = [array[j], array[i]];
                }
                return array;
            }

            function loadTask(taskType) {
                currentTaskType = taskType;
                fetch(`/section/{{ $section->id }}/tasks/random?type=` + taskType)
                    .then(response => response.json())
                    .then(data => {
                        currentTaskData = data;
                        displayTask(data);
                    })
                    .catch(error => console.error('Error fetching task:', error));
            }

            function displayTask(task) {
                document.getElementById('task-choice').style.display = 'none';
                document.getElementById('task-display').style.display = 'block';

                let taskContent = task.text;

                if (currentTaskType == 1) {
                    taskContent = taskContent.replace(/\*CA:(.*?)\/(.*?)\*/g, (match, correctOption, incorrectOption) => {
                        const options = shuffleArray([correctOption, incorrectOption]);

                        let selectDropdown = `<select class="task-dropdown">
                                                <option class="element-background gray-border label-color" value="" disabled selected>Wybierz jedną z opcji</option>
                                                <option class="element-background gray-border label-color" value="${options[0]}" data-correct="${correctOption === options[0]}">${options[0]}</option>
                                                <option class="element-background gray-border label-color" value="${options[1]}" data-correct="${correctOption === options[1]}">${options[1]}</option>
                                              </select>`;
                        return selectDropdown;
                    });
                } else if (currentTaskType == 2) {
                    taskContent = taskContent.replace(/\*(.*?)\*/g, (match, options) => {
                        return `<input type="text" class="task-input label-color element-background" data-options="${options}" placeholder="Wpisz odpowiedź">`;
                    });
                }

                document.getElementById('task-content').innerHTML = taskContent;
                document.getElementById('result-message').innerHTML = '';
            }

            function checkTask() {
                let resultMessage = '';
                let correct = true;

                document.querySelectorAll('.feedback').forEach(feedback => feedback.remove());

                if (currentTaskType == 1) {
                    let dropdowns = document.querySelectorAll('.task-dropdown');

                    dropdowns.forEach((dropdown, index) => {
                        let selectedValue = dropdown.value;
                        let correctAnswer = dropdown.querySelector('option[data-correct="true"]').value;

                        if (selectedValue === correctAnswer) {
                            dropdown.insertAdjacentHTML('afterend',
                                `<span class="feedback" style="color: green;">✔️</span>`);
                        } else {
                            correct = false;
                            dropdown.insertAdjacentHTML('afterend',
                                `<span class="feedback" style="color: red;">❌ Poprawna odpowiedź: ${correctAnswer}</span>`
                            );
                        }
                    });
                } else if (currentTaskType == 2) {
                    document.querySelectorAll('.task-input').forEach(input => {
                        let options = input.getAttribute('data-options').split('/');
                        if (options.includes(input.value.trim())) {
                            input.insertAdjacentHTML('afterend',
                                `<span class="feedback" style="color: green;">✔️</span>`);
                        } else {
                            correct = false;
                            input.insertAdjacentHTML('afterend',
                                `<span class="feedback" style="color: red;">❌ Poprawna odpowiedź: ${options.join(', ')}</span>`
                            );
                        }
                    });
                }

                resultMessage = correct ? '<div style="color: green;">Wszystkie odpowiedzi są poprawne!</div>' :
                    '<div style="color: red;">Część odpowiedzi jest niepoprawna. Spróbuj ponownie!</div>';

                document.getElementById('result-message').innerHTML = resultMessage;
            }


            function goBack() {
                document.getElementById('task-choice').style.display = 'block';
                document.getElementById('task-display').style.display = 'none';
                document.getElementById('task-content').innerHTML = '';
                document.getElementById('result-message').innerHTML = '';
            }
        </script>
    </div>
</x-app-layout>
