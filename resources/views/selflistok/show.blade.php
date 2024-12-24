<h4><b>Self Tourist</b></h4>
<div class="tabs">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="employee-info-tab" data-bs-toggle="tab" href="#employee-info" role="tab"
                aria-controls="employee-info" aria-selected="true">Детальная информация</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="cleaning-history-tab" data-bs-toggle="tab" href="#cleaning-history" role="tab"
                aria-controls="cleaning-history" aria-selected="false">Информация о детях </a>
        </li>
    </ul>

    <div class="tab-content mt-3" id="myTabContent">
        <!-- Employee Info Tab -->
        <div class="tab-pane fade show active" id="employee-info" role="tabpanel" aria-labelledby="employee-info-tab">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>НАЗВАНИЕ ГОСТИНИЦЫ</th>
                    <td class="text-danger fw-bold">name</td>
                </tr>
                <tr>
                    <th>Ф.И.О. СОТРУДНИКА</th>
                    <td class="text-danger fw-bold">name</td>
                </tr>
                <tr>
                    <th>НАНЯТ НА РАБОТУ</th>
                    <td>2020.20.10</td>
                </tr>
                <tr>
                    <th>ДАТА УВОЛЬНЕНИЯ</th>
                    <td>2022.10.10</td>
                </tr>
                <tr>
                    <th>СТАТУС</th>
                    <td class="fw-bold">
                        <span class="bg-success p-2 rounded">Active</span>
                        {{-- <span class="bg-danger p-2 rounded">No Active</span> --}}
                    </td>
                </tr>
                <tr>
                    <th>ЗАПИСЬ СОЗДАЛ</th>
                    <td>name</td>
                </tr>
                <tr>
                    <th>ЗАПИСЬ СОЗДАНА</th>
                    <td>2020.10.10</td>
                </tr>
                <tr>
                    <th>ЗАПИСЬ ИЗМЕНЕНА</th>
                    <td>2022.10.12</td>
                </tr>
            </table>
        </div>

        <!-- Cleaning History Tab (Content can be added later) -->
        <div class="tab-pane fade" id="cleaning-history" role="tabpanel" aria-labelledby="cleaning-history-tab">
            <p>Информация о детях </p>
        </div>
    </div>
</div>
