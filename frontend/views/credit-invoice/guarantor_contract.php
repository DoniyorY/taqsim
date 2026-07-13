<?php
$this->title = 'КАФИЛЛИК ШАРТНОМАСИ ';

$this->params['breadcrumbs'][] = ['label' => 'Все кредиты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id.' '.$model->doc_date_start, 'url' => ['/credit/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
/**
 * @var $model \common\models\Credit
 * @var $items \common\models\CreditItem
 */

?>

<style>
    table tr td {padding:3px;}
    ul { }
</style>
<button class="btn btn-primary  btn-xsmall" onclick="PrintDocFunc('PrintDoc')">Распечатать</button>


<div id="PrintDoc">

    <h3 class="text-center" style="text-align: center;font-size:12px;">КАФИЛЛИК ШАРТНОМАСИ  №  <?php echo $model->id; ?> </h3>
    <p style="font-size:12px;" align="center">(<?php echo $model->doc_date_start; ?> йилдаги №<?php echo $model->id; ?>-сонли муддатли тўлов шарти билан олди-сотди шартномасига илова)</p>
    <table style="width: 100%;">
        <tr>
            <td style="text-align: left;font-size:12px;"> Қўқон шаҳар</td>
            <td style="text-align: right;font-size:12px;"><?php echo $model->doc_date_start; ?></td>
        </tr>
    </table>

    <?php if($model->guarantor_id!=null): ?>
        <p style="text-indent: 30px;font-size:12px;">
            Бир томондан Қўқон шаҳар Давлат хизматлари маркази томонидан 23.11.2020 йилда № 916981–сон билан берилган гувоҳнома
            асосида фаолият кўрсатувчи <?php echo $model->company->name; ?>, кейинги ўринларда
            “Сотувчи” деб юритилувчи ҳамда <?php echo $model->client->address; ?>да
            яшовчи фуқаро <?php echo $model->client->fullname; ?> ( паспорт <?php echo $model->client->passport_numb; ?>
            <?php echo $model->client->passport_whose; ?> томонидан  <?php echo $model->client->passport_enddate; ?> йилда берилган)
            кейинги ўринда “Харидор” деб юритилувчи иккинчи томондан ҳамда
            <?php echo $model->guarantor->fullname; ?> ( паспорт <?php echo $model->guarantor->passport_numb; ?>
            <?php echo $model->guarantor->passport_whose; ?>  томонидан <?php echo $model->guarantor->passport_enddate; ?>  йилда берилган)
            кейинги ўринларда “Кафил” деб юритилувчи учинчи томондан ушбу шартномани тарафлар ўртасида ўзаро келишув асосида
            қуйидагилар тўғрисида туздилар.
        </p>
    <?php endif; ?>

    <?php if($model->guarantor_id==null): ?>
        <p style="text-indent: 30px;font-size:12px;">
            Бир томондан Қўқон шаҳар Давлат хизматлари маркази томонидан 23.11.2020 йилда № 916981–сон билан берилган гувоҳнома
            асосида фаолият кўрсатувчи <?php $model->company->name; ?>, кейинги ўринларда
            “Сотувчи” деб юритилувчи ҳамда <?php echo $model->client->address; ?>да
            яшовчи фуқаро <?php echo $model->client->fullname; ?> ( паспорт <?php echo $model->client->passport_numb; ?>
            <?php echo $model->client->passport_whose; ?> томонидан  <?php echo $model->client->passport_enddate; ?> йилда берилган)
            кейинги ўринда “Харидор” деб юритилувчи иккинчи томондан ҳамда
            <?php echo $model->guarantor->fullname; ?> ( паспорт <?php echo $model->guarantor->passport_numb; ?>
            <?php echo $model->guarantor->passport_whose; ?>  томонидан <?php echo $model->guarantor->passport_enddate; ?>  йилда берилган)
            кейинги ўринларда “Кафил” деб юритилувчи учинчи томондан ушбу шартномани тарафлар ўртасида ўзаро келишув асосида
            қуйидагилар тўғрисида туздилар.
        </p>
    <?php endif; ?>


    <h4 class="text-center" style="text-align: center;font-size:12px;margin-bottom:0;">1.	ШАРТНОМА МАЗМУНИ.</h4>
    <ul class="list-unstyled" style="list-style: none;font-size:12px; margin:0;padding: 0;">
        <li>
            1.1	Ушбу кафиллик шартномаси орқали “Кафил”, “Сотувчи” ва “Харидор” ўртасида тузилган <?php echo $model->doc_date_start; ?> йилдаги
            № <?php echo $model->id; ?>-сонли
            муддатли тўлов шарти билан олди-сотди шартномасига асосан
            <?php echo yii::$app->formatter->asDecimal(($model->doc_total_price+$model->prepaid_summa),0); ?>
            (<?php echo $model->doc_total_text; ?>) сўм қарздорликни
            <?php echo $model->month_count; ?> ой муддат давомида қайтариш юзасидан “Харидор”нинг барча мажбуриятларини солидар тартибда
            ўз зиммасига олади.
        </li>
    </ul>
    <h4 class="text-center" style="text-align: center;font-size:12px;margin-bottom:0;">2.	ТОМОНЛАРНИНГ ҲУҚУҚ ВА МАЖБУРИЯТЛАРИ.</h4>
    <ul class="list-unstyled" style="list-style: none;font-size:12px; margin:0;padding: 0;">
        <li>2.1		 Мазкур қарздорлик “Харидор” томонидан ўз вақтида ва тўлиқ қайтарилмаган тақдирда, “Сотувчи” “Кафил”дан ундириш
            чоралари куриш ёки суд органларигамурожаат қилиш ҳуқуқига эга. </li>
        <li> 2.2	“Кафил” “Харидор” сотиб олган маҳсулот(лар)нинг тўловлари ҳақида «Сотувчи»дан маълумот олишга ҳақли. </li>
        <li>2.3	“Кафил” “Сотувчи” томонидан тўловлар амалга оширилмаётганлиги ҳақида оғзаки ва ёзма тарзда огохлантирилганидан сўнг
            3 кун муддат ичида тўловларни амалга оширишга мажбур. </li>
        <li> 2.4	“Кафил” томонидан мазкур шартноманинг 2.3-банди талаблари ўз вақтида бажарилмаса, “Сотувчи” 2.1-бандига асосан суд
            органларига ҳужжатларни тақдим қилиб, қарздорлик суммасини унинг шахсий мол-мулклари ёки бошқа даромадлари ҳисобидан ундириб
            олишга ҳақли. </li>
        <li>2.5	Агарда қарздорлик “Кафил” томонидан қопланса, кейинчалик ушбу қопланган қисмига нисбатан “Харидор”дан ундириб олиш
            юзасидан суд органларига мурожаат қилиш ҳуқуқига эга. </li>
        <li> 2.6	 “Сотувчи” “Харидор” томонидан ўз мажбуриятлари тўлиқ бажарилгандан сўнг ушбу ҳақда “Кафил”ни огохлантиради. </li>
        <li>2.7	“Харидор” ва “Кафил” “Сотувчи” олдида солидар тартибда жавоб берадилар. “Харидор” ўз мажбуриятларини бажармаган
            тақдирда “Кафил” ушбу мажбурият бўйича иш ҳақи ва мол-мулки билан жавоб беради. </li>
    </ul>

    <h4 class="text-center" style="text-align: center;font-size:12px;margin-bottom:0;">3.	ШАРТНОМАНИНГ АМАЛ ҚИЛИШ МУДДАТИ.</h4>
    <ul class="list-unstyled" style="list-style: none;font-size:12px; margin:0;padding: 0;">
        <li>3.1	Мазкур шартнома тарафлар томонидан имзолангандан сўнг кучга кириб, <?php echo $model->doc_date_start; ?> йилдаги №<?php echo $model->id; ?>-сонли муддатли
            тўлов шарти билан олди-сотди шартномасининг мажбуриятлари тўлиқ бажарилгунига қадар амал қилади.</li>
        <li>3.2	Олди-сотдишартномаси шартлари ва мажбуриятлари “Кафил”нинг розилигисиз ўзгартирилган тақдирда, ушбу шартнома
            ўз кучини йуқотган ҳисобланади.</li>
    </ul>

    <h4 class="text-center" style="text-align: center;font-size:12px;margin-bottom:0;">4.	НИЗОЛАРНИ КЎРИБ ЧИҚИШ ТАРТИБИ.</h4>
    <ul class="list-unstyled" style="list-style: none;font-size:12px; margin:0;padding: 0;">
        <li>4.1    Ушбу шартнома бўйича юзага келадиган барча низоли вазиятлар Ўзбекистон Республикасининг амалдаги
            қонун ҳужжатларига амал қилган ҳолда, томонлар ўртасида музокоролар йўли билан ҳал қилинади. Томонлар келишувга
            эришмаган тақдирда низолар Ўзбекистон Республикасининг амалдаги қонун ҳужжатларида белгиланган тартибда Суд органлари
            томонидан ҳал қилинади.</li>
    </ul>

    <h4 class="text-center" style="text-align: center;font-size:12px;margin-bottom:0;">5.	БОШҚА ШАРТЛАР.</h4>
    <ul class="list-unstyled" style="list-style: none;font-size:12px; margin:0;padding: 0;">
        <li>5.1	Ушбу шартномага барча ўзгартириш ва қўшимчалар томонлар имзолаган қушимча битим тарзида ёзма шаклда расмийлаштирилади.</li>
        <li>5.2	Ушбу шартномада кўзда тутилмаган ҳолатлар юзасидан томонлар амалдаги қонунчиликка асосан иш кўрадилар.</li>
        <li>5.3	Ушбу шартнома ҳар қайси томон учун бир хил юридик кучга эга бўлган 3 нусхада тузилди.</li>
    </ul>


    <h4 class="text-center" style="text-align: center;font-size:12px;margin-bottom:0;">ТОМОНЛАРНИНГ МАНЗИЛГОХИ ВА БАНКДАГИ ҲИСОБ РАҚАМЛАРИ</h4>
    <table style="margin:2px auto;width: 98%;font-size:11px;" border="0">
        <tr>
            <td style="text-align: center;width: 33%;"><p style="font-size:15px;"><strong>«Сотувчи»</strong></p></td>
            <td style="text-align: center;width: 33%;"><p style="font-size:15px;"><strong>«Кафил»</strong></p></td>
            <td style="text-align: center;width: 33%;"><p style="font-size:15px;"><strong>«Харидор» </strong></p></td>
        </tr>
        <tr>
            <td style="text-align: justify;">
                <?php echo $model->company->company_props; ?>
            </td>
            <td style="text-align: justify;padding-right: 10px;">
                <?php if($model->guarantor_id!=null): ?>
                    <p>
                        Фуқаро: <strong>  <?php echo $model->guarantor->fullname; ?></strong><br/>
                        Яшаш манзили: <strong><?php echo $model->guarantor->address; ?></strong><br/>
                        Паспорт маълумотлари: <?php echo $model->guarantor->passport_numb; ?> <br/>
                        <?php echo $model->guarantor->passport_enddate; ?>,
                        <?php echo $model->guarantor->passport_whose; ?> томонидан берилган <br/>
                    </p>
                    <br clear="all"/>
                    <strong><?php echo $model->guarantor->fullname; ?> <img src="<?=$sign->guarantor_sign?>" style="height: 80px;" alt=""> </strong><br/>
                <?php endif; ?>
                <?php if($model->guarantor_id==null): ?>
                    <p>
                        Фуқаро: <strong>  <?php echo $model->guarantor->fullname; ?></strong><br/>
                        Яшаш манзили: <strong><?php echo $model->guarantor->address; ?></strong><br/>
                        Паспорт маълумотлари: <?php echo $model->guarantor->passport_numb; ?> <br/>
                        <?php echo $model->guarantor->passport_enddate; ?>,
                        <?php echo $model->guarantor->passport_whose; ?> томонидан берилган <br/>
                    </p>
                    <br clear="all"/>
                    <strong><?php echo $model->guarantor->fullname; ?> ________ </strong><br/>
                <?php endif; ?>

            </td>
            <td style="text-align: justify;">
                <p>
                    Фуқаро: <strong><?php echo $model->client->fullname; ?></strong><br/>
                    Яшаш манзили: <strong><?php echo $model->client->address; ?></strong><br/>
                    Паспорт маълумотлари: <?php echo $model->client->passport_numb; ?> <br/> <?php echo $model->client->passport_enddate; ?>,
                    <?php echo $model->client->passport_whose; ?> томонидан берилган <br/>
                    телефон: <?php echo $model->client->phone; ?>
                </p>
                <br clear="all"/>
                <strong><?php echo $model->client->fullname; ?> <img src="<?=$sign->client_sign?>" style="height: 80px;" alt=""> </strong><br/>
            </td>
        </tr>
    </table>


</div>

