{{--Шаблон вывода всех параметров--}}
<tr>
    <td>{{$setting->name}}</td>
    <td>{{$setting->val}}</td>
    <td>
        <div class="btn-group" role="group" aria-label="Функционал программы">
            <a href="{{route('telegram-setting.edit', $setting->id)}}" type="button" class="btn btn-sm btn-warning">Редактировать</a>
            {{-- Дата это универсальный элемент хранения параметров для передачи, мы бкдем хранить в нем id элемента --}}
            <button type="button"
                    class="btn btn-sm btn-danger destroy"
                    data-bs-toggle="modal"
                    data-bs-target="#getOpenDestroyModalWindow"
                    data-id = "{{$setting->id}}">Удалить</button>
        </div>
    </td>
</tr>
