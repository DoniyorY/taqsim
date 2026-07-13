
let item_summa = document.getElementById('total_item_summa');

let self_price_input = document.getElementById('credit_self_price');
let credit_percent_input = document.getElementById('credit_percent');
let credit_month_input = document.getElementById('credit_month');
let credit_prepaid_input = document.getElementById('credit_prepaid');
let total_price_input = document.getElementById('credit_total_price');
let total_as_word_input = document.getElementById('credit-doc_total_text');

document.addEventListener('DOMContentLoaded', () => {
    self_price_input.setAttribute('value', parseFloat(item_summa.value), 0);
    calcCredit()
})


function calcCredit() {
    let self_price = +self_price_input.value
    let credit_percent = +credit_percent_input.value
    let credit_prepaid = +credit_prepaid_input.value
    let credit_month = +credit_month_input.value

    let credit_total = (self_price - credit_prepaid)
    let credit_total_with_percent = (credit_total * (credit_percent / 100)) + credit_total

    let monthly_payment = credit_total_with_percent / credit_month // har oylik kredit summasi

    let total_price = Math.round(credit_total_with_percent + credit_prepaid)

    total_price_input.value = total_price

    total_as_word_input.value = numbToWord(total_price.toString())
}


function numbToWord(_number) {
    let number = _number.split('').reverse().join('')
    console.log(_number)
    let yuz = number.slice(0, 3)
    let ming = number.slice(3, 6)
    let milion = number.slice(6, 9)
    let miliard = number.slice(9, 12)
    let trilion = number.slice(12, 15)
    let _word = ''

    if (trilion.length && hunderToWord(trilion)) _word += `${hunderToWord(trilion)} трилоион `;

    if (miliard.length && hunderToWord(miliard)) _word += `${hunderToWord(miliard)} миллиард `;

    if (milion.length && hunderToWord(milion)) _word += `${hunderToWord(milion)} миллион `;

    if (ming.length && hunderToWord(ming)) _word += `${hunderToWord(ming)} минг `;

    if (yuz.length) _word += hunderToWord(yuz);

    return _word
}

function hunderToWord(numbStr) {
    let sonlar = {
        '1': 'бир',
        '2': 'икки',
        '3': 'уч',
        '4': 'турт',
        '5': 'беш',
        '6': 'олти',
        '7': 'етти',
        '8': 'саккиз',
        '9': 'туккиз',
    }

    let unlik = {
        '1': 'уй',
        '2': 'йигирма',
        '3': 'уттиз',
        '4': 'кирк',
        '5': 'эллик',
        '6': 'олмиш',
        '7': 'етмиш',
        '8': 'саксон',
        '9': 'туксон',
    }

    let un = numbStr.slice(0, 1)
    let yuz = numbStr.slice(1, 2)
    let ming = numbStr.slice(2, 3)

    let word = ''

    if (+ming) word += `${sonlar[ming]} юз `;

    if (+yuz) word += `${unlik[yuz]} `

    if (+un) word += `${sonlar[un]} `

    return word
}


function PrintDocFunc(elem) {
    var mywindow = window.open('', '_blank');
    mywindow.document.write('<html><head>');
    mywindow.document.write('</head><body >');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    mywindow.close();
    mywindow.document.write('</body></html>');
    return true;
}

/*Sign Modal function*/
function openCreateClientModal(type) {
    // bid-sender_client_id
    // bid-receiver_client_id
    if (type === 'creditor') {
        $('#ClientCreateFormModal').modal('show');
        $('#client_create_form_type').text('Кредитор имзоси')
        $('#sig-dataUrl').attr('name', 'creditor_sign')
        //$('#client_create_form_send_button').attr('onclick', `createReceiverClient('bid-sender_client_id')`)

    } else if (type === 'guarantor') {
        $('#ClientCreateFormModal').modal('show');
        $('#client_create_form_type').text('Кафил имзоси')
        $('#sig-dataUrl').attr('name', 'guarantor_sign')
        $('#offers').attr('hidden', true)
        //$('#client_create_form_send_button').attr('onclick', `createReceiverClient('bid-receiver_client_id')`)
    }
}

/* kassa Modal */
$(function () {
    $(document).on('click', '.showModalButton', function () {

        if ($('#modal').hasClass('in')) {
            $('#modal').find('#modalContent')
                .load($(this).attr('value'));
            document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        } else {
            $('#modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'));
            document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        }
    });
});


