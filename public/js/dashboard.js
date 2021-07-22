$(document).ready(function(){

  if(module == "home"){
        $('#table-blood-availability').DataTable({
            "paging": false
            ,scrollX: true
            ,processing: true
            ,ajax: 'ajax/getBloodAvailableToday'
            ,columns:[
                      { "data":"si_no"},
                      { "data":"A+"},
                      { "data":"A-"},
                      { "data":"B+"},
                      { "data":"B-"},
                      { "data":"AB+"},
                      { "data":"AB-"},
                      { "data":"O+"},
                      { "data":"O-"},
                      { "data":"total"},
                      { "data":"collection_type"}
                    ]

        });
        var CurrentDate = new Date();
        CurrentDate.setMonth(CurrentDate.getMonth() + 12);
        var inputDate = CurrentDate.getFullYear() + "-" + CurrentDate.getMonth() + "-" + CurrentDate.getDate();
        
        $.get("getAggregates")
            .done(function(result) {
                $('.dashboard-reserved-widget').html(result.reserved);
                $('.dashboard-released-widget').html(result.released);
                $('.dashboard-daily-reserved-widget').html(result.dailyReserved);
                $('.dashboard-patients-widget').html(result.patients);
        });
    }
});