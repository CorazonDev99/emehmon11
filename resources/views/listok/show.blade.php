<table>
    <tr>
        <td>Регистрационный номер</td>
        <td>{{ $listok->regnum }}</td>
    </tr>
    <tr>
        <td>ФИО</td>
        <td>{{ $listok->surname }} {{ $listok->firstname }} {{ $listok->lastname }}</td>
    </tr>
    <tr>
        <td>Дата рождения</td>
        <td>{{ $listok->datebirth }}</td>
    </tr>
    <tr>
        <td>Страна</td>
        <td>{{ $citizens->where('id', $listok->id_country)->first()->sp_name04 }}</td>
    </tr>
    <tr>
        <td>Гражданство</td>
        <td>{{ $citizens->where('id', $listok->id_citizen)->first()->sp_name04 }}</td>
    </tr>
    <tr>
        <td>Страна прибытия</td>
        <td>{{ $citizens->where('id', $listok->id_countryfrom)->first()->sp_name04 }}</td>
    </tr>
    <tr>
        <td>Адрес прописки</td>
        <td>{{ $listok->propiska }}</td>
    </tr>
    <tr>
        <td>Пол</td>
        <td>{{ $listok->sex == "M" ? "Мужской" : "Женский" }}</td>
    </tr>
    <tr>
        <td>Дата прибытия</td>
        <td>{{ $listok->datevisiton }}</td>
    </tr>
    <tr>
        <td>Дата отъезда</td>
        <td>{{ $listok->id_visittype }}</td>
    </tr>
    <tr>
        <td>Тип документа</td>
        <td>{{ $listok->id_passporttype }}</td>
    </tr>
    <tr>
        <td>Дата регистрации</td>
        <td>{{ $listok->datevisitoff }}</td>
    </tr>
    <tr>
        <td>Паспорт</td>
        <td>{{ $listok->passport }}</td>
    </tr>
    <tr>
        <td>Дата выдачи</td>
        <td>{{ $listok->datepassport }}</td>
    </tr>
    <tr>
        <td>Кем выдан</td>
        <td>{{ $listok->passportissuedby }}</td>
    </tr>{{--
    <tr>
        <td>Виза</td>
        <td>{{ $listok->id_visa }}</td>
    </tr>
    <tr>
        <td>Номер визы</td>
        <td>{{ $listok->visanumber }}</td>
    </tr>
    <tr>
        <td>Дата начала визы</td>
        <td>{{ $listok->datevisaon }}</td>
    </tr>
    <tr>
        <td>Дата окончания визы</td>
        <td>{{ $listok->datevisaoff }}</td>
    </tr>
    <tr>
        <td>Кем выдана виза</td>
        <td>{{ $listok->visaissuedby }}</td>
    </tr>
    <tr>
        <td>Номер КПП</td>
        <td>{{ $listok->kppnumber }}</td>
    </tr>
    <tr>
        <td>Дата КПП</td>
        <td>{{ $listok->datekpp }}</td>
    </tr>
    <tr>
        <td>Кем выдан КПП</td>
        <td>{{ $listok->id_guest }}</td>
    </tr>
    <tr>
        <td>Сумма</td>
        <td>{{ $listok->amount }}</td>
    </tr>
    <tr>
        <td>Создал</td>
        <td>{{ $listok->entry_by }}</td>
    </tr>--}}
    <tr>
        <td>Дата создания</td>
        <td>{{ $listok->created_at }}</td>
    </tr>
    <tr>
        <td>Изменил</td>
        <td>{{ $listok->updated_at }}</td>
    </tr>
    <tr>
        <td>Дни проживания</td>
        <td>{{ $listok->wdays }}</td>
    </tr>
    <tr>
        <td>Дни проживания</td>
        <td>{{ $listok->lived_days }}</td>
    </tr>
    <tr>
        <td>Выписал</td>
        <td>{{ $listok->out_by }}</td>
    </tr>
    <tr>
        <td>Дата выписки</td>
        <td>{{ $listok->payed }}</td>
    </tr>
    <tr>
        <td>Регион</td>
        <td>{{ \DB::table('regions')->where('id_sgb', $listok->id_region)->first()->name }}</td>
    </tr>
    <tr>
        <td>ПИНФЛ</td>
        <td>{{ $listok->pinfl }}</td>
    </tr>
</table>
