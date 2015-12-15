$(document).ready(function (){
    $('select[select2="true"]').each(function () {
        var table = $(this).attr('table');
        var contain = $(this).attr('contain');
        var colName = $(this).attr('colName');
        var imageSrcCol = $(this).attr('imageSrcCol');
        
        //select2 options
        var select2Options = {
            allowClear: true,
            placeholder: '',
            ajax: {
                url: "/lightstrap/ajax/select2adminquery/" + table + "/" + colName + "/" + contain + "/" + imageSrcCol,
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
                          'text': item[colName],
                          'image': item[imageSrcCol]
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
        };
        
        //if isset imageSrcCol - use templates with images
        if (typeof imageSrcCol != 'undefined') {
            select2Options.templateResult = formatResImg;
            select2Options.templateSelection = formatSelImg;
        }
        
        // init select2 with select2Options object
        $(this).select2(select2Options);
        
        
        
        // templates
        function formatSelImg (item) {
            if (typeof item.image == 'undefined') {
                $.ajax({
                    async:false,
                    dataType: 'json',
                    url: "/lightstrap/ajax/select2getimage/" + contain + "/" + item.id + "/" + imageSrcCol, 
                    success: function(result){
                        item.image = result[0][imageSrcCol];
                    }
                });
            }
            var $item = $(
            '<span><img height="50px" src="/files/' + item.image + '" /> ' + item.text+ '</span>'
            );
            return $item;
        };
        
        function formatResImg (item) {
            var $item = $(
            '<span><img height="50px" src="/files/' + item.image + '" /> ' + item.text+ '</span>'
            );
            return $item;
        };
    });
});