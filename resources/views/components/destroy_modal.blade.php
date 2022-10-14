{{-- Наше модальное окно, которое будет обрабатывать информацию об удалении через JS изменения и подставляя данные --}}
<div class="modal fade" id="getOpenDestroyModalWindow" tabindex="-1" aria-labelledby="getOpenDestroyModalWindow_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="getOpenDestroyModalWindow_label">Окно подтверждения удаления</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="getOpenDestroyModalWindow_context">
                ...
            </div>
            <div class="modal-footer">
                {{-- Сделаем с вами функционал подставки адреса автоматически через JS --}}
                <form method="POST" id="getOpenDestroyModalWindow_operation">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Добавляем собственный скрипт --}}
@pushonce('script')
    <script>
        // Получаем идентификатор адреса ресурса по index
        // Так как мы не знаем id, который пользователь изначально передаст
        // Чтобы построить ссылку с возможностью динамически в дальнейшем меняя ссылку в web.php
        // используем главный роут, который построит нам как минимум адресс: http://domain/telegram-setting
        // А в конце мы уже поставим идентификатор для удаления
        let url = '{{route($nameRoute)}}'

        // Получаем все элементы кнопок удаления
        let allQuerySelector = document.querySelectorAll('.destroy');

        // Перебираем их и накладываем на них слышателя на нажатие
        allQuerySelector.forEach((element) =>{
            element.addEventListener('click', (el) =>{
                // Получаем идентификатор из data-id
                let id = element.dataset.id;

                // Меняем текст в div с getOpenDestroyModalWindow_operation
                let elementModalContext = document.querySelector('#getOpenDestroyModalWindow_context');
                elementModalContext.innerText = 'Вы точно хотите удалить элемент с идентификатором ' + id;

                // Добавляем href  в форму модального окна адресс ссылки по формату: url + / + id
                // Получится что-то вроде: http://domain/telegram-setting/9
                let elementModalForm = document.querySelector('#getOpenDestroyModalWindow_operation');
                elementModalForm.setAttribute('action', url + '/' + id)
            })
        })
    </script>
@endpushonce
