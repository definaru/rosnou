<div class="header-addon">
    <form class="form-inline" action="" method="get" id="filter">
        <div class="form-group" id="site_category">
            <label class="h3 small">Категория сайта</label>
            <select name="site_type"
                    class="form-control select-width250"
                    data-placeholder="Выберите">
                <option></option>


                <?php foreach ($siteTypes as $id => $type): ?>
                    <option value="<?=$id?>" <?=$id == $searchModel->siteType ? 'selected="selected"' : ''?>><?=$type?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" id="status">
            <label class="h3 small">
                Статус заявки
            </label><select name="fields_filter[status]" class="form-control select-width250"
                            data-placeholder="Выберите">
                <option></option>
                <option value="2427">на модерации</option>
                <option value="369">одобрена</option>
                <option value="371">оплачена</option>
                <option value="370">отклонена</option>
                <option value="372">принята (без оплаты)</option>
                <option value="3021">проходит самообследование</option>
                <option value="368">проходит экспертизу</option>
                <option value="373">сайт проверен</option>
                <option value="2924">самообследование завершено</option>
            </select>
        </div>
        <div class="form-group" id="status">
            <label class="h3 small">
                Модератор
            </label><select class="form-control select-width250" data-placeholder="Выберите"
                            name="fields_filter[expert_id]">
                <option></option>
                <option value="2394">expert2</option>
                <option value="2407">expert3</option>
                <option value="5119">cessia007</option>
                <option value="10830">experttest</option>
                <option value="11219">Victoria</option>
                <option value="5930">peter911</option>
                <option value="14081">vbifrerfyjd</option>
                <option value="14144">leruhin</option>
                <option value="2436">koralena</option>
                <option value="15383">Nerilka</option>
                <option value="17234">shtil</option>
                <option value="17237">kozhanov</option>
                <option value="5934">nickita</option>
                <option value="13568">aleksandriya</option>
                <option value="33252">makhova</option>
                <option value="17111">AT103</option>
                <option value="33532">rikalna</option>
                <option value="2393">expert</option>
            </select>
        </div>
        <div class="form-group" id="status">
            <label class="h3 small">
                Модератор
            </label><select class="form-control select-width250" data-placeholder="Выберите"
                            name="fields_filter[moderator_id]">
                <option></option>
                <option value="2392">moderator</option>
                <option value="7444">corliniene</option>
                <option value="6718">DeeHolden</option>
                <option value="10829">moderatortest</option>
                <option value="29055">polzza20</option>
                <option value="29341">grix</option>
            </select>
        </div>
        <div class="form-group" id="federal_district">
            <label class="h3 small">Федеральный округ</label><select name="fields_filter[federal_district]"
                                                                     class="form-control select-width190"
                                                                     data-placeholder="Выберите">
                <option></option>
                <option value="414">Дальневосточный</option>
                <option value="419">Крымский</option>
                <option value="417">Приволжский</option>
                <option value="413">Северо-Западный</option>
                <option value="418">Северо-Кавказский</option>
                <option value="415">Сибирский</option>
                <option value="416">Уральский</option>
                <option value="411">Центральный</option>
                <option value="412">Южный</option>
            </select>
        </div>
        <div class="form-group" id="subject_federation">
            <label class="h3 small">Субъект Федерации</label><select name="fields_filter[subject_federation]"
                                                                     class="form-control select-width250"
                                                                     data-placeholder="Выберите">
                <option></option>
                <option value="447">Алтайский край</option>
                <option value="456">Амурская область</option>
                <option value="457">Архангельская область</option>
                <option value="458">Астраханская область</option>
                <option value="459">Белгородская область</option>
                <option value="460">Брянская область</option>
                <option value="461">Владимирская область</option>
                <option value="462">Волгоградская область</option>
                <option value="463">Вологодская область</option>
                <option value="464">Воронежская область</option>
                <option value="502">Еврейская автономная область</option>
                <option value="448">Забайкальский край</option>
                <option value="465">Ивановская область</option>
                <option value="466">Иркутская область</option>
                <option value="431">Кабардино-Балкарская Республика</option>
                <option value="467">Калининградская область</option>
                <option value="468">Калужская область</option>
                <option value="449">Камчатский край</option>
                <option value="433">Карачаево-Черкесская Республика</option>
                <option value="469">Кемеровская область</option>
                <option value="470">Кировская область</option>
                <option value="471">Костромская область</option>
                <option value="450">Краснодарский край</option>
                <option value="451">Красноярский край</option>
                <option value="472">Курганская область</option>
                <option value="473">Курская область</option>
                <option value="474">Ленинградская область</option>
                <option value="475">Липецкая область</option>
                <option value="476">Магаданская область</option>
                <option value="422">Москва</option>
                <option value="477">Московская область</option>
                <option value="478">Мурманская область</option>
                <option value="503">Ненецкий автономный округ</option>
                <option value="479">Нижегородская область</option>
                <option value="480">Новгородская область</option>
                <option value="481">Новосибирская область</option>
                <option value="482">Омская область</option>
                <option value="483">Оренбургская область</option>
                <option value="484">Орловская область</option>
                <option value="485">Пензенская область</option>
                <option value="452">Пермский край</option>
                <option value="453">Приморский край</option>
                <option value="486">Псковская область</option>
                <option value="425">Республика Адыгея</option>
                <option value="426">Республика Алтай</option>
                <option value="427">Республика Башкортостан</option>
                <option value="428">Республика Бурятия</option>
                <option value="429">Республика Дагестан</option>
                <option value="430">Республика Ингушетия</option>
                <option value="432">Республика Калмыкия</option>
                <option value="434">Республика Карелия</option>
                <option value="435">Республика Коми</option>
                <option value="436">Республика Крым</option>
                <option value="437">Республика Марий Эл</option>
                <option value="438">Республика Мордовия</option>
                <option value="439">Республика Саха (Якутия)</option>
                <option value="440">Республика Северная Осетия – Алания</option>
                <option value="441">Республика Татарстан</option>
                <option value="442">Республика Тыва (Тува)</option>
                <option value="444">Республика Хакасия</option>
                <option value="487">Ростовская область</option>
                <option value="488">Рязанская область</option>
                <option value="489">Самарская область</option>
                <option value="423">Санкт-Петербург</option>
                <option value="490">Саратовская область</option>
                <option value="491">Сахалинская область</option>
                <option value="492">Свердловская область</option>
                <option value="424">Севастополь</option>
                <option value="493">Смоленская область</option>
                <option value="454">Ставропольский край</option>
                <option value="494">Тамбовская область</option>
                <option value="495">Тверская область</option>
                <option value="496">Томская область</option>
                <option value="497">Тульская область</option>
                <option value="498">Тюменская область</option>
                <option value="443">Удмуртская Республика</option>
                <option value="499">Ульяновская область</option>
                <option value="455">Хабаровский край</option>
                <option value="504">Ханты-Мансийский автономный округ</option>
                <option value="500">Челябинская область</option>
                <option value="445">Чеченская Республика</option>
                <option value="446">Чувашская Республика</option>
                <option value="505">Чукотский автономный округ</option>
                <option value="507">Школа при посольстве РФ</option>
                <option value="506">Ямало-Ненецкий автономный округ</option>
                <option value="501">Ярославская область</option>
            </select>
        </div>
        <button type="submit" class="btn btn-green">Обновить</button>
    </form>
</div>