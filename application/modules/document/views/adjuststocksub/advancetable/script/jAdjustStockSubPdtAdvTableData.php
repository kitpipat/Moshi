<script>

  $(document).ready(function(){

    //Put Sum HD In Footer
    // $('#othFCXthTotal').text($('#ohdFCXthTotalShow').val());

    JSxShowButtonChoose();

    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    var nlength = $('#odvRGPList').children('tr').length;
    for($i=0; $i < nlength; $i++){
          var tDataCode = $('#otrSpaTwoPdt'+$i).data('seq');
      if(aArrayConvert == null || aArrayConvert == ''){
      }else{
              var aReturnRepeat = findObjectByKey(aArrayConvert[0],'tSeq',tDataCode);
        if(aReturnRepeat == 'Dupilcate'){
          $('#ocbListItem'+$i).prop('checked', true);
        }else{ }
      }
    }

    $('.ocbListItem').click(function(){

        var tSeq = $(this).parent().parent().parent().data('seqno');    //Seq
        var tPdt = $(this).parent().parent().parent().data('pdtcode');  //Pdt
        var tDoc = $(this).parent().parent().parent().data('docno');    //Doc
        var tPun = $(this).parent().parent().parent().data('puncode');  //Pun

        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if(LocalItemData){
            obj = JSON.parse(LocalItemData);
        }
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            obj.push({"tSeq": tSeq, 
                      "tPdt": tPdt, 
                      "tDoc": tDoc, 
                      "tPun": tPun 
                    });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxAdjStkSubPdtTextinModal();
        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'tSeq',tSeq);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"tSeq": tSeq, 
                          "tPdt": tPdt, 
                          "tDoc": tDoc, 
                          "tPun": tPun 
                        });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxAdjStkSubPdtTextinModal();
            }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].tSeq == tSeq){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
                JSxAdjStkSubPdtTextinModal();
            }
        }
        JSxShowButtonChoose();
    })
  });

    //Functionality: Event Edit Pdt Table
    //Parameters: Event Proporty
    //Creator: 04/04/2019 Krit(Copter)
    //Return:  Status Edit
    function JSnEditDTRow(event) {
        var tTypeButton = $(".xCNTextDetail2.xWPdtItem > td > lable > img").not("[title='Remove']");
        for (var nI = 0; nI < tTypeButton.length; nI++) {
            if ($(tTypeButton.get(nI)).attr("title") == "Edit") {
                $(tTypeButton.get(nI)).addClass("xCNDisabled");
                $(tTypeButton.get(nI)).attr("onclick", "");
                $(tTypeButton.get(nI)).unbind("click");
            }
        }
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var tEditSeqNo = $(event)
                    .parents()
                    .eq(2)
                    .attr("data-seqno");

            $(".xWShowInLine" + tEditSeqNo).addClass("xCNHide");
            $(".xWEditInLine" + tEditSeqNo).removeClass("xCNHide");

            $(event)
                    .parents()
                    .eq(2)
                    .find(".xWPdtOlaShowQty")
                    .addClass("xCNHide");
            $(event)
                    .parents()
                    .eq(2)
                    .find(".xWPdtDivSetQty")
                    .removeClass("xCNHide");
            $(event)
                    .parents()
                    .eq(2)
                    .find(".xWPdtDivSetQty")
                    .find(".xWPdtSetInputQty")
                    .focus();

            $(event)
                    .parent()
                    .empty()
                    .append(
                            $("<img>")
                            .attr("class", "xCNIconTable")
                            .attr("title", "Save")
                            .attr(
                                    "src",
                                    tBaseURL +
                                    "/application/modules/common/assets/images/icons/save.png"
                                    )
                            .click(function () {
                                JSnSaveDTEdit(this);
                            })
                            );
        } else {
            JCNxShowMsgSessionExpired();
        }
    }
    
    /**
     * Functionality: Save Pdt And Calculate Field
     * Parameters: Event Proporty
     * Creator: 22/05/2019 Piya  
     * Return:  Cpntroll input And Call Function Edit
     * Return Type: number
     */
    function JSnSaveDTEdit(event) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            var nPdtValQty = $(event)
                    .parents()
                    .eq(2)
                    .find(".xWPdtSetInputQty")
                    .val();
            var tEditSeqNo = $(event)
                    .parents()
                    .eq(2)
                    .attr("data-seqno");
            var aField = [];
            var aValue = [];

            $(".xWValueEditInLine" + tEditSeqNo).each(function (index) {
                tValue = $(this).val();
                tField = $(this).attr("data-field");
                $(".xWShowValue" + tField + tEditSeqNo).text(tValue);
                aField.push(tField);
                aValue.push(tValue);
            });

            FSvAdjStkSubEditPdtIntoTableDT(tEditSeqNo, aField, aValue);

            $(".xWShowInLine" + tEditSeqNo).removeClass("xCNHide");
            $(".xWEditInLine" + tEditSeqNo).addClass("xCNHide");

            $(event)
                    .parent()
                    .empty()
                    .append(
                            $("<img>")
                            .attr("class", "xCNIconTable")
                            .attr(
                                    "src",
                                    tBaseURL + "application/modules/common/assets/images/icons/edit.png"
                                    )
                            .click(function () {
                                JSnEditDTRow(this);
                            })
                            );
        } else {
            JCNxShowMsgSessionExpired();
        }
    }

</script>









