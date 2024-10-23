<x-app-layout>
    @section('title', 'Dodaj zadanie dla czasu ' . $section->title)

    <div class="container mt-3">
        <h1 class="main-text">Dodaj zadanie dla czasu {{ $section->title }}</h1>

        <form action="{{ route('tasks.store', $section->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="label-color" for="task_type">Wybierz typ zadania</label>
                <select id="task_type" name="task_type" class="form-element" required>
                    <option class="element-background gray-border label-color" value="" disabled selected>Wybierz
                        z opcji</option>
                    <option class="element-background gray-border label-color" value="1">Zadanie 1: Wybierz
                        poprawną formę z opcji</option>
                    <option class="element-background gray-border label-color" value="2">Zadanie 2: Uzupełnij
                        brakującą część zdania</option>
                </select>
            </div>

            <div id="task1_help" class="form-text" style="display: none;">
                <p class="main-text">Dla tego zadania aby utworzyć część do wyboru opcji, dany fragment tekstu należy umieścić miedzy
                    symbolami ' * '.</p>
                <p class="main-text bold">Poprawną odpowiedź wskazuje się poprzez wpisanie ' CA:' tuż przed poprawną odpowiedzią.</p>
                <p class="main-text">Do rozdzielenia opcji służy symbol ' / '. Nie dawaj spacji ani przed ani po symbolu '/'</p>
                <p class="main-text">Przykład: ___  She *CA:visits/visited* the museum last week.  ___ W tym przykładzie 'visits' to poprawna odpowiedź.</p>
            </div>

            <div id="task2_help" class="form-text" style="display: none;">
                <p class="main-text">Dla tego zadania aby utworzyć zadanie z miejscem do wpisania brakującej części zdania, dany fragment tekstu należy umieścić miedzy
                    symbolami ' * '.</p>
                <p class="main-text">W przpadku gdy zdanie ma więcej poprawnych odpowiedzi należy te odpowiedzi rozdzielić poprzez symbol '/'. Nie dawaj spacji ani przed ani po symbolu '/'</p>
                <p class="main-text">W nawiasach uwzględnij podstawową formę czasownika, osoba w pzypadku pytania lub 'not' w przypadku przeczenia. Rozdziel je za pomocą '/'
                <p class="main-text">Przykład: ___  She *did not visit/didn't visit* (not/visit) the museum last week.  ___</p>
            </div>

            <div class="form-group">
                <label class="label-color" for="text">Wpisz treść zadania</label>
                <textarea name="text" id="text" class="form-control">{{ old('text') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Dodaj zadanie</button>
        </form>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#text'))
            .catch(error => {
                console.error(error);
            });

        document.getElementById('task_type').addEventListener('change', function() {
            let selectedType = this.value;
            document.getElementById('task1_help').style.display = (selectedType == '1') ? 'block' : 'none';
            document.getElementById('task2_help').style.display = (selectedType == '2') ? 'block' : 'none';
        });
    </script>
</x-app-layout>
