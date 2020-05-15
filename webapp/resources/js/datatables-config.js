$(document).ready(function () {
    $(".table").dataTable({
        sDom:
            '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12 mb-0 mt-2"p>>',
        scrollX: false,
        autoWidth: true,
        pageLength: 15,
        ordering: false,
        lengthChange: false,
        pagingType: "simple_numbers",
        searching: false,
        language: {
            sEmptyTable: "Nessun dato presente nella tabella",
            sInfo: "Vista da _START_ a _END_ di _TOTAL_ elementi",
            sInfoEmpty: "Vista da 0 a 0 di 0 elementi",
            sInfoFiltered: "(filtrati da _MAX_ elementi totali)",
            sInfoPostFix: "",
            sInfoThousands: ",",
            sLengthMenu: "Visualizza _MENU_ elementi",
            sLoadingRecords: "Caricamento...",
            sProcessing: "Elaborazione...",
            sSearch: "Cerca:",
            sZeroRecords: "La ricerca non ha portato alcun risultato.",
            oPaginate: {
                sFirst: "Inizio",
                sPrevious: "Precedente",
                sNext: "Successivo",
                sLast: "Fine",
            },
            oAria: {
                sSortAscending:
                    ": attiva per ordinare la colonna in ordine crescente",
                sSortDescending:
                    ": attiva per ordinare la colonna in ordine decrescente",
            },
        },
        fnDrawCallback: function (oSettings) {
            if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                $(oSettings.nTableWrapper).find(".dataTables_paginate").hide();
            }
        },
    });
});
