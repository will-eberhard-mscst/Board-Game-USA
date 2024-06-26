

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
async function AddPosition(position_no){

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
/**
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


/**
 * Converts the given Card UID to an Image file.
 * http://html2canvas.hertzen.com/configuration/
 * @param {int} uid 
 * @param {int} i The index of the element to return. Enter 0 for the first element.
 * @returns 
 */
async function cardToImage(uid, i){

    var elm = $('#'+ uid +' .card');

    var options ={
        backgroundColor: null //transparent background
        ,scale: 8 //Image scaling/resolution
    }

    var image = await html2canvas(elm[i], options).then(image => {
        return image;
    });

    return image;
}

/**
 * Allows the user to download a single card as an Image:
 * Enter 0 for Front, enter 1 for Back of the card.
 */
async function ImageCard(uid, i){

    var image = await cardToImage(uid, i);

    var type = i == 0 ? "front" : "back";
        
    //Convert the image to a PNG file and download it.
    var a = document.createElement('a');
    // toDataURL defaults to png, so we need to request a jpeg, then convert for file download.
    a.href = image.toDataURL("image/png").replace("image/png", "image/octet-stream");
    a.download = 'card'+ uid + type+'.png';
    a.click();
}


/**
 * Returns a Zip file containing all the cards that the user has searched for.
 * @returns JSZip
 */
async function ZipAll(){

    var zip = new JSZip();

    //Show the Download Progress area:
    $("#download-progress").removeAttr("hidden");

    //Set the Deonimator's value:
    var denom = positions.length + questions.length;
    $("#progress-denominator").text(denom);

    //Get the Numerator:
    var numer = $("#progress-numerator");
    //set as 0 to start:
    numer.text(0);

    //number of cards downloaded:
    var count = 0;

    //Download all the Position cards on screen:
    var len = positions.length;
    for(var i = 0; i < len; i++){

        var uid = positions[i].uid;
        //console.log("card" + uid);

        //Convert the card to an Image:
        var image = await cardToImage(uid, 0);

        //Get the Blob from the Image:
        var blob = await new Promise(resolve => image.toBlob(resolve) );
        //saves the blob as a PNG:
        await zip.file("card"+ uid +".png", blob);

        //Show the number of cards downloaded:
        count++;
        numer.text(count);
    }

    //Download all the Question cards on screen:
    var len = questions.length;
    for(var i = 0; i < len; i++){

        var uid = questions[i].uid;
        //console.log("card" + uid);

        //Zip the front of the card:
        //Convert the card to an Image:
        var image = await cardToImage(uid, 0);

        //Get the Blob from the Image:
        var blob = await new Promise(resolve => image.toBlob(resolve) );
        //saves the blob as a PNG:
        await zip.file("card"+ uid +"-front.png", blob);


        //Zip the back of the card:
        //Convert the card to an Image:
        var image = await cardToImage(uid, 1);

        //Get the Blob from the Image:
        var blob = await new Promise(resolve => image.toBlob(resolve) );
        //saves the blob as a PNG:
        await zip.file("card"+ uid +"-back.png", blob);

        //Show the number of cards downloaded:
        count++;
        numer.text(count);
    }

    return zip;
}

/**
 * Download all the cards that the user has searched for as PNGs
 */
async function DownloadAll(){

    var zip = await ZipAll();

    await zip.generateAsync({type:"blob"}).then(
        function (blob) { // 1) generate the zip file
            saveAs(blob, "cards.zip");// 2) trigger the download
        }, 
        function (err) {
            jQuery("#blob").text(err);
        }
    );
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


