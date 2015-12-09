$(document).ready(function (){
    $('select[select2="true"]').each(function () {
        var table = $(this).attr('table');
        var contain = $(this).attr('contain');
        var colName = $(this).attr('colName');
        
        $(this).select2({
            allowClear: true,
            placeholder: '',
            ajax: {
            url: "/lightstrap/ajax/select2adminquery/" + table + "/" + colName + "/" + contain,
            dataType: 'json',
            quietMillis: 250,
            data: function (params) {
              return {
                q: params.term, // search term
                page: params.page || 1 // pagination
              };
            },
            processResults: function (data, params) {
              params.page = params.page || 1;
              var myResults = [];
              $.each(data.results, function (index, item) {
                  myResults.push({
                      'id': item.id,
                      'text': item[colName]
                  });
              });
              return {
                results: myResults,
                pagination: {
                  more: data.pagination.more
                 },
              
              };
            },
            cache: true
            }
        });
    });
});
