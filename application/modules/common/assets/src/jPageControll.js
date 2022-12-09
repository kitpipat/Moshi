//Loading URL Page
$(function () {

    $("ul.get-menu li > a").unbind().click(function () {
        localStorage.GrpBothNumItem = ''; //Remove Local Storage
        var tURL    = $(this).data('mnrname');
        // ฟังก์ชั่น Check Session
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            $.ajax({
                url: tURL,
                type: "POST",
                error: function (jqXHR, textStatus, errorThrown) {
    
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                },
                success: function (tView) {

                    //console.log(tView);
                    $(window).scrollTop(0);
                    $('.odvMainContent').html(tView);

                    // Chk Status Favorite
                    JSxChkStaDisFavorite(tURL);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //Control Navibar
    var nNavibarHeight = $('.navbar').height();
    $('#odvNavibarClearfixed').css('height', nNavibarHeight);

});

$(window).resize(function () {
    var nNavibarHeight = $('.navbar').height();
    $('#odvNavibarClearfixed').css('height', nNavibarHeight);

    JCNxLayoutControll();
});



function JCNxLayoutControll() {
    //Control Height MenuCump
    var nCumpBarHeight = $('.main-menu').height();
    nCumpBarHeight = nCumpBarHeight + 15;
    $('#odvMenuCump').css('height', nCumpBarHeight);
}


function JSxCallPage(ptURL) {

    $.ajax({
        url: ptURL,
        data: {},
        method: "GET"
    }).done(function (tResult) {
        $('.odvMainContent').html(tResult);
        $('.xCNOverlay').hide();
    }).fail(function (aData) {
        alert('xxxxx');
        console.log(aData);
        $('.xCNOverlay').hide();
    });
}

function JSxCallMainPage(ptURL) {
    $.ajax({
        url: ptURL,
        data: {},
        method: "GET"
    }).done(function (tResult) {
        $('.odvMainContent').html(tResult);
    }).fail(function (aData) {
        console.log(aData);
    });
}

function FCNCropper(input, tRatio) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#oImgUpload')
                    .append(
                            '<div class="modal fade" id="oModalCropper" aria-labelledby="modalLabel" role="dialog" tabindex="-1"> <div class="modal-dialog" role="document" style="z-index:2000; margin-top: 60px;"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="modalLabel">Crop Image</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div> <div class="modal-body"> <div> <img id="oImageCropper" style="max-width: 100%;" src="' +
                            e.target.result +
                            '" alt="Picture"> </div> </div> <div class="modal-footer"> <div class="pull-left"> <div class="btn-group"> <button type="button" class="btn btn-outline-primary" onclick="$(\'#oImageCropper\').cropper(\'zoom\', 0.1)" title="Zoom In"> <span class="docs-tooltip"> <span class="fa fa-search-plus"></span> </span> </button> <button type="button" class="btn btn-outline-primary" onclick="$(\'#oImageCropper\').cropper(\'zoom\', -0.1)" title="Zoom Out"> <span class="docs-tooltip"> <span class="fa fa-search-minus"></span> </span> </button> </div> <div class="btn-group"> <button type="button" class="btn btn-outline-primary" onclick="$(\'#oImageCropper\').cropper(\'move\', -10, 0)" title="Move Left"> <span class="docs-tooltip"> <span class="fa fa-arrow-left"></span> </span> </button> <button type="button" class="btn btn-outline-primary" onclick="$(\'#oImageCropper\').cropper(\'move\', 10, 0)" title="Move Right"> <span class="docs-tooltip"> <span class="fa fa-arrow-right"></span> </span> </button> <button type="button" class="btn btn-outline-primary" onclick="$(\'#oImageCropper\').cropper(\'move\', 0, -10)" title="Move Up"> <span class="docs-tooltip"> <span class="fa fa-arrow-up"></span> </span> </button> <button type="button" class="btn btn-outline-primary" onclick="$(\'#oImageCropper\').cropper(\'move\', 0, 10)" title="Move Down"> <span class="docs-tooltip"> <span class="fa fa-arrow-down"></span> </span> </button> </div> </div> <button type="button" class="btn btn-outline-primary pull-right xWBtnCropImage" title="Crop"> <span> Crop </span> </button> </div> </div> </div> </div>');
        };
        reader.readAsDataURL(input.files[0]);
    }
    setTimeout(function () {
        $('#oModalCropper').modal("show");
        var $image = $('#oImageCropper');
        var $button = $('.xWBtnCropImage');
        var cropBoxData;
        var canvasData;
        $('#oModalCropper').on('shown.bs.modal', function () {
            $image.cropper({
                width: 215,
                height: 130,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.7,
                restore: false,
                guides: false,
                highlight: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                aspectRatio: tRatio,
                ready: function () {
                    $image.cropper('setCanvasData', canvasData);
                    $image.cropper('setCropBoxData', cropBoxData);
                }
            });
        }).on('hidden.bs.modal', function () {
            cropBoxData = $image.cropper('getCropBoxData');
            canvasData = $image.cropper('getCanvasData');
            $image.cropper('destroy');
            $('#oModalCropper').remove();
            $('#ofilePhotoAdd').val('');
        });
        $button.on('click', function () {
            var croppedCanvas;
            var roundedCanvas;
            croppedCanvas = $image.cropper('getCroppedCanvas');
            roundedCanvas = croppedCanvas.toDataURL();
            $('#ohdPhotoAdd').val(roundedCanvas);
            $('#oImageThumbnail').attr('src', roundedCanvas);
            $('#oModalCropper').modal("hide");
        });
        $('#oModalCropper').on('hidden.bs.modal', function () {
            $('#oModalCropper').remove();
            $('#ofilePhotoAdd').val('');
        });
    }, 500);
}
function JSxCallPageResult(ptURL, tCss) {
    $('#odvModelData').css({
        'display': 'none'
    });
    $('.xWNameSlider').addClass('xWshow');
    $('#ospSwitchPanelModel').html(
            '<i class="fa fa-chevron-down" aria-hidden="true"></i>');
    $.ajax({
        type: "GET",
        url: ptURL,
        data: {},
        success: function (tResult) {
            $(tCss).html(tResult);
            $('.xCNOverlay').hide();
        },
        error: function (data) {
            console.log(data);
            $('.xCNOverlay').hide();
        }
    });
}


function JCNtMessageError(poError) {
    alert(poError);
}


// Create By Witsarut 13/01/2020
//ChkStatus rDisble 1 and 2
function JSxChkStaDisFavorite(ptStadissable){
    $.ajax({
        type: "POST",
        url: "ChkStafavorite",
        dataType : "json",
        cache: false,
        data: {
            tStadissable  :   ptStadissable
        },
        success: function (tResult){
            // console.log(tResult)
            if(tResult.rDisable == 2){
                $('#oimImgFavicon').removeClass('xCNDisabled');
                $('#oimImgFavicon').removeClass('xWImgDisable');
            }else{
                $('#oimImgFavicon').addClass('xCNDisabled');
                $('#oimImgFavicon').addClass('xWImgDisable');
            }
        },
        timeout: 3000,
        error: function (data) {
            console.log(data);
        }
    });
}

      /*
      * Functionality : ปุ่ม กด menu fav
      * Parameters : poEvent = route 
      * Creator : 14/1/2020 nonpaiwch(petch)
      * Last Modified : -
      * Return : -
      * Return Type : -
      */  
function JSxCallmenuFav(poEvent){
    var nStaSession = JCNxFuncChkSessionExpired();
    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        var tURL = $(poEvent).attr('data-menu');
        $.ajax({
            url: tURL,
            type: "POST",
            success: function (tView) {
                $(window).scrollTop(0);
                $('.odvMainContent').html(tView);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxCloseLoading();
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            },
        });
    }else{
        JCNxShowMsgSessionExpired();
    }
}

