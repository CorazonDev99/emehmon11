<h1>Info</h1>
<div class="tabs">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="employee-info-tab" data-bs-toggle="tab" href="#employee-info" role="tab"
                aria-controls="employee-info" aria-selected="true">Информация о сотруднике</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="cleaning-history-tab" data-bs-toggle="tab" href="#cleaning-history" role="tab"
                aria-controls="cleaning-history" aria-selected="false">История уборок за последний месяц</a>
        </li>
    </ul>

    <div class="tab-content mt-3" id="myTabContent">
        <!-- Employee Info Tab -->
        <div class="tab-pane fade show active" id="employee-info" role="tabpanel" aria-labelledby="employee-info-tab">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>НАЗВАНИЕ ГОСТИНИЦЫ</th>
                    <td class="text-danger fw-bold">{{ $data->hotel_name }}</td>
                </tr>
                <tr>
                    <th>Ф.И.О. СОТРУДНИКА</th>
                    <td class="text-danger fw-bold">{{ $data->cleaner }}</td>
                </tr>
                <tr>
                    <th>НАНЯТ НА РАБОТУ</th>
                    <td>{{ $data->work_start }}</td>
                </tr>
                <tr>
                    <th>ДАТА УВОЛЬНЕНИЯ</th>
                    <td>{{ $data->work_stop }}</td>
                </tr>
                <tr>
                    <th>СТАТУС</th>
                    <td class="fw-bold">
                        @if ($data->active)
                            <span class="bg-success p-2 rounded">Active</span>
                        @else
                            <span class="bg-danger p-2 rounded">No Active</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>ЗАПИСЬ СОЗДАЛ</th>
                    <td>{{ $data->entry_by }}</td>
                </tr>
                <tr>
                    <th>ЗАПИСЬ СОЗДАНА</th>
                    <td>{{ $data->created_at }}</td>
                </tr>
                <tr>
                    <th>ЗАПИСЬ ИЗМЕНЕНА</th>
                    <td>{{ $data->updated_at }}</td>
                </tr>
            </table>
        </div>

        <!-- Cleaning History Tab (Content can be added later) -->
        <div class="tab-pane fade" id="cleaning-history" role="tabpanel" aria-labelledby="cleaning-history-tab">
            <p>История уборок за последний месяц.</p>
        </div>
    </div>
</div>
