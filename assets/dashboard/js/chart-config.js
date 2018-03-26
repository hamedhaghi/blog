var stats = $('#stats').html();
var far = 0;
var ord = 0;
var kho = 0;
var tir = 0;
var mor = 0;
var sha = 0;
var meh = 0;
var aba = 0;
var aza = 0;
var dey = 0;
var bah = 0;
var esf = 0;
var count = {};
if (!$.isEmptyObject(stats)) {
    var orders = JSON.parse(stats);
    $.each(orders, function (index, element) {

        var month = gregorian_to_jalali(2017,element.month,1)[1];

        switch (parseInt(month)) {
            case 1:
                far = element.count;
                break;
            case 2:
                ord = element.count;
                break;
            case 3 :
                kho = element.count;
                break;
            case 4 :
                tir = element.count;
                break;
            case 5 :
                mor = element.count;
                break;
            case 6 :
                sha = element.count;
                break;
            case 7 :
                meh = element.count;
                break;
            case 8 :
                aba = element.count;
                break;
            case 9 :
                aza = element.count;
                break;
            case 10 :
                dey = element.count;
                break;
            case 11 :
                bah = element.count;
                break;
            case 12 :
                esf = element.count;
                break;
        }
    });
}


Chart.defaults.global.defaultFontColor = '#333333';
Chart.defaults.global.defaultFontFamily = 'Yekan';
Chart.defaults.global.defaultFontSize = 15;
Chart.defaults.global.legend.display = false;

var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان",
            "آذر",
            "دی",
            "بهمن",
            "اسفند"
        ],
        datasets: [{
            label: "سفارشات",
            data: [far, ord, kho, tir, mor, sha, meh, aba, aza, dey, bah, esf],
            backgroundColor: [
                '#669752',
                '#7cb965',
                '#b9b647',
                '#d0cd51',
                '#d0a33a',
                '#d0861b',
                '#da6017',
                '#d02a16',
                '#d60b40',
                '#a218d0',
                '#0b40d0',
                '#08aed0',
            ],
        }],

    },

    // Configuration options go here
    options: {
        title: {
            display: false,
            fontSize: 20,
            position: 'bottom',
            text: "This Year"

        },
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            xAxes: [{
                gridLines: {
                    display: true
                }
            }],
            yAxes: [{
                gridLines: {
                    display: true
                },
                ticks: {
                    beginAtZero: true,
                    userCallback: function (label) {
                        // when the floored value is the same as the value we have a whole number
                        if (Math.floor(label) === label) {
                            return label;
                        }

                    }
                }
            }]
        }
    }
});





function gregorian_to_jalali(gy,gm,gd){
    g_d_m=[0,31,59,90,120,151,181,212,243,273,304,334];
    if(gy > 1600){
        jy=979;
        gy-=1600;
    }else{
        jy=0;
        gy-=621;
    }
    gy2=(gm > 2)?(gy+1):gy;
    days=(365*gy) +(parseInt((gy2+3)/4)) -(parseInt((gy2+99)/100)) +(parseInt((gy2+399)/400)) -80 +gd +g_d_m[gm-1];
    jy+=33*(parseInt(days/12053));
    days%=12053;
    jy+=4*(parseInt(days/1461));
    days%=1461;
    if(days > 365){
        jy+=parseInt((days-1)/365);
        days=(days-1)%365;
    }
    jm=(days < 186)?1+parseInt(days/31):7+parseInt((days-186)/30);
    jd=1+((days < 186)?(days%31):((days-186)%30));
    return [jy,jm,jd];
}


function jalali_to_gregorian(jy,jm,jd){
    if(jy > 979){
        gy=1600;
        jy-=979;
    }else{
        gy=621;
    }
    days=(365*jy) +((parseInt(jy/33))*8) +(parseInt(((jy%33)+3)/4)) +78 +jd +((jm<7)?(jm-1)*31:((jm-7)*30)+186);
    gy+=400*(parseInt(days/146097));
    days%=146097;
    if(days > 36524){
        gy+=100*(parseInt(--days/36524));
        days%=36524;
        if(days >= 365)days++;
    }
    gy+=4*(parseInt(days/1461));
    days%=1461;
    if(days > 365){
        gy+=parseInt((days-1)/365);
        days=(days-1)%365;
    }
    gd=days+1;
    sal_a=[0,31,((gy%4==0 && gy%100!=0) || (gy%400==0))?29:28,31,30,31,30,31,31,30,31,30,31];
    for(gm=0;gm<13;gm++){
        v=sal_a[gm];
        if(gd <= v)break;
        gd-=v;
    }
    return [gy,gm,gd];
}
