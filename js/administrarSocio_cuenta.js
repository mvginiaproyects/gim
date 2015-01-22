

$(document).ready(function(){
        var tabla_cuenta = $('#tabla_cuenta').dataTable({
        "bFilter": true,
        "bPaginate": true,
        'bInfo': false,        
        "bSort" : false,
        "bLengthChange": false,
        "sPaginationType": "bootstrap",
        "iDisplayLength": 15,
        "bPlaceHolder": 'Busqueda de registros',
        //"iDisplayStart": 10,
        //"aLengthMenu": [[10, 50, 100, -1], [10, 50, 100, 'All']],
        "oLanguage": {
            "sSearch": "",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
            "sEmptyTable": "No se encontraron registros.",
            "oPaginate": {
              "sNext": "Mas antiguos",
              "sPrevious": "Mas nuevos",
              "sLast": "Ultima",
              "sFirst": "Primera"
            }
        },

        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            data = api.column( 3 ).data();
            debeTotal = data.length ?
                data.reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                } ) :
                0;
 
            // Total over this page
            data = api.column( 3, { page: 'current'} ).data();
            debePageTotal = data.length ?
                data.reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                } ) :
                0;
 
            // Update footer
            $( api.column( 3 ).footer() ).html(
                '$'+debePageTotal +' ($'+ debeTotal +')'
            );
            data = api.column( 4 ).data();
            haberTotal = data.length ?
                data.reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                } ) :
                0;
 
            // Total over this page
            data = api.column( 4, { page: 'current'} ).data();
            haberPageTotal = data.length ?
                data.reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                } ) :
                0;
 
            // Update footer
            $( api.column( 4 ).footer() ).html(
                '$'+haberPageTotal +' ($'+ haberTotal+')'
            );

            $( api.column( 1 ).footer() ).html(
                '$'+ (haberPageTotal-debePageTotal) +' ($'+(haberTotal - debeTotal)+')'
            );

        }
        
    });
    
});

   