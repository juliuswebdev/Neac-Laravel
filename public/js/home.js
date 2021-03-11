$(function () {
'use strict'

    $(".table-home").DataTable({
        "responsive": true,
        "autoWidth": true,
        "paging": true,
        "lengthChange": true,
        "searching":true,
        "ordering": true,
        "info": true,
    });

    $('#applicant_year').change(function(){
        applicant_chart($(this).val());
    });

    applicant_chart($('#applicant_year').val());
    var salesChart;

    function applicant_chart(year) {
        var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
        }
        var mode      = 'index'
        var intersect = true
        fetch('/home/userdataregister/'+year)
        .then(res => res.json())
        .then((data) => {
        if (salesChart) {
            salesChart.destroy();
        }
        var $salesChart = $('#salesChart');
        salesChart  = new Chart($salesChart, {
            type   : 'bar',
            data   : {
            labels  : Object.keys(data),
            datasets: [
                {
                backgroundColor: '#007bff',
                borderColor    : '#007bff',
                data           :  Object.values(data)
                },
            ]
            },
            options: {
            maintainAspectRatio: false,
            tooltips           : {
                mode     : mode,
                intersect: intersect
            },
            hover              : {
                mode     : mode,
                intersect: intersect
            },
            legend             : {
                display: false
            },
            scales             : {
                yAxes: [{
                // display: false,
                gridLines: {
                    display      : true,
                    lineWidth    : '4px',
                    color        : 'rgba(0, 0, 0, .2)',
                    zeroLineColor: 'transparent'
                },
                ticks    : $.extend({
                    beginAtZero: true,

                    // Include a dollar sign in the ticks
                    callback: function (value, index, values) {
                    if (value >= 1000) {
                        value /= 1000
                        value += 'k'
                    }
                    return value
                    }
                }, ticksStyle)
                }],
                xAxes: [{
                display  : true,
                gridLines: {
                    display: false
                },
                ticks    : ticksStyle
                }]
            }
            }
        })

        });
    }



})
