<?php
$this->title = 'Договор кредитора';

$this->params['breadcrumbs'][] = ['label' => 'Все кредиты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id . ' ' . $model->doc_date_start];
$this->params['breadcrumbs'][] = $this->title;
/** @var $model  \common\models\Credit */
/** @var $items  \common\models\CreditItem */
?>

<style>

    #PrintDoc > p {
        font-size: 12px !important;
    }

    table tr td {
        padding: 5px;
        font-size: 12px !important;
    }

    @media print {
        * {
            font-size: 11px !important;
        }
    }
</style>
<button type="button" onclick="PrintElem('PrintDoc')" class="btn btn-primary"><i
            class="bi bi-printer"></i> Распечатать
</button>
<div id="PrintDoc">
    <div class="row">
        <div class="col-md-11">
            <h2 class="text-center" style="text-align: center;">Шартнома № <?php echo $model->id; ?> </h2>
            <p align="center">(муддатли тўлов шарти билан олди – сотди шартномаси)</p>
        </div>
        <div class="col-md-1">
            <?php

            use Da\QrCode\QrCode;
            use yii\helpers\Html;

            $url_base = 'https://taqsimsavdo.uz';
            $url_action = '/credit/graphic';
            $url = '?token=' . $model->token;
            $qrCode = (new QrCode($url_base . $url_action . $url))
                //->setLogo(__DIR__ . 'http://localhost:8888/clinics_multi/frontend/web/logo/1630226301.png')
                //->setEncoding('UTF-8')
                //->setLogoWidth(60)
                ->setSize(100)
                ->setMargin(1)
                ->useForegroundColor(0, 0, 0);

            // now we can display the qrcode in many ways
            // saving the result to a file:

            $qrCode->writeFile(__DIR__ . '/code.png'); // writer defaults to PNG when none is specified

            // or even as data:uri url
            echo '<img style="" src="' . $qrCode->writeDataUri() . '">';
            ?>
        </div>
    </div>


    <table style="width: 100%; border: none" border="0">
        <tr>
            <td style="text-align: left"> <?= $model->region->name ?></td>
            <td style="text-align: right"><?php echo $model->doc_date_start; ?></td>
        </tr>
    </table>
    <br clear="all"/>
    <div class="row">
        <div class="col-md-12">
            <p>
                Бир томондан Коканд шахар “ Ягонадарча “ маркази томонидан(23.11.2020 йилда № 916981) -сон билан
                берилган
                гувоҳнома
                асосида фаолият кўрсатувчи <?php /*echo $model->company->name; */?> "YEC CARPETS" MCHJ,
                кейинги ўринларда “Сотувчи” деб юритилувчи,
                <?php echo $model->client->address; ?> яшовчи фуқаро <?php echo $model->client->fullname; ?> (
                паспорт <?php echo $model->client->passport_numb; ?>
                <?php echo $model->client->passport_whose; ?> томонидан <?php echo $model->client->passport_enddate; ?>
                йилгача)
                ( кейинги ўринда “Харидор”) икинчи томондан
                ушбу шартномани тарафлар ўртасида ўзаро келишув асосида қуйидагилар тўғрисида туздилар.
            </p>
        </div>
    </div>
    <h3 class="text-center" style="text-align: center;">1. ШАРТНОМА МАҚСАДИ</h3>

    <ul class="list-unstyled" style="list-style: none;">
        <li>
            1.1. Ушбу шартнома тарафлар ўртасидаги ўзаро муносабатларни тартибга солади, яъни «Сотувчи» қуйдаги
            маҳсулотларни
            «Харидор»га ўзаро келишув асосида <?php echo $model->month_count; ?> ой муддат давомида қийматини бўлиб
            тўлаш шарти билан сотади.
        </li>
    </ul>

    <table style="margin:0 auto;width: 100%;text-align: center;font-size:11px; border-collapse:collapse" border="1">
        <tr style="font-weight: bold;">
            <td> №</td>
            <td> Махсулот номи</td>
            <td> Микдори</td>
            <td> Нархи</td>
            <td> Суммаси</td>
        </tr>
        <?php $i = 1;
        $item_total = 0;
        foreach ($items as $item): ?>
            <tr>
                <td> <?php echo $i; ?></td>
                <td> <?php echo $item->title; ?></td>
                <td> <?php echo $item->count; ?></td>
                <td>
                    <?php
                    $item_percent = ($item->summa * $model->percent / 100) + $item->summa;
                    ?>

                    <?php echo Yii::$app->formatter->asDecimal($item_percent, 0); ?></td>
                <td>  <?php echo Yii::$app->formatter->asDecimal($item_percent * $item->count, 0); ?></td>
            </tr>
            <?php $i++;
            $item_total = $item_total + ($item_percent * $item->count); endforeach; ?>
    </table>
    <br/>
    <p>
        Жами маҳсулотнинг cуммаси: <?php echo Yii::$app->formatter->asDecimal($item_total, 0); ?>
        (<?php echo $model->doc_total_text; ?>) сўм.
    </p>
    <h3 class="text-center" style="text-align: center;">2. ХИСОБ КИТОБ ТАРТИБИ</h3>
    <ul class="list-unstyled" style="list-style: none;">
        <li>2.1. Мазкур шартнома суммаси қуйидаги жадвал бўйича аниқланди ;</li>
    </ul>
    <table style="margin:0 auto;width: 100%;text-align: center; border-collapse:collapse" border="1">
        <tr style="font-weight: bold;">
            <td> Шартноманинг умумий киймати</td>
            <td> Олдинан тўлов</td>
            <td> Колдик карз суммаси</td>
            <td> Ойлик тўлов <br/> Суммаси (яхлит)</td>

        </tr>
        <tr>
            <?php
            $dept = $item_total - $model->prepaid_summa;
            $per_month = $model->doc_total_price / $model->month_count;
            ?>
            <td> <?php echo Yii::$app->formatter->asDecimal($item_total, 0); ?> </td>
            <td> <?php echo Yii::$app->formatter->asDecimal($model->prepaid_summa, 0); ?> </td>
            <td> <?php echo Yii::$app->formatter->asDecimal($dept, 0); ?></td>
            <td> <?php echo Yii::$app->formatter->asDecimal($per_month, 0); ?></td>

        </tr>
    </table>
    <br/>
    <ul class="list-unstyled" style="list-style: none;">
        <li>2.2. Шартноманинг умумий қиймати <strong> <?php echo Yii::$app->formatter->asDecimal($item_total, 0); ?>
                ( <?php echo $model->doc_total_text; ?>) сумни </strong> ташкил қилади.
        </li>
        <li>2.3. Сотиб олинаётган махсулот(лар) кийматининг «Харидор» уз ойлик иш хакисидан ёки бошка даромадлари
            хисобидан
            хар ойнинг 4 – кунига кадар маскур шартноманинг ажралмас кисми булган Тўлов жадвали – илова асосида тулаб
            боради,
            агар «Харидор» шартномада курсатилган суммани уз вахтида амалга оширмаган такдирда <strong>«Кафил» тулашга
                мажбурдир.</strong>
            Кафолат хати илова килинади, агар шартнома кафиллик асосида тузилган булса.
        </li>
        <li>
            2.4. Маскур шартнома имзоланган кундан бошлаб юридик кучга киради ,шартноманинг амал килиш мудати
            <?php echo $model->doc_date_start; ?> - йилдан <?php echo $model->doc_date_end; ?> – йилгача булиб,
            карздорлик тулик тулаб
            булинганидан сунг шартнома тугатилади.
        </li>
        <li>
            2.5. «Харидор» сотиб олган махсулот(лар)нинг кийматини уз вахтида туламаган холда ёки кечиктирса «Сотувчи»
            томонидан шартномада курсатилган кийматни колган кисмини бирдан талаб килишга хаклидур.
        </li>
    </ul>


    <h3 class="text-center" style="text-align: center;">3. МАХСУЛОТ(ЛАР)НИ ЕТКАЗИШ ТАРТИБИ</h3>
    <ul class="list-unstyled" style="list-style: none;">
        <li>3.1. «Харидор» томонидан сотиб олинаётган махсулот(лар) учун шартнома имзоланганидан
            сунг 10 кун муддат ичида «Сотувчи» ва «Харидор» уртасида келишув асосида махсулот(лар)ни етказиб берилади.
        </li>
    </ul>


    <h3 class="text-center" style="text-align: center;">4. ТОМОНЛАРНИНГ ҲУҚУҚ ВА МАЖБУРИЯТЛАРИ.</h3>
    <ul class="list-unstyled" style="list-style: none;">

        <li><strong>«Сотувчи»нинг хукук ва мажбуриятлари;</strong></li>
        <li>4.1.1 «Сотувчи» «Харидор»нинг оиладаги молиявий холатни урганиши ва тахлил килгандан сунг 3-5 кун ичида
            шартномани имзолашга хаклидур.
        </li>
        <li>4.1.2. «Харидор»дан сотиб олинган махсулот(лар) Тўловини уз вактида туланишини талаб килиб бориш ;
            Муддатидан икки ёки уч кун
            давомида тўловларни амалга оширмаган такдирда , «Харидор» ва «Кафил»нинг яшаш жойи ва иш жойига бориб
            рахбариятига карздорликни
            ундириш юзасидан амалий ёрдам бериши хакида ёзма мурожат килиш ;
        </li>
        <li><strong>«Харидор»нинг хукук ва мажбуриятлари;</strong></li>
        <li>4.2.1. «Харидор» сотиб олинаётган махсулот(лар) сифатида нуксон маьлум булган такдирда 3 кун ичида кайтариб
            бериш ва бошкасига
            алмаштиришга хакли ;
        </li>
        <li>4.2.2 «Сотувчи»дан махсулот(лар)ни тулик текшириб олиш хамда 10 кун ичида кабул килиб олиш ;</li>
        <li>- сотиб олинган махсулот(лар) учун Тўловларни уз вактида амалга ошириш ;</li>
        <li> -иш жойи, яшаш манзили ва бошка маьлумотлари узгарган такдирда, 5 кун муддат ичида «Сотувчи»га хабар
            бериш.
        </li>


    </ul>

    <h3 class="text-center" style="text-align: center;">5. ТОМОНЛАРНИНГ ЖАВОБГАРЛИГИ</h3>
    <ul class="list-unstyled" style="list-style: none;">
        <li>
            5.1 сотувчининг хукуклари
            - <?= $model->client->fullname ?> томонидан ушбу шартномага асосан туловлар муддатидан кечиктирилганда
            зудлик билан <?= $model->client->fullname ?>
            шартноманинг тулик кийматини накд пул шаклида ёки пластик картасидан акцептсиз йечиб олиш оркали ундириш
            чораларини куриш;
            <!--5.1. Шартнома шартлари ,агар Конун хужжатларида бошкача холда назарда тутилган булса , тарафларнинг
            келишувига биноан
            узгартирилиши мумкин.-->
        </li>
        <li>5.2. Томонлар ушбу шартнома шартларини бажармаслик ва белгиланган шартларга риоя килмасдан бажарган холда
            амалдаги
            конунчилик олдида жавобгардирлар.
        </li>
        <li>5.3. Ушбу шартномани бажармаслик окибатида юзага келган барча низоли вазиятлар Узбекистон Республикасининг
            амалидаги
            конун хужжатларига амал килган холда, музокоролар йули билан хал килинади. Томонлар келишувга еришмаган
            такдирда низолар
            жойлардаги Суд органлари томонидан хал килинади.
        </li>
        <li>
            5.4 -узининг барча шахсий пластик карталаридан ва барча банклардаги банк хисоб варакаларидан мазкур шартнома
            буйича мажбуриятларини амалга ошириш учун зарур суммани акцептсиз хисобдан чикариш хукукини
            <?php /*=$model->client->fullname*/?>"YEC CARPETS" MCHJ га такдим етиш.
        </li>

    </ul>

    <h3 class="text-center" style="text-align: center;">6. ТОМОНЛАРНИНГ МАНЗИЛГОХИ ВА БАНКДАГИ ХИССОБ РАКАМЛАРИ</h3>
    <table style="text-align:left;margin:10px auto;width: 90%;font-size:12px;vertical-align: top;" border="0">
        <tr>
            <td style="text-align: left;width: 49%;"><p style="font-size:15px;"><strong>«Сотувчи»</strong></p><br/></td>
            <td style="text-align: left;width: 49%;"><p style="font-size:15px;"><strong>«Харидор» </strong></p><br/>
            </td>
        </tr>
        <tr>
            <td style="text-align: left">
                <strong><?= $model->company->company_title ?></strong>
                <?php echo $model->company->company_props; ?>
            </td>
            <td style="text-align: left;">
                <p style="margin-top: -75px;">
                    <strong>Фуқаро:</strong> <?php echo $model->client->fullname; ?><br/>
                    <strong>Яшаш манзили:</strong> <?php echo $model->client->address; ?><br/>
                    <strong>Паспорт маълумотлари:</strong> <?php echo $model->client->passport_numb; ?>
                    <br/> <?php echo $model->client->passport_enddate; ?>,
                    <?php echo $model->client->passport_whose; ?> <strong>томонидан берилган</strong> <br/>
                    <strong>Tелефон:</strong> <?php echo $model->client->phone; ?>
                </p>

            </td>

        </tr>
        <tr>
            <td>
                <strong><?= $model->company->company_director ?></strong>__________________
            </td>
            <td>
                <strong><?php echo $model->client->fullname; ?> <img src="<?= $sign->client_sign ?>" alt=""
                                                                     style="width: 220px;"> </strong><br/>
            </td>
        </tr>
    </table>
</div>
<script>
    function PrintElem(elem) {
        var mywindow = window.open('', '<?=Html::encode($this->title)?>', 'height=1000,width=1000');

        mywindow.document.write('<html><head><title>' + document.title + '</title>');
        mywindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"></head><body >');
        mywindow.document.write(`<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap');

      body {
           font-family: 'Roboto Condensed', sans-serif;
font-size: 12px;
        }
        /*table tr td {
            padding:5px 7px;
            border: 1px black solid;
border-collapse: collapse;
text-align: center;
        }*/
        th{padding:5px 7px;
            border: 1px black solid;}
        .pagination{
        display: none;
        }

</style>`);
        // mywindow.document.write('<h2 style="text-align:center"><?=Html::encode($this->title)?> на <?=date('d.m.Y H:i')?></h2>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();

        return true;
    }
</script>