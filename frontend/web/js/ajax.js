/*Today Payment Button Ajax*/
function ajaxRequest() {

    var url = 'https://taqsimsavdo.uz/site/ajax-request'
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open('GET', url)
    xmlhttp.send()

    let total_cash = document.getElementById('totalCash')
    let total_card = document.getElementById('totalCard')
    let total_atmos = document.getElementById('totalAtmos')
    let total = document.getElementById('total')
    let today_cash = document.getElementById('todayCash')
    let today_card = document.getElementById('todayCard')
    let today_atmos = document.getElementById('todayAtmos')
    let today_total = document.getElementById('todayTotal')
    xmlhttp.onreadystatechange = function () {
        const NumberFormatter = (value, decimal) => {
            return parseFloat(parseFloat(value).toFixed(decimal)).toLocaleString(
                "ru-Ru",
                {
                    useGrouping: true,
                }
            );
        };
        //console.log(xmlhttp);
        if (this.readyState === 4 && this.status === 200) {
            var data = JSON.parse(this.responseText)
            let req_total = Number(data['total_cash']) + Number(data['total_card'] + Number(data['today_atmos']))

            //console.log(NumberFormatter(req_total))
            total_cash.innerHTML = NumberFormatter(data['total_cash'], 0)
            total_card.innerHTML = NumberFormatter(data['total_card'], 0)
            total_atmos.innerHTML = NumberFormatter(data['total_atmos'], 0)
            total.innerHTML = NumberFormatter(req_total, 0)
            today_card.innerHTML = NumberFormatter(data['today_card'], 0)
            today_cash.innerHTML = NumberFormatter(data['today_cash'], 0)
            today_atmos.innerHTML = NumberFormatter(data['today_atmos'], 0)
            let req_today_total = Number(data['today_card']) + Number(data['today_cash'] + Number(data['today_atmos']))
            today_total.innerHTML = NumberFormatter(req_today_total)
        }
    }

}