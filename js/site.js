
$(".chosen-select").chosen({
    
});

/*
Add a Bonus
*/
var bonus_no = 0;

function AddBonus(){
    
    $.ajax({
        type: "POST",
        url: 'add.php',
        data: {
                'functionname': 'AddBonus',
                'data0': 0,
                'data1': ++bonus_no
            },

        success: function (obj, textstatus) {
            console.log(textstatus);

            var elm = $('#bonuses');

            if(textstatus = 'success'){
                elm.append(obj);
                //chosen must be called again to apply it to the dropdown menu.
                $(".chosen-select").chosen();
            }
            else{
                elm.append("<p class='alert alert-danger'>An error occured</p>");
            }
        }
    });
}

/*
Add a Smear
*/
var smear_no = 0;

function AddSmear(){
    
    $.ajax({
        type: "POST",
        url: 'add.php',
        data: {
                'functionname': 'AddSmear',
                'data0': 0,
                'data1': ++smear_no
            },

        success: function (obj, textstatus) {
            console.log(textstatus);

            var elm = $('#smears');

            if(textstatus = 'success'){
                elm.append(obj);
                $(".chosen-select").chosen();
            }
            else{
                elm.append("<p class='alert alert-danger'>An error occured</p>");
            }
        }
    });
}
