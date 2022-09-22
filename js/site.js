/*
JS for add.php------------------------------------------------------------
*/
$(".chosen-select").chosen({
    
});

/*
Add a Bonus
*/
var bonus_nos = [];

function AddBonus(position_no){

    //Try getting the number by counting the number of Bonus Tags under this Position currently
    bonus_nos[position_no] = $(".bonus"+ position_no).length - 1;
    
    $.ajax({
        type: "POST",
        url: 'utils.php',
        data: {
                'functionname': 'AddBonus',
                'data0': position_no,
                'data1': ++bonus_nos[position_no]
            },

        success: function (obj, textstatus) {
            console.log(textstatus);

            var elm = $('#bonuses'+position_no+'');

            if(textstatus == 'success'){
                console.log(obj);
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
var smear_nos = [];

function AddSmear(position_no){

    //Try getting the number by counting the number of Bonus Tags under this Position currently
    smear_nos[position_no] = $(".smear"+ position_no).length - 1;
    
    $.ajax({
        type: "POST",
        url: 'utils.php',
        data: {
                'functionname': 'AddSmear',
                'data0': position_no,
                'data1': ++smear_nos[position_no]
            },

        success: function (obj, textstatus) {
            console.log(textstatus);

            var elm = $('#smears'+position_no+'');

            if(textstatus == 'success'){
                elm.append(obj);
                $(".chosen-select").chosen();
            }
            else{
                elm.append("<p class='alert alert-danger'>An error occured</p>");
            }
        }
    });
}


/*
Call the add position function from PHP
*/
function AddPosition(position_no){

    $.ajax({
        type: "POST",
        url: 'utils.php',
        data: {
                'functionname': 'AddPosition',
                'data0': position_no
            },

        success: function (obj, textstatus) {
            console.log(textstatus);

            var elm = $('#positions');

            var success = textstatus == 'success'

            if(success){
                elm.append(obj);
                $(".chosen-select").chosen();
                console.log(obj);
            }
            else{
                elm.append("<p class='alert alert-danger'>An error occured</p>");
            }

            return success;
        }
    });
}

/*
Check if the Question Radio Button is clicked:
*/
$('#question').click(function() {
    if($('#question').is(':checked')) { 

        //Clear these arrays:
        bonus_nos = [];
        smear_nos = [];

        //Reveal the Question Text.
        $('#question_text').removeAttr('hidden');

        //Clear all the elements within the position tag.
        var pos = $('#positions');
        pos.empty();

        //Add 3 Positions:
        AddPosition(0);
        AddPosition(1);
        AddPosition(2);      
        
    }
 });

 /*
 Check if the Position Radio Button is clicked:
 */
 $('#position').click(function() {
    if($('#position').is(':checked')) { 

        //Clear these arrays:
        bonus_nos = [];
        smear_nos = [];

        //Reveal the Question Text.
        $('#question_text').attr('hidden', true);

        //Clear all the elements within the position tag.
        var pos = $('#positions');
        pos.empty();

        //Add 1 Position:
        AddPosition(0);
    }
 });


 /*
 -----------------------------------------album.php JS -------------------------------------------------
 */
/*
Delete a Card permanently
*/
function DeleteCard(uid, card_type){

    if(confirm("Are you sure you want to delete this card?")){

        $.ajax({
            type: "POST",
            url: 'utils.php',
            data: {
                    'functionname': 'DeleteCard',
                    'data0': uid,
                    'data1': card_type
                },

            success: function (obj, textstatus) {
                console.log(textstatus);

                var elm = $('#'+ uid +'');

                var success = textstatus == 'success'

                if(success && obj){
                    //delete the card element.
                    elm.remove();

                    console.log(obj);
                }
                else{
                    elm.append("<p class='alert alert-danger'>An error occured</p>");
                }

                return success;
            }
        });
    }
}

/*
--------------------------------stats.php---------------------------
*/

$('table.bonus').dataTable({
    paging: false,
    //sort by second column by default:
    order: [[1,'desc']]
});

$('table.smear').dataTable({
    paging: false,
    //sort by second column by default:
    order: [[2,'desc']]
});