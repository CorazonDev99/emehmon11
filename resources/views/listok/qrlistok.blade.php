<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document of Customer</title>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href="/assets/css/bootstrap.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
</head>

<body>
<div class="container pb-4">
    <img style="height: 100px;" src="{{ asset('assets/images/logo-sm.png') }}" alt="Logo">
    <div class="card container p-4">
        <h4><b>№{{ $data->regNum }} &emsp;{{ $data->surname }} &emsp;{{ $data->firstname }} &emsp;{{ $data->lastname }}</b></h4>
        <div class="tabs mt-4">
            <ul class="nav nav-tabs" id="myTab" role="tablist">

                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="employee-info-tab" data-bs-toggle="tab" href="#employee-info"
                       role="tab" aria-controls="employee-info" aria-selected="true">Information/Информация</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="details-tab" data-bs-toggle="tab" href="#details" role="tab"
                       aria-controls="details" aria-selected="false">Details/Дополнительная
                        информация </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="cleaning-history-tab" data-bs-toggle="tab" href="#cleaning-history"
                       role="tab" aria-controls="cleaning-history" aria-selected="false">Children/Детях </a>
                </li>
            </ul>
            <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="employee-info" role="tabpanel"
                     aria-labelledby="employee-info-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="dataTable compact row-border hover align-middle">
                                <tr>
                                    <th>Рег. №</th>
                                    <td>{{ $data->regNum ? $data->regNum : 'Нет' }}</td>
                                </tr>
                                <tr>
                                    <th>Фамилия, Имя, Отчество:</th>
                                    <td>
                                        {{ $data->surname ? $data->surname : 'Нет' }}
                                        &emsp;
                                        {{ $data->firstname ? $data->firstname : 'Нет' }}
                                        &emsp;
                                        {{ $data->lastname ? $data->lastname : 'Нет' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Дата рождения</th>
                                    <td>
                                        {{ $data->datebirth ? \Carbon\Carbon::parse($data->datebirth)->format('d.m.Y') : '--/--/----' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Страна рождения</th>
                                    <td>{{ $data->born_country ? $data->born_country : 'Нет' }}</td>
                                </tr>
                                <tr>
                                    <th>Гражданство</th>
                                    <td>{{ $data->ctzn ? $data->ctzn : 'Нет' }}</td>
                                </tr>
                                <tr>
                                    <th>Откуда</th>
                                    <td>{{ $data->from_country ? $data->from_country : 'Нет' }}</td>
                                </tr>
                                <tr>
                                    <th>Прибыл на (дней):</th>
                                    <td>{{ $data->wdays ? $data->wdays : 'Нет' }}</td>
                                </tr>
                                <tr>
                                    <th>Пол</th>
                                    <td>
                                        {{ $data->sex == 1 ? 'Муж' : ($data->sex == 2 ? 'Жен' : 'Not indicated') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>ПРИБЫЛ</th>
                                    <td>
                                        {{ $data->dateVisitOn ? \Carbon\Carbon::parse($data->dateVisitOn)->format('d.m.Y H:i') : 'Нет' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Место прописки:</th>
                                    <td>{{ $data->region ? $data->region : 'Нет' }}</td>
                                </tr>
                                <tr>
                                    <th>Визит</th>
                                    <td>{{ $data->visit_type ? $data->visit_type : 'Нет' }}</td>
                                </tr>
                                <tr>
                                    <th>Тип гостя:</th>
                                    <td>{{ $data->guest_type ? $data->guest_type : 'Нет' }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        Сохранено
                                    </th>
                                    <td>
                                        {{ $data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('d.m.Y H:i') : 'Нет' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Обновлено</th>
                                    <td>
                                        {{ $data->updated_at ? \Carbon\Carbon::parse($data->updated_at)->format('d.m.Y H:i') : '--/--/----' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Гостиница</th>
                                    <td>
                                        {{ $data->hotel_name ? $data->hotel_name : 'Нет' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th> Зарегистрировал</th>
                                    <td>
                                        {{ $data->administrator ? $data->administrator : 'Нет' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="qrcode img-fluid" id="qrcode"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="dataTable compact row-border hover align-middle">
                                <tr>
                                    <th>Тип документа</th>
                                    <td>
                                        {{ $data->passport_type ? $data->passport_type : 'Нет' }} |
                                        {{ $data->passportSerial ? $data->passportSerial : 'Нет' }}
                                        {{ $data->passportNumber ? $data->passportNumber : 'Нет' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Дата паспорта / кем</th>
                                    <td>
                                        {{ $data->datePassport ? \Carbon\Carbon::parse($data->datePassport)->format('d.m.Y') : 'Нет' }}

                                        {{ $data->PassportIssuedBy ? $data->PassportIssuedBy : 'Нет' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Виза № (Кем выдана)</th>
                                    <td>
                                        {{ $data->visaNumber ? $data->visaNumber : 'Нет' }} |
                                        {{ $data->visaIssuedBy ? $data->visaIssuedBy : 'Нет' }}
                                        Виза с:
                                        {{ $data->dateVisaOn ? \Carbon\Carbon::parse($data->dateVisaOn)->format('d.m.Y') : '--/--/----' }}
                                        |
                                        Виза до:
                                        {{ $data->dateVisaOff ? \Carbon\Carbon::parse($data->dateVisaOff)->format('d.m.Y') : '--/--/----' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>КПП и дата</th>
                                    <td>№
                                        {{ $data->kppNumber ? $data->kppNumber : 'Нет' }}
                                        Дата:
                                        {{ $data->dateKPP ? \Carbon\Carbon::parse($data->dateKPP)->format('d.m.Y') : 'Нет' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="cleaning-history" role="tabpanel"
                     aria-labelledby="cleaning-history-tab">
                    <table class="dataTable row-border compact hover align-middle">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>ФИО</th>
                            <th>Дата рождения</th>
                            <th>Пол</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($datachildren->isEmpty())
                            <tr>
                                <td colspan="4" style="text-align: center"><strong> Данные
                                        отсутствуют</strong></td>
                            </tr>
                        @else
                            @foreach ($datachildren as $child)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $child->name }}</td>
                                    <td>
                                        {{ $child->dateBirth ? \Carbon\Carbon::parse($child->dateBirth)->format('d.m.Y') : '--/--/----' }}
                                    </td>
                                    <td>
                                        {{ $child->gender == 'M' ? 'Мужчина' : ($child->gender == 'W' ? 'Женщина' : 'Неизвестно') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<style>
    a,
    table {
        font-size: 18px
    }
</style>

