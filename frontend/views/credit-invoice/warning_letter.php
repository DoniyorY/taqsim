<?php
$this->title = 'О Г О Х Л А Н Т И Р И Ш   Х А Т И';



?>

<style xmlns="http://www.w3.org/1999/html">
    table tr td {padding:5px;}
    ul { }
</style>
<button class="btn btn-primary  btn-xsmall" onclick="PrintDocFunc('PrintDoc')">Распечатать</button>

<div id="PrintDoc">
    <table style="width: 100%;">
        <tr>
            <td style="padding-right:20px;">
                <!-- -->
                <div style="margin:0 auto;width: 70%;">
                    <p style="text-align: right;font-size:11px;">
                        <strong>Даъвогар:</strong> <?php echo $model->company->company_title; ?><br/>
                        <?=$model->company->company_props?>
                    </p>
                    <br/>

                    <p style="text-align: center;text-transform: uppercase;">
                        <?php echo $model->client->address; ?> яшовчи <br/>
                        <?php echo $model->client->fullname; ?>га
                    </p>
                </div>


                <h4 class="text-center" style="text-align: center;">ОГОХЛАНТИРИШ   ХАТИ</h4>

                <p style="text-indent: 30px;text-align: justify;font-size:12px;">
                    Сиз <strong> <?php echo $model->client->fullname; ?></strong> "YEC CARPETS" MCHJ <?php /*echo $model->company->name; */?>га тегишли бўлган «YEC» гиламлари дўконидан
                    <strong><?php echo $model->doc_date_start; ?> йил</strong> кунги
                    <strong>№<?php echo $model->id; ?>-сонли</strong>
                    «Муддатли тўлов шарти билан олди-сотди шартнома»га асосан Жами:
                    <strong><?php echo Yii::$app->formatter->asDecimal($model->doc_total_price, 0); ?></strong> сўмлик гилам махсулоти олгансиз.
                    Лекин сиз «Муддатли тўлов шарти билан олди-сотди шартнома»сининг 2.3 бандни қўпол
                    равишда бузиб, тўлов жадвалига асосан қарз тўловларини белгиланган  муддатда  амалга
                    оширмасдан келмоқдасиз.  <?php /*echo $model->company->name; */?> "YEC CARPETS" MCHJ Сиздан туловларни
                    3 банк иш куни ичида жами: <strong><?php echo Yii::$app->formatter->asDecimal($sum, 0); ?></strong> карздорлик суммасини тулашни қатъий талаб қилади.   Акс  ҳолда  шартномани  муддатидан
                    олдин  бекор  қилиниб, Ўзбекистон   Республикаси   Президентининг   2015 йил 15-май
                    кунги   №  ПФ- 4725 - сонли «Хусусий мулк, кичик бизнес ва хусусий тадбиркорликни
                    ишончли ҳимоя қилишни таъминлаш, уларни  жадал  ривожлантириш   йўлидаги
                    тўсиқларни  бартараф   этиш   чора-тадбирлари тўғрисида»ги Фармонинг  ижросини
                    таъминлаб,  Ўзбекистон  Республикаси  ФПКнинг  5,57 ва 224-моддаларига асосан
                    Фуқаролик ишлари  бўйича  судларга  фуқаролик  ишини  қўзғатиш бўйича тайёрланаётган
                    қарздорлик  суммасини  ундириш  юзасидан даъво   аризаси   орқали, чиқарилган суд
                    қарорига  асосан Ўзбекистон  Респудликаси Бош Прокуратураси ҳузуридаги Мажбурий
                    ижро Бюроси оркали Ўзбекистон  Респудликаси  Президентининг  29.08.2001  йил кунги №
                    258-II-сонли «Суд ҳужжатлари ва бошқа орган ҳужжатларини ижро этиш тўғрисида»ги
                    Қонунининг 46,47,50,53 ва 82-моддаларига асосан ундирувни Сизнинг мол-мулкига
                    қаратилишини мумкинлиги ҳақида <strong>РАСМАН ОГОХЛАНТИРАМАН!</strong>
                </p>
                <br/>


                <p  style="text-align: left;font-size:11px;">
                    <strong>Асос:</strong><br/>
                    1.<?php echo $model->doc_date_start; ?> йил кунги №<?php echo $model->id; ?>-сонли «Муддатли тўлов шарти билан олди-сотди шартнома»<br/>
                    2.Қарздорлик тўлов жадвали
                </p>
                <p  style="text-align: left;"><?php echo $model->company->company_title; ?> __________________</p>
                <!-- -->
            </td>

            <td style="padding-right:20px;">
                <!-- -->
                <div style="margin:0 auto;width: 70%;">
                    <p style="text-align: right;font-size:11px;">
                        <strong>Даъвогар:</strong> <?php echo $model->company->name; ?><br/>
                        Кукон шахар  Давлат хизматлари Марказидан  13.03.2018 йдаги<br/>
                        № 710940 руйхатга олинган гувохнома асосида фаолия юритувчи.<br/>
                        <strong> ИНН:</strong> № 548371195<br/>
                        <strong>Банк:</strong> АТБ «InFinBank» Қўқон филиали, МФО: 01116,<br/>
                        X/р 2021 8000 2007 1997 9001<br/>
                        <strong> Манзил:</strong> Фаргона вилояти, Қўқон шахри, А.Навоий 25А уй
                    </p>
                    <br/>

                    <p style="text-align: center;text-transform: uppercase;">
                        <?php echo $model->client->address; ?> яшовчи <br/>
                        <?php echo $model->client->fullname; ?>га
                    </p>
                </div>


                <h4 class="text-center" style="text-align: center;">ОГОХЛАНТИРИШ   ХАТИ</h4>

                <p style="text-indent: 30px;text-align: justify;font-size:12px;">
                    Сиз <strong> <?php echo $model->client->fullname; ?></strong> <?php /*echo $model->company->name; */?> "YEC CARPETS" MCHJ га тегишли бўлган «YEC» гиламлари дўконидан
                    <strong><?php echo $model->doc_date_start; ?> йил</strong> кунги
                    <strong>№<?php echo $model->id; ?>-сонли</strong>
                    «Муддатли тўлов шарти билан олди-сотди шартнома»га асосан Жами:
                    <strong><?php echo Yii::$app->formatter->asDecimal($model->doc_total_price, 0); ?></strong> сўмлик гилам махсулоти олгансиз.
                    Лекин сиз «Муддатли тўлов шарти билан олди-сотди шартнома»сининг 2.3 бандни қўпол
                    равишда бузиб, тўлов жадвалига асосан қарз тўловларини белгиланган  муддатда  амалга
                    оширмасдан келмоқдасиз.  <?php /*echo $model->company->name; */?> "YEC CARPETS" MCHJ Сиздан туловларни
                    3 банк иш куни ичида жами: <strong><?php echo Yii::$app->formatter->asDecimal($sum, 0); ?></strong> карздорлик суммасини тулашни қатъий талаб қилади.   Акс  ҳолда  шартномани  муддатидан
                    олдин  бекор  қилиниб, Ўзбекистон   Республикаси   Президентининг   2015 йил 15-май
                    кунги   №  ПФ- 4725 - сонли «Хусусий мулк, кичик бизнес ва хусусий тадбиркорликни
                    ишончли ҳимоя қилишни таъминлаш, уларни  жадал  ривожлантириш   йўлидаги
                    тўсиқларни  бартараф   этиш   чора-тадбирлари тўғрисида»ги Фармонинг  ижросини
                    таъминлаб,  Ўзбекистон  Республикаси  ФПКнинг  5,57 ва 224-моддаларига асосан
                    Фуқаролик ишлари  бўйича  судларга  фуқаролик  ишини  қўзғатиш бўйича тайёрланаётган
                    қарздорлик  суммасини  ундириш  юзасидан даъво   аризаси   орқали, чиқарилган суд
                    қарорига  асосан Ўзбекистон  Респудликаси Бош Прокуратураси ҳузуридаги Мажбурий
                    ижро Бюроси оркали Ўзбекистон  Респудликаси  Президентининг  29.08.2001  йил кунги №
                    258-II-сонли «Суд ҳужжатлари ва бошқа орган ҳужжатларини ижро этиш тўғрисида»ги
                    Қонунининг 46,47,50,53 ва 82-моддаларига асосан ундирувни Сизнинг мол-мулкига
                    қаратилишини мумкинлиги ҳақида <strong>РАСМАН ОГОХЛАНТИРАМАН!</strong>
                </p>
                <br/>


                <p  style="text-align: left;font-size:11px;">
                    <strong>Асос:</strong><br/>
                    1.<?php echo $model->doc_date_start; ?> йил кунги №<?php echo $model->id; ?>-сонли «Муддатли тўлов шарти билан олди-сотди шартнома»<br/>
                    2.Қарздорлик тўлов жадвали
                </p>
                <p  style="text-align: left;"><?php echo $model->company->company_title; ?> __________________</p>
                <!-- -->
            </td>


        </tr>
    </table>
</div>

