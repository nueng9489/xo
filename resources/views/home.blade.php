@extends('layouts.app')
@section('head')
<script type="text/javascript">  
    var o = [], x = [];
    var swPlayer = 'x';
    var bot = false;
    var addNumber = 0;
    var winTemplate = [];

    $('document').ready(function(){
        $("#turnplyer").html(swPlayer);

        $("#submitTable").on('click',function(){
            clearVariable();
            checkBot();
            createGame();
            startPlay();

            return false;
        });

        $("#reset").on('click',function(){
            clearVariable();
            checkBot();
            createGame();
            startPlay();
        });
    });

    function clearVariable(){
        o = [], x = [];
        swPlayer = 'x';
        bot = false;
        addNumber = 0;
        winTemplate = [];
    }

    function createGame(){
        let tableNum = $('#tableNumber').val();
        let tableView = generateTable(tableNum);
        addNumber = tableNum;
        $("#turnplyer").html(swPlayer);
        if(tableNum<3){
            $("#tableResult").html("Please number more then 2");
            return;
        }
        $("#tableResult").html(tableView);          
        winTemplate = generateWinTemplate(tableNum);
    }

    function startPlay(){
        $("#tableResult .box").on('click',function(){
            let chkField = $(this).html();
            let getVal = $(this).data('name');
            let checkWin = false;
            if(chkField!=''){
                return;
            }else{
                $(this).html(swPlayer);
                switch(swPlayer){
                    case 'x':
                        swPlayer = 'o';
                        x.push(getVal);             
                        checkWin = checkTemplates(winTemplate, x);
                        if(checkWin){
                            winner('x');
                            submitHistory('x');
                            return;
                        }
                        break;
                    case 'o':
                        swPlayer = 'x';
                        o.push(getVal);
                        checkWin = checkTemplates(winTemplate, o);
                        if(checkWin){
                            winner('o');
                            submitHistory('o');
                            return;
                        }
                        break;
                }
            }

            let emptyEle=0;
            $('#tableResult .box').each(function(i, obj) {
                let chk = $(this).html();
                if(chk==''){
                    emptyEle++;
                }
            });
            if(emptyEle==0){
                winner('equal');
                submitHistory('equal');
                return;
            }

            if(bot && swPlayer=='o'){
                let loop = addNumber*addNumber;
                for(let i=1; i<=loop; i++){
                    let rendomClick = randomBotClick();
                    let chkEmpty = $("#tableResult .box"+rendomClick).html();
                    if(chkEmpty==''){
                        sleep(500).then(() => {
                            $("#tableResult .box"+rendomClick).trigger('click');
                        })
                        break;
                    }
                }
            }
            $("#turnplyer").html(swPlayer);
        });
    }

    const submitHistory = (who) =>{
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        let sendData = {number:addNumber, winner:who, action:{o:o, x:x}}
        var formData = {
            'log' : JSON.stringify(sendData),
        };
        var state = 'add';
        var type = "POST";
        var ajaxurl = 'history';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    const checkBot = () =>{
        if($('#allowbot').is(':checked')){
            bot = true;
        }else{
            bot = false;
        }
    }
    const sleep = ms => new Promise(res => setTimeout(res, ms));

    const randomBotClick = () =>{
        let col = Math.floor(Math.random() * addNumber) + 1;
        let row = Math.floor(Math.random() * addNumber) + 1;
        return ".name-"+col+"-"+row;
    }

    const winner = (who) =>{
        let txt = "Player&nbsp;<span class='text-uppercase fw-bold'>"+who+"</span>&nbsp;is winner";
        if(who=='equal'){
            txt = "<span class='text-uppercase fw-bold'>"+who+"</span>";
        }
        
        $("#tableResult").append("<div class='overlay'>"+txt+"</div>");
    }

    const checkTemplates = (winTemplate, p = []) =>{
        if(p.length==0) return false;
        let arrCompare;
        for(let i = 0; i<winTemplate.length; i++){
            arrCompare = winTemplate[i];
            let difference = arrCompare.filter(x => !p.includes(x));
            if(difference.length==0){
                return true;
            }
        }
        return false;
    }

    const generateTable = (number) =>{
        let htmlCol = "<div id='tableView'>";
        for(let row = 1; row <= number; row++) {
            htmlCol += "<div class='row'>";
            for(let col = 1; col <= number; col++) {
                htmlCol += "<div class='box col-auto name-"+row+"-"+col+"' data-name='"+row+"-"+col+"'></div>";
            }
            htmlCol += "</div>";
        }
        htmlCol += "</div>";
        return htmlCol;
    }

    
    const generateWinTemplate = (number) =>{
        const winRow = [];
        const winCol = [];
        const winObliqueLeft = [];
        const winObliqueRight = [];

        for(let row = 1; row <= number; row++) {
            let colArr = [];
            for(let col = 1; col <= number; col++) {
                colArr[col-1] = row+'-'+col;
            }
            winRow[row-1] = colArr;
        }

        for(let row = 1; row <= number; row++) {
            let rowArr = [];
            for(let col = 1; col <= number; col++) {
                rowArr[col-1] = col+'-'+row;
            }
            winCol[row-1] = rowArr;
        }

        let arrLeft = [];
        for(let row = 1; row <= number; row++) {
            let point = '';
            for(let col = row; col <= row; col++) {
                point = col+'-'+row;
            }
            arrLeft[row-1] = point;
        }
        winObliqueLeft[0] = arrLeft;

        let arrRight = [];
        let checkCol = parseInt(number);
        for(let row = 1; row <= number; row++) {
            let point2 = '';
            for(let col = 1; col <= number; col++) {
                if((checkCol)==col){
                    point2 = row+'-'+col;
                }
            }
            checkCol--;
            arrRight[row-1] = point2;
        }
        winObliqueRight[0] = arrRight;

        const temp = winRow.concat(winCol, winObliqueLeft, winObliqueRight);
        return temp;
    }
    </script>
    <style>
        #tableResult{
            position: relative;
        }
        .overlay{
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            text-align: center;
            font-size: 30px;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .box{
            width: 50px;
            height: 50px;
            border: 2px solid #000;
            margin: -1px;
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            text-transform: uppercase;
        }
        #turnplyer{
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase
        }
    </style>
@endsection
@section('content')
                <div class="row g-4 justify-content-center">
                    <div class="row">
                        <label for="tableNumber" class="col-sm-2 col-form-label">Add number</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="tableNumber">
                        </div>
                        <div class="col-sm-2">
                          <button type="submit" id="submitTable" class="btn btn-primary mb-3">Start</button>
                        </div>
                    </div>
                    <div class="mb-3 row d-flex justify-content-center">
                        <div class="form-check d-flex justify-content-center">
                            <input class="form-check-input" type="checkbox" value="1" checked id="allowbot">&nbsp;
                            <label class="form-check-label" for="allowbot">
                              Play with bot
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center text-xl">Turn player: &nbsp;<span id="turnplyer"></span></div>
                <div id="tableResult" class="d-flex justify-content-center p-15">

                </div>
                <div class="d-flex mt-3 justify-content-center">
                    <button id="reset" class="btn btn-warning">Reset Game</button>
                </div>
@endsection
