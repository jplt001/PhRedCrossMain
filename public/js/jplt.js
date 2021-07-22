
if(module == "serology"){
    
    !isUserValid() && disableButtons();

    var tblLabResults;
    var tblDonorResults;
    
    tblLabResults = $('#tbl-lab-results').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        order: [[ 0, "asc" ]],
        ajax:{
            url : "serology/getLabResults",
            type: "get",
            dataFilter: function(data){
                return data;
            }
        },
        columnDefs: [
            {
                "targets": [-1],
                "orderable": false
            },{
                "targets": -1,
                "data": null,
                "render" : function (data, type, row) {
                    if(isUserValid()){
                        return '<button data-title="Edit" data-toggle="tooltip" class="btn btn-primary btn-xs" onclick="editFilnalSerologyResult(' + data[data.length - 1] + ')">' + 
                                    '<i class="fa fa-pencil"></i>' + 
                               '</button>';
                    } else {
                        return '';
                    }
                }
            }
        ]
    });

    tblDonorResults = $('#tbl-donor-results').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        order: [[ 9, "asc" ]],
        ajax:{
            url : "serology/getDonorResults",
            type: "get",
            dataFilter: function(data){
                return data;
            }
        },
        columnDefs: [
            {
                "targets": [-1, -2, -3],
                "orderable": false
            },{
                "targets": -1,
                "data": null,
                "render" : function (data, type, row) {
                    var toReturn;
                    if(isUserValid()){
                        toReturn = '<div class="btn-group">';
                        if(data[9] != 0){
                            toReturn += '<button type="button" class="btn btn-primary btn-xs" disabled><i class="fa fa-check"></i> Passed</button>';
                            toReturn += '<button type="button" class="btn btn-danger btn-xs" disabled><i class="fa fa-ban"></i> Failed</button>';
                        } else {
                            toReturn += '<button type="button" class="btn btn-primary btn-xs" onclick="setPassed(' + data[data.length - 1] + ')"><i class="fa fa-check"></i> Passed</button>';
                            toReturn += '<button type="button" class="btn btn-danger btn-xs" onclick="setFailed(' + data[data.length - 1] + ')"><i class="fa fa-ban"></i> Failed</button>';
                        }
                        toReturn += '</div>';
                    } else {
                        toReturn = '';
                    }
                    return toReturn;
                }
            },{
                "targets": -2,
                "data": null,
                "render" : function (data, type, row) {
                    var toReturn;
                    if(isUserValid()){
                        toReturn = '<div class="btn-group">';
                        if(data[9] != 0){
                            toReturn += '<button class="btn btn-danger btn-xs" disabled><i class="fa fa-trash"></i></button>';
                        } else {
                            toReturn += '<button data-title="Delete" class="btn btn-danger btn-xs" onclick="editSerologyResult(' + data[data.length - 1] + ')"><i class="fa fa-trash"></i></button>';
                        }
                        toReturn += '</div>';
                    } else {
                        toReturn = '';
                    }
                 return toReturn;
                }
            },{
                "targets": -3,
                "data": null,
                "render" : function (data, type, row) {
                    var toReturn;
                    if(isUserValid()){
                        toReturn = '<div class="btn-group">';
                        if(data[9] != 0){
                            toReturn += '<button class="btn btn-primary btn-xs" disabled><i class="fa fa-pencil"></i></button>';
                        } else {
                            toReturn += '<button data-title="Edit" class="btn btn-primary btn-xs" onclick="editSerologyResult(' + data[data.length - 1] + ')"><i class="fa fa-pencil"></i></button>';
                        }
                        toReturn += '</div>';
                    } else {
                        toReturn = '';
                    }
                    return toReturn;
                }
            },{
                "targets": -5,
                "data": null,
                "render" : function (data, type, row) {
                    switch(data[9]){
                        case 0:
                            return '<span class="label bg-primary">Pending</span>';
                            break;
                        case 1:
                            return '<span class="label bg-green">Passed</span>';
                            break;
                        case 2:
                            return '<span class="label bg-red">Failed</span>';
                            break;
                        case 3:
                            return '<span class="label bg-gray" style="color: #fff;">Reserved/Released</span>';
                            break;
                        default:
                            return '<span class="label bg-gray" style="color: #fff;">Unknown</span>';
                            break;
                    }
                }
            }
        ]
    });

    function editSerologyResult(id){
        // alert(id);
        // edit-serology1
        // console.log(JSON.stringify(data));
        $('#patient_id').val(id);
        $('#edit-serology1').modal('show');
    }

    function editFilnalSerologyResult(id){
        // alert(id);
        // edit-serology1
        // console.log(JSON.stringify(data));
        $('#patient_id2').val(id);
        $('#edit-serology2').modal('show');
    }

    function deleteSerologyResult(id){

    }


    function setFailed(id){

        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "serology/failed/"+id,
            "method": "GET",
            "headers": {
                "cache-control": "no-cache",
                "postman-token": "c5413060-08db-2f94-fd7b-4de66dca9b39"
            }
        }
        var r = confirm("Are you sure that this Test is Failed ?");
        if(r == true){
            $.ajax(settings).done(function (response) {
                location.reload();
            });
            // alert("");
        }
        
        
    }

    function setPassed(id){
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "serology/passed/"+id,
            "method": "GET",
            "headers": {
                "cache-control": "no-cache",
                "postman-token": "c5413060-08db-2f94-fd7b-4de66dca9b39"
            }
        }

        $.ajax(settings).done(function (response) {
            location.reload();
        });
    }

}